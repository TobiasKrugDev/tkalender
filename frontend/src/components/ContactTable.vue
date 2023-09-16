<template>
  <div>
    <q-table
      flat
      :rows="contacts"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="$q.screen.xs"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      :title="searchMode ? 'Kontakte' : ''"
      :hide-pagination="searchMode ? true : false"
      @row-click="onRowClick"
      @request="onRequest"
    >
      <template #body-cell-image="props">
        <q-td :props="props">
          <div>
            <q-avatar v-if="props.row.image">
              <img :src="props.row.image" />
            </q-avatar>
            <q-avatar
              v-else
              icon="mdi-account"
              color="grey-5"
              text-color="white"
            />
          </div>
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
          entity="contact"
          @item-created="onContactCreate"
        />
        <ItemShow
          v-if="dialogMode === 'show'"
          :item="selectedContact"
          entity="contact"
          @delete-click="onDeleteClick"
          @edit-click="onEditClick"
        />
        <ItemUpdate
          v-if="dialogMode === 'update'"
          :item="selectedContact"
          entity="contact"
          @item-updated="onContactUpdate"
        />
      </DialogCard>
    </q-dialog>

    <!-- <q-dialog v-model="showDialog">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Test Modal</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div>
            <input
              ref="contactImageUpload"
              type="file"
              accept="image/*"
              @change="onFileUpload()"
            />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog> -->

    <q-dialog ref="deleteConfirm" v-model="showDeleteDialog">
      <DeleteConfirm
        :item="selectedContact"
        entity="contact"
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
  name: "ContactTable",

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

  data() {
    return {
      selectedContact: null,
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
          name: "image",
          label: "",
          align: "left",
          field: "image",
        },
        {
          name: "firstname",
          label: "Vorname",
          align: "left",
          field: "firstname",
        },
        {
          name: "lastname",
          label: "Nachname",
          align: "left",
          field: "lastname",
        },
        {
          name: "description",
          label: "Beschreibung",
          align: "left",
          field: "description",
        },
        {
          name: "phoneNumber",
          label: "Telefonnr.",
          align: "left",
          field: "phoneNumber",
        },
        {
          name: "emailAddress",
          label: "E-Mail-Adresse",
          align: "left",
          field: "emailAddress",
        },
        { name: "actions" },
      ],
    }
  },

  computed: {
    ...mapState("contacts", [
      "contacts",
      "totalItems",
      "createdContact",
      "updatedContact",
    ]),

    dialogCardTitle() {
      if (this.dialogMode === "show") {
        return `${this.selectedContact.firstname} ${this.selectedContact.lastname}`
      } else if (this.dialogMode === "update") {
        return "Kontakt bearbeiten"
      } else {
        return "Neuer Kontakt"
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
    this.getContactData()
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),

    onRowClick(e, row) {
      if (this.itemDialog || this.showDeleteDialog) {
        return
      }

      this.selectedContact = row
      this.dialogMode = "show"
      this.itemDialog = true
    },

    onDeleteClick(row) {
      this.selectedContact = row
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
      this.selectedContact = row
      this.dialogMode = "update"
      this.itemDialog = true
    },

    async getContactData() {
      this.loading = true
      await this.getContacts({
        ...this.pagination,
        filter: this.filter,
      })
      this.pagination.rowsNumber = this.totalItems
      this.loading = false
    },

    onRequest(props) {
      this.pagination = props.pagination
      this.filter = props.filter
      this.getContactData()
    },

    onPageChange(page) {
      this.pagination.page = page
      this.getContactData()
    },

    onItemDeleted() {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      this.getContactData()
    },

    onContactCreate() {
      this.selectedContact = this.createdContact
      this.dialogMode = "show"

      // Refresh category list
      this.getContactData()
    },

    onContactUpdate() {
      this.selectedContact = this.updatedContact
      this.dialogMode = "show"

      // Refresh category list
      this.getContactData()
    },

    onFileUpload() {
      var file = this.$refs.contactImageUpload.files[0]
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onloadend = function () {
        console.log("RESULT", reader.result)
      }
    },
  },
})
</script>
