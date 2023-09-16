import { api } from "src/boot/axios"

const state = {
  contacts: [],
}

const mutations = {
  setContacts(state, value) {
    state.contacts = value
  },
}

const actions = {
  // GET Contacts
  async getContacts({ commit }) {
    const response = await api.get("/contact/read")
    commit("setContacts", response.data.items)
  },

  // DELETE Contacts
  async deleteContact({}, id) {
    await api.delete("/contact/delete", {
      params: { id },
    })
  },

  // POST / Create Contact
  async createContact({}, contact) {
    axios.post("/api/contact/create", contact, {
      headers: {
        "Content-Type": "application/json",
      },
    })
  },

  // PUT / Update Contact
  async updateContact({}, contact) {
    axios.put("/api/contact/update", contact, {
      params: {
        id: contact.id,
      },
      headers: {
        "Content-Type": "application/json",
      },
    })
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
