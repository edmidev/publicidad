import mitt from 'mitt'

const emiter = mitt()

const install = (app) => {
    app.provide('emitter', emiter)
}

export { install as default }
