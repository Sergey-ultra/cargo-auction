import {useState,useCallback}  from'react'
export const useHttp = () => {
    const[isLoading,setLoading] = useState(false)
    const[error,setError] = useState(null)
    const request = useCallback(async (url, method ='GET', body = null, headers = {}) => {
        setLoading(true)
        try {
            Object.assign(headers,{
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            });

            if ('GET' !== method && body) {
                body = JSON.stringify(body)
            }
            const response = await fetch(url,{ method, body, headers })
            const data = await response.json()
            // console.log(data)
            if (!response.ok) {
                throw new Error(data.message || 'error')
            }

            return data
        } catch (e) {
            setError(e.message)
            throw e
        } finally {
            setLoading(false);
        }
    },[])
    const clearError = useCallback(() => setError(null),[])
    return {isLoading, error, clearError, request}
}
