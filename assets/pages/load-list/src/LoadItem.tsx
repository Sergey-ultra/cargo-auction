import React, {Fragment, useState} from "react";
import {Button, TextField, Tooltip} from "@mui/material";
import PrecisionRating from "../../../components/rating/Rating";
import EditIcon from "../../../components/icons/EditIcon";
import EditSmallIcon from "../../../components/icons/EditSmallIcon";
import MapIcon from "../../../components/icons/MapIcon";

export interface Comment {
    id: number|null,
    comment: string,
    entityId: number,
}

function LoadItem({
                      load,
                      openSendBidModal,
                      openAuthModal,
                      addingComment,
                      toggleAddingComment,
                      saveComment,
                      deleteComment
                  }) {
    let userId = window?.authData?.userId;
    if (userId) {
        userId = Number(userId);
    }

    const [comment, setComment] = useState<Comment>({
        id: load.comment ? load.comment?.id : null,
        comment: load.comment ? load.comment.comment : '',
        entityId: load.id,
    });

    const sendComment = async (): Promise<void> => {
        const id = await saveComment(comment);
        if (id) {
            setComment({ ...comment, id });
            toggleAddingComment(load.id);
        }
    }

    const makeDeleteComment = async() => {
        await deleteComment(comment.id);
        setComment({
            id: null,
            comment: '',
            entityId: load.id,
        });
    }

    return (
        <div className="table__row" key={load.id}>
            <div className="table__item table__item-direction">
                <div>{load.route.country}</div>
                <Tooltip title={`${load.loading.location.city} - ${load.unloading.location.city}`} placement="top">
                    <div className="table__distance">{load.route.distance} км</div>
                </Tooltip>
                {load.route.totalDistance > 0 &&
                    <div className="table__distance">{load.route.totalDistance} км</div>
                }
            </div>
            <div className="table__item small-font">
                <Tooltip title={load.truck.bodyType} placement="top">
                    <span>{load.truck.bodyTypeShort}</span>
                </Tooltip>
                <div>
                    <span className="text-gray">загр/выгр: </span>
                    <Tooltip
                        title={load.truck.loadingType + `${load.truck.loadingType !== load.truck.unloadingType ? '/' + load.truck.unloadingType : ''}`}
                        placement="top">
                        <span>
                            <span>{load.truck.loadingTypeShort}</span>
                            {load.truck.loadingTypeShort !== load.truck.unloadingTypeShort &&
                                <span>/{load.truck.unloadingTypeShort}</span>
                            }
                        </span>
                    </Tooltip>
                </div>
                {load.load.type === 'ftl'
                    ? <div>отд.машина</div>
                    : <div>возм.догруз</div>
                }
            </div>
            <div className="table__item">
                <span className="text-bold">{load.load.weight}т. {load.load.volume}м3</span> {load.load.cargoType}
            </div>
            <div className="table__item table__item-route route">
                <div className={`location ${load.loading.location.street ? 'map' : ''}`}>
                    <div className="city">
                        <div className="text-bold">
                            {load.loading.location.city}
                            {load.loading.location.street &&
                                <svg height="12" viewBox="0 0 8 12" width="8" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M 4 0 C 1.79 0 0 1.8 0 4.03 C 0 6.13 1.81 8.84 3.02 10.16 C 3.47 10.65 4 11.32 4 11.32 C 4 11.32 4.57 10.65 5.05 10.14 C 6.26 8.88 8 6.34 8 4.03 C 8 1.8 6.21 0 4 0 Z M 4 6.22 C 5.23 6.22 6.22 5.23 6.22 4 C 6.22 2.77 5.23 1.78 4 1.78 C 2.77 1.78 1.78 2.77 1.78 4 C 1.78 5.23 2.77 6.22 4 6.22 Z M 4 6.22"
                                        fill="var(--glz-color-primary)" fillRule="evenodd"></path>
                                </svg>
                            }
                        </div>
                        <div>{load.loading.location.street}</div>
                    </div>
                    {load.loading.type === 'permanently' && <div className="text-bold">постоянно</div>
                        || load.loading.type === 'from-date' &&
                        <span>
                            <span className="text-bold">готов {load.loading.date}</span>
                            {load.loading.time &&
                                <span>{load.loading.time}</span>
                            }
                        </span>
                    }
                </div>
                <div className={`location ${load.loading.location.street ? 'map' : ''}`}>
                    <div className="city">
                        <div className="text-bold">
                            {load.unloading.location.city}
                            {load.unloading.location.street && <MapIcon/>}
                        </div>
                        <div>{load.unloading.location.street}</div>
                    </div>
                    {load.unloading.date &&
                        <span>
                            <span className="text-bold">готов {load.unloading.date}</span>
                            {load.unloading.time &&
                                <span>{load.unloading.time}</span>
                            }
                        </span>
                    }
                </div>
            </div>

            <div className="table__item table__item-bid">
                    {!userId && (<span className="text-gray">скрыто</span>) ||
                        <Fragment>
                            {load.rate.priceType !== 'request' &&
                                <Fragment>
                                    <div className="small-font">
                                        {!!load.rate.priceCash &&
                                            <div className="price__item">
                                                <div>
                                                    <span className="text-bold">{load.rate.priceCash} руб</span>
                                                    <span className="text-gray"> нал</span>
                                                </div>
                                                <div>
                                                    <span className="text-bold">{ load.rate.priceCashPerKm }</span> руб/км
                                                </div>
                                            </div>
                                        }

                                        {!!load.rate.priceWithoutTax &&
                                            <div className="price__item">
                                                <div>
                                                    <span className="text-bold">{load.rate.priceWithoutTax} руб</span>
                                                    <span className="text-gray"> без НДС</span>
                                                </div>
                                                <div>
                                                    <span className="text-bold">{ load.rate.priceWithoutTaxPerKm }</span> руб/км
                                                </div>
                                            </div>
                                        }

                                        {!!load.rate.priceWithTax &&
                                            <div className="price__item">
                                                <div>
                                                    <span className="text-bold">{load.rate.priceWithTax} руб</span>
                                                    <span className="text-gray"> c НДС</span>
                                                </div>
                                                <div>
                                                    <span className="text-bold">{ load.rate.priceWithTaxPerKm }</span> руб/км
                                                </div>
                                            </div>
                                        }
                                    </div>

                                    {load.rate.priceType !== 'fix'
                                        ? <div className="text-bold">торг</div>
                                        : <div className="text-gray">без торга</div>
                                    }
                                </Fragment>
                                ||
                                <div className="text-gray">запрос ставки</div>
                            }
                            <Tooltip title={`Отправить встречное предложение. ${load.bids.count > 0
                                ? `Встречных предложений: ${load.bids.count} ${load.bids.maxValue} руб`
                                : 'Нет встречных предложений.'}`}
                                     placement="bottom">
                                <button className={`send-bid ${load.bids.count > 0 ? 'send-bid-bold' : ''}`}
                                        id="sendBid" onClick={() => openSendBidModal(load.id)}>
                                    <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0"
                                         data-qa="icon" viewBox="0 0 15 15" width="15" height="15"
                                         className="arrow-svg">
                                        <use href="#ic_shape">
                                            <symbol id="ic_shape" viewBox="0 0 18 14">
                                                <path
                                                    d="M17.924 13.557c.028-.175.624-4.377-2.047-7.27-1.64-1.776-4.172-2.75-7.441-2.846L8.419.52a.52.52 0 0 0-.322-.47.603.603 0 0 0-.596.066L.207 5.656A.509.509 0 0 0 0 6.059c0 .156.075.304.205.402L7.5 12.053a.596.596 0 0 0 .597.069.518.518 0 0 0 .323-.47l.017-2.988c7.88-.005 8.44 4.667 8.459 4.86.024.263.204.467.488.476a.514.514 0 0 0 .541-.442Z"></path>
                                            </symbol>
                                        </use>
                                    </svg>
                                    <span>Отправить ставку</span>
                                    {load.bids.count > 0 && <span> ({load.bids.count})</span>}
                                </button>
                            </Tooltip>
                        </Fragment>
                    }
            </div>

            <div className="table-bottom">
                <div className="table__left">
                    {userId && (
                            <Fragment>
                                <div>
                                    {load.note &&
                                        <div className="note">
                                            <svg fill="var(--glz-color-neutral-tone-3)"
                                                 stroke="var(--glz-color-neutral-tone-3)" strokeWidth="0"
                                                 data-qa="icon" viewBox="0 0 14 14" width="14" height="14">
                                                <use href="#ic_note">
                                                    <symbol id="ic_note" viewBox="0 0 18 16">
                                                        <path fillRule="evenodd" clipRule="evenodd"
                                                              d="M14.164 0H3.836C2.001 0 .5 1.53.5 3.401V8.62c0 1.87 1.501 3.401 3.336 3.401h1.503V16l4.88-3.98h3.945c1.835 0 3.336-1.53 3.336-3.401V3.4c0-1.869-1.501-3.4-3.336-3.4Z"></path>
                                                    </symbol>
                                                </use>
                                            </svg>
                                            <span>{load.note}</span>
                                        </div>
                                    }
                                    <div className="contact">
                                        <a className="contact_send" href={`/company/${load.company.id}`}>
                                            <span>{load.company.fullName.toUpperCase()}</span>
                                        </a>
                                        <span>Код: {load.company.id}, </span>
                                        <span>{load.company.cityName}, </span>
                                        <span>{load.company.type}</span>
                                        <Tooltip title={`Бал участника ${load.company.rating.score}`} placement="top">
                                            <div>
                                                <PrecisionRating rating={load.company.rating.score} large={false}/>
                                            </div>
                                        </Tooltip>
                                    </div>
                                    {userId !== load.userId && load.company.contacts.map(contact =>
                                        <div key={contact.id} className="contact">
                                            <a className="contact_send"
                                               href={`profile/messages/${contact.id}?load_id=${load.id}`}>
                                                <span className="contact_svg">
                                                    <svg fill="#2277cc" stroke="#2277cc" strokeWidth="0" data-qa="icon"
                                                         viewBox="0 0 15 15" width="15" height="15">
                                                        {/*<symbol id="ic_message" viewBox="0 0 17 16">*/}
                                                        <path fillRule="evenodd" clipRule="evenodd"
                                                              d="m6.5 13-4.327-.022-.242-.001-1.008-.005-.462-.003a.464.464 0 0 1-.323-.793l.329-.324.005-.005.713-.704.172-.17.226-.222A6.452 6.452 0 0 1 0 6.5C0 2.916 2.916 0 6.5 0S13 2.916 13 6.5 10.084 13 6.5 13Zm-2.31-2.012.132-.13L3.094 9.44A4.452 4.452 0 0 1 2 6.5C2 4.02 4.02 2 6.5 2S11 4.02 11 6.5 8.98 11 6.5 11l-2.31-.012ZM14 4c3 2.5 3.273 6.777.855 9.621l1.498 1.508c.304.306.092.836-.335.838L9.755 16A6.651 6.651 0 0 1 5 13.997l1.842.011H6.84C11.945 14.008 15.4 9.007 14 4Z"></path>
                                                        {/*</symbol>*/}
                                                    </svg>
                                                 </span>
                                                <span>Написать</span>
                                            </a>
                                            {contact.phone && <span><a className="telephone"
                                                                       href={`tel:${contact.phone}`}>{contact.phone}, </a></span>}
                                            {contact.mobilePhone && <span><a className="telephone"
                                                                             href={`tel:${contact.mobilePhone}`}>{contact.mobilePhone}, </a></span>}
                                            <span>{contact.name}</span>
                                        </div>
                                    )}
                                </div>
                                {addingComment === load.id &&
                                    <div className="speech speech-yellow">
                                        <TextField
                                            sx={{m: 1, background: 'white'}}
                                            fullWidth
                                            size="small"
                                            value={comment.comment}
                                            placeholder="Написать комментарий. Он будет виден только вам и сотрудникам компании"
                                            onChange={e => setComment({...comment, comment: e.target.value})}/>
                                        <div className="speech__buttons">
                                            <Button sx={{width: '20ch'}} variant="contained" size="small"
                                                    onClick={sendComment}>Сохранить</Button>
                                            <Button onClick={() => toggleAddingComment(load.id)}>Отмена</Button>
                                        </div>
                                    </div>
                                }
                                {comment.id && !addingComment &&
                                    <div className="speech__comment">
                                        <div className="speech__avatar">
                                            <svg fill="var(--glz-color-neutral-tone-4)"
                                                 stroke="var(--glz-color-neutral-tone-4)" strokeWidth="0"
                                                 data-qa="icon" viewBox="0 0 12 12" width="12" height="12">
                                                <use href="#ic_human">
                                                    <symbol id="ic_human" viewBox="0 0 16 16">
                                                        <path fillRule="evenodd" clipRule="evenodd"
                                                              d="M8 10.012c1.684 0 3.439.366 5.263 1.1 1.825.732 2.737 1.691 2.737 2.876V16H0v-2.012c0-1.185.912-2.144 2.737-2.877 1.824-.733 3.579-1.1 5.263-1.1ZM8 8c-1.092 0-2.027-.39-2.807-1.17-.78-.78-1.17-1.715-1.17-2.807 0-1.091.39-2.035 1.17-2.83C5.973.398 6.908 0 8 0c1.092 0 2.027.398 2.807 1.193.78.795 1.17 1.739 1.17 2.83 0 1.092-.39 2.028-1.17 2.807C10.027 7.61 9.092 8 8 8Z">

                                                        </path>
                                                    </symbol>
                                                </use>
                                            </svg>
                                        </div>
                                        <div className="speech__inner">
                                            <span>{comment.comment}</span>
                                            <div className="speech__icons">
                                                <button className="speech__button icon" onClick={() => toggleAddingComment(load.id)}>
                                                    <span className="speech__icon">
                                                       <EditSmallIcon/>
                                                    </span>
                                                </button>
                                                <button className="speech__button icon" onClick={makeDeleteComment}>
                                                    <span className="speech__icon">
                                                        <svg fill="var(--glz-color-neutral-tone-4)"
                                                             stroke="var(--glz-color-neutral-tone-4)"
                                                             strokeWidth="0"
                                                             hoverColor="var(--glz-color-neutral-tone-5)"
                                                             viewBox="0 0 12 12" width="12" height="12">
                                                            <use href="#ic_delete-outline">
                                                                <symbol id="ic_delete-outline" viewBox="0 0 18 18">
                                                                    <path fillRule="evenodd" clipRule="evenodd"
                                                                        d="M6.75 7.125v6.75c0 .11-.035.2-.105.27a.365.365 0 0 1-.27.105h-.75c-.11 0-.2-.035-.27-.105a.365.365 0 0 1-.105-.27v-6.75c0-.11.035-.2.105-.27.07-.07.16-.105.27-.105h.75c.11 0 .2.035.27.105.07.07.105.16.105.27Zm3 0v6.75c0 .11-.035.2-.105.27a.365.365 0 0 1-.27.105h-.75c-.11 0-.2-.035-.27-.105a.365.365 0 0 1-.105-.27v-6.75c0-.11.035-.2.105-.27.07-.07.16-.105.27-.105h.75c.11 0 .2.035.27.105.07.07.105.16.105.27Zm3 0v6.75c0 .11-.035.2-.105.27a.365.365 0 0 1-.27.105h-.75c-.11 0-.2-.035-.27-.105a.365.365 0 0 1-.105-.27v-6.75c0-.11.035-.2.105-.27.07-.07.16-.105.27-.105h.75c.11 0 .2.035.27.105.07.07.105.16.105.27Zm1.5 8.484V4.5H3.75v11.11a1.33 1.33 0 0 0 .252.79c.059.067.1.1.123.1h9.75c.023 0 .065-.033.123-.1a1.33 1.33 0 0 0 .252-.791ZM6.375 3h5.25l-.563-1.371a.33.33 0 0 0-.199-.129H7.148a.33.33 0 0 0-.199.129L6.375 3Zm10.875.375v.75c0 .11-.035.2-.105.27a.365.365 0 0 1-.27.105H15.75v11.11c0 .648-.184 1.208-.55 1.681-.368.473-.81.709-1.325.709h-9.75c-.516 0-.957-.229-1.324-.686-.367-.457-.551-1.01-.551-1.658V4.5H1.125c-.11 0-.2-.035-.27-.105a.365.365 0 0 1-.105-.27v-.75c0-.11.035-.2.105-.27.07-.07.16-.105.27-.105h3.621l.82-1.957c.118-.29.329-.535.633-.738C6.504.102 6.813 0 7.125 0h3.75c.313 0 .621.102.926.305.304.203.515.449.633.738L13.254 3h3.621c.11 0 .2.035.27.105.07.07.105.16.105.27Z"></path>
                                                                </symbol>
                                                            </use>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                }
                            </Fragment>
                        ) ||
                        <div className="contact">
                            <div className="button-empty" onClick={openAuthModal}>показать контакты и ставку</div>
                            <div className="contact__note">Доступно <b>бесплатно</b> после быстрой регистрации</div>
                        </div>
                    }
                </div>
                <div className="table-right">
                    <div className="table__rightWrapper">
                        <Tooltip title="Подробней о грузе" placement="top">
                            <a href={`/load/${load.id}`} className="icon icon-info">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                    <svg fill="white" stroke="white" strokeWidth="0" data-qa="icon"
                                         viewBox="0 0 20 20" width="20" height="20"
                                         preserveAspectRatio="none" x="2" y="2">
                                        <path fillRule="evenodd" clipRule="evenodd"
                                              d="M0 10c0-1.38.26-2.679.781-3.896A10.035 10.035 0 0 1 2.92 2.92 10.035 10.035 0 0 1 6.104.78 9.801 9.801 0 0 1 10 0c1.38 0 2.679.26 3.896.781A10.036 10.036 0 0 1 17.08 2.92a10.037 10.037 0 0 1 2.139 3.184A9.802 9.802 0 0 1 20 10c0 1.38-.26 2.679-.781 3.896a10.038 10.038 0 0 1-2.139 3.184 10.038 10.038 0 0 1-3.183 2.139A9.802 9.802 0 0 1 10 20c-1.38 0-2.679-.26-3.896-.781A10.037 10.037 0 0 1 2.92 17.08a10.036 10.036 0 0 1-2.14-3.183A9.802 9.802 0 0 1 0 10Zm1.25 0c0 1.21.228 2.347.684 3.408a8.813 8.813 0 0 0 1.875 2.783 8.811 8.811 0 0 0 2.783 1.875A8.545 8.545 0 0 0 10 18.75c1.21 0 2.347-.228 3.408-.684a8.811 8.811 0 0 0 2.783-1.875 8.811 8.811 0 0 0 1.875-2.783A8.545 8.545 0 0 0 18.75 10c0-1.21-.228-2.347-.684-3.408a8.811 8.811 0 0 0-1.875-2.783 8.813 8.813 0 0 0-2.783-1.875A8.545 8.545 0 0 0 10 1.25c-1.21 0-2.347.228-3.408.684a8.812 8.812 0 0 0-2.783 1.875 8.812 8.812 0 0 0-1.875 2.783A8.545 8.545 0 0 0 1.25 10Z"></path>
                                        <path
                                            d="M11.336 8.125v8H8.72v-8h2.616Zm.27-2.839c0 .208-.043.403-.127.586-.084.183-.2.343-.346.481a1.694 1.694 0 0 1-.51.329 1.584 1.584 0 0 1-1.22 0 1.684 1.684 0 0 1-.493-.329 1.519 1.519 0 0 1-.338-.481 1.423 1.423 0 0 1-.122-.586c0-.214.04-.414.122-.6a1.58 1.58 0 0 1 .831-.818 1.634 1.634 0 0 1 1.73.33c.146.14.262.303.346.489.084.185.127.385.127.599Z"></path>
                                    </svg>
                                </svg>
                            </a>
                        </Tooltip>

                        {(userId && userId === load.userId) &&
                            <a href={`/${load.id}/edit`} className="icon">
                                <EditIcon/>
                            </a>
                        }

                        {userId &&
                            <Tooltip title="Написать комментарий. Он будет виден только вам и сотрудникам компании" placement="top">
                                <div className="icon icon-note" onClick={() => toggleAddingComment(load.id)}>
                                    <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0" data-qa="icon" viewBox="0 0 12 12"
                                         width="12" height="12">
                                        <path fillRule="evenodd" clipRule="evenodd"
                                              d="M16.225 0c.48 0 .895.176 1.247.528.352.352.528.768.528 1.247V12.59c0 .48-.176.902-.528 1.268-.352.366-.768.55-1.247.55H3.592L0 18V1.775C0 1.295.176.88.528.528A1.705 1.705 0 0 1 1.775 0h14.45Z"></path>
                                    </svg>
                                </div>
                            </Tooltip>
                        }

                        {userId &&
                            <div className="icon">
                                <svg fill="var(--glz-color-primary)" stroke="var(--glz-color-primary)"
                                     strokeWidth="0" data-qa="icon" viewBox="0 0 18 17" width="18" height="17"
                                     preserveAspectRatio="none" x="3" y="3.5">
                                    <use href="#ic_star-outline">
                                        <symbol id="ic_star-outline" viewBox="0 0 18 17">
                                            <path fillRule="evenodd" clipRule="evenodd"
                                                  d="m9 12.537 3.849 2-.735-4.231 3.089-2.975-4.27-.613L9 2.849 7.068 6.718l-4.27.613 3.088 2.975-.735 4.23L9 12.538Zm3.055-7.375L9.69.424a.772.772 0 0 0-1.378 0L5.945 5.162.66 5.92a.761.761 0 0 0-.426 1.303l3.822 3.681-.903 5.202c-.108.623.55 1.098 1.115.805L9 14.453l4.732 2.458c.564.293 1.223-.182 1.115-.805l-.903-5.202 3.822-3.681a.761.761 0 0 0-.426-1.303l-5.285-.758Z"></path>
                                        </symbol>
                                    </use>
                                </svg>
                            </div>
                        }

                        <Tooltip title="Скрыть заявку" placement="top">
                            <div className="icon icon-close">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <svg fill="white" stroke="white" strokeWidth="0" data-qa="icon" viewBox="0 0 18 18"
                                         width="18"
                                         height="18" preserveAspectRatio="none" x="3" y="3">
                                        <use href="#ic_close">
                                            <symbol id="ic_close" viewBox="0 0 18 18">
                                                <path fillRule="evenodd" clipRule="evenodd"
                                                      d="M18 1.82 16.18 0 9 7.18 1.82 0 0 1.82 7.18 9 0 16.18 1.82 18 9 10.82 16.18 18 18 16.18 10.82 9 18 1.82Z"></path>
                                            </symbol>
                                        </use>
                                    </svg>
                                </svg>
                            </div>
                        </Tooltip>

                    </div>
                    <div className="table__rightWrapper">
                        <div>доб <span>{load.createdAt}</span></div>
                        {load.updatedAt && load.createdAt !== load.updatedAt && <div>изм {load.updatedAt}</div>}
                    </div>
                </div>
            </div>
        </div>
    )
}

export default LoadItem;

