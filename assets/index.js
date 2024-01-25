import React from 'react';
import ReactDOM from 'react-dom/client';
import '../public/assets/css/app.css';
import Nav from './Nav';
import LoadList from './LoadList';

// ReactDOM.render(<App/>, document.getElementById('nav'));

const nav = ReactDOM.createRoot(document.getElementById('nav'));
nav.render(<Nav />);

const loadList = ReactDOM.createRoot(document.getElementById('loadList'));
loadList.render(<LoadList />);


