import React from 'react';

import './Card.scss';
import {useTranslation} from "react-i18next";
import Button from "../Button/Button";
import CardField from "./CardField";
import CardImage from "./CardImage";

const ContactCard = ({contact, imageUrl}) => {
  const {t} = useTranslation();

  return (
    <div className="contact-card">
      <div className="contact-card__fields">
        <CardField label={t('Card.field.contact.firstName')}
                   value={contact.firstname}/>
        <CardField label={t('Card.field.contact.lastName')}
                   value={contact.lastname}/>
        <CardField label={t('Card.field.contact.email')}
                   value={contact.email}/>
        <CardField label={t('Card.field.contact.phone')}
                   value={contact.phone}/>
        {contact.contact_extended_fields.map((field, index) => (
          <CardField key={index} label={field.field_name} value={field.field_value}/>
        ))}
        {contact.groups && <CardField label={t('Card.field.contact.groups')}
                                      value={contact.groups.map(group => group.name).join(', ')}
        />}
      </div>

      {imageUrl && <CardImage imageUrl={imageUrl}/>}

      {/* TODO: Mettre à jour les cartes avec toutes les infos*/}


      <div className="button-actions">
        <Button type="info"
                link={`/contacts/${contact.id}`}
                content={t('Card.button.edit')}/>
      </div>
    </div>
  );
}

export default ContactCard;
