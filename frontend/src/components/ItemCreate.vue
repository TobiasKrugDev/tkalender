<template>
  <AppointmentForm v-if="entity === 'appointment'" ref="appointmentForm" />
  <ContactForm v-if="entity === 'contact'" ref="contactForm" />
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
import AppointmentForm from "./AppointmentForm.vue"
import ContactForm from "components/ContactForm.vue"
import CategoryForm from "components/CategoryForm.vue"
import LocationForm from "components/LocationForm.vue"

export default defineComponent({
  name: "ItemCreate",

  components: {
    AppointmentForm,
    ContactForm,
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
    ...mapActions("appointments", ["createAppointment"]),
    ...mapActions("contacts", ["createContact"]),
    ...mapActions("locations", ["createLocation"]),
    ...mapActions("categories", ["createCategory"]),

    async onItemSave() {
      let isValid
      if (this.entity === "appointment") {
        isValid = this.$refs.appointmentForm.validateForm()
        if (isValid)
          await this.createAppointment(this.$refs.appointmentForm.appointment)
      } else if (this.entity === "contact") {
        isValid = this.$refs.contactForm.validateForm()
        if (isValid) await this.createContact(this.$refs.contactForm.contact)
      } else if (this.entity === "location") {
        isValid = this.$refs.locationForm.validateForm()
        if (isValid) await this.createLocation(this.$refs.locationForm.location)
      } else if (this.entity === "category") {
        isValid = this.$refs.categoryForm.validateForm()
        if (isValid) await this.createCategory(this.$refs.categoryForm.category)
      }

      if (isValid) this.$emit("itemCreated")
    },
  },
})
</script>
