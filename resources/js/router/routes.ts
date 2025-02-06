import type { RouteRecordRaw } from 'vue-router'
import { TICKET_ROUTE } from '../constants/routeConstants'


export const publicRoutes: RouteRecordRaw[] = [
  {
    path: `${TICKET_ROUTE}`,
    name: 'knowledge-builder',
    component: () => import('../views/TicketView.vue'),
    meta: {
      requiresAuth: true,
      layout: 'DefaultLayout',
    },
  },
]