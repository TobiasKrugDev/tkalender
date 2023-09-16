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
      :filter="filter"
      :title="searchMode ? 'Kategorien' : ''"
      :hide-pagination="searchMode ? true : false"
      @row-click="onRowClick"
      @request="onRequest"
    >
      <template #body-cell-color="props">
        <q-td :props="props">
          <div
            class="category-table-color-circle"
            :style="'background-color: ' + props.value"
          />
        </q-td>
      </template>
      <template #body-cell-actions="props">
        <td class="text-right">
          <q-btn
            flat
            round
            color="grey-7"
            icon="mdi-pencil"
            @click="openUpdateDialog(props.row)"
          />
          <q-btn
            flat
            round
            color="grey-7"
            icon="mdi-delete"
            @click="onDeleteClick(props.row)"
          />
        </td>
      </template>
      <template #pagination>
        <!-- Hide default pagination -->
      </template>
      <template v-if="!searchMode" #top-right>
        <q-input
          outlined
          dense
          debounce="300"
          v-model="filter"
          placeholder="Suche"
        >
          <template #append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template #item="props">
        <div class="col-10 q-pl-md">
          <div class="text-h6 q-mb-md mobile-item-title-circle">
            <div
              class="category-table-color-circle"
              :style="'background-color: ' + props.row.color"
            />
            <div class="q-ml-sm">{{ props.row.name }}</div>
          </div>
          <div class="q-table__grid-item-title">Beschreibung</div>
          <div class="q-mb-md">
            <span v-if="props.row.description">{{
              props.row.description
            }}</span>
            <span v-else>-</span>
          </div>
        </div>
        <div class="col-2 text-right">
          <q-btn flat round dense color="grey-7" icon="more_vert">
            <q-menu transition-show="flip-up" transition-hide="flip-down">
              <q-list style="min-width: 100px">
                <q-item clickable @click="onRowClick(null, props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-eye-outline" />
                  </q-item-section>
                  <q-item-section> Ansehen </q-item-section>
                </q-item>
                <q-item clickable @click="openUpdateDialog(props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-pencil" />
                  </q-item-section>
                  <q-item-section avatar> Bearbeiten </q-item-section>
                </q-item>
                <q-item clickable @click="onDeleteClick(props.row)">
                  <q-item-section side>
                    <q-icon name="mdi-delete" />
                  </q-item-section>
                  <q-item-section avatar> Löschen </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
        <div class="col-12">
          <q-separator class="q-my-md" />
        </div>
      </template>
    </q-table>

    <!-- Pagination -->
    <div v-if="showPagination" class="flex flex-center q-mb-lg">
      <CustomPagination
        :totalItems="pagination.rowsNumber"
        :itemsPerPage="pagination.rowsPerPage"
        class="q-mx-auto"
        @page-change="onPageChange"
      />
    </div>

    <q-dialog ref="itemDialog" v-model="itemDialog" persistent>
      <DialogCard :title="dialogCardTitle">
        <ItemCreate
          v-if="dialogMode === 'create'"
          entity="category"
          @item-created="onCategoryCreate"
        />
        <ItemShow
          v-if="dialogMode === 'show'"
          :item="selectedCategory"
          entity="category"
          @delete-click="onDeleteClick"
          @edit-click="onEditClick"
        />
        <ItemUpdate
          v-if="dialogMode === 'update'"
          :item="selectedCategory"
          entity="category"
          @item-updated="onCategoryUpdate"
        />
      </DialogCard>
    </q-dialog>

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm
        :item="selectedCategory"
        entity="category"
        @item-deleted="onItemDeleted"
      />
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState } from "vuex"
import DeleteConfirm from "components/DeleteConfirm.vue"
import DialogCard from "components/DialogCard.vue"
import ItemCreate from "components/ItemCreate.vue"
import ItemShow from "components/ItemShow.vue"
import ItemUpdate from "components/ItemUpdate.vue"
import CustomPagination from "src/components/CustomPagination.vue"

export default defineComponent({
  name: "CategoryTable",

  components: {
    DeleteConfirm,
    DialogCard,
    ItemCreate,
    ItemShow,
    ItemUpdate,
    CustomPagination,
  },

  props: {
    searchMode: {
      type: Boolean,
      deafult: false,
    },
  },

  emits: ["searchResultsFetched"],

  data() {
    return {
      selectedCategory: null,
      itemDialog: false,
      showDeleteDialog: false,
      dialogMode: "",
      loading: false,
      pagination: {
        // sortBy: 'name',
        // descending: false,
        page: 1,
        rowsPerPage: this.searchMode ? 5 : 10,
        rowsNumber: this.totalItems,
      },
      filter: this.searchMode ? this.$route.params.query : "",
      columns: [
        {
          name: "color",
          label: "",
          align: "left",
          field: "color",
        },
        {
          name: "name",
          label: "Name",
          align: "left",
          field: "name",
          sortable: true,
        },
        {
          name: "description",
          label: "Beschreibung",
          align: "left",
          field: "description",
        },
        { name: "actions" },
      ],
    }
  },

  computed: {
    ...mapState("categories", [
      "categories",
      "totalItems",
      "createdCategory",
      "updatedCategory",
    ]),
    dialogCardTitle() {
      if (this.dialogMode === "show") {
        return this.selectedCategory.name
      } else if (this.dialogMode === "update") {
        return "Kategorie bearbeiten"
      } else {
        return "Neue Kategorie"
      }
    },
    showPagination() {
      if (!this.searchMode) return true

      if (this.pagination.rowsNumber <= this.pagination.rowsPerPage) {
        return false
      } else {
        return true
      }
    },
  },

  async mounted() {
    await this.getCategoryData()
    if (this.searchMode) {
      this.$emit("searchResultsFetched", this.totalItems)
    }
  },

  methods: {
    ...mapActions("categories", ["getCategories"]),

    onRowClick(e, row) {
      if (this.itemDialog || this.showDeleteDialog) {
        return
      }

      this.selectedCategory = row
      this.dialogMode = "show"
      this.itemDialog = true
    },

    onDeleteClick(row) {
      this.selectedCategory = row
      this.showDeleteDialog = true
    },

    onEditClick(row) {
      this.openUpdateDialog(row)
    },

    openCreateDialog() {
      this.dialogMode = "create"
      this.itemDialog = true
    },

    openUpdateDialog(row) {
      this.selectedCategory = row
      this.dialogMode = "update"
      this.itemDialog = true
    },

    async getCategoryData() {
      this.loading = true
      await this.getCategories({
        ...this.pagination,
        filter: this.filter,
      })
      this.pagination.rowsNumber = this.totalItems
      this.loading = false
    },

    onRequest(props) {
      this.pagination = props.pagination
      this.filter = props.filter
      this.getCategoryData()
    },

    onPageChange(page) {
      this.pagination.page = page
      this.getCategoryData()
    },

    onItemDeleted() {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      this.getCategoryData()
    },

    onCategoryCreate() {
      this.selectedCategory = this.createdCategory
      this.dialogMode = "show"

      // Refresh category list
      this.getCategoryData()
    },

    onCategoryUpdate() {
      this.selectedCategory = this.updatedCategory
      this.dialogMode = "show"

      // Refresh category list
      this.getCategoryData()
    },
  },
})
</script>

<style lang="scss">
.category-table-color-circle {
  height: 48px;
  width: 48px;
  border-radius: 50%;
}

.q-table__title {
  margin-bottom: 20px;
}

.mobile-item-title-circle {
  display: flex;
  line-height: 3rem;
}
</style>
