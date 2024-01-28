import React, {useState} from 'react';
import RightNav from "./components/RightNav";
import Notification from "./components/Notification";
import {useNotification} from "./hooks/notification";
import {NotificationContext} from "./context/notification.context";
import {useCookie} from "./hooks/cookie";
import CookieModal from "./components/CookieModal";


function Nav() {
    const {notify, message, clearNotification, setMessage} = useNotification();
    const {getCookie, setCookie} = useCookie();

    const [isOpenWasModal, setIsOpenWasModal] = useState(false);

    if (window["WebSocket"] && window.authData && window.authData.userId) {
        const socket = new WebSocket(`ws://localhost:4000?user_id=${window.authData.userId}`);

        socket.onopen = function (e) {
            console.log(e);
        }

        socket.onclose = function () {
            console.log("Connection has been closed.");
        }
        socket.onmessage = function (e) {
            const message = (JSON.parse(e.data)).message;

            let notification = new Notify('Notification Title', message);
        }
    }


    if (!getCookie('was')) {
        setIsOpenWasModal(true);
    }


    setCookie('was', true, {
        expires: 365,
        path: '/'
    });



    return (
        <NotificationContext.Provider value={{ notify, message, clearNotification, setMessage }}>
            <CookieModal handleClose={() => setIsOpenWasModal(false)} isOpen={isOpenWasModal}/>
            <Notification/>
            <RightNav/>
        </NotificationContext.Provider>
    );
}

export default Nav;

