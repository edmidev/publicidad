<template>
    <div class="card">
        <div class="card-header">
            <h4 class="page-title">Información del paciente</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row g-2 mb-3 flex-row-reverse">
                        <label class="mb-1">Fecha de creación</label>
                        <VueDatePicker
                            v-model="payload.created_at"
                            :format="formatDate"
                            :max-date="new Date()"
                            @update:model-value="calculateAge"
                            auto-apply
                            :enable-time-picker="false">
                        </VueDatePicker>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">Nombre y Apellidos</label>
                        <input type="email" class="form-control" id="floatingInput" placeholder="" v-model="payload.names">
                        <div class="invalid-tooltip d-block" v-if="errors.names">{{ errors.names[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">Email</label>
                        <input type="email" class="form-control" id="floatingInput" placeholder="" v-model="payload.email">
                        <div class="invalid-tooltip d-block" v-if="errors.email">{{ errors.email[0] }}</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md ">
                            <label class="mb-1">Tipo de Documento</label>
                            <v-select class="nice-select default-select wide form-control"
                                v-model="payload.document_type"
                                placeholder="Selecciona un género"
                                :options="CONST.documentTypes"
                                :reduce="name => name.id"
                                :class="(errors.document_type) ? 'invalid' : ''"
                                label="name">
                            </v-select>
                            <div class="invalid-feedback d-block" v-if="errors.document_type">{{ errors.document_type[0] }}</div>
                        </div>
                        <div class="col-md">
                            <div class="">
                                <label class="mb-1">Documento de Identidad</label>
                                <input type="text" class="form-control" id="floatingInputGrid" placeholder=""
                                    v-model="payload.document_id" :class="(errors.document_id) ? 'invalid' : ''">
                                <div class="invalid-feedback d-block" v-if="errors.document_id">{{ errors.document_id[0] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md">
                            <label class="mb-1">Grupo étnico</label>
                            <input type="text" class="form-control" id="floatingInput" placeholder=""
                                v-model="payload.ethnic_group">
                            <div class="invalid-tooltip d-block" v-if="errors.ethnic_group">{{ errors.ethnic_group[0] }}
                            </div>
                        </div>
                        <div class="col-md ">
                            <label class="mb-1">Religión</label>
                            <input type="text" class="form-control" id="floatingInput" placeholder=""
                                v-model="payload.religion">
                            <div class="invalid-tooltip d-block" v-if="errors.religion">{{ errors.religion[0] }}</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md">
                            <label class="mb-1">Teléfono (opcional)</label>
                            <input type="text" class="form-control" id="floatingInputGrid" placeholder=""
                                v-model="payload.phone">
                        </div>
                        <div class="col-md ">
                            <label class="mb-1">Sexo</label>
                            <v-select class="nice-select default-select wide form-control" v-model="payload.sex"
                                placeholder="Selecciona un rol" :options="sexList" :reduce="name => name.id" label="name">
                            </v-select>
                            <div class="invalid-tooltip d-block" v-if="errors.sex">{{ errors.sex[0] }}</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md">
                            <label class="mb-1">Fecha de Nacimientio</label>
                            <VueDatePicker v-model="payload.birthday" :format="formatDate" :max-date="new Date()"
                                @update:model-value="calculateAge" auto-apply >
                            </VueDatePicker>
                            <div class="invalid-tooltip d-block" v-if="errors.birthday">{{ errors.birthday[0] }}</div>
                        </div>
                        <div class="col-md ">
                            <label class="mb-1">Edad</label>
                            <input type="email" class="form-control" id="floatingInputGrid" placeholder=""
                                v-model="payload.age" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-body -->
        <div class="card-footer">
            <ul class="list-inline wizard mb-0">
                <li class="next list-inline-item float-end">
                    <a @click="save" class="btn btn-info">Guardar y continuar <i class="ri-arrow-right-line ms-1"></i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, defineEmits, watch } from 'vue'
import { CONST } from './../../../../services';

const toast = inject('toast')
const $loading = inject('$loading')
const emit = defineEmits(['stepForward'])

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
const sexList = ref([
    {
        id: 'M',
        name: 'Masculino'
    },
    {
        id: 'F',
        name: 'Femenino'
    },
])

const save = async () => {
    let url = '/clients'
    const loader = $loading.show()
    if (props.item)
        url += '/' + props.item.id

    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            emit('stepForward')
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

watch(() => props.item, (item) => {
    if (item.id) {
        payload.value.names = props.item.names
        payload.value.email = item.email
        payload.value.phone = item.phone
        payload.value.document_type = item.document_type
        payload.value.document_id = item.document_id
        payload.value.sex = item.sex
        payload.value.ethnic_group = item.ethnic_group
        payload.value.religion = item.religion
        payload.value.birthday = item.birthday
        payload.value.created_at = item.created_at
        calculateAge(item.birthday)
    }
})

const formatDate = (date) => {
    const day = date.getDate()
    const month = date.getMonth() + 1
    const year = date.getFullYear()

    return `${day}/${month}/${year}`
}

const calculateAge = (date = null) => {
    let birthdayMs = (date) ? new Date(date).getTime() : payload.value.birthday.getTime()
    let ageDifMs = Date.now() - birthdayMs
    let ageDate = new Date(ageDifMs)
    payload.value.age = Math.abs(ageDate.getUTCFullYear() - 1970)
}

</script>
