<template>
  <q-layout view="lHh lpR lFf">
    <q-header class="bg-transparent row q-py-lg" style="flex-wrap: nowrap">
      <div class="blurred-background" />
      <!-- Desktop Toolbars -->
      <q-toolbar
        class="bg-white shadow-7 rounded-borders q-pl-lg q-pr-none gt-sm search-toolbar"
      >
        <q-toolbar-title>
          <div class="row no-wrap">
            <q-input
              v-model="searchInput"
              borderless
              placeholder="Suche nach..."
              class="col"
              type="search"
              @keyup.enter="navigateToSearch"
            />
            <q-btn
              id="search-button"
              color="primary"
              icon="search"
              class="q-px-xl"
            />
          </div>
        </q-toolbar-title>
      </q-toolbar>

      <q-space class="gt-sm" />

      <q-toolbar
        class="bg-white shadow-7 rounded-borders q-px-none gt-sm q-mx-lg logout-toolbar-card"
      >
        <q-toolbar-title class="full-width full-height">
          <q-btn
            color="grey-7"
            icon="mdi-logout"
            :label="$q.screen.gt.md ? 'Abmelden' : ''"
            flat
            class="full-width full-height"
          />
        </q-toolbar-title>
      </q-toolbar>

      <!-- Mobile Toolbar -->
      <q-toolbar
        class="bg-white shadow-7 rounded-borders q-px-none lt-md q-mx-md"
      >
        <q-btn
          flat
          dense
          color="grey-7"
          round
          icon="menu"
          aria-label="Menu"
          size="lg"
          class="q-ml-md"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <router-link to="/">
            <img
              src="/tkalender-logo-mobile.png"
              class="absolute-center mobile-layout-toolbar-logo"
            />
          </router-link>
        </q-toolbar-title>

        <q-btn
          id="mobile-search-btn"
          flat
          round
          icon="search"
          color="grey-7"
          size="lg"
          class="q-mr-md"
        >
          <q-popup-proxy> ToDo: Mobile Suche </q-popup-proxy>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left">
      <div class="relative-position q-my-lg layout-desktop-logo">
        <router-link to="/">
          <img
            src="/tkalender-logo.png"
            class="absolute-center layout-logo-img"
          />
        </router-link>
      </div>
      <q-list>
        <MenuLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
          :entity="link.entity"
        />

        <q-item
          clickable
          target="_self"
          to="/"
          class="text-white mobile-logout-link lt-md"
        >
          <q-item-section avatar>
            <q-icon name="mdi-logout" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Abmelden</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container class="page-container-background">
      <router-view :key="$route.fullPath" />
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

  data() {
    return {
      searchInput: "",
      essentialLinks: linksList,
      leftDrawerOpen: false,
    }
  },

  methods: {
    navigateToSearch() {
      if (this.searchInput) {
        const queryString = "/search/" + encodeURIComponent(this.searchInput)
        this.$router.push(queryString)
        this.searchInput = ""
      }
    },

    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },
  },
})
</script>

<style lang="scss">
.layout-desktop-logo {
  height: 180px;
}

.q-drawer {
  width: 250px;
  background: $primary;
  margin: 24px 0 24px 16px;
  border-radius: 8px;
  height: calc(100% - 48px) !important;
  box-shadow: 0 4px 5px -2px rgba(0, 0, 0, 0.2),
    0 7px 10px 1px rgba(0, 0, 0, 0.14), 0 2px 16px 1px rgba(0, 0, 0, 0.12);
}

.rounded-borders {
  border-radius: 8px;
}

.page-container-background {
  background-color: #f5f6fa;
}

#search-button {
  border-radius: 8px;
}

.mobile-layout-toolbar-logo {
  height: 90%;
}

.logout-toolbar-card {
  width: 100px;
}

.mobile-logout-link {
  position: absolute;
  bottom: 20px;
  width: 100%;
}

.q-drawer {
  position: fixed;
}

.blurred-background {
  position: absolute;
  inset: 0;
  bottom: 12px;
  backdrop-filter: blur(3px);
  background-color: rgba(245, 246, 250, 0.5);
}

.search-toolbar {
  margin-left: 21px;
}

@media screen and (min-width: 1024px) {
  .q-header {
    left: 317px !important;
  }
}

@media screen and (min-width: 1440px) {
  .logout-toolbar-card {
    width: 200px;
  }
}
</style>
