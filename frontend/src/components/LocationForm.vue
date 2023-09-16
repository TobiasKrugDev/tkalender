<template>
  <div class="q-mb-md">
    <q-input
      ref="locationNameInput"
      v-model="location.name"
      outlined
      label="Name"
      :rules="[(val) => !!val || 'Name ist ein Pflichtfeld']"
    />
  </div>
  <div class="q-mb-md">
    <q-input
      v-model="location.description"
      label="Beschreibung"
      outlined
      type="textarea"
    />
  </div>
  <div class="q-mb-md">
    <q-input
      v-model="location.streetAddress"
      label="Straße + Hausnr."
      outlined
    />
  </div>
  <div class="q-mb-md">
    <q-input v-model="location.postalCode" label="Postleitzahl" outlined />
  </div>
  <div class="q-mb-md">
    <q-input v-model="location.city" label="Ort" outlined />
  </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "LocationForm",

  props: {
    initialLocationData: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      location: {},
    }
  },

  mounted() {
    if (this.initialLocationData) {
      this.location = this.initialLocationData
    }
  },

  methods: {
    validateLocationForm() {
      this.$refs.locationNameInput.validate()

      if (this.$refs.locationNameInput.hasError) {
        return false
      } else {
        return true
      }
    },
  },
})
</script>
