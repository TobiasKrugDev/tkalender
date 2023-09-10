import { api, axios } from "src/boot/axios"

const state = {
  categories: [],
}

const mutations = {
  setCategories (state, value) {
    state.categories = value
  },
}

const actions = {
  // GET Categories
  async getCategories ({ commit }) {
    const response = await api.get('/category/read')
    commit("setCategories", response.data.items)
  },

  // DELETE Category
  async deleteCategory ({}, id) {
    await api.delete("/category/delete", 
      { 
        params: 
          { id },
      },
    )
  },

  // POST / Create Category
  async createCategory ({}, category) {
    axios.post('/api/category/create', category, 
    {
      headers: {
        "Content-Type": "application/json",
      },
    })
  },

  // PUT / Update Category
  async updateCategory ({}, category) {
    axios.put('/api/category/update', category, 
    {
      params: {
        id : category.id
      },
      headers: {
        "Content-Type": "application/json",
      },
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
