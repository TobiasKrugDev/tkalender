<template>
  <div>
    <vue-cal 
        active-view="week"
        style="height: 80vh; width: 80vw;" 
        :disable-views="['years', 'year']"
        locale="de"
        :events="events"
        events-on-month-view="short"
        :on-event-click="onEventClick"
    />
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

export default defineComponent({
  // eslint-disable-next-line vue/multi-word-component-names
  name: 'Calendar',

  components: { 
    VueCal 
    },
  
  computed: {
    ...mapState("appointments", ["appointments"]),
  },

  data () {
    return {
        selectedAppointment: null,
        events: []
    }
  },

  mounted () {
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
            }
            
            this.events = [ ...this.events, event ]
        }
    },

    onEventClick(event, e) {
        this.selectedAppointment = event
        console.log(event)
        // this.showDialog = true

        // Prevent navigating to narrower view (default vue-cal behavior).
        e.stopPropagation()
    }
  }
})
</script>

<style lang="scss">
.vuecal__now-line {
    color: #06c;
}
</style>
