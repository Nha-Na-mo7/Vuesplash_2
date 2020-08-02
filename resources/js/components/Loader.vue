<template>
  <div class="loader">
    <p class="loading__rext">
      <slot>Loading ...</slot>
    </p>
    <div class="loader__item loader__item--heart">
      <div></div>
    </div>
  </div>
</template>

<script>
export default {
name: "Loader"
}
</script>

<style scoped>

</style>