<!--- ToDo: Integrate CategryChip on all occasions -->
<template>
  <q-chip
    :text-color="textColor"
    :style="'background-color: ' + category.color"
  >
    {{ category.name }}
  </q-chip>
</template>

<script>
import { defineComponent } from "vue"
import { colors } from "quasar"

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

  computed: {
    textColor() {
      if (colors) {
        return colors.brightness(this.category.color) < 128 ? "white" : "black"
      } else {
        return "black"
      }
    },
  },

  methods: {},
})
</script>

<style lang="scss"></style>
