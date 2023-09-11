import { api, axios } from "src/boot/axios"

const state = {
  categories: [],
  totalItems: 0,
}

const mutations = {
  setCategories (state, value) {
    state.categories = value
  },

  setTotalItems (state, value) {
    state.totalItems = value
  },
}

const actions = {
  // GET Categories
  async getCategories ({ commit }, { itemsPerPage, page }) {
    const response = await api.get('/category/read', 
      { 
        params: { 
          itemsPerPage,
          page,
        } 
      })
    commit("setCategories", response.data.items)
    commit("setTotalItems", response.data.totalItems)
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
