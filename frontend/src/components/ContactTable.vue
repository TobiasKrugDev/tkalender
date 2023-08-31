<template>
  <div class="q-pa-md">
    <q-table
      title="Kontakte"
      :rows="contacts"
      :columns="columns"
    >
      <template v-slot:body-cell-image="props">
        <q-td :props="props">
          <div>
            <q-avatar>
              <img src="https://cdn.quasar.dev/img/avatar.png">
            </q-avatar>
          </div>
        </q-td>
      </template>
    </q-table>
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
          name: 'image',
          label: '',
          align: 'left',
          field: 'image'
        },
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
