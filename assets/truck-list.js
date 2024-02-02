import ReactDOM from "react-dom/client";
import TruckList from "./pages/truck-list/TruckList";
import React from "react";

const loadList = ReactDOM.createRoot(document.getElementById('truckList'));
loadList.render(<TruckList />);
