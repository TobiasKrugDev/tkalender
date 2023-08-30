<template>
  <div>
    ToDo: ContactTable <br>
    {{ contacts }}
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapState } from "vuex"

export default defineComponent({
  name: 'ContactTable',
    computed: {
    ...mapState("contacts", ["contacts"]),
  },

  mounted () {
    this.getContacts()
  },

  methods: {
    ...mapActions("contacts", ["getContacts"]),
  }
})
</script>
