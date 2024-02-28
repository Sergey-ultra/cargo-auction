import React, {Fragment} from "react";

function TransportItem({truck, openSendBidModal, openAuthModal}) {
    const userId = window?.authData?.userId;


    return (
        <div className="table__row" key={truck.id}>
            <div className="table__item">{truck.bodyTypeName}</div>
            <div className="table__item table__item-price text-bold">
                {truck.fromAddress}
                <span>{truck.toAddress}</span>
                {truck.downtruckingDateStatus === 'permanently' && <div>Постоянно</div>
                    || truck.downtruckingDateStatus === 'ready' && <div>готов {truck.downtruckingDate}.</div>}
            </div>
            <div className="table__item">
                {truck.weight}т. {truck.volume}м3 {truck.cargoTypeName}
            </div>
            <div className="table__item table__item-price">
                {!userId && (<span className="text-gray">скрыто</span>)
                    ||
                    <Fragment>
                        <div>
                            {truck.priceWithoutTax &&
                                (<div className="price__item">
                                    <span className="text-bold">{truck.priceWithoutTax} руб</span>
                                    <span className="text-gray"> без НДС </span>
                                    <span className="text-bold"></span> руб/км
                                </div>)}

                            {truck.priceWithTax &&
                                (<div className="price__item">
                                    <span className="text-bold">{truck.priceWithTax} руб</span>
                                    <span className="text-gray"> c НДС </span>
                                    <span className="text-bold"></span> руб/км
                                </div>)}
                        </div>

                        <button className="send-bid" id="sendBid" onClick={() => openSendBidModal(truck.id)}>
                            <svg fill="#ffffff" stroke="#ffffff" strokeWidth="0"
                                 data-qa="icon" viewBox="0 0 15 15" width="15" height="15"
                                 className="arrow-svg">
                                <symbol id="ic_shape" viewBox="0 0 18 14">
                                    <path
                                        d="M17.924 13.557c.028-.175.624-4.377-2.047-7.27-1.64-1.776-4.172-2.75-7.441-2.846L8.419.52a.52.52 0 0 0-.322-.47.603.603 0 0 0-.596.066L.207 5.656A.509.509 0 0 0 0 6.059c0 .156.075.304.205.402L7.5 12.053a.596.596 0 0 0 .597.069.518.518 0 0 0 .323-.47l.017-2.988c7.88-.005 8.44 4.667 8.459 4.86.024.263.204.467.488.476a.514.514 0 0 0 .541-.442Z"></path>
                                </symbol>
                            </svg>
                            <span>Отправить ставку</span>
                        </button>
                    </Fragment>
                }

            </div>

            <div className="table-bottom">
                <div className="table__contact">
                    {userId && (
                            userId !== truck.user.id && (
                                <Fragment>
                                    <a href={`profile/messages/${truck.user.id}?truck_id=${truck.id}`}>
                                        <span>
                                            <svg fill="#3a7bbf" stroke="#3a7bbf" strokeWidth="0" data-qa="icon" viewBox="0 0 15 15" width="15" height="15">
                                                <symbol id="ic_message" viewBox="0 0 17 16">
                                                    <path fillRule="evenodd" clipRule="evenodd" d="m6.5 13-4.327-.022-.242-.001-1.008-.005-.462-.003a.464.464 0 0 1-.323-.793l.329-.324.005-.005.713-.704.172-.17.226-.222A6.452 6.452 0 0 1 0 6.5C0 2.916 2.916 0 6.5 0S13 2.916 13 6.5 10.084 13 6.5 13Zm-2.31-2.012.132-.13L3.094 9.44A4.452 4.452 0 0 1 2 6.5C2 4.02 4.02 2 6.5 2S11 4.02 11 6.5 8.98 11 6.5 11l-2.31-.012ZM14 4c3 2.5 3.273 6.777.855 9.621l1.498 1.508c.304.306.092.836-.335.838L9.755 16A6.651 6.651 0 0 1 5 13.997l1.842.011H6.84C11.945 14.008 15.4 9.007 14 4Z"></path>
                                                </symbol>
                                            </svg>
                                         </span>
                                        <span>Написать</span>
                                    </a>
                                    <span>{truck.user.email}</span>
                                    {truck.user.phones.map(phone => <span key={phone.phone}><a href={`tel:${ phone.phone }`}>{phone.phone}</a></span>)}
                                </Fragment>
                            )

                        ) ||
                        <Fragment>
                            <div className="button-empty" onClick={openAuthModal}>показать контакты и ставку</div>
                            <div>Доступно <b>бесплатно</b> после быстрой регистрации</div>
                        </Fragment>
                    }
                </div>
                <div className="table-right">
                    {userId === truck.user.id && (
                        <Fragment>
                            <a href={`/${truck.id}/edit`}>
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

                    <a href={`/truck/${truck.id}`} className="truck-info">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <svg fill="white" stroke="white" strokeWidth="0" data-qa="icon"
                                 viewBox="0 0 20 20" width="20" height="20"
                                 preserveAspectRatio="none" x="2" y="2">
                                <path fillRule="evenodd" clipRule="evenodd" d="M0 10c0-1.38.26-2.679.781-3.896A10.035 10.035 0 0 1 2.92 2.92 10.035 10.035 0 0 1 6.104.78 9.801 9.801 0 0 1 10 0c1.38 0 2.679.26 3.896.781A10.036 10.036 0 0 1 17.08 2.92a10.037 10.037 0 0 1 2.139 3.184A9.802 9.802 0 0 1 20 10c0 1.38-.26 2.679-.781 3.896a10.038 10.038 0 0 1-2.139 3.184 10.038 10.038 0 0 1-3.183 2.139A9.802 9.802 0 0 1 10 20c-1.38 0-2.679-.26-3.896-.781A10.037 10.037 0 0 1 2.92 17.08a10.036 10.036 0 0 1-2.14-3.183A9.802 9.802 0 0 1 0 10Zm1.25 0c0 1.21.228 2.347.684 3.408a8.813 8.813 0 0 0 1.875 2.783 8.811 8.811 0 0 0 2.783 1.875A8.545 8.545 0 0 0 10 18.75c1.21 0 2.347-.228 3.408-.684a8.811 8.811 0 0 0 2.783-1.875 8.811 8.811 0 0 0 1.875-2.783A8.545 8.545 0 0 0 18.75 10c0-1.21-.228-2.347-.684-3.408a8.811 8.811 0 0 0-1.875-2.783 8.813 8.813 0 0 0-2.783-1.875A8.545 8.545 0 0 0 10 1.25c-1.21 0-2.347.228-3.408.684a8.812 8.812 0 0 0-2.783 1.875 8.812 8.812 0 0 0-1.875 2.783A8.545 8.545 0 0 0 1.25 10Z"></path>
                                <path d="M11.336 8.125v8H8.72v-8h2.616Zm.27-2.839c0 .208-.043.403-.127.586-.084.183-.2.343-.346.481a1.694 1.694 0 0 1-.51.329 1.584 1.584 0 0 1-1.22 0 1.684 1.684 0 0 1-.493-.329 1.519 1.519 0 0 1-.338-.481 1.423 1.423 0 0 1-.122-.586c0-.214.04-.414.122-.6a1.58 1.58 0 0 1 .831-.818 1.634 1.634 0 0 1 1.73.33c.146.14.262.303.346.489.084.185.127.385.127.599Z"></path>
                            </svg>
                        </svg>
                    </a>

                    <div>доб <span>{truck.createdAt}</span></div>
                    { truck.updatedAt && truck.createdAt !== truck.updatedAt && <div>изм { truck.updatedAt }</div>}
                </div>
            </div>
        </div>
    )
}

export default TransportItem;

