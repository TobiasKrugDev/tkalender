<template>
  <div>
    <q-table
      flat
      :rows="contacts"
      :columns="columns"
      rows-per-page-label="Einträge pro Seite"
      :grid="$q.screen.lt.lg"
      :rows-per-page-options="[10, 25, 50, 100]"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      :title="searchMode ? 'Kontakte' : ''"
      :hide-pagination="searchMode ? true : false"
      @row-click="onRowClick"
      @request="onRequest"
    >
      <template #body-cell-description="props">
        <q-td :props="props">
          <div class="contact-table-description ellipsis">
            {{ props.row.description }}
          </div>
        </q-td>
      </template>
      <template #body-cell-image="props">
        <q-td :props="props">
          <div>
            <q-avatar v-if="props.row.image" class="shadow-3">
              <img :src="props.row.image" />
            </q-avatar>
            <q-avatar
              v-else
              icon="mdi-account"
              color="grey-5"
              text-color="white"
              class="shadow-3"
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
          class="q-mb-lg"
        >
          <template #append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template #item="props">
        <div class="col-10 q-pl-md">
          <div class="text-h6 q-mb-md mobile-item-title-circle">
            <q-avatar v-if="props.row.image">
              <img :src="props.row.image" />
            </q-avatar>
            <q-avatar
              v-else
              icon="mdi-account"
              color="grey-5"
              text-color="white"
              class="shadow-3"
            />
            <div class="q-ml-sm">
              {{ props.row.firstname }} {{ props.row.lastname }}
            </div>
          </div>
          <div class="q-table__grid-item-title">Beschreibung</div>
          <div class="q-mb-md">
            <span v-if="props.row.description">{{
              props.row.description
            }}</span>
            <span v-else>-</span>
          </div>
          <div class="q-table__grid-item-title">Telefonnr.</div>
          <div>
            <div v-if="props.row.phoneNumber">
              <div>{{ props.row.phoneNumber }}</div>
            </div>
            <span v-else>-</span>
          </div>
          <div class="q-table__grid-item-title">E-Mail-Adresse</div>
          <div>
            <div v-if="props.row.emailAdress">
              <div>{{ props.row.emailAdress }}</div>
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
      <DialogCard
        :title="dialogCardTitle"
        :profile-image="dialogCardProfileImage"
        :show-placeholder-image="showDialogCardPlaceholderImage"
      >
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

  emits: ["searchResultsFetched"],

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
          name: "lastname",
          label: "Nachname",
          align: "left",
          field: "lastname",
        },
        {
          name: "firstname",
          label: "Vorname",
          align: "left",
          field: "firstname",
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

    dialogCardProfileImage() {
      if (this.dialogMode === "show") {
        if (this.selectedContact.image) {
          return this.selectedContact.image
        }
      }

      return ""
    },

    showDialogCardPlaceholderImage() {
      if (this.dialogMode === "show") {
        if (!this.selectedContact.image) {
          return true
        }
      }

      return false
    },
  },

  async mounted() {
    await this.getContactData()
    if (this.searchMode) {
      this.$emit("searchResultsFetched", this.totalItems)
    }
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
  },
})
</script>

<style lang="scss">
.contact-table-description {
  max-width: 250px;
}
</style>
