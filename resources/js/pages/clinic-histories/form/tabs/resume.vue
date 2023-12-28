<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <h2 class="mt-0"><i class="ri-check-double-line"></i></h2>
                    <h3 class="mt-0">¡Proceso Finalizado!</h3>

                    <p class="w-75 mb-2 mx-auto">Se han registrado todos los datos exitosamente.</p>

                    <!-- <div class="mb-3">
                        <div class="form-check d-inline-block">
                            <input type="checkbox" class="form-check-input" id="customCheck3">
                            <label class="form-check-label" for="customCheck3">I agree with
                                the
                                Terms and Conditions</label>
                        </div>
                    </div> -->
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <ul class="pager wizard mb-0 list-inline mt-1">
            <li class="previous list-inline-item disabled">
                <button type="button" class="btn btn-warning" @click="emit('stepBack')">
                    <i class="ri-arrow-left-line me-1"></i>Atras
                </button>
            </li>
            <li class="next list-inline-item float-end">
                <a  @click="emit('stepForward')" class="btn btn-info">
                    Volver al inicio
                    <i class="ri-arrow-right-line ms-1"></i>
                </a>
            </li>

        </ul>
    </div>
</template>

<script setup>

const emit = defineEmits(['stepForward', 'stepBack'])

</script>
