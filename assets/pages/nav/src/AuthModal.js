import React, {useEffect, useState} from 'react';
import {useForm} from "react-hook-form";
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Alert, Button, DialogContent} from "@mui/material";
import {useHttp} from "../../../hooks/api";
import Register from "./Register";
import "./auth.scss"



export default function AuthModal({ onClose, isOpen, showMode}) {
    const { request, error, clearError } = useHttp();
    const {
        getValues,
        register,
        handleSubmit,
        setError,
        formState: { errors, isSubmitting }
    } = useForm({
        defaultValues: {
            username: '',
            password: '',
        }
    });


    const [isRequiredEmailVerification, setIsRequiredEmailVerification] = useState(false);
    const [showModeLocal, setShowModeLocal] = useState('login');
    const showLogin = () => setShowModeLocal('login');
    const showRegister = () => setShowModeLocal('register');

    const closeRequiredEmailVerification = () => {
        onClose();
        setIsRequiredEmailVerification(false);
    }

    useEffect(() => {
        if (error) {
            setError('root', {
                message: error
            });
            clearError();
        }
    },[error]);

    useEffect(() => {
        setShowModeLocal(showMode);
    }, [showMode]);

    const login = async data => {
        const response = await request('/api/sign-in', 'POST', {body: {...data}});

        if (response.token) {
            window.location.reload();
        } else if (response.is_required_email_verification) {
            setIsRequiredEmailVerification(true);
        }
    }

    const resendVerificationEmail = async() => {
        console.log(getValues());
        //await request('/api/send-verification-email', 'POST', { body: { email: form.username}});
    }

    const loginWithService = async(service) => {
        const { url } = await request(`/login/${service}`);
        if (url) {
            window.location.href = url;
        }
    }

    return (
        <Dialog onClose={onClose} open={isOpen}>
            <DialogTitle>{showModeLocal === 'login' ? 'Войдите с помощью' : 'Регистрация'}</DialogTitle>
            <DialogContent>
                {isRequiredEmailVerification
                    ?
                    <div>
                        <div className="flex">
                            <span>Необходимо подтверждение email</span>
                        </div>
                        <div className="flex">
                            <Button className="btn" onClick={resendVerificationEmail}>
                                Повторно выслать подтверждение аккаунта
                            </Button>
                        </div>
                        <div className="flex">
                            <Button className="btn" onClick={closeRequiredEmailVerification}>Понятно</Button>
                        </div>
                    </div>

                    :
                    <div className="auth">
                        {showModeLocal === 'login' &&
                            <div>
                                {errors?.root &&
                                    <Alert severity="error" sx={{ marginBottom: 3 }}>{errors.root.message}</Alert>
                                }
                                <form onSubmit={handleSubmit(login)}>
                                    <p className="auth__meta control-label">Email</p>
                                    <div className="form-margin">
                                        <input type="text" name="username" className="fullWidth" {...register("username", {
                                            required: 'Введите email',
                                            pattern: {
                                                value: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/,
                                                message: 'Email не валидный'
                                            },
                                        })}/>
                                        {errors?.username && <div className="text-error">{errors.username.message}</div>}
                                    </div>


                                    <p className="auth__meta control-label">Password</p>
                                    <div className="form-margin">
                                        <input type="password" name="password" className="fullWidth" {...register("password", {
                                            required: 'Введите пароль',
                                        })}/>
                                        {errors?.password && <div className="text-error">{errors.password.message}</div>}
                                    </div>

                                    <Button variant="contained" fullWidth className="button button-primary"
                                            type="submit"
                                            sx={{marginRight: 2}} disabled={isSubmitting}>
                                        {isSubmitting ? 'Loading...' : 'Войти'}
                                    </Button>
                                </form>

                                <div className="section-hr">
                                    <div className="section-hr__line"></div>
                                    <div className="section-hr__text"> или</div>
                                    <div className="section-hr__line"></div>
                                </div>
                                <div className="auth__choice">
                                    <div className="auth__wrap">
                                        <div className="auth__wrap-el"
                                             onClick={() => loginWithService('google')}>
                                            <div className="icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16.4 13.6a4.4 4.4 0 0 1-3.6 3.14 4.92 4.92 0 0 1-5.6-4.36 4.8 4.8 0 0 1 6.61-4.82.4.4 0 0 0 .51-.17l1.16-2.12a.42.42 0 0 0-.19-.56A8 8 0 0 0 4 12.23 8.08 8.08 0 0 0 11.66 20 8 8 0 0 0 20 12.42v-1.6a.4.4 0 0 0-.4-.4h-7.2a.4.4 0 0 0-.4.4v2.4a.4.4 0 0 0 .4.4h4"
                                                        fill="#EA4335"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div className="auth__wrap-el"
                                             onClick={() => loginWithService('facebook')}>
                                            <div className="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" fillRule="evenodd"
                                                     clipRule="evenodd"
                                                     data-name="Layer 21" imageRendering="optimizeQuality"
                                                     shapeRendering="geometricPrecision"
                                                     textRendering="geometricPrecision"
                                                     viewBox="0 0 32 32">
                                                    <path fill="#fefefe"
                                                          d="M17.09 32H1.77C.79 32 0 31.21 0 30.23V1.77C0 .79.79 0 1.77 0h28.46C31.21 0 32 .79 32 1.77v28.46c0 .98-.79 1.77-1.77 1.77h-8.15V19.61h4.16l.62-4.83h-4.78v-3.09c0-1.39.39-2.35 2.39-2.35h2.56V5.02c-.44-.06-1.96-.19-3.73-.19-3.68 0-6.21 2.25-6.21 6.39v3.56h-4.17v4.83h4.17V32z"/>
                                                </svg>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <p className="auth__hint">
                                    Нет аккаунта?
                                    <span className="auth__hintText login" onClick={showRegister}>Регистрация</span>
                                </p>
                            </div>
                        }
                        {showModeLocal === 'register' && <Register
                            showLogin={showLogin}
                            setIsRequiredEmailVerification={setIsRequiredEmailVerification}
                        />}
                    </div>
                }
            </DialogContent>
        </Dialog>
    );
}
