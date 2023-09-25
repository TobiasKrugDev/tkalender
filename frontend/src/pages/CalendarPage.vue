<template>
  <q-page class="flex">
    <WrapperCard title="Kalender">
      <Calendar ref="calendar" />
    </WrapperCard>

    <FABCreateButton @create-button-click="openCreateDialog" />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import Calendar from "components/Calendar.vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: "CalendarPage",

  components: {
    Calendar,
    FABCreateButton,
    WrapperCard,
  },

  data() {
    return {
      createDialog: false,
    }
  },

  mounted() {
    // Check for "/create_appointment" in requested route
    if (this.$route.path.includes("/create_appointment")) {
      this.openCreateDialog()
    }
  },

  methods: {
    openCreateDialog() {
      this.$refs.calendar.openCreateDialog()
    },
  },
})
</script>
