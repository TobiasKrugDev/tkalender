<template>
  <q-page class="flex flex-center">
    <CategoryTable />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import CategoryTable from "components/CategoryTable.vue"

export default defineComponent({
  name: 'CategoryPage',

  components: {
    CategoryTable,
  },
})
</script>