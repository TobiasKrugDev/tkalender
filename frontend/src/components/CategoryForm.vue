<template>
  <div class="q-mb-md">
    <q-input
      ref="categoryNameInput"
      v-model="category.name"
      outlined
      label="Name"
      :rules="[(val) => !!val || 'Name ist ein Pflichtfeld']"
    />
  </div>
  <div class="q-mb-md">
    <q-input
      v-model="category.description"
      label="Beschreibung"
      outlined
      type="textarea"
    />
  </div>
  <div>
    <q-field
      ref="colorPickerInput"
      v-model="category.color"
      label="Farbe"
      stack-label
      outlined
      :rules="[(val) => !!val || 'Farbe ist ein Pflichtfeld']"
    >
      <template v-slot:control>
        <q-color
          v-model="category.color"
          no-header-tabs
          no-footer
          class="my-picker q-mx-auto q-my-lg"
        />
      </template>
    </q-field>
  </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "CategoryForm",

  props: {
    initialCategoryData: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      category: {},
    }
  },

  mounted() {
    if (this.initialCategoryData) {
      this.category = this.initialCategoryData
    }
  },

  methods: {
    validateForm() {
      this.$refs.categoryNameInput.validate()
      this.$refs.colorPickerInput.validate()

      if (
        this.$refs.categoryNameInput.hasError ||
        this.$refs.colorPickerInput.hasError
      ) {
        return false
      } else {
        return true
      }
    },
  },
})
</script>

<style lang="scss">
.q-color-picker {
  width: 250px;
}
</style>
