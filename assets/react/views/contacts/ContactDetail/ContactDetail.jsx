import React, { useEffect, useState } from "react";
import ContactsService from "../../../../services/contacts.service";

const ContactDetail = ({ id, context }) => {
    const [firstname, setFirstname] = useState('');
    const [lastname, setLastname] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [action, setAction] = useState('');

    useEffect(() => {
        if (id !== null) {
            fetchContact();
            setAction("PUT");
        } else {
            setAction("POST");
        }
    }, [location.search]);

    const fetchContact = () => {
        ContactsService.getContactById(id).then(response => {
            const { firstname, lastname, email, phone } = response.data;
            setFirstname(firstname);
            setLastname(lastname);
            setEmail(email);
            setPhone(phone);
        });
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        const contact = {
            firstname,
            lastname,
            email,
            phone
        };

        if (action === "POST") {
            ContactsService.createContact(contact);
        } else {
            ContactsService.updateContact(id, contact);
        }
    };

    const handleDelete = (event) => {
        event.preventDefault();
        ContactsService.deleteContact(id).then(response => {
            window.location.replace(`/contacts`);
        });
    }

    return (
        <div>
            <h1>Contact Detail</h1>
            <form onSubmit={handleSubmit}>
                <div className="input-field">
                    <label htmlFor="firstname">Firstname</label>
                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        value={firstname}
                        onChange={(e) => setFirstname(e.target.value)}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="lastname">Lastname</label>
                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        value={lastname}
                        onChange={(e) => setLastname(e.target.value)}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="email">Email</label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </div>
                <div className="input-field">
                    <label htmlFor="phone">Phone</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                    />
                </div>
                <div className="input-field">
                    <button type="submit">Submit</button>
                </div>
                {action === "PUT" && (
                    <div className="input-field">
                        <button type="button" onClick={handleDelete}>Delete</button>
                    </div>
                )}
            </form>
        </div>
    );
};

export default ContactDetail;
