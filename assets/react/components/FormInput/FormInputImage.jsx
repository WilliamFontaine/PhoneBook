import React from "react";

import "./FormInput.scss";
import {IoIosCloseCircleOutline} from "react-icons/io";
import {useTranslation} from "react-i18next";

const FormInputImage = ({label, name, imageUrl, onChange, deleteImage}) => {
    const {t} = useTranslation();
    return (
        <div className="image_container">
            <div className="input-field">
                <label htmlFor={name}>{label}</label>
                <div className="input">
                    <input
                        type="file"
                        id={name}
                        name={name}
                        onChange={onChange}
                        accept="image/*"
                    />
                </div>
            </div>
            {imageUrl &&
                <div className="display-image">
                    <p>{t("FormInput.imagePreview")}</p>
                    <div className="delete-icon">
                        <IoIosCloseCircleOutline className="icon" onClick={deleteImage}/>
                    </div>
                    <img src={imageUrl} alt="contact image" className="image"/>
                </div>
            }
        </div>
    )
}

export default FormInputImage;

