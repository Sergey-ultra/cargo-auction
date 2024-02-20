import ReactDOM from "react-dom/client";
import Transport from "./pages/transport-list/Transport";
import React from "react";

const loadList = ReactDOM.createRoot(document.getElementById('transportList'));
loadList.render(<Transport />);
