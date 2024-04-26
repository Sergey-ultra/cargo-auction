import React, {Fragment, useState} from 'react';
import {Button, Menu, MenuItem} from "@mui/material";
import Cookies from 'js-cookie';
import './menu.scss';


export default function MenuUser({ email }) {
    const [dropAnchorEl, setDropAnchorEl] = useState(null);
    const [notificationAnchorEl, setNotificationAnchorEl] = useState(null);
    const isOpenDrop = Boolean(dropAnchorEl);
    const isOpenNotification = Boolean(notificationAnchorEl);
    const openDrop = (event) => {
        setDropAnchorEl(event.currentTarget);
    };

    const openNotification = (event) => {
        setNotificationAnchorEl(event.currentTarget)
    }

    const closeDrop = () => setDropAnchorEl(null);



    const logout = () => {
        Cookies.remove('jwt')
        closeDrop();
        window.location.reload();
    }

    const closeNotification = () => setNotificationAnchorEl(null);

    return (
        <Fragment>
            <button onClick={openNotification} className="notification__button">
                <svg className="notification-icon"  data-qa="icon" viewBox="0 0 20.08 22" >
                    <path fillRule="evenodd" clipRule="evenodd"
                          d="M10.534 14.546c.727 0 1.072-.364 1.163-.728.336-1.34-2.737-3.042-3.827-3.645l-.231-.13C4.5 8.258 2.705 7.939 2.243 8.728c-.37.633.218 1.68 1.673 2.91-.219.363-.582 1.09-.582 1.454-2.87-1.988-3.87-4.178-3.066-5.68.31-.577.935-.93 1.769-1.065a15.576 15.576 0 0 1 1.661-.415c1.311-.268 1.8-.367 3.142-2.63a4.1 4.1 0 0 1 1.783-1.727 4.173 4.173 0 0 1 2.467-.395A1.544 1.544 0 0 1 12.684.002a1.561 1.561 0 0 1 1.201.682 1.513 1.513 0 0 1-.214 1.939 4.03 4.03 0 0 1 .277 4.787c-1.305 2.263-1.16 2.703-.749 3.957.162.491.364 1.108.536 2.008l-.002.001c.135.557.101 1.066-.128 1.494-.544 1.017-2.072 1.337-3.991 1.003.41-.237.774-.964.92-1.327Zm-3.61 1.363a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"></path>
                </svg>
            </button>
            <Menu
                id="basic-menu"
                anchorEl={notificationAnchorEl}
                open={isOpenNotification}
                onClose={closeNotification}
                MenuListProps={{
                    'aria-labelledby': 'basic-button',
                }}>
                <MenuItem onClick={closeNotification}>
                    <div className="notificationsList__read">
                        <svg width="16" height="16" viewBox="0 0 16 16">
                            <svg strokeWidth="0" data-qa="icon" viewBox="0 0 14 10" width="14" height="10"
                                 preserveAspectRatio="none" x="1" y="3">
                                <path
                                    d="M6.356 12.335 1.603 7.67 0 9.247 6.356 15.5 20 2.077 18.385.5 6.355 12.335Z"></path>
                            </svg>
                        </svg>
                        <span>Все прочитаны</span>
                    </div>
                </MenuItem>
            </Menu>
            <Button
                id="basic-button"
                aria-controls={open ? 'basic-menu' : undefined}
                aria-haspopup="true"
                aria-expanded={open ? 'true' : undefined}
                onClick={openDrop}>
            {email}
            </Button>
            <Menu
                id="basic-menu"
                anchorEl={dropAnchorEl}
                open={isOpenDrop}
                onClose={closeDrop}
                MenuListProps={{
                    'aria-labelledby': 'basic-button',
                }}>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="/profile/edit">Мои настройки</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="/profile/messages">Личные сообщения</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="/profile/company">Моя компания</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="/profile/load-list">Мои объявления</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="#">Мои фильтры</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="#">Мои отзывы</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="#">Мои рассылки</a>
                </MenuItem>
                <MenuItem onClick={closeDrop}>
                    <a className="menu__link" href="#">Баланс</a>
                </MenuItem>
                <MenuItem onClick={logout}>
                    <span className="menu__link">Выйти</span>
                </MenuItem>
            </Menu>
        </Fragment>
    );
}
