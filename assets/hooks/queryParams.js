export const setQuery = params => {
    let searchParams = new URLSearchParams(window.location.search);

    for(let [name, value] of Object.entries(params)) {
        if (['null', null, ''].includes(value)) {
            searchParams.delete(name);
        } else if (name !== 'page' && String(searchParams.get(name)) !== String(value)) {
            searchParams.delete('page');
            searchParams.set(name, String(value));
        } else if (name === 'page') {
            if (value > 1) {
                searchParams.set('page', String(value));
            } else {
                searchParams.delete('page');
            }
        }
    }

    let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + searchParams.toString();
    window.history.pushState({path: newurl}, '', newurl);
}
