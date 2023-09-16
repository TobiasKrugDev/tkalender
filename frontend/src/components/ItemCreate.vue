<template>
  <LocationForm v-if="entity === 'location'" ref="locationForm" />
  <CategoryForm v-if="entity === 'category'" ref="categoryForm" />

  <div class="text-right q-mt-xl">
    <q-btn
      rounded
      flat
      color="grey-7"
      icon="close"
      :label="$q.screen.gt.xs ? 'Abbrechen' : ''"
      class="q-mr-xs"
      v-close-popup
    />
    <q-btn
      rounded
      color="primary"
      icon="mdi-content-save"
      label="Speichern"
      class="q-ml-xs"
      @click="onItemSave"
    />
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions } from "vuex"
import CategoryForm from "components/CategoryForm.vue"
import LocationForm from "components/LocationForm.vue"

export default defineComponent({
  name: "ItemCreate",

  components: {
    CategoryForm,
    LocationForm,
  },

  props: {
    entity: {
      type: String,
      required: true,
    },
  },

  emits: ["itemCreated"],

  methods: {
    ...mapActions("categories", ["createCategory"]),
    ...mapActions("locations", ["createLocation"]),

    async onItemSave() {
      let isValid
      if (this.entity === "location") {
        isValid = this.$refs.locationForm.validateLocationForm()
        if (isValid) await this.createLocation(this.$refs.locationForm.location)
      } else if (this.entity === "category") {
        isValid = this.$refs.categoryForm.validateCategoryForm()
        if (isValid) await this.createCategory(this.$refs.categoryForm.category)
      }

      if (isValid) this.$emit("itemCreated")
    },
  },
})
</script>

<style lang="scss"></style>
