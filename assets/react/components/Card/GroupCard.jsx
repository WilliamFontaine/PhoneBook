import React from 'react';

import './Card.scss';
import CardField from "./CardField";
import {useTranslation} from "react-i18next";
import Button from "../Button/Button";

const GroupCard = ({group}) => {
    const {t} = useTranslation();

    return (
        <div className="group-card">
            <div className="group-card__fields">
                <CardField label={t('Card.field.group.name')}
                           value={group.name}/>
                <CardField label={t('Card.field.group.description')}
                           value={group.description}
                           customClass="text-area"/>
            </div>

            <div className="button-actions">
                <Button type="info"
                        link={`/groups/${group.id}`}
                        content={t('Card.button.edit')}/>
            </div>
        </div>
    );
}

export default GroupCard;