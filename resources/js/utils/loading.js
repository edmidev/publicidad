import { useLoading } from 'vue-loading-overlay'

const $loading = useLoading({
    color: 'blue',
    loader: 'spinner',
    width: 64,
    height: 64,
    backgroundColor: '#ffffff',
    opacity: 0.5,
    zIndex: 999,
})

const install = (app) => {
    app.provide('$loading', $loading)
}

export { install as default }
