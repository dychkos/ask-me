<template>
  <form class="add-answer-form" @submit.prevent="addNewAnswer">
    <div class="form-group add-answer-area ">
      <label for="add_answer">Enter your answer</label>
      <textarea class="form-control" v-model="answerText" id="add_answer" rows="3"></textarea>
    </div>
    <styled-alert v-if="error">
      {{error}}
    </styled-alert>
    <styled-button type="submit" class="btn btn-styled">Add answer</styled-button>
  </form>

</template>

<script>
export default {
  name:"add-answer-form",
  data(){
    return {
      answerText:"",
      error:"",
    }
  },
  methods:{
    addNewAnswer(){
      if(!this.answerText || this.answerText.length<3){
        this.error = "Your answer is too short!";
      }else{
        this.$emit("answer",this.answerText);
        this.answerText = "";
        this.error="";
      }
    }
  }
}
</script>

<style scoped>

.add-answer-area{
  margin-bottom: 12px;
}

.add-answer-form{
  margin-top: 15px;
  display: flex;
  flex-direction: column;
}

.add-answer-form .btn{
  align-self: end;
}
</style>