import { api, axios } from "src/boot/axios"

const state = {
  categories: [],
  createdCategory: null,
  totalItems: 0,
}

const mutations = {
  setCategories (state, value) {
    state.categories = value
  },

  setCreatedCategory (state, value) {
    state.createdCategory = value
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
  async createCategory ({ commit }, category) {
    const response = await  axios.post('/api/category/create', category, 
    {
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedCategory", response.data)
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
