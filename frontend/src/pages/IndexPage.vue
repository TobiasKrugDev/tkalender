<template>
  <q-page class="flex flex-center">
    ToDo: Dashboard {{ categories }}
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"

export default defineComponent({
  name: 'IndexPage',

  computed: {
    ...mapState("categories", ["categories"]),
  },

  mounted () {
    this.getCategories()
  },

  methods: {
    ...mapActions("categories", ["getCategories"]),
  }
})
</script>
