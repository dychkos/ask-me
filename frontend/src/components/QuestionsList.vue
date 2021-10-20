<template>
  <div class="message-list">
    <question-item
        v-for="question in questions"
        :key="question.id"
        :allowRemove="allowRemove"
        :question="question"
        @makePublic="makeQuestionPublic"
        @removeQuestion="removeQuestion"/>
  </div>


</template>

<script>
import QuestionItem from "./QuestionItem";
import {mapActions} from "vuex";
export default {
  name:"question-list",
  components: {QuestionItem},
  props:{
    questions:{
      type:Array,
      required:true
    },
    allowRemove:{
      type:Boolean,
      default:false
    },
  },
  methods:{
    ...mapActions({
      removeQuestion:"removeQuestion",
      makeQuestionPublic:"makeQuestionPublic",
    })
  }
}
</script>

<style scoped>
.message-list{
  background-color: #fff;
  border-radius: 20px;
  padding: 5px;
}
</style>