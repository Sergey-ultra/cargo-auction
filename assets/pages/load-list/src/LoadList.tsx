import React, {Fragment, useContext, useEffect, useState} from 'react';
import {Button, FormControl, MenuItem, Select} from "@mui/material";
import {useHttp} from "../../../hooks/api";
import {setQuery} from "../../../hooks/queryParams";
import Pagination from "../../../components/pagination/Pagination";
import LoadItem from "./LoadItem";
import SendBidModal from "../../../components/SendBidModal";
import SaveFilterModal from "../../../components/SaveFilterModal";
import AuthModal from "../../nav/src/AuthModal";
import Filter from "../../../components/filter/Filter";
import {FilterContext} from "../../../context/filter.context";
import {useHandleSelectOptions} from "../../../hooks/handleSelectOptions";
import {BackendFilter, FilterProvider} from "../../../hooks/filter";
import {isAuthFunc} from "../../../hooks/isAuthFunc";
import {Option} from "../../load-form/types";

function LoadList(callback, deps) {
    const { filter, clearFilter, convertToBackendFilter }: FilterProvider = useContext(FilterContext);
    const { request, isLoading, error, clearError } = useHttp();
    const { handleSelectOptions} = useHandleSelectOptions();
    const isAuth: boolean = isAuthFunc();

    const isMy = window.location.pathname.match(/\/profile\/load-list/);

    const [loadOptions, setLoadOptions] = useState<Option[]>([]);
    const [perPageOptions, setPerPageOptions] = useState([]);


    const [list, setList] = useState([]);
    const [totalCount, setTotalCount] = useState(0);
    const [orderBy, setOrderBy] = useState<string>('created_at');
    const [perPage, setPerPage] = useState<number>(10);
    const [page, setPage] = useState<number>(1);
    const [lastPage, setLastPage] = useState<number>(1);

    const [isOpenSaveFilterModal, setOpenSaveFilterModal] = useState(false);
    const openSaveFilterModal = () => setOpenSaveFilterModal(true);
    const closeSaveFilterModal = () => setOpenSaveFilterModal(false);

    const [addingComment, setAddingComment] = useState(null);
    const toggleAddingComment = id => {
        if (id === addingComment) {
            setAddingComment(null);
        } else {
            setAddingComment(id);
        }
    }

    const saveComment = async (object: Comment): Promise<number|null> => {
        let response;
        if (object.id) {
             response = await request(`/api/load/comment/${object.id}`, 'PUT', { body: object });
        } else {
             response = await request('/api/load/comment', 'POST', { body: object });
        }
        const { data } = response;
        if (data) {
            return data.id;
        }
       return null;
    }

    const deleteComment = async (id: number): Promise<void> => {
        await request(`/api/load/comment/${id}`, 'DELETE');
    }

    const saveFilter = async(name: string): Promise<void> => {
        const localFilter: BackendFilter = convertToBackendFilter(page, perPage, orderBy);
        const { data } = await request('/api/load-filter', 'POST', { body: {name, filter: localFilter, type: 'load'}});
        if (data.status === 'ok') {
            closeSaveFilterModal();
        }
    }


    const [isOpenSendBidModal, setOpenSendBidModal] = useState(false);
    const [currentLoadId, setCurrentLoadId] = useState<number|null>(null);
    const openSendBidModal = (loadId: number) => {
        setCurrentLoadId(loadId);
        setOpenSendBidModal(true);
    }
    const closeSendBidModal = () => setOpenSendBidModal(false);

    const addBidToLoad = bid => {
        const newList = list.map(load => {
            if (load.id === currentLoadId) {
                load.bids.count++;
                load.bids.bids.push(bid);

                if (bid.bid > load.bids.maxValue) {
                    load.bids.maxValue = bid.bid;
                }

                return load;
            }
            return load;
        })

        setList(newList);
    }



    const [isOpenAuthModal, setOpenAuthModal] = useState(false);
    const openAuthModal = () => setOpenAuthModal(true);
    const closeAuthModal = () => setOpenAuthModal(false);



    const fetchLoadList = async() => {
        const params = convertToBackendFilter(page, perPage, orderBy);
        if (isMy) {
            params.isMy = true;
        }

        setQuery(params);

        const { data } = await request('/api/load-list', 'GET', {params});

        if (data.list && Array.isArray(data.list)) {
            setList(data.list);
        }

        setTotalCount(data.totalCount);
        setLastPage(data.lastPage);
    }

    const updateLoadList = async () => {
        setPage(1);
        await fetchLoadList();
    }

    useEffect(() => {
        const fetchLists = async() => {
            const params = {
                parameters: ['load']
            };
            const { data } = await request('/api/list', 'GET', {params});
            if (data.options) {
                setLoadOptions(handleSelectOptions(data.options));
            }

            if (data.perPageOptions) {
                setPerPageOptions(data.perPageOptions);
            }
        };
        fetchLists()
    },[])

    useEffect(() => {
        fetchLoadList();
        console.log(filter);
    },[filter, page, perPage, orderBy]);




    return (
        <Fragment>
            <SendBidModal
                addBidToLoad={addBidToLoad}
                handleClose={closeSendBidModal}
                isOpen={isOpenSendBidModal}
                currentLoadId={currentLoadId}/>
            <SaveFilterModal handleClose={closeSaveFilterModal} isOpen={isOpenSaveFilterModal} saveFilter={saveFilter}/>
            <AuthModal isOpen={isOpenAuthModal} onClose={closeAuthModal} showMode='login'/>

            <Filter/>

            <div className="row row-end">
                <Button onClick={clearFilter}>Очистить</Button>
                {isAuth && (!!filter.from.id || !!filter.to.id) && <Button onClick={openSaveFilterModal}>Сохранить как фильтр</Button>}
                <Button variant="contained" className="button button-primary button-small" onClick={updateLoadList}>Найти</Button>
            </div>

            {list.length > 0
                ? <Fragment>
                    <div className="meta">
                        <h2>Найдено {totalCount} грузов</h2>
                        <div className="meta-right">
                            <div className="sort">
                                <label>Упорядочить по </label>
                                    <Select
                                        size="small"
                                        value={orderBy}
                                        label="orderBy"
                                        onChange={e => setOrderBy(e.target.value)}>
                                        {loadOptions.map((loadOption) =>
                                            <MenuItem key={loadOption.value} value={loadOption.value} selected={orderBy === loadOption.value}>{loadOption.title}</MenuItem>
                                        )}
                                    </Select>
                            </div>
                            {lastPage > 1 && <Pagination page={page} lastPage={lastPage} setPage={setPage}/>}
                        </div>
                    </div>


                    <div className="table">
                        <div className="table__row table__row-header">
                            <div className="table__item table__item-direction">Направление</div>
                            <div className="table__item">Транспорт</div>
                            <div className="table__item">Груз</div>
                            <div className="table__item table__item-route">Маршрут</div>
                            <div className="table__item table__item-bid">Ставка</div>
                        </div>
                        {list.map(load =>
                            <LoadItem
                                key={load.id}
                                load={load}
                                openSendBidModal={openSendBidModal}
                                openAuthModal={openAuthModal}
                                addingComment={addingComment}
                                toggleAddingComment={toggleAddingComment}
                                saveComment={saveComment}
                                deleteComment={deleteComment}/>
                        )}
                    </div>


                    <div className="table__meta">
                        {lastPage > 1 && <Pagination page={page} lastPage={lastPage} setPage={setPage}/>}
                        <div className="perPage">
                            <span>Выводить строк</span>
                            <Select
                                size="small"
                                value={perPage}
                                onChange={e => setPerPage(e.target.value)}
                            >
                                {perPageOptions.map((option, key) =>
                                    <MenuItem key={key} value={option} selected={perPage === option}>{option}</MenuItem>
                                )}
                            </Select>
                        </div>
                    </div>
                </Fragment>
                : <div>Ничего не нашли</div>}
        </Fragment>
    );
}

export default LoadList;

