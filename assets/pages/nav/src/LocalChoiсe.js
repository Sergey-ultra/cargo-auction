import React from 'react';
import {useCookie} from "../../../hooks/cookie";
import {FormControl, MenuItem, Select} from "@mui/material";

export default function LocalChoice({}) {
    const {getCookie, setCookie} = useCookie();
    const setLocal = event => {
        setCookie('locale', event.target.value);
        window.location.reload();
    }
    const currentLocal = getCookie('locale') ?? 'en';

    return (
        <FormControl variant="standard">
            <Select
                size="small"
                value={currentLocal}
                label="Local"
                onChange={setLocal}>
                <MenuItem value='en'>
                    <svg className="-f-icon-l" width="16" height="16" viewBox="0 0 28 18">
                        <path
                            d="M0 15.5v-13L1.75 0h8.75l3.5.5 3.5-.5h8.75L28 2.5v13L26.25 18H17.5l-3.5-.5-3.5.5H1.75L0 15.5Z"
                            fill="#346DA6"></path>
                        <path d="M0 6h28v6H0V6Z" fill="#fff"></path>
                        <path d="M0 7h28v4H0V7Z" fill="#C24638"></path>
                        <path d="M10.5 0h7v18h-7V0Z" fill="#fff"></path>
                        <path d="M11.667 0h4.666v18h-4.666V0Z" fill="#C24638"></path>
                        <path d="M28 0h-1.75L17.5 5v1.5l4.667-.5L28 2.5V0Z" fill="#fff"></path>
                        <path d="M28 0 17.5 6h2.917L28 1.5V0Z" fill="#C24638"></path>
                        <path d="M0 0h1.75l8.75 5v1.5L5.833 6 0 2.5V0Z" fill="#fff"></path>
                        <path d="m0 0 10.5 6H7.583L0 1.5V0Z" fill="#C24638"></path>
                        <path d="M0 18h1.75l8.75-5v-1.5l-4.667.5L0 15.5V18Z" fill="#fff"></path>
                        <path d="m0 18 10.5-6H7.583L0 16.5V18Z" fill="#C24638"></path>
                        <path d="M28 18h-1.75l-8.75-5v-1.5l4.667.5L28 15.5V18Z" fill="#fff"></path>
                        <path d="m28 18-10.5-6h2.917L28 16.5V18Z" fill="#C24638"></path>
                    </svg>
                    <span>En</span>
                </MenuItem>
                <MenuItem value='ru'>Ru</MenuItem>
            </Select>
        </FormControl>
    );
}