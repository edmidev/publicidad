<template>
    <div class="col-xxl-8 col-lg-10">
        <div class="card overflow-hidden">
            <div class="row g-0">
                <div class="col-lg-6 d-none d-lg-block p-2">
                    <img src="@/assets/images/vision.jpg" alt="" class="img-fluid rounded h-100">
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-column h-100">
                        <div class="auth-brand p-4">
                            <a href="index.html" class="logo-dark">
                                <img src="@/assets/images/logo.png" alt="dark logo" height="52">
                            </a>
                        </div>
                        <div class="p-4">
                            <h4 class="fs-20">Iniciar sesión</h4>
                            <p class="text-muted mb-3">
                                Ingrese su dirección de correo electrónico y contraseña para acceder a la cuenta.
                            </p>
                            <section>
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Correo electrónico</label>
                                    <input class="form-control" type="email" required v-model="payload.email"
                                        placeholder="Ingrese su dirección de correo electrónico" @keyup.enter="doLogin()">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input class="form-control" type="password" required v-model="payload.password"
                                        placeholder="Ingrese su contraseña" @keyup.enter="doLogin()">
                                </div>
                                <!-- <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" v-model="payload.remember">
                                        <label class="form-check-label" for="checkbox-signin">
                                            Recuérdame
                                        </label>
                                    </div>
                                </div> -->
                                <div class="mb-0 text-start">
                                    <button class="btn btn-soft-primary w-100" @click="doLogin()">
                                        <i class="ri-login-circle-fill me-1"></i>
                                        <span class="fw-bold">
                                            Iniciar sesion
                                        </span>
                                    </button>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</template>

<script setup>

import { ref, inject, onMounted } from 'vue'
import { axios } from './../../../services'
import { useUserStore } from '../../../stores'
import { useRouter } from 'vue-router'

const router = useRouter()
const userStore = useUserStore()
const toast = inject('toast')
const $loading = inject('$loading')
const payload = ref({})

const doLogin = async () => {
    const loader = $loading.show()
    try {
        const response = await axios.post('login', payload.value)
        const data = response.data
        if (data.status) {
            userStore.setToken(data.data.token)
            userStore.setUser(data.data.user)
            router.push({
                name: 'dashboard'
            })
        } else {
            toast.error(data.message)
        }
    } catch (error) {
        toast.error(error.response.data.message)
    } finally {
        loader.hide()
    }
}

onMounted(() => {
    if(userStore.token != null) {
        router.push({
            name: 'dashboard'
        })
    }
})

</script>
