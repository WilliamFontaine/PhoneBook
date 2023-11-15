import React from "react";
import {Toaster} from "react-hot-toast";

import "./App.scss";
const App = () => {
    return (
        <div className="app">
            <Toaster
                toastOptions={{
                    error: {
                        duration: 10000,

                    }
                }}
            />
        </div>
    )
}

export default App;
