import { routes } from './routes'
import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '../stores'

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { left: 0, top: 0 }
    },
})

router.beforeEach((to, from, next) => {
    const userStore = useUserStore()
    const { token } = userStore

    if (to.meta.requiresAuth && token == null) {
        next({name: 'login'})
    } else {
        next()
    }

    next()
})

export default router
