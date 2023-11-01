<template>
  <q-page class="flex">
    <WrapperCard title="Termine">
      <AppointmentTable ref="appointmentTable" />
    </WrapperCard>

    <!-- Floating Create Button -->
    <FABCreateButton @create-button-click="openCreateDialog" />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import AppointmentTable from "components/AppointmentTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: "AppointmentPage",

  components: {
    AppointmentTable,
    FABCreateButton,
    WrapperCard,
  },

  data() {
    return {
      createDialog: false,
    }
  },

  mounted() {
    // Check for "/create" in requested route
    if (this.$route.path.includes("/create")) {
      this.openCreateDialog()
    }
  },

  methods: {
    openCreateDialog() {
      this.$refs.appointmentTable.openCreateDialog()
    },
  },
})
</script>
