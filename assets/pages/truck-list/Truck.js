import React, {useState} from 'react';
import {Tab, Tabs} from "@mui/material";
import {FilterContext} from "../../context/filter.context";
import {useFilter} from "../../hooks/filter";
import MyFilters from "../../components/MyFilters";
import TruckList from "./src/TruckList";



function Truck() {
    const [tab, setTab] = useState('one');
    const {filter, setFilter, clearFilter, changeFilterAddresses} = useFilter();
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
            {tab === "one" && <TruckList/>}
            {tab === "two" && <MyFilters setLoadListTab={setMainTab}/>}
        </FilterContext.Provider>
    );
}

export default Truck;
