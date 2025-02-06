// src/router/index.ts
import { createRouter, createWebHistory } from 'vue-router'
import { publicRoutes } from './routes'
import { authGuard } from './guard'
import { TICKET_ROUTE } from '@/constants/routeConstants'
import NotFound from '@/views/NotFound.vue'
const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL || '/'),
  routes: [
    {
      path: '/',
      redirect: TICKET_ROUTE,
    },
    ...publicRoutes,
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: NotFound,
      meta: { requiresAuth: false },
    },
  ],
})

router.beforeEach(authGuard)

export default router