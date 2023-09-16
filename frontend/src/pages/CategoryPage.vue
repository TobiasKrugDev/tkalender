<template>
  <q-page class="flex">
    <WrapperCard title="Kategorien">
      <CategoryTable ref="categoryTable" />
    </WrapperCard>

    <FABCreateButton @create-button-click="openCreateDialog" />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import CategoryTable from "components/CategoryTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: "CategoryPage",

  components: {
    CategoryTable,
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
      this.$refs.categoryTable.openCreateDialog()
    },
  },
})
</script>
