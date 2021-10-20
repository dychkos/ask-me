<template>
  <span class="category">
    <slot></slot>
  </span>
</template>

<script>
export default {
  name:"category-tag"
}
</script>

<style scoped>
.category {
  padding: 4px 18px;
  background-color: #f4e777;
  color: #5e512d;
  border-radius: 20px;
}
</style>