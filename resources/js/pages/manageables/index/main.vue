<template>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Lista de administrables</h4>
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
                                    <th>Nombre del administrable</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Servicios</td>
                                    <td>
                                        <span class="badge bg-warning rounded-pill pointer px-2 fs-5 me-1"
                                            @click="selectManageable('services')">
                                            <i class="ri-pencil-fill"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Terapias</td>
                                    <td>
                                        <span class="badge bg-warning rounded-pill pointer px-2 fs-5 me-1"
                                            @click="selectManageable('therapies')">
                                            <i class="ri-pencil-fill"></i>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div> <!-- end row-->
    </div>
</template>

<script setup>

import { useRouter } from 'vue-router'

const router = useRouter()

const selectManageable = (name = null) => {
    router.push({ name: name})
}


</script>
