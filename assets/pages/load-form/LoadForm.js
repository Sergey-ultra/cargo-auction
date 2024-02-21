import React, {Fragment, useEffect, useState} from "react";
import {useHttp} from "../../hooks/api";
import {FormControl, MenuItem, Select, TextField} from "@mui/material";
import {useHandleSelectOptions} from "../../hooks/handleSelectOptions";


function LoadForm() {
    const isAuth = window.authData && window.authData.userId;
    const { request, isLoading } = useHttp();
    const { handleSelectOptions} = useHandleSelectOptions();

    const [cargoTypes, setCargoTypes] = useState([]);
    const [packageTypes, setPackageTypes] = useState([]);
    const [bodyTypes, setBodyTypes] = useState([]);
    const [loadingTypes, setLoadingTypes] = useState([]);
    const [downloadingDateStatuses, setDownloadingDateStatuses] = useState([]);

    const [load, setLoad] = useState({
        cargoType: '',
        volume: '',
    });

    const fillLoad = e => setLoad({...load, [[e.target.name]]: e.target.value})
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
        if (data.packageTypes) {
            setPackageTypes(data.packageTypes);
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

    useEffect(() => fetchFormLists(),[])

    return (
        <Fragment>
            {!isAuth &&
                <div className="alert">
                    <div>Для продолжения необходимо авторизоваться на сайте.</div>
                </div>
            }

        <div className={!isAuth ? 'overlay-box' : ''}>
            <form name="order">
                <div className="legend">Описание груза</div>
                 <div className="fieldset">
                     <div className="form-item">
                         <div className="label">Тип груза</div>
                         <div className="input">
                             {/*{errors['cargoType'] && <span>{ errors['cargoType'] }</span>}*/}

                             <FormControl sx={{ m: 1 }} variant="standard">
                                 <Select
                                     name="cargoType"
                                     id="orderBy"
                                     value={load.cargoType}
                                     label="orderBy"
                                     onChange={fillLoad}>
                                     {cargoTypes.map((cargoType) =>
                                         <MenuItem key={cargoType} value={cargoType} selected={load.cargoType === cargoType}>{cargoType}</MenuItem>
                                     )}
                                 </Select>
                             </FormControl>
                         </div>
                     </div>
    {/*                 <div className="form-item">*/}
    {/*                     <div className="label">Упаковка</div>*/}
    {/*                     <div className="input">*/}
    {/*                         */}
    {/*                         <select name="packageType" id="">*/}
    {/*                             {% for key, type in packageTypes %}*/}
    {/*                                 {% if type.children is not defined %}*/}
    {/*                                 <option value="{{ key }}">{{ type }}</option>*/}
    {/*    //                             {% else %}*/}
    {/*    //                             <optgroup label="{{ type.value }}">*/}
    {/*    //                                 {% for childKey, childType in type.children %}*/}
    {/*    //                                 <option value="{{ childKey }}">{{ childType }}</option>*/}
    {/*    //                                 {% endfor %}*/}
    {/*    //                             </optgroup>*/}
    {/*    //                             {% endif %}*/}
    {/*//*/}
    {/*//                             {% endfor %}*/}
    {/*//                         </select>*/}
    {/*//                     </div>*/}
    {/*//                 </div>*/}
                     <div className="form-item">
                         <div className="label">Объём (на одну машину)</div>
                         <div className="input">
                             <TextField type="number" name="volume" size="small" value={ load.volume } onChange={fillLoad}/>
                         </div>
                     </div>
                     <div className="form-item">
                         <div className="label">Вес (на одну машину)</div>
                         <div className="input">
                             <TextField type="number" name="weight"  size="small" value={ load.weight } onChange={fillLoad}/>
                         </div>
                     </div>
                     <div className="form-item">
                         <div className="label">Температурный режим</div>
                         <div className="input">
                             <input type="number" name="temperatureModeFrom" placeholder="в ℃" min="-30" max="30" step="1"/>
                         </div>
                         -
                         <div className="input">
                             <input type="number" name="temperatureModeTo" placeholder="в ℃" min="-30" max="30" step="1"  value=""/>
                         </div>
                     </div>
                 </div>

                 <div className="legend">Когда</div>
                 <div className="fieldset">
                     <div className="form-item">
                         <div className="label">Дата загрузки</div>
                         <div className="input">
                             <FormControl sx={{ m: 1 }} variant="standard">
                                 <Select
                                     name="downloadingDateStatus"
                                     id="downloadingDateStatus"
                                     value={load.downloadingDateStatus}
                                     onChange={fillLoad}>
                                     {downloadingDateStatuses.map(status =>
                                         <MenuItem key={status} value={status} selected={load.downloadingDateStatus === status}>{status}</MenuItem>
                                     )}
                                 </Select>
                             </FormControl>
                         </div>
                     </div>
                 </div>

                 <div className="legend">Требования к транспортному средству</div>
                 <div className="fieldset">
                     <div className="form-item">
                         <div className="label">Кузов*</div>
                         <div className="input">
                             <FormControl sx={{ m: 1 }} variant="standard">
                                 <Select
                                     name="bodyType"
                                     id="bodyType"
                                     value={load.bodyType}
                                     onChange={fillLoad}>
                                     {bodyTypes.map(bodyType =>
                                         <MenuItem key={bodyType} value={bodyType} selected={load.bodyType === bodyType}>{bodyType}</MenuItem>
                                     )}
                                 </Select>
                             </FormControl>
                         </div>
                     </div>
                     <div className="form-item">
                         <div className="label">Загрузка</div>
                         <div className="input">
                             <FormControl sx={{ m: 1 }} variant="standard">
                                 <Select
                                     name="downloadingType"
                                     value={load.downloadingType}
                                     onChange={fillLoad}>
                                     {loadingTypes.map(downloadingType =>
                                         <MenuItem key={downloadingType} value={downloadingType} selected={load.downloadingType === downloadingType}>{downloadingType}</MenuItem>
                                     )}
                                 </Select>
                             </FormControl>
                         </div>
                     </div>

                     <div className="form-item">
                         <div className="label">Выгрузка</div>
                         <div className="input">
                             <FormControl sx={{ m: 1 }} variant="standard">
                                 <Select
                                     name="unloadingType"
                                     value={load.unloadingType}
                                     onChange={fillLoad}>
                                     {loadingTypes.map(unloadingType =>
                                         <MenuItem key={unloadingType} value={unloadingType} selected={load.unloadingType === unloadingType}>{unloadingType}</MenuItem>
                                     )}
                                 </Select>
                             </FormControl>
                         </div>
                     </div>
                 </div>

               <div className="legend">Маршрут</div>
                <div className="fieldset">
                    <div className="form-item">
                        <div className="label">Откуда</div>
                        <div className="input">
                            <TextField type="text" name="fromAddress"  size="small" value={ load.fromAddress } onChange={fillLoad}/>
                        </div>
                    </div>
                    <div className="form-item">
                        <div className="label">Куда</div>
                        <div className="input">
                            <TextField type="text" name="toAddress"  size="small" value={ load.toAddress } onChange={fillLoad}/>
                        </div>
                    </div>
                </div>

                 <div className="legend">Условия перевозки</div>
                 <div className="fieldset">
                     <div className="form-item">
                         <div className="label">Оплата</div>
                         <div className="input">
                             <div id="agreedPriceBlock" className="tab">
                                 <label>
                                     <input type="radio" name="priceType" value="negotiable" checked={ load.priceType === 'negotiable'}/>
                                         Возможен торг
                                 </label>
                                 <label>
                                     <input type="radio" name="priceType" value="fix" checked={ load.priceType === 'fix'}/>
                                         Без торга
                                 </label>
                                 <label>
                                     <input type="radio" name="priceType" value="request" checked={ load.priceType === 'request'}/>
                                         Запрос
                                 </label>
                                 <label>
                                     <input type="radio" name="priceType" value="auction" checked={load.priceType === 'auction'}/>
                                         Торги
                                 </label>
                             </div>

                             <div id="priceBlock">
                                 <div>
                                     <span>С НДС, безнал</span>
                                     <TextField type="number" name="priceWithoutTax"  size="small" value={ load.priceWithoutTax } onChange={fillLoad}/> руб
                                 </div>
                                 <div>
                                     <span>Без НДС, безнал</span>
                                     <TextField type="number" name="priceWithTax"  size="small" value={ load.priceWithTax } onChange={fillLoad}/> руб
                                 </div>
                                 <div>
                                     <span>Наличными</span>
                                     <TextField type="number" name="priceCash"  size="small" value={ load.priceCash } onChange={fillLoad}/> руб
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
