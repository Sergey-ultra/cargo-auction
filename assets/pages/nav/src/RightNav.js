import React, {useState} from "react";
import MenuUser from "./MenuUser";
import {Button} from "@mui/material";
import AuthModal from "./AuthModal";
import GreenButton from "../../../components/buttons/GreenButton";

export default function RightNav() {
    const isAuth = window.authData && window?.authData?.userId;
    const email = window?.authData?.email;

    const [isOpenAuthModal, setOpenAuthModal] = useState(false);

    const [showMode, setShowMode] = useState('login');
    const showLogin = () => setShowMode('login');
    const showRegister = () => setShowMode('register');

    const showRegisterModal = () => {
        setOpenAuthModal(true);
        showRegister();
    }

    const openAuthModal = () => {
        setOpenAuthModal(true);
        showLogin();
    }
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
            <Button variant="contained" className="button button-primary button-small" sx={{ marginRight: 2 }} onClick={openAuthModal}>
                Вход
            </Button>
            <GreenButton onClick={showRegisterModal}>
                Регистрация
            </GreenButton>
            <AuthModal isOpen={isOpenAuthModal} onClose={closeAuthModal} showMode={showMode}/>
        </div>
    );
}
