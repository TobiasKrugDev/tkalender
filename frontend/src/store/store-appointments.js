import { axios } from "src/boot/axios"
import { date } from "quasar"
import { getToken } from "../helpers/token-storage.js"

const state = {
  appointments: [],
  calendarAppointments: [],
  entityAppointments: [],
  createdAppointment: null,
  updatedAppointment: null,
  totalItems: 0,
  calendarTotalItems: 0,
  entityTotalItems: 0,
  createdTimespanStart: "",
  createdTimespanEnd: "",
}

const mutations = {
  setAppointments(state, value) {
    state.appointments = value
  },

  setCalendarAppointments(state, value) {
    state.calendarAppointments = value
  },

  setEntityAppointments(state, value) {
    state.entityAppointments = value
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

  setEntityTotalItems(state, value) {
    state.entityTotalItems = value
  },

  setCreatedTimespanStart(state, value) {
    state.createdTimespanStart = value
  },

  setCreatedTimespanEnd(state, value) {
    state.createdTimespanEnd = value
  },
}

const actions = {
  // GET Appointments
  async getAppointments(
    { commit },
    { rowsPerPage, page, sortBy, desc, filter, futureAppointmentsOnly }
  ) {
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

    if (futureAppointmentsOnly) params.futureAppointmentsOnly = true
    const response = await axios.get("/api/appointment/read", { params })
    commit("setAppointments", response.data.items)
    commit("setTotalItems", response.data.totalItems)
  },

  // GET Calendar Appointments
  async getCalendarAppointments({ commit }, { start, end }) {
    const calendarTimespanStart = date.formatDate(start, "YYYY-MM-DD HH:mm:00")
    const calendarTimespanEnd = date.formatDate(end, "YYYY-MM-DD HH:mm:00")

    const params = {
      calendarMode: true,
      calendarTimespanStart,
      calendarTimespanEnd,
      token: await getToken(),
    }

    const response = await axios.get("/api/appointment/read", { params })
    commit("setCalendarAppointments", response.data.items)
    commit("setCalendarTotalItems", response.data.totalItems)
  },

  // GET Entity Appointments
  async getEntityAppointments(
    { commit },
    { rowsPerPage, page, entity, filterID }
  ) {
    const params = {
      itemsPerPage: rowsPerPage,
      page,
      token: await getToken(),
    }

    if (entity === "contact") {
      params.filter_contact = filterID
    } else if (entity === "location") {
      params.filter_location = filterID
    } else if (entity === "category") {
      params.filter_category = filterID
    }

    const response = await axios.get("/api/appointment/read", { params })
    commit("setEntityAppointments", response.data.items)
    commit("setEntityTotalItems", response.data.totalItems)
  },

  // DELETE Appointment
  async deleteAppointment({}, id) {
    await axios.delete("/api/appointment/delete", {
      params: { id, token: getToken() },
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
    if (appointment.contacts)
      appointment.contacts = appointment.contacts.map((contact) => contact.id)

    const response = await axios.post("/api/appointment/create", appointment, {
      params: {
        token: await getToken(),
      },
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
        token: await getToken(),
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
