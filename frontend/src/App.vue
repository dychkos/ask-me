<template>
  <Header/>
  <div class="main">
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
  <Footer/>
</template>

<script>
import Header from './components/Header.vue';
import Footer from './components/Footer';
import {mapActions} from "vuex";


export default {
  name: 'App',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      backendImageURL: "http://backend.ask-me.pp.ua/uploads/"
    }
  },
  methods:{
    ...mapActions({
      validateAuth:"validateAuth"
    })
  },


  async created(){
    await this.validateAuth();
  }



}
</script>

<style>

@import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;700&display=swap');


body{
  font-family: Ubuntu,sans-serif;
}

.main{
  background-color: #F2F8FF;
  min-height: calc(100vh - 180px);
}
</style>
