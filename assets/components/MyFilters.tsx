import React, {useContext, useEffect, useState} from "react";
import {useHttp} from "../hooks/api";
import {Card, CardActionArea} from "@mui/material";
import {FilterContext} from "../context/filter.context";
import {BackendFilter, FilterProvider} from "../hooks/filter";

interface MyFilterOption {
    id: number,
    type: string,
    name: string,
    filter: BackendFilter,
    createdAt: string
}

interface FetchFiltersResponse {
    data: MyFilterOption[]
}

interface MyFiltersProps {
    setLoadListTab: () => void,
    type: string,
}
function MyFilters({ setLoadListTab, type }: MyFiltersProps) {
    const { convertFromBackendFilter }: FilterProvider = useContext(FilterContext);
    const { request, isLoading, error, clearError } = useHttp();
    const [filterList, setFilterList] = useState<MyFilterOption[]>([]);

    const fetchFilters = async(): Promise<void> => {
        const { data }: Promise<FetchFiltersResponse> = await request('/api/load-filter', 'GET', {params: {type}});
        if (data && Array.isArray(data)) {
            setFilterList(data);
        }
    }

    const setCurrentFilter = (filter: MyFilterOption) => {
        convertFromBackendFilter(filter.filter);
        setLoadListTab();
    }

    const openFilterModal = e => e.stopPropagation();


    useEffect(() => {
        fetchFilters();
    }, [])

    return (
        <div className="box">
            {filterList.map((filter: MyFilterOption) =>
                <Card sx={{ maxWidth: 345, padding: 2 }} key={filter.id} onClick={() => setCurrentFilter(filter)}>
                    <CardActionArea>
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <svg fill="#b8b8b8" stroke="#b8b8b8"
                                     data-qa="icon" viewBox="0 0 18 18" width="18" height="18"
                                     preserveAspectRatio="none" x="3" y="3">
                                    <symbol id="ic_lens" viewBox="0 0 19 18">
                                        <path fillRule="evenodd" clipRule="evenodd"
                                              d="M10.864 14.281a7.142 7.142 0 1 0-5.82-3L.347 15.977a1.171 1.171 0 0 0 0 1.665l.015.015c.465.45 1.2.465 1.665 0l4.696-4.696a7.051 7.051 0 0 0 4.14 1.32Zm-5.46-7.14a5.46 5.46 0 1 1 10.92 0 5.46 5.46 0 0 1-10.92 0Z"></path>
                                    </symbol>
                                </svg>
                            </svg>
                        </span>

                        <span>{filter.name}</span>

                        <span onClick={openFilterModal}>
                            <svg height="24" width="24"
                                 className="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-vubbuv" focusable="false"
                                 aria-hidden="true" viewBox="0 0 24 24" data-testid="MoreVertIcon">
                                <path
                                    d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2m0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2m0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2"></path>
                            </svg>
                       </span>
                    </CardActionArea>
                </Card>
            )}
        </div>
    );
}

export default MyFilters;
