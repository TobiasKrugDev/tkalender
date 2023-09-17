import { api, axios } from "src/boot/axios"

const state = {
  appointments: [],
  createdAppointment: null,
  updatedAppointment: null,
  totalItems: 0,
}

const mutations = {
  setAppointments(state, value) {
    state.appointments = value
  },

  setCreatedAppointment(state, value) {
    state.createdAppointment = value
  },

  setUpdatedAppointment(state, value) {
    state.updatedAppointment = value
  },

  setTotalItems(state, value) {
    state.totalItems = value
  },
}

const actions = {
  // GET Appointments
  async getAppointments(
    { commit },
    { rowsPerPage, page, sortBy, desc, filter }
  ) {
    const params = { itemsPerPage: rowsPerPage, page, filter }
    if (sortBy) {
      params.sortBy = sortBy
      if (desc) {
        params.orderDirection = "DESC"
      } else {
        params.orderDirection = "ASC"
      }
    }
    const response = await api.get("/appointment/read", { params })
    commit("setAppointments", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // DELETE Appointment
  async deleteAppointment({}, id) {
    await api.delete("/appointment/delete", {
      params: { id },
    })
  },

  // POST / Create Appointment
  async createAppointment({ commit }, appointment) {
    const response = await axios.post("/api/appointment/create", appointment, {
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedAppointment", response.data)
  },

  // PUT / Update Appointment
  async updateAppointment({ commit }, appointment) {
    const response = await axios.put("/api/appointment/update", appointment, {
      params: {
        id: appointment.id,
      },
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setUpdatedAppointment", response.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
