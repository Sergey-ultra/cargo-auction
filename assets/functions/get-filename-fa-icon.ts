/**
 * Convert filename or extension to Font Awesome icon
 * @param filename File name or extension
 * @returns Font Awesome icon without `fa-` prefix
 */
export function getFilenameFaIcon(filename: string): string {
    if (!filename || typeof filename !== 'string') {
        return 'alt';
    }

    let parts: string[] = filename.split('.');
    let extension: string = parts[parts.length - 1];

    if (!extension || typeof extension !== 'string') {
        return 'alt';
    }

    switch (extension) {
        case 'rar':
        case 'zip':
            extension = 'archive';
            break;

        case 'mp3':
        case 'wav':
            extension = 'audio';
            break;

        case 'bmp':
        case 'gif':
        case 'jpeg':
        case 'jpg':
        case 'png':
        case 'psd':
        case 'svg':
        case 'tif':
        case 'tiff':
            extension = 'image';
            break;

        case 'pdf':
            extension = 'pdf';
            break;

        case 'doc':
        case 'docx':
        case 'dot':
        case 'odt':
        case 'ott':
        case 'rtf':
        case 'stw':
        case 'sxw':
        case 'txt':
            extension = 'word';
            break;

        case 'odp':
        case 'otp':
        case 'pot':
        case 'potx':
        case 'potm':
        case 'pps':
        case 'ppt':
        case 'pptm':
        case 'pptx':
        case 'sti':
        case 'sxi':
            extension = 'powerpoint';
            break;

        case 'csv':
        case 'ods':
        case 'ots':
        case 'stc':
        case 'sxc':
        case 'xls':
        case 'xlsm':
        case 'xlsx':
        case 'xlt':
        case 'xltx':
        case 'xltm':
        case 'xlw':
            extension = 'excel';
            break;

        case 'avi':
        case 'mp4':
        case 'mpg':
        case 'mkv':
        case 'mov':
            extension = 'video';
            break;

        default:
            extension = 'alt';
    }

    return extension;
}
