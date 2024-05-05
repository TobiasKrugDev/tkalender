// Handle the storage of the user specific JWT in the device storage depending on platform.
// Use Capacitor Preferences for native app and Local Storage for everything else.

import { Platform, LocalStorage } from "quasar"
import { Preferences } from "@capacitor/preferences"

export async function storeToken(token) {
  if (Platform.is.capacitor) {
    await Preferences.set({
      key: "token",
      value: token,
    })
  } else {
    LocalStorage.set("token", token)
  }
}

export async function checkToken() {
  if (Platform.is.capacitor) {
    const { value } = await Preferences.get({ key: "token" })
    return value ? true : false
  } else {
    return LocalStorage.has("token")
  }
}

export async function getToken() {
  if (Platform.is.capacitor) {
    const { value } = await Preferences.get({ key: "token" })
    return value
  } else {
    return LocalStorage.getItem("token")
  }
}

export async function removeToken() {
  if (Platform.is.capacitor) {
    await Preferences.remove({ key: "token" })
  } else {
    LocalStorage.remove("token")
  }
}

async function importPreferences() {
  if (Platform.is.capacitor) {
    const moduleImport = await import("@capacitor/preferences")
    Preferences = moduleImport.Preferences
  }
}
