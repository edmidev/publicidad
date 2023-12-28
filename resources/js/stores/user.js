import { defineStore } from 'pinia'
import { ref } from 'vue'
import CONST from './../utils/'

const useUserStore = defineStore('user', () => {
    const user = localStorage.getItem('user') === null
        ? ref(null)
        : ref(JSON.parse(localStorage.getItem('user')))

    const permissions = localStorage.getItem('permissions') === null
        ? ref(null)
        : ref(JSON.parse(localStorage.getItem('permissions')))

    const token = localStorage.getItem('token') === null
        ? ref(null)
        : ref(localStorage.getItem('token'))

    function setUser(u) {
        user.value = u
        localStorage.setItem('user', JSON.stringify(u))
    }

    function setPermissions(p) {
        permissions.value = p
        localStorage.setItem('permissions', JSON.stringify(p))
    }

    function setToken(t) {
        token.value = t
        localStorage.setItem('token', t)
    }

    function setAvatar(a) {
        user.value.avatar = a
        localStorage.setItem('user', JSON.stringify(user))
    }

    function logout() {
        user.value = null
        permissions.value = null
        token.value = null
        localStorage.removeItem('user')
        localStorage.removeItem('permissions')
        localStorage.removeItem('token')
    }

    function reloadToken() {
        token.value = (localStorage.getItem('token') === 'null' )
            ? null
            : localStorage.getItem('token')
    }

    function getAvatar() {
        if (user.value)
            return CONST.baseUrl + 'storage/avatar/' + user.value.avatar
        else
            return CONST.baseUrl + 'images/no-img-avatar.png'
    }

    function reloadFromPersistence() {
        user.value = JSON.parse(localStorage.getItem('user'))
        permissions.value = JSON.parse(localStorage.getItem('permissions'))
        token.value = (localStorage.getItem('token') == 'null') ? null : localStorage.getItem('token')
    }

    return {
        user,
        permissions,
        token,
        setUser,
        setPermissions,
        setToken,
        logout,
        reloadToken,
        reloadFromPersistence,
        setAvatar,
        getAvatar
    }
})

export default useUserStore
