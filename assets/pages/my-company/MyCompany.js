import React, {useEffect, useState} from 'react';
import {useHttp} from "../../hooks/api";
import {Button, TextField} from "@mui/material";
import AutocompleteAddress from "../../components/AutocompleteAddress";


function MyCompany() {
    const [company, setCompany] = useState({
        name: '',
        description: '',
        cityId: null,
    });
    const {request} = useHttp();

    const setCityId = value => setCompany({...company, cityId: value});

    const saveCompany = async e => {
        e.preventDefault();
        if (company.name) {
            const {data} = await request('/api/company', 'POST', {body: {...company}});
        }
    }

    const fetchMyCompany = async() => {
        const { data } = await request('/api/company/my');
        if (data && typeof data === 'object') {
            setCompany({...data});
        }
    }

    useEffect(() => {
        fetchMyCompany()
    }, []);

    return (
        <div className="fieldset">
            <form onSubmit={saveCompany}>
                <div className="flex">
                    <label className="label">Месторасположение центрального офиса или город юридической регистрации*</label>
                    <AutocompleteAddress
                        initialValue={company.cityId}
                        setAddressValue={setCityId}
                        label="Откуда"/>
                </div>
                <div className="flex">
                    <label className="label">Правовая форма и название</label>
                    <TextField size="small" value={company.name} onChange={e => setCompany({...company, name: e.target.value})}/>
                    <span>например: ООО АльфаТранс</span>
                </div>
                <div className="flex">
                    <label className="label">Описание компании</label>
                    <TextField size="small" value={company.description} onChange={e => setCompany({...company, description: e.target.value})}/>
                </div>

                <Button variant="contained" type="submit" className="button button-primary button-small"
                        sx={{marginRight: 2}}>Сохранить компанию</Button>
            </form>
        </div>
    );
}

export default MyCompany;

