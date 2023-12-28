<template>
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="profile-bg-picture" style="background-image:url('assets/images/bg-profile.jpg')">
                    <span class="picture-bg-overlay"></span>
                    <!-- overlay -->
                </div>
                <!-- meta -->
                <div class="profile-user-box">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="profile-user-img">
                                <img :src="avatarPath" alt="" class="avatar-lg rounded-circle">
                                <div class="upload-link" title="" data-bs-toggle="tooltip"
                                    data-placement="right" data-original-title="update">
                                    <input type="file" class="update-flie d-none" ref="fileRef" @change="updateAvatar">
                                    <i style="margin-left: 80px;" class="ri-camera-switch-line pointer" @click="fileRef.click()"></i>
                                </div>
                            </div>
                            <div class="">
                                <h4 class="mt-4 fs-17 ellipsis">{{ (userStore.user) ? userStore.user.name : '' }}</h4>
                                <p class="font-13">{{ (userStore.user) ? userStore.user.roles[0].name : '' }}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <!--/ meta -->
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card p-0">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header border-bottom">
                                        <h4 class="header-title">Información de perfil</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="mb-3 col-md-6">
                                                <label for="user-name" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="user-name"
                                                    placeholder="Nombre" v-model="payload.name">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="user-email" class="form-label">E-mail</label>
                                                <input type="email" class="form-control" id="user-email"
                                                    placeholder="E-mail" v-model="payload.email">
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="mb-3 col-md-6">
                                                <label for="user-phone" class="form-label">Teléfono</label>
                                                <input type="tel" class="form-control" id="user-phone" @keypress="validatePhoneNumber"
                                                    placeholder="Teléfono" v-model="payload.phone">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="user-address" class="form-label">Dirección</label>
                                                <input type="text" class="form-control" id="user-address"
                                                    placeholder="Dirección" v-model="payload.address">
                                            </div>
                                        </div>

                                        <h4 class="header-title text-danger mb-3">Modifica estos campos si quieres cambiar la contraseña</h4>

                                        <div class="row g-2">
                                            <div class="mb-3 col-12 col-md-4">
                                                <label for="old-password" class="form-label">Contraseña actual</label>
                                                <input type="password" class="form-control" v-model="payload.old_password"
                                                    id="old-password" placeholder="Password">
                                            </div>
                                            <div class="mb-3 col-12 col-md-4">
                                                <label for="password" class="form-label">Contraseña nueva</label>
                                                <input type="password" class="form-control" v-model="payload.password"
                                                    id="password" placeholder="Password">
                                            </div>
                                            <div class="mb-3 col-12 col-md-4">
                                                <label for="repeat-password" class="form-label">Repetir contraseña</label>
                                                <input type="password" class="form-control" v-model="payload.repeat_password"
                                                    id="repeat-password" placeholder="Password">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary float-end" @click="save">Actualizar</button>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>
    <!-- end row -->
</template>

<script setup>

import { axios } from './../../services'
import { onMounted, ref, inject, computed } from 'vue'
import { useUserStore } from '../../stores'
import { CONST, validatePhoneNumber } from './../../services'

const userStore = useUserStore()
const payload = ref({})
const toast = inject('toast')
const $loading = inject('$loading')
const fileRef = ref()

onMounted(() => {
    show(userStore.user)
})

const show = (item) => {
    payload.value.name = item.name
    payload.value.email = item.email
    payload.value.address = item.address
    payload.value.phone = item.phone
}

const save = async () => {
    const loader = $loading.show()
    try {
        const response = await axios.post('update/profile', payload.value)
        const data = response.data

        if (data.status){
            toast.success(data.message)
            show(data.data)
            userStore.setUser(data.data)
        } else {
            toast.error(data.message)
        }
    } catch (error) {
        // toast.error(error.message)
    } finally {
        loader.hide()
    }
}
const updateAvatar = async(event) => {
    const loader = $loading.show()
    try {
        const localPayload = new FormData()
        localPayload.append('avatar', event.target.files[0])
        const response = await axios.post('update/avatar', localPayload, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        const data = response.data
        if (data.status) {
            toast.success(data.message)
            userStore.setAvatar(data.avatar)
        } else {
            toast.error(data.message)
        }
    } catch (error) {
        // loader.hide()
        // console.log(error)
    } finally {
        loader.hide()
    }
}

const avatarPath = computed(() => {
    return (userStore.user && userStore.user.avatar)
        ? CONST.baseUrl + 'storage/avatar/' + userStore.user.avatar
        : '/assets/images/users/avatar_perfil.jpg'
})

</script>
