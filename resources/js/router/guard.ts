import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'

export function authGuard(
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext,
) {
  next()
}