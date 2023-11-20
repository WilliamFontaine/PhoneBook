import React, {useEffect, useState} from "react";
import ContactsService from "../../../../services/contacts.service";
import {useTranslation} from "react-i18next";
import toast from "react-hot-toast";

import "./ContactDetail.scss";
import FormButton from "../../../components/Button/FormButton";
import FormInput from "../../../components/FormInput/FormInput";
import Button from "../../../components/Button/Button";

const ContactDetail = ({id}) => {
    const [contact, setContact] = useState(null);
    const [errors, setErrors] = useState([]);
    const [action, setAction] = useState(null);
    const {t} = useTranslation();


    useEffect(() => {
        if (id !== null) {
            fetchContact();
            setAction("PUT");
        } else {
            setAction("POST");
            setContact({
                firstname: "",
                lastname: "",
                email: "",
                phone: ""
            });
        }
    }, [location.search]);

    const fetchContact = () => {
        ContactsService.getContactById(id).then(response => {
            setContact(response.data);
        });
    };

    const handleSubmit = (event) => {
        event.preventDefault();

        if (action === "POST") {
            ContactsService.createContact(contact).then(response => {
                setContact(response.data);
                setAction("PUT");
                setErrors([]);
                toast.success(t('ContactDetail.toast.success'));
            }).catch(
                error => {
                    toast.error(t('ContactDetail.toast.error'));
                    handleErrors(error.response.data);
                }
            );
        } else {
            ContactsService.updateContact(contact.id, contact).then(response => {
                setContact(response.data);
                toast.success(t('ContactDetail.toast.updated'));
                setErrors([])
            }).catch(
                error => {
                    toast.error(t('ContactDetail.toast.error'));
                    handleErrors(error.response.data);
                }
            );
        }
    };

    const handleErrors = (errors) => {
        setErrors([])
        errors.forEach(error => {
            setErrors(prevErrors => ({
                ...prevErrors,
                [error["property_path"]]: error.message
            }));
        });
    }

    const handleDelete = (event) => {
        event.preventDefault();
        ContactsService.deleteContact(id).then(response => {
            setContact(response.data);
            localStorage.setItem("toast", JSON.stringify({
                type: "success",
                message: t('ContactDetail.toast.deleted')
            }));
            window.location.href = "/contacts";
        });
    }

    const handleInputChange = (event) => {
        const {name, value} = event.target;
        setContact(prevContact => ({
            ...prevContact,
            [name]: value
        }));
    };

    const handleProfilePictureChange = (event) => {
        const {name, files} = event.target;
        setContact(prevContact => ({
            ...prevContact,
            [name]: files[0]
        }));
    }

    return (
        <div className="contact-detail">
            <h1>Contact Detail</h1>
            {contact && (
                <form onSubmit={handleSubmit} encType={"multipart/form-data"}>
                    <FormInput label={t('ContactDetail.field.firstName')}
                               type="text"
                               name="firstname"
                               value={contact.firstname}
                               onChange={handleInputChange}
                               error={errors.firstname}/>
                    <FormInput label={t('ContactDetail.field.lastName')}
                               type="text"
                               name="lastname"
                               value={contact.lastname}
                               onChange={handleInputChange}
                               error={errors.lastname}/>
                    <FormInput label={t('ContactDetail.field.email')}
                               type="text"
                               name="email"
                               value={contact.email}
                               onChange={handleInputChange}
                               error={errors.email}/>
                    <FormInput label={t('ContactDetail.field.phone')}
                               type="text"
                               name="phone"
                               value={contact.phone}
                               onChange={handleInputChange}
                               error={errors.phone}/>
                    <FormInput label={t('ContactDetail.field.profilePicture')}
                               type="file"
                               name="profilePicture"
                               value={contact.profilePicture}
                               onChange={handleProfilePictureChange}
                               error={errors.profilePicture}/>


                    {/* TODO: ajouter des champ aditionnel à la vollée */}

                    <div className="buttons-container">
                        <div className="button-field">
                            <Button type="secondary"
                                    content={t('ContactDetail.button.return')}
                                    link="/contacts"/>
                        </div>
                        <div className="button-field">
                            <FormButton buttonType="primary"
                                        type="submit"
                                        content={t('ContactDetail.button.submit')}/>
                        </div>
                        {action === "PUT" && (
                            <div className="button-field">
                                <FormButton buttonType="danger"
                                            type="button"
                                            content={t('ContactDetail.button.delete')}
                                            onClick={handleDelete}/>
                            </div>
                        )}
                    </div>
                </form>
            )}
        </div>
    );
}


export default ContactDetail;
