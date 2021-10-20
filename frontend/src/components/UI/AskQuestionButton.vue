<template>
  <styled-button type="button" class="m-auto" @click="$router.push('/ask-question')" >
    <span style="margin-right: 4px">Add question</span>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
      <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"></path>
    </svg>
  </styled-button>
</template>

<script>
export default {
  name: "ask-question-button"
}
</script>

<style scoped>

</style>