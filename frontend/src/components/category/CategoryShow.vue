<template>
    <div class="q-mb-lg">
        <div class="show-property-name q-mb-xs">
            Beschreibung:
        </div>
        <div>
            {{ category.description }}
        </div>
    </div>
    <div>
        <div class="show-property-name q-mb-xs">
            Farbe:
        </div>
        <div>
            <div class="category-table-color-circle" :style="'background-color: ' + category.color" />
        </div>
    </div>

    <div class="text-right q-mt-xl">
        <q-btn rounded flat color="red-6" icon="delete" :label="$q.screen.gt.xs ? 'Löschen' : ''" class="q-mr-xs" @click="onDeleteClick" />
        <q-btn rounded color="primary" icon="edit" :label="$q.screen.gt.xs ? 'Bearbeiten' : ''" class="q-ml-xs" />
    </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'CategoryShow',

    props: {
        category: {
            type: Object,
            required: true,
        },
    },

    emits: ["deleteClick"],

    methods: {
        onDeleteClick () {
            this.$emit("deleteClick", this.category)
        }
    }
})
</script>

<style lang="scss">
</style>
