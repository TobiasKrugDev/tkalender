const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", component: () => import("pages/IndexPage.vue") },
      { path: "calendar", component: () => import("pages/CalendarPage.vue") },
      {
        path: "calendar/create_appointment",
        component: () => import("pages/CalendarPage.vue"),
      },
      {
        path: "appointments",
        component: () => import("pages/AppointmentPage.vue"),
      },
      {
        path: "appointments/create",
        component: () => import("pages/AppointmentPage.vue"),
      },
      { path: "contacts", component: () => import("pages/ContactPage.vue") },
      {
        path: "contacts/create",
        component: () => import("pages/ContactPage.vue"),
      },
      { path: "locations", component: () => import("pages/LocationPage.vue") },
      {
        path: "locations/create",
        component: () => import("pages/LocationPage.vue"),
      },
      { path: "categories", component: () => import("pages/CategoryPage.vue") },
      {
        path: "categories/create",
        component: () => import("pages/CategoryPage.vue"),
      },
      {
        path: "search/:query",
        component: () => import("pages/SearchPage.vue"),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
]

export default routes
