<template>
  <form class="search"  @submit.prevent="submitSearch" >
    <div class="input-group mb-3">
      <input v-model="searchText" type="text" class="form-control" placeholder="What you need?">
      <styled-button type="submit">Search</styled-button>
    </div>
  </form>
</template>

<script>
export default {
  name:"Search",
  data(){
    return {
      searchText:""
    }
  },
  methods:{
    submitSearch(){
      this.$emit("search",this.searchText);
      this.searchText="";
    }
  }
}
</script>

<style scoped>

</style>