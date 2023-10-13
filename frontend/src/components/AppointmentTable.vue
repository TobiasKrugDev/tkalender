<template>
  <div>
    <q-table
      flat
      :rows="appointments"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="isGridActivated"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      :title="searchMode ? 'Termine' : ''"
      :hide-pagination="searchMode || dashboardMode ? true : false"
      @row-click="onRowClick"
      @request="onRequest"
    >
      <template #body-cell-startAt="props">
        <td class="text-left">
          <AppointmentTableDateDisplay :appointment-date="props.row.startAt" />
        </td>
      </template>
      <template #body-cell-name="props">
        <td class="text-left">
          <span style="font-size: 17px">{{ props.row.name }}</span>
        </td>
      </template>
      <template #body-cell-location="props">
        <td class="text-left">
          <span v-if="props.row.location">{{ props.row.location.name }}</span>
        </td>
      </template>
      <template #body-cell-contacts="props">
        <td class="text-left">
          <ContactChip
            v-for="contact in props.row.contacts"
            :key="contact.id"
            :contact="contact"
          />
        </td>
      </template>
      <template #body-cell-category="props">
        <td class="text-left">
          <span v-if="props.row.category">
            <CategoryChip :category="props.row.category"
          /></span>
        </td>
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
      <template v-if="!searchMode && !dashboardMode" #top-left>
        <q-toggle
          v-model="timespanToggle"
          color="primary"
          label="Nur anstehende Termine"
          left-label
          checked-icon="check"
          unchecked-icon="clear"
          keep-color
          class="q-mb-lg"
        />
      </template>
      <template v-if="!searchMode && !dashboardMode" #top-right>
        <q-input
          outlined
          dense
          debounce="300"
          v-model="filter"
          placeholder="Suche"
          class="q-mb-lg"
        >
          <template #append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template #item="props">
        <div class="col-12">
          <div class="row">
            <div class="col-6">
              <div class="text-grey-7 text-weight-medium">
                {{ formatMobileStartAt(props.row.startAt) }}
              </div>
              <div class="text-h6">
                {{ props.row.name }}
              </div>
              <div class="text-grey-7 q-mb-xs">
                <span v-if="props.row.location">{{
                  props.row.location.name
                }}</span>
              </div>
            </div>
            <div class="col-5 text-right">
              <span v-if="props.row.category">
                <CategoryChip :category="props.row.category" />
              </span>
            </div>
            <div class="col-1">
              <!-- Desktop button -->
              <q-btn
                v-if="$q.screen.gt.sm"
                flat
                round
                dense
                color="grey-7"
                icon="more_vert"
              >
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

              <!-- Mobile button -->
              <q-btn
                v-else
                flat
                round
                dense
                color="grey-7"
                icon="more_vert"
                @click="showBottomSheet(props.row)"
              />
            </div>
            <div class="col-12">
              <div>
                <ContactChip
                  v-for="contact in props.row.contacts"
                  :key="contact.id"
                  :contact="contact"
                />
              </div>
            </div>
          </div>
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
import { date } from "quasar"
import DeleteConfirm from "components/DeleteConfirm.vue"
import DialogCard from "components/DialogCard.vue"
import ItemCreate from "src/components/ItemCreate.vue"
import ItemShow from "src/components/ItemShow.vue"
import ItemUpdate from "src/components/ItemUpdate.vue"
import CustomPagination from "src/components/CustomPagination.vue"
import AppointmentTableDateDisplay from "./AppointmentTableDateDisplay.vue"
import ContactChip from "./ContactChip.vue"
import CategoryChip from "./CategoryChip.vue"

export default defineComponent({
  name: "AppointmentTable",

  components: {
    DeleteConfirm,
    DialogCard,
    ItemCreate,
    ItemShow,
    ItemUpdate,
    CustomPagination,
    AppointmentTableDateDisplay,
    ContactChip,
    CategoryChip,
  },

  props: {
    searchMode: {
      type: Boolean,
      deafult: false,
    },

    dashboardMode: {
      type: Boolean,
      deafult: false,
    },
  },

  emits: ["searchResultsFetched", "appointmentChange"],

  data() {
    return {
      selectedAppointment: null,
      itemDialog: false,
      showDeleteDialog: false,
      dialogMode: "",
      loading: false,
      timespanToggle: true,
      bottomSheetItem: null,
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
          name: "startAt",
          label: "Datum",
          align: "center",
          field: "startAt",
        },
        {
          name: "name",
          label: "Name",
          align: "left",
          field: "name",
        },
        {
          name: "location",
          label: "Ort",
          align: "left",
        },
        {
          name: "contacts",
          label: "Kontakte",
          align: "left",
        },
        {
          name: "category",
          label: "Kategorie",
          align: "left",
        },
        { name: "actions" },
      ],
    }
  },

  computed: {
    ...mapState("appointments", [
      "appointments",
      "totalItems",
      "createdAppointment",
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

    isGridActivated() {
      if (this.dashboardMode) {
        return true
      } else {
        return this.$q.screen.lt.lg
      }
    },
  },

  watch: {
    timespanToggle() {
      this.pagination.page = 1
      this.getAppointmentData()
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
        futureAppointmentsOnly: this.timespanToggle,
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

      this.$emit("appointmentChange")
    },

    onAppointmentCreate() {
      this.selectedAppointment = this.createdAppointment
      this.dialogMode = "show"

      // Refresh category list
      this.getAppointmentData()

      this.$emit("appointmentChange")
    },

    onAppointmentUpdate() {
      this.selectedAppointment = this.updatedAppointment
      this.dialogMode = "show"

      // Refresh category list
      this.getAppointmentData()

      this.$emit("appointmentChange")
    },

    formatMobileStartAt(startAt) {
      const jsStartAt = new Date(startAt)
      return date.formatDate(jsStartAt, "DD. MMMM YYYY - HH:mm") + " Uhr"
    },

    showBottomSheet(item) {
      this.bottomSheetItem = item
      this.$q
        .bottomSheet({
          grid: false,
          actions: [
            {
              label: "Ansehen",
              icon: "mdi-eye-outline",
              id: "show",
            },
            {
              label: "Bearbeiten",
              icon: "mdi-pencil",
              id: "update",
            },
            {
              label: "Löschen",
              icon: "mdi-delete",
              id: "delete",
            },
          ],
        })
        .onOk((action) => {
          if (action.id === "show") {
            this.onRowClick(null, this.bottomSheetItem)
          } else if (action.id === "update") {
            this.openUpdateDialog(this.bottomSheetItem)
          } else if (action.id === "delete") {
            this.onDeleteClick(this.bottomSheetItem)
          }
        })
    },
  },
})
</script>

<style lang="scss" scope>
.q-toggle__label {
  color: #546e7a;
}
</style>
