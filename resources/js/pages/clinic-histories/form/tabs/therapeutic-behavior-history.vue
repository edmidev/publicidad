<template>
    <!-- Modal -->
    <div id="active-history-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="active-history-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="row px-4">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Historial de Cambios</h4>
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
                                            <th>Terapia</th>
                                            <th>Sessiones</th>
                                            <th>Concentracion</th>
                                            <th>Volumen</th>
                                            <th>Frecuencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in props.activeHistory.history" :key="item.id">
                                            <td>{{ new Date(item.created_at).toLocaleDateString('en-GB') }}</td>
                                            <td>{{ props.activeHistory.therapy }}</td>
                                            <td>{{ item.sessions }}</td>
                                            <td>{{ item.concentration }}</td>
                                            <td>{{ item.volume }}</td>
                                            <td>{{ item.frecuency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end card body-->
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

const props = defineProps({
    activeHistory: {
        type: Object,
        default() {
            return {}
        }
    },
})

</script>
