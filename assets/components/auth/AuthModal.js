import React, {useContext, useEffect, useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent} from "@mui/material";
import {useHttp} from "../../hooks/api";
import {NotificationContext} from "../../context/notification.context";
import "./auth.scss"
import Register from "./Register";


export default function AuthModal({ onClose, isOpen, showMode, showLogin, showRegister}) {
    const [form,setForm] = useState({
        username: '',
        password:''
    });

    const [isRequiredEmailVerification, setIsRequiredEmailVerification] = useState(false);


    const [authHeader, setAuthHeader] = useState('Войдите с помощью');

    useEffect(() => {
        switch (showMode) {
            case 'login':
                setAuthHeader('Войдите с помощью');
                break;
            case 'registration':
                setAuthHeader('Регистрация');
                break;
            case 'recover':
                setAuthHeader('Восстановить пароль');
                break;
        }
    },[showMode])

    // let authHeader = () => {
    //     switch (showMode) {
    //         case 'login':
    //             return 'Войдите с помощью';
    //         case 'registration':
    //             return 'Регистрация';
    //         case 'recover':
    //             return 'Восстановить пароль';
    //     }
    // };


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

        const response = await request('/api/sign-in', 'POST', {body: {...form}});

        if (response.token) {
            window.location.reload();
        } else if (response.not_verified) {
            setIsRequiredEmailVerification(true);
        }
    }

    const resendVerificationEmail = async() => {
        await request('/api/send-verification-email', 'POST', { body: { email: form.username}});
    }

    const loginWithService = async(service) => {
        const { url } = await request(`/login/${service}`);
        if (url) {
            window.location.href = url;
        }
    }

    return (
        <Dialog onClose={onClose} open={isOpen}>
            <DialogTitle>{authHeader}</DialogTitle>
            <DialogContent>
                {isRequiredEmailVerification
                    ?
                    <div>
                        <div className="form-group">
                            <span>На ваш email выслано подтверждение аккаунта</span>
                        </div>
                        <div className="form-group">
                            <Button className="btn" onClick={resendVerificationEmail}>
                                Повторно выслать подтверждение аккаунта
                            </Button>
                        </div>
                        <div className="form-group">
                            <Button className="btn" onClick={onClose}>Понятно</Button>
                        </div>
                    </div>

                    :
                    <div className="auth">
                        {showMode === 'login' &&
                            <div>
                                <div className="auth__choice">
                                    <div className="auth__wrap">
                                        <div className="auth__wrap-el auth__with-g"
                                             onClick={() => loginWithService('google')}>
                                            <div className="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" fillRule="evenodd"
                                                     clipRule="evenodd" data-name="Layer 21"
                                                     imageRendering="optimizeQuality"
                                                     shapeRendering="geometricPrecision"
                                                     textRendering="geometricPrecision"
                                                     viewBox="0 0 31 32">
                                                    <path fill="#fbbc05"
                                                          d="M6.4 16c0-1.04.17-2.04.48-2.97l-5.4-4.12C.43 11.05-.16 13.45-.16 16s.59 4.95 1.64 7.08l5.39-4.12c-.3-.93-.47-1.93-.47-2.96z"/>
                                                    <path fill="#ea4335"
                                                          d="M15.86 6.55c2.26 0 4.3.8 5.9 2.11L26.42 4c-2.84-2.47-6.48-4-10.56-4C9.53 0 4.09 3.62 1.48 8.91l5.4 4.12c1.24-3.77 4.78-6.48 8.98-6.48z"/>
                                                    <path fill="#34a853"
                                                          d="M15.86 25.45c-4.2 0-7.74-2.71-8.98-6.48l-5.4 4.12C4.09 28.38 9.53 32 15.86 32c3.91 0 7.64-1.39 10.44-3.99l-5.12-3.95c-1.44.91-3.26 1.39-5.32 1.39z"/>
                                                    <path fill="#4285f4"
                                                          d="M31.16 16c0-.94-.15-1.96-.37-2.91H15.86v6.18h8.6c-.43 2.11-1.6 3.73-3.28 4.79l5.12 3.95c2.95-2.73 4.86-6.8 4.86-12.01z"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <div className="auth__wrap-el auth__with-fb"
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
                                <div className="section-hr">
                                    <div className="section-hr__line"></div>
                                    <div className="section-hr__text"> или</div>
                                    <div className="section-hr__line"></div>
                                </div>
                                <form onSubmit={login}>
                                    <div className="form-item">
                                        <input type="text" id="username" name="username" value={form.username}
                                               onChange={changeHandler} placeholder="Email"/>
                                    </div>
                                    <div className="form-item">
                                        <input type="password" id="password" name="password" value={form.password}
                                               onChange={changeHandler} placeholder="Password"/>
                                    </div>


                                    <Button variant="outlined" type="submit"
                                            className="button button-primary button-small"
                                            sx={{marginRight: 2}} disabled={isLoading}>Войти</Button>

                                    <Button className="login" onClick={showRegister}>Регистрация</Button>
                                </form>
                            </div>
                        }
                        {showMode === 'register' && <Register showLogin={showLogin}/>}
                    </div>
                }
            </DialogContent>
        </Dialog>
    );
}
