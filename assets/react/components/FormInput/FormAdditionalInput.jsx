import React from 'react';

import './FormAdditionalInput.scss';
import {useTranslation} from "react-i18next";

const FormAdditionalInput = ({fieldName, fieldValue, onUpdateName, onUpdateValue}) => {
    const {t} = useTranslation();

    return (
        <div className="additional-input-field">
            <div className="input-name">
                <input type="text"
                       id="field_name"
                       name="field_name"
                       placeholder={t("ContactDetail.field.extendedName")}
                       value={fieldName}
                       onChange={onUpdateName}/>
            </div>
            <div className="input-value">
                <input type="text"
                       id="field_value"
                       name="field_value"
                       placeholder={t("ContactDetail.field.extendedValue")}
                       value={fieldValue}
                       onChange={onUpdateValue}/>
            </div>
        </div>
    );
}

export default FormAdditionalInput;
