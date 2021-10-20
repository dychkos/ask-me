<template >
  <div class="row">
    <div class="col-lg-10 left-side">
      <div>
        <Search @search="search"/>
        <div class="main-header">
          <div>
            <h3>Questions</h3>
            <div v-if="searchQuery" class="">
              <h5>Results by : "{{searchQuery}}"</h5>
              <styled-button @click="init" class="m-1">Back</styled-button>
            </div>
          </div>
          <div class="dropdown ">
            <styled-select :selectItems="selectItems" @select="changeSelectSort">Sort by
            <b>{{currentSort}}</b></styled-select>
          </div>

        </div>
      </div>
      <Loader v-if="isQuestionsLoading" />
      <div v-else>
        <question-list :questions="sortedQuestions"/>
        <styled-alert v-if="sortedQuestions.length===0"> Nothing found </styled-alert>
      </div>

      <div ref="observer" class="observer"></div>

    </div>
    <Sidebar>
      <ask-question-button/>
      <Navbar :categories="categories" :selectedCategory="selectedCategory" @changeCategory="changeCategory"/>
    </Sidebar>
  </div>
</template>

<script>
import QuestionList from "../components/QuestionsList";
import {mapGetters, mapMutations, mapActions} from 'vuex';
import Sidebar from "@/components/Sidebar";
import Navbar from "@/components/Navbar";
import StyledButton from "@/components/UI/StyledButton";
import Loader from "@/components/UI/Loader";

export default {
  name:"Main",
  components: {Loader,  StyledButton, Navbar, Sidebar,QuestionList},
  computed:{
    ...mapGetters({
      sortedQuestions:"sortedQuestions",
      isQuestionsLoading:'isQuestionsLoading',
      selectItems:'selectItems',
      currentSort:'getCurrentSort',
      searchQuery:'searchQuery',
      categories:"getCategories",
      selectedCategory:"getSelectedCategory"
    })
  },
  mounted() {
    this.init();
    let options = {
      rootMargin:"0px",
      threshold:1.0
    }

    const callback = (entries) => {
      if(entries[0].isIntersecting && !this.searchQuery && this.selectedCategory==="All questions"){
        this.loadMoreQuestions();
      }
    }

    const observer = new IntersectionObserver(callback,options);
    observer.observe(this.$refs.observer);
  },
  methods:{
    ...mapMutations({
      setSelectedSort:"setSelectedSort",
      setSearchQuery:"setSearchQuery"
    }),
    ...mapActions({
      init:"fetchQuestions",
      loadMoreQuestions:"loadMoreQuestions",
      searchQuestions:"searchQuestions",
      fetchQuestionsByCategory:"fetchQuestionsByCategory"
    }),

    changeSelectSort(option){
      this.setSelectedSort(option);
    },

    changeCategory(){
      if(this.selectedCategory==="All questions"){
        this.init();
        return;
      }
      this.fetchQuestionsByCategory();
    },

    toInitialQuestion(){
      this.setSearchQuery("");
      this.setSelectedSort("date");
      this.init();
    },

    search(query){
      this.setSearchQuery(query);
      this.searchQuestions();
    }
  }
}
</script>

<style >
.left-side{
  padding: 15px;
}
.main-header{
  display: flex;
  justify-content: space-between;
}

.observer{
  height: 1px;
}

</style>