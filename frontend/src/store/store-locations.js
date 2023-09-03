import { api } from "src/boot/axios"

const state = {
  locations: [],
}

const mutations = {
  setLocations (state, value) {
    state.locations = value
  },
}

const actions = {
  async getLocations ({ commit }) {
    const response = await api.get('/location/read')
    commit("setLocations", response.data.items)
  },

  async deleteLocation ({ }, id) {
    await api.delete("/location/delete",
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
