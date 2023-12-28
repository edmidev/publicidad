<template>
    <div>
        <div class="card" v-if="patientSex === 'M'">
            <div class="card-header pb-0">
                <h4 class="page-title">Descendencia</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Nº Hijos:</label>
                            <input type="number" class="form-control" placeholder=""
                                v-model="payload.children">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Vivos:</label>
                            <input type="number" class="form-control" placeholder=""
                                v-model="payload.children_live">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pe-2">
                        <div class="mb-3">
                            <label class="mb-1">Muertos:</label>
                            <input type="number" class="form-control" placeholder=""
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
        payload.value.children = clinicHistory.children
        payload.value.children_live = clinicHistory.children_live
        payload.value.children_no_live = clinicHistory.children_no_live
    }
})

</script>
