<template>
  <div>
    <vue-cal
      id="vuecal"
      active-view="week"
      :class="
        dashboardMode ? 'dashboard-mode-height' : 'fullscreen-mode-height'
      "
      :disable-views="['years', 'year']"
      locale="de"
      :events="events"
      events-on-month-view="short"
      show-all-day-events="short"
      :on-event-click="onEventClick"
      today-button
      class="full-width"
      :xsmall="$q.screen.lt.lg"
      :editable-events="{
        title: false,
        drag: true,
        resize: true,
        delete: false,
        create: $q.screen.gt.sm, // Don't allow create on mobile devices because scrolling might confuse users
      }"
      :snap-to-time="15"
      :time-cell-height="timeCellHeight"
      @ready="onCalendarReady"
      @view-change="onViewChange"
      @event-change="onEventChange"
      :on-event-create="onEventCreate"
      @event-drag-create="onEventDragCreate"
      @cell-click="onCellClick"
    >
      <template #today-button>
        <q-btn flat color="white" icon="gps_fixed">
          <q-tooltip> Heute </q-tooltip>
        </q-btn>
      </template>
    </vue-cal>

    <q-dialog
      ref="itemDialog"
      v-model="itemDialog"
      persistent
      @before-hide="onDialogHide"
    >
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
import { mapActions, mapState, mapMutations } from "vuex"
import { colors, date } from "quasar"
import { getHolidays } from "feiertagejs"
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

  props: {
    dashboardMode: {
      type: Boolean,
      deafult: false,
    },
  },

  emits: ["appointmentChange"],

  computed: {
    ...mapState("appointments", [
      "calendarAppointments",
      "createdAppointment",
      "updatedAppointment",
    ]),
    ...mapState("categories", ["categories"]),

    events() {
      const result = []

      // Appointments
      for (let appointment of this.calendarAppointments) {
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

      // Holidays
      for (let holiday of this.holidays) {
        const event = {
          start: holiday.dateString,
          end: holiday.dateString,
          title: holiday.name,
          class: "calendar-holiday",
          allDay: true,
          draggable: false,
          resizable: false,
          isHoliday: true,
        }
        // feiertage.js's holiday naming is pretty ugly so we replace it with more readable titles
        switch (holiday.name) {
          case "NEUJAHRSTAG":
            event.title = "Neujahr"
            break
          case "HEILIGEDREIKOENIGE":
            event.title = "Hl. drei Könige"
            break
          case "KARFREITAG":
            event.title = "Karfreitag"
            break
          case "OSTERMONTAG":
            event.title = "Ostermontag"
            break
          case "TAG_DER_ARBEIT":
            event.title = "Tag der Arbeit"
            break
          case "CHRISTIHIMMELFAHRT":
            event.title = "Christi Himmelfahrt"
            break
          case "PFINGSTMONTAG":
            event.title = "Pfingstmontag"
            break
          case "FRONLEICHNAM":
            event.title = "Fronleichnam"
            break
          case "MARIAHIMMELFAHRT":
            event.title = "Maria Himmelfahrt"
            break
          case "DEUTSCHEEINHEIT":
            event.title = "Tag der dt. Einheit"
            break
          case "ALLERHEILIGEN":
            event.title = "Allerheiligen"
            break
          case "ERSTERWEIHNACHTSFEIERTAG":
            event.title = "1. Weihnachtsfeiertag"
            break
          case "ZWEITERWEIHNACHTSFEIERTAG":
            event.title = "2. Weihnachtsfeiertag"
            break
          default:
            event.title = holiday.name

          //ToDo: maybe add holidays like Heiligabend, Silvester, Ostersonntag
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
      currentStartMonth: new Date().getMonth(),
      currentStartYear: new Date().getFullYear(),
      timespanStart: null,
      calendarTimespanEnd: null,
      holidays: [],
      deleteEventFunction: () => {},
      timeCellHeight: 39,
    }
  },

  methods: {
    ...mapMutations("appointments", [
      "setCreatedTimespanStart",
      "setCreatedTimespanEnd",
    ]),
    ...mapActions("appointments", [
      "getCalendarAppointments",
      "updateAppointment",
    ]),
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
          `.${selectorClass}{ background-color: ${category.color}; border-color: ${category.color}; color: ${fontColor}; }`
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
      this.getCalendarAppointments({
        start: this.timespanStart,
        end: this.timespanEnd,
      })

      this.$emit("appointmentChange")
    },

    onAppointmentUpdate() {
      this.selectedAppointment = this.updatedAppointment
      this.dialogMode = "show"

      // Refresh category list
      this.getCalendarAppointments({
        start: this.timespanStart,
        end: this.timespanEnd,
      })

      this.$emit("appointmentChange")
    },

    onEventClick(event, e) {
      if (event.isHoliday) return // Do nothing if holiday is clicked
      this.selectedAppointment = this.calendarAppointments.find(
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
      this.getCalendarAppointments({
        start: this.timespanStart,
        end: this.timespanEnd,
      })

      this.$emit("appointmentChange")
    },

    async onCalendarReady(e) {
      this.scrollToCurrentTime()
      this.holidays = [
        ...getHolidays(e.startDate.getFullYear() - 1, "BY"),
        ...getHolidays(e.startDate.getFullYear(), "BY"),
        ...getHolidays(e.startDate.getFullYear() + 1, "BY"),
      ]
      await this.getAppointmentCategoryColors()

      const start = new Date(
        e.startDate.getFullYear(),
        e.startDate.getMonth() - 1
      )
      const end = new Date(
        e.startDate.getFullYear(),
        e.startDate.getMonth() + 1
      )

      this.timespanStart = start
      this.timespanEnd = end

      this.getCalendarAppointments({
        start: this.timespanStart,
        end: this.timespanEnd,
      })
    },

    onViewChange(e) {
      // Fetch appointments on month change
      if (e.startDate.getMonth() !== this.currentStartMonth) {
        const start = new Date(
          e.startDate.getFullYear(),
          e.startDate.getMonth() - 1
        )
        const end = new Date(
          e.startDate.getFullYear(),
          e.startDate.getMonth() + 1
        )

        this.timespanStart = start
        this.timespanEnd = end

        this.getCalendarAppointments({
          start: this.timespanStart,
          end: this.timespanEnd,
        })

        this.currentStartMonth = e.startDate.getMonth()
      }

      // Get holidays on year change
      if (e.startDate.getFullYear() !== this.currentStartYear) {
        this.holidays = [
          ...getHolidays(e.startDate.getFullYear() - 1, "BY"),
          ...getHolidays(e.startDate.getFullYear(), "BY"),
          ...getHolidays(e.startDate.getFullYear() + 1, "BY"),
        ]

        this.currentStartYear = e.startDate.getFullYear()
      }
    },

    async onEventChange(e) {
      if (e.event.isHoliday) return // Do nothing if holiday is clicked
      this.selectedAppointment = this.calendarAppointments.find(
        (appointment) => appointment.id === e.event.id
      )

      const appointment = { ...this.selectedAppointment } // Copy appointment to prevent vuex mutation errors

      appointment.startAt = date.formatDate(
        e.event.start,
        "YYYY-MM-DD HH:mm:00"
      )

      appointment.endAt = date.formatDate(e.event.end, "YYYY-MM-DD HH:mm:00")

      await this.updateAppointment(appointment)

      this.$emit("appointmentChange")
    },

    onEventCreate(e, deleteEventFunction) {
      this.deleteEventFunction = deleteEventFunction
      return e
    },

    onEventDragCreate(e) {
      this.setCreatedTimespanStart(
        date.formatDate(e.start, "YYYY-MM-DD HH:mm:00")
      )

      this.setCreatedTimespanEnd(date.formatDate(e.end, "YYYY-MM-DD HH:mm:00"))
      this.openCreateDialog()
    },

    onDialogHide() {
      this.deleteEventFunction()
      this.setCreatedTimespanStart("")
      this.setCreatedTimespanEnd("")
    },

    onCellClick(e) {
      this.setCreatedTimespanStart(date.formatDate(e, "YYYY-MM-DD HH:mm:00"))
      this.openCreateDialog()
    },

    scrollToCurrentTime() {
      const calendar = document.querySelector("#vuecal .vuecal__bg")
      const now = new Date()
      const hours = now.getHours() + now.getMinutes() / 60
      calendar.scrollTo({
        top: hours * this.timeCellHeight,
        behavior: "smooth",
      })
    },

    refreshAppointments() {
      this.getCalendarAppointments({
        start: this.timespanStart,
        end: this.timespanEnd,
      })
    },
  },
})
</script>

<style lang="scss">
.vuecal__now-line {
  color: #06c;
}

.vuecal__event,
.vuecal__event.no-category {
  background-color: rgba(3, 36, 252, 0.9);
  border: 1px solid rgb(3, 36, 252);
  color: #fff;
}

.vuecal__event.calendar-holiday {
  background-color: #6363dc;
  border: 1px solid #6363dc;
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

.fullscreen-mode-height {
  height: calc(100vh - 290px);
}

.dashboard-mode-height {
  height: 50vh;
}

@media screen and (min-width: 600px) {
  .fullscreen-mode-height {
    height: calc(100vh - 300px);
  }
}

@media screen and (min-width: 1024px) {
  .fullscreen-mode-height {
    height: calc(100vh - 224px);
  }
}

@media screen and (max-height: 900px) and (min-width: 1024px) {
  .dashboard-mode-height {
    height: 40vh;
  }
}

@media screen and (min-height: 1100px) and (min-width: 1024px) {
  .dashboard-mode-height {
    height: 55vh;
  }
}
</style>
