import React, {Fragment, useEffect, useState} from 'react';
import {MenuItem, Select} from "@mui/material";
import {useHttp} from "./hooks/api";
import Pagination from "./components/common/Pagination";


function LoadList() {
    const { request, isLoading, error, clearError } = useHttp();
    const userId = window?.authData?.userId;

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

    const [orderOptions, setOrderOptions] = useState([]);
    const [perPageOptions, setPerPageOptions] = useState([]);


    const [list, setList] = useState([]);
    const [totalCount, setTotalCount] = useState(0);
    const [orderBy, setOrderBy] = useState('');
    const [perPage, setPerPage] = useState(10);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);



    useEffect(() => {
        const fetchLists = async() => {
            const { data } = await request('/api/list');
            setOrderOptions(data.orderOptions);
            setPerPageOptions(data.perPageOptions);
        }
        fetchLists();
    },[])

    useEffect(() => {
        const fetchLoadList = async() => {
            const params = Object.assign(filter, {page}, {perPage}, {orderBy});
            const {data} = await request('/api/load-list', 'GET', {params});
            setList(data.list);
            setTotalCount(data.totalCount);
            setLastPage(data.lastPage);
        }
        fetchLoadList();
    },[filter, page, perPage]);

    const changeFilter = event => {
        setFilter({...filter, [event.target.name]: event.target. value})
    }

    return (
        <Fragment>
            <form  className="filter" id="filter">
                <div className="filter__row">
                    <div className="filter__block">
                        <div className="filter__element">
                            <label className="filter__label">Откуда</label>
                            <input type="text" className="filter__input" id="fromAddressInput" name="fromAddress" value={ filter.fromAddress } onChange={changeFilter}/>
                                <div id="fromAddressSuggest">
                                    <ul className="suggest-list"></ul>
                                </div>
                        </div>
                        <div className="filter__element filter__element-radius">
                            <label className="filter__label">Радиус</label>
                            <input type="text" className="filter__input" id="fromRadius" name="fromRadius" placeholder="км"
                                   disabled={ filter.fromAddress === '' } value={ filter.fromRadius } onChange={changeFilter}/>
                        </div>
                        <div className="filter__swap">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.99 11L3 15l3.99 4v-3H14v-2H6.99v-3zM21 9l-3.99-4v3H10v2h7.01v3L21 9z"></path>
                                <path d="M0 0h24v24H0z" fill="none"></path>
                            </svg>
                        </div>
                        <div className="filter__element">
                            <label className="filter__label">Куда</label>
                            <input type="text" className="filter__input" id="toAddressInput" name="toAddress" value={ filter.toAddress } onChange={changeFilter}/>
                                <div id="toAddressSuggest">
                                    <ul className="suggest-list"></ul>
                                </div>
                        </div>
                        <div className="filter__element filter__element-radius">
                            <label className="filter__label">Радиус</label>
                            <input type="text" className="filter__input" id="toRadius" name="toRadius" placeholder="км"
                                   disabled={filter.toAddress === ''} value={ filter.toRadius } onChange={changeFilter}/>
                        </div>
                    </div>
                    <div className="filter__block filter__block-right">
                        <div className="filter__element filter__minMax">
                            <label className="filter__label">Вес, т</label>
                            <div className="filter__block">
                                <input type="text" className="filter__input" name="weightMin" value={ filter.weightMin } onChange={changeFilter}/>
                                <input type="text" className="filter__input" name="weightMax" value={ filter.weightMax } onChange={changeFilter}/>
                            </div>
                        </div>
                        <div className="filter__element filter__minMax">
                            <label className="filter__label">Объем, м<sup>3</sup></label>
                            <div className="filter__block">
                                <input type="text" className="filter__input" name="volumeMin" value={ filter.volumeMin } onChange={changeFilter}/>
                                <input type="text" className="filter__input" name="volumeMax" value={ filter.volumeMax } onChange={changeFilter}/>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="filter__row">
                    <button type="submit" className="button">Найти</button>
                </div>
            </form>
            {list.length > 0 ? (
                <Fragment>
                    <div className="meta">
                         <h2>Найдено {totalCount} грузов</h2>
                         <div className="meta-right">
                             <div className="sort">
                                 <label>Упорядочить по </label>
                                 {/*<Select*/}
                                 {/*    labelId="demo-simple-select-label"*/}
                                 {/*    name="orderBy"*/}
                                 {/*    id="orderBy"*/}
                                 {/*    value={orderBy}*/}
                                 {/*    label="Age"*/}
                                 {/*    onChange={e => setOrderBy(e.target.value)}*/}
                                 {/*>*/}
                                 {/*    {orderOptions.map((orderOptionKey, orderOption) =>*/}
                                 {/*       <MenuItem key={orderOptionKey} value={orderOptionKey} selected={orderBy === orderOptionKey}>{orderOption}</MenuItem>*/}
                                 {/*    )}*/}
                                 {/*</Select>*/}
                             </div>
                             <Pagination page={page} lastPage={lastPage} setPage={setPage}/>
                         </div>
                    </div>


                    <div className="table">
                        <div className="row row__header">
                            <div className="table__item">Транспорт</div>
                            <div className="table__item table__item-price">Маршрут</div>
                            <div className="table__item">Груз</div>
                            <div className="table__item table__item-price">Ставка</div>
                        </div>
                        {list.map(load => (
                            <div className="row" key={load.id}>
                                <div className="table__item">{load.bodyTypeName}</div>
                                <div className="table__item table__item-price text-bold">
                                    {load.fromAddress}
                                    <span>{load.distance} км</span>
                                    <span>{load.toAddress}</span>
                                    {
                                        load.downloadingDateStatus === 'permanently' && <div>Постоянно</div>
                                        || load.downloadingDateStatus === 'ready' &&
                                        <div>готов {load.downloadingDate}.</div>
                                    }
                                </div>
                                <div className="table__item">
                                    {load.weight}т. {load.volume}м3 {load.cargoTypeName}
                                </div>
                                <div className="table__item table__item-price">
                                    {userId && (<span className="text-gray">скрыто</span>)
                                        || ((load.priceType === 'fix' || load.priceType === 'negotiable') &&
                                            <Fragment>
                                                <div>
                                                    {
                                                        load.priceWithoutTax &&
                                                        (<div className="price__item">
                                                            <span
                                                                className="text-bold">{load.priceWithoutTax} руб</span>
                                                            <span className="text-gray"> без НДС </span>
                                                            <span
                                                                className="text-bold">{(load.priceWithoutTax / load.distance).toFixed(2)}</span> руб/км
                                                        </div>)
                                                    }
                                                    {load.priceWithTax &&
                                                        (<div className="price__item">
                                                            <span className="text-bold">{load.priceWithTax} руб</span>
                                                            <span
                                                                className="text-gray"> c НДС </span>
                                                            <span
                                                                className="text-bold">{(load.priceWithTax / load.distance).toFixed(2)}</span> руб/км
                                                        </div>)
                                                    }
                                                </div>
                                                { load.priceType === 'negotiable'
                                                    ? (
                                                    <button className="send-bid" id="sendBid" data-toogle="modalBid"
                                                            data-target="#sendBidModal" data-order-id="{{ load.id }}">
                                                        <svg fill="#ffffff" stroke="#ffffff" stroke-width="0"
                                                             data-qa="icon" viewBox="0 0 15 15" width="15" height="15"
                                                             className="arrow-svg">
                                                            <symbol id="ic_shape" viewBox="0 0 18 14">
                                                                <path
                                                                    d="M17.924 13.557c.028-.175.624-4.377-2.047-7.27-1.64-1.776-4.172-2.75-7.441-2.846L8.419.52a.52.52 0 0 0-.322-.47.603.603 0 0 0-.596.066L.207 5.656A.509.509 0 0 0 0 6.059c0 .156.075.304.205.402L7.5 12.053a.596.596 0 0 0 .597.069.518.518 0 0 0 .323-.47l.017-2.988c7.88-.005 8.44 4.667 8.459 4.86.024.263.204.467.488.476a.514.514 0 0 0 .541-.442Z"></path>
                                                            </symbol>
                                                        </svg>
                                                        <span>Отправить ставку</span>
                                                    </button>
                                                // ({load.bids.count > 0 && (<span>{load.bids.count}</span>)})
                                                    ) : <span className="text-gray">без торга</span>
                                                }
                                            </Fragment>)

                                        || (<span className="text-gray">запрос ставки</span>)
                                    }

                                </div>

                                <div className="table-bottom">
                                    <div className="table__contact">
                                        {userId && (
                                        userId !== load.user.id && (
                                            <Fragment>
                                                <a href={`profile/messages/${load.user.id}?load_id=${load.id}`}>
                                                                        <span>
                                                                            <svg fill="#3a7bbf" stroke="#3a7bbf"
                                                                                 stroke-width="0"
                                                                                 data-qa="icon" viewBox="0 0 15 15"
                                                                                 width="15"
                                                                                 height="15">
                                                                                <symbol id="ic_message"
                                                                                        viewBox="0 0 17 16">
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="m6.5 13-4.327-.022-.242-.001-1.008-.005-.462-.003a.464.464 0 0 1-.323-.793l.329-.324.005-.005.713-.704.172-.17.226-.222A6.452 6.452 0 0 1 0 6.5C0 2.916 2.916 0 6.5 0S13 2.916 13 6.5 10.084 13 6.5 13Zm-2.31-2.012.132-.13L3.094 9.44A4.452 4.452 0 0 1 2 6.5C2 4.02 4.02 2 6.5 2S11 4.02 11 6.5 8.98 11 6.5 11l-2.31-.012ZM14 4c3 2.5 3.273 6.777.855 9.621l1.498 1.508c.304.306.092.836-.335.838L9.755 16A6.651 6.651 0 0 1 5 13.997l1.842.011H6.84C11.945 14.008 15.4 9.007 14 4Z"></path>
                                                                                </symbol>

                                                                            </svg>
                                                                        </span>
                                                    <span>Написать</span>
                                                </a>
                                                <span>{load.user.email}</span>
                                                ({load.user.phones.map(phone => (
                                                <span><a href="tel:{ phone.phone }">{phone.phone}</a></span>))})
                                            </Fragment>
                                        )

                                    ) ||
                                        <Fragment>
                                            <div className="button-empty" data-toogle="modal"
                                                 data-target="#authModal">показать контакты и ставку
                                            </div>
                                            <div>Доступно <b>бесплатно</b> после быстрой регистрации</div>
                                        </Fragment>
                                    }
                                    </div>
                                    <div className="table-right">
                                        {userId === load.user.id && (
                                            <Fragment>
                                                <a href={`/${load.id}/edit`}>
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M13.9687 0.281254L12.1406 2.10938L15.8906 5.85938L17.7187 4.03125C17.9063 3.84375 18 3.60938 18 3.32813C18 3.04688 17.9063 2.8125 17.7187 2.625L15.375 0.281254C15.1875 0.093752 14.9531 3.94788e-06 14.6719 3.96017e-06C14.3906 3.97247e-06 14.1563 0.0937521 13.9687 0.281254ZM3.75 18L14.8125 6.9375L11.0625 3.1875L-1.63918e-07 14.25L0 18L3.75 18Z"
                                                              fill="#8C969D"/>
                                                    </svg>
                                                </a>
                                                <div>
                                                    <svg fill="#478cc8" stroke="#478cc8" stroke-width="0" data-qa="icon"
                                                         width="24" height="24" viewBox="0 0 24 24"
                                                         preserveAspectRatio="none" x="3" y="3.5">
                                                        <symbol id="ic_star-outline" viewBox="0 0 18 17">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                  d="m9 12.537 3.849 2-.735-4.231 3.089-2.975-4.27-.613L9 2.849 7.068 6.718l-4.27.613 3.088 2.975-.735 4.23L9 12.538Zm3.055-7.375L9.69.424a.772.772 0 0 0-1.378 0L5.945 5.162.66 5.92a.761.761 0 0 0-.426 1.303l3.822 3.681-.903 5.202c-.108.623.55 1.098 1.115.805L9 14.453l4.732 2.458c.564.293 1.223-.182 1.115-.805l-.903-5.202 3.822-3.681a.761.761 0 0 0-.426-1.303l-5.285-.758Z"></path>
                                                        </symbol>
                                                    </svg>
                                                </div>
                                            </Fragment>
                                        ) || ''}

                                        <a href={`/load/${load.id}`} className="load-info">
                                            <svg width="24" height="24" viewBox="0 0 24 24">
                                                <svg fill="white" stroke="white" stroke-width="0" data-qa="icon"
                                                     viewBox="0 0 20 20" width="20" height="20"
                                                     preserveAspectRatio="none" x="2" y="2">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M0 10c0-1.38.26-2.679.781-3.896A10.035 10.035 0 0 1 2.92 2.92 10.035 10.035 0 0 1 6.104.78 9.801 9.801 0 0 1 10 0c1.38 0 2.679.26 3.896.781A10.036 10.036 0 0 1 17.08 2.92a10.037 10.037 0 0 1 2.139 3.184A9.802 9.802 0 0 1 20 10c0 1.38-.26 2.679-.781 3.896a10.038 10.038 0 0 1-2.139 3.184 10.038 10.038 0 0 1-3.183 2.139A9.802 9.802 0 0 1 10 20c-1.38 0-2.679-.26-3.896-.781A10.037 10.037 0 0 1 2.92 17.08a10.036 10.036 0 0 1-2.14-3.183A9.802 9.802 0 0 1 0 10Zm1.25 0c0 1.21.228 2.347.684 3.408a8.813 8.813 0 0 0 1.875 2.783 8.811 8.811 0 0 0 2.783 1.875A8.545 8.545 0 0 0 10 18.75c1.21 0 2.347-.228 3.408-.684a8.811 8.811 0 0 0 2.783-1.875 8.811 8.811 0 0 0 1.875-2.783A8.545 8.545 0 0 0 18.75 10c0-1.21-.228-2.347-.684-3.408a8.811 8.811 0 0 0-1.875-2.783 8.813 8.813 0 0 0-2.783-1.875A8.545 8.545 0 0 0 10 1.25c-1.21 0-2.347.228-3.408.684a8.812 8.812 0 0 0-2.783 1.875 8.812 8.812 0 0 0-1.875 2.783A8.545 8.545 0 0 0 1.25 10Z"></path>
                                                    <path
                                                        d="M11.336 8.125v8H8.72v-8h2.616Zm.27-2.839c0 .208-.043.403-.127.586-.084.183-.2.343-.346.481a1.694 1.694 0 0 1-.51.329 1.584 1.584 0 0 1-1.22 0 1.684 1.684 0 0 1-.493-.329 1.519 1.519 0 0 1-.338-.481 1.423 1.423 0 0 1-.122-.586c0-.214.04-.414.122-.6a1.58 1.58 0 0 1 .831-.818 1.634 1.634 0 0 1 1.73.33c.146.14.262.303.346.489.084.185.127.385.127.599Z"></path>
                                                </svg>
                                            </svg>
                                        </a>

                                        {/*<div>доб <span>{{ load.createdAt|date('Y-m-d-H-i') != "now"|date('Y-m-d-H-i') ? load.createdAt|date('d M') : load.createdAt|date('H:i') }}</span></div>*/}
                                        {/*{% if load.updatedAt and load.createdAt != load.updatedAt %}*/}
                                        {/*<div>изм {{ load.updatedAt|date('H:i') }}</div>*/}
                                        {/*{% endif %}*/}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>


                    <div className="table__meta">
                        <Pagination page={page} lastPage={lastPage}/>
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
                </Fragment>)
            : (
                <div>Ничего не нашли</div>
            )}
        </Fragment>
    );
}

export default LoadList;
