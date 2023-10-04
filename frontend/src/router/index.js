import { route } from "quasar/wrappers"
import {
  createRouter,
  createMemoryHistory,
  createWebHistory,
  createWebHashHistory,
} from "vue-router"
import routes from "./routes"
import { Cookies } from "quasar"

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : process.env.VUE_ROUTER_MODE === "history"
    ? createWebHistory
    : createWebHashHistory

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(process.env.VUE_ROUTER_BASE),
  })

  Router.beforeEach((to, from, next) => {
    const loggedIn = Cookies.has("PHPSESSID") // Check for cookie set by PHP session

    // User wants to access /login
    if (to.path === "/login") {
      // Redirect to "/" if user is already logged in
      if (loggedIn) {
        next("/")
      } else {
        next()
      }
      // User wants to access anything else than /login
    } else {
      if (loggedIn) {
        next()
      } else {
        // Block access when user isn't authenticated -> redirect to /login
        next("/login")
      }
    }
  })

  return Router
})
