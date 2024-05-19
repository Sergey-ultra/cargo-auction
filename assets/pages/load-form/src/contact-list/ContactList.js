import React, {Fragment, useState} from "react";
import { useTranslation } from 'react-i18next';
import {Menu, MenuItem, Tooltip} from "@mui/material";
import "./contact-list.scss";

export default function ContactList({options, selectedContacts, setSelectedContacts}) {
    const { t } = useTranslation();
    const [dropAnchorEl, setDropAnchorEl] = useState(null);
    const isOpenDrop = Boolean(dropAnchorEl);

    const openDrop = (event) => {
        setDropAnchorEl(event.currentTarget);
    };

    const closeDrop = () => setDropAnchorEl(null);

    const deleteContact = id => {
        const newList = selectedContacts.filter(el => el !== id);
        setSelectedContacts(newList);
    }

    const addContact = id => {
        const newList = [...selectedContacts];
        newList.push(id);
        setSelectedContacts(newList);
        closeDrop();
    }

    const list = options.filter(el => selectedContacts.includes(el.id));
    const availableOptions = options.filter(el => !selectedContacts.includes(el.id));

    return (
        <div>
            {selectedContacts.length !== options.length &&
                <Fragment>
                    <div className="contact__add" onClick={openDrop}>Добавить контакт</div>
                    <Menu
                        anchorEl={dropAnchorEl}
                        open={isOpenDrop}
                        onClose={closeDrop}>
                        {availableOptions.map(contact =>
                            <MenuItem onClick={() => addContact(contact.id)}>
                                <span className="contact__name">{contact.name}</span>
                                <span className="contact__phone">{contact.phone}</span>
                            </MenuItem>
                        )}
                    </Menu>
                </Fragment>
            }
            <div className="contact">
                <div className="contact__description">
                    {list.map(contact =>
                        <div className="contact__show contact__item">
                            <span className="contact__name">{contact.name}</span>
                            <span className="contact__phone">{contact.phone}</span>
                            <Tooltip title={t('delete')} placement="top">
                                <span className="close-button" onClick={() => deleteContact(contact.id)}></span>
                            </Tooltip>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}


