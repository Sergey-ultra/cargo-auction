import React, {Fragment, useEffect, useState} from 'react';
import {Button, MenuItem, Select, Tab, Tabs} from "@mui/material";
import {useHttp} from "../../hooks/api";
import {setQuery} from "../../hooks/queryParams";
import Pagination from "../../components/common/Pagination";
import LoadItem from "../../components/LoadItem";
import SendBidModal from "../../components/SendBidModal";
import SaveFilterModal from "../../components/SaveFilterModal";
import AuthModal from "../../components/AuthModal";

function LoadList({ filter, setFilter, clearFilter,changeFilterAddresses }) {
    const { request, isLoading, error, clearError } = useHttp();
    const userId = window?.authData?.userId;

    const isMy = window.location.pathname.match(/\/profile\/load-list/);



    const changeFilter = event => {
        setFilter({...filter, [event.target.name]: event.target. value})
    }


    const [orderOptions, setOrderOptions] = useState([]);
    const [perPageOptions, setPerPageOptions] = useState([]);


    const [list, setList] = useState([]);
    const [totalCount, setTotalCount] = useState(0);
    const [orderBy, setOrderBy] = useState('');
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

        const { data } = await request('/api/load-list', 'GET', {params});
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
            setOrderOptions(data.orderOptions);
            setPerPageOptions(data.perPageOptions);
        }
        fetchLists();
    },[])

    useEffect(() => {
        fetchLoadList();
    },[filter, page, perPage]);


    return (
        <Fragment>
            <SendBidModal handleClose={closeSendBidModal} isOpen={isOpenSendBidModal} currentLoadId={currentLoadId}/>
            <SaveFilterModal handleClose={closeSaveFilterModal} isOpen={isOpenSaveFilterModal} saveFilter={saveFilter}/>
            <AuthModal isOpen={isOpenAuthModal} onClose={closeAuthModal}/>

            <form className="box" id="filter">
                <div className="filter__row">
                    <div className="filter__block">
                        <div className="filter__element">
                            <label className="filter__label">Откуда</label>
                            <input type="text" className="filter__input" id="fromAddressInput" name="fromAddress"
                                   value={filter.fromAddress} onChange={changeFilter}/>
                            <div id="fromAddressSuggest">
                                <ul className="suggest-list"></ul>
                            </div>
                        </div>
                        <div className="filter__element filter__element-radius">
                            <label className="filter__label">Радиус</label>
                            <input type="text" className="filter__input" id="fromRadius" name="fromRadius"
                                   placeholder="км"
                                   disabled={filter.fromAddress === ''} value={filter.fromRadius}
                                   onChange={changeFilter}/>
                        </div>
                        <div className="filter__swap" onClick={changeFilterAddresses}>
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.99 11L3 15l3.99 4v-3H14v-2H6.99v-3zM21 9l-3.99-4v3H10v2h7.01v3L21 9z"></path>
                                <path d="M0 0h24v24H0z" fill="none"></path>
                            </svg>
                        </div>
                        <div className="filter__element">
                            <label className="filter__label">Куда</label>
                            <input type="text" className="filter__input" id="toAddressInput" name="toAddress"
                                   value={filter.toAddress} onChange={changeFilter}/>
                            <div id="toAddressSuggest">
                                <ul className="suggest-list"></ul>
                            </div>
                        </div>
                        <div className="filter__element filter__element-radius">
                            <label className="filter__label">Радиус</label>
                            <input type="text" className="filter__input" id="toRadius" name="toRadius" placeholder="км"
                                   disabled={filter.toAddress === ''} value={filter.toRadius} onChange={changeFilter}/>
                        </div>
                    </div>
                    <div className="filter__block filter__block-right">
                        <div className="filter__element filter__minMax">
                            <label className="filter__label">Вес, т</label>
                            <div className="filter__block">
                                <input type="text" className="filter__input" name="weightMin" value={filter.weightMin}
                                       onChange={changeFilter}/>
                                <input type="text" className="filter__input" name="weightMax" value={filter.weightMax}
                                       onChange={changeFilter}/>
                            </div>
                        </div>
                        <div className="filter__element filter__minMax">
                            <label className="filter__label">Объем, м<sup>3</sup></label>
                            <div className="filter__block">
                                <input type="text" className="filter__input" name="volumeMin" value={filter.volumeMin}
                                       onChange={changeFilter}/>
                                <input type="text" className="filter__input" name="volumeMax" value={filter.volumeMax}
                                       onChange={changeFilter}/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                                {/*<Select*/}
                                {/*    name="orderBy"*/}
                                {/*    id="orderBy"*/}
                                {/*    value={orderBy}*/}
                                {/*    label="orderBy"*/}
                                {/*    onChange={e => setOrderBy(e.target.value)}*/}
                                {/*>*/}
                                {/*    {orderOptions.map((orderOption, orderOptionKey) =>*/}
                                {/*       <MenuItem key={orderOptionKey} value={orderOptionKey} selected={orderBy === orderOptionKey}>{orderOption}</MenuItem>*/}
                                {/*    )}*/}
                                {/*</Select>*/}
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
                        {list.map(load =>
                            <LoadItem
                                key={load.id}
                                load={load}
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
                                    <MenuItem key={key} value={key} selected={perPage === option}>{option}</MenuItem>
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

