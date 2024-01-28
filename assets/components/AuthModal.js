import React, {useContext, useEffect, useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent} from "@mui/material";
import {useHttp} from "../hooks/api";
import {NotificationContext} from "../context/notification.context";


export default function AuthModal({ onClose, selectedValue, isOpen }) {
    const [form,setForm] = useState({
        username: '',
        password:''
    })
    const handleClose = () => {
        onClose(selectedValue);
    };

    const changeHandler = event => {
        setForm({...form, [event.target.name]: event.target. value})
    }

    const {notify} = useContext(NotificationContext)
    const { request, isLoading, error, clearError } = useHttp();

    useEffect(() => {
        if (error) {
            notify(error);
            clearError();
        }
    },[error])

    const login = async e => {
        e.preventDefault();

        try {
            const { data } = await request('/sign-in', 'POST', {body: {...form}});

            if (data && data.userId) {
                window.location.reload();
            }
        } catch (e) {
            console.log(e.message)
        }

    }

    return (
        <Dialog onClose={handleClose} open={isOpen}>
            <DialogTitle>Вход</DialogTitle>
            <DialogContent>
                <form action="" method="post" onSubmit={login}>
                    <div className="form-item">
                        <label htmlFor="username" className="control-label">Email:</label>
                        <input type="text" id="username" name="username" value={form.username} onChange={changeHandler}/>
                    </div>
                    <div className="form-item">
                        <label htmlFor="password" className="control-label">Password:</label>
                        <input type="password" id="password" name="password" value={form.password} onChange={changeHandler}/>
                    </div>


                    <Button variant="outlined" type="submit" className="button button-primary button-small" sx={{ marginRight: 2 }}  disabled={isLoading}>Войти</Button>

                    <a className="login" href="/register">Регистрация</a>
                </form>
            </DialogContent>
        </Dialog>
    );
}
