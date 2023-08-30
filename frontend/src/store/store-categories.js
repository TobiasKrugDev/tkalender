import { api } from "src/boot/axios"

const state = {
  categories: [],
}

const mutations = {
  setCategories (state, value) {
    state.categories = value
  },
}

const actions = {
  async getCategories ({ commit }) {
    const response = await api.get('/appointment/read')
    commit("setCategories", response.data.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
