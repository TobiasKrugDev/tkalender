import { api } from "src/boot/axios"

const state = {
  appointments: [],
}

const mutations = {
  setAppointments (state, value) {
    state.appointments = value
  },
}

const actions = {
  async getAppointments ({ commit }) {
    const response = await api.get('/appointment/read')
    commit("setAppointments", response.data.items)
  },

  async deleteAppointment ({ }, id) {
    await api.delete("/appointment/delete",
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