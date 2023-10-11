import React from 'react';
import './App.css';
import {Routes, BrowserRouter, Route, Navigate} from 'react-router-dom';
import NotFound from "./pages/problems/404/NotFound";
import Footer from './components/Footer/Footer';

function App() {
    return (
        <>
            <BrowserRouter>
                <Routes>
                    <Route path={"/"} element={<Navigate to={"/home"}/>}/>
                    <Route path={"/home"}/>

                    <Route path={"*"} element={<NotFound/>}/>
                </Routes>
            </BrowserRouter>

            <Footer/>
        </>
    );
}

export default App;
