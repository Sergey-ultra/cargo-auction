import React, {useEffect, useState} from 'react';
import { Centrifuge } from 'centrifuge';
import RightNav from "../../components/RightNav";
import Notification from "../../components/Notification";
import {useNotification} from "../../hooks/notification";
import {NotificationContext} from "../../context/notification.context";
import {useCookie} from "../../hooks/cookie";
import CookieModal from "../../components/CookieModal";
import {useHttp} from "../../hooks/api";


function Nav() {
    const {notify, message, clearNotification, setMessage} = useNotification();
    const { request } = useHttp();
    const {getCookie, setCookie} = useCookie();

    const [isOpenWasModal, setIsOpenWasModal] = useState(false);


    const [centrifugoToken, setCentrifugoToken] = useState(false);
    const fetchCentrifugoCredentials = async() => {
        const { data } = await request('/centrifugo/credentials/user');
        if (data.token) {
            setCentrifugoToken(data.token);
        }
    }
    if (window.authData && window.authData.userId) {
        useEffect(() => {
            if (!centrifugoToken) {
                fetchCentrifugoCredentials();
            }

            if (centrifugoToken) {
                const centrifuge = new Centrifuge(`ws://${location.host}/connection/websocket`, {
                    token: centrifugoToken
                });

                centrifuge.on('connected', function (ctx) {
                    //console.log(ctx);
                });

                const sub = centrifuge.newSubscription(`notification_${window.authData.userId}`);

                sub.on('publication', function (ctx) {
                    console.log(ctx);
                    if (ctx.data.message) {
                        notify('publication', ctx.data.message);
                    }
                }).on('subscribing', function (ctx) {
                    //console.log('subscribing', ctx.code, ctx.reason);
                }).on('subscribed', function (ctx) {
                    console.log('subscribed', ctx);
                }).on('unsubscribed', function (ctx) {
                    console.log('unsubscribed', ctx);
                });

                sub.subscribe();
                centrifuge.connect();
            }
        }, [centrifugoToken]);
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

