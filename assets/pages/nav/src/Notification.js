import React, {Fragment, useContext, useEffect, useState} from "react";
import {Button, IconButton, Snackbar} from "@mui/material";
import {NotificationContext} from "../../../context/notification.context";

export default function Notification() {

    const { message, clearNotification } = useContext(NotificationContext);

    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
           message ? setIsOpen(true) : setIsOpen(false);
    }, [message])


    const vertical = 'top';
    const horizontal = 'right';


    const action = (
        <Fragment>
            <Button color="secondary" size="small" onClick={clearNotification}>
                UNDO
            </Button>
            <IconButton
                size="small"
                aria-label="close"
                color="inherit"
                onClick={clearNotification}
            >
                x
            </IconButton>
        </Fragment>
    );

    return (
        <Snackbar
            anchorOrigin={{ vertical, horizontal }}
            open={isOpen}
            autoHideDuration={6000}
            onClose={clearNotification}
            message={message}
            action={action}/>
    );
}
