import React, { Component } from 'react';
import "bootstrap/dist/css/bootstrap.min.css";
import ReactDom from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import HomePage from './src/HomePage';

class App extends Component {
    render() {
        return (
            <BrowserRouter>
                <div>
                    <HomePage/>
                </div>
            </BrowserRouter>
        )
    }
}

ReactDom.render(<App />, document.getElementById('root'));