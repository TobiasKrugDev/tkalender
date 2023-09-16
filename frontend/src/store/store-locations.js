import { api, axios } from "src/boot/axios"

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
    const params = { itemsPerPage: rowsPerPage, page, filter }
    if (sortBy) {
      params.sortBy = sortBy
      if (desc) {
        params.orderDirection = "DESC"
      } else {
        params.orderDirection = "ASC"
      }
    }
    const response = await api.get("/location/read", { params })
    commit("setLocations", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Location
  async deleteLocation({}, id) {
    await api.delete("/location/delete", {
      params: { id },
    })
  },

  // POST / Create Location
  async createLocation({ commit }, location) {
    const response = await axios.post("/api/location/create", location, {
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
