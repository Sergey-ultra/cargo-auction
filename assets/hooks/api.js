import {useState,useCallback}  from'react';
export const useHttp = () => {
    const[isLoading, setLoading] = useState(false);
    const[status, setStatus] = useState(0);
    const[error, setError] = useState(null);

    const request = useCallback(async (url, method ='GET', params = {}) => {
        setLoading(true);

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }

        if (params.hasOwnProperty('headers')) {
            Object.assign(params.headers, headers);
        }

        let body = null;

        if (method === 'GET') {
            if (params && params.hasOwnProperty('params')) {
                let queryArray = []
                for (let [key, value] of Object.entries(params.params)) {
                    if (Array.isArray(value) && value.length) {
                        value.forEach(el => queryArray.push(`${key}[]=${el}`))
                    } else if (("string" === typeof value && value !== '') || ["number", "boolean"].includes(typeof value)) {
                        queryArray.push(`${key}=${value}`);
                    }
                }
                delete params.params

                if (queryArray.length) {
                    url += '?' + queryArray.join('&')
                }
            }
        } else if (params.hasOwnProperty('body')) {
            body = JSON.stringify(params.body);
        }

        const fetchParams = { method, body, headers };

        if (params.hasOwnProperty('credentials') && params.credentials) {
            fetchParams.credentials = 'include';
        }

        try {
            const response = await fetch(url, fetchParams);
            const data = await response.json();
            await setStatus(response.status);

            if (!response.ok) {
                if (response.status === 422) {
                    setError([...data.violations]);
                    console.log(response)
                } else if (response.status === 401) {
                    setError(data.message);
                } else {
                    setError(data.error);
                }
            }

            return data
        } catch (e) {
            console.log(error, status);
            setError(e.message);
            //throw e;
        } finally {
            console.log(error, status);
            await setLoading(false);
        }
    },[]);

    const clearError = useCallback(() => setError(null),[]);

    return {isLoading, error, clearError, request, status};
}
