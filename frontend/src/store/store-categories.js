import { api, axios } from "src/boot/axios"

const state = {
  categories: [],
  createdCategory: null,
  updatedCategory: null,
  totalItems: 0,
}

const mutations = {
  setCategories(state, value) {
    state.categories = value
  },

  setCreatedCategory(state, value) {
    state.createdCategory = value
  },

  setUpdatedCategory(state, value) {
    state.updatedCategory = value
  },

  setTotalItems(state, value) {
    state.totalItems = value
  },
}

const actions = {
  // GET Categories
  async getCategories(
    { commit },
    { itemsPerPage, page, sortBy, desc, filter }
  ) {
    const params = { itemsPerPage, page, filter }
    if (sortBy) {
      params.sortBy = sortBy
      if (desc) {
        params.orderDirection = "DESC"
      } else {
        params.orderDirection = "ASC"
      }
    }
    const response = await api.get("/category/read", { params })
    commit("setCategories", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Category
  async deleteCategory({}, id) {
    await api.delete("/category/delete", {
      params: { id },
    })
  },

  // POST / Create Category
  async createCategory({ commit }, category) {
    const response = await axios.post("/api/category/create", category, {
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedCategory", response.data)
  },

  // PUT / Update Category
  async updateCategory({ commit }, category) {
    const response = await axios.put("/api/category/update", category, {
      params: {
        id: category.id,
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setUpdatedCategory", response.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
