<template>
  <q-page>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-6 col-12 q-pr-md dashboard-calendar-col">
          <WrapperCard title="Kalender"
            ><Calendar dashboard-mode
          /></WrapperCard>
        </div>
        <div
          class="col-lg-4 col-md-6 col-12 q-pl-lg-md dashboard-appointment-list-col"
        >
          <WrapperCard title="Anstehende Termine"
            ><AppointmentTable dashboard-mode class="appointment-list"
          /></WrapperCard>
        </div>
      </div>
      <div class="row">
        <DashboardEntityButton
          v-for="(entity, index) in entityButtons"
          :key="entity"
          :entity-data="entity"
          :class="{ 'left-dashboard-column': index === 0 }"
        />
      </div>
    </div>

    <FABCreateButton expandable-fab />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"
import Calendar from "components/Calendar.vue"
import AppointmentTable from "components/AppointmentTable.vue"
import DashboardEntityButton from "components/DashboardEntityButton.vue"

export default defineComponent({
  name: "IndexPage",

  components: {
    FABCreateButton,
    WrapperCard,
    Calendar,
    AppointmentTable,
    DashboardEntityButton,
  },

  data() {
    return {
      entityButtons: [
        { to: "/contacts", icon: "mdi-account-group", label: "Kontakte" },
        { to: "/locations", icon: "mdi-map-marker-radius", label: "Orte" },
        { to: "/categories", icon: "mdi-palette", label: "Kategorien" },
      ],
    }
  },
})
</script>

<style lang="scss">
.dashboard-btn {
  width: 100%;
  height: 100%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.dashboard-calendar-col,
.dashboard-appointment-list-col {
  padding-right: 31px;
}

@media screen and (min-width: 1024px) {
  .left-dashboard-column {
    margin-left: 18px;
  }

  .dashboard-appointment-list-col {
    padding-right: 63px;
  }

  .appointment-list {
    height: 50vh;
    overflow-y: scroll;
  }
}

@media screen and (min-width: 1440px) {
  .dashboard-calendar-col {
    padding-right: 16px;
  }
}
</style>
