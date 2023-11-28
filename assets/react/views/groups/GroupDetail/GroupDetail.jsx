import React, {useEffect, useState} from 'react';

import './GroupDetail.scss';
import {useTranslation} from "react-i18next";
import GroupsController from "../../../../controllers/groups.controller";
import FormInput from "../../../components/FormInput/FormInput";
import Button from "../../../components/Button/Button";
import FormButton from "../../../components/Button/FormButton";

const GroupDetail = ({id}) => {
    const [group, setGroup] = useState({});
    const [errors, setErrors] = useState([]);
    const {t} = useTranslation();

    useEffect(() => {
        fetchGroup();
    }, []);

    const fetchGroup = () => {
        GroupsController.getGroupById(id).then(
            (response) => {
                setGroup(response.data);
            }
        );
    }

    const handleSubmit = async (event) => {
        event.preventDefault();

        await GroupsController.updateGroup(id, group).then(
            (response) => {
                setErrors([]);
                setGroup(response.data);
            },
            (error) => {
                handleErrors(error.response.data);
            }
        );
    }

    const handleErrors = (errors) => {
        errors.map((error) => {
            setErrors(prevErrors => ({
                ...prevErrors,
                [error["property_path"]]: error.message
            }));
        });
    }

    const handleInputChange = (event) => {
        const {name, value} = event.target;
        setGroup(prevGroup => ({
            ...prevGroup,
            [name]: value
        }));
    }

    return (
        <div className="group-detail">
            <h1>{t('GroupDetail.title')}</h1>
            {group &&
                <form onSubmit={handleSubmit}>
                    <FormInput label={t('GroupDetail.field.name')}
                               type="text"
                               name="name"
                               value={group.name || ''}
                               onChange={handleInputChange}
                               error={errors.name}/>
                    <FormInput label={t('GroupDetail.field.description')}
                               type="text"
                               name="description"
                               value={group.description || ''}
                               onChange={handleInputChange}
                               error={errors.description}/>

                    <div className="buttons-container">
                        <div className="button-field">
                            <Button type="secondary"
                                    content={t('GroupDetail.button.return')}
                                    link="/groups"/>
                        </div>
                        <div className="button-field">
                            <FormButton buttonType="primary"
                                        type="submit"
                                        content={t('GroupDetail.button.submit')}/>
                        </div>
                    </div>
                </form>
            }
        </div>
    );
}

export default GroupDetail;