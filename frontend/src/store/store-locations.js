import { axios } from "src/boot/axios"
import { getToken } from "../helpers/token-storage.js"

const state = {
  locations: [],
  createdLocation: null,
  updatedLocation: null,
  totalItems: 0,
}

const mutations = {
  setLocations(state, value) {
    state.locations = value
  },

  setCreatedLocation(state, value) {
    state.createdLocation = value
  },

  setUpdatedLocation(state, value) {
    state.updatedLocation = value
  },

  setTotalItems(state, value) {
    state.totalItems = value
  },
}

const actions = {
  // GET Locations
  async getLocations({ commit }, { rowsPerPage, page, sortBy, desc, filter }) {
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
    const response = await axios.get("/api/location/read", {
      params,
    })
    commit("setLocations", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Location
  async deleteLocation({}, id) {
    await axios.delete("/api/location/delete", {
      params: { id, token: await getToken() },
    })
  },

  // POST / Create Location
  async createLocation({ commit }, location) {
    const response = await axios.post("/api/location/create", location, {
      params: {
        token: await getToken(),
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedLocation", response.data)
  },

  // PUT / Update Location
  async updateLocation({ commit }, location) {
    const response = await axios.put("/api/location/update", location, {
      params: {
        id: location.id,
        token: await getToken(),
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setUpdatedLocation", response.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
