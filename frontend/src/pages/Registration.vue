<template>
  <div class="col-md-6 offset-md-3 p-3">
    <div class="mb-3">
      <h3>Registration</h3>
    </div>
    <form class="form-auth shadow p-4" :class="{formLoading:isLoading}" @submit.prevent="registerUser">
      <div v-if="isLoading" class="loading-block" >
        <Loader/>
      </div>
      <div class="mb-3">
        <label for="your_name">Your name</label>
        <input type="text" class="form-control" v-model="name"  name="Username" id="your_name" placeholder="Your name">
      </div>

      <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" class="form-control" placeholder="Email">
      </div>

      <div class="mb-3">
        <label for="Password">Password</label>
        <input type="password" class="form-control" v-model="password" id="Password" placeholder="Password">
      </div>

      <div class="mb-3">
        <label class="form-label" for="customFile">Download image</label>
        <input type="file" @change="previewFiles()" ref="file" accept="image/jpeg, image/png" class="form-control" id="customFile" />
      </div>

      <div class="mb-3">
        <styled-button type="submit" class="btn btn-styled">Registration</styled-button>
      </div>
      <hr>
      <p class="text-center mb-0">If you have account <router-link to="/login" >Login Now</router-link></p>
    </form>
    <styled-alert v-if="error">{{error}}</styled-alert>

  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";

export default {
  name: "Registration",
  data(){
    return ({
      email:"",
      password:"",
      name:"",
      file:"",

    })
  },
  mounted() {
    this.setError("");
  },

  computed:{
   ...mapGetters({
     error:"getUserError",
     isLoading:"getIsUserLoading"
   })
  },
  methods:{
    previewFiles() {
      this.file = this.$refs.file.files[0];
    },
    ...mapMutations({
      setError:"setError"
    }),
    ...mapActions({
      doRegistration:"doRegistration"
    }),
    async registerUser(){
      if(this.email.length<8){
        this.setError("Your email is too short");
      }else if(this.password.length<8){
        this.setError("Your password is too short");
      }else if(!this.file){
        this.setError("Please upload the image");
      }else{
        let data = {
          email:this.email,
          name:this.name,
          image:this.file,
          password:this.password,
        }
        await this.doRegistration(data);
        if(this.error.length===0){
          await this.$router.push("/login?message=You are registered successful. Please log in");
        }
      }

    }
  },
}
</script>

<style scoped>

</style>