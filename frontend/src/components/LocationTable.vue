<template>
  <div>
    <q-table
      flat
      :rows="locations"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="$q.screen.xs"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      @row-click="onRowClick"
      @request="onRequest"
    >
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
      <template #top-right>
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
      <!-- ToDo Props Slot -->
    </q-table>

    <!-- Pagination -->
    <div class="flex flex-center q-mb-lg">
      <CustomPagination
        :totalItems="pagination.rowsNumber"
        :itemsPerPage="pagination.rowsPerPage"
        class="q-mx-auto"
        @page-change="onPageChange"
      />
    </div>

    <q-dialog ref="itemDialog" v-model="itemDialog" persistent>
      <DialogCard :title="dialogCardTitle">
        <!-- <CategoryCreate
          v-if="dialogMode === 'create'"
          @category-created="onCategoryCreate"
        /> -->
        <ItemShow
          v-if="dialogMode === 'show'"
          :item="selectedLocation"
          entity="location"
          @delete-click="onDeleteClick"
          @edit-click="onEditClick"
        />
        <!-- <CategoryUpdate
          v-if="dialogMode === 'update'"
          :category="selectedCategory"
          @category-updated="onCategoryUpdate"
        /> -->
      </DialogCard>
    </q-dialog>

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm :item="selectedLocation" entity="location" />
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState } from "vuex"
import DeleteConfirm from "components/DeleteConfirm.vue"
import DialogCard from "components/DialogCard.vue"
import ItemShow from "src/components/ItemShow.vue"
import CustomPagination from "src/components/CustomPagination.vue"

export default defineComponent({
  name: "LocationTable",

  components: {
    DeleteConfirm,
    DialogCard,
    ItemShow,
    CustomPagination,
  },

  computed: {
    ...mapState("locations", ["locations"]),
  },

  data() {
    return {
      selectedLocation: null,
      itemDialog: false,
      showDeleteDialog: false,
      dialogMode: "",
      loading: false,
      pagination: {
        // sortBy: 'name',
        // descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: this.totalItems,
      },
      filter: "",
      columns: [
        {
          name: "name",
          label: "Name",
          align: "left",
          field: "name",
        },
        {
          name: "description",
          label: "Beschreibung",
          align: "left",
          field: "description",
        },
        {
          name: "streetAddress",
          label: "Straße + Hausnr.",
          align: "left",
          field: "streetAddress",
        },
        {
          name: "postalCode",
          label: "Postleitzahl",
          align: "left",
          field: "postalCode",
        },
        {
          name: "city",
          label: "Ort",
          align: "left",
          field: "city",
        },
        { name: "actions" },
      ],
    }
  },

  computed: {
    ...mapState("locations", [
      "locations",
      "totalItems",
      "createdLocation",
      "updatedLocation",
    ]),
    dialogCardTitle() {
      if (this.dialogMode === "show") {
        return this.selectedLocation.name
      } else if (this.dialogMode === "update") {
        return "Ort bearbeiten"
      } else {
        return "Neuer Ort"
      }
    },
  },

  mounted() {
    this.getLocationData()
  },

  methods: {
    ...mapActions("locations", ["getLocations"]),

    onRowClick(e, row) {
      if (this.itemDialog || this.showDeleteDialog) {
        return
      }

      this.selectedLocation = row
      this.dialogMode = "show"
      this.itemDialog = true
    },

    onDeleteClick(row) {
      this.selectedLocation = row
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
      this.selectedLocation = row
      this.dialogMode = "update"
      this.itemDialog = true
    },

    async getLocationData() {
      this.loading = true
      await this.getLocations({
        ...this.pagination,
        filter: this.filter,
      })
      this.pagination.rowsNumber = this.totalItems
      this.loading = false
    },

    onRequest(props) {
      this.pagination = props.pagination
      this.filter = props.filter
      this.getLocationData()
    },

    onPageChange(page) {
      this.pagination.page = page
      this.getLocationData()
    },

    onItemDeleted() {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      this.getLocationData()
    },

    onLocationCreate() {
      this.selectedLocation = this.createdLocation
      this.dialogMode = "show"

      // Refresh category list
      this.getLocationData()
    },

    onLocationUpdate() {
      this.selectedLocation = this.updatedLocation
      this.dialogMode = "show"

      // Refresh category list
      this.getLocationData()
    },
  },
})
</script>
