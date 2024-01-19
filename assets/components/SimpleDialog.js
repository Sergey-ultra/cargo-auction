import React, {useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {DialogContent} from "@mui/material";


import { blue } from '@mui/material/colors';
import {useHttp} from "../hooks/api";


export default function SimpleDialog({ onClose, selectedValue, open }) {
    const [form,setForm] = useState({
        email: '',
        password:''
    })
    const handleClose = () => {
        onClose(selectedValue);
    };

    const changeHandler = event => {
        setForm({...form, [event.target.name]: event.target. value})
    }

    const { request, isLoading }  = useHttp();

    const login = async e => {
        e.preventDefault();
        const data = await request('/sign-in', 'POST', {...form})
    }

    return (
        <Dialog onClose={handleClose} open={open}>
            <DialogTitle>Вход</DialogTitle>
            <DialogContent>
                <form action="" method="post" onSubmit={login}>
                    <div className="form-item">
                        <label htmlFor="username" className="control-label">Email:</label>
                        <input type="text" id="username" name="email" value={form.email} onChange={changeHandler}/>
                    </div>
                    <div className="form-item">
                        <label htmlFor="password" className="control-label">Password:</label>
                        <input type="password" id="password" name="password" value={form.password} onChange={changeHandler}/>
                    </div>


                    <button type="submit" className="button" disabled={isLoading}>Войти</button>

                    <a className="login" href="/register">Регистрация</a>
                </form>
            </DialogContent>
        </Dialog>
    );
}
