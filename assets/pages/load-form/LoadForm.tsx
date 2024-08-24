import React from "react";
import { LocalizationProvider } from '@mui/x-date-pickers';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs'
import LoadForm from "./LoadFormInner";


function LoadFormForm() {
    return (
        <LocalizationProvider dateAdapter={AdapterDayjs}>
            <LoadForm/>
        </LocalizationProvider>
    );
}

export default LoadFormForm;
