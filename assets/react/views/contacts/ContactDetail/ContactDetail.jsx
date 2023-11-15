import React, {useEffect, useState} from "react";
import ContactsService from "../../../../services/contacts.service";
import {useTranslation} from "react-i18next";
import toast from "react-hot-toast";

const ContactDetail = ({id, context}) => {
    const [contact, setContact] = useState(null);
    const [action, setAction] = useState(null);
    const { t } = useTranslation();

    console.log(contact)

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
                toast.success(t('ContactDetail.toast.success'));
            }).catch(
                error => {
                    toast.error(t('ContactDetail.toast.error'));
                    console.log(error);
                    // TODO : handle error
                }
            );
        } else {
            ContactsService.updateContact(contact.id, contact).then(response => {
                setContact(response.data);
                toast.success(t('ContactDetail.toast.updated'));
            }).catch(
                error => {
                    toast.error(t('ContactDetail.toast.error'));
                    console.log(error);
                //     TODO : handle error
                }
            );
        }
    };

    const handleDelete = (event) => {
        event.preventDefault();
        ContactsService.deleteContact(id).then(response => {
            setContact(response.data);
        });
    }

    const handleInputChange = (event) => {
        const {name, value} = event.target;
        setContact(prevContact => ({
            ...prevContact,
            [name]: value
        }));
    };

    return (
        <div>
            <h1>Contact Detail</h1>
            {contact && (
            <form onSubmit={handleSubmit}>
                <div className="input-field">
                    <label htmlFor="firstname">{t('ContactDetail.field.firstName')}</label>
                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        value={contact.firstname}
                        onChange={handleInputChange}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="lastname">{t('ContactDetail.field.lastName')}</label>
                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        value={contact.lastname}
                        onChange={handleInputChange}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="email">{t('ContactDetail.field.email')}</label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        value={contact.email}
                        onChange={handleInputChange}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="phone">{t('ContactDetail.field.phone')}</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value={contact.phone}
                        onChange={handleInputChange}
                    />
                </div>

                {/* TODO: ajouter la photo de profil */}

                {/* TODO: ajouter des champ aditionnel à la vollée */}

                <div className="input-field">
                    <button type="submit">{t('ContactDetail.button.submit')}</button>
                </div>
                {action === "PUT" && (
                    <div className="input-field">
                        <button type="button" onClick={handleDelete}>{t('ContactDetail.button.delete')}</button>
                    </div>
                )}
            </form>
            )}
        </div>
    );
}


export default ContactDetail;
