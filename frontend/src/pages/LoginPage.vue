<template>
  <q-page class="flex">
    <div id="blurred-background"></div>
    <div id="login-card">
      <WrapperCard title="Login" id="login-wrapper-card">
        <div class="text-center q-mb-lg">
          <img src="/login-logo.png" alt="TKalender Logo" />
        </div>
        <div class="q-mb-md">
          <q-input
            ref="username"
            outlined
            v-model="username"
            label="Benutzername"
            @keyup.enter="onLoginSubmit"
          />
        </div>
        <div class="q-mb-xl">
          <q-input
            ref="password"
            outlined
            v-model="password"
            label="Passwort"
            type="password"
            @keyup.enter="onLoginSubmit"
          />
        </div>
        <div class="text-right">
          <q-btn
            color="primary"
            label="Anmelden"
            :loading="loading"
            @click="onLoginSubmit"
          >
            <template v-slot:loading> <q-spinner-hourglass /> </template>
          </q-btn>
        </div>
      </WrapperCard>
    </div>
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
      loading: false,
    }
  },

  methods: {
    ...mapActions("authentication", ["login"]),
    async onLoginSubmit() {
      // Frontend Validation
      if (!this.username || !this.password) return

      this.loading = true

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

      this.loading = false
    },
  },
})
</script>

<style lang="scss" scope>
#blurred-background {
  width: 100%;
  background-image: url("/login-background-image.jpg");
  filter: blur(3px);
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

#login-card {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 500px;
}

#login-wrapper-card {
  margin: 0;
}

@media screen and (max-width: 600px) {
  #login-card {
    width: 90vw;
  }
}
</style>
