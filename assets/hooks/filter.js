import {useState} from "react";

export const useFilter = () => {
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

    return {filter, setFilter, clearFilter, changeFilterAddresses}
}
