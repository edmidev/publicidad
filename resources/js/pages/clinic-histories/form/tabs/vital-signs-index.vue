<template>
    <!-- Modal -->
    <div id="measures-index-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="measures-index-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="row px-4">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Historial</h4>
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
                                            <th>Fecha</th>
                                            <th>Peso</th>
                                            <th>Talla</th>
                                            <th>Presión arterial</th>
                                            <th>Temperatura</th>
                                            <th>Saturación</th>
                                            <th>Frecuencia Respiratoria</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in dataList" :key="item.id">
                                            <td>{{ new Date(item.created_at).toLocaleDateString('en-GB') }}</td>
                                            <td>{{ item.weight }}kg</td>
                                            <td>{{ item.size }}cm</td>
                                            <td>{{ item.systolic }} / {{ item.diastolic }}mm Hg</td>
                                            <td>{{ item.temperature }}°C</td>
                                            <td>{{ item.saturation }}O2</td>
                                            <td>{{ item.breathing_frequency }}rpm</td>
                                            <td>
                                                <span class="badge bg-warning pointer p-2 fs-5 me-1"
                                                    @click="edit(item)">
                                                    <i class="ri-pencil-fill"></i>
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

import { axios } from './../../../../services'
import { onMounted, ref, inject, defineEmits } from 'vue'
import Paginator from './../../../../components/paginator/main.vue'

const $loading = inject('$loading')
const dataList = ref([])
const search = ref({})
const paginator = ref({})
const toast = inject('toast')
const emit = defineEmits(['showItem'])
const props = defineProps({
    clinicHistoryId: {
        type: Number,
        default: 0
    },
})

const fetchData = async (url = 'vital/signs/search') => {
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

const edit = (item) => {
    emit('showItem', item)
    // eslint-disable-next-line no-undef
    $('#measures-index-modal').modal('hide')
}


const deleteRegister = async (item) => {
    try {
        const response = await axios.delete('vital/signs/' + item.id)
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

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#measures-index-modal').on('shown.bs.modal', function () {
        if (props.clinicHistoryId) {
            search.value.clinic_history_id = props.clinicHistoryId
            fetchData()
        }
    }).on('hide.bs.modal', function () {
        dataList.value = []
    })
})

</script>
