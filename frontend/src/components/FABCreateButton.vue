<template>
    <q-page-sticky position="bottom-right" :offset="[75, 75]">
        <!-- Expandable Floating Action Button -->
        <q-fab v-if="expandableFab" color="primary" icon="add" direction="up" class="custom-expandable-floating-action-button">
            <router-link to="/categories/create">
                <q-fab-action color="primary" icon="mdi-palette" label="Kategorie hinzufügen" />
            </router-link>
            <router-link to="/locations/create">
                <q-fab-action color="primary" icon="mdi-map-marker-radius" label="Ort hinzufügen" />
            </router-link>
            <router-link to="/contacts/create">
                <q-fab-action color="primary" icon="mdi-account-group" label="Kontakt hinzufügen" />
            </router-link>
            <router-link to="/calendar/create_appointment">
                <q-fab-action color="primary" icon="mdi-calendar" label="Termin hinzufügen" />
            </router-link>
        </q-fab>
        
        <!-- Regular Floating Action Button -->
        <q-btn v-else fab icon="add" color="primary" class="custom-floating-action-button" @click="openCreateDialog" />
    </q-page-sticky>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'FABCreateButton',

    props: {
        expandableFab: {
            type: Boolean,
            default: false
        },
    },

    emits: ["createButtonClick"],

    methods: {
        openCreateDialog () {
            this.$emit("createButtonClick")
        }
    }
})
</script>

<style lang="scss">
    .custom-floating-action-button {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .custom-floating-action-button .q-icon, .custom-expandable-floating-action-button .q-fab__icon-holder .q-icon {
        font-size: 40px;
    }

    .custom-expandable-floating-action-button {
        width: 100px;
        height: 100px;
    }

    .custom-expandable-floating-action-button > a {
        border-radius: 50%;
    }

    .custom-expandable-floating-action-button .q-fab__icon-holder {
        min-width: 40px;
    }
</style>
