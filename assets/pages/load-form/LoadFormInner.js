import React, {Fragment, useEffect, useState} from "react";
import { useTranslation } from 'react-i18next';
import dayjs from 'dayjs'
import updateLocale from 'dayjs/plugin/updateLocale'
import {useHttp} from "../../hooks/api";
import {
    Autocomplete,
    Button, Checkbox,
    FormControl, FormControlLabel,
    FormHelperText,
    InputLabel,
    MenuItem,
    Select,
    TextField, Tooltip
} from "@mui/material";
import {useHandleSelectOptions, transformToTree} from "../../hooks/handleSelectOptions";
import AutocompleteAddress from "../../components/AutocompleteAddress";
import CustomTabs from "../../components/custom-tabs/CustomTabs";
import { DatePicker } from '@mui/x-date-pickers';
import {useForm} from "react-hook-form";
import './form.scss';
import GreenButton from "../../components/buttons/GreenButton";
import ContactList from "./src/contact-list/ContactList";
import FileUploader from "../../components/file-upload/FileUploader";
import {DownloadingDateStatus, PeriodicityOptions, LoadingTypes, BodyTypes, FixedDurations} from "./src/enums";
import MultipleCheckbox from "./src/multiple-checkbox";
import TreeCheckbox from "./src/tree-checkbox";
import MapModal from "./src/map-modal";
import InfoIcon from "../../components/icons/InfoIcon";
import MapIcon from "../../components/icons/MapIcon";


