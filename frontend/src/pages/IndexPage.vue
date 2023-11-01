<template>
  <q-page class="dashboard-background">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-12 q-pr-md dashboard-calendar-col">
          <WrapperCard title="Kalender" class="dashboard-wrapper-card"
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
          <WrapperCard title="Anstehende Termine" class="dashboard-wrapper-card"
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
      <div class="row absolute-dashboard-buttons">
        <div style="position: absolute; bottom: 24px; width: 100%">
          <div class="container">
            <div class="row">
              <div class="col-sm-10 col-12">
                <div class="row dashboard-btn-row">
                  <DashboardEntityButton
                    v-for="(entity, index) in entityButtons"
                    :key="entity"
                    :entity-data="entity"
                    :class="{ 'left-dashboard-column': index === 0 }"
                  />
                </div>
              </div>
              <div class="col-2" />
            </div>
          </div>
        </div>
      </div>
      <div class="row regular-dashboard-buttons">
        <div class="col-sm-10 col-12">
          <div class="row dashboard-btn-row">
            <DashboardEntityButton
              v-for="(entity, index) in entityButtons"
              :key="entity"
              :entity-data="entity"
              :class="{
                'left-dashboard-column': index === 0,
                'q-mb-md': $q.screen.xs,
              }"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Create Button -->
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

// Add extra background layer to override background logo ONLY on dashbaord
.dashboard-background {
  background-color: #f5f6fa;
}

@media screen and (min-width: 1024px) {
  .left-dashboard-column {
    padding-left: 18px;
  }

  .dashboard-appointment-list-col {
    padding-right: 63px;
  }

  .appointment-list {
    height: 50vh;
    overflow-y: auto;
  }

  .dashboard-calendar-col .wrapper-card,
  .dashboard-appointment-list-col .wrapper-card {
    margin-bottom: 3vh;
  }
}

@media screen and (min-width: 1024px) {
  .dashboard-wrapper-card {
    height: 100%;
  }
}

@media screen and (min-width: 1024px) and (min-height: 700px) {
  .regular-dashboard-buttons {
    display: none;
  }
}

@media screen and (max-width: 1024px) {
  .dashboard-btn-row {
    margin-bottom: 50px;
  }

  .absolute-dashboard-buttons {
    display: none;
  }
}

@media screen and (min-width: 1440px) {
  .dashboard-calendar-col {
    padding-right: 16px;
  }
}

@media screen and (max-height: 900px) and (min-width: 1024px) {
  .appointment-list {
    height: 40vh;
  }
}

@media screen and (max-height: 700px) {
  .absolute-dashboard-buttons {
    display: none;
  }

  .regular-dashboard-buttons {
    padding-top: 24px;
    padding-bottom: 24px;
  }
}

@media screen and (min-height: 1100px) and (min-width: 1024px) {
  .appointment-list {
    height: 55vh;
  }
}
</style>
