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
})
</script>
