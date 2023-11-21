import React, {useEffect, useState} from "react";
import ContactCard from "../../../components/ContactCard/ContactCard";
import {useTranslation} from "react-i18next";
import Button from "../../../components/Button/Button";
import Paginator from "../../../components/Paginator/Paginator";
import toast from "react-hot-toast";

import "./ContactsList.scss";
import ContactsController from "../../../../controllers/contacts.controller";
import ImagesController from "../../../../controllers/images.controller";

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
        ContactsController.getAllContacts().then(response => {
            setContacts(response.data);
            setDisplayedContacts(response.data.slice(0, contactsPerPage));
            setTotalPages(Math.ceil(response.data.length / contactsPerPage));

            response.data.forEach(contact => {
              // TODO: do a generic method
                if (contact.image_name) {
                    ImagesController.getImageByName(contact.image_name)
                        .then(res => {
                            const blob = new Blob([res.data]);
                            const url = URL.createObjectURL(blob);
                            const img = document.getElementById(contact.image_name)
                            img.src = url;
                            img.onload = e => URL.revokeObjectURL(url);
                    })
                }
            })
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

