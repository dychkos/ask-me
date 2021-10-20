<template>
  <div class="message">
    <div class="message-body">
      <div class="message-user">
        <img :src="this.$root.backendImageURL + answer.author_image" alt="logo" width="40" height="40" >
      </div>
      <div class="message-info">
        <span class="message-username">{{ answer.author }}</span>
        <span class="message-text">{{answer.title}}</span>
      </div>
    </div>
    <div class="message-annex">
      <div class="like">
        <styled-button class="btn-like text-nowrap" :class="{active:answer.isLiked}" @click="likeAnswer">
          {{ answer.likes_count}} <img class="m-1" src="@/assets/like.png" width="18" alt="Like">
        </styled-button>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name:"answer-item",
  props:{
    answer:{
      type:Object,
      required:true
    }
  },
  computed:{
    ...mapGetters({
      currentUser:"getUserInfo"
    })
  },
  methods:{
    likeAnswer(){
      this.$emit("like",this.answer);
    }
  }

}
</script>

<style scoped>

.active{
  background-color: #f8f3c6;
}

.message{
  display: flex;
  padding: 15px;
  margin: 15px 0;
  align-items: center;
  justify-content: center;
}

.message-body{
  display: flex;
  width: 100%;

}
.message-user img{
  width: 40px;
  border-radius: 50%;
  margin-right: 8px;

}

.message-info{
  display: flex;
  flex-direction: column;
  margin-right: 20px;
}

.message-username{
  font-weight: 900;
  color: #5e512d;
  font-size: 14px;
}

.message-text{
  margin-bottom: 20px;
}

</style>