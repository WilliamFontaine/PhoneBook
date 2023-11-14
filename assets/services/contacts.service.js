import api from "./caller.service";

class ContactsService {
    static CONTACT_API_URL = '/contacts';

    async getAllContacts () {
        return await api.get(ContactsService.CONTACT_API_URL);
    }

    async getContactById (id) {
        return await api.get(`${ContactsService.CONTACT_API_URL}/${id}`);
    }
}

export default new ContactsService();
