<template>
  <div class="q-mb-md text-center">
    <q-avatar v-if="contact.image" size="150px" class="relative-position">
      <img :src="contact.image" />
      <div class="image-edit-btn-container">
        <q-btn
          flat
          round
          color="white"
          icon="edit"
          size="20px"
          class="image-edit-btn"
        />
        <q-btn
          flat
          round
          color="white"
          icon="delete"
          size="20px"
          class="image-edit-btn"
        />
      </div>
    </q-avatar>
    <q-avatar
      v-else
      icon="mdi-account"
      size="150px"
      color="grey-5"
      text-color="white"
    >
      <div class="image-edit-btn-container">
        <q-btn
          flat
          round
          color="white"
          icon="edit"
          size="20px"
          class="image-edit-btn"
        />
      </div>
    </q-avatar>
  </div>
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

<style lang="scss">
.image-edit-btn-container {
  position: absolute;
  background-color: rgba(37, 34, 34, 0.5);
  height: 100%;
  width: 100%;
  border-radius: 50%;
}

.image-edit-btn {
  margin-top: 43px;
}
</style>
