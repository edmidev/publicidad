<template>
    <!-- Modal -->
    <div id="patient-form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="patient-form-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="patient-form-modalLabel">{{ (props.item ? 'Editar' : 'Crear') }} Paciente</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
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
                                    <div class="row g-2 mb-3">
                                        <div class="col-md">
                                            <label class="mb-1">Nombre del paciente</label>
                                            <input type="email" class="form-control" id="floatingInput" placeholder=""
                                                v-model="payload.names" :class="(errors.names) ? 'invalid' : ''">
                                            <div class="invalid-feedback d-block" v-if="errors.names">{{ errors.names[0] }}</div>
                                        </div>
                                        <div class="col-md">
                                            <label class="mb-1">Email</label>
                                            <input type="email" class="form-control" id="floatingInput" placeholder=""
                                                v-model="payload.email" :class="(errors.email) ? 'invalid' : ''">
                                            <div class="invalid-feedback d-block" v-if="errors.email">{{ errors.email[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md ">
                                            <label class="mb-1">Tipo de Documento</label>
                                            <v-select class="nice-select default-select wide form-control"
                                                v-model="payload.document_type"
                                                placeholder="Selecciona un tipo de documento"
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
                                            <div class="">
                                                <label class="mb-1">Grupo étnico</label>
                                                <input type="text" class="form-control" id="floatingInput" placeholder=""
                                                    v-model="payload.ethnic_group">
                                                <div class="invalid-tooltip d-block" v-if="errors.ethnic_group">{{ errors.ethnic_group[0] }}</div>
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
                                            <div class="">
                                                <label class="mb-1">Teléfono (opcional)</label>
                                                <input type="text" class="form-control" id="floatingInputGrid" @keypress="validatePhoneNumber"
                                                    placeholder="" v-model="payload.phone">
                                            </div>
                                        </div>
                                        <div class="col-md ">
                                            <label class="mb-1">Sexo</label>
                                            <v-select class="nice-select default-select wide form-control"
                                                v-model="payload.sex"
                                                placeholder="Selecciona un género"
                                                :options="sexList"
                                                :reduce="name => name.id"
                                                :class="(errors.sex) ? 'invalid' : ''"
                                                label="name">
                                            </v-select>
                                            <div class="invalid-feedback d-block" v-if="errors.sex">{{ errors.sex[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md">
                                            <div class="">
                                                <label class="mb-1">Fecha de Nacimientio</label>
                                                <VueDatePicker
                                                    v-model="payload.birthday"
                                                    :format="formatDate"
                                                    :max-date="new Date()"
                                                    @update:model-value="calculateAge"
                                                    auto-apply
                                                    :class="(errors.birthday) ? 'invalid' : ''"
                                                    :enable-time-picker="false">
                                                </VueDatePicker>
                                                <div class="invalid-feedback d-block" v-if="errors.birthday">{{ errors.birthday[0] }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md ">
                                            <label class="mb-1">Edad</label>
                                            <input type="email" class="form-control" id="floatingInputGrid"
                                                placeholder="" v-model="payload.age" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="save()">{{ (props.item ? 'Editar' : 'Crear') }} paciente</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, defineEmits, onMounted } from 'vue'
import { CONST, validatePhoneNumber } from './../../../services'

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
            $('#patient-form-modal').modal('hide')
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
    // eslint-disable-next-line no-undef
    $('#patient-form-modal').on('shown.bs.modal', function () {
        if (props.item) {
            payload.value.names = props.item.names
            payload.value.document_type = props.item.document_type
            payload.value.document_id = props.item.document_id
            payload.value.email = props.item.email
            payload.value.phone = props.item.phone
            payload.value.sex = props.item.sex
            payload.value.ethnic_group = props.item.ethnic_group
            payload.value.religion = props.item.religion
            payload.value.birthday = props.item.birthday
            payload.value.created_at = props.item.created_at
            calculateAge(props.item.birthday)
        } else {
            payload.value.created_at = new Date()
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        emit('deselectItem')
    })
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
