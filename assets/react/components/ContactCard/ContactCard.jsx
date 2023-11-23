import React from 'react';

import './ContactCard.scss';
import {useTranslation} from "react-i18next";
import Button from "../Button/Button";

const ContactCard = ({contact, imageUrl}) => {
  const {t} = useTranslation();

  return (
    <div className="contact-card">
      <div className="contact-card__fields">
        <ContactCardField label={t('ContactCard.field.firstName')}
                          value={contact.firstname}/>
        <ContactCardField label={t('ContactCard.field.lastName')}
                          value={contact.lastname}/>
        <ContactCardField label={t('ContactCard.field.email')}
                          value={contact.email}/>
        <ContactCardField label={t('ContactCard.field.phone')}
                          value={contact.phone}/>
      </div>

      {imageUrl && <ContactCardImage imageUrl={imageUrl}/>}

      <div className="button-actions">
        <Button type="info"
                link={`/contacts/${contact.id}`}
                content={t('ContactCard.button.edit')}/>
      </div>
    </div>
  );
}

const ContactCardField = ({label, value}) => {
  return (
    <div className="contact-card__field">
      <span className="property">{label}</span>
      <span className="value">{value}</span>
    </div>
  );
}

const ContactCardImage = ({imageUrl}) => {
  return (
    <div className="contact-card__image">
      <img src={imageUrl} alt="contact image" className="image"/>
    </div>
  );
}

export default ContactCard;
