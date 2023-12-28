<template>
    <!-- Modal -->
    <div id="document-files-index-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="document-files-index-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="row px-4">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Lista de documentos</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Nombre del documento</th>
                                            <th>Paciente</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in dataList" :key="item.id">
                                            <td>{{ item.name }}</td>
                                            <td>{{ props.client.names }}</td>
                                            <td>
                                                <span class="badge bg-warning pointer p-2 fs-5 me-1"
                                                    @click="editDocument(item)">
                                                    <i class="ri-pencil-fill"></i>
                                                </span>
                                                <span class="badge bg-primary pointer p-2 fs-5 me-1">
                                                    <a :href="getDocumentPath(item)" target="_blank">
                                                        <i class="ri-external-link-line text-white"></i>
                                                    </a>
                                                </span>
                                                <span class="badge pointer p-2 fs-5 me-1"
                                                    :class="(item.deleted_at) ? 'bg-primary' : 'bg-danger'"
                                                    @click="deleteRegister(item)">
                                                    <i class="ri-delete-bin-2-line"></i>
                                                </span>
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
import { CONST } from './../../../services'

const helperStore = useHelperStore()
const dataList = ref([])
const search = ref({})
const paginator = ref({})
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

const fetchData = async (url = 'document/files/search') => {
    const loader = $loading.show()
    try {
        const response = await axios.post(url, search.value)
        const data = response.data
        paginator.value = data
        dataList.value = data.data
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

const editDocument = (item) => {
    helperStore.setSelectedItem(item)
    helperStore.setItemType('document')
    // eslint-disable-next-line no-undef
    $('#document-files-form-modal').modal('show')
    // eslint-disable-next-line no-undef
    $('#document-files-index-modal').modal('hide')
}


const deleteRegister = async (item) => {
    try {
        const response = await axios.delete('document/files/' + item.id)
        const data = response.data

        if (data.status) {
            toast.success(data.message)
            fetchData()
        } else {
            toast.error(data.message)
        }
    } catch (e) {
        console.log(e)
    }
}

const getDocumentPath = (item) => {
    return CONST.baseUrl + item.path.replace('public', 'storage')
}

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#document-files-index-modal').on('shown.bs.modal', function () {
        if (props.client) {
            search.value.client_id = props.client.id
            fetchData()
        }
    }).on('hide.bs.modal', function () {
        dataList.value = []
    })
})

</script>
