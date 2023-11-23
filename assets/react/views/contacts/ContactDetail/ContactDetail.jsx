import React, {useEffect, useState} from "react";
import {useTranslation} from "react-i18next";
import toast from "react-hot-toast";

import "./ContactDetail.scss";
import FormButton from "../../../components/Button/FormButton";
import FormInput from "../../../components/FormInput/FormInput";
import Button from "../../../components/Button/Button";
import ContactsController from "../../../../controllers/contacts.controller";
import ImagesController from "../../../../controllers/images.controller";
import FormInputImage from "../../../components/FormInput/FormInputImage";
import ImageService from "../../../../services/image.service";

const ContactDetail = ({id}) => {
  const [contact, setContact] = useState({});
  const [contactImagesUrl, setContactImageUrl] = useState({});
  const [profilePicture, setProfilePicture] = useState(null);
  const [errors, setErrors] = useState([]);
  const [action, setAction] = useState(null);
  const {t} = useTranslation();


  useEffect(() => {
    if (id !== null) {
      fetchContact();
      setAction("PUT");
    } else {
      setAction("POST");
    }
  }, [location.search]);

  const fetchContact = async () => {
    let image_name = null;

    await ContactsController.getContactById(id).then(response => {
      setContact(response.data);
      image_name = response.data.image_name;
    });
    loadProfilePicture(image_name);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    let contactId = contact.id;
    let image_name = null;

    try {
      if (action === "POST") {
        const response = await ContactsController.createContact(contact);
        setContact(response.data);
        contactId = response.data.id;
        image_name = response.data.image_name;
        setAction("PUT");
        setErrors([]);
        toast.success(t('ContactDetail.toast.success'));
      } else {
        const response = await ContactsController.updateContact(contact.id, contact);
        setContact(response.data);
        toast.success(t('ContactDetail.toast.success'));
      }

      if (profilePicture !== null) {
        await ImagesController.postProfilePicture(contactId, profilePicture);
        const response = await ContactsController.getContactById(contactId);
        setContact(response.data);
        setErrors([]);
        image_name = response.data.image_name;
      }
      loadProfilePicture(image_name);
    } catch (error) {
      handleErrors(error.response.data);
    }
  }

  const handleErrors = (errors) => {
    setErrors([]);
    errors
      .filter(error => error["property_path"] !== "invalid_uuid" && error["property_path"] !== "invalid_uuid")
      .forEach(error => {
        setErrors(prevErrors => ({
          ...prevErrors,
          [error["property_path"]]: error.message
        }));
      });

    errors
      .filter(error => error["property_path"] === "invalid_uuid" || error["property_path"] === "invalid_file")
      .forEach(error => {
        toast.error(t(`ContactDetail.toast.${error.message}`));
      });
  }

  const handleDelete = (event) => {
    event.preventDefault();
    ContactsController.deleteContact(id).then(response => {
      setContact(response.data);
      localStorage.setItem("toast", JSON.stringify({
        type: "success",
        message: t('ContactDetail.toast.deleted')
      }));
      window.location.href = "/contacts";
    });
  }

  const handleInputChange = (event) => {
    const {name, value} = event.target;
    setContact(prevContact => ({
      ...prevContact,
      [name]: value
    }));
  };

  const handleProfilePictureChange = (event) => {
    setProfilePicture(event.target.files[0]);
  }

  const loadProfilePicture = (image_name) => {
    if (image_name) {
      ImagesController.getImageByName(image_name)
        .then(res => {
          const imageUrl = ImageService.handleFile(res.data, id);
          setContactImageUrl({
            imageUrl,
          });
        })
    }
  }

  const handleDeleteProfilePicture = async () => {
    await ImagesController.deleteImageByContactId(id);
    setContactImageUrl({});
    toast.success(t('ContactDetail.toast.imageDeleted'));
  }
  return (
    <div className="contact-detail">
      <h1>Contact Detail</h1>
      {contact && (
        <form onSubmit={handleSubmit} encType={"multipart/form-data"}>
          <FormInput label={t('ContactDetail.field.firstName')}
                     type="text"
                     name="firstname"
                     value={contact.firstname || ""}
                     onChange={handleInputChange}
                     error={errors.firstname}/>
          <FormInput label={t('ContactDetail.field.lastName')}
                     type="text"
                     name="lastname"
                     value={contact.lastname || ""}
                     onChange={handleInputChange}
                     error={errors.lastname}/>
          <FormInput label={t('ContactDetail.field.email')}
                     type="text"
                     name="email"
                     value={contact.email || ""}
                     onChange={handleInputChange}
                     error={errors.email}/>
          <FormInput label={t('ContactDetail.field.phone')}
                     type="text"
                     name="phone"
                     value={contact.phone || ""}
                     onChange={handleInputChange}
                     error={errors.phone}/>
          <FormInputImage label={t('ContactDetail.field.profilePicture')}
                          name="profilePicture"
                          onChange={handleProfilePictureChange}
                          imageUrl={contactImagesUrl.imageUrl}
                          deleteImage={handleDeleteProfilePicture}/>

          {/* TODO: ajouter des champ aditionnel à la vollée */}

          <div className="buttons-container">
            <div className="button-field">
              <Button type="secondary"
                      content={t('ContactDetail.button.return')}
                      link="/contacts"/>
            </div>
            <div className="button-field">
              <FormButton buttonType="primary"
                          type="submit"
                          content={t('ContactDetail.button.submit')}/>
            </div>
            {action === "PUT" && (
              <div className="button-field">
                <FormButton buttonType="danger"
                            type="button"
                            content={t('ContactDetail.button.delete')}
                            onClick={handleDelete}/>
              </div>
            )}
          </div>
        </form>
      )}
    </div>
  );
}


export default ContactDetail;
