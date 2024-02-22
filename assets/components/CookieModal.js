import React from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent} from "@mui/material";


export default function CookieModal({ handleClose, isOpen }) {

    return (
        <Dialog onClose={handleClose} open={isOpen}>
            <DialogTitle>Мы используем cookie</DialogTitle>
            <DialogContent>
                <span>Уведомляем вас, что мы используем файлы cookie. Продолжая пользование сайтом, Пользователь соглашается на использование Сайтом файлов cookie.</span>
                <Button variant="contained" className="button button-primary button-small" onClick={handleClose}
                        sx={{marginLeft: 'auto', marginTop: 2}}>OK</Button>
            </DialogContent>
        </Dialog>
    );
}
