<template>
    <!-- Modal -->
    <div id="therapy-detail-attend-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="therapy-detail-attend-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="document-files-form-modalLabel">{{ payload.onlyShow ? 'Detalles de la terapia' : 'Atender paciente'}}</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="row px-4 my-0">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="page-title">Detalles de la terapia</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-body py-0">
                    <div class="card">
                        <div class="card-body py-0">
                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <label for="document-file-name">Alergias</label>
                                    <textarea class="form-control" rows="3" readonly :value="payload.allergies" style="resize: none;"></textarea>
                                </div>
                                <div class="col-md">
                                    <label for="document-file-name">Motivo de la consulta</label>
                                    <textarea class="form-control" rows="3" readonly :value="payload.consultation_reason" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <label class="mb-1" for="document-file-name">Fecha</label>
                                    <input type="email" class="form-control" placeholder="" readonly
                                        v-model="payload.date">
                                </div>
                                <div class="col-md">
                                    <label class="mb-1" for="document-file-name">Terapia</label>
                                    <v-select class="nice-select default-select wide form-control"
                                        v-model="payload.therapeutic_behavior_id"
                                        placeholder="Selecciona una terapia"
                                        :options="therapyAvailables"
                                        :disabled="true"
                                        :reduce="name => name.id"
                                        label="name">
                                    </v-select>
                                    <div class="invalid-tooltip d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                                </div>
                                <div class="col-md">
                                <label class="mb-1">Sesion numero</label>
                                <input type="email" class="form-control" id="floatingInput" placeholder="" readonly
                                    v-model="payload.sessionName">
                                <div class="invalid-tooltip d-block" v-if="errors.names">{{ errors.names[0] }}</div>
                            </div>
                            </div>
                            <div class="row g-2 mb-3">
                            <div class="col-md">
                                <label class="mb-1">Observaciones</label>
                                <textarea class="form-control h-auto" id="allergies" v-model="payload.observations" :readonly="payload.onlyShow"
                                placeholder="" rows="3"></textarea>
                                <div class="invalid-tooltip d-block" v-if="errors.email">{{ errors.email[0] }}</div>
                            </div>
                        </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button"
                        v-if="!payload.onlyShow"
                        class="btn btn-primary"
                        @click="markAsAttended()">
                        Marcar como Procesado
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, onMounted } from 'vue'
import { useHelperStore } from './../../../stores'

const toast = inject('toast')
const $loading = inject('$loading')
const props = defineProps({
    client: {
        type: Object,
        default() {
            return {}
        }
    },
})

const payload = ref({})
const errors = ref({})
const therapyAvailables = ref([])
const helperStore = useHelperStore()

const fetchClientTherapies = async () => {
    try {
        const response = await axios.post(`therapeutic/behaviors/${props.client.id}/therapies`)
        const data = response.data

        if(data.status){
            therapyAvailables.value = data.data
        }
    } catch (error) {
        console.log(error)
    }
}

const markAsAttended = async () => {
    let url = 'therapy/details/'+ payload.value.id
    const loader = $loading.show()
    try {
        payload.value.status = 'processed'
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#therapy-detail-attend-modal').modal('hide')
            toast.success(data.message)
        } else {
            toast.error(data.message)
            errors.value = data.errors
        }
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

const fetchHistoryDetails = async () => {
    let url = `therapy/details/${props.client.id}/history/details`
    const loader = $loading.show()
    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            payload.value.allergies = data.data.allergies
            payload.value.consultation_reason = data.data.consultation_reason
        }
    } catch (error) {
        // console.log(error)
    } finally {
        loader.hide()
    }
}

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#therapy-detail-attend-modal').on('shown.bs.modal', function () {
        fetchClientTherapies()
        if(helperStore.selectedItem && helperStore.itemType == 'therapy-detail'){
            payload.value.id = helperStore.selectedItem.id
            payload.value.status = helperStore.selectedItem.status
            payload.value.date = helperStore.selectedItem.date
            payload.value.session_number = helperStore.selectedItem.session_number
            payload.value.therapeutic_behavior_id = helperStore.selectedItem.therapeutic_behavior_id
            payload.value.observations = helperStore.selectedItem.observations
            payload.value.sessionName = helperStore.selectedItem.session_number + '/' + helperStore.selectedItem.sessions
            payload.value.onlyShow = helperStore.selectedItem.onlyShow
            helperStore.reset()
            fetchHistoryDetails()
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        // eslint-disable-next-line no-undef
        $('#therapy-details-index-modal').modal('show')
    })
})

</script>
