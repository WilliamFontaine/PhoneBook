import React, {useEffect, useState} from "react";
import ContactsService from "../../../../services/contacts.service";
import ContactCard from "../../../components/ContactCard/ContactCard";
import {useTranslation} from "react-i18next";
import Button from "../../../components/Button/Button";
import Paginator from "../../../components/Paginator/Paginator";

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
            <h1>Liste des contacts</h1>
            <Button id=""
                    content={t("ContactList.button.new")}
                    type="success" />
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

