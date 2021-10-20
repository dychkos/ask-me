<template>
  <div class="col-md-6 offset-md-3 pt-5">
    <div class="login-message" v-if="message">{{message}}</div>
    <div>
      <h3>Login</h3>
    </div>

      <form @submit.prevent="loginUser" class="form-auth shadow p-4" :class="{formLoading:isLoading}">
        <div v-if="isLoading" class="loading-block" >
          <Loader/>
        </div>
        <div class="mb-3">
          <label for="username">Email</label>
          <input type="email" class="form-control" id="username" v-model="email" placeholder="Email">
        </div>

        <div class="mb-3">
          <label for="Password">Password</label>
          <input type="password" class="form-control" id="Password"  v-model="password" placeholder="Password">
        </div>

        <div class="mb-3">
          <styled-button type="submit" class="btn btn-styled">Login</styled-button>
        </div>

        <hr>

        <p class="text-center mb-0">If you have not account <router-link to="/registration" >Register Now</router-link></p>

      </form>

      <styled-alert v-if="error">{{error}}</styled-alert>

  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";

export default {
  name:"Login",
  data(){
    return ({
      email:"",
      password:"",
      message:"",
    })
  },

  mounted() {
    this.setError("");
    if(this.$router.currentRoute.value.query.message){
      this.message = this.$router.currentRoute.value.query.message;
    }else {
      this.message="";
    }
  },

  computed:{
    ...mapGetters({
      error:"getUserError",
      isLoading:"getIsUserLoading"
    })
  },
  methods:{
    ...mapMutations({
      setError:"setError"
    }),
    ...mapActions({
      doLogin:"doLogin"
    }),

    checkCorrectData(){
      if(this.email.length<8){
        this.setError("Your email is too short");
        return false;
      }else if(this.password.length<8){
        this.setError("Your password is too short");
        return false;
      }else{
        return true;
      }
    },

    async loginUser(){
      if(this.checkCorrectData()){
        let data = {
          email:this.email,
          password:this.password
        }
        await this.doLogin(data);
        if(this.error.length===0){
          await this.$router.push("/");
        }
      }
    }
  }
}
</script>

<style>

.form-auth{
  background-color: #fff;
  position: relative;
}

.form-auth.formLoading{
  pointer-events: none;
}

.loading-block{
  position: absolute;
  z-index: 3;
  display: flex;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  align-items: center;
  background-color: rgba(255 ,255 ,255, 0.8);
}

.login-message{
  padding: 15px;
  background: #fff;
  margin-bottom: 12px;
  text-align: center;
  color: #686239;
  border-radius: 10px;
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>