<template>
  <div class="row pt-2">
    <div class="col-lg-4 mb-5">
      <h3>Users list</h3>
      <Loader v-if="isUsersLoading"/>
      <div v-else class="users-list overflow-auto">
        <div class="user mb-2" v-for="user in usersList" :key="user.id">
          <img :src="this.$root.backendImageURL + user.image" alt="logo" width="40" height="40">
          <span class="message-username">{{ user.name }}</span>
        </div>
      </div>
    </div>

    <div class="col-lg-3 mb-5">
      <h3>Categories</h3>
      <Loader v-if="isCategoriesLoading"/>
      <div v-else class="categories overflow-auto">

        <div class="category-item" v-for="category in categories" :key="category.id">
          <span>{{ category.title }}</span>
          <span @click="deleteCategoryByID(category.id)" class="close">&times;</span>
        </div>

      </div>
      <div class="input-group mb-3">
        <input type="text" v-model="title_category" class="form-control" placeholder="New category">
        <styled-button @click="addNewCategory" type="button">Add new</styled-button>
      </div>
      <div v-if="categoriesError" class="alert alert-danger" role="alert">
        {{categoriesError }}
      </div>
    </div>
  </div>
</template>

<script>
import StyledButton from "@/components/UI/StyledButton";
import {mapActions, mapGetters, mapMutations} from "vuex";
export default {
  name: "AdminPanel",
  components: {StyledButton},
  data(){
    return ({
      title_category:""
    })
  },
  mounted() {
    this.fetchCategories();
    this.fetchUsersList();
  },
  computed:{
    ...mapGetters({
      isCategoriesLoading:"isCategoriesLoading",
      isUsersLoading:"getIsUserLoading",
      usersList:"getUsersList",
      categories:"getCategories",
      categoriesError:"getCategoriesError"
    })
  },
  methods:{
    ...mapActions({
      fetchCategories:"fetchCategories",
      fetchUsersList:"fetchUsersList",
      addCategory:"addCategory",
      deleteCategory:"deleteCategory",
    }),
    ...mapMutations({
      setCategoriesError:"setCategoriesError"
    }),
    async addNewCategory(){
      if(this.title_category.length<3){
        this.setCategoriesError("Category title is too short")
      }else {
        await this.addCategory(this.title_category);
      }
    },
    deleteCategoryByID(id){
      if(confirm("Are you sure want delete it?")){
        this.deleteCategory(id);
      }
    }
  }
}
</script>

<style scoped>
.categories{
  max-height: 400px;
}
.category-item{
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #fff;
  padding:0 18px;
  margin-bottom: 5px;
  color: #5e512d;
}

.users-list{
  height: 400px;
  background-color: #fff;
  padding: 20px;
  border-radius: 20px;
}

.user{
  cursor: pointer;
  padding-bottom: 4px;

}

.close{
  font-size: 36px;
  cursor: pointer;
}
</style>