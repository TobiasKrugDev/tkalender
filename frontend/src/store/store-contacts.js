import { axios } from "src/boot/axios"
import { getToken } from "../helpers/token-storage.js"

const state = {
  contacts: [],
  createdContact: null,
  updatedContact: null,
  totalItems: 0,
}

const mutations = {
  setContacts(state, value) {
    state.contacts = value
  },

  setCreatedContact(state, value) {
    state.createdContact = value
  },

  setUpdatedContact(state, value) {
    state.updatedContact = value
  },

  setTotalItems(state, value) {
    state.totalItems = value
  },
}

const actions = {
  // GET Contacts
  async getContacts({ commit }, { rowsPerPage, page, sortBy, desc, filter }) {
    const params = {
      itemsPerPage: rowsPerPage,
      page,
      filter,
      token: await getToken(),
    }
    if (sortBy) {
      params.sortBy = sortBy
      if (desc) {
        params.orderDirection = "DESC"
      } else {
        params.orderDirection = "ASC"
      }
    }
    const response = await axios.get("/api/contact/read", { params })
    commit("setContacts", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Contact
  async deleteContact({}, id) {
    await axios.delete("/api/contact/delete", {
      params: { id, token: await getToken() },
    })
  },

  // POST / Create Contact
  async createContact({ commit }, contact) {
    const response = await axios.post("/api/contact/create", contact, {
      params: {
        token: await getToken(),
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedContact", response.data)
  },

  // PUT / Update Location
  async updateContact({ commit }, contact) {
    const response = await axios.put("/api/contact/update", contact, {
      params: {
        id: contact.id,
        token: await getToken(),
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setUpdatedContact", response.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
