import { defineStore } from 'pinia'

// The object 'permissions' is an Object that contain the permission or permissions
// associated to each route

const usePermissionsStore = defineStore('permissions', () => {
    const rolesRules = {
        'articles'    : ['root', 'administrator'],
        'users'       : ['root', 'administrator'],
        'inventories' : ['root', 'administrator', 'supervisor'],
        'count-orders': ['root', 'administrator', 'supervisor', 'worker'],
        'settings'    : ['root', 'administrator'],
        'profiles'    : ['root', 'administrator', 'supervisor', 'worker']
    }

    const permissionsRules = {
        'articles-show'    : 'articles.search',
        'articles-list'    : 'articles.index',
        'users-add'        : 'users.add',
        'users-list'       : 'users.index',
        'inventories-list' : 'inventories.index',
        'count-orders-add' : 'count-orders.add',
        'count-orders-list': 'count-orders.index',
        'count-orders-fill': 'count-orders.fill',
        'profiles-edit'    : 'profile.edit',
        'settings-company' : 'settings.company',
        'settings-sync'    : 'settings.sync'
    }

    return { rolesRules, permissionsRules }
})

export default usePermissionsStore
