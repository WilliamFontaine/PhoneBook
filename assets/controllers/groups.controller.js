import api from "./caller.controller";

class GroupsController {
  static GROUP_API_URL = '/groups';

  async getAllGroups() {
    return await api.get(GroupsController.GROUP_API_URL);
  }

  async getGroupById(id) {
    return await api.get(`${GroupsController.GROUP_API_URL}/${id}`);
  }

  async updateGroup(id, group) {
    return await api.put(`${GroupsController.GROUP_API_URL}/${id}`, group);
  }

  async addContactToGroups(contactId, groups) {
    return await api.put(`${GroupsController.GROUP_API_URL}/add/${contactId}`, groups);
  }
}

export default new GroupsController();
