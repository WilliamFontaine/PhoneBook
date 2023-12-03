import api from "./caller.controller";

class ImagesController {
  static CONTACT_API_URL = '/images';


  async postProfilePicture(contactId, image) {
    return await api.post(`${ImagesController.CONTACT_API_URL}/${contactId}`, {profilePicture: image}, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  async getImageByName(name) {
    return await api.get(`${ImagesController.CONTACT_API_URL}/${name}`, {responseType: 'arraybuffer'})
  }

  async deleteImageByContactId(contactId) {
    return await api.delete(`${ImagesController.CONTACT_API_URL}/${contactId}`);
  }

}

export default new ImagesController();
