import React, {useEffect, useMemo} from "react";
import { getFilenameFaIcon } from '../../../functions/get-filename-fa-icon';
import "./file-item.scss";
import {Box, LinearProgress, Tooltip} from "@mui/material";
import {FileStatus} from "./enums";
import FileConstructor from "./FileConstructor";

interface FileItemProps {
    file: FileConstructor,
    remove: () => void
}

export default function FileItem({ file, remove }: FileItemProps) {
    const fileIconClass = `fa fa-file-${getFilenameFaIcon(file.extension)}`;
    // const isLoaded = useMemo(() => file.status === FileStatus.LOADED, [file]);
    // const isLoading = file.status === FileStatus.LOADING;
    //
    //useEffect(() => console.log('file_status', file.name, file.status), [file]);

    return (
        <div className="uploaded">
            <div className="uploaded__inner">
                <div className="uploaded__extension-icon">
                    <i className={fileIconClass}></i>
                </div>
                {file.status === FileStatus.LOADED
                    ? <a href={file.url} target="_blank" className="b-href uploaded__name">
                        {file.name}
                    </a>
                    : <span
                        className={`uploaded__name${file.status === FileStatus.LOADING ? ' uploaded__name--loading' : ''}`}>{file.name}</span>
                }

                <Tooltip title="Удалить" placement="top">
                    <div className="uploaded__actions">
                        <button className="uploaded__button" type="button" onClick={remove}/>
                    </div>
                </Tooltip>
            </div>

            {file.status === FileStatus.LOADING &&
                <Box sx={{width: '100%'}}>
                    <LinearProgress/>
                </Box>
            }
        </div>
    );
}

