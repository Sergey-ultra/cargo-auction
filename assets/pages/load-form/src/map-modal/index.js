import React, {useState} from 'react';
import DialogTitle from "@mui/material/DialogTitle";
import Dialog from "@mui/material/Dialog";
import {DialogContent} from "@mui/material";
import {YMaps, Map, Placemark, ObjectManager, ZoomControl} from '@pbe/react-yandex-maps';


export default function MapModal({
                                     isOpen,
                                     onClose,
                                     location
                                 }) {

    const startAddress = [
        location?.latitude || 55.751574,
        location?.longitude || 37.573856
    ];

    const [maps, setMaps] = useState(null);
    const [address, setAddress] = useState("");

    const handleBoundsChange = e => {
        let coord = e.get("target").getCenter();
        console.log(coord);

        let resp = maps.geocode(coord);
        resp.then((res) => {
            setAddress(res.geoObjects.get(0).getAddressLine());
            console.log(console.log(Object.keys(res.geoObjects.get(0))));
        });
    }



    return(
        <Dialog onClose={onClose} open={isOpen} maxWidth="md" sx={{m: 0, p: 0}}>
            <DialogTitle>
                {address}
                Выбор адреса
            </DialogTitle>
            <DialogContent>
                <div className="mapWrapper">
                    <div className="mapMap">
                        <YMaps query={{apikey: '20782eee-9155-4aa6-9ba7-d4bab3139587'}}>
                            <Map
                                defaultState={{
                                    center: startAddress,
                                    zoom: 8,
                                    behaviors: ["default", "scrollZoom"],
                                    controls: [],
                                    yandexMapDisablePoiInteractivity: true,
                                }}
                                width="100%"
                                height="100%"
                                modules={["geolocation", "geocode", "control.GeolocationControl"]}
                                onLoad={(ymaps) => setMaps(ymaps)}
                                onBoundsChange={handleBoundsChange}
                            >
                                <ZoomControl options={{
                                    position: {
                                        top: 300,
                                        right: 10,
                                    },
                                    adjustMapMargin: false,
                                }}/>
                                {/*<ObjectManager*/}
                                {/*    onMouseEnter={async(e) => {*/}
                                {/*        console.log(e);*/}
                                {/*        const id = e.get('objectId');*/}
                                {/*        if (typeof id === 'number') {*/}
                                {/*            console.log(id);*/}
                                {/*        }*/}
                                {/*    }}*/}
                                {/*/>*/}
                                {/*<Placemark geometry={startAddress} />*/}
                            </Map>
                        </YMaps>
                    </div>
                    <div className="mapDragger">
                        <svg className="AddressMap_Dragger__pinIcon" width="28" height="40" viewBox="0 0 28 40"
                             fill="red" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 0C6.268 0 0 6.268 0 14C0 16.812 0.836038 19.426 2.26404 21.62L14 40L25.736 21.62C27.164 19.426 28 16.814 28 14C28 6.268 21.732 0 14 0Z"
                                fill="#FE2722" data-spm-anchor-id="a2g2x.cart.0.i2.12764aa6UcRivc"></path>
                            <path
                                d="M14 18C16.2091 18 18 16.2091 18 14C18 11.7909 16.2091 10 14 10C11.7909 10 10 11.7909 10 14C10 16.2091 11.7909 18 14 18Z"
                                fill="white"></path>
                        </svg>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
