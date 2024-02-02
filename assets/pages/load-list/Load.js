import {Tab, Tabs} from "@mui/material";
import React, {Fragment, useState} from "react";
import LoadList from "./LoadList";
import LoadFilter from "./LoadFilter";

function Load() {
    const [tab, setTab] = useState('one');
    const [filter, setFilter] = useState({
        fromAddress: '',
        fromRadius: '',
        toAddress: '',
        toRadius: '',
        weightMin: '',
        weightMax: '',
        volumeMin: '',
        volumeMax: '',

    });

    const clearFilter = () => setFilter({
        fromAddress: '',
        fromRadius: '',
        toAddress: '',
        toRadius: '',
        weightMin: '',
        weightMax: '',
        volumeMin: '',
        volumeMax: '',

    });

    const changeFilterAddresses = () => {
        if (filter.toAddress || filter.fromAddress) {
            setFilter({
                ...filter,
                fromAddress: filter.toAddress,
                fromRadius: filter.toRadius,
                toAddress: filter.fromAddress,
                toRadius: filter.fromRadius
            });
        }
    }
    const handleTab = (event, newValue) => setTab(newValue);
    const setLoadListTab = () => setTab("one");

    return (
        <Fragment>
            <Tabs
                value={tab}
                onChange={handleTab}
                aria-label="">
                <Tab value="one" label="Найти груз" wrapped/>
                <Tab value="two" label="Мои фильтры"/>
            </Tabs>
            {tab === "one" && <LoadList filter={filter} setFilter={setFilter} clearFilter={clearFilter} changeFilterAddresses={changeFilterAddresses}/>}
            {tab === "two" && <LoadFilter setFilter={setFilter} setLoadListTab={setLoadListTab}/>}

        </Fragment>
    );
}

export default Load;
