<template>
  <q-layout view="lHh lpR lFf">

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left">
      <div class="relative-position q-my-lg layout-desktop-logo">
        <router-link to="/">
          <img
            src="/icons/favicon-128x128.png"
            class="absolute-center layout-logo-img"
          >
        </router-link>
      </div>
      <q-list>
        <MenuLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
          :entity="link.entity"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import { defineComponent } from "vue"
import MenuLink from "components/MenuLink.vue"

const linksList = [
  {
    title: "Dashboard",
    icon: "mdi-home",
    link: "/",
  },
  {
    title: "Kalender",
    icon: "mdi-calendar",
    link: "/calendar",
  },
  {
    title: "Terminliste",
    icon: "mdi-format-list-bulleted",
    link: "/appointments",
  },
  {
    title: "Kontakte",
    icon: "mdi-account-group",
    link: "/contacts",
  },
  {
    title: "Orte",
    icon: "mdi-map-marker-radius",
    link: "/locations",
  },
  {
    title: "Kategorien",
    icon: "mdi-palette",
    link: "/categories",
  },
]

export default defineComponent({
  name: "MainLayout",

  components: {
    MenuLink,
  },

  data () {
    return {
      essentialLinks: linksList,
      leftDrawerOpen: false,
    }
  },

  methods: {
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },
  },
})
</script>

<style lang="scss">
  .layout-desktop-logo {
      height: 140px;
  }

  .q-drawer {
    width: 250px;
    background: #2121ce;
    margin: 24px 0 24px 16px;
    border-radius: 8px;
    height: calc(100% - 48px) !important;
    box-shadow: 0 4px 5px -2px rgba(0, 0, 0, 0.2), 0 7px 10px 1px rgba(0, 0, 0, 0.14), 0 2px 16px 1px rgba(0, 0, 0, 0.12)
  }
</style>