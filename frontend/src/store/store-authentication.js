import { axios } from "src/boot/axios"
import { Cookies } from "quasar"

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

      // If session cookie isn't set automatically we set it manually
      if (!Cookies.has("PHPSESSID")) {
        Cookies.set("PHPSESSID", response.data.sessionID)
      }
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

    // If session cookie isn't deleted automatically we delete it manually
    if (Cookies.has("PHPSESSID")) {
      Cookies.remove("PHPSESSID")
    }

    return { success }
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
