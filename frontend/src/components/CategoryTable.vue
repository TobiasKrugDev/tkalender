<template>
  <div>
    ToDo: CategoryTable <br>
    {{ categories }}
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"

export default defineComponent({
  name: 'CategoryTable',
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
