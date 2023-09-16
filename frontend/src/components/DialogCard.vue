<template>
  <q-card class="dialog-card shadow-7">
    <q-card-section>
      <div class="row items-center no-wrap">
        <div class="text-h5 text-weight-medium">
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
  },
})
</script>

<style lang="scss">
.dialog-card {
  width: 700px;
  max-width: 80vw;
}
</style>
