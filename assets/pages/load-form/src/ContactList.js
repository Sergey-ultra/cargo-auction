import React from "react";
import {Tooltip} from "@mui/material";

export default function ContactList({availableContacts, selectedContacts, setSelectedContacts}) {
    const deleteContact = id => {
        const newList = selectedContacts.filter(el => el !== id);
        setSelectedContacts(newList);
    }

    const list = availableContacts.filter(el => selectedContacts.includes(el.id));

    return (
        <div className="contact">
            <div className="description">
                {list.map(contact =>
                    <div className="contact__show description__item">
                        <span className="contact__name">{contact.name}</span>
                        <span className="contact__phone">{contact.phone}</span>
                        <Tooltip title="Удалить" placement="top">
                            <span className="close-button" onClick={() => deleteContact(contact.id)}></span>
                        </Tooltip>
                    </div>
                )}
            </div>
        </div>
    );
}


