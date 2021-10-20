<template>
  <Loader v-if="loading"/>
  <ul v-else class="mt-2 nav flex-column align-content-center">
    <li class="nav-item" @click="changeSelectedCategory">
      <a class="nav-link" aria-current="page"  :class="{active:selectedCategory==='All questions'}" href="#">All questions</a>
    </li>
    <li v-for="category in categories" :key="category.id"  :class="{active:selectedCategory===category.title}" class="nav-item" @click="changeSelectedCategory">
      <a class="nav-link " aria-current="page" href="#">{{category.title}}</a>
    </li>
  </ul>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";

export default {
  name:"navbar",
  props:{
    categories:{
      type:Array,
      required:true
    },
    selectedCategory:{
      type:String,
      required: true
    }
  },
  mounted() {
    this.fetchCategories();
  },
  computed:{
    ...mapGetters({
      loading:"isCategoriesLoading",
    })
  },
  methods:{
    ...mapActions({
      fetchQuestionsByCategory:"fetchQuestionsByCategory",
      fetchCategories:"fetchCategories",
    }),
    ...mapMutations({
      setSelectedCategory:"setSelectedCategory"
    }),

    changeSelectedCategory(e){
      this.setSelectedCategory(e.target.innerText);
      this.$emit("changeCategory");
    }
  }
}
</script>

<style scoped>
.nav-link{
  color: #5e512d;
  cursor: pointer;
}

.active{
  font-weight: 700;
}


</style>