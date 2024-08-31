import ReactDOM from "react-dom/client";
import MyCompany from "./pages/my-company/MyCompany";
import React from "react";

const loadList = ReactDOM.createRoot(document.getElementById('myCompany'));
loadList.render(<MyCompany />);
