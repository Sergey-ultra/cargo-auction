import {useState} from "react";

export interface Filter {
    from: {
        id: number|null,
        name: string,
    },
    fromRadius: string,
    to: {
        id: number|null,
        name: string,
    },
    toRadius: string,
    weightMin: number|null,
    weightMax: number|null,
    volumeMin: number|null,
    volumeMax: number|null,
}

export interface BackendFilter {
    fromAddressId: number|null,
    fromAddress: string,
    fromRadius: string,
    toAddressId: number|null,
    toAddress: string,
    toRadius: string,
    weightMin: number|null,
    weightMax: number|null,
    volumeMin: number|null,
    volumeMax: number|null,
    page: number,
    perPage: number,
    orderBy: string
}

export interface FilterProvider {
    filter: Filter,
    setFilter: (filter: Filter) => void,
    clearFilter: () => void,
    changeFilterAddresses: () => void,
    convertToBackendFilter: (page: number, perPage:  number, orderBy: string) => BackendFilter,
    convertFromBackendFilter: (backendFilter: BackendFilter) => void,
}

export const useFilter = () => {
    const initialState: Filter = {
        from: {
            id: null,
            name: '',
        },
        fromRadius: '',
        to: {
            id: null,
            name: '',
        },
        toRadius: '',
        weightMin: null,
        weightMax: null,
        volumeMin: null,
        volumeMax: null,
    };
    const [filter, setFilter] = useState<Filter>(initialState);

    const clearFilter = () => setFilter(initialState);

    const convertToBackendFilter = (page: number, perPage:  number, orderBy: string): Object => {
        return {
            fromAddressId: filter.from.id,
            fromAddress: filter.from.name,
            fromRadius: filter.fromRadius,
            toAddressId: filter.to.id,
            toAddress: filter.to.name,
            toRadius: filter.toRadius,
            weightMin: filter.weightMin,
            weightMax: filter.weightMax,
            volumeMin: filter.volumeMin,
            volumeMax: filter.volumeMax,
            page,
            perPage,
            orderBy
        };
    }

    const convertFromBackendFilter = (backendFilter: BackendFilter): void => {
        setFilter({
            from: {
                id: backendFilter.fromAddressId,
                name: backendFilter.fromAddress,
            },
            fromRadius: backendFilter.fromRadius,
            to: {
                id: backendFilter.toAddressId,
                name: backendFilter.toAddress,
            },
            toRadius: backendFilter.toRadius,
            weightMin: backendFilter.weightMin,
            weightMax: backendFilter.weightMax,
            volumeMin: backendFilter.volumeMin,
            volumeMax: backendFilter.volumeMax,
        });
    }

    const changeFilterAddresses = (): void => {
        if (filter.to.name || filter.from.name) {
            setFilter({
                ...filter,
                from: filter.to,
                fromRadius: filter.toRadius,
                to: filter.from,
                toRadius: filter.fromRadius
            });
        }
    }

    return {filter, setFilter, clearFilter, changeFilterAddresses, convertToBackendFilter, convertFromBackendFilter}
}
