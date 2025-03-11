import React, {ChangeEvent, SyntheticEvent, useEffect, useState} from "react";
import {Autocomplete, AutocompleteRenderInputParams, TextField} from "@mui/material";
import {useHttp} from "../hooks/api";

export interface City {
    id:  number|null,
    approx: boolean,
    district: string,
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
    initialValue: number|null,
    initialList: City[]
}

function AutocompleteAddress({initialValue, setCityObject, label, initialList = []}: AutocompleteAddressProps) {
    const { request } = useHttp();

    const [localName, setLocalName] = useState<string>( '');
    const [citiesList, setCitiesList] = useState<City[]>([...initialList]);

    const local = window.local || 'RU';

    const getSuggest = async(name: string = ''): Promise<void> => {
        const { data }: {data: City[]} = await request('/api/city/suggest', 'GET', { params: {
            name,
            lang: local,
            type: 1,
        }});

        if (data && Array.isArray(data)) {
            setCitiesList( [...data]);
        }
    }

    const getCityById = async(): Promise<void> => {
        const { data }: { data: City } = await request(`/api/city/by-id/${initialValue}`);
        console.log(data);

        if (typeof data === 'object') {
            setCitiesList( [data]);
            setLocalName(data.name)
        }
    }

    const onFocus = async(): Promise<void> => {
        await getSuggest();
        console.log(citiesList);
    }

    const changeAddressValue = async (e: ChangeEvent<HTMLInputElement>): Promise<void> => {
        setLocalName(e.target.value);
        await getSuggest(e.target.value);
    }

    useEffect(() => {
        if (initialValue) {
            getCityById();
        }
    }, [initialValue]);

    return  <Autocomplete
        sx={{m: 0, minWidth: 232}}
        options={citiesList}
        getOptionLabel={(option: City): string => option.name + ', ' + option.regionName}
        getOptionKey={(option: City): number => option.id}
        onChange={(event: SyntheticEvent<Element>, newValue: City|null): void => {
            if (newValue && newValue.name) {
                setLocalName(newValue.name);
                setCityObject(newValue);
                setCitiesList([]);
            }
        }}
        inputValue={localName}
        size="small"
        disablePortal
        renderInput={(params: AutocompleteRenderInputParams) =>
            <TextField
                {...params}
                label={label}
                onFocus={onFocus}
                onChange={changeAddressValue}/>}
    />
}

export default AutocompleteAddress;
