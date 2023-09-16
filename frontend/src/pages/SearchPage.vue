<template>
  <q-page class="flex">
    <WrapperCard title="Suchergebnisse">
      <!-- ToDo: AppointmentTable -->
      <!-- ToDo: ContactTable -->
      <LocationTable ref="locationTable" :searchMode="true" class="q-mb-lg" />
      <CategoryTable ref="categoryTable" :searchMode="true" />
    </WrapperCard>

    <!-- <FABCreateButton @create-button-click="openCreateDialog" /> -->
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
// import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"
import LocationTable from "components/LocationTable.vue"
import CategoryTable from "components/CategoryTable.vue"

export default defineComponent({
  name: "SearchPage",

  components: {
    // FABCreateButton,
    WrapperCard,
    LocationTable,
    CategoryTable,
  },

  data() {
    return {}
  },

  mounted() {},

  methods: {},
})
</script>
