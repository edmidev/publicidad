<template>
    <!-- Modal -->
    <div id="schedule-form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="schedule-form-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="schedule-form-modalLabel">{{ (props.selectedEvent ? 'Editar' : 'Crear') }} Cita</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">
                    <div class="card mb-0">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="mb-1">Fecha de la cita</label>
                                        <VueDatePicker
                                            v-model="payload.start_date"
                                            auto-apply
                                            :class="(errors.start_date) ? 'invalid' : ''"
                                            :enable-time-picker="false">
                                        </VueDatePicker>
                                        <div class="invalid-feedback d-block" v-if="errors.start_date">{{ errors.start_date[0] }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="mb-1">Paciente</label>
                                        <v-select class="nice-select default-select wide form-control"
                                            v-model="payload.client_id" placeholder="Selecciona un Paciente" :options="patientsList"
                                            :reduce="name => name.id" label="names" :class="(errors.client_id) ? 'invalid' : ''"></v-select>
                                        <div class="invalid-feedback d-block" v-if="errors.client_id">{{ errors.client_id[0] }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6" v-if="client.phone">
                                    <div class="mb-3">
                                        <label class="mb-1">Teléfono</label>
                                        <input type="text" class="form-control" id="floatingInput" placeholder="" readonly
                                            v-model="client.phone" />
                                    </div>
                                </div>
                                <div class="col-lg-6" v-if="client.email">
                                    <div class="mb-3">
                                        <label class="mb-1">Correo electróncio</label>
                                        <input type="email" class="form-control" id="floatingInput" placeholder="" readonly
                                            v-model="client.email" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="mb-1">Servicio</label>
                                        <v-select class="nice-select default-select wide form-control"
                                            v-model="payload.services" placeholder="Selecciona un Servicio" :options="servicesList" multiple
                                            @option:selected="recalculatePrice" @option:deselected="recalculatePrice"
                                            :reduce="name => name.id" label="name"  :closeOnSelect="false"
                                            :class="(errors.services) ? 'invalid' : ''"></v-select>
                                        <div class="invalid-feedback d-block" v-if="errors.services">{{ errors.services[0] }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="mb-1">Precio del servicio</label>
                                        <input type="text" class="form-control" id="floatingInput" placeholder="" readonly
                                            v-model="payload.service_price" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check form-switch form-check-inline me-2">
                                        <label class="form-check-label mb-1" for="customGinoSwitch3">¿Tiene descuento?</label>
                                        <input type="checkbox" class="form-check-input" id="customGinoSwitch3"
                                            v-model="payload.haveDiscount">
                                        </div>
                                        <input type="text" class="form-control" id="floatingInput" placeholder="Descuento"
                                            v-model="payload.discount" v-if="payload.haveDiscount"/>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="mb-3">
                                        <label class="mb-1">Hora de Inicio</label>
                                        <VueDatePicker
                                            v-model="payload.start_time"
                                            time-picker mode-height="120"
                                            :is-24="false"
                                            auto-apply
                                            minutes-increment="30"
                                            :start-time="defaultTime"
                                            minutes-grid-increment="30"
                                        />
                                        <div class="invalid-tooltip d-block" v-if="errors.start_time">{{ errors.start_time[0] }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="mb-3">
                                        <label class="mb-1">Hora de Finalización</label>
                                        <VueDatePicker
                                            v-model="payload.end_time"
                                            time-picker mode-height="120"
                                            :is-24="false"
                                            auto-apply
                                            minutes-increment="30"
                                            :start-time="defaultTime"
                                            minutes-grid-increment="30"
                                        />
                                        <div class="invalid-tooltip d-block" v-if="errors.end_time">{{ errors.end_time[0] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success"
                        v-if="payload.client_id"
                        @click="showClinicHistory">
                        Mostrar historia clinica
                    </button>
                    <button type="button" class="btn btn-warning"
                        v-if="props.event?.extendedProps && props.event.extendedProps.status == 'created'"
                        @click="deleteRegister">
                        Eliminar cita
                    </button>
                    <button type="button" class="btn btn-purple"
                        v-if="props.event?.extendedProps && props.event.extendedProps.status == 'created'"
                        @click="markAsAttended">
                        Marcar como Atendido
                    </button>
                    <button type="button" class="btn btn-primary"
                        v-if="(props.event?.extendedProps && props.event.extendedProps.status == 'created') || !(props.event?.extendedProps)"
                        @click="save()">
                        {{ (props.selectedEvent ? 'Editar' : 'Crear') }} cita
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, defineEmits, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useHelperStore } from '../../../stores'

const toast = inject('toast')
const $loading = inject('$loading')
const router = useRouter()
const helperStore = useHelperStore()
const emit = defineEmits(['updateCalendar', 'clear'])
const props = defineProps({
    selectedStartDate: {
        type: Date,
        default: null
    },
    selectedEndDate: {
        type: Date,
        default: null
    },
    selectedClient: {
        type: Number,
        default: null
    },
    selectedEvent: {
        type: Number,
        default: null
    },
    event: {
        type: Object,
        default () {
            return {}
        }
    },
    services: {
        type: Array,
        default () {
            return []
        }
    },
})

const payload = ref({
    start_date: null,
    start_time: { hours: 12, minutes: 0 },
    end_time: { hours: 12, minutes: 0 }
})
const errors = ref({})
const patientsList = ref([])
const servicesList = ref([])
const defaultTime = ref({ hours: 0, minutes: 0 })

const client = computed(() => {
    const c = patientsList.value.find(p => {
        return p.id == payload.value.client_id
    })
    return (c) ? c : {}
})

const showClinicHistory = () => {
    const client = patientsList.value.find(p => {
        return p.id == payload.value.client_id
    })
    helperStore.setSelectedItem(client)
    helperStore.setItemType('patient')
    router.push({ name: 'clinic-history', params: {id: client.id}})
    // eslint-disable-next-line no-undef
    $('#schedule-form-modal').modal('hide')
}

const fetchPatients = async () => {
    let url = 'clients'
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            patientsList.value = data.data
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    }
}

const fetchServices = async () => {
    let url = 'services'
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            servicesList.value = data.data
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    }
}

const recalculatePrice = () => {
    let total = 0
    servicesList.value.forEach(s => {
        if(payload.value.services.includes(s.id)){
            total += s.price
        }
    })
    payload.value.service_price = total
}

const deleteRegister = async () => {
    let url = '/scheduler/' + props.selectedEvent
    const loader = $loading.show()
    try {
        const response = await axios.delete(url)
        const data = response.data

        if (data.status) {
            toast.success(data.message)
            emit('updateCalendar')
            // eslint-disable-next-line no-undef
            $('#schedule-form-modal').modal('hide')
        } else {
            toast.error(data.message)
        }
    } catch (error) {
        toast.error('Ocurrio un error, intente mas tarde.')
        // console.log(error)
    } finally {
        loader.hide()
    }
}

const markAsAttended = async () => {
    let url = '/scheduler/' + props.selectedEvent + '/mark/as/attended'
    const loader = $loading.show()
    try {
        const response = await axios.post(url)
        const data = response.data

        if (data.status) {
            toast.success(data.message)
            emit('updateCalendar')
            // eslint-disable-next-line no-undef
            $('#schedule-form-modal').modal('hide')
        } else {
            toast.error(data.message)
        }
    } catch (error) {
        toast.error('Ocurrio un error, intente mas tarde.')
        // console.log(error)
    } finally {
        loader.hide()
    }
}

const save = async () => {
    let url = '/scheduler'
    const loader = $loading.show()
    if (props.selectedEvent)
        url += '/' + props.selectedEvent

    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#schedule-form-modal').modal('hide')
            emit('updateCalendar')
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

const setTimes = () => {
    if(props.selectedStartDate){
        payload.value.start_time.hours = props.selectedStartDate.getHours()
        payload.value.start_time.minutes = props.selectedStartDate.getMinutes()
    }
    if(props.selectedEndDate){
        payload.value.end_time.hours = props.selectedEndDate.getHours()
        payload.value.end_time.minutes = props.selectedEndDate.getMinutes()
    }
}

onMounted(() => {
    fetchPatients()
    fetchServices()
    // eslint-disable-next-line no-undef
    $('#schedule-form-modal').on('shown.bs.modal', function () {
        if (props.selectedStartDate) {
            payload.value.start_date = props.selectedStartDate
            payload.value.client_id = props.selectedClient ?? null
            payload.value.haveDiscount = props.event?.extendedProps?.discount ? true : false
            payload.value.discount = props.event?.extendedProps?.discount ?? null
            payload.value.id = props.selectedEvent ?? null
            payload.value.services = props.services ? props.services.map(el => el.id) : []
            setTimes()
            recalculatePrice()
        }
        if (props.event){

        }
        if (props.selectedClient) {
            payload.value.client_id = props.selectedClient
        }
    }).on('hide.bs.modal', function () {
        payload.value = {
            start_time: { hours: 12, minutes: 0 },
            end_time: { hours: 12, minutes: 0 }
        }
        errors.value = {}
        emit('clear')
    })
})

</script>
