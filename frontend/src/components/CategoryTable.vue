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
          <div class="category-table-color-circle" :style="'background-color: ' + props.value" />
        </q-td>
      </template>
      <template #body-cell-actions="props">
        <td class="text-right">
          <q-btn flat round color="red-6" icon="mdi-delete" @click="onDeleteClick(props.row)" />
        </td>
      </template>
    </q-table>

    <q-dialog v-model="itemDialog" persistent>
      <DialogCard :title="selectedCategory.name">
        <CategoryShow :category="selectedCategory" />
      </DialogCard>
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
import DialogCard from "components/DialogCard.vue"
import CategoryShow from "components/category/CategoryShow.vue"

export default defineComponent({
  name: 'CategoryTable',

  components: {
    DeleteConfirm,
    DialogCard,
    CategoryShow,
  },
  
  computed: {
    ...mapState("categories", ["categories"]),
  },

  data () {
    return {
      selectedCategory: null,
      itemDialog: false,
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
      this.itemDialog = true
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
