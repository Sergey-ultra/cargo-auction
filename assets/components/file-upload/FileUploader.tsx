import React, {ChangeEvent, FC, useEffect, useMemo, useState} from "react";
import GreenButton from "../buttons/GreenButton";
import './file.scss';
import FileConstructor from "./src/FileConstructor";
import { FileStatus, FileOrigin, FileError } from './src/enums';
import {useHttp} from "../../hooks/api";
import FileItem from "./src/FileItem";
import '@fortawesome/fontawesome-free/css/all.min.css';

interface FileUploadProps {
    files: FileConstructor[],
    setFiles: (files: FileConstructor[]) => void,
    entity: string,
    allowedExtensions: string[],
    allowedMaxFiles: number,
    multiple: boolean,
    allowedSize: number,
    disabled: boolean,
    emitAborted: () => void,
}

export default function FileUploader({
                                         files,
                                         setFiles,
                                         entity,
                                         allowedExtensions,
                                         allowedMaxFiles,
                                         multiple,
                                         allowedSize = 0,
                                         disabled = false,
                                         emitAborted = () => {}
                                     }: FileUploadProps) {
    const { request, isLoading, error, status } = useHttp();
    const unloadedFiles: FileConstructor[] = useMemo(
        (): FileConstructor[] => files.filter((file: FileConstructor): boolean => file.status === FileStatus.IDLE),
        [files]
    );

    useEffect(() => {
        if (unloadedFiles.length) {
            unloadedFiles.forEach(uploadFile);
        }
    }, [files]);

    const isFileLimit: boolean = useMemo((): boolean => {
        if (multiple && allowedMaxFiles) {
            return files.length >= allowedMaxFiles;
        }

        return false;
    }, [multiple, allowedMaxFiles, files]);

    const isDisabled: boolean = disabled || isFileLimit;

    const saveFile = async (event: ChangeEvent<HTMLInputElement>): Promise<void> => {
        if (!isDisabled) {
            const uploadingFiles: FileList|null = event.target.files;
                //|| event.dataTransfer.files
            // for (const file of files) {
            //     let reader = new FileReader();
            //     reader.readAsDataURL(file);
            //     reader.onload = e => previewImages.files.push(e.target.result);
            // }

            if (uploadingFiles === null) {
                return;
            }

            let newFiles: File[] = [];

            if (multiple) {
                newFiles = allowedMaxFiles
                    ? [...uploadingFiles].slice(0, allowedMaxFiles - uploadingFiles.length)
                    : [...uploadingFiles];
            } else {
                newFiles = uploadingFiles[0] ? [uploadingFiles[0]] : [];
            }


            let newFileConstructorList: FileConstructor[] = newFiles
                .map((file: File) => new FileConstructor(file, {origin: FileOrigin.INPUT}))
                .map(validators);

            setFiles((prev: FileConstructor[]): FileConstructor[] => [...prev, ...newFileConstructorList]);
        }
    }

    const validators = (file: FileConstructor): FileConstructor => {
        validateSize(file);
        validateExtension(file);

        if (file.status === FileStatus.ERROR) {
            //emitFailed(file);
        }

        return file;
    }

    const validateSize = (file: FileConstructor) => {
        if (allowedSize && file.size > allowedSize) {
            file.setError(FileError.SIZE);
        }
    }

    const validateExtension = (file: FileConstructor) => {
        if (allowedExtensions && allowedExtensions.length && !allowedExtensions.includes(file.extension)) {
            file.setError(FileError.EXTENSION);
        }
    }

    const setFile = (file: FileConstructor): void => {
        setFiles((prev: FileConstructor[]): FileConstructor[] => {
            return [...prev.map((item: FileConstructor): FileConstructor => item.name === file.name ? file : item)];
        });
    }

    const uploadFile = async (file: FileConstructor): Promise<void> => {
        let form: FormData = new FormData();
        form.append('entity', entity);
        form.append('file', file.bin);

        file.status = FileStatus.LOADING;
        setFile(file);

        try {
            const response = await request('/api/file', 'POST', {form, signal: file.cancelController.signal});

            if (response.success) {
                const { data } = response;

                file.url = data.url;
                file.status = FileStatus.LOADED;
                setFile(file);
            } else {
                file.setError(FileError.SERVER);
                setFile(file);
            }
        } catch (e) {
            file.setError(FileError.INTERNAL);
            setFile(file);
            //emitFailed(file);
        }
    }

    const removeFile = (file: FileConstructor): void => {
        if (file.status === FileStatus.LOADING) {
            file.setAbort();
            //emitAborted(file);
        }
        setFiles((prev: FileConstructor[]): FileConstructor[] => [...prev.filter((item: FileConstructor): boolean => item !== file)]);
    }

    return (
        <div>
            <label className="file">
                <input type="file" className="file__input" multiple name="file" onChange={(e: ChangeEvent<HTMLInputElement>): Promise<void> => saveFile(e, 'load')}/>
                <div>
                    <GreenButton className="file__button">Загрузить</GreenButton>
                </div>
                <div className="file__description">Фото или документы до 10 МБ. Не более 10 файлов</div>
            </label>
            {files.map((file: FileConstructor, index: number) => <FileItem
                key={index}
                file={file}
                remove={(): void => removeFile(file)}/>
            )}
        </div>

    );
}

