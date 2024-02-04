import {Tab, Tabs} from "@mui/material";
import React, {useState} from "react";
import LoadList from "./src/LoadList";
import MyFilters from "./src/MyFilters";
import {useFilter} from "../../hooks/filter";
import {FilterContext} from "../../context/filter.context";

function Load() {
    const [tab, setTab] = useState('one');
    const {filter, setFilter, clearFilter, changeFilterAddresses} = useFilter();

    const handleTab = (event, newValue) => setTab(newValue);
    const setLoadListTab = () => setTab("one");

    return (
        <FilterContext.Provider value={{ filter, setFilter, clearFilter, changeFilterAddresses }}>
            <Tabs
                value={tab}
                onChange={handleTab}
                aria-label="">
                <Tab value="one" label="Найти груз" wrapped/>
                <Tab value="two" label="Мои фильтры"/>
            </Tabs>
            {tab === "one" && <LoadList/>}
            {tab === "two" && <MyFilters setLoadListTab={setLoadListTab}/>}
        </FilterContext.Provider>
    );
}

export default Load;
