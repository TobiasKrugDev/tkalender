import { api } from "src/boot/axios"

const state = {
  contacts: [],
}

const mutations = {
  setContacts (state, value) {
    state.contacts = value
  },
}

const actions = {
  async getContacts ({ commit }) {
    const response = await api.get('/contact/read')
    commit("setContacts", response.data.items)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}