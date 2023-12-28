import axios from 'axios'
import { useUserStore } from './../stores'
import { createPinia } from 'pinia'
// import { inject } from 'vue'
import CONST  from './consts'

const pinia = createPinia()
const store = useUserStore(pinia)

const { reloadToken } = store

const serverUrl = CONST.baseUrl + 'api/'

const httpClient = axios.create()

httpClient.defaults.baseURL = serverUrl

const authInterceptor = (config) => {
    reloadToken()
    const { token } = store
    if (token) {
        config.headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }
    return config
}

const responseInterceptor = response => {
    return response
}

const errorInterceptor = (error) => {
    // check if it's a server error
    if (!error.response) {
        //notify.warn('Network/Server error')
        console.error('**Network/Server error')
        return Promise.reject(error)
    }

    // all the other error responses
    switch (error.response.status) {
    case 400:
        console.error(error.response.status, error.message)
        //notify.warn('Nothing to display', 'Data Not Found')
        break

    case 401: // authentication error, logout the user
        store.logout()
        window.location = '/login'
        break

    default:
        console.error(error.response.status, error.message)
        //notify.error('Server Error')
    }
    return Promise.reject(error)
}

httpClient.interceptors.request.use(authInterceptor)
httpClient.interceptors.response.use(responseInterceptor, errorInterceptor)

export default httpClient