function LoadForm() {
    const isAuth = window.authData && window.authData.userId;
    const {request, isLoading, error, status} = useHttp();
    const {t} = useTranslation();


    dayjs.extend(updateLocale)
    dayjs.updateLocale('en', {
        weekStart: 1,
    })

    const {
        watch,
        setValue,
        getValues,
        register,
        reset,
        handleSubmit,
        setError,
        formState: {errors, isSubmitting, isSubmitted, isDirty, isValidating}
    } = useForm({
        defaultValues: {
            loading: {
                cargos: {
                    type: '',
                    volume: '',
                    weight: '',
                },
                dates: {
                    type: DownloadingDateStatus.FROM_DATE,
                    periodicity: 'everyday',
                    firstDate: dayjs(),
                    lastDate: dayjs(),
                    time: {
                        type: "bounded",
                        start: null,
                        end: null,
                    }
                },
                location: {
                    cityId: null,
                    address: null,
                    coordinates: {
                        longitude: '',
                        latitude: '',
                    },
                },
            },
            unloading: {
                location:  {
                    cityId: '',
                    address: '',
                    coordinates: {
                        longitude: '',
                        latitude: '',
                    },
                },
                dates: {
                    firstDate: null,
                    time: {
                        type: "bounded",
                        start: null,
                        end: null,
                    },
                },
            },

            truck: {
                bodyTypes: [],
                loadingTypes: [],
                unloadingTypes: [],
                loadType: 'ftl',
                temperatureFrom: null,
                temperatureTo: null,
            },
            payment: {
                type:'negotiable',
                priceWithoutTax: '',
                priceWithTax: '',
                priceCash: '',
                rateWithVatAvailable: true,
                cashAvailable: true,
                rateWithoutVatAvailable: true,
                onCard: false,
                hideCounterOffers: false,
                acceptBidsWithVat: true,
                acceptBidsWithoutVat: false,
                vatPercents: 20,
                bidStep: 0,
                auctionDuration: {
                    fixedDuration: '4h',
                    countFromFirstBid: false,
                }
            },
            contactIds: [],
            note: '',
            files: [],
        }
    });

    const [isOpenMapModal, setIsOpenMapModal] = useState(false);
    const [mapLocation, setMapLocation] = useState({});
    const [isShowLoadingTime, setIsShowLoadingTime] = useState(false);
    const [isShowUnloadingDatetime, setIsShowUnloadingDatetime] = useState(false);
    const [isShowAddTemperature, setIsShowAddTemperature] = useState(false);
    const [isShowAddFiles, setIsShowAddFiles] = useState(false);

    const closeMapModal = () => setIsOpenMapModal(false);
    const openMapModal = location => {
        setMapLocation(location);
        setIsOpenMapModal(true);
    }


    register('loading.cargos.type', {
        required: t('validation.cargoType'),
    });

    register('truck.bodyTypes', {
        required: true,
    });

    register('contactIds', {
        required: true,
    });

    register('loading.dates.firstDate', {
        required: t('validation.downloadingDate')
    });

    register('unloading.dates.firstDate');


    const [daysAfterLoading, setDaysAfterLoading] = useState(0);

    const updateDaysAfterLoading = value => {
        setDaysAfterLoading(value)
    }

    const updateDownloadingDate = value => {
        setValue('loading.dates.firstDate', value);
    }

    useEffect(() => {
        const currentDate = getValues('loading.dates.firstDate');

        setValue('loading.dates.lastDate', currentDate.add(daysAfterLoading, "day"));

    }, [watch('loading.dates.firstDate'), daysAfterLoading]);


    const [files, setFiles] = useState([]);
    const allowedExtensions = ['.pdf', '.doc', '.docx', '.xml', '.xlsx', '.xls', '.ods', '.odt', '.rar', '.zip', '.mp3', '.aac', '.wma', '.jpeg', '.jpg', '.tiff', '.bmp', '.gif', '.png', '.jp2', '.raw'];

    useEffect(() => {
        setValue('files', files);
    }, [files]);


    const [availableContacts, setAvailableContacts] = useState([]);
    const [cargoTypes, setCargoTypes] = useState([]);
    const [priceTypes, setPriceTypes] = useState([]);
    const [bodyTypes, setBodyTypes] = useState([]);
    const [loadingTypes, setLoadingTypes] = useState([]);
    const [downloadingDateStatuses, setDownloadingDateStatuses] = useState([]);

    const setFromCity = cityObj => {
        setValue('loading.location.cityId', cityObj.id);
        setValue('loading.location.coordinates.longitude', cityObj.lon);
        setValue('loading.location.coordinates.latitude', cityObj.lat);
    }
    const setToCity = cityObj => {
        setValue('unloading.location.cityId', cityObj.id);
        setValue('unloading.location.coordinates.longitude', cityObj.lon);
        setValue('unloading.location.coordinates.latitude', cityObj.lat);
    }
    const setPriceType = value => setValue('payment.type', value);


    const saveLoad = async formData => {
        formData.loading.cargos.weight = Number.parseFloat(formData.loading.cargos.weight);
        formData.loading.cargos.volume = Number.parseFloat(formData.loading.cargos.volume);
        const {data} = await request('/api/load/create', 'POST', {body: formData});
        console.log(error, status, isLoading);
        if (error && status === 422) {

            console.log(error);
        }
        if (status === 200) {
            reset();
        }
    }


    const [bodyTypeValue, setBodyTypeValue] = useState([]);
    const changeBodyTypeValue = value => {
        setBodyTypeValue(prev => {
            if (prev.length === 0 && value.length) {
                setValue('truck.unloadingTypes', [LoadingTypes.DEFAULT_LOADING_TYPE]);
                setValue('truck.loadingTypes', [LoadingTypes.DEFAULT_LOADING_TYPE]);
            }
            return value;
        });

        if (value.filter(type => BodyTypes.HAS_TEMPERATURE.includes(type)).length) {
            setIsShowAddTemperature(true);
        } else {
            setIsShowAddTemperature(false);
        }

        setValue('truck.bodyTypes', value, {
            shouldValidate: isSubmitting && isDirty,
            shouldTouch: true,
            shouldDirty: true,
        });
    }

    const { handleSelectOptions } = useHandleSelectOptions();
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
                const options = data.cargoTypes.map((item, index) => {
                    return {
                        label : item,
                        id: index,
                    }
                });
                setCargoTypes(options);
            }

            if (data.priceTypes) {
                setPriceTypes(handleSelectOptions(data.priceTypes));
            }
            if (data.availableContacts) {
                setAvailableContacts(data.availableContacts);
                setValue('contactIds', data.availableContacts.map(el => el.id))
            }
            if (data.bodyTypes) {
                const list = transformToTree(data.bodyTypes, 'typeId', 'parentTypeId')
                    .sort((a, b) => a.position - b.position);

                setBodyTypes([...list]);
            }
            if (data.loadingTypes) {
                const options = data.loadingTypes.map((item, index) => {
                    return {
                        title: item.name,
                        value: item.typeId,
                    }
                });

                setLoadingTypes(options);
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
                    <div>{t('notAuth')}</div>
                </div>
            }

            <div className={!isAuth ? 'overlay-box' : ''}>
                <MapModal isOpen={isOpenMapModal} onClose={closeMapModal} location={mapLocation}/>
                <div className="form__container">
                    <div className="form__main">
                        <form name="load" onSubmit={handleSubmit(saveLoad)}>
                            <div className="fieldset">
                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="flex">
                                            <div className="form__label text-bold">{t('block.cargo')}</div>
                                            <Autocomplete
                                                sx={{width: 232}}
                                                options={cargoTypes}
                                                onChange={(event, newValue) => {
                                                    setValue('loading.cargos.type', newValue.id, {shouldValidate: true});
                                                }}
                                                size="small"
                                                disablePortal
                                                renderInput={(params) => <TextField  {...params}
                                                                                     error={!!errors.loading?.cargos?.cargoType}
                                                                                     helperText={errors.loading?.cargos?.cargoType?.message}
                                                                                     label={t('label.cargoType')}/>}
                                            />

                                            <TextField
                                                sx={{width: 100}}
                                                size="small"
                                                type="number"
                                                label={t('label.volume')}
                                                {...register('loading.cargos.volume', {
                                                    required: t('validation.volume')
                                                })}
                                                error={!!errors.loading?.cargos?.volume}
                                                helperText={errors.loading?.cargos?.volume?.message}
                                                onChange={e => setValue('loading.cargos.volume', Number.parseFloat(e.target.value), {shouldValidate: true})}/>

                                            <TextField
                                                sx={{width: 100}}
                                                size="small"
                                                type="number"
                                                label={t('label.weight')}
                                                {...register('loading.cargos.weight', {
                                                    required: t('validation.weight')
                                                })}
                                                error={!!errors.loading?.cargos?.weight}
                                                helperText={errors.loading?.cargos?.weight?.message}
                                                onChange={e => setValue('loading.cargos.weight', Number.parseFloat(e.target.value))}/>
                                        </div>
                                    </div>
                                </div>

                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="flex">
                                            <div className="form__label text-bold">{t('block.when')}</div>

                                            <FormControl sx={{width: 200}} size="small"
                                                         error={!!errors.loading?.dates?.type}>
                                                <InputLabel>{t('label.downloadingDateStatus')}</InputLabel>
                                                <Select
                                                    defaultValue={getValues('loading.dates.type')}
                                                    label={t('label.downloadingDateStatus')}
                                                    name="downloadingDateStatus"
                                                    {...register('loading.dates.type', {
                                                        required: t('validation.downloadingDateStatus')
                                                    })}>
                                                    {downloadingDateStatuses.map(status =>
                                                        <MenuItem key={status.value} value={status.value}
                                                                  selected={watch('loading.dates.type') === status.value}>
                                                            {status.title}
                                                        </MenuItem>
                                                    )}
                                                </Select>
                                                {errors.loading?.dates?.type &&
                                                    <FormHelperText>{errors.loading?.dates?.type?.message}</FormHelperText>}
                                            </FormControl>

                                            {watch('loading.dates.type') === DownloadingDateStatus.FROM_DATE &&
                                                <Fragment>
                                                    <DatePicker
                                                        minDate={dayjs()}
                                                        name="loading.dates.firstDate"
                                                        sx={{width: 200}}
                                                        defaultValue={getValues('loading.dates.firstDate')}
                                                        slotProps={{textField: {size: 'small'}}}
                                                        onChange={value => updateDownloadingDate(value)}
                                                    />
                                                    <span>+</span>
                                                    <Select
                                                        size="small"
                                                        defaultValue={0}
                                                        onChange={e => updateDaysAfterLoading(e.target.value)}>
                                                        {[...Array(10).keys()].map(option =>
                                                            <MenuItem key={option} value={option} selected={daysAfterLoading === option}>
                                                                {option} {t('dayShort')}
                                                            </MenuItem>
                                                        )}
                                                    </Select>
                                                    <span className="description">
                                                        {t('to')} {watch('loading.dates.lastDate').format()}{t('periodicityDescription')}
                                                    </span>
                                                </Fragment>
                                            }
                                            {watch('loading.dates.type') === DownloadingDateStatus.PERMANENTLY &&
                                                <Fragment>
                                                    <div>
                                                        <Select
                                                            size="small"
                                                            defaultValue={getValues('loading.dates.periodicity')}
                                                            {...register('loading.dates.periodicity')}>
                                                            {PeriodicityOptions.map(option =>
                                                                <MenuItem key={option.value} value={option.value} selected={watch('loading.dates.periodicity') === option.value}>
                                                                    {t(option.title)}
                                                                </MenuItem>
                                                            )}
                                                        </Select>
                                                    </div>
                                                    <span className="periodicity-description">
                                                        {t('to')} {dayjs().add(30, "day").format()}{t('periodicityDescription')}
                                                    </span>
                                                </Fragment>
                                            }
                                            {watch('loading.dates.type') === DownloadingDateStatus.REQUEST &&
                                                <span className="periodicity-description">
                                                        {t('to')} {dayjs().add(30, "day").format()}{t('periodicityDescription')}
                                                </span>
                                            }
                                        </div>
                                    </div>
                                </div>

                                <div className="form__block">
                                    <div className="relative">
                                        <div className="route__container">
                                            <div className="route form__item flex">
                                                <div className="route__point"></div>
                                                <div className="form__label text-bold">{t('label.load')}</div>
                                                <div className="flex flex-center">
                                                    <div className="input">
                                                        <AutocompleteAddress
                                                            {...register('loading.location.cityId')}
                                                            setCityObject={setFromCity}
                                                            label={t('label.locality')}/>

                                                    </div>
                                                    <div className="input">
                                                        <TextField
                                                            size="small"
                                                            error={!!errors.loading?.location?.address}
                                                            helperText={errors.loading?.location?.address?.message}
                                                            {...register('loading.location.address', {
                                                                required: t('validation.fromAddress'),
                                                            })}
                                                            placeholder={t('placeholder.address')}/>
                                                    </div>
                                                    <div className="open-map" onClick={() => openMapModal(watch('loading.location.coordinates'))}>
                                                        <MapIcon/>
                                                    </div>
                                                </div>
                                            </div>

                                            {[DownloadingDateStatus.PERMANENTLY, DownloadingDateStatus.FROM_DATE].includes(watch('loading.dates.type')) &&
                                                <div className="route form__item flex route__withoutBottom">
                                                    <div className="route__inner">
                                                        {isShowLoadingTime
                                                            ?
                                                            <div className="datetime">
                                                                <div className="datetime__inner">
                                                                    <div className="datetime__label">
                                                                        <Tooltip title={t('hideField')} placement="top">
                                                                            <div className="close-button form__close"
                                                                                 onClick={() => setIsShowLoadingTime(false)}></div>
                                                                        </Tooltip>
                                                                        {t('label.loadTime')}
                                                                    </div>
                                                                    <div className="datetime__content">
                                                                        <div className="datetime__fields">
                                                                            <div className="datetime__item datetime__item-first timeInputStart">
                                                                                <input type="time" {...register('loading.dates.time.start')}/>
                                                                            </div>
                                                                            <div className="datetime__item datetime__item-first">
                                                                                <input type="time" {...register('loading.dates.time.end')}/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            :
                                                            <div className="chips-button">
                                                                <GreenButton onClick={() => setIsShowLoadingTime(true)}>
                                                                    {t('label.loadTime')}
                                                                </GreenButton>
                                                            </div>
                                                        }
                                                    </div>
                                                </div>
                                            }

                                            <div className="route form__item flex">
                                                <div className="route__point"></div>
                                                <div className="form__label text-bold">{t('label.downloading')}</div>
                                                <div className="flex flex-center">
                                                    <div className="input">
                                                        <AutocompleteAddress
                                                            {...register('unloading.location.cityId')}
                                                            setCityObject={setToCity}
                                                            label={t('label.locality')}/>
                                                    </div>
                                                    <div className="input">
                                                        <TextField
                                                            name="toAddress"
                                                            size="small"
                                                            error={!!errors.unloading?.location?.address}
                                                            helperText={errors.unloading?.location?.address?.message}
                                                            {...register('unloading.location.address', {
                                                                required: t('validation.toAddress'),
                                                            })}
                                                            placeholder={t('placeholder.address')}/>
                                                    </div>
                                                    <div className="open-map" onClick={() => openMapModal(watch('unloading.location.coordinates'))}>
                                                        <MapIcon/>
                                                    </div>
                                                </div>
                                            </div>

                                            {[DownloadingDateStatus.PERMANENTLY, DownloadingDateStatus.FROM_DATE].includes(watch('loading.dates.type')) &&
                                                <div className="flex route form__item route__withoutBottom">
                                                    <div className="route__inner">
                                                        {isShowUnloadingDatetime
                                                            ?
                                                            <div className="datetime">
                                                                <div className="datetime__inner">
                                                                    <div className="datetime__label">
                                                                        <Tooltip title={t('hideField')} placement="top">
                                                                            <div className="close-button form__close"
                                                                                 onClick={() => setIsShowUnloadingDatetime(false)}></div>
                                                                        </Tooltip>
                                                                        {t('label.unloadingDateAndTime')}
                                                                    </div>
                                                                    <div className="datetime__content">
                                                                        <div className="datetime__fields">
                                                                            <DatePicker
                                                                                minDate={dayjs()}
                                                                                label={t('chooseDate')}
                                                                                slotProps={{textField: {size: 'small'}}}
                                                                                onChange={value => setValue('unloading.dates.firstDate', value)}
                                                                            />
                                                                            <div className="datetime__item datetime__item-first">
                                                                                <input type="time" {...register('unloading.dates.time.start')}/>
                                                                            </div>
                                                                            <div className="datetime__item">
                                                                                <input type="time" {...register('unloading.dates.time.end')}/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            :
                                                            <div className="chips-button">
                                                                <GreenButton
                                                                    onClick={() => setIsShowUnloadingDatetime(true)}>
                                                                    {t('label.unloadingDateAndTime')}
                                                                </GreenButton>
                                                            </div>
                                                        }
                                                    </div>
                                                </div>
                                            }
                                        </div>
                                    </div>
                                </div>

                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="flex">
                                            <div className="car__block">
                                                <div className="form__label text-bold">{t('label.body')}</div>
                                                <div className={`car__selector  ${errors.truck?.bodyTypes ? 'error' : ''}`}>
                                                    <TreeCheckbox
                                                        options={bodyTypes}
                                                        itemText="name"
                                                        itemValue="typeId"
                                                        value={watch('truck.bodyTypes')}
                                                        onChange={changeBodyTypeValue}/>
                                                </div>
                                            </div>

                                            <div className="car__block">
                                                <div className="form__label">{t('label.load')}</div>
                                                <div className="car__selector">
                                                    <MultipleCheckbox
                                                        disabled={!watch('truck.bodyTypes').length}
                                                        options={loadingTypes}
                                                        itemText="title"
                                                        itemValue="value"
                                                        value={watch('truck.loadingTypes')}
                                                        onChange={value => setValue('truck.loadingTypes', value)}/>
                                                </div>
                                            </div>
                                            <div className="car__block">
                                                <div className="form__label">{t('label.unloading')}</div>
                                                <div className="car__selector">
                                                    <MultipleCheckbox
                                                        disabled={!watch('truck.bodyTypes').length}
                                                        options={loadingTypes}
                                                        itemText="title"
                                                        itemValue="value"
                                                        value={watch('truck.unloadingTypes')}
                                                        onChange={value => setValue('truck.unloadingTypes', value)}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex">
                                            <div className="form__label">{t('label.load')}</div>
                                            <div>
                                                <div className="loading">
                                                    <label className="radio">
                                                        <input type="radio" {...register('truck.loadType')} value="ftl"/>
                                                        <span>
                                                            <span>{t('ftl')}</span>
                                                            <Tooltip title={t('ftlDescription')} placement="top">
                                                                <span>
                                                                    <InfoIcon/>
                                                                </span>
                                                            </Tooltip>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div className="loading">
                                                    <label className="radio">
                                                        <input type="radio" {...register('truck.loadType')} value="dont-care"/>
                                                        <span>{t('ltl')}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {isShowAddTemperature &&
                                        <div className="form__inner">
                                            <div className="flex flex-end">
                                                <div className="form__label">{t('temperature')}</div>
                                                <div className="flex">
                                                    <div className="flex flex-center">
                                                        <span>{t('from')}</span>
                                                        <TextField
                                                            sx={{width: 100}}
                                                            size="small"
                                                            {...register('truck.temperatureFrom')}
                                                            placeholder="°C"/>
                                                    </div>
                                                    <div className="flex flex-center">
                                                        <span>{t('to')}</span>
                                                        <TextField
                                                            sx={{width: 100}}
                                                            size="small"
                                                            {...register('truck.temperatureTo')}
                                                            placeholder="°C"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    }
                                </div>

                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="flex">
                                            <CustomTabs defaultValue="negotiable" tabs={priceTypes} onChange={setPriceType}/>
                                        </div>

                                        {['negotiable', 'fix'].includes(watch('payment.type')) &&
                                            <Fragment>
                                                <div className="form__label text-bold">{t('label.bid')}</div>
                                                {watch('payment.type') === 'negotiable' &&
                                                    <p>{t('negotiablePriceType')}</p>
                                                }
                                                {watch('payment.type') === 'fix' &&
                                                    <p>{t('fixPriceType')}</p>
                                                }
                                                {isSubmitted && !watch('payment.priceWithoutTax') && !watch('payment.priceWithTax') && !watch('payment.priceCash') &&
                                                    <div className="text-description text-red">{t('validation.bid')}</div>
                                                }

                                                <div className="flex">
                                                    <span className="form__label">{t('withVatNonCash')}</span>
                                                    <TextField
                                                        type="number"
                                                        size="small"
                                                        {...register('payment.priceWithoutTax')}/> {t('ruble')}
                                                </div>
                                                <div className="flex">
                                                    <span className="form__label">{t('withoutVatNonCash')}</span>
                                                    <TextField
                                                        type="number"
                                                        size="small"
                                                        {...register('payment.priceWithTax')}/> {t('ruble')}
                                                </div>
                                                <div className="flex">
                                                    <span className="form__label">{t('cash')}</span>
                                                    <TextField
                                                        type="number"
                                                        size="small"
                                                        {...register('payment.priceCash')}/> {t('ruble')}
                                                </div>
                                                <div className="flex">
                                                    <div className="form__label"></div>
                                                    <div className="checkbox loading">
                                                        <label className="checkbox__wrapper">
                                                            <input type="checkbox" {...register('payment.onCard')}/>
                                                            <span>{t('onCard')}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </Fragment>
                                        }
                                        {watch('payment.type') === 'request' &&
                                            <Fragment>
                                                <p>{t('requestPriceType')}</p>
                                                <div className="flex">
                                                    <div className="form-label">{t('canBeOffered')}</div>
                                                    <div>
                                                        <div className="checkbox payment__row">
                                                            <label className="checkbox__wrapper">
                                                                <input type="checkbox"{...register('payment.rateWithVatAvailable')}/>
                                                                <span>{t('withVatNonCash')}</span>
                                                            </label>
                                                        </div>
                                                        <div className="checkbox payment__row">
                                                            <label className="checkbox__wrapper">
                                                                <input type="checkbox" {...register('payment.rateWithoutVatAvailable')}/>
                                                                <span>{t('withoutVatNonCash')}</span>
                                                            </label>
                                                        </div>
                                                        <div className="checkbox payment__row">
                                                            <label className="checkbox__wrapper">
                                                                <input type="checkbox"{...register('payment.cashAvailable')}/>
                                                                <span>{t('cash')}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Fragment>
                                        }
                                        {['negotiable', 'fix', 'request'].includes(watch('payment.type')) &&
                                            <div className="flex counterOffer">
                                                <div className="form__label">{t('counterOffers')}</div>
                                                <div className="checkbox loading">
                                                    <label className="checkbox__wrapper">
                                                        <input type="checkbox" {...register('payment.hideCounterOffers')}/>
                                                        <span>{t('visiblyOnlyToYou')}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        }
                                        {watch('payment.type') === 'auction' &&
                                            <Fragment>
                                                <p>{t('auctionPriceType')}</p>
                                                <div className="flex payment__row">
                                                    <div className="form__label">{t('startingBid')}</div>
                                                    <div>
                                                        <div className="payment__item">
                                                            <Select
                                                                size="small"
                                                                defaultValue={getValues('payment.acceptBidsWithVat')}
                                                                {...register('payment.acceptBidsWithVat')}>
                                                                <MenuItem value={true} selected={watch('payment.acceptBidsWithVat')}>
                                                                    {t('withVat')}
                                                                </MenuItem>
                                                                <MenuItem value={false} selected={!watch('payment.acceptBidsWithVat')}>
                                                                    {t('withoutVat')}
                                                                </MenuItem>
                                                            </Select>
                                                        </div>
                                                        <div className="payment__item">
                                                            <TextField
                                                                sx={{width:100}}
                                                                size="small"
                                                                {...register('payment.startRate') }/>
                                                        </div>

                                                        <span className="payment__item">{t('ruble')}</span>
                                                    </div>
                                                </div>

                                                {watch('payment.acceptBidsWithVat') &&
                                                    <div className="flex payment__row">
                                                        <div className="form__label"></div>
                                                        <div className="flex flex-center">
                                                            <div className="checkbox payment__row">
                                                                <label className="checkbox__wrapper">
                                                                    <input
                                                                        type="checkbox"{...register('payment.acceptBidsWithoutVat')}/>
                                                                    <span>{t('acceptRatesWithoutVAT')}</span>
                                                                </label>
                                                            </div>
                                                            {watch('payment.acceptBidsWithoutVat') &&
                                                                <div className="flex flex-center">
                                                                    <TextField
                                                                        sx={{width: 100}}
                                                                        size="small"
                                                                        {...register('payment.vatPercents')}/>
                                                                    <div className="description">
                                                                        {t('carriersWillBeAbleToChoose')}
                                                                    </div>
                                                                </div>
                                                            }
                                                        </div>
                                                    </div>
                                                }
                                                <div className="flex payment__row">
                                                    <div className="form__label">{t('step')}</div>
                                                    <div className="flex flex-center">
                                                        <TextField
                                                            sx={{width:180}}
                                                            size="small"
                                                            {...register('payment.bidStep') }/>
                                                        <div className="description">
                                                            {t('rateReduced')}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="flex">
                                                    <div className="form__label">{t('tradesDuration')}</div>
                                                    <div className="payment__item">
                                                        <Select
                                                            size="small"
                                                            defaultValue={getValues('payment.auctionDuration.fixedDuration')}
                                                            {...register('payment.auctionDuration.fixedDuration')}>
                                                            {FixedDurations.map((option, index) =>
                                                                <MenuItem key={index} value={option.value}
                                                                          selected={watch('payment.auctionDuration.fixedDuration') === option.value}>
                                                                    {t(option.title)}
                                                                </MenuItem>
                                                            )}
                                                        </Select>
                                                    </div>
                                                    <div className="payment__item">
                                                        <Select
                                                            size="small"
                                                            defaultValue={getValues('payment.auctionDuration.countFromFirstBid')}
                                                            {...register('payment.auctionDuration.countFromFirstBid')}>
                                                            <MenuItem value={false} selected={!watch('payment.auctionDuration.countFromFirstBid')}>
                                                                    {t('fixedDurations.countFromFirstBid.false')}
                                                            </MenuItem>
                                                            <MenuItem value={true} selected={watch('payment.auctionDuration.countFromFirstBid')}>
                                                                    {t('fixedDurations.countFromFirstBid.true')}
                                                            </MenuItem>
                                                        </Select>
                                                    </div>
                                                </div>
                                            </Fragment>
                                        }
                                    </div>
                                </div>

                                <div className="form__block form__block-last">
                                    <div className="form__item flex">
                                        <div className="form__label"></div>
                                        <div className={`${errors.contactIds ? 'text-red' : ''}`}>
                                            {t('contactIndicate')}
                                        </div>
                                    </div>
                                    <div className="form__item flex">
                                        <div className="form__label text-bold">{t('contacts')}</div>
                                        <ContactList
                                            options={availableContacts}
                                            selectedContacts={watch('contactIds')}
                                            setSelectedContacts={ids => setValue('contactIds', ids)}
                                        />
                                    </div>
                                    <div className="form__item flex">
                                        <div className="form__label text-bold">{t('note')}</div>
                                        <div className="note">
                                            <TextField
                                                className="note__item"
                                                size="small"
                                                {...register('note')}/>
                                        </div>
                                    </div>
                                    {isShowAddFiles &&
                                        <div className="form__item flex">
                                            <div className="form__label">
                                                <div className="relative">
                                                    <Tooltip title={t('hideField')} placement="top">
                                                        <div className="close-button form__close" onClick={() => setIsShowAddFiles(false)}></div>
                                                    </Tooltip>
                                                    {t('photosOfCargoAndDocuments')}
                                                </div>
                                            </div>

                                            <FileUploader
                                                entity="load"
                                                files={files}
                                                allowedMaxFiles="10"
                                                allowedExtensions={allowedExtensions}
                                                multiple="true"
                                                setFiles={setFiles}/>
                                        </div>
                                    }
                                    {!isShowAddFiles &&
                                        <div className="form__item flex">
                                            <div className="form__label text-bold">{t('add')}</div>
                                            <div className="input">
                                                <GreenButton onClick={() => setIsShowAddFiles(true)}>{t('photo')}</GreenButton>
                                            </div>
                                        </div>
                                    }
                                </div>
                            </div>

                            <div className="buttons">
                                <Button variant="contained" type="submit" className="button" disabled={!isAuth || isSubmitting}>
                                    {t('add')}
                                </Button>
                            </div>
                        </form>
                    </div>
                    <div className="form__sidebar">
                        <div className="sidebar__content">
                            <div className="fieldset"></div>
                        </div>
                    </div>
                </div>
            </div>
        </Fragment>
    );
}

export default LoadForm;
