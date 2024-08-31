import {useCallback, useState} from 'react'

export const useNotification = () => {
    const[message, setMessage] = useState(null);

    const notify = useCallback(text => {
        setMessage(text);
    }, []);

    const clearNotification = useCallback(() => {
        setMessage(null);
    }, []);

    return {notify, message, setMessage, clearNotification}
}
