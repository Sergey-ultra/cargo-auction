import {createContext} from 'react'

function noop () {}

export const NotificationContext = createContext({
    message: null,
    notify: noop,
    clearNotification: noop,
    setMessage: noop,
})
