import React from "react";
// import { useTranslation } from 'react-i18next';
// // import '../../../../i18n';
// const { t } = useTranslation();
const DownloadingDateStatus = {
    FROM_DATE: 'from-date',
    PERMANENTLY: 'permanently',
    REQUEST: 'request',
};

const PeriodicityOptions = [
    {
        value: 'everyday', title: 'daily',
    },
    {
        value: 'workdays', title: 'workDays',
    }
];

const LoadingTypes = {
    DEFAULT_LOADING_TYPE: 4
};

const BodyTypes = {
    HAS_TEMPERATURE: [50000, 300, 312, 310],
};

const FixedDurations = [
    {
        title: 'fixedDurations.10m',
        value: '10m',
    },
    {
        title: 'fixedDurations.15m',
        value: '15m',
    },
    {
        title: 'fixedDurations.30m',
        value: '30m',
    },
    {
        title: 'fixedDurations.1h',
        value: '1h',
    },
    {
        title: 'fixedDurations.2h',
        value: '2h',
    },
    {
        title: 'fixedDurations.3h',
        value: '3h',
    },
    {
        title: 'fixedDurations.4h',
        value: '4h',
    },
    {
        title: 'fixedDurations.6h',
        value: '6h',
    },
];


export {
    DownloadingDateStatus,
    PeriodicityOptions,
    LoadingTypes,
    BodyTypes,
    FixedDurations,
};
