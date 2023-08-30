<template>
  <q-page class="flex flex-center">
    <ContactTable />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import ContactTable from "components/ContactTable.vue"

export default defineComponent({
  name: 'ContactPage',

  components: {
    ContactTable,
  },
})
</script>
