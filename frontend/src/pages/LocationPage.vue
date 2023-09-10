<template>
  <q-page class="flex flex-center">
    <WrapperCard title="Orte">
      <LocationTable />
    </WrapperCard>

    <FABCreateButton @create-button-click="openCreateDialog" />

    <q-dialog v-model="createDialog">
      <q-card>
        ToDo: Location Create Dialog
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import LocationTable from "components/LocationTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: 'LocationPage',

  components: {
    LocationTable,
    FABCreateButton,
    WrapperCard,
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
