<template>
    <div>
        <div class="card" v-if="patientSex === 'F'">
            <div class="card-header pb-0">
                <h4 class="page-title">Antecedentes Gineco-obstétricos</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <h6 class="fs-15 mt-3 mb-2">Indique:</h6>
                        <div class="mt-2 row">
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch1">Menarquía</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch1"
                                        v-model="payload.menarche">
                                </div>
                                <div v-if="payload.menarche" style="padding-right: 10%;">
                                    <input type="text" class="form-control"  v-model="payload.menarche_date">
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch2">FUM</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch2"
                                        v-model="payload.lmp">
                                </div>
                                <div v-if="payload.lmp" style="padding-right: 10%;">
                                    <VueDatePicker
                                        v-model="payload.lmp_date"
                                        :format="formatDate"
                                        :max-date="new Date()"
                                        @update:model-value="setLMPDate"
                                        auto-apply
                                        :enable-time-picker="false">
                                    </VueDatePicker>
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch3">Gesta</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch3"
                                        v-model="payload.gestation">
                                </div>
                                <div v-if="payload.gestation">
                                    <input type="number" @keypress="validateNumber" class="form-control" placeholder="Cantidad" v-model="payload.gestation_quantity">
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch4">Parto</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch4"
                                        v-model="payload.birth">
                                </div>
                                <div v-if="payload.birth">
                                    <input type="number" @keypress="validateNumber" class="form-control" placeholder="Cantidad" v-model="payload.birth_quantity">
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch5">Aborto</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch5"
                                        v-model="payload.abortion">
                                </div>
                                <div v-if="payload.abortion">
                                    <input type="number" @keypress="validateNumber" class="form-control" placeholder="Cantidad" v-model="payload.abortion_quantity">
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch6">Horizontal</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch6"
                                        v-model="payload.horizontal">
                                </div>
                                <div v-if="payload.horizontal">
                                    <input type="number" @keypress="validateNumber" class="form-control" placeholder="Cantidad" v-model="payload.horizontal_quantity">
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                <div class="form-check form-switch form-check-inline me-2">
                                    <label class="form-check-label" for="customGinoSwitch7">Cesárea</label>
                                    <input type="checkbox" class="form-check-input" id="customGinoSwitch7"
                                        v-model="payload.c_section">
                                </div>
                                <div v-if="payload.c_section">
                                    <input type="number" @keypress="validateNumber" class="form-control" placeholder="Cantidad" v-model="payload.c_section_quantity">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Nº Hijos:</label>
                            <input type="number" class="form-control" placeholder="" @keypress="validateNumber"
                                v-model="payload.children">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Vivos:</label>
                            <input type="number" class="form-control" placeholder="" @keypress="validateNumber"
                                v-model="payload.children_live">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Muertos:</label>
                            <input type="number" class="form-control" placeholder="" @keypress="validateNumber"
                                v-model="payload.children_no_live">
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <div class="card-footer">
            <ul class="list-inline wizard mb-0">
                <li class="previous list-inline-item disabled">
                    <button type="button" class="btn btn-warning" @click="emit('stepBack')">
                        <i class="ri-arrow-left-line me-1"></i>Atras
                    </button>
                </li>
                <li class="next list-inline-item float-end">
                    <a @click="save" class="btn btn-info">Guardar y continuar <i class="ri-arrow-right-line ms-1"></i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, watch } from 'vue'

const toast = inject('toast')
const $loading = inject('$loading')
const emit = defineEmits(['stepForward', 'stepBack'])

const props = defineProps({
    clinicHistory: {
        type: Object,
        default() {
            return {}
        }
    },
    patientSex: {
        type: String,
        default: 'M'
    }
})

const setMenarcheDate = (modelData) => {
    // const date = modelData.getFullYear() + '-' + (modelData.getMonth() + 1) + '-' + modelData.getDate()
    const date = modelData.getFullYear() + '/' + (modelData.getMonth() + 1) + '/' + modelData.getDate()
    payload.value.menarche_date = date
    payload.value.menarche_date_toshow = date
}

const setLMPDate = (modelData) => {
    const date = modelData.getFullYear() + '/' + (modelData.getMonth() + 1) + '/' + modelData.getDate()
    payload.value.lmp_date = date
    payload.value.lmp_date_toshow = date
    // payload.value.lmp_date_toshow = modelData
    // payload.value.lmp_date = (new Date(modelData)).toLocaleDateString('en-GB')
}



const payload = ref({})
const errors = ref({})

const save = async () => {
    let url = '/clinic/history/' + props.clinicHistory.id
    const loader = $loading.show()
    try {
        const response = await axios.post(url, payload.value)
        const data = response.data
        if (data.status) {
            toast.success(data.message)
            emit('stepForward')
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

watch(() => props.clinicHistory, (clinicHistory) => {
    if (clinicHistory.id) {
        payload.value.menarche =  Boolean(clinicHistory.menarche)
        payload.value.menarche_date = clinicHistory.menarche_date
        payload.value.menarche_date_toshow = clinicHistory.menarche_date
        payload.value.lmp = Boolean(clinicHistory.lmp)
        payload.value.lmp_date = clinicHistory.lmp_date
        payload.value.lmp_date_toshow = clinicHistory.lmp_date
        payload.value.gestation = Boolean(clinicHistory.gestation)
        payload.value.gestation_quantity = clinicHistory.gestation_quantity
        payload.value.birth = Boolean(clinicHistory.birth)
        payload.value.birth_quantity = clinicHistory.birth_quantity
        payload.value.abortion = Boolean(clinicHistory.abortion)
        payload.value.abortion_quantity = clinicHistory.abortion_quantity
        payload.value.horizontal = Boolean(clinicHistory.horizontal)
        payload.value.horizontal_quantity = clinicHistory.horizontal_quantity
        payload.value.c_section = Boolean(clinicHistory.c_section)
        payload.value.c_section_quantity = clinicHistory.c_section_quantity
        payload.value.children = clinicHistory.children
        payload.value.children_live = clinicHistory.children_live
        payload.value.children_no_live = clinicHistory.children_no_live
    }
})

const formatDate = (date) => {
    const day = date.getDate()
    const month = date.getMonth() + 1
    const year = date.getFullYear()

    return `${day}/${month}/${year}`
}

const validateNumber = (event) => {
    if (event.keyCode < 48 || event.keyCode > 57){
        event.preventDefault()
    }
}


</script>
