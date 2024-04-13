import React, {useContext, useEffect, useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent, MenuItem, Select} from "@mui/material";
import {useHttp} from "../../hooks/api";
import {NotificationContext} from "../../context/notification.context";
import "./auth.scss"


export default function Register({ showLogin }) {
    const [form,setForm] = useState({
        name: '',
        email: '',
        password: '',
        role: '',
    })


    const changeHandler = event => {
        setForm({...form, [event.target.name]: event.target. value})
    }

    const setRole = role => setForm({...form, role });

    const ROLES = [
        {value: 'expeditor', title: 'Экспедитор'},
        {value: 'owner', title: 'Грузовладелец'},
        {value: 'carrier', title: 'Перевозчик'}
    ];

    const {notify} = useContext(NotificationContext)
    const { request, isLoading, error, clearError } = useHttp();

    useEffect(() => {
        if (error) {
            notify(error);
            clearError();
        }
    },[error])

    const register = async e => {
        e.preventDefault();
        const { data } = await request('/api/register', 'POST', {body: {...form}});

        if (data) {

        }
    }


    return (
        <form onSubmit={register}>
            <div className="form-item">
                <input type="text" name="name" value={form.name} onChange={changeHandler}
                       placeholder="Логин"/>
            </div>
            <div className="form-item">
                <input type="text" name="email" value={form.email} onChange={changeHandler}
                       placeholder="Email"/>
            </div>
            <div className="form-item">
                <input type="password" name="password" value={form.password} onChange={changeHandler}
                       placeholder="Password"/>
            </div>
            <div className="form-item">
                <Select
                    size="small"
                    value={form.role}
                    onChange={e => setRole(e.target.value)}
                >
                    {ROLES.map((option, key) =>
                        <MenuItem key={key} value={option.value}
                                  selected={form.role === option.value}>{option.title}</MenuItem>
                    )}
                </Select>
            </div>


            <Button variant="outlined" type="submit" className="button button-primary button-small"
                    sx={{marginRight: 2}} disabled={isLoading}>Регистрация</Button>
            <Button className="login" onClick={showLogin}>У меня есть аккаунт</Button>
        </form>
);
}
