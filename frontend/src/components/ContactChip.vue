<template>
  <q-chip color="primary" text-color="white">
    <q-avatar v-if="contact.image">
      <img :src="contact.image" />
    </q-avatar>
    {{ contact.firstname }} {{ contact.lastname }}
  </q-chip>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "ContactChip",

  props: {
    contact: {
      type: Object,
      default: null,
    },
  },
})
</script>
