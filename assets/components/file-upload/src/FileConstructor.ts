import { FileOrigin, FileStatus } from './enums';
import {FileStatusType} from "./enums/FileStatus";

function getFileExtension(filename: string): string {
    let ext = (/\.tar\.gz$|\.[^.]+$/).exec(filename);

    if (ext) {
        return ext[0];
    }

    return '';
}

function getFileTitle(filename: string): string {
    return filename.replace(getFileExtension(filename), '');
}

export default class FileConstructor {
    cancelController: AbortController;
    status: FileStatusType;
    origin: string;
    error: string;
    bin: null|File;
    url: string;
    size: number;

    constructor(file: File|FileConstructor, optionsArg = {}) {
        if (file instanceof FileConstructor) {
            // eslint-disable-next-line no-constructor-return
            return file;
        }

        let options = Object.assign({ origin: FileOrigin.REMOTE }, optionsArg);

        this.status = options.origin === FileOrigin.REMOTE ? FileStatus.LOADED : FileStatus.IDLE;

        this.origin = options.origin;

        this.error = null;

        Object.defineProperties(this, {
            name: {
                value: file.name,
                writable: true,
                enumerable: true,
            },
            title: {
                enumerable: true,
                get() {
                    return getFileTitle(this.name);
                },
            },
            extension: {
                enumerable: true,
                get() {
                    return getFileExtension(this.name);
                },
            },
        });

        this.size = file.size || 0;
        this.url = file.url || null;
        this.bin = (file instanceof File) ? file : null;

        this.cancelController = new AbortController();
    }

    setError(error) {
        if (this.status !== FileStatus.ERROR) {
            this.status = FileStatus.ERROR;

            this.error = error;
        }
    }

    setAbort() {
        if (this.status === FileStatus.LOADING) {
            this.cancelController.abort();
            this.status = FileStatus.ABORTED;
        }
    }
}
