<template>
    <div class="h-screen flex justify-center items-center flex-col gap-3">
      <img src="@/assets/images/not-found.svg" alt="not-found" class="w-full max-w-lg" />
      <button @click="goBack"
        class="w-full max-w-sm h-[50px] mt-5 text-lg bg-primary text-white py-2 rounded-md hover:bg-green-700 focus-visible:outline-none">
        Go back
      </button>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue'
  import { useRouter } from 'vue-router'
  
  export default defineComponent({
    name: 'NotFound',
    setup() {
      const router = useRouter()
  
      const goBack = () => {
        // First, try to go back in browser history
        router.go(-1)
  
      }
  
      return {
        goBack
      }
    }
  })
  </script>
  
  <style scoped></style>