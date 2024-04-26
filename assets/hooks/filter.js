import {useState} from "react";

export const useFilter = () => {
    const initialState = {
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
    };
    const [filter, setFilter] = useState(initialState);

    const clearFilter = () => setFilter(initialState);

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
