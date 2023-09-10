<template>
  <q-page class="flex flex-center">
    ToDo: Dashboard

    <FABCreateButton expandable-fab />
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import FABCreateButton from "components/FABCreateButton.vue"

export default defineComponent({
  name: 'IndexPage',

  components: {
    FABCreateButton,
  },
})
</script>
