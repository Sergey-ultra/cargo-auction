import React from 'react';
import RightNav from "./components/RightNav";
import Notification from "./components/Notification";
import {useNotification} from "./hooks/notification";
import {NotificationContext} from "./context/notification.context";



function Nav() {
    const {notify, message, clearNotification, setMessage} = useNotification();
    return (
        <NotificationContext.Provider value={{ notify, message, clearNotification, setMessage }}>
          <Notification/>
          <RightNav/>
        </NotificationContext.Provider>
    );
}

export default Nav;

