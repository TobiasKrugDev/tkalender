import { api, axios } from "src/boot/axios"
import { date } from "quasar"

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
    // Convert datetimes into backend format
    const jsStartAt = new Date(appointment.startAt)
    const jsEndAt = new Date(appointment.endAt)

    appointment.startAt = date.formatDate(jsStartAt, "YYYY-MM-DD HH:mm:00")
    appointment.endAt = date.formatDate(jsEndAt, "YYYY-MM-DD HH:mm:00")

    if (appointment.location) appointment.location = appointment.location.id
    if (appointment.category) appointment.category = appointment.category.id

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
