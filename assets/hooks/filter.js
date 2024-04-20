import {useState} from "react";

export const useFilter = () => {
    const [filter, setFilter] = useState({
        from: {
            id: '',
            name: '',
        },
        fromRadius: '',
        to: {
            id: '',
            name: '',
        },
        toRadius: '',
        weightMin: '',
        weightMax: '',
        volumeMin: '',
        volumeMax: '',
    });

    const clearFilter = () => setFilter({
        from: {},
        fromRadius: '',
        to: {},
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
                from: filter.to,
                fromRadius: filter.toRadius,
                to: filter.from,
                toRadius: filter.fromRadius
            });
        }
    }

    return {filter, setFilter, clearFilter, changeFilterAddresses}
}
