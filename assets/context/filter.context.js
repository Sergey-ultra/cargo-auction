import {createContext} from 'react'

function noop () {}

export const FilterContext = createContext({
    filter: {},
    setFilter: noop,
    clearFilter: noop,
    changeFilterAddresses: noop,
})
