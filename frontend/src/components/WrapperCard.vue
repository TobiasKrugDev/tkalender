<template>
  <q-card class="wrapper-card shadow-7 q-mx-md">
    <q-card-section>
      <div class="row items-center no-wrap">
        <div class="col">
          <div class="text-h5 text-weight-medium">
            {{ title }}
          </div>
        </div>
      </div>
    </q-card-section>
    <q-card-section>
      <slot />
    </q-card-section>
  </q-card>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "WrapperCard",

  props: {
    title: {
      type: String,
      default: "",
    },
  },
})
</script>

<style lang="scss">
.wrapper-card {
  width: 100%;
  margin-bottom: 24px;
}

@media screen and (min-width: 1024px) {
  .wrapper-card {
    margin-right: 24px;
    margin-left: 40px;
  }
}
</style>
