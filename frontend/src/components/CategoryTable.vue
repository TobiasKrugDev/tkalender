<template>
  <div>
    <q-table
      title="Katgorien"
      :rows="categories"
      :columns="columns"
    />
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
          name: 'color',
          label: 'Farbe',
          align: 'left',
          field: 'color'
        },
      ]
    }
  },

  mounted () {
    this.getCategories()
  },

  methods: {
    ...mapActions("categories", ["getCategories"]),
  }
})
</script>
