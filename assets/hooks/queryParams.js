export const setQuery = params => {
    const url = new URL(window.location);
    const searchParams = url.searchParams;

    for(let [name, value] of Object.entries(params)) {
        if (['null', null, ''].includes(value)) {
            searchParams.delete(name);
        } else if (! ['page', 'perPage'].includes(name) && String(searchParams.get(name)) !== String(value)) {
            searchParams.delete('page');
            searchParams.delete('perPage');
            searchParams.set(name, String(value));
        } else if (name === 'page') {
            if (value > 1) {
                searchParams.set('page', String(value));
            } else {
                searchParams.delete('page');
            }
        } else if (name === 'perPage') {
            if (value > 10) {
                searchParams.set('perPage', String(value));
            } else {
                searchParams.delete('perPage');
            }
        }
    }

    url.search = searchParams.toString();
    const newUrl = url.toString();

    window.history.pushState({path: newUrl}, '', newUrl);
}
