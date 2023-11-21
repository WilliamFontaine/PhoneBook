import api from "./caller.controller";

class ContactsController {
    static CONTACT_API_URL = '/contacts';

    async getAllContacts () {
        return await api.get(ContactsController.CONTACT_API_URL);
    }

    async getContactById (id) {
        return await api.get(`${ContactsController.CONTACT_API_URL}/${id}`);
    }

    async createContact (contact) {
        return await api.post(ContactsController.CONTACT_API_URL, contact, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    async updateContact (id, contact) {
        return await api.put(`${ContactsController.CONTACT_API_URL}/${id}`, contact, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    async deleteContact (id) {
        return await api.delete(`${ContactsController.CONTACT_API_URL}/${id}`);
    }
}

export default new ContactsController();
