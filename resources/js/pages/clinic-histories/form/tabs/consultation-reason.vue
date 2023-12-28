<template>
    <div class="card">
        <div class="card-header pb-0">
            <h4 class="page-title">Motivo de la consulta</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="mb-1">Motivo de la consulta</label>
                        <textarea class="form-control h-auto" v-model="payload.consultation_reason" placeholder="" rows="4" @change="updated = true"></textarea>
                    </div>
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
    </div>
    <Index @showItem="showItem" :clinicHistoryId="clinicHistoryId"/>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, watch, defineEmits } from 'vue'
import Index from './consultation-reason-index.vue'

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
    let url = '/consultation/reasons'

    if (payload.value.id)
        url += '/' + payload.value.id
    try {
        payload.value.clinic_history_id = props.clinicHistory.id
        const response = await axios.post(url, payload.value)
        const data = response.data
        if (data.status) {
            emit('stepForward')
            toast.success(data.message)
            updated.value = false
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
    payload.value.consultation_reason = item.consultation_reason
}

const showHistory = () => {
    clinicHistoryId.value = props.clinicHistory.id
    // eslint-disable-next-line no-undef
    $('#consultation-reasons-index-modal').modal('show')
}
</script>
