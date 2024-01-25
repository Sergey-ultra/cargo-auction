import React, {useState} from "react";
import MenuUser from "./MenuUser";
import {Button} from "@mui/material";
import AuthModal from "./AuthModal";

export default function RightNav() {
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
            <div className="right">
                <MenuUser email={email}/>
            </div>
        );
    }


    return (
        <div className="right">
            <Button variant="outlined" className="button button-primary button-small" sx={{ marginRight: 2 }} onClick={handleClickOpen}>
                Вход
            </Button>
            <a className="button button-secondary button-small" href="/register">Регистрация</a>
            <AuthModal
                selectedValue={selectedValue}
                open={open}
                onClose={handleClose}
            />
        </div>
    );
}
