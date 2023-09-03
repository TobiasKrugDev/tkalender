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
    const response = await api.get('/category/read')
    commit("setCategories", response.data.items)
  },

  async deleteCategory ({}, id) {
    await api.delete("/category/delete", 
      { 
        params: 
          { id },
      },
    )
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
