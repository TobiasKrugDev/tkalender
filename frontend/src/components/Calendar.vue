<template>
  <div>
    <vue-cal
      active-view="week"
      style="height: 75vh; width: 100%"
      :disable-views="['years', 'year']"
      locale="de"
      :events="events"
      events-on-month-view="short"
      :on-event-click="onEventClick"
      today-button
    >
      <template #today-button>
        <q-btn flat color="white" icon="gps_fixed">
          <q-tooltip> Heute </q-tooltip>
        </q-btn>
      </template>
    </vue-cal>

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
          @edit-click="openUpdateDialog"
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
import { colors } from "quasar"
import VueCal from "vue-cal"
import "vue-cal/dist/vuecal.css"
import DialogCard from "components/DialogCard.vue"
import ItemCreate from "src/components/ItemCreate.vue"
import ItemShow from "src/components/ItemShow.vue"
import ItemUpdate from "src/components/ItemUpdate.vue"
import DeleteConfirm from "src/components/DeleteConfirm.vue"

export default defineComponent({
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Calendar",

  components: {
    VueCal,
    DialogCard,
    ItemCreate,
    ItemShow,
    ItemUpdate,
    DeleteConfirm,
  },

  computed: {
    ...mapState("appointments", [
      "appointments",
      "createdAppointment",
      "updatedAppointment",
    ]),
    ...mapState("categories", ["categories"]),

    events() {
      const result = []
      for (let appointment of this.appointments) {
        const event = {
          id: appointment.id,
          start: appointment.startAt,
          end: appointment.endAt,
          title: appointment.name,
          class: appointment.category
            ? `calendar-appointment-category-${appointment.category.id}`
            : "no-category",
        }

        result.push(event)
      }

      return result
    },

    dialogCardTitle() {
      if (this.dialogMode === "show") {
        return this.selectedAppointment.name
      } else if (this.dialogMode === "update") {
        return "Termin bearbeiten"
      } else {
        return "Neuer Termin"
      }
    },
  },

  data() {
    return {
      selectedAppointment: null,
      dialogMode: "",
      itemDialog: false,
      showDeleteDialog: false,
    }
  },

  async mounted() {
    await this.getAppointmentCategoryColors()
    // ToDo: dynamic appointment loading
    this.getAppointments({ rowsPerPage: 25, page: 1 })
  },

  methods: {
    ...mapActions("appointments", ["getAppointments"]),
    ...mapActions("categories", ["getCategories"]),

    // Generate dynamic CSS classes for appointment background colors depending on category
    async getAppointmentCategoryColors() {
      await this.getCategories({ rowsPerPage: 25, page: 1 }) // ToDo: Fetch all pages if there are more than 25 categories

      const styleSheet = document.styleSheets[document.styleSheets.length - 1]
      for (let category of this.categories) {
        const selectorClass = `calendar-appointment-category-${category.id}`
        const fontColor =
          colors.brightness(category.color) < 128 ? "white" : "black"
        styleSheet.insertRule(
          `.${selectorClass}{ background-color: ${category.color}; color: ${fontColor}; }`
        )
      }
    },

    openCreateDialog() {
      this.dialogMode = "create"
      this.itemDialog = true
    },

    openUpdateDialog() {
      this.dialogMode = "update"
      this.itemDialog = true
    },

    onAppointmentCreate() {
      this.selectedAppointment = this.createdAppointment
      this.dialogMode = "show"

      // Refresh category list
      // ToDo: dynamic appointment loading
      this.getAppointments({ rowsPerPage: 25, page: 1 })
    },

    onAppointmentUpdate() {
      this.selectedAppointment = this.updatedAppointment
      this.dialogMode = "show"

      // Refresh category list
      // ToDo: dynamic appointment loading
      this.getAppointments({ rowsPerPage: 25, page: 1 })
    },

    onEventClick(event, e) {
      this.selectedAppointment = this.appointments.find(
        (appointment) => appointment.id === event.id
      )
      this.dialogMode = "show"
      this.itemDialog = true

      // Prevent navigating to narrower view (default vue-cal behavior).
      e.stopPropagation()
    },

    onDeleteClick() {
      this.showDeleteDialog = true
    },

    onItemDeleted() {
      // Close dialogs
      this.$refs.deleteConfirm.hide()
      this.$refs.itemDialog.hide()

      // Refresh category list
      // ToDo: dynamic appointment loading
      this.getAppointments({ rowsPerPage: 25, page: 1 })
    },
  },
})
</script>

<style lang="scss">
.vuecal__now-line {
  color: #06c;
}
.vuecal__event.no-category {
  background-color: rgba(3, 36, 252, 0.9);
  border: 1px solid rgb(3, 36, 252);
  color: #fff;
}

.vuecal__event:hover {
  cursor: pointer;
}

.vuecal__flex .vuecal__menu {
  background-color: $primary;
  color: white;
}

.vuecal__title-bar {
  background-color: rgba(33, 33, 206, 0.7);
  color: white;
}

.vuecal__title-bar button {
  color: white;
}

.vuecal__menu {
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

.vuecal__event {
  border-radius: 4px;
}
</style>
