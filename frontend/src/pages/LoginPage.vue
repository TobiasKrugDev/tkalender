<template>
  <q-page class="flex">
    <WrapperCard title="Login">
      <q-input
        ref="username"
        outlined
        v-model="username"
        label="Benutzername"
        :rules="[(val) => !!val || 'Bitte geben Sie Ihren Benutzernamen ein']"
      />
      <q-input
        ref="password"
        outlined
        v-model="password"
        label="Passwort"
        :rules="[(val) => !!val || 'Bitte geben Sie Ihr Passwort ein']"
        type="password"
      />
      <div class="text-right">
        <q-btn color="primary" label="Login" @click="onLoginClick" />
      </div>
    </WrapperCard>
  </q-page>
</template>

<script>
import { defineComponent } from "vue"
import { mapActions } from "vuex"
import WrapperCard from "components/WrapperCard.vue"

export default defineComponent({
  name: "LoginPage",

  components: {
    WrapperCard,
  },

  data() {
    return {
      username: "",
      password: "",
    }
  },

  methods: {
    ...mapActions("authentication", ["login"]),
    async onLoginClick() {
      // Frontend Validation
      this.$refs.username.validate()
      this.$refs.password.validate()

      if (this.$refs.username.hasError || this.$refs.password.hasError) return

      const response = await this.login({
        username: this.username,
        password: this.password,
      })

      if (response.success) {
        this.$router.push("/")
      } else {
        this.$q.notify({
          message: "Login nicht erfolgreich",
          type: "negative",
          position: "bottom",
          progress: true,
        })
      }
    },
  },
})
</script>
