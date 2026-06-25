import React from "react";
import ReactDOM from "react-dom/client";
import AiChat from "./components/AiChat";
import Sortable from 'sortablejs';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Sortable = Sortable;
window.Quill = Quill;

const root = document.getElementById("ai-chat");
if (root) {
    ReactDOM.createRoot(root).render(<AiChat />);
}

