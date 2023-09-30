<template>
  <div class="text-center">
    <div class="text-weight-medium" style="font-size: 20px">{{ day }}</div>
    <div class="text-weight-medium" style="font-size: 15px">{{ month }}</div>
    <div style="font-size: 15px">{{ year }}</div>
    <div class="q-mt-sm" style="font-size: 17px">{{ time }} Uhr</div>
  </div>
</template>

<script>
import { date } from "quasar"
import { defineComponent } from "vue"

export default defineComponent({
  name: "AppointmentTableDateDisplay",

  props: {
    appointmentDate: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      day: "",
      month: "",
      year: "",
      time: "",
    }
  },

  watch: {
    appointmentDate() {
      this.splitDate()
    },
  },

  mounted() {
    this.splitDate()
  },

  methods: {
    splitDate() {
      const jsDate = new Date(this.appointmentDate)
      this.day = date.formatDate(jsDate, "DD")
      this.month = date.formatDate(jsDate, "MMMM")
      this.year = date.formatDate(jsDate, "YYYY")
      this.time = date.formatDate(jsDate, "HH:mm")
    },
  },
})
</script>

<style lang="scss"></style>
