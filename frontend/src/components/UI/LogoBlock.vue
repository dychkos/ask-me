<template>
  <div class="logo-block" @click="toInitialState">
    <img src="@/assets/lg.svg" width="40" height="40" alt="LogoBlock">
    <span class="logo-text">ASK.ME </span>
  </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
  name:"logo-block",
  methods:{
    ...mapActions({
      init:"fetchQuestions",
    }),
    toInitialState(){
      this.$router.push("/");
      this.init();
    }
  },

}
</script>

<style scoped>
.logo-block{
  display: flex;
  align-items: center;
  cursor: pointer;

}
.logo-text{
  margin-left: 15px;
  font-weight: 900;
  font-size: 24px;
}
</style>