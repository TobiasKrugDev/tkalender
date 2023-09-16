<template>
  <div class="q-mb-md">
    <q-input
      ref="contactFirstnameInput"
      v-model="contact.firstname"
      outlined
      label="Vorname"
      :rules="[(val) => !!val || 'Vorname ist ein Pflichtfeld']"
    />
  </div>
  <div class="q-mb-md">
    <q-input
      ref="contactLastnameInput"
      v-model="contact.lastname"
      outlined
      label="Nachname"
      :rules="[(val) => !!val || 'Nachname ist ein Pflichtfeld']"
    />
  </div>
  <div class="q-mb-md">
    <q-input
      v-model="contact.description"
      label="Beschreibung"
      outlined
      type="textarea"
    />
  </div>
  <div class="q-mb-md">
    <q-input v-model="contact.phoneNumber" outlined label="Telefonnr." />
  </div>
  <div class="q-mb-md">
    <q-input v-model="contact.emailAdress" outlined label="E-Mail-Adresse" />
  </div>
  <div class="q-mb-md">ToDo: Image</div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "ContactForm",

  props: {
    initialContactData: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      contact: {},
    }
  },

  mounted() {
    if (this.initialContactData) {
      this.contact = this.initialContactData
    }
  },

  methods: {
    // ToDo: Email validation (+ other specific types?)
    validateContactForm() {
      this.$refs.contactFirstnameInput.validate()
      this.$refs.contactLastnameInput.validate()
      if (
        this.$refs.contactFirstnameInput.hasError ||
        this.$refs.contactLastnameInput.hasError
      ) {
        return false
      } else {
        return true
      }
    },
  },
})
</script>
