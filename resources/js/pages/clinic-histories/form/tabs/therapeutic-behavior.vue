<template>
    <div class="card">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title">Conducta terapéutica</h4>
            <button type="button" class="btn btn-success mx-2 " @click="addTherapy()">Agregar terapia</button>
        </div>
        <div class="card-body">
            <div class="row" v-for="(therapy, index) in therapies" :key="therapy">
                <div v-if="!therapy.deleted" class="row">
                    <div class="col-12 col-md-2">
                        <div class="mb-3">
                            <label class="mb-1">Terapia</label>
                            <v-select class="nice-select default-select wide form-control" :disabled="!therapy.editable" :class="!therapy.editable ? 'bg-primary-subtle' : (errors[index]?.therapy_id) ? 'invalid' : ''"
                                v-model="therapy.therapy_id" placeholder="Selecciona una terapia" :options="therapiesLists"
                                :reduce="name => name.id" label="name"></v-select>
                            <div class="invalid-feedback d-block" v-if="errors[index]?.therapy_id">{{ errors[index]?.therapy_id[0] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="mb-3">
                            <label class="mb-1">Número de sesiones</label>
                            <input type="text" class="form-control h-auto" v-model="therapy.sessions" placeholder="" :disabled="!therapy.editable" :class="!therapy.editable ? 'bg-primary-subtle' : (errors[index]?.sessions) ? 'invalid' : ''">
                            <div class="invalid-feedback d-block" v-if="errors[index]?.sessions">{{ errors[index]?.sessions[0] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="mb-3">
                            <label class="mb-1">Concentración</label>
                            <input type="text" class="form-control h-auto" v-model="therapy.concentration" placeholder="" :disabled="!therapy.editable" :class="!therapy.editable ? 'bg-primary-subtle' : (errors[index]?.concentration) ? 'invalid' : ''">
                            <div class="invalid-feedback d-block" v-if="errors[index]?.concentration">{{ errors[index]?.concentration[0] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="mb-3">
                            <label class="mb-1">Volumen</label>
                            <input type="text" class="form-control h-auto" v-model="therapy.volume" placeholder="" :disabled="!therapy.editable" :class="!therapy.editable ? 'bg-primary-subtle' : (errors[index]?.volume) ? 'invalid' : ''">
                            <div class="invalid-feedback d-block" v-if="errors[index]?.volume">{{ errors[index]?.volume[0] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="mb-3">
                            <label class="mb-1">Frecuencia</label>
                            <input type="text" class="form-control h-auto" v-model="therapy.frecuency" placeholder="" :disabled="!therapy.editable" :class="!therapy.editable ? 'bg-primary-subtle' : (errors[index]?.frecuency) ? 'invalid' : ''">
                            <div class="invalid-feedback d-block" v-if="errors[index]?.frecuency">{{ errors[index]?.frecuency[0] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 d-flex flex-row align-items-end">
                        <div class="mb-3 align-self-center">
                            <button type="button" class="btn btn-warning mx-1" v-if="therapy.id && !therapy.editable" @click="editTherapy(index)">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-primary mx-1" v-if="(therapy.id && therapy.editable) || (!therapy.id)" @click="updateTherapy(therapy, index)">
                                <i class="ri-save-3-fill"></i>
                            </button>
                            <button type="button" class="btn btn-purple mx-1" v-if="therapy.id" @click="showHistory(therapy)">
                                <i class="ri-eye-fill "></i>
                            </button>
                            <button type="button" class="btn btn-danger mx-1" @click="deleteTherapy(therapy, index)">
                                <i class="ri-delete-bin-7-fill"></i>
                            </button>
                        </div>
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
                    <a @click="save" class="btn btn-info">Finalizar <i class="ri-arrow-right-line ms-1"></i></a>
                </li>
            </ul>
        </div>
        <History :activeHistory="activeHistory" />
    </div>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, watch, defineEmits, onMounted } from 'vue'
import History from './therapeutic-behavior-history.vue'

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

const errors = ref([])
const therapies = ref([])
const therapiesLists = ref([])
const activeHistory = ref({})

const fetchTherapies = async () => {
    let url = 'therapies'
    const loader = $loading.show()
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            therapiesLists.value = data.data
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    } finally {
        loader.hide()
    }
}

const fetchTherapeuticBehavior = async () => {
    let url = '/therapeutic/behaviors/client/' + props.clinicHistory.client_id
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            showTherapies(data.data)
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    }
}

const showTherapies = (data) => {
    therapies.value = []
    data.forEach(b => {
        therapies.value.push({
            id: b.id,
            therapy_id: b.therapy_id,
            sessions: b.sessions,
            concentration: b.concentration,
            history: b.history,
            volume: b.volume,
            frecuency: b.frecuency,
            deleted: false,
        })
    })
    addTherapy()
}

const addTherapy = () => {
    therapies.value.push({
        therapy_id: null,
        sessions: null,
        concentration: null,
        volume: null,
        frecuency: null,
        deleted: false,
        editable: true
    })
}

const editTherapy = (index) => {
    therapies.value[index].editable = true
}

const updateTherapy = async (item, index) => {
    let url = '/therapeutic/behaviors/' + props.clinicHistory.client_id
    try {
        const response = await axios.post(url, {...item})
        const data = response.data
        if (data.status) {
            toast.success(data.message)
            item.editable = false
            errors.value[index] = {}
            item.id = data.data.id
            item.history = data.data.history
        } else {
            toast.error(data.message)
            errors.value[index] = data.errors
        }
    } catch (error) {
        toast.error('Ha ocurrido un error')
    }
}

const deleteTherapy = async (item, index) => {
    if (!item.id) {
        therapies.value.splice(index, 1)
    } else {
        let url = '/therapeutic/behaviors/' + item.id
        try {
            const response = await axios.delete(url)
            const data = response.data
            if (data.status) {
                toast.success(data.message)
                therapies.value.splice(index, 1)
            } else {
                toast.error(data.message)
            }
        } catch (error) {
            toast.error('Ha ocurrido un error')
        }
    }
}

const showHistory = (therapy) => {
    // activeHistory.value.therapy = therapy
    const t = therapiesLists.value.find(t => t.id == therapy.therapy_id)
    activeHistory.value.therapy = t.name
    activeHistory.value.history = therapy.history
    // eslint-disable-next-line no-undef
    $('#active-history-modal').modal('show')
}

const save = async () => {
    emit('stepForward')
    // let url = '/therapeutic/behaviors/' + props.clinicHistory.client_id
    // try {
    //     const response = await axios.post(url, { therapies: therapies.value})
    //     const data = response.data
    //     if (data.status) {
    //         toast.success(data.message)
    //         console.log(data.data)
    //         showTherapies(data.data)
    //         emit('stepForward')
    //     } else {
    //         toast.error(data.message)
    //         errors.value = data.errors
    //     }
    // } catch (error) {
    //     toast.error('Ha ocurrido un error')
    // }
}

onMounted(async () => {
    fetchTherapies()
}),

watch(() => props.clinicHistory, (clinicHistory) => {
    if (clinicHistory.id) {
        fetchTherapeuticBehavior()
    }
})

</script>














