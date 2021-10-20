<template>
  <div class="message">
    <div class="message-body">
      <div class="message-user">
        <img :src="this.$root.backendImageURL + question.author_image" alt="logo"  width="40" height="40">

      </div>
      <div class="message-info">
        <span class="message-username">{{question.author}}</span>
        <span class="message-title" @click="$router.push(`/question/${question.id}`)">{{question.title}}</span>
        <div class="message-footer">
          <category-tag>{{ question.category }}</category-tag>
          <span class="message-mark">{{question.answersCount}} answers</span>
        </div>

      </div>
    </div>
    <div class="message-annex">
      <div class="control">
        <div  class="d-flex align-items-center">

          <styled-button v-if="question.isDraft==='1'" class="text-nowrap" @click="$emit('makePublic',question.id)">
            Make public
          </styled-button>

          <styled-button v-else  class="text-nowrap" @click="$router.push(`/question/${question.id}`)">
            Send answer
          </styled-button>

          <button v-if="allowRemove" class="btn btn-danger text-nowrap"  @click="removeQuestion">
            &times;
          </button>
        </div>
      </div>


    </div>
  </div>
</template>

<script>
export default {
  name:"question-item",
  props:{
    question:{
      type:Object,
      required:true
    },
    allowRemove:{
      type:Boolean,
      default:false
    },
  },methods:{
    removeQuestion(){
      if(confirm("Are you really want to delete this message ?")){
        this.$emit("removeQuestion",this.question.id);
      }
    }
  }

}
</script>

<style>
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
.message-title{
  cursor: pointer;
}

.message-footer{
  display: flex;
  align-items: center;
  margin-top: 15px;
}

.message-mark{
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 1px;
  padding: 4px 12px;
  border:1px solid #5e512d;
  margin-left: 20px;
  border-radius: 20px;

}
@media (max-width: 768px) {
  .message-annex .control .btn {
    display: none;
  }
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

.message-title{
  font-size: 18px;
  font-weight: 500;
}


</style>