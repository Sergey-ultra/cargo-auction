import React, {Fragment, useContext, useEffect, useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, Checkbox, DialogContent, MenuItem, Select} from "@mui/material";
import {useHttp} from "../../hooks/api";
import {NotificationContext} from "../../context/notification.context";
import "./auth.scss"
import EditIcon from "../common/icons/EditIcon";
import ExpeditorIcon from "../common/icons/ExpeditorIcon";


export default function Register({ showLogin, setIsRequiredEmailVerification }) {
    const [form,setForm] = useState({
        name: '',
        email: '',
        password: '',
        password_confirm: '',
        phone: '',
        role: '',
    })


    const changeHandler = event => {
        setForm({...form, [event.target.name]: event.target. value})
    }

    const setRole = role => setForm({...form, role });

    const ROLES = [
        {value: 'expeditor', title: 'Экспедитор', icon: ExpeditorIcon},
        {value: 'owner', title: 'Грузовладелец', icon: ExpeditorIcon},
        {value: 'carrier', title: 'Перевозчик', icon: ExpeditorIcon}
    ];

    const getRoleNameByValue = () => {
        const role = ROLES.find(role => role.value === form.role);

        if (role) {
            return role.title;
        }
        return '';
    }

    function Icon(name) {
        const Specific = name
        return <Specific/>
    }

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

        if (data && data.status === 'is_required_email_verification') {
            setIsRequiredEmailVerification(true);
        }
    }


    return (
        <form onSubmit={register}>
            {!form.role
                ? <Fragment>
                    <h3>Выберите роль аккаунта</h3>
                    <div className="tipconts">
                        {ROLES.map((option, key) =>
                            <div className="tipcont" onClick={() => setRole(option.value)}>
                                <div>{Icon(option.icon)}</div>
                                <span>{option.title}</span>
                            </div>
                        )}
                    </div>
                </Fragment>
                : <Fragment>
                    <p className="auth__meta control-label">Роль</p>
                    <div className="form-item">
                        <div className="firm-type" onClick={() => setRole('')}>
                            <span>{getRoleNameByValue()}</span>
                            <EditIcon/>
                        </div>
                    </div>

                    <p className="auth__meta control-label">Моб. телефон</p>
                    <div className="form-item">
                        <input type="text" name="phone" className="fullWidth" value={form.phone}
                               onChange={changeHandler}/>
                    </div>
                    <p className="auth__meta control-label">Email</p>
                    <div className="form-item">
                        <input type="text" name="email" className="fullWidth" value={form.email}
                               onChange={changeHandler}/>
                    </div>
                    <p className="auth__meta control-label">Логин</p>
                    <div className="form-item">
                        <input type="text" name="name" className="fullWidth" value={form.name}
                               onChange={changeHandler}/>
                    </div>
                    <p className="auth__meta control-label">Password</p>
                    <div className="form-item">
                        <input type="password" name="password" className="fullWidth" value={form.password}
                               onChange={changeHandler}/>
                    </div>
                    <p className="auth__meta control-label">Подтверждение пароля</p>
                    <div className="form-item">
                        <input type="password" name="password_confirm" className="fullWidth"
                               value={form.password_confirm}
                               onChange={changeHandler}/>
                    </div>

                    <p>
                        <Checkbox defaultChecked />
                        Я заявляю, что я прочитал, и я согласен с Сроки и условия и Политика конфиденциальности.
                    </p>

                    <Button variant="contained" fullWidth type="submit" className="button button-primary"
                            sx={{marginRight: 2}} disabled={isLoading}>Регистрация</Button>
                </Fragment>
            }
            <p className="auth__hint">
                Уже есть аккаунт?
                <span className="auth__hintText login" onClick={showLogin}>Регистрация</span>
            </p>
        </form>
    );
}
