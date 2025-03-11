import {useState,useCallback}  from'react';

type HTTPMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

interface Params {
    headers: Object|undefined,
    params: Object|undefined,
    body: Object|undefined,
    form: Object|undefined,
}

export const useHttp = () => {
    const[isLoading, setLoading] = useState<boolean>(false);
    const[status, setStatus] = useState<number>(0);
    const[error, setError] = useState<string|null>(null);

    const request = useCallback(async (url: string, method: HTTPMethod ='GET', params: Params = {}): Promise<void> => {
        setLoading(true);

        const headers: Object = {
            'Accept': 'application/json',
        }

        if (!params.hasOwnProperty('form')) {
            headers['Content-Type'] = 'application/json';
        }

        if (params.hasOwnProperty('headers')) {
            Object.assign(headers, params.headers);
        }

        let body = null;

        if (method === 'GET') {
            if (params && params.hasOwnProperty('params')) {
                let queryArray: string[] = [];
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
        } else if (params.hasOwnProperty('form')) {
            body = params.form
        }

        const fetchParams = { method, body, headers };

        if (params.hasOwnProperty('signal') && params.signal) {
            fetchParams.signal = params.signal;
        }
        if (params.hasOwnProperty('credentials') && params.credentials) {
            fetchParams.credentials = 'include';
        }

        try {
            const response: Response = await fetch(url, fetchParams);
            const data = await response.json();

            setStatus(response.status);

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
            console.log('error',e, status);
            setError(e.message);
            throw e;
        } finally {
            console.log(error, status);
            await setLoading(false);
        }
    },[]);

    const clearError = useCallback(() => setError(null),[]);

    return {isLoading, error, clearError, request, status};
}
