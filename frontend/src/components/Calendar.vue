<template>
  <div>
    <vue-cal
      active-view="week"
      style="height: 75vh; width: 100%"
      :disable-views="['years', 'year']"
      locale="de"
      :events="events"
      events-on-month-view="short"
      :on-event-click="onEventClick"
      today-button
    >
      <template #today-button>
        <q-btn flat color="white" icon="gps_fixed">
          <q-tooltip> Heute </q-tooltip>
        </q-btn>
      </template>
    </vue-cal>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState } from "vuex"
import VueCal from "vue-cal"
import "vue-cal/dist/vuecal.css"

export default defineComponent({
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Calendar",

  components: {
    VueCal,
  },

  computed: {
    ...mapState("appointments", ["appointments"]),
  },

  data() {
    return {
      selectedAppointment: null,
      events: [],
    }
  },

  mounted() {
    this.handleAppointments()
  },

  methods: {
    ...mapActions("appointments", ["getAppointments"]),

    async handleAppointments() {
      await this.getAppointments()
      this.events = []
      for (let appointment of this.appointments) {
        let event = {
          start: new Date(appointment.startAt),
          end: new Date(appointment.endAt),
          title: appointment.name,
          class: appointment.category ? appointment.category : "no-category",
        }

        this.events = [...this.events, event]
      }
    },

    onEventClick(event, e) {
      this.selectedAppointment = event
      console.log(event)
      // this.showDialog = true

      // Prevent navigating to narrower view (default vue-cal behavior).
      e.stopPropagation()
    },
  },
})
</script>

<style lang="scss">
.vuecal__now-line {
  color: #06c;
}
.vuecal__event.no-category {
  background-color: rgba(3, 36, 252, 0.9);
  border: 1px solid rgb(3, 36, 252);
  color: #fff;
}

.vuecal__event:hover {
  cursor: pointer;
}

.vuecal__flex .vuecal__menu {
  background-color: $primary;
  color: white;
}

.vuecal__title-bar {
  background-color: rgba(33, 33, 206, 0.7);
  color: white;
}

.vuecal__title-bar button {
  color: white;
}

.vuecal__menu {
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}
</style>
