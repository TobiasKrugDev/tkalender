<template>
  <div>
    <div class="q-mb-md">
      <q-input
        ref="nameInput"
        v-model="appointment.name"
        outlined
        label="Name"
        :rules="[(val) => !!val || 'Name ist ein Pflichtfeld']"
      />
    </div>
    <div class="q-mb-md row">
      <div class="col q-mr-sm">
        <q-input
          ref="startAtInput"
          outlined
          v-model="appointment.startAt"
          label="Start"
          type="datetime-local"
          stack-label
          :rules="[(val) => !!val || 'Start ist ein Pflichtfeld']"
        />
      </div>
      <div class="col q-ml-sm">
        <q-input
          ref="endAtInput"
          outlined
          v-model="appointment.endAt"
          label="Ende"
          stack-label
          type="datetime-local"
          :rules="[(val) => !!val || 'Ende ist ein Pflichtfeld']"
        />
      </div>
    </div>
    <div class="q-mb-md">
      <q-input
        v-model="appointment.description"
        label="Beschreibung"
        outlined
        type="textarea"
      />
    </div>
    <div class="row q-mb-md">
      <div class="col-10">
        <!-- ToDo: Use custom option slot for more information -->
        <q-select
          outlined
          v-model="appointment.location"
          use-input
          hide-selected
          fill-input
          input-debounce="250"
          label="Ort"
          :options="locations"
          option-label="name"
          @filter="filterLocations"
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey">
                Keine Ergebnisse
              </q-item-section>
            </q-item>
          </template>
        </q-select>
      </div>
      <div class="col-2">
        <div class="relative-position full-height">
          <q-btn
            round
            color="primary"
            icon="add"
            class="absolute-center"
            @click="openCreateDialog('location')"
          />
        </div>
      </div>
    </div>
    <div class="q-mb-md">
      <!-- ToDo: Use custom option slot for more information -->
      <!-- ToDo: Adjust option-label -->
      <q-select
        outlined
        v-model="appointment.contacts"
        use-input
        hide-selected
        fill-input
        input-debounce="250"
        label="Kontakte"
        :options="contacts"
        option-label="lastname"
        @filter="filterContacts"
      >
        <template v-slot:no-option>
          <q-item>
            <q-item-section class="text-grey">
              Keine Ergebnisse
            </q-item-section>
          </q-item>
        </template>
      </q-select>
    </div>
    <div class="row q-mb-md">
      <div class="col-10">
        <!-- ToDo: Use custom option slot for more information -->
        <q-select
          outlined
          v-model="appointment.category"
          use-input
          hide-selected
          fill-input
          input-debounce="250"
          label="Kategorie"
          :options="categories"
          option-label="name"
          @filter="filterCategories"
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey">
                Keine Ergebnisse
              </q-item-section>
            </q-item>
          </template>
        </q-select>
      </div>
      <div class="col-2">
        <div class="relative-position full-height">
          <q-btn
            round
            color="primary"
            icon="add"
            class="absolute-center"
            @click="openCreateDialog('category')"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState, mapMutations } from "vuex"

export default defineComponent({
  name: "AppointmentForm",

  components: {},

  props: {
    initialAppointmentData: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      appointment: {},
    }
  },

  computed: {
    ...mapState("contacts", ["contacts", "createdContact"]),
    ...mapState("locations", ["locations", "createdLocation"]),
    ...mapState("categories", ["categories", "createdCategory"]),
  },

  watch: {
    createdContact(newValue) {
      // ToDo
    },

    createdLocation(newValue) {
      this.appointment.location = newValue
    },

    createdCategory(newValue) {
      this.appointment.category = newValue
    },
  },

  mounted() {
    if (this.initialAppointmentData) {
      this.appointment = this.initialAppointmentData
    }
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),
    ...mapActions("locations", ["getLocations"]),
    ...mapActions("categories", ["getCategories"]),
    ...mapMutations("shortcuts", [
      "setShortcutCreateDialog",
      "setShortcutCreateEntity",
    ]),
    validateAppointmentForm() {
      this.$refs.nameInput.validate()
      this.$refs.startAtInput.validate()
      this.$refs.endAtInput.validate()
      if (
        this.$refs.nameInput.hasError ||
        this.$refs.startAtInput.hasError ||
        this.$refs.endAtInput.hasError
      ) {
        return false
      } else {
        return true
      }
    },

    async filterContacts(val, update) {
      const params = { rowsPerPage: 25, page: 1, filter: val }
      await this.getContacts(params)
      update()
    },

    async filterLocations(val, update) {
      const params = { rowsPerPage: 25, page: 1, filter: val }
      await this.getLocations(params)
      update()
    },

    async filterCategories(val, update) {
      const params = { rowsPerPage: 25, page: 1, filter: val }
      await this.getCategories(params)
      update()
    },

    openCreateDialog(entity) {
      this.setShortcutCreateEntity(entity)
      this.setShortcutCreateDialog(true)
    },
  },
})
</script>

<style lang="scss"></style>
