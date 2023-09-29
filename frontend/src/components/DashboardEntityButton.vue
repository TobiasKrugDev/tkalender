<template>
  <div class="col-sm col-12 q-my-lg">
    <div class="dashboard-btn-container shadow-7">
      <q-btn
        :to="entityData.to"
        :icon="entityData.icon"
        :label="entityData.label"
        flat
        stack
        :size="dashboardBtnSize"
        class="dashboard-btn"
        color="grey-7"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "DashboardEntityButton",

  props: {
    entityData: {
      type: Object,
      default: null,
    },
  },

  computed: {
    dashboardBtnSize() {
      if (this.$q.screen.gt.md) {
        return "25px"
      } else {
        return "20px"
      }
    },
  },
})
</script>

<style lang="scss">
.dashboard-btn-container {
  height: 150px;
  width: 300px;
  position: relative;
  margin: auto;
  background-color: #fff;
  border-radius: 8px;
}

@media screen and (min-width: 600px) {
  .dashboard-btn-container {
    width: 165px;
  }
}

@media screen and (min-width: 1024px) {
  .dashboard-btn-container {
    width: 200px;
  }
}

@media screen and (min-width: 1440px) {
  .dashboard-btn-container {
    height: 200px;
    width: 300px;
  }
}
</style>
