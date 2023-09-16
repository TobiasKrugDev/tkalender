import { api } from "src/boot/axios"

const state = {
  locations: [],
}

const mutations = {
  setLocations(state, value) {
    state.locations = value
  },
}

const actions = {
  // GET Locations
  async getLocations({ commit }) {
    const response = await api.get("/location/read")
    commit("setLocations", response.data.items)
  },

  // DELETE Location
  async deleteLocation({}, id) {
    await api.delete("/location/delete", {
      params: { id },
    })
  },

  // POST / Create Location
  async createLocation({}, location) {
    axios.post("/api/location/create", location, {
      headers: {
        "Content-Type": "application/json",
      },
    })
  },

  // PUT / Update Location
  async updateLocation({}, location) {
    axios.put("/api/location/update", location, {
      params: {
        id: location.id,
      },
      headers: {
        "Content-Type": "application/json",
      },
    })
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
