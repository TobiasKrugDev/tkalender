<template>
  <q-page class="flex">
    <WrapperCard title="Kalender">
      <Calendar />
    </WrapperCard>

    <FABCreateButton @create-button-click="openCreateDialog" />

    <q-dialog v-model="createDialog">
      <q-card> ToDo: Appointment Create Dialog </q-card>
    </q-dialog>
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
      this.createDialog = true
    },
  },
})
</script>
