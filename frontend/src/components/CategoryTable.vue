<template>
  <div>
    <q-table
      flat
      :rows="categories"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="$q.screen.xs"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      @row-click="onRowClick"
      @request="onRequest"
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
      <template #pagination>
          <!-- Hide default pagination -->
      </template>
      <template #item="props">
        <div class="col-12">
          <q-separator class="q-my-md" />
        </div>
        <div class="col-10">
          <div v-if="props.row.name" class="text-h6 q-mb-md">
            {{ props.row.name }}
          </div>
          <div class="text-weight-medium">
            Beschreibung:
          </div>
          <div class="q-mb-md">
            {{ props.row.description }}
          </div>
          <div class="text-weight-medium q-mb-xs">
            Farbe:
          </div>
          <div>
            <div class="category-table-color-circle" :style="'background-color: ' + props.row.color" />
          </div>
        </div>
        <div class="col-2 text-right">
          <q-btn
            flat
            round
            dense
            color="grey-7"
            icon="more_vert"
          >
            <q-menu
              transition-show="flip-up"
              transition-hide="flip-down"
            >
              <q-list style="min-width: 100px">
                <q-item clickable @click="onRowClick(null, props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-eye-outline" />
                  </q-item-section>
                  <q-item-section>
                    Ansehen
                  </q-item-section>
                </q-item>
                <q-item clickable @click="openUpdateDialog(props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-pencil" />
                  </q-item-section>
                  <q-item-section avatar>
                    Bearbeiten
                  </q-item-section>
                </q-item>
                <q-item clickable @click="onDeleteClick(props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-delete" />
                  </q-item-section>
                  <q-item-section avatar>
                    Löschen
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </template>
    </q-table>

    <!-- Pagination -->
    <div class="flex flex-center q-mb-lg">
      <CustomPagination 
        :totalItems="pagination.rowsNumber" 
        :itemsPerPage="pagination.rowsPerPage" 
        class="q-mx-auto" 
        @page-change="onPageChange" />
    </div>

    <q-dialog ref="itemDialog" v-model="itemDialog" persistent>
      <DialogCard :title="dialogCardTitle">
        <CategoryCreate v-if="dialogMode === 'create'" @category-created="onCategoryCreate" />
        <CategoryShow v-if="dialogMode === 'show'" :category="selectedCategory" @delete-click="onDeleteClick" @edit-click="onEditClick" />
        <CategoryUpdate v-if="dialogMode === 'update'" :category="selectedCategory" />
      </DialogCard>
    </q-dialog>

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm :item="selectedCategory" entity="category" @item-deleted="onItemDeleted" />
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
import CustomPagination from "src/components/CustomPagination.vue"

export default defineComponent({
  name: 'CategoryTable',

  components: {
    DeleteConfirm,
    DialogCard,
    CategoryCreate,
    CategoryShow,
    CategoryUpdate,
    CustomPagination,
  },

  data () {
    return {
      selectedCategory: null,
      itemDialog: false,
      showDeleteDialog: false,
      dialogMode: "",
      loading: false,
      pagination: {
        // sortBy: 'desc',
        // descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: this.totalItems,
      },
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
    ...mapState("categories", ["categories", "totalItems", "createdCategory"]),
    dialogCardTitle () {
      if (this.dialogMode === "show") {
        return this.selectedCategory.name
      } else if (this.dialogMode === "update") {
        return "Kategorie bearbeiten"
      } else {
        return "Neue Kategorie"
      }
    },
  },

  mounted () {
    this.getCategoryData()
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

    onEditClick (row) {
      this.openUpdateDialog(row)
    },

    openCreateDialog () {
      this.dialogMode = "create"
      this.itemDialog = true
    },

    openUpdateDialog (row) {
      this.selectedCategory = row
      this.dialogMode = "update"
      this.itemDialog = true
    },

    async getCategoryData () {
      this.loading = true
      await this.getCategories({ itemsPerPage: this.pagination.rowsPerPage, page: this.pagination.page })
      this.pagination.rowsNumber = this.totalItems
      this.loading = false
    },

    onRequest (props) {
      this.pagination = props.pagination
      this.getCategoryData()
    },

    onPageChange (page) {
      this.pagination.page = page
      this.getCategoryData()
    },

    onItemDeleted () {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      this.getCategoryData()
    },

    onCategoryCreate () {
      this.selectedCategory = this.createdCategory
      this.dialogMode = "show"

      // Refresh category list
      this.getCategoryData()
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
