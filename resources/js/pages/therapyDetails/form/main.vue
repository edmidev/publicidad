<template>
    <!-- Modal -->
    <div id="therapy-detail-form-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="therapy-detail-form-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="document-files-form-modalLabel">{{ (payload.id ? 'Editar' : 'Crear') }}
                        cronograma</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="row px-4">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Datos del paciente</h4>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body py-0">
                        <div class="row g-2 mb-3">
                            <div class="col-md">
                                <label for="document-file-name">Nombres</label>
                                <input type="email" class="form-control" id="document-file-name" placeholder="" readonly
                                    :value="(props.client) ? props.client.names : ''">
                            </div>
                            <div class="col-md">
                                <label for="document-file-name">Email</label>
                                <input type="text" class="form-control" id="document-file-name" placeholder=""
                                    :value="(props.client) ? props.client.email : ''">
                                <div class="invalid-tooltip d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md">
                                <label for="document-file-name">Teléfono</label>
                                <input type="email" class="form-control" id="document-file-name" placeholder="" readonly
                                    :value="(props.client) ? props.client.phone : ''">
                            </div>
                            <div class="col-md">
                                <label for="document-file-name">Edad</label>
                                <input type="text" class="form-control" id="document-file-name" placeholder="" readonly
                                    :value="(props.client) ? calculateAge(props.client.birthday) : ''">
                                <div class="invalid-tooltip d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div>
                <div class="row px-4 my-0">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="page-title">Detalles de la terapia</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <label class="mb-1" for="document-file-name">Fecha</label>
                                    <VueDatePicker v-model="payload.date" auto-apply :class="(errors.date) ? 'invalid' : ''"
                                        :disabled="payload.status == 'processed'" :enable-time-picker="false">
                                    </VueDatePicker>
                                    <div class="invalid-feedback d-block" v-if="errors.date">{{ errors.date[0] }}</div>
                                </div>
                                <div class="col-md">
                                    <label class="mb-1" for="document-file-name">Terapia</label>
                                    <v-select class="nice-select default-select wide form-control"
                                        v-model="payload.therapeutic_behavior_id" placeholder="Selecciona una terapia"
                                        :options="therapyAvailables" @option:selected="calculateSessionNumber"
                                        :disabled="payload.status == 'processed'" :reduce="name => name.id" label="name"
                                        :class="(errors.therapeutic_behavior_id) ? 'invalid' : ''">
                                        <template #no-options="{  }">
                                            No hay terapias disponibles
                                        </template>
                                    </v-select>
                                    <div class="invalid-feedback d-block" v-if="errors.therapeutic_behavior_id">{{ errors.therapeutic_behavior_id[0] }}</div>
                                </div>
                                <div class="col-md">
                                    <label class="mb-1">Sesión número</label>
                                    <input type="email" class="form-control" id="floatingInput" placeholder="" readonly
                                        v-model="payload.sessionName" :class="(errors.session_number) ? 'invalid' : ''">
                                    <div class="invalid-feedback d-block" v-if="errors.session_number">{{ errors.session_number[0] }}</div>
                                </div>
                            </div>
                            <div class="row g-2 mb-3" v-if="payload.status == 'processed'">
                                <div class="col-md">
                                    <label class="mb-1">Observaciones</label>
                                    <textarea class="form-control h-auto" id="allergies" v-model="payload.observations"
                                        placeholder="" rows="3"></textarea>
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" :disabled="disableButton" @click="save()">
                        {{ (payload.id ? 'Editar' : 'Crear') }} cronograma
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
const disableButton = ref(false)
const helperStore = useHelperStore()

const fetchClientTherapies = async () => {
    try {
        const response = await axios.post(`therapeutic/behaviors/${props.client.id}/therapies`)
        const data = response.data

        if (data.status) {
            therapyAvailables.value = data.data
        }
    } catch (error) {
        console.log(error)
    }
}

const calculateSessionNumber = async (selectedOption) => {
    try {
        const response = await axios.post(`therapy/details/${selectedOption.id}/sessions`)
        const data = response.data

        if (data.status) {
            if (data.data >= selectedOption.sessions) {
                disableButton.value = true
                toast.error('Ya se han asignado todas las sesiones de esta terapia')
                payload.value.sessionName = data.data + '/' + selectedOption.sessions
            } else {
                disableButton.value = false
                payload.value.therapeutic_behavior_id = selectedOption.id
                payload.value.session_number = Number(data.data) + 1
                payload.value.sessionName = (Number(data.data) + 1) + '/' + selectedOption.sessions
            }
        }
    } catch (error) {
        console.log(error)
    }
}

const save = async () => {
    let url = 'therapy/details'
    const loader = $loading.show()
    if (payload.value.id) {
        url = url + '/' + payload.value.id
    }

    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#therapy-detail-form-modal').modal('hide')
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

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#therapy-detail-form-modal').on('shown.bs.modal', function () {
        fetchClientTherapies()
        if (helperStore.selectedItem && helperStore.itemType == 'therapy-detail') {
            console.log({...helperStore.selectedItem})
            payload.value.id = helperStore.selectedItem.id
            payload.value.status = helperStore.selectedItem.status
            payload.value.date = helperStore.selectedItem.date
            payload.value.session_number = helperStore.selectedItem.session_number
            payload.value.therapeutic_behavior_id = helperStore.selectedItem.therapeutic_behavior_id
            payload.value.observations = helperStore.selectedItem.observations
            payload.value.sessionName = helperStore.selectedItem.session_number + '/' + helperStore.selectedItem.sessions
            helperStore.reset()
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        // eslint-disable-next-line no-undef
        $('#therapy-details-index-modal').modal('show')
    })
})

const calculateAge = (date = null) => {
    let birthdayMs = new Date(date).getTime()
    let ageDifMs = Date.now() - birthdayMs
    let ageDate = new Date(ageDifMs)
    return Math.abs(ageDate.getUTCFullYear() - 1970)
}

</script>
