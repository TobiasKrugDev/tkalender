<template>
  <q-page class="flex flex-center">
    <LocationTable />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import LocationTable from "components/LocationTable.vue"

export default defineComponent({
  name: 'LocationPage',

  components: {
    LocationTable,
  },
})
</script>
