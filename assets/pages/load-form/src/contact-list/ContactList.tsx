import React, {Fragment, MouseEventHandler, useState} from "react";
import { useTranslation } from 'react-i18next';
import {Menu, MenuItem, Tooltip} from "@mui/material";
import "./contact-list.scss";
import {Contact} from "../../LoadFormInner";

export interface ContactProps {
    options: Contact[],
    selectedContacts: number[],
    setSelectedContacts: (ids: number[]) => void
}

export default function ContactList({options, selectedContacts, setSelectedContacts}: ContactProps) {
    const { t } = useTranslation();
    const [dropAnchorEl, setDropAnchorEl] = useState(null);
    const isOpenDrop = Boolean(dropAnchorEl);

    const openDrop = (event) => {
        setDropAnchorEl(event.currentTarget);
    };

    const closeDrop = () => setDropAnchorEl(null);

    const deleteContact = (id: number): void => {
        const newList: number[] = selectedContacts.filter((el: number) => el !== id);
        setSelectedContacts(newList);
    }

    const addContact = (id: number): void => {
        const newList: number[] = [...selectedContacts];
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
                        {availableOptions.map((contact: Contact) =>
                            <MenuItem onClick={(): void => addContact(contact.id)}>
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
                        <div className="contact__show contact__item" key={contact.name}>
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


