<template>
  <q-page class="flex flex-center">
    ToDo: Dashboard

    <q-page-sticky position="bottom-right" :offset="[75, 75]">
      <q-btn fab icon="add" color="primary" size="xl" class="custom-floating-action-button" />
    </q-page-sticky>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'IndexPage',
})
</script>

<style lang="scss">
  .custom-floating-action-button {
    width: 100px;
    height: 100px;
    border-radius: 50%;
  }

  .custom-floating-action-button .q-icon {
    font-size: 40px;
  }
</style>
