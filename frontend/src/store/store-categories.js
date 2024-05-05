import { axios } from "src/boot/axios"
import { getToken } from "../helpers/token-storage.js"

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
  async getCategories({ commit }, { rowsPerPage, page, sortBy, desc, filter }) {
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
    const response = await axios.get("/api/category/read", {
      params,
    })
    commit("setCategories", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Category
  async deleteCategory({}, id) {
    await axios.delete("/api/category/delete", {
      params: { id, token: await getToken() },
    })
  },

  // POST / Create Category
  async createCategory({ commit }, category) {
    const response = await axios.post("/api/category/create", category, {
      params: {
        token: await getToken(),
      },
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
        token: await getToken(),
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
