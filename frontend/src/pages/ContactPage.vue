<template>
  <q-page class="flex flex-center">
    <ContactTable />

    <FABCreateButton @create-button-click="openCreateDialog" />

    <q-dialog v-model="createDialog">
      <q-card>
        ToDo: Contact Create Dialog
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import ContactTable from "components/ContactTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"

export default defineComponent({
  name: 'ContactPage',

  components: {
    ContactTable,
    FABCreateButton,
  },

  data () {
    return {
      createDialog: false,
    }
  },

  mounted () {
    // Check for "/create" in requested route
    if (this.$route.path.includes("/create")) {
      this.openCreateDialog()
    }
  },

  methods: {
    openCreateDialog () {
      this.createDialog = true
    }
  }
})
</script>
