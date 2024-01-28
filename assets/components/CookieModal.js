import React from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {DialogContent} from "@mui/material";



export default function CookieModal({ handleClose, isOpen }) {

    return (
        <Dialog onClose={handleClose} open={isOpen}>
            <DialogTitle>Вход</DialogTitle>
            <DialogContent>
               Принять
            </DialogContent>
        </Dialog>
    );
}
