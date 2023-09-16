<template>
  <q-page class="flex">
    <WrapperCard title="Suchergebnisse">
      <!-- ToDo: AppointmentTable -->
      <ContactTable
        v-if="displayContactTable"
        :searchMode="true"
        class="q-mb-lg"
        @search-results-fetched="
          (foundItems) => handleSearchResults('contact', foundItems)
        "
      />
      <LocationTable
        v-if="displayLocationTable"
        :searchMode="true"
        class="q-mb-lg"
        @search-results-fetched="
          (foundItems) => handleSearchResults('location', foundItems)
        "
      />
      <CategoryTable
        v-if="displayCategoryTable"
        :searchMode="true"
        class="q-mb-lg"
        @search-results-fetched="
          (foundItems) => handleSearchResults('category', foundItems)
        "
      />

      <div
        v-if="noSearchResultsFound"
        class="text-h4 text-center text-grey-7 no-search-results-found"
      >
        <div class="q-mb-lg">
          <q-icon name="sentiment_dissatisfied" size="75px" />
        </div>
        <div>Keine Suchergebnisse gefunden</div>
      </div>
    </WrapperCard>

    <!-- <FABCreateButton @create-button-click="openCreateDialog" /> -->
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import { useQuasar } from "quasar"
// import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"
import ContactTable from "components/ContactTable.vue"
import LocationTable from "components/LocationTable.vue"
import CategoryTable from "components/CategoryTable.vue"

export default defineComponent({
  name: "SearchPage",

  components: {
    // FABCreateButton,
    WrapperCard,
    ContactTable,
    LocationTable,
    CategoryTable,
  },

  data() {
    return {
      fetchedEntities: [],
      noSearchResultsFound: false,
      displayAppointmentTable: true,
      displayContactTable: true,
      displayLocationTable: true,
      displayCategoryTable: true,
    }
  },

  mounted() {
    // Show loading screen
    this.$q.loading.show({
      message: "Suche...",
    })
  },

  methods: {
    handleSearchResults(entity, totalItems) {
      this.fetchedEntities.push(entity)

      if (totalItems === 0) {
        if (entity === "appointment") {
          this.displayAppointmentTable = false
        } else if (entity === "contact") {
          this.displayContactTable = false
        } else if (entity === "location") {
          this.displayLocationTable = false
        } else if (entity === "category") {
          this.displayCategoryTable = false
        }
      }

      // ToDo: Adjust this number and if statement
      if (this.fetchedEntities.length === 3) {
        // Hide loading screen
        this.$q.loading.hide()
        if (
          this.displayContactTable === false &&
          this.displayLocationTable === false &&
          this.displayCategoryTable === false
        ) {
          this.noSearchResultsFound = true
        }
      }
    },
  },
})
</script>

<style lang="scss">
.no-search-results-found {
  margin-top: 200px;
}
</style>
