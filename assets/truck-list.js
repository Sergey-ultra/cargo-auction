import ReactDOM from "react-dom/client";
import Truck from "./pages/truck-list/Truck";
import React from "react";

const loadList = ReactDOM.createRoot(document.getElementById('truckList'));
loadList.render(<Truck />);
