<template>
  <q-page class="flex">
    ToDo: Dashboard

    <FABCreateButton expandable-fab />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import FABCreateButton from "components/FABCreateButton.vue"

export default defineComponent({
  name: "IndexPage",

  components: {
    FABCreateButton,
  },
})
</script>
