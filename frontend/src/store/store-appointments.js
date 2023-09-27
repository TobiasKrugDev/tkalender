import { api, axios } from "src/boot/axios"
import { date } from "quasar"

const state = {
  appointments: [],
  calendarAppointments: [],
  createdAppointment: null,
  updatedAppointment: null,
  totalItems: 0,
  calendarTotalItems: 0,
}

const mutations = {
  setAppointments(state, value) {
    state.appointments = value
  },

  setCalendarAppointments(state, value) {
    state.calendarAppointments = value
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

  setCalendarTotalItems(state, value) {
    state.calendarTotalItems = value
  },
}

const actions = {
  // GET Appointments
  async getAppointments(
    { commit },
    { rowsPerPage, page, sortBy, desc, filter, futureAppointmentsOnly }
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

    if (futureAppointmentsOnly) params.futureAppointmentsOnly = true
    const response = await api.get("/appointment/read", { params })
    commit("setAppointments", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  async getCalendarAppointments({ commit }, { start, end }) {
    console.log(start)
    const calendarTimespanStart = date.formatDate(start, "YYYY-MM-DD HH:mm:00")
    const calendarTimespanEnd = date.formatDate(end, "YYYY-MM-DD HH:mm:00")

    const params = {
      calendarMode: true,
      calendarTimespanStart,
      calendarTimespanEnd,
    }

    const response = await api.get("/appointment/read", { params })
    commit("setCalendarAppointments", response.data.items)
    commit("setCalendarTotalItems", response.data.totalItems)
  },

  // DELETE Appointment
  async deleteAppointment({}, id) {
    await api.delete("/appointment/delete", {
      params: { id },
    })
  },

  // POST / Create Appointment
  async createAppointment({ commit }, payloadAppointment) {
    const appointment = { ...payloadAppointment } // Prevent side effects in components
    // Convert datetimes into backend format
    const jsStartAt = new Date(appointment.startAt)
    const jsEndAt = new Date(appointment.endAt)

    appointment.startAt = date.formatDate(jsStartAt, "YYYY-MM-DD HH:mm:00")
    appointment.endAt = date.formatDate(jsEndAt, "YYYY-MM-DD HH:mm:00")

    if (appointment.location) appointment.location = appointment.location.id
    if (appointment.category) appointment.category = appointment.category.id
    appointment.contacts = appointment.contacts.map((contact) => contact.id)

    const response = await axios.post("/api/appointment/create", appointment, {
      headers: {
        "Content-Type": "application/json",
      },
    })

    commit("setCreatedAppointment", response.data)
  },

  // PUT / Update Appointment
  async updateAppointment({ commit }, payloadAppointment) {
    const appointment = { ...payloadAppointment } // Prevent side effects in components
    // Convert datetimes into backend format
    const jsStartAt = new Date(appointment.startAt)
    const jsEndAt = new Date(appointment.endAt)

    appointment.startAt = date.formatDate(jsStartAt, "YYYY-MM-DD HH:mm:00")
    appointment.endAt = date.formatDate(jsEndAt, "YYYY-MM-DD HH:mm:00")

    if (appointment.location) appointment.location = appointment.location.id
    if (appointment.category) appointment.category = appointment.category.id
    if (appointment.contacts)
      appointment.contacts = appointment.contacts.map((contact) => contact.id)
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
