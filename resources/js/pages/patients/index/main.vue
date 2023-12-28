<template>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Lista de Pacientes</h4>
                    <button type="button" class="btn btn-success" @click="selectItem()">Crear paciente</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-sm-5 col-lg-4 col-xl-3 pe-2 mb-1">
                                <div>
                                    <label class="mb-1">Buscar...</label>
                                    <input type="text" class="form-control" placeholder="Buscar paciente"
                                        v-model="search.text" @keypress.enter="fetchData()">
                                </div>
                            </div>
                            <div class="col-12 col-sm-5 col-lg-4 col-xl-3 pe-2 mb-1 mt-auto">
                                <button type="button" class="btn btn-danger" @click="cleanFilters()">Limpiar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="overflow-x: scroll">
                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Nombre y apellido</th>
                                    <th>Teléfono</th>
                                    <th>Sexo</th>
                                    <th>Edad</th>
                                    <th>Fecha de creación</th>
                                    <th class="text-center">Documentos</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in dataList" :key="item.id">
                                    <td>{{ item.names }}</td>
                                    <td>{{ item.phone }}</td>
                                    <td>{{ item.sex }}</td>
                                    <td>{{ calculateAge(item.birthday) }}</td>
                                    <td>{{ new Date(item.created_at).toLocaleDateString('en-GB') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning pointer p-2 fs-5 me-1"
                                            @click="uploadDocuments(item)">
                                            Cargar Documentos
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <span class="badge bg-warning pointer p-2 fs-5 me-1" title="Editar"
                                                @click="selectItem(item)">
                                                <i class="ri-pencil-fill"></i>
                                            </span>
                                            <span class="badge bg-info pointer p-2 fs-5 me-1" title="Historia clínica"
                                                @click="showClinicHistory(item)">
                                                <i class="ri-contacts-book-line"></i>
                                            </span>
                                            <span class="badge bg-primary pointer p-2 fs-5 me-1" title="Agendar cita"
                                                @click="setSchedule(item)">
                                                <i class="ri-calendar-todo-fill"></i>
                                            </span>
                                            <span class="badge bg-secondary pointer p-2 fs-5 me-1" title="Registrar cronograma"
                                                @click="showTherapyDetails(item)">
                                                <i class="ri-calendar-check-fill"></i>
                                            </span>
                                            <span class="badge bg-purple pointer p-2 fs-5 me-1" title="Ver documentos del paciente"
                                                @click="showDocuments(item)">
                                                <i class="ri-book-3-fill"></i>
                                            </span>
                                            <span class="badge pointer p-2 fs-5 me-1"
                                                :class="(item.deleted_at) ? 'bg-primary' : 'bg-danger'"
                                                :title="(item.deleted_at) ? 'Habilitar' : 'Deshabitilitar'"
                                                @click="toggleRegister(item)">
                                                <i class="ri-delete-bin-2-line"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                    <div class="card-footer">
                        <Paginator :paginator="paginator" @updateData="fetchData"/>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div> <!-- end row-->
        <Modal :item="itemSelected" @updateData="fetchData"/>
        <ScheduleModal :selectedStartDate="new Date()" :selectedClient="selectedClient"/>
        <DocumentFilesIndex :client="itemSelected" />
        <DocumentFilesForm :client="itemSelected" />
        <TherapyDetailsIndex :client="itemSelected" />
        <TherapyDetailsForm :client="itemSelected" />
        <TherapyDetailsAttend :client="itemSelected" />
    </div>
</template>

<script setup>

import { axios } from './../../../services'
import { onMounted, ref, inject } from 'vue'
import Modal from './../form/main.vue'
import ScheduleModal from './../../schedule/form/main.vue'
import Paginator from './../../../components/paginator/main.vue'
import { useHelperStore } from '../../../stores'
import { useRouter } from 'vue-router'
import DocumentFilesIndex from './../../document-files/index/main.vue'
import DocumentFilesForm from './../../document-files/form/main.vue'
import TherapyDetailsIndex from './../../therapyDetails/index/main.vue'
import TherapyDetailsForm from './../../therapyDetails/form/main.vue'
import TherapyDetailsAttend from './../../therapyDetails/attend/main.vue'

const dataList = ref([])
const search = ref({})
const paginator = ref([])
const itemSelected = ref({})
const toast = inject('toast')
const $loading = inject('$loading')
const helperStore = useHelperStore()
const router = useRouter()
const selectedClient = ref(null)

const fetchData = async (url = 'clients/search') => {
    let text = ''
    if(helperStore.selectedItem && helperStore.itemType == 'search-client'){
        text = helperStore.selectedItem.names
        helperStore.reset()
    } else {
        text = search.value.text
    }
    const loader = $loading.show()
    try {
        const response = await axios.post(url, {text})
        const data = response.data
        paginator.value = data
        dataList.value = data.data
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

const selectItem = (item = null) => {
    itemSelected.value = item
    // eslint-disable-next-line no-undef
    $('#patient-form-modal').modal('show')
}

const setSchedule = (item = null) => {
    selectedClient.value = item.id
    // eslint-disable-next-line no-undef
    $('#schedule-form-modal').modal('show')
}

const showTherapyDetails = (item = null) => {
    itemSelected.value = item
    // eslint-disable-next-line no-undef
    $('#therapy-details-index-modal').modal('show')
}

const showClinicHistory = async (item) => {
    helperStore.setSelectedItem(item)
    helperStore.setItemType('patient')
    router.push({ name: 'clinic-history', params: { id: item.id }})
}

const toggleRegister = async (item) => {
    const loader = $loading.show()
    try {
        const response = await axios.post('clients/toggle/' + item.id)
        const data = response.data

        if (data.status) {
            toast.success(data.message)
            fetchData()
        } else {
            toast.error(data.message)
        }
    } catch (e) {
        console.log(e)
    } finally {
        loader.hide()
    }
}

const cleanFilters = () => {
    search.value = {}
    fetchData()
}

const uploadDocuments = (item) => {
    itemSelected.value = item
    // eslint-disable-next-line no-undef
    $('#document-files-form-modal').modal('show')
}

const showDocuments = (item) => {
    itemSelected.value = item
    // eslint-disable-next-line no-undef
    $('#document-files-index-modal').modal('show')
}

onMounted(() => {
    fetchData()
})

const calculateAge = (date = null) => {
    let birthdayMs = new Date(date).getTime()
    let ageDifMs = Date.now() - birthdayMs
    let ageDate = new Date(ageDifMs)
    return Math.abs(ageDate.getUTCFullYear() - 1970)
}

</script>
