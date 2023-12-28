import emitter from './mitt'
import toast from './toast'
import $loading from './loading'

export default (app) => {
    app.use(emitter)
    app.use(toast)
    app.use($loading)
}
