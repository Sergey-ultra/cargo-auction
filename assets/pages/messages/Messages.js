import React, {Fragment, useEffect, useState} from 'react';
import {useHttp} from "../../hooks/api";
import {Button} from "@mui/material";
export default function Messages() {
    const pathParts = window.location.pathname.match(/\/profile\/chat\/(\d+)/);
    const chatId = pathParts && pathParts.length >= 2 ? pathParts[1] : null;

    const isAuth = window.authData && window.authData.userId;

    const {request} = useHttp();

    const [chats, setChats] = useState([]);
    const [currentChat, setCurrentChat] = useState({});
    const [messages, setMessages] = useState([]);
    const [sendText, setSendText] = useState([]);

    const onChangeMessage = e => setSendText(e.target.value);

    const sendMessage = async() => {
        if (sendText && currentChat.id) {
            const { data } = await request(`/api/chat/${currentChat.id}/messages`, 'POST', {body: {message: sendText}});
            if (data.status === 'ok') {
                setSendText('');
            }
        }
    }

    const getChat = async() => {
        if (chatId) {
            const {data} = await request(`/api/chat/${chatId}`, 'GET');
            if (data && typeof data === 'object') {
                setCurrentChat(data);
                setSendText(data.draft);
            }
        }
    }

    const getChats = async() => {
        const { data } = await request('/api/chats');
        if (data && Array.isArray(data)) {
            setChats(data);
        }
    }

    const getMessages = async() => {
        const { data } = await request(`/api/chat/${currentChat.id}/messages`);
        if (data && Array.isArray(data)) {
            setMessages(data);
            if (!data.length) {
                setSendText(currentChat.draft);
            } else {
                setSendText('');
            }
        }
    }

    useEffect( () => {
        getChat();
        getChats();
    }, []);

    useEffect(() => {
        if (currentChat.id) {
            getMessages();
        }

    }, [currentChat])

    return (
        <Fragment>
            <div className="sidebar">
                <div className="messages__chatList">
                    {chats.map((chat, index) =>
                        <div
                            onClick={() => setCurrentChat(chat)}
                            className={`message__chatLink  ${chat.id === currentChat.id ? 'message__chatLink-active' : ''}`}
                            key={chat.id}>
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
                <div className="container__headerMain">
                    <div className="container__headerHeader">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60">
                                <g>
                                    <title>background</title>
                                    <rect fill="none" id="canvas_background" height="62" width="62" y="-1" x="-1"/>
                                </g>
                                <g>
                                    <path id="svg_1" fill="#8C969D"
                                          d="m30.000001,33.0175c2.5263,0 5.1579,0.5497 7.8947,1.6492c2.7369,1.0994 4.1053,2.538 4.1053,4.3158l0,3.0175l-24,0l0,-3.0175c0,-1.7778 1.36841,-3.2164 4.10526,-4.3158c2.73686,-1.0995 5.36841,-1.6492 7.89474,-1.6492zm0,-3.0175c-1.6374,0 -3.04093,-0.5848 -4.21053,-1.7544c-1.16959,-1.16958 -1.75438,-2.57308 -1.75438,-4.21051c0,-1.63744 0.58479,-3.05263 1.75438,-4.24562c1.1696,-1.19299 2.57313,-1.78947 4.21053,-1.78947c1.6374,0 3.0409,0.59649 4.2105,1.78947c1.1696,1.19299 1.7544,2.60818 1.7544,4.24562c0,1.63743 -0.5848,3.04093 -1.7544,4.21051c-1.1696,1.1696 -2.5731,1.7544 -4.2105,1.7544z"
                                          clipRule="evenodd" fillRule="evenodd"/>
                                </g>
                            </svg>
                        </div>
                        {currentChat.name}
                    </div>
                </div>
                <div className="message__dialog">
                    {messages.map(message =>
                        <div key={message.id} className={`message ${message.fromUser.id === Number(isAuth) ? ' message-green' : ' message-red'}`}>
                            <span>{message.message}</span>
                            <span>{message.createdAt}</span>
                        </div>
                    )}
                </div>
                <div className="message__input">
                    <div className="message__inputContent">
                        <input className="message__input-text" value={sendText} onChange={onChangeMessage}/>

                        <Button onClick={sendMessage} variant="outlined" className="button button-secondary button-small"
                                sx={{marginLeft: 2}}>
                            Отправить
                        </Button>
                    </div>
                </div>
            </div>
        </Fragment>
    );
}
