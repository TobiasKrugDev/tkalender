<template>
  <q-layout view="lHh lpR lFf">
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "LoginLayout",

  components: {},

  data() {
    return {}
  },

  methods: {},
})
</script>

<style lang="scss"></style>
