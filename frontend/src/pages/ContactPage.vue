<template>
  <q-page class="flex">
    <WrapperCard title="Kontakte">
      <ContactTable ref="contactTable" />
    </WrapperCard>

    <FABCreateButton @create-button-click="openCreateDialog" />
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import ContactTable from "components/ContactTable.vue"
import FABCreateButton from "components/FABCreateButton.vue"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: "ContactPage",

  components: {
    ContactTable,
    FABCreateButton,
    WrapperCard,
  },

  data() {
    return {
      createDialog: false,
    }
  },

  mounted() {
    // Check for "/create" in requested route
    if (this.$route.path.includes("/create")) {
      this.openCreateDialog()
    }
  },

  methods: {
    openCreateDialog() {
      this.$refs.contactTable.openCreateDialog()
    },
  },
})
</script>
