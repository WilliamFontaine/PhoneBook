import React from "react";

import "./FormInput.scss";

const FormInputImage = ({label, name, value, onChange}) => {

    return (
        <div className="input_field">
            <label htmlFor={name}>{label}</label>
            <div className="input">
                <input
                    type="file"
                    id={name}
                    name={name}
                    onChange={onChange}
                    value={value}
                    accept="image/*"
                />
            </div>
        </div>
    )
}

export default FormInputImage;
