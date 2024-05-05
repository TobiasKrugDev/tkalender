import { axios } from "src/boot/axios"
import { storeToken, removeToken } from "../helpers/token-storage.js"

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
      await storeToken(response.data.token)
    } catch (e) {
      // Unsuccessful login
      success = false
    }

    return { success }
  },

  // Logout
  async logout() {
    // ToDo: Is a API call here needed anymore?

    // ToDo: Error handling
    await removeToken()

    return { success: true }
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
