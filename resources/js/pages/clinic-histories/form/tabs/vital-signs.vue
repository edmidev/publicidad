<template>
    <div class="card">
        <div class="card-header">
            <h4 class="page-title">Signos Vitales</h4>
        </div>
        <div class="card-body">
            <div class="mt-2 row">
                <div class="mb-3 col-12 col-sm-6 col-lg-4 d-flex align-items-center">
                    <label class="mb-1">Peso</label>
                    <input type="email" class="form-control mx-2" placeholder="" v-model="payload.weight" @change="updated = true">
                    <label>Kg</label>
                </div>
                <div class="mb-3 col-12 col-sm-6 col-lg-4 d-flex align-items-center">
                    <label class="mb-1">Talla</label>
                    <input type="email" class="form-control mx-2" placeholder="" v-model="payload.size" @change="updated = true">
                    <label>cm</label>
                </div>
                <div class="mb-3 col-12 col-sm-6 col-lg-4">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-4 text-right">
                            <label class="mb-1">Presión arterial</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="email" class="form-control" placeholder="" v-model="payload.systolic" @change="updated = true">
                        </div>
                        <div class="col-12 col-md-1">
                            <label class="mx-1">/</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="email" class="form-control" placeholder="" v-model="payload.diastolic" @change="updated = true">
                        </div>
                        <div class="col-12 col-md-1">
                            <label class="mx-1">mm Hg</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-12 col-sm-6 col-lg-4 d-flex align-items-center">
                    <label class="mb-1">Temperatura</label>
                    <input type="email" class="form-control mx-2" placeholder="" v-model="payload.temperature" @change="updated = true">
                    <label class="mb-1">°C</label>
                </div>
                <div class="mb-3 col-12 col-sm-6 col-lg-4 d-flex align-items-center">
                    <label class="mb-1">Saturación</label>
                    <input type="email" class="form-control mx-2" placeholder="" v-model="payload.saturation" @change="updated = true">
                    <label class="mb-1">O2</label>
                </div>
                <div class="mb-3 col-12 col-sm-6 col-lg-4 d-flex align-items-center">
                    <label class="mb-1">Frecuencia Respiratoria</label>
                    <input type="email" class="form-control mx-2" placeholder="" v-model="payload.breathing_frequency" @change="updated = true">
                    <label class="mb-1">rpm</label>
                </div>
            </div>
        </div> <!-- end card-body -->
        <div class="card-footer">
            <ul class="list-inline wizard mb-0">
                <li class="previous list-inline-item disabled">
                    <button type="button" class="btn btn-warning" @click="emit('stepBack')">
                        <i class="ri-arrow-left-line me-1"></i>Atras
                    </button>
                </li>
                <li class="next list-inline-item float-end">
                    <button type="button" class="btn btn-warning mx-2" @click="showHistory">
                        <i class="ri-file-list-3-fill me-1"></i>Ver historial
                    </button>
                    <a @click="save" class="btn btn-info">Guardar y continuar <i class="ri-arrow-right-line ms-1"></i></a>
                </li>
            </ul>
        </div>
        <Index @showItem="showItem" :clinicHistoryId="clinicHistoryId"/>
    </div>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, defineEmits } from 'vue'
import Index from './vital-signs-index.vue'

const toast = inject('toast')
const $loading = inject('$loading')
const emit = defineEmits(['stepForward', 'stepBack'])

const props = defineProps({
    clinicHistory: {
        type: Object,
        default() {
            return {}
        }
    }
})


const payload = ref({})
const errors = ref({})
const clinicHistoryId = ref(0)
const updated = ref(false)

const save = async () => {
    if (!updated.value){
        emit('stepForward')
        return
    }

    const loader = $loading.show()
    let url = '/vital/signs'
    if (payload.value.id)
        url += '/' + payload.value.id

    try {
        payload.value.clinic_history_id = props.clinicHistory.id
        const response = await axios.post(url, payload.value)
        const data = response.data
        if (data.status) {
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

const showItem = (item) => {
    payload.value.id = item.id
    payload.value.weight = item.weight
    payload.value.size = item.size
    payload.value.systolic = item.systolic
    payload.value.diastolic = item.diastolic
    payload.value.temperature = item.temperature
    payload.value.saturation = item.saturation
    payload.value.breathing_frequency = item.breathing_frequency
}

const showHistory = () => {
    clinicHistoryId.value = props.clinicHistory.id
    // eslint-disable-next-line no-undef
    $('#measures-index-modal').modal('show')
}

</script>
