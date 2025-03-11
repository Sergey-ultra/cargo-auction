import React, {useEffect, useRef, useState} from 'react';
import Dialog from "@mui/material/Dialog";
import {Button, CircularProgress, DialogContent} from "@mui/material";
import {YMaps, Map, Placemark, ObjectManager, ZoomControl} from '@pbe/react-yandex-maps';
import {coordinates} from "../../LoadFormInner";
import {YMapsApi} from "@pbe/react-yandex-maps/typings/util/typing";
import ymaps, {GeocodeResult, IGeoObject} from "yandex-maps";
import {useCookie} from "../../../../hooks/cookie";


interface MapModalProps {
    isOpen: boolean,
    onClose: () => void,
    coordinates: coordinates,
    setCoordinates: (coordinates: coordinates, address: string, administrativeAreas: string[]) => void
}

export default function MapModal({isOpen, onClose, coordinates, setCoordinates}: MapModalProps) {
    const {getCookie} = useCookie();
    const currentLocal = getCookie('locale') ?? 'en';

    //const [isLoading, setIsLoading] = useState<boolean>(true);
    const [map, setMap] = useState<YMapsApi|undefined>(undefined);
    const [address, setAddress] = useState<string>("");
    const [administrativeAreas, setAdministrativeAreas] = useState<string[]>([]);
    const [localCoordinates, setLocalCoordinates] = useState<coordinates>(coordinates);


    //const mapRef: MutableRefObject<ymaps.Map | undefined> = useRef(undefined);

    const setAddressByCoordinates = async (): Promise<void> => {
        if (!map || !localCoordinates) {
            return;
        }

        let res: IGeocodeResult = await map.geocode([localCoordinates.latitude, localCoordinates.longitude]);
        let geoObject: GeocodeResult = res.geoObjects.get(0);
        setAddress(geoObject.getAddressLine());
    }

    const onLoad =  (map: YMapsApi): void => {
        setMap(map);
    }

    const handleBoundsChange = async e => {
        const localMap: ymaps.Map = e.get("target");

        let coordinates: number[] = localMap.getCenter();
        setLocalCoordinates({
            latitude: coordinates[0],
            longitude: coordinates[1]
        });
        const res: IGeocodeResult = await map.geocode(coordinates);
        const geoObject: GeocodeResult = res.geoObjects.get(0);
        console.log(geoObject.getAdministrativeAreas());
        setAddress(geoObject.getAddressLine());
        setAdministrativeAreas(geoObject.getAdministrativeAreas())
    }

    const save = async(): Promise<void> => {
        setCoordinates(localCoordinates, address, administrativeAreas);
        onClose();
    }

    useEffect( () => {
        setAddressByCoordinates();
    },[map]);

    useEffect(() => setLocalCoordinates(coordinates), [coordinates]);

    return(
        <Dialog onClose={onClose} open={isOpen} maxWidth="lg">
            <DialogContent sx={{p: 0}}>
                <div className="mapFlex">
                    <div className="mapControl">
                        <div className="mapBottom">
                            {address || '  Выбор адреса'}
                            <div>Широта <span>{localCoordinates?.longitude}</span></div>
                            <div>Долгота <span>{localCoordinates?.latitude}</span></div>
                            <Button variant="contained" onClick={save}>Сохранить</Button>
                        </div>
                    </div>
                    <div className="mapWrapper">
                        <div className="mapMap">
                            <YMaps
                                query={{
                                    apikey: window && window.yandexMapApikey || '',
                                    //lang: currentLocal ==='en' ? 'en_US' : 'en_RU'
                                }}>
                                <Map
                                    //instanceRef={mapRef.current}
                                    defaultState={{
                                        center: [localCoordinates.latitude, localCoordinates.longitude],
                                        zoom: 8,
                                        behaviors: ["default", "scrollZoom"],
                                        controls: [],
                                        yandexMapDisablePoiInteractivity: true,
                                    }}
                                    width="100%"
                                    height="100%"
                                    modules={["geolocation", "geocode", "control.GeolocationControl"]}
                                    onLoad={(ymaps: YMapsApi): void => onLoad(ymaps)}
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
                                    {/*<Placemark geometry={startCoordinates} />*/}
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
                </div>
            </DialogContent>
        </Dialog>
    );
}
