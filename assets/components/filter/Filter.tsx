import React, {ChangeEventHandler, DetailedHTMLProps, InputHTMLAttributes, useContext} from "react";
import AutocompleteAddress, {City} from "../AutocompleteAddress";
import {FilterContext} from "../../context/filter.context";
import {Tooltip} from "@mui/material";
import InfoIcon from "../icons/InfoIcon";
import {FilterProvider} from "../../hooks/filter";


function Filter() {
    const { filter, setFilter, changeFilterAddresses }: FilterProvider = useContext(FilterContext);
    const changeFilter = (event: ChangeEventHandler<DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement>>): void => {
        setFilter({...filter, [event.target.name]: event.target.value})
    }

    const setFromAddressFilterValue = (city: City) => setFilter({...filter, from: {id: city.id, name: city.name}});
    const setToAddressFilterValue = (city: City) => setFilter({...filter, to: {id: city.id, name: city.name}});

    return (
    <form className="box" id="filter">
        <div className="filter__row">
            <div className="filter__block">
                <div className="filter__element">
                    <label className="filter__label text-bold">Откуда</label>
                    <AutocompleteAddress
                        initialValue={filter.from.id}
                        setCityObject={setFromAddressFilterValue}
                        label=""/>
                </div>
                <div className="filter__element filter__element-radius">
                    <label className="filter__label">
                        Радиус
                        <Tooltip title="Укажите расстояние до указанного пункта, на котором искать грузы" placement="top">
                           <span>
                               <InfoIcon/>
                           </span>
                        </Tooltip>
                    </label>
                    <input type="text" className="filter__input" id="fromRadius" name="fromRadius"
                           placeholder="км"
                           disabled={filter.from.name === ''} value={filter.fromRadius}
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
                        initialValue={filter.to.id}
                        setCityObject={setToAddressFilterValue}
                        label=""
                        initialList={[]}
                    />
                </div>
                <div className="filter__element filter__element-radius">
                    <label className="filter__label">
                        Радиус
                        <Tooltip title="Укажите расстояние до указанного пункта, на котором искать грузы" placement="top">
                            <span>
                                <InfoIcon/>
                            </span>
                        </Tooltip>
                    </label>
                    <input type="text" className="filter__input" id="toRadius" name="toRadius" placeholder="км"
                           disabled={filter.to.name === ''} value={filter.toRadius} onChange={changeFilter}/>
                </div>
            </div>
            <div className="filter__block filter__block-right">
                <div className="filter__element filter__minMax">
                    <label className="filter__label">Вес, т</label>
                    <div className="filter__block">
                        <input type="text" className="filter__input" name="weightMin" value={filter.weightMin ?? ''}
                               onChange={changeFilter} placeholder="от"/>
                        <input type="text" className="filter__input" name="weightMax" value={filter.weightMax ?? ''}
                               onChange={changeFilter} placeholder="до"/>
                    </div>
                </div>
                <div className="filter__element filter__minMax">
                    <label className="filter__label">
                        <span>Объем, м<sup>3</sup></span>
                    </label>
                    <div className="filter__block">
                        <input type="text" className="filter__input" name="volumeMin" value={filter.volumeMin?? ''}
                               onChange={changeFilter} placeholder="от"/>
                        <input type="text" className="filter__input" name="volumeMax" value={filter.volumeMax ?? ''}
                               onChange={changeFilter} placeholder="до"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
    );
}

export default Filter;
