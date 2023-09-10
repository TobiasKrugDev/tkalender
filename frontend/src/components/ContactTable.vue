<template>
  <div class="q-pa-md">
    <q-table
      flat
      :rows="contacts"
      :columns="columns"
      @row-click="onRowClick"
    >
      <template v-slot:body-cell-image="props">
        <q-td :props="props">
          <div>
            <q-avatar v-if="props.row.image">
              <img :src="props.row.image">
            </q-avatar>
            <q-avatar v-else icon="mdi-account" color="grey-5" text-color="white" />
          </div>
        </q-td>
      </template>

      <template #body-cell-actions="props">
        <td class="text-right">
          <q-btn flat round color="red-6" icon="mdi-delete" @click="onDeleteClick(props.row)" />
        </td>
      </template>
    </q-table>

    
    <q-dialog v-model="showDialog">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Test Modal</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div>
            <input ref="contactImageUpload" type="file" accept="image/*" @change="onFileUpload()" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showDeleteDialog">
      <DeleteConfirm :item="selectedContact" entity="contact" />
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"
import DeleteConfirm from "components/DeleteConfirm.vue"

export default defineComponent({
  name: 'ContactTable',

  components: {
    DeleteConfirm,
  },

  computed: {
    ...mapState("contacts", ["contacts"]),
  },

  data () {
    return {
      selectedContact: null,
      showDialog: false,
      showDeleteDialog: false,
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
        { name: "actions" },
      ]
    }
  },

  mounted () {
    this.getContacts()
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),

    onRowClick(evt, row) {
      if (this.showDeleteDialog) {
        return
      }

      this.selectedContact = row
      this.showDialog = true
    },

    onDeleteClick (row) {
      this.selectedContact = row
      this.showDeleteDialog = true
    },

    onFileUpload() {
      var file = this.$refs.contactImageUpload.files[0]
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onloadend = function() {
        console.log('RESULT', reader.result)
      }
    }
  }
})
</script>
