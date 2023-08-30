<template>
  <div>
    <q-table
      title="Orte"
      :rows="locations"
      :columns="columns"
    />
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

  data () {
    return {
      columns: [
        {
          name: 'name',
          label: 'Name',
          align: 'left',
          field: 'name'
        },
        {
          name: 'description',
          label: 'Beschreibung',
          align: 'left',
          field: 'description'
        },
        {
          name: 'streetAddress',
          label: 'Straße + Hausnr.',
          align: 'left',
          field: 'streetAddress'
        },
        {
          name: 'postalCode',
          label: 'Postleitzahl',
          align: 'left',
          field: 'postalCode'
        },
        {
          name: 'city',
          label: 'Ort',
          align: 'left',
          field: 'city'
        },
      ]
    }
  },

  mounted () {
    this.getLocations()
  },

  methods: {
    ...mapActions("locations", ["getLocations"]),
  }
})
</script>
