import React, {useEffect, useMemo, useState} from "react";
import GreenButton from "../buttons/GreenButton";
import './file.scss';
import FileConstructor from "./src/FileConstructor";
import { FileStatus, FileOrigin, FileError } from './src/enums';
import {useHttp} from "../../hooks/api";
import FileItem from "./src/FileItem";
import '@fortawesome/fontawesome-free/css/all.min.css';

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
                                     }) {
    const { request, isLoading, error, status } = useHttp();
    const unloadedFiles = useMemo(() =>  files.filter(file => file.status === FileStatus.IDLE), [files]);

    useEffect(() => {
        if (unloadedFiles.length) {
            unloadedFiles.forEach(uploadFile);
        }
    }, [files]);

    const isFileLimit = useMemo(() => {
        if (multiple && allowedMaxFiles) {
            return files.length >= allowedMaxFiles;
        }

        return false;
    }, [multiple, allowedMaxFiles, files]);

    const isDisabled = disabled || isFileLimit;

    const saveFile = async (event) => {
        if (!isDisabled) {
            let uploadingFiles = event.target.files || event.dataTransfer.files
            // for (const file of files) {
            //     let reader = new FileReader();
            //     reader.readAsDataURL(file);
            //     reader.onload = e => previewImages.files.push(e.target.result);
            // }

            let newFiles = [];

            if (multiple) {
                newFiles = allowedMaxFiles
                    ? [...uploadingFiles].slice(0, allowedMaxFiles - uploadingFiles.length)
                    : [...uploadingFiles];
            } else {
                newFiles = uploadingFiles[0] ? [uploadingFiles[0]] : [];
            }


            newFiles = newFiles
                .map(file => new FileConstructor(file, {origin: FileOrigin.INPUT}))
                .map(validators);

            setFiles(prev => [...prev, ...newFiles]);
        }
    }

    const validators = (file) => {
        validateSize(file);
        validateExtension(file);

        if (file.status === FileStatus.ERROR) {
            //emitFailed(file);
        }

        return file;
    }

    const validateSize = (file) => {
        if (allowedSize && file.size > allowedSize) {
            file.setError(FileError.SIZE);
        }
    }

    const validateExtension = (file) => {
        if (allowedExtensions && allowedExtensions.length && !allowedExtensions.includes(file.extension)) {
            file.setError(FileError.EXTENSION);
        }
    }

    const setFile = (file) => {
        setFiles(prev => {
            return [...prev.map(item => item.name === file.name ? file : item)];
        });
    }

    const uploadFile = async (file) => {
        let form = new FormData();
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

    const removeFile = file => {
        if (file.status === FileStatus.LOADING) {
            file.setAbort();
            //emitAborted(file);
        }
        setFiles(prev => [...prev.filter(item => item !== file)]);
    }

    return (
        <div>
            <label className="file">
                <input type="file" className="file__input" multiple name="file" onChange={e => saveFile(e, 'load')}/>
                <div>
                    <GreenButton className="file__button">Загрузить</GreenButton>
                </div>
                <div className="file__description">Фото или документы до 10 МБ. Не более 10 файлов</div>
            </label>
            {files.map(file => <FileItem
                key={file.name}
                file={file}
                remove={() => removeFile(file)}/>)
            }
        </div>

    );
}

