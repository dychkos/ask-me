<template>
  <div class="auth">
    <div v-if="!isAuth" class="un_login">
      <button class="btn btn-login" style="margin-right: 8px" @click="$router.push('/login')">
        Login
      </button>
      <button class="btn btn-login" @click="$router.push('/registration')">
        Registration
      </button>
    </div>
    <div v-else class="login">
      <div @click="toggleVisible" >
      <span class="username" >
                    {{user.name}}
                </span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
          <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
        </svg>
      </div>

      <div v-if="isControlPanelOpen" class="auth-select shadow">
      <span class="auth-select-item" @click="$router.push('/user-profile')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        My question</span>
      <span v-if="role==='ADMIN'" class="auth-select-item" @click="$router.push('/admin-panel')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Admin panel</span>
        <span
            class="auth-select-item"
            @click="logoutUser"
        >
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M5 22a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v3h-2V4H6v16h12v-2h2v3a1 1 0 0 1-1 1H5zm13-6v-3h-7v-2h7V8l5 4-5 4z" fill="#000"/></svg>
        Logout</span>
      </div>
    </div>

  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  name: "Auth",
  data(){
    return{
      isControlPanelOpen:false,
      role:"USER"
    }
  },
  computed:{
    ...mapGetters({
      isAuth:"getIsUserAuth",
      user:"getUserInfo",
    }),
  },

  methods:{
    ...mapActions({
      doLogout:"doLogout"
    }),
    hideControlPanel(){
      this.isControlPanelOpen=false;
    },
    toggleVisible(){
      this.isControlPanelOpen=!this.isControlPanelOpen;
    },

    logoutUser(){
      this.$router.push('/');
      this.doLogout();
    }
  },
  mounted() {
    document.addEventListener('click',this.hideControlPanel.bind(this),true);

  },
  updated() {
    this.role = localStorage.getItem("ROLE");
  },
  beforeUnmount() {
    document.removeEventListener('click',this.hideControlPanel.bind(this),true);

  }
}
</script>

<style scoped>
.auth{
  cursor: pointer;
  position: relative;
}

.auth-select{
  position: absolute;
  padding: 20px 0;
  width: 140px;
  top:30px;
  left:-70px;
  background-color: #fff;
  border-radius: 10px;
  z-index: 10;
}
.auth-select-item {
  display: block;
  padding: 5px 10px;
  
}

.auth-select-item:hover{
  background-color: #ececec;
}
.btn-login{
  background-color: #fff;
  color: #5e512d;
}
</style>