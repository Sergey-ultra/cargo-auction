import React, {Fragment, useEffect, useState} from 'react';
import {useForm} from "react-hook-form";
import {Alert, Button, Checkbox} from "@mui/material";
import {useHttp} from "../../../hooks/api";
import EditIcon from "../../../components/icons/EditIcon";
import ExpeditorIcon from "../../../components/icons/ExpeditorIcon";
import "./auth.scss";

export default function Register({ showLogin, setIsRequiredEmailVerification }) {
    const { request, status, error, clearError } = useHttp();
    const {
        reset,
        watch,
        setValue,
        getValues,
        register,
        handleSubmit,
        setError,
        formState: { errors, isSubmitting }
    } = useForm({
        defaultValues: {
            name: '',
            email: '',
            password: '',
            password_confirm: '',
            phone: '',
            role: '',
        }
    });


    const selectedRole = watch('role');

    const ROLES = [
        {value: 'expeditor', title: 'Экспедитор', icon: ExpeditorIcon},
        {value: 'owner', title: 'Грузовладелец', icon: ExpeditorIcon},
        {value: 'carrier', title: 'Перевозчик', icon: ExpeditorIcon}
    ];


    const getRoleNameByValue = () => {
        const role = ROLES.find(role => role.value === selectedRole);

        if (role) {
            return role.title;
        }
        return '';
    }

    function Icon(name) {
        const Specific = name
        return <Specific/>
    }



    useEffect(() => {
        if (error) {
            setError('root', {
                message: error
            });
            clearError();
        }
    },[error])

    const signIn = async formData => {
        const { data } = await request('/api/register', 'POST', {body: {...formData}});

        if (data) {
            if (data.status === 'is_required_email_verification') {
                setIsRequiredEmailVerification(true);
                reset();
            } else if (data.status === 'email_is_already_exists') {
                setError('root', {
                    message: 'Данный email уже занят',
                });
            }
        }
    }


    return (
        <form onSubmit={handleSubmit(signIn)}>
            {!selectedRole
                ? <Fragment>
                    <h3>Выберите роль аккаунта</h3>
                    <div className="tipconts">
                        {ROLES.map((option, key) =>
                            <div className="tipcont" onClick={() => setValue('role', option.value)}>
                                <div>{Icon(option.icon)}</div>
                                <span>{option.title}</span>
                            </div>
                        )}
                    </div>
                </Fragment>
                : <Fragment>
                    <p className="auth__meta control-label">Роль</p>
                    <div className="form-margin">
                        <div className="firm-type" onClick={() => setValue('role', '')}>
                            <span>{getRoleNameByValue()}</span>
                            <EditIcon/>
                        </div>
                    </div>

                    <p className="auth__meta control-label">Моб. телефон</p>
                    <div className="form-margin">
                        <input type="text" name="phone" className="fullWidth" {...register("phone", {
                            required: 'Введите телефон',
                            pattern: {
                                value: /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/,
                                message: 'Телефон не валидный',
                            }
                        })}/>
                        {errors?.phone && <div className="text-error">{errors.phone.message}</div>}
                    </div>

                    <p className="auth__meta control-label">Email</p>
                    <div className="form-margin">
                        <input type="text" name="email" className="fullWidth" {...register("email", {
                            required: 'Введите email',
                            pattern: {
                                value: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/,
                                message: 'Email не валидный',
                            },
                        })}/>
                        {errors?.email && <div className="text-error">{errors.email.message}</div>}
                    </div>


                    <p className="auth__meta control-label">Имя</p>
                    <div className="form-margin">
                        <input type="text" name="name" className="fullWidth" {...register("name", {
                            required: 'Введите имя',
                            minLength: {
                                value: 3,
                                message: 'Минимальное количество символов 3',
                            },
                            maxLength: {
                                value: 255,
                                message: 'Максимальное количество символов 255',
                            },
                        })}/>
                        {errors?.name && <div className="text-error">{errors.name.message}</div>}
                    </div>


                    <p className="auth__meta control-label">Пароль</p>
                    <div className="form-margin">
                        <input type="password" name="password" className="fullWidth" {...register("password", {
                            required: 'Введите пароль',
                            minLength: {
                                value: 8,
                                message: 'Минимальное количество символов 8',
                            },
                            maxLength: {
                                value: 255,
                                message: 'Максимальное количество символов 255',
                            },
                        })}/>
                        {errors?.password && <div className="text-error">{errors.password.message}</div>}
                    </div>


                    <p className="auth__meta control-label">Подтверждение пароля</p>
                    <div className="form-margin">
                        <input type="password" name="password_confirm" className="fullWidth" {...register("password_confirm", {
                            required: 'Введите подтверждение пароля',
                            validate: (value) => {
                                const { password } = getValues();
                                return password === value || "Пароли не совпадают";
                            }
                        })}/>
                        {errors?.password_confirm && <div className="text-error">{errors.password_confirm.message}</div>}
                    </div>


                    <p>
                        <Checkbox defaultChecked />
                        Я заявляю, что я прочитал, и я согласен с Сроки и условия и Политика конфиденциальности.
                    </p>

                    <Button variant="contained" fullWidth type="submit" className="button button-primary"
                            sx={{marginRight: 2}} disabled={isSubmitting}>
                        {isSubmitting ? 'Loading...' : 'Регистрация'}
                    </Button>

                    {errors?.root &&
                        <Alert severity="error" sx={{ marginTop: 3 }}>{errors.root.message}</Alert>
                    }
                </Fragment>
            }
            <p className="auth__hint">
                Уже есть аккаунт?
                <span className="auth__hintText login" onClick={showLogin}>Вход</span>
            </p>
        </form>
    );
}
