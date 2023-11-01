import { axios } from "src/boot/axios"

const state = {}

const mutations = {}

const actions = {
  // Login
  async login({}, { username, password }) {
    const data = { username, password }
    let success
    try {
      const response = await axios.post("/api/authentication/login", data, {
        headers: {
          "Content-Type": "application/json",
        },
      })

      success = response.data.success
    } catch (e) {
      // Unsuccessful login
      success = false
    }

    return { success }
  },

  // Logout
  async logout() {
    const response = await axios.get("/api/authentication/logout")
    let success = response.data.success
    return { success }
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
