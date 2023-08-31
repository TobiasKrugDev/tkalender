<template>
  <div>
    <q-table
      title="Katgorien"
      :rows="categories"
      :columns="columns"
    >
      <template v-slot:body-cell-color="props">
        <q-td :props="props">
          <div class="category-table-color-circle" :style="'background-color: ' + props.value">
          </div>
        </q-td>
      </template>
    </q-table>
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
          name: 'color',
          label: '',
          align: 'left',
          field: 'color'
        },
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

<style lang="scss">
  .category-table-color-circle {
    height: 48px;
    width: 48px;
    border-radius: 50%;
  }
</style>
