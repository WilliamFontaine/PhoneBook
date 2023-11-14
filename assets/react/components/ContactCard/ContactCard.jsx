import React from 'react';

import './ContactCard.scss';
import {useTranslation} from "react-i18next";
import Button from "../Button/Button";
const ContactCard = ({ contact }) => {
    const { t } = useTranslation();

    return (
        <div className="contact-card">
            <ContactCardField label={t('ContactCard.firstName')}
                              value={contact.firstname} />
            <ContactCardField label={t('ContactCard.lastName')}
                              value={contact.lastname} />
            <ContactCardField label={t('ContactCard.email')}
                              value={contact.email} />
            <ContactCardField label={t('ContactCard.phone')}
                              value={contact.phone} />
            <Button type="info"
                    content={t('ContactCard.edit')}
                    id={contact.id} />
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

export default ContactCard;
