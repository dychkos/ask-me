<template>
  <div class="footer">
    <div class="container">
      <logo-block/>
      <p>2021 - Powered by Sergey Dychko</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "Footer"
}
</script>

<style scoped>

.footer{
  background-color: #ffd95c;
  padding: 40px 0;
}

.footer .container{
  display: flex;
  align-items: center;
  justify-content: space-between;
}


</style>