<template>
  <q-card class="dialog-card shadow-7">
    <q-card-section>
      <div class="row items-center no-wrap">
        <!-- ToDo: Profile Image -->
        <div v-if="color || profileImage" class="display-flex">
          <div
            class="category-table-color-circle"
            :style="'background-color: ' + color"
          />
          <div
            class="text-h5 text-weight-medium q-ml-sm additional-line-height"
          >
            {{ title }}
          </div>
        </div>
        <div v-else class="text-h5 text-weight-medium">
          {{ title }}
        </div>
        <q-space />
        <q-btn icon="close" color="grey-7" flat round dense v-close-popup />
      </div>
    </q-card-section>
    <q-card-section>
      <slot />
    </q-card-section>
  </q-card>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "DialogCard",

  props: {
    title: {
      type: String,
      default: "",
    },

    color: {
      type: String,
      default: "",
    },

    profileImage: {
      type: String,
      default: "",
    },
  },
})
</script>

<style lang="scss">
.dialog-card {
  width: 700px;
  max-width: 80vw;
}
</style>
