<template>
  <!-- Appointment -->
  <div v-if="entity === 'appointment'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Datum:</div>
      <div>{{ appointmentDateSpan }}</div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        {{ item.description }}
      </div>
    </div>
    <div v-if="item.location" class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Ort:</div>
      <div class="row">
        <div class="col">
          <div>
            {{ item.location.name }}
          </div>
          <div>
            {{ item.location.streetAddress }}
          </div>
          <div>{{ item.location.postalCode }} {{ item.location.city }}</div>
        </div>
        <div class="col">
          <div class="relative-position full-height">
            <div class="location-map-button">
              <q-btn
                rounded
                outline
                color="primary"
                :label="showMap ? 'Karte schließen' : 'Karte öffnen'"
                :icon="showMap ? 'close' : 'location_on'"
                @click="toggleMapEmbed"
              />
            </div>
          </div>
        </div>
      </div>
      <q-slide-transition>
        <div v-show="showMap">
          <GoogleMapsEmbed :location="item.location" class="q-mt-lg" />
        </div>
      </q-slide-transition>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Kontakte:</div>
      <ContactChip
        v-for="contact in item.contacts"
        :key="contact.id"
        :contact="contact"
      />
    </div>
    <div v-if="item.category" class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Kategorie:</div>
      <div>
        <!-- ToDo: Text color depending on background color -->
        <q-chip
          text-color="white"
          :style="'background-color: ' + item.category.color"
          >{{ item.category.name }}</q-chip
        >
      </div>
    </div>
  </div>

  <!-- Contact -->
  <div v-if="entity === 'contact'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        {{ item.description }}
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Telefonnr.:</div>
      <div>
        {{ item.phoneNumber }}
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">E-Mail-Adresse:</div>
      <div>
        {{ item.emailAddress }}
      </div>
    </div>
  </div>

  <!-- Location -->
  <div v-if="entity === 'location'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        {{ item.description }}
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Adresse:</div>
      <div>
        {{ item.streetAddress }}
      </div>
      <div>
        {{ item.postalCode }}
      </div>
      <div>
        {{ item.city }}
      </div>
    </div>
    <div class="q-mb-lg text-center">
      <GoogleMapsEmbed :location="item" />
    </div>
  </div>

  <!-- Category -->
  <div v-if="entity === 'category'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        {{ item.description }}
      </div>
    </div>
  </div>

  <div class="text-right q-mt-xl">
    <q-btn
      rounded
      flat
      color="red-6"
      icon="delete"
      :label="$q.screen.gt.xs ? 'Löschen' : ''"
      class="q-mr-xs"
      @click="onDeleteClick"
    />
    <q-btn
      rounded
      color="primary"
      icon="edit"
      :label="$q.screen.gt.xs ? 'Bearbeiten' : ''"
      class="q-ml-xs"
      @click="onEditClick"
    />
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { date } from "quasar"
import ContactChip from "./ContactChip.vue"
import GoogleMapsEmbed from "./GoogleMapsEmbed.vue"

export default defineComponent({
  name: "ItemShow",

  components: {
    ContactChip,
    ContactChip,
    GoogleMapsEmbed,
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

  emits: ["deleteClick", "editClick"],

  data() {
    return {
      showMap: false,
    }
  },

  computed: {
    appointmentDateSpan() {
      const jsStartAt = new Date(this.item.startAt)
      const jsEndAt = new Date(this.item.endAt)

      // Check if start and end is the same day
      if (jsStartAt.getDate() === jsEndAt.getDate()) {
        return (
          date.formatDate(jsStartAt, "DD. MMMM YYYY - HH:mm") +
          " bis " +
          date.formatDate(jsEndAt, "HH:mm") +
          " Uhr"
        )
      } else {
        return (
          date.formatDate(jsStartAt, "DD. MMMM YYYY - HH:mm") +
          " Uhr bis " +
          date.formatDate(jsEndAt, "DD. MMMM YYYY - HH:mm") +
          " Uhr"
        )
      }
    },
  },

  methods: {
    onDeleteClick() {
      this.$emit("deleteClick", this.item)
    },

    onEditClick() {
      this.$emit("editClick", this.item)
    },

    toggleMapEmbed() {
      this.showMap = !this.showMap
    },
  },
})
</script>

<style lang="scss">
.location-map-button {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translate(0, -50%);
}
</style>
