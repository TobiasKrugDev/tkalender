import { store } from 'quasar/wrappers'
import { createStore } from 'vuex'
import categories from "./store-categories"
import locations from "./store-locations"
import contacts from "./store-contacts"

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default store(function (/* { ssrContext } */) {
  const Store = createStore({
    modules: {
      categories,
      locations,
      contacts,
    },

    // enable strict mode (adds overhead!)
    // for dev mode and --debug builds only
    strict: process.env.DEBUGGING
  })

  return Store
})
