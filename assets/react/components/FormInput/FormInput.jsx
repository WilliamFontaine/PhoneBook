import React from "react";

import "./FormInput.scss";
import {useTranslation} from "react-i18next";

const FormInput = ({label, type, name, value, onChange, error}) => {

    const { t } = useTranslation();



    return (
        <div className="input-field">
            <label htmlFor={name}>{label}</label>
            <div className="input">
                <input
                    type={type}
                    id={name}
                    name={name}
                    onChange={onChange}
                    value={value}
                />
                {error && <div className="error">
                    {t(`ContactDetail.error.${error}`)}
                </div>}
            </div>

        </div>
    )
}

export default FormInput;
