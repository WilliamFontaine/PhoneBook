import React from "react";

import "./Button.scss";

const FormButton = ({buttonType, content, type, onClick}) => {
    return (
        <div className={`button button-${buttonType}`}>
            <button onClick={onClick} type={type}>{content}</button>
        </div>

    )
}

export default FormButton;
