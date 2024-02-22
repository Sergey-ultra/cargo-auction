import ReactDOM from "react-dom/client";
import LoadForm from "./pages/load-form/LoadForm";
import React from "react";
import "./components/custom-tabs/custom-tabs.css"

const form = ReactDOM.createRoot(document.getElementById('load-form'));
form.render(<LoadForm />);
