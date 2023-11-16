import React, {useEffect, useState} from "react";
import ContactsService from "../../../../services/contacts.service";
import ContactCard from "../../../components/ContactCard/ContactCard";
import {useTranslation} from "react-i18next";
import Button from "../../../components/Button/Button";
import Paginator from "../../../components/Paginator/Paginator";
import toast from "react-hot-toast";

import "./ContactsList.scss";

const ContactsList = () => {
    const { t } = useTranslation();

    const [contacts, setContacts] = useState([]);
    const [displayedContacts, setDisplayedContacts] = useState([]);

    const[currentPage, setCurrentPage] = useState(0);
    const[totalPages, setTotalPages] = useState(0);
    const[contactsPerPage] = useState(20);


    useEffect(() => {
        fetchContacts();

        if (localStorage.getItem("toast")) {
            const message = JSON.parse(localStorage.getItem("toast"));
            toast[message.type](message.message);
            localStorage.removeItem("toast");
        }
    }, []);

    const fetchContacts =  () => {
        ContactsService.getAllContacts().then(response => {
            setContacts(response.data);
            setDisplayedContacts(response.data.slice(0, contactsPerPage));
            setTotalPages(Math.ceil(response.data.length / contactsPerPage));
        });
    }

    const handlePageChange = (page) => {
        if (page !== currentPage) {
            setCurrentPage(page);
            setDisplayedContacts(contacts.slice(page * contactsPerPage, (page + 1) * contactsPerPage));
        }
    }

    return (
        <div className="contacts-list">
            <div className="contacts-list__header">
                <h1>Liste des contacts</h1>
                <div className="buttons-container">
                    <Button type="primary"
                            link="/contacts/new"
                            content={t('ContactsList.button.new')} />
                </div>

            {/* TODO: ajouter la recherhche dans les contacts (dans les champs classiques mais aussi dans les champs étendus) */}
            </div>
            <div className="contacts-list__contacts">
                {displayedContacts.map((contact, index) => (
                    <ContactCard key={index} contact={contact} />
                ))
                }
            </div>
            <Paginator currentPage={currentPage} totalPages={totalPages} onPageChange={(page) => handlePageChange(page)} />
        </div>
    );
}

export default ContactsList;

