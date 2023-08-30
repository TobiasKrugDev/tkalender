<template>
  <div>
    ToDo: LocationTable <br>
    {{ locations }}
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"

export default defineComponent({
  name: 'LocationTable',
    computed: {
    ...mapState("locations", ["locations"]),
  },

  mounted () {
    this.getLocations()
  },

  methods: {
    ...mapActions("locations", ["getLocations"]),
  }
})
</script>
