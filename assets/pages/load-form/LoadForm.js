import React, {Fragment, useEffect, useState} from "react";
import {useHttp} from "../../hooks/api";
import {Autocomplete, FormControl, InputLabel, MenuItem, Select, TextField} from "@mui/material";
import {useHandleSelectOptions} from "../../hooks/handleSelectOptions";
import AutocompleteAddress from "../../components/AutocompleteAddress";
import CustomTabs from "../../components/custom-tabs/CustomTabs";


function LoadForm() {
    const isAuth = window.authData && window.authData.userId;
    const { request, isLoading } = useHttp();
    const { handleSelectOptions} = useHandleSelectOptions();

    const [cargoTypes, setCargoTypes] = useState([]);
    const [priceTypes, setPriceTypes] = useState([]);
    const [bodyTypes, setBodyTypes] = useState([]);
    const [loadingTypes, setLoadingTypes] = useState([]);
    const [downloadingDateStatuses, setDownloadingDateStatuses] = useState([]);

    const [load, setLoad] = useState({
        cargoType: '',
        volume: '',
        downloadingType: '',
        unloadingType: '',
        fromAddress: '',
        toAddress: '',
        priceType: '',
        priceWithoutTax: '',
        priceWithTax: '',
        priceCash: '',
    });

    const fillLoad = e => setLoad({...load, [[e.target.name]]: e.target.value});
    const setFromAddressValue = value => setLoad({...load, fromAddress: value});
    const setToAddressValue = value => setLoad({...load, toAddress: value});
    const setPriceType = value => setLoad({...load, priceType: value});
    const saveLoad = async e => {
        e.preventDefault();
        const { data } = await request('/api/load/create', 'POST', {body: {load}});
    }


    useEffect(() => {
        const fetchFormLists = async() => {
            const { data } = await request(
                '/api/list',
                'GET',
                {
                    params: {
                        parameters: ['load-form']
                    }
                }
            );
            if (data.cargoTypes) {
                setCargoTypes(data.cargoTypes);
            }

            if (data.priceTypes) {
                setPriceTypes(handleSelectOptions(data.priceTypes));
            }
            if (data.bodyTypes) {
                setBodyTypes(data.bodyTypes);
            }
            if (data.loadingTypes) {
                setLoadingTypes(data.loadingTypes);
            }
            if (data.downloadingDateStatuses) {
                setDownloadingDateStatuses(handleSelectOptions(data.downloadingDateStatuses));
            }
        }
         fetchFormLists();
    },[])

    return (
        <Fragment>
            {!isAuth &&
                <div className="alert">
                    <div>Для продолжения необходимо авторизоваться на сайте.</div>
                </div>
            }

        <div className={!isAuth ? 'overlay-box' : ''}>
            <form name="load" onSubmit={saveLoad}>
                <div className="legend">Описание груза</div>
                <div className="fieldset">
                    <div className="form-item">
                        <div className="label">Описание груза</div>
                        <div>
                            <div className="input">
                                <Autocomplete
                                    sx={{m:1, width: 300}}
                                    options={cargoTypes}
                                    onChange={(event, newValue) => {
                                        setLoad({...load, cargoType: newValue});
                                    }}
                                    size="small"
                                    disablePortal
                                    renderInput={(params) => <TextField  {...params} label="Тип груза"/>}
                                />
                            </div>
                            <div className="input">
                                <TextField sx={{m:1, minWidth: 300}} type="number" name="volume" size="small"
                                           label="Объём (на одну машину)" value={load.volume} onChange={fillLoad}/>
                            </div>
                            <div className="input">
                                <TextField sx={{m:1, minWidth: 300}} type="number" name="weight" size="small"
                                           label="Вес (на одну машину)" value={load.weight} onChange={fillLoad}/>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="legend">Когда</div>
                <div className="fieldset">
                    <div className="form-item">
                        <div className="label">Когда</div>
                        <div className="input">
                            <FormControl sx={{m: 1, minWidth: 300}} size="small">
                                <InputLabel>Тип загрузки</InputLabel>
                                <Select
                                    label="Тип загрузки"
                                    name="downloadingDateStatus"
                                    value={load.downloadingDateStatus}
                                    onChange={fillLoad}>
                                    {downloadingDateStatuses.map(status =>
                                        <MenuItem key={status.value} value={status.value}
                                                  selected={load.downloadingDateStatus === status.value}>{status.title}</MenuItem>
                                    )}
                                </Select>
                            </FormControl>
                        </div>
                    </div>
                </div>

                <div className="legend">Требования к транспортному средству</div>
                <div className="fieldset">
                    <div className="form-item">
                        <div className="label">Требования к транспортному средству</div>
                        <div>
                            <div className="input">
                                <FormControl sx={{m: 1, minWidth: 300}} size="small">
                                    <InputLabel>Кузов</InputLabel>
                                    <Select
                                        label="Кузов"
                                        name="bodyType"
                                        value={load.bodyType}
                                        onChange={fillLoad}>
                                        {bodyTypes.map(bodyType =>
                                            <MenuItem key={bodyType} value={bodyType}
                                                      selected={load.bodyType === bodyType}>{bodyType}</MenuItem>
                                        )}
                                    </Select>
                                </FormControl>
                            </div>
                            <div className="input">
                                <FormControl sx={{m: 1, minWidth: 300}} size="small">
                                    <InputLabel>Загрузка</InputLabel>
                                    <Select
                                        label="Загрузка"
                                        name="downloadingType"
                                        value={load.downloadingType}
                                        onChange={fillLoad}>
                                        {loadingTypes.map(downloadingType =>
                                            <MenuItem key={downloadingType} value={downloadingType}
                                                      selected={load.downloadingType === downloadingType}>{downloadingType}</MenuItem>
                                        )}
                                    </Select>
                                </FormControl>
                            </div>
                            <div className="input">
                                <FormControl sx={{m: 1, minWidth: 300}} size="small">
                                    <InputLabel>Выгрузка</InputLabel>
                                    <Select
                                        label="Выгрузка"
                                        name="unloadingType"
                                        value={load.unloadingType}
                                        onChange={fillLoad}>
                                        {loadingTypes.map(unloadingType =>
                                            <MenuItem key={unloadingType} value={unloadingType}
                                                      selected={load.unloadingType === unloadingType}>{unloadingType}</MenuItem>
                                        )}
                                    </Select>
                                </FormControl>
                            </div>
                        </div>

                    </div>
                </div>

                <div className="legend">Маршрут</div>
                <div className="fieldset">
                    <div className="form-item">
                        <div className="label">Требования к транспортному средству</div>
                        <div>
                            <div className="input">
                                <AutocompleteAddress
                                    value={load.fromAddress}
                                    setAddressValue={setFromAddressValue}
                                    label="Откуда"/>
                            </div>
                            <div className="input">
                                <AutocompleteAddress
                                    value={load.toAddress}
                                    setAddressValue={setToAddressValue}
                                    label="Куда"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="legend">Условия перевозки</div>
                <div className="fieldset">
                <div className="form-item">
                         <div className="label">Оплата</div>
                         <div className="input">
                             <div className="form-item">
                                 <CustomTabs tabs={priceTypes} onChange={setPriceType}/>
                             </div>
                             <div id="priceBlock">
                                 <div className="form-item">
                                     <span className="label">С НДС, безнал</span>
                                     <TextField type="number" name="priceWithoutTax" size="small" value={ load.priceWithoutTax } onChange={fillLoad}/> руб
                                 </div>
                                 <div className="form-item">
                                     <span className="label">Без НДС, безнал</span>
                                     <TextField type="number" name="priceWithTax" size="small" value={ load.priceWithTax } onChange={fillLoad}/> руб
                                 </div>
                                 <div className="form-item">
                                     <span className="label">Наличными</span>
                                     <TextField type="number" name="priceCash" size="small" value={ load.priceCash } onChange={fillLoad}/> руб
                                 </div>
                             </div>

                          </div>
                     </div>
                 </div>

                 <div className="buttons">
                     <button type="submit" className="button" disabled={!isAuth}>Добавить</button>
                 </div>
            </form>
        </div>
    </Fragment>
    );
}

export default LoadForm;
