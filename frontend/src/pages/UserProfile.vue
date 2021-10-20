<template>
  <div>
    <div class="row">
      <div class="col-lg-10 right-side">
        <div class="main-header mt-3">
          <div>
            <h3>My question</h3>
          </div>
        </div>
        <div v-if="allQuestions.length===0" class="no-question">
          <h5>  You haven't asked any questions yet</h5>
          <ask-question-button/>

        </div>
        <question-list v-else :questions="allQuestions" :allowRemove="true"/>
      </div>
      <Sidebar>
        <ask-question-button/>
        <Loader v-if="isLoading"/>
        <div v-else class="user-info mt-2">
          <img :src="this.$root.backendImageURL + userInfo.image" alt="logo" width="110">
          <span class="username">{{ userInfo.name }}</span>
        </div>
      </Sidebar>
    </div>
  </div>

</template>

<script>
import Sidebar from "@/components/Sidebar";
import QuestionList from "@/components/QuestionsList";
import {mapActions, mapGetters} from "vuex";
import AskQuestionButton from "@/components/UI/AskQuestionButton";

export default {
  name: "UserProfile",
  components: {AskQuestionButton, Sidebar,QuestionList},

  computed:{
   ...mapGetters({
     allQuestions: 'allQuestions',
     isLoading: 'getIsUserLoading',
     userInfo:"getUserInfo"
   })
  },
  mounted() {
    this.fetchQuestionsForCurrentUser();
  },
  methods:{
    ...mapActions({
      fetchQuestionsForCurrentUser:"fetchQuestionsForCurrentUser",
    }),


  }
}
</script>

<style>
.user-info{
  text-align: center;
  margin-bottom: 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.username{
  font-weight: 900;
}
.no-question{
  background-color: #fff;
  padding: 15px;
  border-radius: 20px;
}
</style>