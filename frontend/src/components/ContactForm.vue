<template>
  <div class="q-mb-md text-center">
    <q-avatar
      v-if="contact.image"
      size="150px"
      class="relative-position shadow-3"
    >
      <img :src="contact.image" />
      <div class="image-edit-btn-container">
        <q-btn
          flat
          round
          color="white"
          icon="edit"
          size="20px"
          class="image-edit-btn"
          @click="openFileBrowser"
        />
        <q-btn
          flat
          round
          color="white"
          icon="delete"
          size="20px"
          class="image-edit-btn"
          @click="removeImage"
        />
      </div>
    </q-avatar>
    <q-avatar
      v-else
      icon="mdi-account"
      size="150px"
      color="grey-5"
      text-color="white"
      class="shadow-3"
    >
      <div class="image-edit-btn-container">
        <q-btn
          flat
          round
          color="white"
          icon="edit"
          size="20px"
          class="image-edit-btn"
          @click="openFileBrowser"
        />
      </div>
    </q-avatar>
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      hidden
      @change="onFileUpload"
    />
  </div>
  <div class="validation-input">
    <q-input
      ref="contactFirstnameInput"
      v-model="contact.firstname"
      outlined
      label="Vorname"
      :rules="[(val) => !!val || 'Vorname ist ein Pflichtfeld']"
    />
  </div>
  <div class="validation-input">
    <q-input
      ref="contactLastnameInput"
      v-model="contact.lastname"
      outlined
      label="Nachname"
      :rules="[(val) => !!val || 'Nachname ist ein Pflichtfeld']"
    />
  </div>
  <div class="no-validation-input">
    <q-input
      v-model="contact.description"
      label="Beschreibung"
      outlined
      type="textarea"
    />
  </div>
  <div class="no-validation-input">
    <q-input v-model="contact.phoneNumber" outlined label="Telefonnr." />
  </div>
  <div class="no-validation-input">
    <q-input
      v-model="contact.emailAddress"
      outlined
      label="E-Mail-Adresse"
      lazy-rules
      :rules="[
        (val) =>
          !!val.match(
            /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
          ) || 'Bitte geben Sie eine gültige E-Mail-Adresse an',
      ]"
    />
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
    validateForm() {
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

    removeImage() {
      this.contact.image = ""
    },

    openFileBrowser() {
      this.$refs.fileInput.click()
    },

    onFileUpload() {
      var file = this.$refs.fileInput.files[0]
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onloadend = () => {
        if (file.type.startsWith("image/")) {
          this.contact.image = reader.result
        }
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
