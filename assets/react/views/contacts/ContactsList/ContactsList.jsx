import React, {useEffect, useState} from "react";
import ContactsService from "../../../../services/contacts.service";

import "./ContactsList.scss";
import ContactCard from "../../../components/ContactCard/ContactCard";
import {useTranslation} from "react-i18next";
import Button from "../../../components/Button/Button";

const ContactsList = () => {
    const [contacts, setContacts] = useState([]);
    const { t, i18n } = useTranslation();

    useEffect(() => {
        fetchContacts();
    }, []);

    const fetchContacts =  () => {
        ContactsService.getAllContacts().then(response => {
            setContacts(response.data);
        });
    }

    return (
        <div className="contacts-list">
            <h1>Liste des contacts</h1>
            <Button id=""
                    content={t("ContactList.button.new")}
                    type="success" />
            <div className="contacts-list__contacts">
                {contacts.map((contact, index) => (
                    <ContactCard key={index} contact={contact} />
                ))
                }
            </div>
        </div>
    );
}

export default ContactsList;

