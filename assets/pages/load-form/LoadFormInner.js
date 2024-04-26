import React, {Fragment, useEffect, useState} from "react";
import {useHttp} from "../../hooks/api";
import {
    Autocomplete,
    Button,
    FormControl,
    FormHelperText,
    InputLabel,
    MenuItem,
    Select,
    TextField
} from "@mui/material";
import {useHandleSelectOptions} from "../../hooks/handleSelectOptions";
import AutocompleteAddress from "../../components/AutocompleteAddress";
import CustomTabs from "../../components/custom-tabs/CustomTabs";
import { DatePicker } from '@mui/x-date-pickers';
import dayjs from "dayjs";
import {useForm} from "react-hook-form";
import './form.scss';

function LoadForm() {
    const isAuth = window.authData && window.authData.userId;
    const { request, isLoading, error, status } = useHttp();
    const {
        watch,
        setValue,
        getValues,
        register,
        reset,
        handleSubmit,
        setError,
        formState: { errors, isSubmitting }
    } = useForm({
        defaultValues: {
            fromCityId : '',
            fromAddress: '',
            toCityId: '',
            toAddress: '',
            cargoType: '',
            bodyType: '',
            downloadingType: '',
            downloadingDate: dayjs(),
            downloadingDateStatus: 'ready',
            unloadingType: '',
            priceType: 'negotiable',
            priceWithoutTax: '',
            priceWithTax: '',
            priceCash: '',
            volume: '',
            weight: ''
        }
    });
    const { handleSelectOptions } = useHandleSelectOptions();

    const [requestError, setRequestError] = useState([]);


    const [cargoTypes, setCargoTypes] = useState([]);
    const [priceTypes, setPriceTypes] = useState([]);
    const [bodyTypes, setBodyTypes] = useState([]);
    const [loadingTypes, setLoadingTypes] = useState([]);
    const [downloadingDateStatuses, setDownloadingDateStatuses] = useState([]);

    const setFromCity = cityObj => setValue('fromCityId', cityObj.id);
    const setToCity = cityObj => setValue('toCityId', cityObj.id);
    const setPriceType = value => setValue('priceType', value);

    const saveLoad = async formData => {

        const { data } = await request('/api/load/create', 'POST', {body: {load: formData}});
        console.log(error, status, isLoading);
        if (error && status === 422) {

            setRequestError(error);
        }
        if (status === 200) {
            reset();
        }
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
                <form name="load" onSubmit={handleSubmit(saveLoad)}>
                    <div className="fieldset form__main">
                        <div className="form-group">
                            <div className="label">Описание груза</div>
                            <div className="input">
                                <Autocomplete
                                    sx={{m: 1, width: 300}}
                                    options={cargoTypes}
                                    onChange={(event, newValue) => {
                                        setValue('cargoType', newValue);
                                    }}
                                    size="small"
                                    disablePortal
                                    renderInput={(params) => <TextField  {...params} label="Тип груза"/>}
                                />
                            </div>
                            <div className="input">
                                <TextField
                                    sx={{m: 1, minWidth: 300}}
                                    type="number"
                                    name="volume"
                                    size="small"
                                    label="Объём (на одну машину)"
                                    {...register('volume', {
                                        required: 'Необходимо ввести объем'
                                    })}
                                    error={!!errors.volume}
                                    helperText={errors.volume?.message}
                                    onChange={e => setValue('volume', Number.parseFloat(e.target.value))}/>
                            </div>
                            <div className="input">
                                <TextField
                                    sx={{m: 1, minWidth: 300}}
                                    type="number"
                                    name="weight"
                                    size="small"
                                    label="Вес (на одну машину)"
                                    {...register('weight', {
                                        required: 'Необходимо ввести вес'
                                    })}
                                    error={!!errors.weight}
                                    helperText={errors.weight?.message}
                                    onChange={e => setValue('weight', Number.parseFloat(e.target.value))}/>
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="label">Когда</div>
                            <div className="input">
                                <FormControl sx={{m: 1, minWidth: 300}} size="small"
                                             error={!!errors.downloadingDateStatus}>
                                    <InputLabel>Тип загрузки</InputLabel>
                                    <Select
                                        defaultValue={getValues('downloadingDateStatus')}
                                        label="Тип загрузки"
                                        name="downloadingDateStatus"
                                        {...register('downloadingDateStatus', {
                                            required: 'Необходимо ввести тип загрузки'
                                        })}>
                                        {downloadingDateStatuses.map(status =>
                                            <MenuItem key={status.value} value={status.value}
                                                      selected={watch('downloadingDateStatus') === status.value}>
                                                {status.title}
                                            </MenuItem>
                                        )}
                                    </Select>
                                    {errors.downloadingDateStatus &&
                                        <FormHelperText>{errors.downloadingDateStatus.message}</FormHelperText>}
                                </FormControl>
                            </div>
                            <div className="input">
                                <DatePicker {...register('downloadingDate', {
                                    required: 'Необходимо ввести дату загрузки'
                                })} />
                            </div>
                        </div>

                        <div className="form-group form-margin">
                            <div className="label">Загрузка</div>
                            <div className="input">
                                <AutocompleteAddress
                                    {...register('fromCityId')}
                                    setCityObject={setFromCity}
                                    label="Населенный пункт"/>

                            </div>
                            <div className="input">
                                <TextField
                                    name="fromAddress"
                                    size="small"
                                    error={!!errors.fromAddress}
                                    helperText={errors.fromAddress?.message}
                                    {...register('fromAddress', {
                                        required: 'Введите адрес населенного пункта загрузки',
                                    })}
                                    placeholder="Адрес в населенном пункте"/>
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="label">Разгрузка</div>
                            <div className="input">
                                <AutocompleteAddress
                                    {...register('toCityId')}
                                    setCityObject={setToCity}
                                    label="Населенный пункт"/>
                            </div>
                            <div className="input">
                                <TextField
                                    name="toAddress"
                                    size="small"
                                    error={!!errors.toAddress}
                                    helperText={errors.toAddress?.message}
                                    {...register('toAddress', {
                                        required: 'Введите адрес населенного пункта разгрузки',
                                    })}
                                    placeholder="Адрес в населенном пункте"/>
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="label">Требования к транспортному средству</div>
                            <div>
                                <div className="input">
                                    <FormControl sx={{m: 1, minWidth: 300}} size="small" error={!!errors.bodyType}>
                                        <InputLabel>Кузов</InputLabel>
                                        <Select
                                            label="Кузов"
                                            name="bodyType"
                                            {...register('bodyType', {
                                                required: 'Выбор кузова обязателен'
                                            })}>
                                            {bodyTypes.map(bodyType =>
                                                <MenuItem key={bodyType} value={bodyType}
                                                          selected={watch('bodyType') === bodyType}>
                                                    {bodyType}
                                                </MenuItem>
                                            )}
                                        </Select>
                                        {errors.bodyType && <FormHelperText>{errors.bodyType.message}</FormHelperText>}
                                    </FormControl>
                                </div>
                                <div className="input">
                                    <FormControl sx={{m: 1, minWidth: 300}} size="small"
                                                 error={!!errors.downloadingType}>
                                        <InputLabel>Загрузка</InputLabel>
                                        <Select
                                            {...register('downloadingType', {
                                                required: 'Выберите тип загрузки'
                                            })}
                                            label="Загрузка"
                                            name="downloadingType">
                                            {loadingTypes.map(downloadingType =>
                                                <MenuItem key={downloadingType} value={downloadingType}
                                                          selected={watch(downloadingType) === downloadingType}>
                                                    {downloadingType}
                                                </MenuItem>
                                            )}
                                        </Select>
                                        {errors.downloadingType &&
                                            <FormHelperText>{errors.downloadingType.message}</FormHelperText>}
                                    </FormControl>
                                </div>
                                <div className="input">
                                    <FormControl sx={{m: 1, minWidth: 300}} size="small" error={!!errors.unloadingType}>
                                        <InputLabel>Выгрузка</InputLabel>
                                        <Select
                                            label="Выгрузка"
                                            name="unloadingType"
                                            {...register('unloadingType', {
                                                required: 'Выберите тип разгрузки',
                                            })}>
                                            {loadingTypes.map(unloadingType =>
                                                <MenuItem key={unloadingType} value={unloadingType}
                                                          selected={watch(unloadingType) === unloadingType}>
                                                    {unloadingType}
                                                </MenuItem>
                                            )}
                                            {errors.unloadingType &&
                                                <FormHelperText>{errors.unloadingType.message}</FormHelperText>}
                                        </Select>
                                    </FormControl>
                                </div>
                            </div>
                        </div>

                        <div className="label">Оплата</div>
                        <div className="input">
                            <div className="form-group">
                                <CustomTabs defaultValue="negotiable" tabs={priceTypes} onChange={setPriceType}/>
                            </div>
                            {['negotiable', 'fix'].includes(watch('priceType')) &&
                                <div id="priceBlock">
                                    <div className="form-group">
                                        <span className="label">С НДС, безнал</span>
                                        <TextField
                                            type="number"
                                            name="priceWithoutTax"
                                            size="small"
                                            error={!!errors.priceWithoutTax}
                                            helperText={errors.priceWithoutTax?.message}
                                            {...register('priceWithoutTax')}/> руб
                                    </div>
                                    <div className="form-group">
                                        <span className="label">Без НДС, безнал</span>
                                        <TextField
                                            type="number"
                                            name="priceWithTax"
                                            size="small"
                                            error={!!errors.priceWithTax}
                                            helperText={errors.priceWithTax?.message}
                                            {...register('priceWithTax')}/> руб
                                    </div>
                                    <div className="form-group">
                                        <span className="label">Наличными</span>
                                        <TextField
                                            type="number"
                                            name="priceCash"
                                            size="small"
                                            error={!!errors.priceCash}
                                            helperText={errors.priceCash?.message}
                                            {...register('priceCash')}/> руб
                                    </div>
                                </div>
                            }
                        </div>
                    </div>

                    <div className="buttons">
                        <Button variant="contained" type="submit" className="button"
                                disabled={!isAuth || isSubmitting}>Добавить</Button>
                    </div>
                </form>
            </div>
        </Fragment>
    );
}

export default LoadForm;
