import React, {Fragment, useEffect, useState} from "react";
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
import {useHandleSelectOptions} from "../../hooks/handleSelectOptions";
import AutocompleteAddress from "../../components/AutocompleteAddress";
import CustomTabs from "../../components/custom-tabs/CustomTabs";
import { DatePicker } from '@mui/x-date-pickers';
import dayjs from "dayjs";
import {useForm} from "react-hook-form";
import './form.scss';
import GreenButton from "../../components/buttons/GreenButton";
import ContactList from "./src/ContactList";
import FileUploader from "../../components/file-upload/FileUploader";
import {DownloadingDateStatus, PeriodicityOptions} from "./src/enums";

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
        formState: { errors, isSubmitting, isSubmitted }
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
            downloadingDateStatus: DownloadingDateStatus.FROM_DATE,
            periodicity: 'everyday',
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

    const [isShowLoadingTime, setIsShowLoadingTime] = useState(false);
    const [isShowUnloadingDatetime, setIsShowUnloadingDatetime] = useState(false);
    const [isShowAddPhoto, setIsShowAddPhoto] = useState(false);

    const obj = register('cargoType', {
        required: 'Укажите тип груза'
    });
    const [files, setFiles] = useState([]);
    const allowedExtensions = ['.pdf', '.doc', '.docx', '.xml', '.xlsx', '.xls', '.ods', '.odt', '.rar', '.zip', '.mp3', '.aac', '.wma', '.jpeg', '.jpg', '.tiff', '.bmp', '.gif', '.png', '.jp2', '.raw'];

    const closeFileUploader = () => {
        setIsShowAddPhoto(false);
    }

    const datePickerStyle = {
        height: 28,
    };

    const [availableContacts, setAvailableContacts] = useState([]);

    const [cargoTypes, setCargoTypes] = useState([]);
    const [priceTypes, setPriceTypes] = useState([]);
    const [bodyTypes, setBodyTypes] = useState([]);
    const [loadingTypes, setLoadingTypes] = useState([]);
    const [downloadingDateStatuses, setDownloadingDateStatuses] = useState([]);

    const setFromCity = cityObj => setValue('fromCityId', cityObj.id);
    const setToCity = cityObj => setValue('toCityId', cityObj.id);
    const setPriceType = value => setValue('priceType', value);

    const saveLoad = async formData => {

        const { data } = await request('/api/load/create', 'POST', { body: formData });
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
            if (data.availableContacts) {
                setAvailableContacts(data.availableContacts);
                setValue('contact_ids', data.availableContacts.map(el => el.id))
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
                <div className="form__container">
                    <div className="form__main">
                        <form name="load" onSubmit={handleSubmit(saveLoad)}>
                            <div className="fieldset">
                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="form-group">
                                            <div className="form__label text-bold">Груз</div>
                                            <div className="input">
                                                <Autocomplete
                                                    sx={{width: 232}}
                                                    options={cargoTypes}
                                                    onChange={(event, newValue) => {
                                                        setValue('cargoType', newValue);
                                                    }}
                                                    size="small"
                                                    disablePortal
                                                    renderInput={(params) => <TextField  {...params}
                                                                                         error={!!errors.cargoType}
                                                                                         helperText={errors.cargoType?.message}
                                                                                         label="Тип груза"/>}
                                                />
                                            </div>
                                            <div className="input">
                                                <TextField
                                                    sx={{width: 84}}
                                                    type="number"
                                                    name="volume"
                                                    size="small"
                                                    label="Объём (на одну машину)"
                                                    {...register('volume', {
                                                        required: 'Укажите объем'
                                                    })}
                                                    error={!!errors.volume}
                                                    helperText={errors.volume?.message}
                                                    onChange={e => setValue('volume', Number.parseFloat(e.target.value))}/>
                                            </div>
                                            <div className="input">
                                                <TextField
                                                    sx={{width: 84}}
                                                    type="number"
                                                    name="weight"
                                                    size="small"
                                                    label="Вес (на одну машину)"
                                                    {...register('weight', {
                                                        required: 'Укажите вес'
                                                    })}
                                                    error={!!errors.weight}
                                                    helperText={errors.weight?.message}
                                                    onChange={e => setValue('weight', Number.parseFloat(e.target.value))}/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="form-group">
                                            <div className="form__label text-bold">Когда</div>
                                            <div className="input">
                                                <FormControl sx={{minWidth: 232}} size="small" error={!!errors.downloadingDateStatus}>
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
                                            {watch('downloadingDateStatus') === DownloadingDateStatus.FROM_DATE &&
                                                <div>
                                                    <DatePicker
                                                        defaultValue={getValues('downloadingDate')}
                                                        slotProps={{ textField: { size: 'small' } }}
                                                        {...register('downloadingDate', {
                                                            required: 'Необходимо ввести дату загрузки'
                                                        })} />
                                                </div>
                                            }
                                            {watch('downloadingDateStatus') === DownloadingDateStatus.PERMANENTLY &&
                                                <Fragment>
                                                    <div>
                                                        <Select
                                                            size="small"
                                                            defaultValue={getValues('periodicity')}
                                                            name="periodicity"
                                                            {...register('periodicity')}>
                                                            {PeriodicityOptions.map(option =>
                                                                <MenuItem key={option.value} value={option.value} selected={watch('periodicity') === option.value}>
                                                                    {option.title}
                                                                </MenuItem>
                                                            )}
                                                        </Select>
                                                    </div>
                                                    <span className="periodicity-description">
                                                        до 04 июл., затем переместится в архив
                                                    </span>
                                                </Fragment>
                                            }
                                            {watch('downloadingDateStatus') === DownloadingDateStatus.REQUEST &&
                                                <span className="periodicity-description">
                                                        до 04 июл., затем переместится в архив
                                                </span>
                                            }
                                        </div>
                                    </div>
                                </div>

                                <div className="form__block">
                                <div className="relative">
                                        <div className="route__container">
                                            <div className="route form__item form-group">
                                                <div className="route__point"></div>
                                                <div className="form__label text-bold">Загрузка</div>
                                                <div className="form-group">
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
                                            </div>

                                            {[DownloadingDateStatus.PERMANENTLY, DownloadingDateStatus.FROM_DATE].includes(watch('downloadingDateStatus')) &&
                                                <div className="route form__item form-group route__withoutBottom">
                                                    <div className="route__inner">
                                                        {isShowLoadingTime
                                                            ?
                                                            <div className="datetime">
                                                                <div className="datetime__inner">
                                                                    <div className="datetime__label">
                                                                        <Tooltip title="Скрыть поле" placement="top">
                                                                            <div className="close-button form__close"
                                                                                 onClick={() => setIsShowLoadingTime(false)}></div>
                                                                        </Tooltip>
                                                                        Время загрузки
                                                                    </div>
                                                                    <div className="datetime__content">
                                                                        <div className="datetime__fields">
                                                                            <div
                                                                                className="datetime__item datetime__item-first timeInputStart">
                                                                                <input
                                                                                    type="time" {...register('loading_time_from')}/>
                                                                            </div>
                                                                            <div
                                                                                className="datetime__item datetime__item-first">
                                                                                <input
                                                                                    type="time" {...register('loading_time_to')}/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            :
                                                            <div className="chips-button">
                                                                <GreenButton onClick={() => setIsShowLoadingTime(true)}>
                                                                    Время загрузки
                                                                </GreenButton>
                                                            </div>
                                                        }
                                                    </div>
                                                </div>
                                            }

                                            <div className="route form__item form-group">
                                                <div className="route__point"></div>
                                                <div className="form__label text-bold">Разгрузка</div>
                                                <div className="form-group">
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
                                            </div>

                                            {[DownloadingDateStatus.PERMANENTLY, DownloadingDateStatus.FROM_DATE].includes(watch('downloadingDateStatus')) &&
                                                <div className="form-group route form__item route__withoutBottom">
                                                    <div className="route__inner">
                                                        {isShowUnloadingDatetime
                                                            ?
                                                            <div className="datetime">
                                                                <div className="datetime__inner">
                                                                    <div className="datetime__label">
                                                                        <Tooltip title="Скрыть поле" placement="top">
                                                                            <div className="close-button form__close"
                                                                                 onClick={() => setIsShowUnloadingDatetime(false)}></div>
                                                                        </Tooltip>
                                                                        Дата и время
                                                                    </div>
                                                                    <div className="datetime__content">
                                                                        <div className="datetime__fields">
                                                                            <DatePicker
                                                                                label="Выберите дату"
                                                                                slotProps={{textField: {size: 'small'}}}
                                                                                {...register('unloadingDate')} />
                                                                            <div
                                                                                className="datetime__item datetime__item-first">
                                                                                <input
                                                                                    type="time" {...register('unloading_time_from')}/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            :
                                                            <div className="chips-button">
                                                                <GreenButton
                                                                    onClick={() => setIsShowUnloadingDatetime(true)}>
                                                                    Дата и время
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
                                        <div className="form-group">
                                            <div className="car__block">
                                                <div className="form__label text-bold">Кузов</div>
                                                <div className="car__selector">
                                                    <FormControl sx={{minWidth: 232}} size="small"
                                                                 error={!!errors.bodyType}>
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
                                                        {errors.bodyType &&
                                                            <FormHelperText>{errors.bodyType.message}</FormHelperText>}
                                                    </FormControl>
                                                </div>
                                            </div>

                                            <div className="car__block">
                                                <div className="form__label">Загрузка</div>
                                                <div className="car__selector">
                                                    <FormControl sx={{minWidth: 232}} size="small"
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
                                            </div>
                                            <div className="car__block">
                                                <div className="form__label">Выгрузка</div>
                                                <div className="car__selector">
                                                    <div className="car__list">
                                                        {loadingTypes.map(unloadingType =>
                                                            <li className="car__item">
                                                                <Checkbox size="small" key={unloadingType} value={unloadingType}
                                                                          selected={watch(unloadingType) === unloadingType}>
                                                                    {unloadingType}
                                                                </Checkbox>
                                                            </li>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                </div>

                                <div className="form__block">
                                    <div className="form__inner">
                                        <div className="form-group">
                                            <CustomTabs defaultValue="negotiable" tabs={priceTypes}
                                                        onChange={setPriceType}/>
                                        </div>

                                        {['negotiable', 'fix'].includes(watch('priceType')) &&
                                            <Fragment>
                                                <div className="form__label text-bold">Ставка</div>
                                                {watch('priceType') === 'negotiable' &&
                                                    <p>Участники увидят, что возможен торг, и смогут предложить свою
                                                        ставку</p>
                                                }
                                                {watch('priceType') === 'fix' &&
                                                    <p>Участники смогут предлагать свои услуги только по вашей
                                                        ставке</p>
                                                }
                                                {isSubmitted && !watch('priceWithoutTax') && !watch('priceWithTax') && !watch('priceCash') &&
                                                    <div className="text-description text-red">Укажите хотя бы одну
                                                        ставку</div>
                                                }
                                                <div id="priceBlock">
                                                    <div className="form-group">
                                                        <span className="form__label">С НДС, безнал</span>
                                                        <TextField
                                                            type="number"
                                                            name="priceWithoutTax"
                                                            size="small"
                                                            error={!!errors.priceWithoutTax}
                                                            helperText={errors.priceWithoutTax?.message}
                                                            {...register('priceWithoutTax')}/> руб
                                                    </div>
                                                    <div className="form-group">
                                                        <span className="form__label">Без НДС, безнал</span>
                                                        <TextField
                                                            type="number"
                                                            name="priceWithTax"
                                                            size="small"
                                                            error={!!errors.priceWithTax}
                                                            helperText={errors.priceWithTax?.message}
                                                            {...register('priceWithTax')}/> руб
                                                    </div>
                                                    <div className="form-group">
                                                        <span className="form__label">Наличными</span>
                                                        <TextField
                                                            type="number"
                                                            name="priceCash"
                                                            size="small"
                                                            error={!!errors.priceCash}
                                                            helperText={errors.priceCash?.message}
                                                            {...register('priceCash')}/> руб
                                                    </div>
                                                    <div className="form-group">
                                                        <div className="form__label"></div>
                                                        <FormControlLabel
                                                            {...register('on_card')}
                                                            control={<Checkbox size="small"/>}
                                                            label="Наличные"/>
                                                    </div>
                                                    <div className="form-group">
                                                        <div className="form__label">Встречные предложения</div>
                                                        <FormControlLabel
                                                            {...register('hide_counter_offers')}
                                                            control={<Checkbox size="small"/>}
                                                            label="Видны только вам"/>
                                                    </div>
                                                </div>
                                            </Fragment>
                                        }
                                        {watch('priceType') === 'request' &&
                                            <div className="form-label">
                                                <p>Участники смогут предложить свою ставку</p>
                                                <div>
                                                    <FormControlLabel control={<Checkbox defaultChecked/>}
                                                                      label="С НДС, безнал"/>
                                                    <FormControlLabel control={<Checkbox defaultChecked/>}
                                                                      label="Без НДС, безнал"/>
                                                    <FormControlLabel control={<Checkbox defaultChecked/>}
                                                                      label="Наличными"/>
                                                </div>
                                            </div>
                                        }
                                        {watch('priceType') === 'auction' &&
                                            <div className="form-label">
                                                <p>Участники  будут соревноваться, предлагая наиболее выгодную ставку</p>
                                            </div>
                                        }


                                    </div>
                                </div>

                                <div className="form__block form__block-last">
                                    <div className="form__item form-group">
                                        <div className="form__label"></div>
                                        <div>укажите, к кому обратиться по объявлению</div>
                                    </div>
                                    <div className="form__item form-group">
                                        <div className="form__label text-bold">Контакты</div>
                                        <ContactList
                                            availableContacts={availableContacts}
                                            selectedContacts={watch('contact_ids')}
                                            setSelectedContacts={ids =>  setValue('contact_ids', ids)}
                                        />
                                    </div>
                                    <div className="form__item form-group">
                                        <div className="form__label text-bold">Примечание</div>
                                        <div className="note">
                                            <TextField
                                                className="note__item"
                                                name="note"
                                                size="small"
                                                {...register('note')}/>
                                        </div>
                                    </div>
                                    {isShowAddPhoto &&
                                        <div className="form__item form-group">
                                            <div className="form__label">
                                                <div className="relative">
                                                    <Tooltip title="Скрыть поле" placement="top">
                                                        <div className="close-button form__close" onClick={closeFileUploader}></div>
                                                    </Tooltip>
                                                    Фото груза и документов
                                                </div>
                                            </div>

                                            <FileUploader
                                                entity="load"
                                                files={files}
                                                setFiles={setFiles}
                                                allowedMaxFiles="10"
                                                allowedExtensions={allowedExtensions}
                                                multiple="true"
                                                update={value => setFiles(value)}/>
                                        </div>
                                    }
                                    {!isShowAddPhoto &&
                                        <div className="form__item form-group">
                                            <div className="form__label text-bold">Добавить</div>
                                            <div className="input">
                                                <GreenButton onClick={() => setIsShowAddPhoto(true)}>Фото</GreenButton>
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
