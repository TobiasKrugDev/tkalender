<template>
  <router-view v-slot="{ Component }">
    <transition
      enter-active-class="animated zoomIn"
      leave-active-class="animated zoomOut"
      mode="out-in"
    >
      <component :is="Component" />
    </transition>
  </router-view>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "App",

  mounted () {
    // In the native mobile app set primary color as background for status bar
    if (this.$q.platform.is.capacitor) {
      this.$q.capacitor.Plugins.StatusBar.setBackgroundColor({ color: "#2121ce" }) // ToDo: check if primary variable can be used here
    }
  }
})
</script>
