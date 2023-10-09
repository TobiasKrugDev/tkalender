<template>
  <div>
    <div class="validation-input">
      <q-input
        ref="nameInput"
        v-model="appointment.name"
        outlined
        label="Name"
        :rules="[(val) => !!val || 'Name ist ein Pflichtfeld']"
      />
    </div>
    <div class="validation-input row">
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
    <div class="no-validation-input">
      <q-input
        v-model="appointment.description"
        label="Beschreibung"
        outlined
        type="textarea"
      />
    </div>
    <div class="row no-validation-input">
      <div class="col-10">
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
          <template v-slot:option="scope">
            <q-item v-bind="scope.itemProps">
              <q-item-section>
                <q-item-label>{{ scope.opt.name }}</q-item-label>
                <q-item-label
                  caption
                  class="option-slot-description ellipsis"
                  >{{ scope.opt.description }}</q-item-label
                >
                <q-item-label caption
                  >{{ scope.opt.streetAddress
                  }}<span v-if="scope.opt.postalCode || scope.opt.city">,</span>
                  {{ scope.opt.postalCode }} {{ scope.opt.city }}</q-item-label
                >
              </q-item-section>
            </q-item>
          </template>
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
    <div class="row no-validation-input">
      <div class="col-10">
        <q-select
          outlined
          multiple
          v-model="appointment.contacts"
          use-input
          fill-input
          input-debounce="250"
          label="Kontakte"
          :options="contacts"
          @filter="filterContacts"
        >
          <template v-slot:option="scope">
            <q-item v-bind="scope.itemProps">
              <q-item-section avatar>
                <q-avatar v-if="scope.opt.image" class="shadow-3">
                  <img :src="scope.opt.image" />
                </q-avatar>
                <q-avatar
                  v-else
                  icon="mdi-account"
                  color="grey-5"
                  text-color="white"
                  class="shadow-3"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label
                  >{{ scope.opt.firstname }}
                  {{ scope.opt.lastname }}</q-item-label
                >
                <q-item-label
                  v-if="scope.opt.description"
                  caption
                  class="option-slot-description ellipsis"
                  >{{ scope.opt.description }}</q-item-label
                >
                <q-item-label v-if="scope.opt.emailAddress" caption>{{
                  scope.opt.emailAddress
                }}</q-item-label>
                <q-item-label v-if="scope.opt.phoneNumber" caption>{{
                  scope.opt.phoneNumber
                }}</q-item-label>
              </q-item-section>
            </q-item>
          </template>
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey">
                Keine Ergebnisse
              </q-item-section>
            </q-item>
          </template>
          <template v-slot:selected-item="props">
            <ContactChip
              :contact="props.opt"
              removable
              @remove="onContactChipRemove(props.index)"
            />
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
            @click="openCreateDialog('contact')"
          />
        </div>
      </div>
    </div>
    <div class="row q-mb-md">
      <div class="col-10">
        <q-select
          outlined
          v-model="appointment.category"
          use-input
          input-debounce="250"
          label="Kategorie"
          :options="categories"
          option-label="name"
          @filter="filterCategories"
        >
          <template v-slot:option="scope">
            <q-item v-bind="scope.itemProps">
              <q-item-section avatar>
                <div
                  class="category-table-color-circle"
                  :style="'background-color: ' + scope.opt.color"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ scope.opt.name }}</q-item-label>
                <q-item-label
                  v-if="scope.opt.description"
                  caption
                  class="option-slot-description ellipsis"
                  >{{ scope.opt.description }}</q-item-label
                >
              </q-item-section>
            </q-item>
          </template>
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey">
                Keine Ergebnisse
              </q-item-section>
            </q-item>
          </template>
          <template v-slot:selected-item="props">
            <CategoryChip :category="props.opt" />
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

    <q-dialog v-model="timespanErrorAlert">
      <q-card>
        <q-card-section>
          <div class="text-h6">
            <q-icon
              name="warning"
              color="negative"
              size="2rem"
              class="q-mr-sm"
            />Warnung!
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none text-weight-medium q-my-md">
          Das angegebene Enddatum des Termins liegt vor dem Startdatum.
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="OK" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState, mapMutations } from "vuex"
import { date } from "quasar"
import ContactChip from "./ContactChip.vue"
import CategoryChip from "./CategoryChip.vue"

export default defineComponent({
  name: "AppointmentForm",

  components: {
    ContactChip,
    CategoryChip,
  },

  props: {
    initialAppointmentData: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      appointment: {},
      timespanErrorAlert: false,
    }
  },

  computed: {
    ...mapState("appointments", ["createdTimespanStart", "createdTimespanEnd"]),
    ...mapState("contacts", ["contacts", "createdContact"]),
    ...mapState("locations", ["locations", "createdLocation"]),
    ...mapState("categories", ["categories", "createdCategory"]),
  },

  watch: {
    createdContact(newValue) {
      this.appointment.contacts.push(newValue)
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

    if (this.createdTimespanStart)
      this.appointment.startAt = this.createdTimespanStart
    if (this.createdTimespanEnd)
      this.appointment.endAt = this.createdTimespanEnd
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),
    ...mapActions("locations", ["getLocations"]),
    ...mapActions("categories", ["getCategories"]),
    ...mapMutations("shortcuts", [
      "setShortcutCreateDialog",
      "setShortcutCreateEntity",
    ]),
    validateForm() {
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
        // Extra check if end date is set earlier than start date
        const startAt = new Date(this.appointment.startAt)
        const endAt = new Date(this.appointment.endAt)
        if (startAt > endAt) {
          this.timespanErrorAlert = true
          return false
        } else {
          return true
        }
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

    onContactChipRemove(index) {
      this.appointment.contacts.splice(index, 1)
    },
  },
})
</script>

<style lang="scss">
.option-slot-description {
  max-width: 400px;
}

@media screen and (max-width: 600px) {
  .option-slot-description {
    max-width: 200px;
  }
}
</style>
