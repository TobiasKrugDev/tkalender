<template>
  <q-card class="dialog-card shadow-7">
    <q-card-section>
      <div class="row items-center no-wrap">
        <div
          v-if="color || profileImage || showPlaceholderImage"
          class="display-flex"
        >
          <div
            v-if="color"
            class="category-table-color-circle"
            :style="'background-color: ' + color"
          />
          <div v-if="profileImage">
            <q-avatar>
              <img :src="profileImage" />
            </q-avatar>
          </div>
          <div v-if="showPlaceholderImage">
            <q-avatar icon="mdi-account" color="grey-5" text-color="white" />
          </div>
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

    showPlaceholderImage: {
      type: Boolean,
      default: false,
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
