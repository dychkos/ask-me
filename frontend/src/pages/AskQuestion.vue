<template>
  <div class="row">
    <div class="col-md-6 offset-md-3 pt-3">
      <div class="mb-3">
        <h3>Ask a public question</h3>
      </div>
      <form class="form-auth shadow p-4" @submit.prevent="addNewQuestion">
        <div class="mb-3">
          <label for="title">Title</label>
          <input type="text" class="form-control" v-model="title" id="title" placeholder="What you want to ask?">
        </div>

        <div class="mb-3">
          <div class="form-group">
            <label for="question-body">Body</label>
            <textarea class="form-control" v-model="description" id="question-body" placeholder="Describe your problem" rows="3"></textarea>
          </div>

        </div>

        <div class="mb-3">
          <select class="form-select" v-model="category" aria-label="Default select example">
            <option value="" selected disabled hidden >Choose type of question</option>
            <option :value="category.id" :key="category.id" v-for="category in categories">{{category.title}}</option>
          </select>
        </div>

        <div v-if="errors.length>0" class="alert alert-danger" role="alert">
          {{errors[0]}}
        </div>

        <div class="d-flex justify-content-between mb-3 ">
          <styled-button type="submit" @click="this.isDraft=true" class="btn btn-styled">Save as draft</styled-button>
          <styled-button type="submit" class="btn btn-styled">Send</styled-button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import StyledButton from "@/components/UI/StyledButton";
import {mapActions, mapGetters} from "vuex";
export default {
  name: "AskQuestion",
  components: {StyledButton},
  data(){
    return {
      title:"",
      description:"",
      category: "",
      isDraft:false,
      errors:[]
    }
  },
  mounted() {
    this.fetchCategories();
    },
  computed:{
    ...mapGetters({
      categories:"getCategories",
      user:"getUserInfo"
    })
  },
  methods:{
    ...mapActions({
      fetchCategories:"fetchCategories",
      addQuestion:"addQuestion"
    }),
    addNewQuestion(){
        this.errors = [];
        this.errorCheck();


        if(this.errors.length === 0){
          let data = {
            category_id:this.category,
            title:this.title,
            isDraft:this.isDraft,
            description:this.description
          }
          try {
            this.addQuestion(data);
            this.$router.push('/user-profile');
          }catch (e){
            this.errors.push("Something went wrong!");
          }
        }

    },
    errorCheck(){
      if(this.title.length < 11){
        this.errors.push("Title length is too short");
      }else if(this.description.length < 25){
        this.errors.push("Description length is too short");
      }else if(!this.category){
        this.errors.push("Choose one of the type category")
      }
    }
  }
}
</script>

<style scoped>

</style>