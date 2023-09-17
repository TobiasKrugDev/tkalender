<template>
  <div>
    <q-table
      flat
      :rows="appointments"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="$q.screen.xs"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      :title="searchMode ? 'Termine' : ''"
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
      <!-- ToDo: Item Slot -->
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
          entity="appointment"
          @item-created="onAppointmentCreate"
        />
        <ItemShow
          v-if="dialogMode === 'show'"
          :item="selectedAppointment"
          entity="appointment"
          @delete-click="onDeleteClick"
          @edit-click="onEditClick"
        />
        <ItemUpdate
          v-if="dialogMode === 'update'"
          :item="selectedAppointment"
          entity="appointment"
          @item-updated="onAppointmentUpdate"
        />
      </DialogCard>
    </q-dialog>

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm
        :item="selectedAppointment"
        entity="appointment"
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
  name: "AppointmentTable",

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
      selectedAppointment: null,
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
          name: "startAt",
          label: "Start",
          align: "left",
          field: "startAt",
        },
        {
          name: "endAt",
          label: "Ende",
          align: "left",
          field: "endAt",
        },
        {
          name: "location",
          label: "Ort",
          align: "left",
          field: "location",
        },
        {
          name: "category",
          label: "Kategorie",
          align: "left",
          field: "category",
        },
        {
          name: "icon",
          label: "Icon",
          align: "left",
          field: "icon",
        },
        { name: "actions" },
      ],
    }
  },

  computed: {
    ...mapState("appointments", [
      "appointments",
      "totalItems",
      "createdAppontment",
      "updatedAppointment",
    ]),

    dialogCardTitle() {
      if (this.dialogMode === "show") {
        return this.selectedAppointment.name
      } else if (this.dialogMode === "update") {
        return "Termin bearbeiten"
      } else {
        return "Neuer Termin"
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
    await this.getAppointmentData()
    if (this.searchMode) {
      this.$emit("searchResultsFetched", this.totalItems)
    }
  },

  methods: {
    ...mapActions("appointments", ["getAppointments"]),

    onRowClick(e, row) {
      if (this.itemDialog || this.showDeleteDialog) {
        return
      }

      this.selectedAppointment = row
      this.dialogMode = "show"
      this.itemDialog = true
    },

    onDeleteClick(row) {
      this.selectedAppointment = row
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
      this.selectedAppointment = row
      this.dialogMode = "update"
      this.itemDialog = true
    },

    async getAppointmentData() {
      this.loading = true
      await this.getAppointments({
        ...this.pagination,
        filter: this.filter,
      })
      this.pagination.rowsNumber = this.totalItems
      this.loading = false
    },

    onRequest(props) {
      this.pagination = props.pagination
      this.filter = props.filter
      this.getAppointmentData()
    },

    onPageChange(page) {
      this.pagination.page = page
      this.getAppointmentData()
    },

    onItemDeleted() {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      this.getAppointmentData()
    },

    onAppointmentCreate() {
      this.selectedAppointment = this.createdAppontment
      this.dialogMode = "show"

      // Refresh category list
      this.getAppointmentData()
    },

    onAppointmentUpdate() {
      this.selectedContact = this.updatedContact
      this.dialogMode = "show"

      // Refresh category list
      this.getAppointmentData()
    },
  },
})
</script>
