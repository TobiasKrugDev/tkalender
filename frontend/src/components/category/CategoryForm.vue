<template>
    <div class="q-mb-md">
        <q-input 
            ref="categoryNameInput"
            v-model="category.name" 
            outlined 
            label="Name" 
            :rules="[val => !!val || 'Name ist ein Pflichtfeld']"
        />
    </div>
    <div class="q-mb-md">
        <q-input
            v-model="category.description"
            label="Beschreibung"
            outlined
            type="textarea"
        />
    </div>
    <div>
        <q-color v-model="category.color" no-header-tabs no-footer class="my-picker q-mx-auto" />
    </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'CategoryForm',

    props: {
        initialCategoryData: {
            type: Object,
            default: null,
        },
    },

    data () {
        return {
            category: {}
        }
    },

    mounted () {
        if (this.initialCategoryData) {
            this.category = this.initialCategoryData
        }
    },

    methods: {
        validateCategoryForm () {
            // ToDo: Color Picker Validation
            this.$refs.categoryNameInput.validate()

            if (this.$refs.categoryNameInput.hasError) {
                return false
            } else {
                return true
            }
        }
    }
})
</script>

<style lang="scss">
</style>