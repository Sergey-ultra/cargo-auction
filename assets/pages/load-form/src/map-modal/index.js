import React from 'react';
import DialogTitle from "@mui/material/DialogTitle";
import Dialog from "@mui/material/Dialog";
import {DialogContent} from "@mui/material";
import { YMaps, Map, Placemark } from '@pbe/react-yandex-maps';


export default function MapModal({
                                     isOpen,
                                     onClose,
                                     location
                                 }) {

    const address = [
        location?.latitude || 55.751574,
        location?.longitude || 37.573856
    ];

    return(
        <Dialog onClose={onClose} open={isOpen} maxWidth="md" fullWidth>
            <DialogTitle>Выбор адреса</DialogTitle>
            <DialogContent>
                {location?.latitude}
                {location?.longitude}
                <YMaps query={{ apikey: 'ваш ключ' }}>
                    <Map defaultState={{ center: [55.751574, 37.573856], zoom: 9 }}>
                        <Placemark geometry={[55.751574, 37.573856]} />
                    </Map>
                </YMaps>
            </DialogContent>
        </Dialog>
    );
}
