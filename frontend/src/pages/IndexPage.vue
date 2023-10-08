<template>
  <q-page>
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-12 q-pr-md dashboard-calendar-col">
          <WrapperCard title="Kalender"
            ><Calendar
              ref="calendar"
              dashboard-mode
              @appointment-change="onCalendarAppointmentChange"
            />
            <template #top-right>
              <q-btn color="primary" label="Zum Kalender" flat to="/calendar" />
            </template>
          </WrapperCard>
        </div>
        <div
          class="col-lg-6 col-md-6 col-12 q-pl-lg-md dashboard-appointment-list-col"
        >
          <WrapperCard title="Anstehende Termine"
            ><AppointmentTable
              ref="appointmentTable"
              dashboard-mode
              class="appointment-list"
              @appointment-change="onTableAppointmentChange" />
            <template #top-right>
              <q-btn
                color="primary"
                label="Zur Terminliste"
                flat
                to="/appointments"
              /> </template
          ></WrapperCard>
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

  methods: {
    onCalendarAppointmentChange() {
      this.$refs.appointmentTable.getAppointmentData()
    },

    onTableAppointmentChange() {
      this.$refs.calendar.refreshAppointments()
    },
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
    overflow-y: auto;
  }
}

@media screen and (min-width: 1440px) {
  .dashboard-calendar-col {
    padding-right: 16px;
  }
}
</style>
