<template>
  <iframe
    :src="
      'https://maps.google.com/maps?&amp;q=' +
      location.streetAddress +
      ' ' +
      location.postalCode +
      ' ' +
      location.city +
      '&amp;output=embed'
    "
    width="500"
    height="400"
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
    class="google-maps-iframe"
  ></iframe>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "GoogleMapsEmbed",

  props: {
    location: {
      type: Object,
      default: null,
    },
  },
})
</script>

<style lang="scss">
.google-maps-iframe {
  border: 0;
  width: 100%;
  border-radius: 8px;
}
</style>
