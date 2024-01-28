import React, {useState} from 'react';
import DialogTitle from '@mui/material/DialogTitle';
import Dialog from '@mui/material/Dialog';
import {Button, DialogContent} from "@mui/material";
import {useHttp} from "../hooks/api";


export default function SendBidModal({ handleClose, isOpen, currentLoadId }) {
    const [bid, setBid] = useState(0);

    const changeHandler = event => setBid(event.target. value);


    const { request, isLoading, error, clearError } = useHttp();

    const sendBid = async e => {
        e.preventDefault();
        try {
            const { data } = await request(`/api/sendBid/${currentLoadId}`, 'POST', {body: {bid: bid}});

            handleClose();
        } catch (e) {
            console.log(e.message)
        }

    }

    return (
        <Dialog onClose={handleClose} open={isOpen}>
            <DialogTitle>Отправить ставку</DialogTitle>
            <DialogContent>
                <form onSubmit={sendBid}>
                    <div className="form-item">
                        <div className="label">Ваша ставка</div>
                        <div className="input">
                            <input type="number" name="bid" value={bid} onChange={changeHandler}/>
                        </div>
                    </div>

                    <Button variant="outlined" type="submit" className="button button-primary button-small"
                            sx={{marginRight: 2}} disabled={isLoading}>Отправить ставку</Button>

                </form>
            </DialogContent>
        </Dialog>
    );
}
