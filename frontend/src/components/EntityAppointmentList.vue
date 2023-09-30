<template>
  <div>
    <div
      v-for="appointment in appointments"
      :key="appointment.id"
      class="row q-mb-xs"
    >
      <div class="col text-grey-7 text-weight-medium">
        {{ formatAppointmentDate(appointment.startAt) }}
      </div>
      <div class="col">{{ appointment.name }}</div>
    </div>

    <!-- Pagination -->
    <div v-if="showPagination" class="flex flex-center q-my-lg">
      <CustomPagination
        :totalItems="entityTotalItems"
        :itemsPerPage="pagination.rowsPerPage"
        class="q-mx-auto"
        @page-change="onPageChange"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions, mapState } from "vuex"
import { date } from "quasar"
import CustomPagination from "src/components/CustomPagination.vue"

export default defineComponent({
  name: "EntityAppointmentList",

  components: {
    CustomPagination,
  },

  props: {
    entity: {
      type: String,
      required: true,
    },

    entityId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      appointments: [],
      pagination: {
        rowsPerPage: 5,
        page: 1,
      },
    }
  },

  computed: {
    ...mapState("appointments", ["entityAppointments", "entityTotalItems"]),
    showPagination() {
      return this.pagination.rowsPerPage < this.entityTotalItems
    },
  },

  mounted() {
    this.fetchEntityAppointments()
  },

  methods: {
    ...mapActions("appointments", ["getEntityAppointments"]),
    async fetchEntityAppointments() {
      const params = {
        ...this.pagination,
        entity: this.entity,
        filterID: this.entityId,
      }

      await this.getEntityAppointments(params)
      this.appointments = this.entityAppointments
    },

    formatAppointmentDate(appointmentDate) {
      const jsDate = new Date(appointmentDate)
      return date.formatDate(jsDate, "DD. MMMM YYYY - HH:mm")
    },

    onPageChange(page) {
      this.pagination.page = page
      this.fetchEntityAppointments()
    },
  },
})
</script>
