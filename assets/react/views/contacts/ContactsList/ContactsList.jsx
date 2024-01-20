import React, { useEffect, useState } from "react";
import ContactCard from "../../../components/Card/ContactCard";
import { useTranslation } from "react-i18next";
import Button from "../../../components/Button/Button";
import Paginator from "../../../components/Paginator/Paginator";
import toast from "react-hot-toast";

import "./ContactsList.scss";
import ContactsController from "../../../../controllers/contacts.controller";
import ImagesController from "../../../../controllers/images.controller";
import ImageService from "../../../../services/image.service";

const ContactsList = () => {
  const { t } = useTranslation();

  const [contacts, setContacts] = useState([]);
  const [filteredContacts, setFilteredContacts] = useState([]);
  const [displayedContacts, setDisplayedContacts] = useState([]);
  const [contactImages, setContactImages] = useState({});

  const [currentPage, setCurrentPage] = useState(0);
  const [totalPages, setTotalPages] = useState(0);
  const [contactsPerPage] = useState(20);

  const [extendedFilters, setExtendedFilters] = useState([]);
  const [selectedFilter, setSelectedFilter] = useState('');
  const [filterValue, setFilterValue] = useState('');


  useEffect(() => {
    fetchContacts();

    if (localStorage.getItem("toast")) {
      const message = JSON.parse(localStorage.getItem("toast"));
      toast[message.type](message.message);
      localStorage.removeItem("toast");
    }
  }, []);

  const fetchContacts = () => {
    const localExtendedFilters = [];
    ContactsController.getAllContacts().then(response => {
      setContacts(response.data);
      setFilteredContacts(response.data);
      setDisplayedContacts(response.data.slice(0, contactsPerPage));
      setTotalPages(Math.ceil(response.data.length / contactsPerPage));

      response.data.forEach(contact => {
        if (contact.image_name) {
          ImagesController.getImageByName(contact.image_name)
            .then(res => {
              const imageUrl = ImageService.handleFile(res.data, contact.id);
              setContactImages((prevImages) => ({
                ...prevImages,
                [contact.id]: imageUrl,
              }));
            })
        }
        if (contact.contact_extended_fields) {
          for (const extendedField of contact.contact_extended_fields) {
            if (!localExtendedFilters.includes(extendedField.field_name)) {
              localExtendedFilters.push(extendedField.field_name);
            }
          }
        }
      })
      setExtendedFilters(localExtendedFilters);
    });
  }

  const handlePageChange = (page) => {
    if (page !== currentPage) {
      setCurrentPage(page);
      setDisplayedContacts(filteredContacts.slice(page * contactsPerPage, (page + 1) * contactsPerPage));
    }
  }

  const handleFilterSelectChange = (e) => {
    setSelectedFilter(e.target.value);
    handleFilterValueContacts({ target: { value: filterValue, selectedFilter: e.target.value } });

  }

  const handleFilterValueContacts = (e) => {
    let value = e.target.value;
    setFilterValue(value);
    let filter = e.target?.selectedFilter !== undefined ? e.target.selectedFilter : selectedFilter;


    if (filter !== '' && value !== '') {
      if (filter === 'groups') {
        setFilteredContacts(contacts.filter(contact => contact.groups.find(group => group.name.toLowerCase().includes(value.toLowerCase()))));
        setDisplayedContacts(contacts.filter(contact => contact.groups.find(group => group.name.toLowerCase().includes(value.toLowerCase()))).slice(0, contactsPerPage));
        setTotalPages(Math.ceil(contacts.filter(contact => contact.groups.find(group => group.name.toLowerCase().includes(value.toLowerCase()))).length / contactsPerPage));
      } else if (filter.includes('contact_extended_fields')) {
        const extendedFilter = filter.split('.')[1];

        setFilteredContacts(contacts.filter(contact => contact.contact_extended_fields.find(field => field.field_name === extendedFilter && field.field_value.toLowerCase().includes(value.toLowerCase()))));
        setDisplayedContacts(contacts.filter(contact => contact.contact_extended_fields.find(field => field.field_name === extendedFilter && field.field_value.toLowerCase().includes(value.toLowerCase()))).slice(0, contactsPerPage));
        setTotalPages(Math.ceil(contacts.filter(contact => contact.contact_extended_fields.find(field => field.field_name === extendedFilter && field.field_value.toLowerCase().includes(value.toLowerCase()))).length / contactsPerPage));
      } else {
        setFilteredContacts(contacts.filter(contact => contact[filter]?.toLowerCase().includes(value?.toLowerCase())));
        setDisplayedContacts(contacts.filter(contact => contact[filter]?.toLowerCase().includes(value?.toLowerCase())).slice(0, contactsPerPage));
        setTotalPages(Math.ceil(contacts.filter(contact => contact[filter]?.toLowerCase().includes(value?.toLowerCase())).length / contactsPerPage));
      }
    } else {
      setFilteredContacts(contacts);
      setDisplayedContacts(contacts.slice(0, contactsPerPage));
      setTotalPages(Math.ceil(contacts.length / contactsPerPage));
    }
  }

  return (
    <div className="contacts-list">
      <div className="contacts-list__header">
        <h1>{t('ContactsList.title')}</h1>
        <div className="buttons-container">
          <Button type="primary"
            link="/contacts/new"
            content={t('ContactsList.button.new')} />
        </div>


        <div className="filter-container">
          <p>{t('ContactsList.filter.title')}</p>
          <div className="filters">
            <select className="select" onChange={(event) => handleFilterSelectChange(event)}>
              <option value="">{t('ContactsList.filter.select')}</option>
              <option value="firstname">{t('ContactsList.filter.firstname')}</option>
              <option value="lastname">{t('ContactsList.filter.lastname')}</option>
              <option value="email">{t('ContactsList.filter.email')}</option>
              <option value="phone">{t('ContactsList.filter.phone')}</option>
              <option value="groups">{t('ContactsList.filter.groups')}</option>
              {extendedFilters.map((filter, index) => (
                <option key={index} value={`contact_extended_fields.${filter}`}>{filter}</option>
              ))}
            </select>
            <input type="text"
              placeholder={t('ContactsList.filter.placeholder')}
              onChange={(event) => handleFilterValueContacts(event)} />
          </div>
        </div>
      </div>
      <div className="contacts-list__contacts">
        {displayedContacts.map((contact, index) => (
          <ContactCard key={index}
            contact={contact}
            imageUrl={contactImages[contact.id]} />
        ))
        }
      </div>
      {filteredContacts.length === 0
        ? <p className="no-contacts">{t('ContactsList.noContacts')}</p>
        : <Paginator currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={(page) => handlePageChange(page)} />
      }
    </div>
  );
}

export default ContactsList;
