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
      :title="searchMode ? 'Orte' : ''"
      :hide-pagination="searchMode ? true : false"
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
          <div class="text-h6 q-mb-md">
            <div>{{ props.row.name }}</div>
          </div>
          <div class="q-table__grid-item-title">Beschreibung</div>
          <div class="q-mb-md">
            <span v-if="props.row.description">{{
              props.row.description
            }}</span>
            <span v-else>-</span>
          </div>
          <div class="q-table__grid-item-title">Adresse</div>
          <div>
            <div
              v-if="
                props.row.streetAddress ||
                props.row.postalCode ||
                props.row.city
              "
            >
              <div>{{ props.row.streetAddress }}</div>
              <div>{{ props.row.postalCode }}</div>
              <div>{{ props.row.city }}</div>
            </div>
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
          entity="location"
          @item-created="onLocationCreate"
        />
        <ItemShow
          v-if="dialogMode === 'show'"
          :item="selectedLocation"
          entity="location"
          @delete-click="onDeleteClick"
          @edit-click="onEditClick"
        />
        <ItemUpdate
          v-if="dialogMode === 'update'"
          :item="selectedLocation"
          entity="location"
          @item-updated="onLocationUpdate"
        />
      </DialogCard>
    </q-dialog>

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm
        :item="selectedLocation"
        entity="location"
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
import ItemCreate from "src/components/ItemCreate.vue"
import ItemShow from "src/components/ItemShow.vue"
import ItemUpdate from "src/components/ItemUpdate.vue"
import CustomPagination from "src/components/CustomPagination.vue"

export default defineComponent({
  name: "LocationTable",

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
        rowsPerPage: this.searchMode ? 5 : 10,
        rowsNumber: this.totalItems,
      },
      filter: this.searchMode ? this.$route.params.query : "",
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
    showPagination() {
      if (!this.searchMode) return true

      if (this.pagination.rowsNumber <= this.pagination.rowsPerPage) {
        return false
      } else {
        return true
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
