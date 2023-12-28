import Login from './../pages/auth/login/main.vue'

import Ads from './../pages/ads/index/index.vue'

import AuthLayout from './../layout/auth/main.vue'
import MainLayout from './../layout/main/main.vue'

const routes = [
    {
        path: '/',
        component: AuthLayout,
        redirect: '/',
        children: [
            {
                path: '/login',
                name: 'login',
                component: Login,
            },
        ]
    },
    {
        path: '/sistema',
        component: MainLayout,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/publicidad',
                name: 'ads',
                component: Ads,
            },
            {
                path: '/publicidad',
                name: 'dashboard',
                component: Ads,
            },
        ]
    },
]

export {
    routes
}
