<template>
  <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <slot></slot>
  </button>
  <ul class="dropdown-menu">
    <li class="dropdown-item" @click="setSelectSort(select.value)" v-for="(select,id) in selectItems" :key="id">{{select.name}}</li>
  </ul>
</template>

<script>
export default {
  props:{
    selectItems:{
      type:Array,
      required:true
    }
  },
  methods:{
    setSelectSort (option){
      this.$emit("select",option);
    }
  },
  name:"styled-select"
}
</script>

<style scoped>
  .dropdown-item{
    cursor: pointer;
  }
</style>