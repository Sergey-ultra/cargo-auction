import {useState} from "react";

export interface Filter {
    from: {
        id: string,
        name: string,
    },
    fromRadius: string,
    to: {
        id: string,
        name: string,
    },
    toRadius: string,
    weightMin: string,
    weightMax: string,
    volumeMin: string,
    volumeMax: string,
}

export interface BackendFilter {
    fromAddressId: string,
    fromAddress: string,
    fromRadius: string,
    toAddressId: string,
    toAddress: string,
    toRadius: string,
    weightMin: string,
    weightMax: string,
    volumeMin: string,
    volumeMax: string,
    page: number,
    perPage: number,
    orderBy: string
}

export interface FilterProvider {
    filter: Filter,
    setFilter: (filter: Filter) => void,
    clearFilter: () => void,
    changeFilterAddresses: () => void,
    convertToBackendFilter: (page: number, perPage:  number, orderBy: string) => object,
    convertFromBackendFilter: (backendFilter: BackendFilter) => void,
}

export const useFilter = () => {
    const initialState: Filter = {
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
