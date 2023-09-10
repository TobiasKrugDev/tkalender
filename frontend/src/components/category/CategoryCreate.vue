<template>
    <CategoryForm ref="categoryForm" />

    <div class="text-right q-mt-xl">
        <q-btn rounded flat color="grey-7" icon="close" :label="$q.screen.gt.xs ? 'Abbrechen' : ''" class="q-mr-xs" v-close-popup />
        <q-btn rounded color="primary" icon="mdi-content-save" label="Speichern" class="q-ml-xs" @click="onCategorySave" />
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions } from "vuex"
import CategoryForm from "components/category/CategoryForm.vue"

export default defineComponent({
    name: 'CategoryCreate',

    components: {
        CategoryForm,
    },

    methods: {
        ...mapActions("categories", ["createCategory"]),

        onCategorySave () {
            const isValid = this.$refs.categoryForm.validateCategoryForm()
            if (isValid) this.createCategory(this.$refs.categoryForm.category)
        }
    }
})
</script>

<style lang="scss">
</style>
