<template>
  <AppointmentForm
    v-if="entity === 'appointment'"
    ref="appointmentForm"
    :initial-contact-data="copiedItemData"
  />
  <ContactForm
    v-if="entity === 'contact'"
    ref="contactForm"
    :initial-contact-data="copiedItemData"
  />
  <LocationForm
    v-if="entity === 'location'"
    ref="locationForm"
    :initial-location-data="copiedItemData"
  />
  <CategoryForm
    v-if="entity === 'category'"
    ref="categoryForm"
    :initial-category-data="copiedItemData"
  />

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
import LocationForm from "components/LocationForm.vue"
import CategoryForm from "components/CategoryForm.vue"

export default defineComponent({
  name: "ItemUpdate",

  components: {
    AppointmentForm,
    ContactForm,
    LocationForm,
    CategoryForm,
  },

  props: {
    item: {
      type: Object,
      required: true,
    },
    entity: {
      type: String,
      required: true,
    },
  },

  emits: ["itemUpdated"],

  computed: {
    // Prevent vuex mutate errors
    copiedItemData() {
      return { ...this.item }
    },
  },

  methods: {
    ...mapActions("appointments", ["updateAppointment"]),
    ...mapActions("contacts", ["updateContact"]),
    ...mapActions("locations", ["updateLocation"]),
    ...mapActions("categories", ["updateCategory"]),

    async onItemSave() {
      let isValid
      if (this.entity === "appointment") {
        isValid = this.$refs.appointmentForm.validateAppointmentForm()
        if (isValid)
          await this.updateAppointment(this.$refs.appointmentForm.appointment)
      } else if (this.entity === "contact") {
        isValid = this.$refs.contactForm.validateContactForm()
        if (isValid) await this.updateContact(this.$refs.contactForm.contact)
      } else if (this.entity === "location") {
        // ToDo: "validateForm()" is sufficient
        isValid = this.$refs.locationForm.validateLocationForm()
        if (isValid) await this.updateLocation(this.$refs.locationForm.location)
      } else if (this.entity === "category") {
        isValid = this.$refs.categoryForm.validateCategoryForm()
        if (isValid) await this.updateCategory(this.$refs.categoryForm.category)
      }

      if (isValid) this.$emit("itemUpdated")
    },
  },
})
</script>
