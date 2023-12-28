import { toast, updateGlobalOptions } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

updateGlobalOptions({
    autoClose: 3000,
    closeButton: false
})

const install = (app) => {
    app.provide('toast', toast)
}

export { install as default }
