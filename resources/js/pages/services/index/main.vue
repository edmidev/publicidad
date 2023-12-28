<template>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Lista de servicios</h4>
                    <button type="button" class="btn btn-success" @click="selectItem()">Crear servicio</button>
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
                                    <input type="text" class="form-control" placeholder="Buscar servicio"
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
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in dataList" :key="item.id">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.price }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <span class="badge bg-warning pointer p-2 fs-5 me-1" title="Editar"
                                                @click="selectItem(item)">
                                                <i class="ri-pencil-fill"></i>
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
    </div>
</template>

<script setup>

import { axios } from './../../../services'
import { onMounted, ref, inject } from 'vue'
import Modal from './../form/main.vue'
import Paginator from './../../../components/paginator/main.vue'

const dataList = ref([])
const search = ref({})
const paginator = ref({})
const itemSelected = ref({})
const toast = inject('toast')
const $loading = inject('$loading')

const rolesList = ref([])
const fetchRoles = async () => {
    let url = 'services'
    try {
        const response = await axios.get(url)
        const data = response.data
        if (data.status) {
            rolesList.value = data.data
        }
    } catch (error) {
        // swal.fire('Ocurrio un error cargando los roles', '', 'error')
    }
}

const fetchData = async (url = 'services/search') => {
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

const selectItem = (item = null) => {
    itemSelected.value = item
    // eslint-disable-next-line no-undef
    $('#service-form-modal').modal('show')
}

const toggleRegister = async (item) => {
    const loader = $loading.show()
    try {
        const response = await axios.post('services/toggle/' + item.id)
        const data = response.data

        if (data.status) {
            toast.success(data.message)
            fetchData()
        } else {
            toast.error(data.message)
        }
    } catch (e) {
        // console.log(e)
    } finally {
        loader.hide()
    }
}

const cleanFilters = () => {
    search.value = {}
    fetchData()
}

onMounted(() => {
    fetchRoles()
    fetchData()
})

</script>
