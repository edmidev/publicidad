<template>
    <!-- Modal -->
    <div id="user-form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="user-form-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="user-form-modalLabel">{{ (props.item ? 'Editar' : 'Crear') }} Usuario</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md">
                                            <label class="mb-1">Nombre Completo</label>
                                            <input type="email" class="form-control" id="floatingInput" placeholder=""
                                                v-model="payload.name" :class="(errors.name) ? 'invalid' : ''" >
                                                <div class="invalid-feedback d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                                        </div>
                                        <div class="col-md">
                                            <label class="mb-1">Email</label>
                                            <input type="email" class="form-control" id="floatingInput" placeholder=""
                                                v-model="payload.email" :class="(errors.email) ? 'invalid' : ''" >
                                            <div class="invalid-feedback d-block" v-if="errors.email">{{ errors.email[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md">
                                            <label class="mb-1">Teléfono (opcional)</label>
                                            <input type="email" class="form-control" id="floatingInputGrid" @keypress="validatePhoneNumber"
                                                placeholder="" v-model="payload.phone">
                                        </div>
                                        <div class="col-md">
                                            <label class="mb-1">Dirección (opcional)</label>
                                            <input type="email" class="form-control" id="floatingInputGrid"
                                                placeholder="" v-model="payload.address">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md">
                                            <label class="mb-1">Rol</label>
                                            <v-select class="nice-select default-select wide form-control"
                                                v-model="payload.role_id" placeholder="Selecciona un rol" :options="rolesList"
                                                :reduce="name => name.id" label="name"
                                                :class="(errors.name) ? 'invalid' : ''" ></v-select>
                                            <div class="invalid-feedback d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                                        </div>
                                        <div class="col-md">
                                            <label class="mb-1">Contraseña</label>
                                            <input type="password" class="form-control" id="floatingPassword" placeholder=""
                                                v-model="payload.password" :class="(errors.password) ? 'invalid' : ''">
                                            <div class="invalid-feedback d-block" v-if="errors.password">{{ errors.password[0] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="save()">{{ (props.item ? 'Editar' : 'Crear') }} Usuario</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, defineEmits, onMounted } from 'vue'
import { validatePhoneNumber } from './../../../services'

const toast = inject('toast')
const $loading = inject('$loading')

const emit = defineEmits(['updateData', 'deselectItem'])
const props = defineProps({
    item: {
        type: Object,
        default() {
            return {}
        }
    },
})

const payload = ref({})
const errors = ref({})
const rolesList = ref([])
const fetchRoles = async () => {
    let url = 'roles'
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            rolesList.value = data.data
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    }
}

const save = async () => {
    let url = '/users'
    const loader = $loading.show()
    if (props.item)
        url += '/' + props.item.id

    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#user-form-modal').modal('hide')
            emit('updateData')
            toast.success(data.message)
        } else {
            toast.error(data.message)
            errors.value = data.errors
        }
    } catch (error) {
        toast.error('Ha ocurrido un error')
    } finally {
        loader.hide()
    }
}

onMounted(() => {
    fetchRoles()
    // eslint-disable-next-line no-undef
    $('#user-form-modal').on('shown.bs.modal', function () {
        if (props.item) {
            payload.value.name = props.item.name
            payload.value.email = props.item.email
            payload.value.phone = props.item.phone
            payload.value.address = props.item.address
            payload.value.role_id = props.item.roles[0].id
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        emit('deselectItem')
    })
})

</script>
