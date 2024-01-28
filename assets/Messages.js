import React, {Fragment, useEffect, useState} from 'react';
import {useHttp} from "./hooks/api";
export default function Messages() {
   // let searchParams = new URLSearchParams(window.location);
    const userId = window.location.pathname.match(/\/profile\/messages\/(\d+)/)[1];
    const searchParams = new URLSearchParams(window.location.search);
    const loadId = searchParams.get('load_id');

    const {request} = useHttp();

    const [chats, setChats] = useState([]);
    const [currentChat, setCurrentChat] = useState({});

    const getMessages = async() => {
        const { data } = request(`/api/messages/${userId}`, 'GET', { params: {load_id: loadId}});
        if (data && typeof data === 'object') {
            setCurrentChat(data);
        }
    }

    const getChats = async() => {
        const { data } = await request('/api/chats');
        if (data && Array.isArray(data)) {
            setChats(data);
        }
    }

    useEffect( () => {
        getMessages();
        getChats();
    }, []);

    return (
        <Fragment>
            <div className="sidebar">
                <div className="message__tabs"></div>
                <div className="messages__chatList">
                    {chats.map(chat => <div className="message__chatLink" key={chat.id}>
                        <div className="message__chatAvatar"></div>
                        <div className="message__chatMain">
                            <div className="message__chatHeader">
                                <div className="message__chatName">{chat.name}</div>
                            </div>
                        </div>
                    </div>)}
                </div>
            </div>
            <div className="message__container">
                <div className="container__header">

                </div>
            </div>
        </Fragment>
    );
}
