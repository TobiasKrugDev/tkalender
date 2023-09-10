<template>
  <q-page class="flex">
    ToDo: Appointment List

    <FABCreateButton @create-button-click="openCreateDialog" />

    <q-dialog v-model="createDialog">
      <q-card>
        ToDo: Appointment Create Dialog
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import FABCreateButton from "components/FABCreateButton.vue"

export default defineComponent({
  name: 'AppointmentPage',

  components: {
    FABCreateButton,
  },

  data () {
    return {
      createDialog: false,
    }
  },

  methods: {
    openCreateDialog () {
      this.createDialog = true
    }
  }
})
</script>
