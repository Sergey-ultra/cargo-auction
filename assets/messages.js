import ReactDOM from "react-dom/client";
import Messages from "./pages/messages/Messages";
import React from "react";

const messages = ReactDOM.createRoot(document.getElementById('messages'));
messages.render(<Messages />);
