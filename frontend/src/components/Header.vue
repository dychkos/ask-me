<template>
  <div class="header">
    <div class="container">
      <logo-block/>
      <Auth/>
    </div>
  </div>

</template>

<script>
import Auth from "./Auth";

export default {
  components:{
    Auth
  },

}
</script>

<style>

.header{
  background-color:#ffd95c;
}
.header .container{
  padding: 14px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;

}

</style>