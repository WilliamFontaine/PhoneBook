import api from "./caller.controller";

class ImagesController {
  static CONTACT_API_URL = '/images';

  async getImageByName (name) {
    return await api.get(`${ImagesController.CONTACT_API_URL}/${name}`, { responseType: 'arraybuffer' })
  }

}

export default new ImagesController();
