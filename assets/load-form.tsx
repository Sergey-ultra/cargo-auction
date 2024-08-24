import ReactDOM from "react-dom/client";
import React from "react";
import LoadFormForm from "./pages/load-form/LoadForm";
import './i18n';

const form = ReactDOM.createRoot(document.getElementById('load-form'));
form.render(<LoadFormForm/>);
