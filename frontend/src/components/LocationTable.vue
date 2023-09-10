<template>
  <div>
    <q-table
      flat
      :rows="locations"
      :columns="columns"
      @row-click="onRowClick"
    >
      <template #body-cell-actions="props">
        <td class="text-right">
          <q-btn flat round color="dark" icon="mdi-delete" @click="onDeleteClick(props.row)" />
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
            ToDo: Show Ansicht
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showDeleteDialog">
      <DeleteConfirm :item="selectedLocation" entity="location" />
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"
import DeleteConfirm from "components/DeleteConfirm.vue"

export default defineComponent({
  name: 'LocationTable',

  components: {
    DeleteConfirm,
  },
    
  computed: {
    ...mapState("locations", ["locations"]),
  },

  data () {
    return {
      selectedLocation: null,
      showDialog: false,
      showDeleteDialog: false,
      columns: [
        {
          name: 'name',
          label: 'Name',
          align: 'left',
          field: 'name'
        },
        {
          name: 'description',
          label: 'Beschreibung',
          align: 'left',
          field: 'description'
        },
        {
          name: 'streetAddress',
          label: 'Straße + Hausnr.',
          align: 'left',
          field: 'streetAddress'
        },
        {
          name: 'postalCode',
          label: 'Postleitzahl',
          align: 'left',
          field: 'postalCode'
        },
        {
          name: 'city',
          label: 'Ort',
          align: 'left',
          field: 'city'
        },
        { name: "actions" },
      ]
    }
  },

  mounted () {
    this.getLocations()
  },

  methods: {
    ...mapActions("locations", ["getLocations"]),

    onRowClick (e, row) {
      if (this.showDeleteDialog) {
        return
      }
      
      this.selectedLocation = row
      this.showDialog = true
    },

    onDeleteClick (row) {
      this.selectedLocation = row
      this.showDeleteDialog = true
    }
  }
})
</script>
