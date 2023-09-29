<template>
  <q-page>
    <div class="container">
      <div class="row">
        <div class="col-8 q-pr-md">
          <WrapperCard title="Kalender"><Calendar /></WrapperCard>
        </div>
        <div class="col-4 q-pl-md" style="padding-right: 62px">
          <WrapperCard title="Anstehende Termine"
            ><AppointmentTable dashboard-mode
          /></WrapperCard>
        </div>
      </div>
      <div class="row">
        <div class="col-sm col-12 left-dashboard-column q-my-md">
          <div class="dashboard-btn-container shadow-7">
            <!-- ToDo: Maybe put these buttons into a reusable component -->
            <q-btn
              to="/contacts"
              icon="mdi-account-group"
              label="Kontakte"
              flat
              stack
              :size="dashboardBtnSize"
              class="dashboard-btn"
              color="grey-7"
            />
          </div>
        </div>
        <div class="col-sm col-12 q-my-md">
          <div class="dashboard-btn-container shadow-7">
            <q-btn
              to="/locations"
              icon="mdi-map-marker-radius"
              label="Orte"
              flat
              stack
              :size="dashboardBtnSize"
              class="dashboard-btn"
              color="grey-7"
            />
          </div>
        </div>
        <div class="col-sm col-12 q-my-md">
          <div class="dashboard-btn-container shadow-7">
            <q-btn
              to="/categories"
              icon="mdi-palette"
              label="Kategorien"
              flat
              stack
              :size="dashboardBtnSize"
              class="dashboard-btn"
              color="grey-7"
            />
          </div>
        </div>
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

export default defineComponent({
  name: "IndexPage",

  components: {
    FABCreateButton,
    WrapperCard,
    Calendar,
    AppointmentTable,
  },

  computed: {
    dashboardBtnSize() {
      if (this.$q.screen.gt.md) {
        return "25px"
      } else {
        return "20px"
      }
    },
  },
})
</script>

<style lang="scss">
.dashboard-btn-container {
  height: 150px;
  width: 300px;
  position: relative;
  margin: auto;
  background-color: #fff;
  border-radius: 8px;
}

.dashboard-btn {
  width: 100%;
  height: 100%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@media screen and (min-width: 600px) {
  .dashboard-btn-container {
    width: 165px;
  }
}

@media screen and (min-width: 1024px) {
  .left-dashboard-column {
    margin-left: 18px;
  }

  .dashboard-btn-container {
    width: 200px;
  }
}

@media screen and (min-width: 1440px) {
  .dashboard-btn-container {
    height: 200px;
    width: 300px;
  }
}
</style>
