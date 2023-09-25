<!--- ToDo: Integrate CategryChip on all occasions -->
<template>
  <q-chip text-color="white" :style="'background-color: ' + category.color">
    {{ category.name }}
  </q-chip>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "CategoryChip",

  props: {
    category: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {}
  },

  methods: {},
})
</script>

<style lang="scss"></style>
