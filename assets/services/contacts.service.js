import api from "./caller.service";

class ContactsService {
    static CONTACT_API_URL = '/contacts';

    async getAllContacts () {
        return await api.get(ContactsService.CONTACT_API_URL);
    }

    async getContactById (id) {
        return await api.get(`${ContactsService.CONTACT_API_URL}/${id}`);
    }

    async createContact (contact) {
        return await api.post(ContactsService.CONTACT_API_URL, contact, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    async updateContact (id, contact) {
        return await api.put(`${ContactsService.CONTACT_API_URL}/${id}`, contact, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    async deleteContact (id) {
        return await api.delete(`${ContactsService.CONTACT_API_URL}/${id}`);
    }
}

export default new ContactsService();
