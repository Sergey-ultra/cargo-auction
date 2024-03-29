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
        id: null,
        comment: ''
    });


    return (
        <div className="table__row" key={transport.id}>
            <div className="table__item">RUS</div>
            <div className="table__item table__item-big">
                {transport.bodyType}
                {transport.weight}т. {transport.volume}м3
                {transport.bodyType}
            </div>
            <div className="table__item text-bold">
                <span>{transport.fromName}</span>
            </div>
            <div className="table__item text-bold">
                <span>{transport.toName}</span>
            </div>
            <div className="table__item table__item-big">
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
                <div className="contacts">
                    {userId && (
                            <Fragment>
                                <div className="contacts__wrapper">
                                    <div className="table__contact">
                                        <div>
                                            <span className="contact_send">{transport.company.fullName}</span>
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
                                    <div className="speech-bubble">
                                        <TextField size="small" value={ comment.comment } onChange={e => setComment({ ...comment, comment: e.target.value })}/>
                                        <Button sx={{ m: 1, width: '25ch' }} variant="contained" size="small" onClick={() => saveComment(comment)}>Сохранить</Button>
                                        <Button onClick={() => toggleAddingComment(transport.id)}>Отмена</Button>
                                    </div>}
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
                        <div className="icon-note" onClick={() => toggleAddingComment(transport.id)}>
                            <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0" data-qa="icon" viewBox="0 0 12 12"
                                 width="12" height="12">
                                <path fillRule="evenodd" clipRule="evenodd"
                                      d="M16.225 0c.48 0 .895.176 1.247.528.352.352.528.768.528 1.247V12.59c0 .48-.176.902-.528 1.268-.352.366-.768.55-1.247.55H3.592L0 18V1.775C0 1.295.176.88.528.528A1.705 1.705 0 0 1 1.775 0h14.45Z"></path>
                            </svg>
                        </div>
                    </Tooltip>

                    <div>доб <span>{transport.createdAt}</span></div>
                    {transport.updatedAt && transport.createdAt !== transport.updatedAt &&
                        <div>изм {transport.updatedAt}</div>}
                </div>
            </div>
        </div>
    )
}

export default TransportItem;

