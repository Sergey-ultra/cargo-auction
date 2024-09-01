import React, {useState} from 'react';
import {Tab, Tabs} from "@mui/material";
import {FilterContext} from "../../context/filter.context";
import {FilterProvider, useFilter} from "../../hooks/filter";
import MyFilters from "../../components/MyFilters";
import TransportList from "./src/TransportList";


function Transport() {
    const [tab, setTab] = useState('one');
    const {filter, setFilter, clearFilter, changeFilterAddresses}: FilterProvider = useFilter();
    const handleTab = (event, newValue) => setTab(newValue);
    const setMainTab = () => setTab("one");



    return (
        <FilterContext.Provider value={{ filter, setFilter, clearFilter, changeFilterAddresses }}>
            <Tabs
                value={tab}
                onChange={handleTab}
                aria-label="">
                <Tab value="one" label="Найти транспорт" wrapped/>
                <Tab value="two" label="Мои фильтры"/>
            </Tabs>
            {tab === "one" && <TransportList/>}
            {tab === "two" && <MyFilters setLoadListTab={setMainTab} type="transport"/>}
        </FilterContext.Provider>
    );
}

export default Transport;
