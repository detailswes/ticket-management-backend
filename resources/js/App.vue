<template>
    <div class="min-h-screen flex flex-col relative">
        <div class="flex-1 h-full md:h-auto">
          <RouterView />
        </div>
      </div>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue'
  import { useRoute } from 'vue-router'
  
  const route = useRoute()
  computed(() => {
    const authRoutes = ['sign-in', 'sign-up']
    return authRoutes.includes(route.name as string) || route.name === 'not-found'
  }) 
  </script>
