import React, {ChangeEvent, SyntheticEvent, useState} from "react";
import {Autocomplete, TextField} from "@mui/material";
import {useHttp} from "../hooks/api";

export interface City {
    approx: boolean,
    district: string,
    id:  number,
    lat: number,
    lon: number,
    name: string,
    otherName: string,
    population: number,
    regionName: string,
}

interface AutocompleteAddressProps {
    setCityObject: (cityObj: City) => void,
    label: string,
    value: string,
    initialList: City[]
}

function AutocompleteAddress({value, setCityObject, label, initialList = []}: AutocompleteAddressProps) {
    const { request } = useHttp();

    const [localValue, setLocalValue] = useState<string>(value);
    const [citiesList, setCitiesList] = useState<City[]>([...initialList]);

    const local = window.local || 'RU';

    const getSuggest = async(name: string = ''): Promise<void> => {
        const { data }: {data:City[]} = await request('/api/city-suggest', 'GET', { params: {
            name,
            lang: local,
            type: 1,
        }});

        if (data && Array.isArray(data)) {
            setCitiesList( [...data]);
        }
    }

    const onFocus = async(): Promise<void> => {
        await getSuggest();
        console.log(citiesList);
    }


    const changeAddressValue = async (e: ChangeEvent<HTMLInputElement>): Promise<void> => {
        setLocalValue(e.target.value);
        await getSuggest(e.target.value);
    }

    return  <Autocomplete
        sx={{m: 0, minWidth: 232}}
        options={citiesList}
        getOptionLabel={(option: City): string => option.name + ', ' + option.regionName}
        getOptionKey={(option: City): number => option.id}
        onChange={(event: SyntheticEvent<Element>, newValue: City|null): void => {
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
