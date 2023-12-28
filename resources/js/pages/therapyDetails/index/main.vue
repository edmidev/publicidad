<template>
    <!-- Modal -->
    <div id="therapy-details-index-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="therapy-details-index-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="document-files-form-modalLabel">Cronogramas</h4>
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
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-md">
                                <label for="document-file-name">Nombres</label>
                                <input type="email" class="form-control" id="document-file-name" placeholder="" readonly
                                    :value="(props.client) ? props.client.names : ''">
                            </div>
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
                        <div class="row d-flex align-items-center">
                            <div class="col-12 col-md-3 page-title-box">
                                <h4 class="page-title">Cronograma de terapias</h4>
                            </div>
                            <div class="col-12 col-md-6 d-flex align-items-center">
                                <div class="col-12 col-md-4">
                                    <VueDatePicker v-model="search.date" auto-apply :enable-time-picker="false" @update:model-value="handleDate"
                                        placeholder="Selecciona fecha">
                                    </VueDatePicker>
                                </div>
                                <div class="col-12 col-md-4 px-2">
                                    <select class="form-select" v-model="search.status" @change="fetchData()">
                                        <option value="all" selected>Selecciona estatus</option>
                                        <option value="created">Pendiente</option>
                                        <option value="processed">Atendido</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <span class="badge bg-danger pointer p-2 fs-5 me-1"
                                        @click="clearFilters">
                                        <i class="ri-filter-off-line"></i>
                                </span>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 p-0">
                                <button type="button" class="btn btn-primary me-1" @click="exportToPdf()">Exportar</button>
                                <button type="button" class="btn btn-success" @click="showForm()">Programar terapia</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="overflow-x: scroll">
                            <div class="card-body py-0">
                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Terapia</th>
                                            <th>Sesión</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in dataList" :key="item.id">
                                            <td>{{ item.therapy_name }} {{
                                                item.concentration }}</td>
                                            <td>{{ item.session_number }}/{{ item.sessions }}</td>
                                            <td>{{ item.date }}</td>
                                            <td>
                                                <span :class="statuses[item.status].class">
                                                    {{ statuses[item.status].name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <span class="badge bg-warning pointer p-2 fs-5 me-1"
                                                        v-if="item.status == 'created'" @click="editDocument(item)">
                                                        <i class="ri-pencil-fill"></i>
                                                    </span>
                                                    <span class="badge bg-primary pointer p-2 fs-5 me-1"
                                                        v-if="item.status == 'created'" @click="markAsProcessed(item)">
                                                        <i class="ri-checkbox-line"></i>
                                                    </span>
                                                    <span class="badge bg-purple pointer p-2 fs-5 me-1"
                                                        v-if="item.status == 'processed'" @click="showObservations(item)">
                                                        <i class="ri-eye-fill"></i>
                                                    </span>
                                                    <span class="badge pointer p-2 fs-5 me-1"
                                                        v-if="item.status == 'created'"
                                                        :class="(item.deleted_at) ? 'bg-primary' : 'bg-danger'"
                                                        @click="deleteRegister(item)">
                                                        <i class="ri-delete-bin-2-line"></i>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end card body-->
                            <div class="card-footer">
                                <Paginator :paginator="paginator" @updateData="fetchData" />
                            </div>
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div> <!-- end row-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { onMounted, ref, inject } from 'vue'
import Paginator from './../../../components/paginator/main.vue'
import { useHelperStore } from '../../../stores'

const helperStore = useHelperStore()
const dataList = ref([])
const search = ref({ status: 'all' })
const errors = ref({})
const paginator = ref({})
const statuses = {
    created: {
        name: 'Pendiente',
        class: 'badge bg-warning'
    },
    processed: {
        name: 'Atendido',
        class: 'badge bg-primary'
    }
}
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

// eslint-disable-next-line no-unused-vars
const handleDate = (modelData) => {
    fetchData()
}

const clearFilters = () => {
    search.value = { status: 'all' }
    fetchData()
}


const fetchData = async (url = 'therapy/details/client/' + props.client.id) => {
    const loader = $loading.show()
    try {
        const response = await axios.post(url, search.value)
        const data = response.data

        if (data.status) {
            dataList.value = data.data
        }
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

const showForm = () => {
    // eslint-disable-next-line no-undef
    $('#therapy-detail-form-modal').modal('show')
    // eslint-disable-next-line no-undef
    $('#therapy-details-index-modal').modal('hide')
}

const editDocument = (item) => {
    helperStore.setSelectedItem(item)
    helperStore.setItemType('therapy-detail')
    showForm()
}

const markAsProcessed = (item) => {
    item.onlyShow = false
    helperStore.setSelectedItem(item)
    helperStore.setItemType('therapy-detail')
    // eslint-disable-next-line no-undef
    $('#therapy-detail-attend-modal').modal('show')
    // eslint-disable-next-line no-undef
    $('#therapy-details-index-modal').modal('hide')
}

const showObservations = (item) => {
    item.onlyShow = true
    helperStore.setSelectedItem(item)
    helperStore.setItemType('therapy-detail')
    // eslint-disable-next-line no-undef
    $('#therapy-detail-attend-modal').modal('show')
    // eslint-disable-next-line no-undef
    $('#therapy-details-index-modal').modal('hide')
}

const deleteRegister = async (item) => {
    const loader = $loading.show()
    try {
        const response = await axios.delete('therapy/details/' + item.id)
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

const exportToPdf = async () => {
    const loader = $loading.show()
    try {
        const response = await axios.get(`therapy/details/client/${props.client.id}/export/pdf`, {
            responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'Cronograma '+ props.client.names +'.pdf')
        document.body.appendChild(link)
        link.click()
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#therapy-details-index-modal').on('shown.bs.modal', function () {
        fetchData()
        // if (props.client) {
        //     search.value.client_id = props.client.id
        //     fetchData()
        // }
    }).on('hide.bs.modal', function () {
        dataList.value = []
    })
})

const calculateAge = (date = null) => {
    let birthdayMs = new Date(date).getTime()
    let ageDifMs = Date.now() - birthdayMs
    let ageDate = new Date(ageDifMs)
    return Math.abs(ageDate.getUTCFullYear() - 1970)
}

</script>
