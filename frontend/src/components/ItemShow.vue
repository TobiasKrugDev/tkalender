<template>
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
      <!-- <iframe
        :src="
          'https://maps.google.com/maps?&amp;q=' +
          item.streetAddress +
          ' ' +
          item.postalCode +
          ' ' +
          item.city +
          '&amp;output=embed'
        "
        width="500"
        height="400"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        class="google-maps-iframe"
      ></iframe> -->
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
    <div>
      <div class="show-property-name q-mb-xs">Farbe:</div>
      <div>
        <div
          class="category-table-color-circle"
          :style="'background-color: ' + item.color"
        />
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

export default defineComponent({
  name: "ItemShow",

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

  methods: {
    onDeleteClick() {
      this.$emit("deleteClick", this.item)
    },

    onEditClick() {
      this.$emit("editClick", this.item)
    },
  },
})
</script>

<style lang="scss">
.google-maps-iframe {
  border: 0;
  width: 100%;
  border-radius: 8px;
}
</style>
