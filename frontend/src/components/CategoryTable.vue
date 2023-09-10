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
          <q-btn flat round color="grey-7" icon="mdi-pencil" @click="openUpdateDialog(props.row)" />
          <q-btn flat round color="grey-7" icon="mdi-delete" @click="onDeleteClick(props.row)" />
        </td>
      </template>
    </q-table>

    <q-dialog v-model="itemDialog" persistent>
      <DialogCard :title="dialogCardTitle">
        <CategoryCreate v-if="dialogMode === 'create'" />
        <CategoryShow v-if="dialogMode === 'show'" :category="selectedCategory"  />
        <CategoryUpdate v-if="dialogMode === 'update'" />
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
import CategoryCreate from "components/category/CategoryCreate.vue"
import CategoryShow from "components/category/CategoryShow.vue"
import CategoryUpdate from "components/category/CategoryUpdate.vue"

export default defineComponent({
  name: 'CategoryTable',

  components: {
    DeleteConfirm,
    DialogCard,
    CategoryCreate,
    CategoryShow,
    CategoryUpdate,
  },

  data () {
    return {
      selectedCategory: null,
      itemDialog: false,
      showDeleteDialog: false,
      dialogMode: "",
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

  computed: {
    ...mapState("categories", ["categories"]),
    dialogCardTitle () {
      if (this.dialogMode === "show" || this.dialogMode === "update") {
        return this.selectedCategory.name
      } else {
        return "Neue Kategorie"
      }
    },
  },

  mounted () {
    this.getCategories()
  },

  methods: {
    ...mapActions("categories", ["getCategories"]),

    onRowClick (e, row) {
      if (this.itemDialog || this.showDeleteDialog) {
        return
      }
      
      this.selectedCategory = row
      this.dialogMode = "show"
      this.itemDialog = true
    },

    onDeleteClick (row) {
      this.selectedCategory = row
      this.showDeleteDialog = true
    },

    openCreateDialog () {
      this.dialogMode = "create"
      this.itemDialog = true
    },

    openUpdateDialog (row) {
      this.selectedCategory = row
      this.dialogMode = "update"
      this.itemDialog = true
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
