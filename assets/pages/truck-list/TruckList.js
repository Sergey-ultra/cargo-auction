import React, {Fragment, useEffect, useState} from 'react';
import {Button, MenuItem, Select} from "@mui/material";
import {useHttp} from "../../hooks/api";
import {setQuery} from "../../hooks/queryParams";
import Pagination from "../../components/common/Pagination";
import LoadItem from "../load-list/src/LoadItem";


function TruckList() {
    const { request, isLoading, error, clearError } = useHttp();
    return '';
}

export default TruckList;
