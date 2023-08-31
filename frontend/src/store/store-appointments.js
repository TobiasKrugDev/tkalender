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
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}