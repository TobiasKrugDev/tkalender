import { api, axios } from "src/boot/axios"

const state = {
  shortcutCreateDialog: false,
  shortcutCreateEntity: "",
}

const mutations = {
  setShortcutCreateDialog(state, value) {
    state.shortcutCreateDialog = value
  },

  setShortcutCreateEntity(state, value) {
    state.shortcutCreateEntity = value
  },
}

const actions = {}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
