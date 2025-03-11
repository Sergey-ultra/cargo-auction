import React, {Fragment, useState} from "react";
import {Button, TextField, Tooltip} from "@mui/material";

function TransportItem({
                           transport,
                           openSendBidModal,
                           openAuthModal,
                           addingComment,
                           toggleAddingComment,
                           saveComment
}) {
    const userId = window?.authData?.userId;
    const [comment, setComment] = useState({
        id: transport.comment ? transport.comment.id : null,
        comment: transport.comment ? transport.comment.comment : '',
        entityId: transport.id,
    });

    const sendComment = async () => {
        const id = await saveComment(comment);
        if (id) {
            setComment({ ...comment, id });
            toggleAddingComment(transport.id);
        }
    }
    return (
        <div className="table__row" key={transport.id}>
            <div className="table__item">RUS</div>
            <div className="table__item table__item-bid">
                {transport.bodyType}
                {transport.weight}т. {transport.volume}м3
                {transport.bodyType}
            </div>
            <div className="table__item">
                <span className="text-bold">{transport.fromName}</span>
            </div>
            <div className="table__item">
                <span className="text-bold">{transport.toName}</span>
            </div>
            <div className="table__item table__item-bid">
                {!userId && (<span className="text-gray">скрыто</span>)
                    ||
                    <Fragment>
                        <div>
                            {!!transport.priceWithoutTax &&
                                (<div className="price__item">
                                    <span className="text-bold">{transport.priceWithoutTax} руб</span>
                                    <span className="text-gray"> без НДС </span>
                                    <span className="text-bold"></span> руб/км
                                </div>)}

                            {!!transport.priceWithTax &&
                                (<div className="price__item">
                                    <span className="text-bold">{transport.priceWithTax} руб</span>
                                    <span className="text-gray"> c НДС </span>
                                    <span className="text-bold"></span> руб/км
                                </div>)}
                            <div>торг</div>
                        </div>

                        <button className="send-bid" id="sendBid" onClick={() => openSendBidModal(transport.id)}>
                            <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0"
                                 data-qa="icon" viewBox="0 0 15 15" width="15" height="15"
                                 className="arrow-svg">
                                <symbol id="ic_shape" viewBox="0 0 18 14">
                                    <path
                                        d="M17.924 13.557c.028-.175.624-4.377-2.047-7.27-1.64-1.776-4.172-2.75-7.441-2.846L8.419.52a.52.52 0 0 0-.322-.47.603.603 0 0 0-.596.066L.207 5.656A.509.509 0 0 0 0 6.059c0 .156.075.304.205.402L7.5 12.053a.596.596 0 0 0 .597.069.518.518 0 0 0 .323-.47l.017-2.988c7.88-.005 8.44 4.667 8.459 4.86.024.263.204.467.488.476a.514.514 0 0 0 .541-.442Z"></path>
                                </symbol>
                            </svg>
                            <span>Отправить предложение</span>
                        </button>
                    </Fragment>
                }

            </div>

            <div className="table-bottom">
                <div className="table__left">
                    {userId && (
                            <Fragment>
                                <div className="contacts__wrapper">
                                    <div className="table__contact">
                                        <div className="contact">
                                            <span className="contact_send">{transport.company.fullName}</span>
                                            <span>Код: {transport.company.id}, </span>
                                            <span>{transport.company.cityName}, </span>
                                            <span>{transport.company.type}</span>
                                        </div>
                                        {transport.company.contacts.map(contact =>
                                            <div key={contact.id} className="contact">
                                                <a className="contact_send"
                                                   href={`profile/messages/${contact.id}?truck_id=${transport.id}`}>
                                                    <span className="contact_svg">
                                                        <svg fill="#2277cc" stroke="#2277cc" strokeWidth="0"
                                                             data-qa="icon"
                                                             viewBox="0 0 15 15" width="15" height="15">
                                                            {/*<symbol  viewBox="0 0 17 16">*/}
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
                                            </div>)}

                                    </div>
                                    <a className="contact_send" href="#">Жалоба</a>
                                </div>
                                {addingComment === transport.id &&
                                    <div className="speech">
                                        <TextField
                                            sx={{m: 1}}
                                            fullWidth
                                            size="small"
                                            value={ comment.comment }
                                            onChange={e => setComment({ ...comment, comment: e.target.value })}/>
                                        <div className="speech__buttons">
                                            <Button sx={{ width: '20ch' }} variant="contained" size="small" onClick={sendComment}>Сохранить</Button>
                                            <Button onClick={() => toggleAddingComment(transport.id)}>Отмена</Button>
                                        </div>
                                    </div>
                                }
                                {comment.id &&
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
                                                <button className="speech__button icon">
                                                    <span className="speech__icon">
                                                        <svg fill="var(--glz-color-neutral-tone-4)"
                                                             stroke="var(--glz-color-neutral-tone-4)" strokeWidth="0"
                                                             hoverColor="var(--glz-color-neutral-tone-5)"
                                                             viewBox="0 0 12 12" width="12" height="12">
                                                            <use href="#ic_edit">
                                                                <symbol id="ic_edit" viewBox="0 0 18 18"><path
                                                                    fillRule="evenodd" clipRule="evenodd"
                                                                    d="M17.719 4.031 15.89 5.86l-3.75-3.75L13.969.281A.954.954 0 0 1 14.672 0c.281 0 .515.094.703.281l2.344 2.344a.954.954 0 0 1 .281.703.954.954 0 0 1-.281.703ZM0 14.25 11.063 3.187l3.75 3.75L3.75 18H0v-3.75Z"></path></symbol>
                                                            </use>
                                                        </svg>
                                                    </span>
                                                </button>
                                                <button className="speech__button icon">
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
                            </Fragment>)

                        ||
                        (<Fragment>
                            <div className="button-empty" onClick={openAuthModal}>показать контакты и ставку</div>
                            <div>Доступно <b>бесплатно</b> после быстрой регистрации</div>
                        </Fragment>
                        )
                    }

                </div>
                <div className="table-right">
                    {transport?.user?.id && userId === transport.user.id && (
                        <Fragment>
                            <a href={`/${transport.id}/edit`}>
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fillRule="evenodd" clipRule="evenodd"
                                          d="M13.9687 0.281254L12.1406 2.10938L15.8906 5.85938L17.7187 4.03125C17.9063 3.84375 18 3.60938 18 3.32813C18 3.04688 17.9063 2.8125 17.7187 2.625L15.375 0.281254C15.1875 0.093752 14.9531 3.94788e-06 14.6719 3.96017e-06C14.3906 3.97247e-06 14.1563 0.0937521 13.9687 0.281254ZM3.75 18L14.8125 6.9375L11.0625 3.1875L-1.63918e-07 14.25L0 18L3.75 18Z"
                                          fill="#8C969D"/>
                                </svg>
                            </a>
                            <div>
                                <svg fill="#478cc8" stroke="#478cc8" strokeWidth="0" data-qa="icon"
                                     width="24" height="24" viewBox="0 0 24 24"
                                     preserveAspectRatio="none" x="3" y="3.5">
                                    <symbol id="ic_star-outline" viewBox="0 0 18 17">
                                        <path fillRule="evenodd" clipRule="evenodd"
                                              d="m9 12.537 3.849 2-.735-4.231 3.089-2.975-4.27-.613L9 2.849 7.068 6.718l-4.27.613 3.088 2.975-.735 4.23L9 12.538Zm3.055-7.375L9.69.424a.772.772 0 0 0-1.378 0L5.945 5.162.66 5.92a.761.761 0 0 0-.426 1.303l3.822 3.681-.903 5.202c-.108.623.55 1.098 1.115.805L9 14.453l4.732 2.458c.564.293 1.223-.182 1.115-.805l-.903-5.202 3.822-3.681a.761.761 0 0 0-.426-1.303l-5.285-.758Z"></path>
                                    </symbol>
                                </svg>
                            </div>
                        </Fragment>
                    ) || ''}

                    <Tooltip title="Написать комментарий. Он будет виден только вам и сотрудникам компании" placement="top">
                        <div className="icon icon-note" onClick={() => toggleAddingComment(transport.id)}>
                            <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0" data-qa="icon" viewBox="0 0 12 12"
                                 width="12" height="12">
                                <path fillRule="evenodd" clipRule="evenodd"
                                      d="M16.225 0c.48 0 .895.176 1.247.528.352.352.528.768.528 1.247V12.59c0 .48-.176.902-.528 1.268-.352.366-.768.55-1.247.55H3.592L0 18V1.775C0 1.295.176.88.528.528A1.705 1.705 0 0 1 1.775 0h14.45Z"></path>
                            </svg>
                        </div>
                    </Tooltip>

                    <div>доб <span>{transport.createdAt}</span></div>
                    {transport.updatedAt && transport.createdAt !== transport.updatedAt &&
                        <div>изм {transport.updatedAt}</div>
                    }
                </div>
            </div>
        </div>
    )
}

export default TransportItem;

