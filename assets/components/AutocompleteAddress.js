import React, {useState} from "react";
import {Autocomplete, TextField} from "@mui/material";
import {useHttp} from "../hooks/api";

function AutocompleteAddress({value, setCityObject, label, initialList = []}) {
    const { request } = useHttp();
    const [localValue, setLocalValue] = useState(value);
    const [citiesList, setCitiesList] = useState([...initialList]);

    const local = window.local || 'RU';

    const getSuggest = async(name = '') => {
        const { data } = await request('/api/city-suggest', 'GET', { params: {
            name,
            lang: local,
            type: 1,
        }});

        if (data && Array.isArray(data)) {
            setCitiesList( [...data]);
        }
    }

    const onFocus = async() => {
        await getSuggest();
        console.log(citiesList);
    }


    const changeAddressValue = async (e) => {
        setLocalValue(e.target.value);
        await getSuggest(e.target.value);
    }

    return  <Autocomplete
        sx={{m: 0, minWidth: 232}}
        options={citiesList}
        getOptionLabel={option => option.name + ', ' + option.regionName}
        getOptionKey={option => option.id}
        onChange={(event, newValue) => {
            if (newValue && newValue.name) {
                setLocalValue(newValue.name);
                setCityObject(newValue);
                setCitiesList([]);
            }
        }}
        inputValue={localValue}
        size="small"
        disablePortal
        renderInput={(params) =>
            <TextField
                {...params}
                label={label}
                name="fromAddress"
                onFocus={onFocus}
                onChange={changeAddressValue}/>}
    />
}

export default AutocompleteAddress;
