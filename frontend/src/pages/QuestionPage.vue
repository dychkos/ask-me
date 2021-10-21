<template>
  <div class="row">
    <div class="col-lg-10 left-side">
      <div  class="question">
        <div class="question-header">
          <h2>{{ question.title }}</h2>
        </div>
        <div class="question-body">
          <div class="question-text">
            {{question.description}}
          </div>
          <styled-alert v-if="!isAuth">
            Only auth users can add new answers! <a class="link" @click="$router.push('/login')">Please auth!</a>
          </styled-alert>
          <add-answer-form v-else @answer="newAnswer"/>
          <Loader v-if="isQuestionPageLoading" />

          <div v-else class="answers-list mt-3">
            <h4 v-if="question.answersCount>0">{{ question.answersCount }} answers</h4>
            <h4 v-else>No answers</h4>
            <answer-item v-for="answer in answers" :answer="answer" :key="answer.id" @like="likeOrUnlike"></answer-item>
          </div>
        </div>
      </div>
    </div>
    <Sidebar>
      <ask-question-button/>
      <div class="user-info mt-2">
        <img :src="this.$root.backendImageURL + question.author_image" alt="avatar" width="110">
        <span class="username">{{ question.author }}</span>
      </div>
    </Sidebar>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";
import AnswerItem from "@/components/Answer";
import AddAnswerForm from "@/components/AddAnswerForm";
import Sidebar from "@/components/Sidebar";
import {getUID} from "@/helpers";


export default {
  name:"question-page",

  components: {AddAnswerForm, AnswerItem,Sidebar},
  data(){
    return({
      currentQuestionID:null,
    })
  },

  computed:{
    ...mapGetters({
      question:"questionBody",
      answers:"getAnswers",
      isAuth:"getIsUserAuth",
      user:"getUserInfo",
      isQuestionPageLoading:"isQuestionPageLoading",
    })
  },

   updated() {
     if(parseInt(this.question.isDraft) && this.question.user_id!==getUID()){
        this.$router.push("/");
     }

  },
  async created(){
    this.currentQuestionID = this.$router.currentRoute.value.params.id;
    await this.fetchQuestionBody(this.currentQuestionID);
    await this.fetchAnswers(this.currentQuestionID);

  },
  methods:{
    ...mapMutations({
      addNewAnswer:"addNewAnswer"
    }),
    ...mapActions({
      fetchQuestionBody:"fetchQuestionBody",
      fetchAnswers:"fetchAnswers",
      likeAnswer:"likeAnswer",
      unlikeAnswer:"unlikeAnswer",
      addAnswer:"addAnswer"

    }),
    newAnswer(answerText){
      let answerBody = {
        question_id:this.currentQuestionID,
        title:answerText
      }
      this.addAnswer(answerBody);
    },

    likeOrUnlike(answer){
      if(!getUID()){
        this.$router.push('/login');
      }

      let tmpAnswer = {
        ...answer,
        question_id:this.currentQuestionID
      }

      if(answer.isLiked){
        this.unlikeAnswer(tmpAnswer);
      }else{
        this.likeAnswer(tmpAnswer);
      }
    }
  }

}
</script>

<style scoped>
.question-body{
  display: block;
}
.question{
  background-color: #fff;
  border-radius: 20px;
  padding: 20px;

}


.question-text{
  margin-bottom: 12px;
}

</style>