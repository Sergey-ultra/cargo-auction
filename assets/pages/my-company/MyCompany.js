import React, {useEffect, useState} from 'react';
import {useHttp} from "../../hooks/api";
import {Button, TextField} from "@mui/material";



function MyCompany() {
    const [company, setCompany] = useState({
        name: '',
        description: '',
    });
    const {request} = useHttp();

    const saveCompany = async e => {
        e.preventDefault();
        console.log(company);
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
                <div className="form-item">
                    <label className="label">Месторасположение центрального офиса или город юридической регистрации*</label>

                </div>
                <div className="form-item">
                    <label className="label">Правовая форма и название</label>
                    <TextField size="small" value={company.name} onChange={e => setCompany({...company, name: e.target.value})}/>
                    <span>например: ООО АльфаТранс</span>
                </div>
                <div className="form-item">
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

