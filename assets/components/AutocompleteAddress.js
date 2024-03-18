import React, {useState} from "react";
import {Autocomplete, TextField} from "@mui/material";
import {useHttp} from "../hooks/api";

function AutocompleteAddress({value, setCityObject, label}) {
    const { request, isLoading, error, clearError } = useHttp();
    const [localValue, setLocalValue] = useState(value);
    const [citiesList, setCitiesList] = useState([]);

    const getSuggest = async(name) => {
        const { data } = await request('/api/city-suggest', 'GET', { params: { name }});
        if (data && Array.isArray(data)) {
            setCitiesList( [...data]);
        }
    }


    const changeAddressValue = async (e) => {
        setLocalValue(e.target.value);

        if (e.target.value.length > 1) {
            await getSuggest(e.target.value);
        }
    }

    return  <Autocomplete
        sx={{m: 0, minWidth: 300}}
        options={citiesList}
        getOptionLabel={option => option.name}
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
            <TextField  {...params} label={label} name="fromAddress" onChange={changeAddressValue}/>}
    />
}

export default AutocompleteAddress;
