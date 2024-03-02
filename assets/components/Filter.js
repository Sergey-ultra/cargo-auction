import React, {useContext} from "react";
import AutocompleteAddress from "./AutocompleteAddress";
import {FilterContext} from "../context/filter.context";
import {Tooltip} from "@mui/material";


function Filter() {
    const { filter, setFilter, changeFilterAddresses } = useContext(FilterContext);
    const changeFilter = event => {
        setFilter({...filter, [event.target.name]: event.target.value})
    }

    const setFromAddressFilterValue = name => setFilter({...filter, fromAddress: name});
    const setToAddressFilterValue = name => setFilter({...filter, toAddress: name});

    return (
    <form className="box" id="filter">
        <div className="filter__row">
            <div className="filter__block">
                <div className="filter__element">
                    <label className="filter__label text-bold">Откуда</label>
                    <AutocompleteAddress
                        value={filter.fromAddress}
                        setAddressValue={setFromAddressFilterValue}
                        label=""
                    />
                </div>
                <div className="filter__element filter__element-radius">
                    <label className="filter__label">
                        Радиус
                        <Tooltip title="Укажите расстояние до указанного пункта, на котором искать грузы" placement="top">
                            <span className="question-tooltip">
                                 <svg fill="#8c969d" stroke="#8c969d" strokeWidth="0" data-qa="icon" viewBox="0 0 20 20"  width="14" height="14">
                                     <path fillRule="evenodd" clipRule="evenodd"
                                                  d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0Zm0 1.25a8.75 8.75 0 1 0 0 17.5 8.75 8.75 0 0 0 0-17.5ZM6.25 5.079c.22-.185.46-.357.718-.518a5.62 5.62 0 0 1 .834-.423 5.22 5.22 0 0 1 .958-.285 5.662 5.662 0 0 1 1.11-.103c.56 0 1.063.074 1.512.223.449.149.83.361 1.146.638.315.276.557.609.726.998.17.39.255.822.255 1.298 0 .452-.063.842-.188 1.172-.124.33-.282.617-.472.86-.19.245-.4.456-.629.634-.229.178-.446.343-.65.495a5.064 5.064 0 0 0-.536.45 1.003 1.003 0 0 0-.29.5l-.25 1.248h-1.89l-.196-1.436c-.048-.291-.018-.545.09-.762.106-.217.253-.413.44-.589.188-.175.397-.341.63-.499.231-.158.448-.327.65-.508.202-.182.373-.384.513-.607.14-.223.21-.489.21-.798 0-.356-.118-.64-.353-.851-.235-.211-.557-.317-.967-.317a2.73 2.73 0 0 0-.79.098 3.18 3.18 0 0 0-.543.219c-.152.08-.284.153-.397.218a.671.671 0 0 1-.34.098.614.614 0 0 1-.57-.33L6.25 5.08Zm1.761 9.206a1.48 1.48 0 0 0-.12.597 1.512 1.512 0 0 0 .45 1.088c.14.137.305.244.495.321.19.078.398.116.624.116a1.603 1.603 0 0 0 1.115-.436 1.513 1.513 0 0 0 .455-1.088 1.481 1.481 0 0 0-.455-1.08 1.632 1.632 0 0 0-.5-.325c-.19-.08-.395-.12-.615-.12a1.59 1.59 0 0 0-1.119.445 1.51 1.51 0 0 0-.33.482Z"></path>
                                </svg>
                            </span>
                        </Tooltip>
                    </label>
                    <input type="text" className="filter__input" id="fromRadius" name="fromRadius"
                           placeholder="км"
                           disabled={filter.fromAddress === ''} value={filter.fromRadius}
                           onChange={changeFilter}/>
                </div>
                <div className="filter__swap" onClick={changeFilterAddresses}>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.99 11L3 15l3.99 4v-3H14v-2H6.99v-3zM21 9l-3.99-4v3H10v2h7.01v3L21 9z"></path>
                        <path d="M0 0h24v24H0z" fill="none"></path>
                    </svg>
                </div>
                <div className="filter__element">
                    <label className="filter__label text-bold">Куда</label>
                    <AutocompleteAddress
                        value={filter.toAddress}
                        setAddressValue={setToAddressFilterValue}
                        label=""
                    />
                </div>
                <div className="filter__element filter__element-radius">
                    <label className="filter__label">
                        Радиус
                        <Tooltip title="Укажите расстояние до указанного пункта, на котором искать грузы" placement="top">
                            <span className="question-tooltip">
                                 <svg fill="#8c969d" stroke="#8c969d" strokeWidth="0" data-qa="icon" viewBox="0 0 20 20"  width="14" height="14">
                                     <path fillRule="evenodd" clipRule="evenodd"
                                           d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0Zm0 1.25a8.75 8.75 0 1 0 0 17.5 8.75 8.75 0 0 0 0-17.5ZM6.25 5.079c.22-.185.46-.357.718-.518a5.62 5.62 0 0 1 .834-.423 5.22 5.22 0 0 1 .958-.285 5.662 5.662 0 0 1 1.11-.103c.56 0 1.063.074 1.512.223.449.149.83.361 1.146.638.315.276.557.609.726.998.17.39.255.822.255 1.298 0 .452-.063.842-.188 1.172-.124.33-.282.617-.472.86-.19.245-.4.456-.629.634-.229.178-.446.343-.65.495a5.064 5.064 0 0 0-.536.45 1.003 1.003 0 0 0-.29.5l-.25 1.248h-1.89l-.196-1.436c-.048-.291-.018-.545.09-.762.106-.217.253-.413.44-.589.188-.175.397-.341.63-.499.231-.158.448-.327.65-.508.202-.182.373-.384.513-.607.14-.223.21-.489.21-.798 0-.356-.118-.64-.353-.851-.235-.211-.557-.317-.967-.317a2.73 2.73 0 0 0-.79.098 3.18 3.18 0 0 0-.543.219c-.152.08-.284.153-.397.218a.671.671 0 0 1-.34.098.614.614 0 0 1-.57-.33L6.25 5.08Zm1.761 9.206a1.48 1.48 0 0 0-.12.597 1.512 1.512 0 0 0 .45 1.088c.14.137.305.244.495.321.19.078.398.116.624.116a1.603 1.603 0 0 0 1.115-.436 1.513 1.513 0 0 0 .455-1.088 1.481 1.481 0 0 0-.455-1.08 1.632 1.632 0 0 0-.5-.325c-.19-.08-.395-.12-.615-.12a1.59 1.59 0 0 0-1.119.445 1.51 1.51 0 0 0-.33.482Z"></path>
                                </svg>
                            </span>
                        </Tooltip>
                    </label>
                    <input type="text" className="filter__input" id="toRadius" name="toRadius" placeholder="км"
                           disabled={filter.toAddress === ''} value={filter.toRadius} onChange={changeFilter}/>
                </div>
            </div>
            <div className="filter__block filter__block-right">
                <div className="filter__element filter__minMax">
                    <label className="filter__label">Вес, т</label>
                    <div className="filter__block">
                        <input type="text" className="filter__input" name="weightMin" value={filter.weightMin}
                               onChange={changeFilter}/>
                        <input type="text" className="filter__input" name="weightMax" value={filter.weightMax}
                               onChange={changeFilter}/>
                    </div>
                </div>
                <div className="filter__element filter__minMax">
                    <label className="filter__label">
                        <span>Объем, м<sup>3</sup></span>
                    </label>
                    <div className="filter__block">
                        <input type="text" className="filter__input" name="volumeMin" value={filter.volumeMin}
                               onChange={changeFilter}/>
                        <input type="text" className="filter__input" name="volumeMax" value={filter.volumeMax}
                               onChange={changeFilter}/>
                    </div>
                </div>
            </div>
        </div>
    </form>
    );
}

export default Filter;
