<template>
  <q-card>
    <q-card-section class="row items-center q-pb-none">
      <div class="text-h6">Löschen?</div>
      <q-space />
      <q-btn icon="close" flat round dense v-close-popup />
    </q-card-section>

    <q-card-section>
      <div>
        Sicher, dass Sie
        <span v-if="entity === 'appointment'">den Termin</span>
        <span v-if="entity === 'contact'">den Kontakt</span>
        <span v-if="entity === 'location'">den Ort</span>
        <span v-if="entity === 'category'">die Kategorie</span>
        "{{ itemName }}" wirklich löschen möchten?
      </div>
    </q-card-section>

    <q-card-actions align="right">
      <q-btn v-close-popup flat label="Abbrechen" color="primary" />
      <q-btn
        label="Löschen"
        color="negative"
        @click="deleteItem"
        :loading="loading"
        ><template v-slot:loading> <q-spinner-hourglass /> </template
      ></q-btn>
    </q-card-actions>
  </q-card>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions } from "vuex"

export default defineComponent({
  name: "DeleteConfirm",

  props: {
    item: {
      type: Object,
      required: true,
      default: null,
    },

    entity: {
      type: String,
      required: true,
      default: "",
    },
  },

  emits: ["itemDeleted"],

  data() {
    return {
      loading: false,
    }
  },

  computed: {
    itemName() {
      if (this.entity === "contact") {
        return `${this.item.firstname} ${this.item.lastname}`
      } else {
        return this.item.name
      }
    },
  },

  methods: {
    ...mapActions("appointments", ["deleteAppointment"]),
    ...mapActions("contacts", ["deleteContact"]),
    ...mapActions("locations", ["deleteLocation"]),
    ...mapActions("categories", ["deleteCategory"]),

    async deleteItem() {
      this.loading = true
      switch (this.entity) {
        case "appointment":
          await this.deleteAppointment(this.item.id)
          break
        case "contact":
          await this.deleteContact(this.item.id)
          break
        case "location":
          await this.deleteLocation(this.item.id)
          break
        case "category":
          await this.deleteCategory(this.item.id)
          break
      }

      this.$emit("itemDeleted")
      this.loading = false
    },
  },
})
</script>

<style lang="scss"></style>
