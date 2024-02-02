import React, {useState} from "react";
import MenuUser from "./MenuUser";
import {Button} from "@mui/material";
import AuthModal from "./AuthModal";

export default function RightNav() {
    const isAuth = window.authData && window.authData.userId;
    const email = window?.authData?.email;

    const [isOpenAuthModal, setOpenAuthModal] = useState(false);
    const handleClickOpen = () => setOpenAuthModal(true);
    const closeAuthModal = () => setOpenAuthModal(false);



    if (isAuth) {
        return (
            <div className="right">
                <MenuUser email={email}/>
            </div>
        );
    }

    return (
        <div className="right">
            <Button variant="contained" className="button button-primary button-small" sx={{ marginRight: 2 }} onClick={handleClickOpen}>
                Вход
            </Button>
            <a className="button button-secondary button-small" href="/register">Регистрация</a>
            <AuthModal isOpen={isOpenAuthModal} onClose={closeAuthModal}/>
        </div>
    );
}
