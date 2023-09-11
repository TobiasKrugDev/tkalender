<template>
  <div>
    <q-pagination
      v-model="currentPage"
      :max="pageCount"
      :max-pages="5"
      :ellipses="false"
      :boundary-numbers="false"
      direction-links
      @update:model-value="changePage"
    />
  </div>
</template>

<script>
export default {
  name: "CustomPagination",

  props: {
    totalItems: {
      type: Number,
      default: 10,
    },

    itemsPerPage: {
      type: Number,
      default: 10,
    },
  },

  emits: ["pageChange"],

  data () {
    return {
      currentPage: 1,
    }
  },

  computed: {
    pageCount () {
      return Math.ceil(this.totalItems / this.itemsPerPage)
    },
  },

  methods: {
    changePage () {
      this.$emit("pageChange", this.currentPage)
    },
  },
}
</script>
