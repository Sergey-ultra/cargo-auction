import React, {useEffect, useState} from 'react';
import { Centrifuge } from 'centrifuge';
import RightNav from "./src/RightNav";
import Notification from "./src/Notification";
import {useNotification} from "../../hooks/notification";
import {NotificationContext} from "../../context/notification.context";
import {useCookie} from "../../hooks/cookie";
import CookieModal from "./src/CookieModal";
import {useHttp} from "../../hooks/api";
import LocalChoice from "./src/LocalChoiсe";


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
                try {
                    const centrifuge = new Centrifuge(`ws://${location.host}/connection/websocket`, {
                        token: centrifugoToken
                    });

                    centrifuge.on('connected', function (ctx) {
                        //console.log(ctx);
                    });
                    centrifuge.on('disconnected', function (ctx) {
                        console.log("disconnected", ctx);
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
                } catch (e) {
                    console.log(e)
                }
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
            <LocalChoice/>
        </NotificationContext.Provider>
    );
}

export default Nav;

