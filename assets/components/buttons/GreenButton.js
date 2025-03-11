import {Button, styled} from "@mui/material";

const GreenButton = styled(Button)({
    color: '#fff',
    backgroundColor: '#35c25e',
    borderColor: '#111827',
    height: '28px',
    fontSize: '13px',
    borderRadius: '14px',
    padding: '0 2rem',
    '&:hover': {
        backgroundColor: '#2da44f',
        borderColor: '#111827'
    },
});

export default GreenButton;
