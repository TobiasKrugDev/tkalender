<template>
  <div class="q-pa-md">
    <q-table
      title="Kontakte"
      :rows="contacts"
      :columns="columns"
    />
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"

export default defineComponent({
  name: 'ContactTable',
    computed: {
    ...mapState("contacts", ["contacts"]),
  },

  data () {
    return {
      columns: [
        {
          name: 'firstname',
          label: 'Vorname',
          align: 'left',
          field: 'firstname'
        },
        {
          name: 'lastname',
          label: 'Nachname',
          align: 'left',
          field: 'lastname'
        },
        {
          name: 'description',
          label: 'Beschreibung',
          align: 'left',
          field: 'description'
        },
        {
          name: 'phoneNumber',
          label: 'Telefonnr.',
          align: 'left',
          field: 'phoneNumber'
        },
        {
          name: 'emailAddress',
          label: 'E-Mail-Adresse',
          align: 'left',
          field: 'emailAddress'
        },
        {
          name: 'image',
          label: 'Bild',
          align: 'left',
          field: 'image'
        },
      ]
    }
  },

  mounted () {
    this.getContacts()
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),
  }
})
</script>
