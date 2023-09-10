<template>
  <q-page class="flex flex-center">
    <CategoryTable />

    <FABCreateButton @create-button-click="openCreateDialog" />

    <q-dialog v-model="createDialog">
      <q-card>
        ToDo: Category Create Dialog
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import CategoryTable from "components/CategoryTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"

export default defineComponent({
  name: 'CategoryPage',

  components: {
    CategoryTable,
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