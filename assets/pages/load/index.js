import React, {Fragment, useEffect, useState} from "react";
import MessengerIcon from "../../components/icons/MessengerIcon";
import {useHttp} from "../../hooks/api";

const Load = () => {
    const { request, isLoading, error, clearError } = useHttp();

    // let map = L.map('map').fitBounds([
    //     [43.6956, 38.5235],
    //     [45.2362, 41.1877],
    // ]);

    // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     maxZoom: 19,
    //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    // }).addTo(map);

    const pathParts = window.location.pathname.match(/\/load\/(\d+)/);
    const loadId = pathParts && pathParts.length >= 2 ? pathParts[1] : null;

    const [load, setLoad] = useState({
        id: '',
        route: {
            country: '',
            distance: ''
        },
        bids: {
            count: 0,
            maxValue: 0,
            bids: [],
        },
        loading: {
            location: {
                city: '',
                street: '',
            },
            downloadingDate: '',
        },
        unloading: {
            location: {
                city: '',
                street: '',
            },
        },
        cargoType: '',
        bodyType: '',
        weight: '',
        volume: '',
        rate: {
            priceCash: '',
            priceCashPerKm: '',
            priceWithoutTax: '',
            priceWithoutTaxPerKm: '',

        },
        note: '',
        comment: '',
        userId: '',
        createdAt: '',
        updatedAt: '',
        company: {
            id: '',
            fullName: '',
            cityName: '',
            type: '',
            contacts: [],
        },
    });



    const fetchLoad= async() => {
        if (loadId) {
            const {data} = await request(`/api/load/${loadId}`, 'GET');
            if (data && typeof data === 'object') {
                setLoad(data);
            }
        }
    }

    useEffect(() => {
        fetchLoad();
    }, []);

return (
    <Fragment>
        <div className="header">
            <div className="wrapper">
                <h1 className="">Груз {load.loading.location.city} — {load.unloading.location.city}, другой, от {load.createdAt}</h1>
            </div>
        </div>
        <div className="load">
            <section className="">
                <h5 className="route header">Маршрут и груз
                    <span
                        className="distance">{load.route.country} – {load.route.country} {load.route.distance} км</span>
                </h5>
                <div className="points">
                    <div className="point">
                        <div className="line">
                                <span className="pointIcon">
                                    <svg fill="#088BFD" stroke="#088BFD" strokeWidth="0"
                                         data-qa="icon" viewBox="0 0 14 14" width="14"
                                         height="14">
                                        <use href="#ic_point-load">
                                            <symbol id="ic_point-load" viewBox="0 0 20 16">
                                                <path
                                                    d="M12.857 5h3.136c.548 0 1.053.3 1.315.782L20 10.74v1.373c0 .827-.67 1.497-1.497 1.497h-1.507A2.624 2.624 0 0 1 14.388 16a2.624 2.624 0 0 1-2.608-2.391H8.017A2.624 2.624 0 0 1 5.408 16 2.624 2.624 0 0 1 2.8 13.609H1.497C.67 13.609 0 12.939 0 12.112V10.74h12.109a.748.748 0 0 0 .748-.748V5Z"></path>
                                                <path
                                                    d="M4 5H2.487a.2.2 0 0 0-.14.342L5.912 8.86a.2.2 0 0 0 .283-.001l3.47-3.518A.2.2 0 0 0 9.521 5H8V0H4v5Z"></path>
                                            </symbol>
                                        </use>
                                    </svg>
                                </span>
                            <span className="decorator"></span>
                        </div>
                        <span className="location">
                        <span className="locationCity"></span>
                        <span className="locationFullName">{load.loading.location.city}</span>
                    </span>
                        <span className="dateTime">Загрузка {load.loading.downloadingDate}</span>
                        {load.loading.location.street &&
                            <span className="address">{load.loading.location.street}</span>
                        }
                        <span className="cargos">
                        <div className="cargo">
                            <svg fill="#088BFD" stroke="#088BFD" strokeWidth="0" data-qa="icon" viewBox="0 0 14 14"
                                 width="14" height="14" className="icon">
                                <use href="#ic_cargo">
                                    <symbol id="ic_cargo" viewBox="0 0 16 19">
                                        <path
                                            d="m9.466 9.25-.816.473v8.492L16 13.97V5.479L9.466 9.251Zm1.33-7.614L7.97 0 .416 4.36l2.83 1.637 7.55-4.36ZM15.52 4.36l-3.257-1.855-7.55 4.361.43.223L7.97 8.722 10.78 7.1l4.739-2.74ZM4.142 9.988l-1.353-.695V7.13L0 5.525v8.43l7.297 4.214V9.74L4.142 7.92v2.067Z"></path>
                                    </symbol>
                                </use>
                            </svg>
                            <span className="weightVolume">{load.weight} / {load.volume} </span>
                            <span className="name">{load.cargoType}</span>
                        </div>
                    </span>
                    </div>
                    <div className="point">
                        <div className="line">
                                <span className="pointIcon">
                                    <svg fill="var(--green)" stroke="var(--green)"
                                         strokeWidth="0" data-qa="icon" viewBox="0 0 14 14"
                                         width="14" height="14">
                                        <use href="#ic_point-unload">
                                            <symbol id="ic_point-unload" viewBox="0 0 20 16">
                                                <path
                                                    d="M12.857 5h3.136c.548 0 1.053.3 1.315.782L20 10.74v1.373c0 .827-.67 1.497-1.497 1.497h-1.507A2.624 2.624 0 0 1 14.388 16a2.624 2.624 0 0 1-2.608-2.391H8.017A2.624 2.624 0 0 1 5.408 16 2.624 2.624 0 0 1 2.8 13.609H1.497C.67 13.609 0 12.939 0 12.112V10.74h12.109a.748.748 0 0 0 .748-.748V5Z"></path>
                                                <path
                                                    d="M8 4h1.512a.2.2 0 0 0 .14-.342L6.089.14a.2.2 0 0 0-.283.002L2.335 3.66a.2.2 0 0 0 .143.34H4v5h4V4Z"></path>
                                            </symbol>
                                        </use>
                                    </svg>
                                </span>
                            <span className="decorator"></span>
                        </div>
                        <span className="location">
                        <span className="locationCity"></span>
                        <span className="locationFullName">{load.unloading.location.city}</span>
                    </span>
                        <span className="dateTime">Разгрузка</span>
                        {load.unloading.location.street &&
                            <span className="address">{load.unloading.location.street}</span>
                        }
                    </div>
                </div>
            </section>
            <div className="">
                <section className="">
                    <h5 className="size-5">Расчёт расстояний</h5>
                    <div className="calculation">
                        <span className="timeLabel label">Время</span>
                        <span
                            className="timeValue size-5">{load.route.distanceHours} ч {load.route.distanceMinutes} мин</span>
                        <span className="fuelLabel label">Расход топлива</span>
                        <span className="fuelValue size-5"> {load.route.fuelConsumption} л</span>
                        <span className="links">
                                <a className="reverseRoute glz-link glz-is-medium" href="#" target="_blank"
                                   rel="noopener noreferrer">
                                    Поиск по обратному направлению
                                </a>
                                <a className="openMap glz-link glz-is-medium" href="#" target="_blank"
                                   rel="noopener noreferrer">
                                    Открыть карту
                                </a>
                            </span>
                    </div>
                    <div className="route-map">
                        <div id="map"></div>
                    </div>
                </section>
                <section className="transport">
                    <h5 className="transport__header size-5">Транспорт</h5>
                    <h6 className="label glz-p size-3">Кузов</h6>
                    <p className="value glz-p size-3">{load.bodyType}</p>
                    <div className="label glz-p size-3">Загрузка, выгрузка</div>
                    <div className="value glz-p size-3">{load.loading.downloadingType}</div>
                    <div className="label glz-p size-3">Доп. требования</div>
                    <div className="requirements value glz-p size-3">
                        <span className="requirement LTL">возм. догруз</span>
                    </div>
                </section>
                {load.note &&
                    <section className="">
                        <h5 className="size-5">Комментарий</h5>
                        <p className="glz-p size-3 note">{load.note}</p>
                    </section>
                }

                <h5 className="size-5">Ставка</h5>
                <div className="rate">
                    <div className="info">
                        <div className="payment-info-wrapper">
                            <div className="payment-info">
                                <div className="no-load"></div>
                                <div className="inquire-load"></div>
                                <div className="accept-payment-types">
                                    <div></div>
                                    <div></div>
                                </div>
                                {load.rate.priceCash &&
                                    <div className="rate">
                                        <span className="load-price"><span>{load.rate.priceCash}</span>  руб. </span>
                                        Ставка <span className="moneyType">нал</span>
                                        <span className="load-price-per-km"><span>{load.rate.priceCashPerKm}</span> руб/км</span>
                                    </div>
                                }
                                {load.rate.priceWithoutTax &&
                                    <div className="rate-without-nds">
                                        <span
                                            className="load-price"><span>{load.rate.priceWithoutTax}</span> руб.</span>
                                        без НДС
                                        <span className="moneyType"></span>
                                        <span
                                            className="load-price-per-km"><span>{load.rate.priceWithoutTaxPerKm}</span> руб/км</span>
                                    </div>
                                }
                                <span></span> <span></span> <span></span> <span>через 5 б/д</span> <span
                                className="no-torg">без торга</span>
                                <span className="load-direct"></span></div>
                            <button type="button" className="glz-button glz-is-medium glz-is-primary">
                                Отправить предложение {load.bids.count > 0 && `(${load.bids.count})`}
                            </button>
                        </div>
                    </div>
                </div>
                {load.bids.count > 0 &&
                    <div className="bids">
                        {load.bids.count} предложения:
                        <span>
                                <b>
                                    {load.bids.maxValue} руб.
                                </b>
                            </span>
                    </div>
                }
            </div>

            <aside className="company">
                <div className="sticky">
                    <div className="company__inner">
                        <div className="company__rating">
                            {/*<div className="left">*/}
                            {/*    <span className="icon">*/}
                            {/*        <img className="starsIcon" height="20"*/}
                            {/*             src="https://files.ati.su/assets/shared/icons/stars/stars_svg/stars_2_4.0.svg"*/}
                            {/*             alt="рейтинг фирмы">*/}
                            {/*    </span>*/}
                            {/*</div>*/}
                            {/*<div className="right">*/}
                            {/*    <span className="icon">*/}
                            {/*        <img height="18" src="https://files.ati.su/images/activeAtiDoc.svg" alt="рейтинг фирмы">*/}
                            {/*    </span>*/}
                            {/*    <span className="icon">*/}
                            {/*        <img height="18"*/}
                            {/*         src="https://files.ati.su/assets/shared/img/fstrafficlight/trafficLight_default.svg"*/}
                            {/*         alt="рейтинг фирмы">*/}
                            {/*    </span>*/}
                            {/*    <span className="icon">*/}
                            {/*        <img height="18" src="https://files.ati.su/assets/shared/img/fstrafficlight/firm_document.svg" alt="рейтинг фирмы">*/}
                            {/*    </span>*/}
                            {/*</div>*/}
                        </div>
                        <div className="company__name">
                            <button type="button" className="company__link">
                                {load.company.fullName}
                            </button>
                        </div>
                        <div className="company__meta">Код
                            : {load.company.id} , {load.company.type}, {load.company.cityName}</div>
                        <div className="">
                            {load.company.contacts.map(contact =>
                                <div className="contact">
                                    <div className="contact__name">{contact.name}</div>
                                    <div className="contact__detail">
                                        <svg fill="var(--blue)" stroke="var(--blue)" strokeWidth="0" data-qa="icon"
                                             viewBox="0 0 16 16" width="16" height="16" className="icon">
                                            <use href="#ic_phone">
                                                <symbol id="ic_phone" viewBox="0 0 18 18">
                                                    <path fillRule="evenodd" clipRule="evenodd"
                                                          d="M3.61 7.781c1.5 2.907 3.702 5.11 6.609 6.61l2.203-2.204c.312-.312.656-.39 1.031-.234 1.125.375 2.313.563 3.563.563.28 0 .515.093.703.28A.954.954 0 0 1 18 13.5v3.516a.955.955 0 0 1-.281.703.955.955 0 0 1-.703.281c-4.688 0-8.696-1.664-12.024-4.992C1.664 9.68 0 5.672 0 .984A.96.96 0 0 1 .281.281.954.954 0 0 1 .984 0H4.5c.281 0 .516.094.703.281a.954.954 0 0 1 .281.703c0 1.25.188 2.438.563 3.563.125.406.047.75-.234 1.031L3.608 7.781Z"></path>
                                                </symbol>
                                            </use>
                                        </svg>
                                        <span>{contact.phone}</span>
                                    </div>
                                    <div className="contact__detail">
                                        <svg fill="var(--blue)" stroke="var(--blue)" strokeWidth="0" data-qa="icon"
                                             viewBox="0 0 16 16" width="16" height="16" className="icon">
                                            <use href="#ic_phone"></use>
                                        </svg>
                                        <span>{contact.mobilePhone}</span>
                                    </div>
                                    <div className="contact__detail">
                                        <svg fill="var(--blue)" stroke="var(--blue)" strokeWidth="0" data-qa="icon"
                                             viewBox="0 0 16 16" width="16" height="16" className="icon">
                                            <use href="#ic_mail">
                                                <symbol id="ic_mail" viewBox="0 0 20 16">
                                                    <path fillRule="evenodd" clipRule="evenodd"
                                                          d="M1.25.5h17.5c.688 0 1.25.563 1.25 1.25v.947L10 7.954 0 2.698V1.75C0 1.062.563.5 1.25.5Zm7.5 9.58c.625.303.918.42 1.25.42.332 0 .625-.117 1.25-.42L20 5.5v8.75c0 .688-.563 1.25-1.25 1.25H1.25C.562 15.5 0 14.937 0 14.25V5.5l8.75 4.58Z"></path>
                                                </symbol>
                                            </use>
                                        </svg>
                                        <span>{contact.email}</span>
                                    </div>
                                    <div className="contact__detail">
                                        <MessengerIcon/>
                                        <a className="contact__link"
                                           href={`/profile/messages/${contact.id}/?load_id=${load.id}`}>
                                            Написать сообщение
                                        </a>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </aside>
        </div>

    </Fragment>
    );
}

export default Load;
