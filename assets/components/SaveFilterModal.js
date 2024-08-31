import React, {useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent} from "@mui/material";


export default function SaveFilterModal({ handleClose, saveFilter, isOpen }) {
    const [name, setName] = useState('');
    const changeName = event => setName(event.target.value);

    const save = e => {
        e.preventDefault();
        saveFilter(name);
        setName('');
    }

    return (
        <Dialog onClose={handleClose} open={isOpen}>
            <DialogTitle>Сохранить фильтр</DialogTitle>
            <DialogContent>
                <form onSubmit={save}>
                    <div className="flex">
                        <div className="label">Имя фильтров</div>
                        <div className="input">
                            <input name="name" value={name} onChange={changeName}/>
                        </div>
                    </div>

                    <Button variant="contained" type="submit" className="button button-primary button-small"
                            sx={{marginRight: 2}}>Сохранить фильтр</Button>

                </form>
            </DialogContent>
        </Dialog>
    );
}
