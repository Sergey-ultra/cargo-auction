import {Button, styled} from "@mui/material";

const FindButton = styled(Button)(({ theme }) => ({
    color: theme.palette.getContrastText('#00a400'),
    backgroundColor: '#00a400',
    height: '28px',
    borderRadius: '14px',
    '&:hover': {
        backgroundColor: '#00a400',
    },
}));

export default FindButton;
