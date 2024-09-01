import {Tab, Tabs} from "@mui/material";
import React, {useState} from "react";
import LoadList from "./src/LoadList";
import MyFilters from "../../components/MyFilters";
import {FilterProvider, useFilter} from "../../hooks/filter";
import {FilterContext} from "../../context/filter.context";
import "./cargo-index.css";

function Load() {
    const [tab, setTab] = useState<string>('one');
    const {filter, setFilter, clearFilter, changeFilterAddresses, convertToBackendFilter, convertFromBackendFilter}: FilterProvider = useFilter();

    const handleTab = (event, newValue: string): void => setTab(newValue);
    const setLoadListTab = (): void => setTab("one");

    return (
        <FilterContext.Provider value={{filter, setFilter, clearFilter, changeFilterAddresses, convertToBackendFilter, convertFromBackendFilter}}>
            <Tabs
                value={tab}
                onChange={handleTab}
                aria-label="">
                <Tab value="one" label="Найти груз" wrapped/>
                <Tab value="two" label="Мои фильтры"/>
            </Tabs>
            {tab === "one" && <LoadList/>}
            {tab === "two" && <MyFilters setLoadListTab={setLoadListTab} type="load"/>}
        </FilterContext.Provider>
    );
}

export default Load;
