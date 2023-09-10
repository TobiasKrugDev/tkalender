<template>
  <div>
    <q-table
      flat
      :rows="categories"
      :columns="columns"
      @row-click="onRowClick"
    >
      <template v-slot:body-cell-color="props">
        <q-td :props="props">
          <div class="category-table-color-circle" :style="'background-color: ' + props.value">
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
            ToDo: Show Ansicht
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showDeleteDialog">
      <DeleteConfirm :item="selectedCategory" entity="category" />
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"
import DeleteConfirm from "components/DeleteConfirm.vue"

export default defineComponent({
  name: 'CategoryTable',

  components: {
    DeleteConfirm,
  },
  
  computed: {
    ...mapState("categories", ["categories"]),
  },

  data () {
    return {
      selectedCategory: null,
      showDialog: false,
      showDeleteDialog: false,
      columns: [
        {
          name: 'color',
          label: '',
          align: 'left',
          field: 'color'
        },
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
        { name: "actions" },
      ]
    }
  },

  mounted () {
    this.getCategories()
  },

  methods: {
    ...mapActions("categories", ["getCategories"]),

    onRowClick (e, row) {
      if (this.showDeleteDialog) {
        return
      }
      
      this.selectedCategory = row
      this.showDialog = true
    },

    onDeleteClick (row) {
      this.selectedCategory = row
      this.showDeleteDialog = true
    }
  }
})
</script>

<style lang="scss">
  .category-table-color-circle {
    height: 48px;
    width: 48px;
    border-radius: 50%;
  }
</style>
