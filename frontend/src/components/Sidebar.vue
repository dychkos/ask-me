<template>
  <div class="col-lg-2 right-side">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name:"Styled-Sidebar"
}
</script>

<style scoped>
.right-side{
  padding: 15px;
  margin-top:15px;
  height: 100%;
  background-color: #fff;
  border-radius: 20px;
}

</style>