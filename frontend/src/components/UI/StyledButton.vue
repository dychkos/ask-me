<template>
  <button class="btn btn-styled" type="button" >
    <slot></slot>
  </button>
</template>

<script>
export default {
  name:"styled-button"

}
</script>

<style scoped>
.btn-styled{
  background-color: #f4e777;
  color: #5e512d;
  display: flex;
  align-items: center;
}
</style>