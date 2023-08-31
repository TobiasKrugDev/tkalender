<template>
  <q-page class="flex flex-center">
    <Calendar />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import Calendar from "components/Calendar.vue"

export default defineComponent({
  name: 'CalendarPage',

  components: {
    Calendar,
  },
})
</script>
