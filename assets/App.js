import React, {Fragment, useState} from 'react';
import {Button} from "@mui/material";
import SimpleDialog from "./components/SimpleDialog";





function App() {
    const isAuth = window.authData && window.authData.userId;
    const email = window?.authData?.email;

    const [open, setOpen] = useState(false);
    const [selectedValue, setSelectedValue] = useState([]);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = (value) => {
        setOpen(false);
        setSelectedValue(value);
    };

    if (isAuth) {
        return (
            <div>
                <svg className="notification-icon" width="32" height="32" viewBox="0 0 32 32">
                    <svg stroke-width="0" data-qa="icon" viewBox="0 0 20.08 22" width="20.08" height="22"
                         preserveAspectRatio="none" x="6" y="5">
                        <symbol id="ic_notification" viewBox="0 0 15 16">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M10.534 14.546c.727 0 1.072-.364 1.163-.728.336-1.34-2.737-3.042-3.827-3.645l-.231-.13C4.5 8.258 2.705 7.939 2.243 8.728c-.37.633.218 1.68 1.673 2.91-.219.363-.582 1.09-.582 1.454-2.87-1.988-3.87-4.178-3.066-5.68.31-.577.935-.93 1.769-1.065a15.576 15.576 0 0 1 1.661-.415c1.311-.268 1.8-.367 3.142-2.63a4.1 4.1 0 0 1 1.783-1.727 4.173 4.173 0 0 1 2.467-.395A1.544 1.544 0 0 1 12.684.002a1.561 1.561 0 0 1 1.201.682 1.513 1.513 0 0 1-.214 1.939 4.03 4.03 0 0 1 .277 4.787c-1.305 2.263-1.16 2.703-.749 3.957.162.491.364 1.108.536 2.008l-.002.001c.135.557.101 1.066-.128 1.494-.544 1.017-2.072 1.337-3.991 1.003.41-.237.774-.964.92-1.327Zm-3.61 1.363a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"></path>
                        </symbol>
                    </svg>
                </svg>
                <div id="user-menu" className="dropdown closed">
            <div id="dropdown-btn" className="dropdown-button">{ email }</div>
            <ul id="dropdown-list" className="dropdown-list">
                <li className="dropdown-item">
                    <a href="{{ path('profile') }}">Мои настройки</a>
                </li>
                <li className="dropdown-item">
                    <a href="{{ path('profile.messages') }}">Личные сообщения</a>
                </li>
                <li className="dropdown-item">
                    <a href="{{ path('profile.company') }}">Моя компания</a>
                </li>
                <li className="dropdown-item">
                    <a href="{{ path('profile.my-cargos') }}">Мои объявления</a>
                </li>
                <li className="dropdown-item">
                    <a href="#">Мои фильтры</a>
                </li>
                <li className="dropdown-item">
                    <a href="#">Мои отзывы</a>
                </li>
                <li className="dropdown-item">
                    <a href="#">Мои рассылки</a>
                </li>
                <li className="dropdown-item">
                    <a href="#">Баланс</a>
                </li>
                <li className="dropdown-item">
                    <a href="{{ path('app_sign_out') }}">Выйти</a>
                </li>
            </ul>
        </div>
            </div>
    )}


    return (
        <Fragment>
            <Button variant="outlined" className="button button-primary button-small" onClick={handleClickOpen}>
                Вход
            </Button>
            <a className="button button-secondary button-small" href="/register">Регистрация</a>
            <SimpleDialog
                selectedValue={selectedValue}
                open={open}
                onClose={handleClose}
            />
        </Fragment>
    );
}

export default App;

