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
  // GET Appointments
  async getAppointments ({ commit }) {
    const response = await api.get('/appointment/read')
    commit("setAppointments", response.data.items)
  },

  // DELETE Appointment
  async deleteAppointment ({ }, id) {
    await api.delete("/appointment/delete",
      {
        params:
          { id },
      },
    )
  },

  // POST / Create Appointment
  async createAppointment ({}, appointment) {
    axios.post('/api/appointment/create', appointment, 
    {
      headers: {
        "Content-Type": "application/json",
      },
    })
  },

  // PUT / Update Appointment
  async updateAppointment ({}, appointment) {
    axios.put('/api/appointment/update', appointment, 
    {
      params: {
        id : appointment.id
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