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
        <span v-if="item.description"></span>
        <span v-else>-</span>
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Ort:</div>
      <div v-if="item.location">
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
              <div class="center-action-btn">
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
      <div v-else>-</div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Kontakte:</div>
      <div v-if="item.contacts.length">
        <ContactChip
          v-for="contact in item.contacts"
          :key="contact.id"
          :contact="contact"
        />
      </div>
      <div v-else>-</div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Kategorie:</div>
      <div v-if="item.category">
        <CategoryChip :category="item.category" />
      </div>
      <div v-else>-</div>
    </div>
  </div>

  <!-- Contact -->
  <div v-if="entity === 'contact'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        <span v-if="item.description">{{ item.description }}</span>
        <span v-else>-</span>
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="row">
        <div class="col">
          <div class="show-property-name q-mb-xs">Telefonnr.:</div>
          <div v-if="item.phoneNumber">
            {{ item.phoneNumber }}
          </div>
          <div v-else>-</div>
        </div>
        <div v-if="item.phoneNumber" class="col">
          <div class="relative-position full-height">
            <div class="center-action-btn">
              <q-btn
                rounded
                outline
                color="primary"
                label="Anrufen"
                icon="call"
                :href="'tel:' + item.phoneNumber"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="row">
        <div class="col">
          <div class="show-property-name q-mb-xs">E-Mail-Adresse:</div>
          <div v-if="item.emailAddress">
            {{ item.emailAddress }}
          </div>
          <div v-else>-</div>
        </div>
        <div v-if="item.emailAddress" class="col">
          <div class="relative-position full-height">
            <div class="center-action-btn">
              <q-btn
                rounded
                outline
                color="primary"
                label="E-Mail senden"
                icon="email"
                :href="'mailto:' + item.emailAddress"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Location -->
  <div v-if="entity === 'location'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        <span v-if="item.description">{{ item.description }}</span>
        <span v-else>-</span>
      </div>
    </div>
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Adresse:</div>
      <div v-if="isAddressDataGiven">
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
      <span v-else>-</span>
    </div>
    <div v-if="isAddressDataGiven" class="q-mb-lg text-center">
      <GoogleMapsEmbed :location="item" />
    </div>
  </div>

  <!-- Category -->
  <div v-if="entity === 'category'">
    <div class="q-mb-lg">
      <div class="show-property-name q-mb-xs">Beschreibung:</div>
      <div>
        <span v-if="item.description">{{ item.description }}</span>
        <span v-else>-</span>
      </div>
    </div>
  </div>

  <!-- EntityAppointmentList -->
  <div v-if="entity !== 'appointment'">
    <EntityAppointmentList :entity="entity" :entity-id="item.id" />
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
import CategoryChip from "./CategoryChip.vue"
import GoogleMapsEmbed from "./GoogleMapsEmbed.vue"
import EntityAppointmentList from "./EntityAppointmentList.vue"

export default defineComponent({
  name: "ItemShow",

  components: {
    ContactChip,
    CategoryChip,
    GoogleMapsEmbed,
    EntityAppointmentList,
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

    isAddressDataGiven() {
      if (this.item.street || this.item.postalCode || this.item.city) {
        return true
      } else {
        return false
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
.center-action-btn {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translate(0, -50%);
}
</style>
