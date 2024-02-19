import React, {Fragment, useContext, useEffect, useState} from 'react';
import {Button, FormControl, Menu, MenuItem, Select, Tab, Tabs} from "@mui/material";
import {useHttp} from "../../../hooks/api";
import {setQuery} from "../../../hooks/queryParams";
import Pagination from "../../../components/common/Pagination";
import TruckItem from "./TruckItem";
import SendBidModal from "../../../components/SendBidModal";
import SaveFilterModal from "../../../components/SaveFilterModal";
import AuthModal from "../../../components/AuthModal";
import Filter from "../../../components/Filter";
import {FilterContext} from "../../../context/filter.context";

function TruckList() {
    const { filter, setFilter, clearFilter, changeFilterAddresses } = useContext(FilterContext);
    const { request, isLoading, error, clearError } = useHttp();
    const userId = window?.authData?.userId;

    const isMy = window.location.pathname.match(/\/profile\/load-list/);

    const [loadOptions, setLoadOptions] = useState([]);
    const [perPageOptions, setPerPageOptions] = useState([]);


    const [list, setList] = useState([]);
    const [totalCount, setTotalCount] = useState(0);
    const [orderBy, setOrderBy] = useState('created_at');
    const [perPage, setPerPage] = useState(10);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);

    const [isOpenSaveFilterModal, setOpenSaveFilterModal] = useState(false);
    const openSaveFilterModal = () => setOpenSaveFilterModal(true);
    const closeSaveFilterModal = () => setOpenSaveFilterModal(false);

    const saveFilter = async(name) => {
        const { data } = await request('/api/load-filter', 'POST', { body: {name, filter}});
        if (data.status === 'ok') {
            closeSaveFilterModal();
        }
    }

    const isShowSaveFilter = !!filter.fromAddress

    const [isOpenSendBidModal, setOpenSendBidModal] = useState(false);
    const [currentLoadId, setCurrentLoadId] = useState(null);
    const openSendBidModal = loadId => {
        setCurrentLoadId(loadId);
        setOpenSendBidModal(true);
    }
    const closeSendBidModal = () => setOpenSendBidModal(false);



    const [isOpenAuthModal, setOpenAuthModal] = useState(false);
    const openAuthModal = () => setOpenAuthModal(true);
    const closeAuthModal = () => setOpenAuthModal(false);

    const fetchLoadList = async() => {
        const params = Object.assign(filter, {page}, {perPage}, {orderBy});
        if (isMy) {
            params.isMy = true;
        }

        setQuery(params);

        const { data } = await request('/api/transport', 'GET', {params});
        setList(data.list);
        setTotalCount(data.totalCount);
        setLastPage(data.lastPage);
    }

    const updateLoadList = () => {
        setPage(1);
        fetchLoadList();
    }


    useEffect(() => {
        const fetchLists = async() => {
            const { data } = await request('/api/list');

            let loadParams = [];
            for (let [name, value] of Object.entries(data.loadOptions)) {
                loadParams.push({
                    title: value,
                    value: name,
                })
            }
            setLoadOptions(loadParams);
            setPerPageOptions(data.perPageOptions);
        }
        fetchLists();
    },[])

    useEffect(() => {
        fetchLoadList();
    },[filter, page, perPage, orderBy]);




    return (
        <Fragment>
            <SendBidModal handleClose={closeSendBidModal} isOpen={isOpenSendBidModal} currentLoadId={currentLoadId}/>
            <SaveFilterModal handleClose={closeSaveFilterModal} isOpen={isOpenSaveFilterModal} saveFilter={saveFilter}/>
            <AuthModal isOpen={isOpenAuthModal} onClose={closeAuthModal}/>

            <Filter/>

            <div className="row row-end">
                <Button onClick={clearFilter}>Очистить</Button>
                {isShowSaveFilter && <Button onClick={openSaveFilterModal}>Сохранить как фильтр</Button>}
                <Button variant="contained" className="button button-primary button-small" onClick={updateLoadList}>Найти</Button>
            </div>

            {list.length > 0
                ? <Fragment>
                    <div className="meta">
                        <h2>Найдено {totalCount} грузов</h2>
                        <div className="meta-right">
                            <div className="sort">
                                <label>Упорядочить по </label>
                                <FormControl sx={{ m: 1 }} variant="standard">
                                    <Select
                                        name="orderBy"
                                        id="orderBy"
                                        value={orderBy}
                                        label="orderBy"
                                        onChange={e => setOrderBy(e.target.value)}>
                                        {loadOptions.map((loadOption) =>
                                            <MenuItem key={loadOption.value} value={loadOption.value} selected={orderBy === loadOption.value}>{loadOption.title}</MenuItem>
                                        )}
                                    </Select>
                                </FormControl>
                            </div>
                            <Pagination page={page} lastPage={lastPage} setPage={setPage}/>
                        </div>
                    </div>


                    <div className="table">
                        <div className="table__row table__row-header">
                            <div className="table__item">Транспорт</div>
                            <div className="table__item table__item-price">Маршрут</div>
                            <div className="table__item">Груз</div>
                            <div className="table__item table__item-price">Ставка</div>
                        </div>
                        {list.map(truck =>
                            <TruckItem
                                key={truck.id}
                                truck={truck}
                                openSendBidModal={openSendBidModal}
                                openAuthModal={openAuthModal}/>
                        )}
                    </div>


                    <div className="table__meta">
                        <Pagination page={page} lastPage={lastPage} setPage={setPage}/>
                        <div>
                            <span>Выводить строк</span>
                            <Select
                                id="perPage"
                                value={perPage}
                                label="Age"
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

export default TruckList;

