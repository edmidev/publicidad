<template>
    <!-- Modal -->
    <div id="terapy-form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="terapy-form-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="terapy-form-modalLabel">{{ (props.item ? 'Editar' : 'Crear') }} servicio</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="mb-1">Nombre</label>
                                        <input type="email" class="form-control" id="floatingInput" placeholder=""
                                            v-model="payload.name" :class="(errors.name) ? 'invalid' : ''">
                                        <div class="invalid-feedback d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1">Precio</label>
                                        <input type="numeric" class="form-control" id="floatingInput" placeholder=""
                                            v-model="payload.price" :class="(errors.price) ? 'invalid' : ''">
                                        <div class="invalid-feedback d-block" v-if="errors.price">{{ errors.price[0] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="save()">{{ (props.item ? 'Editar' : 'Crear') }} servicio</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, defineEmits, onMounted } from 'vue'

const toast = inject('toast')
const $loading = inject('$loading')
const emit = defineEmits(['updateData', 'deselectItem'])
const props = defineProps({
    item: {
        type: Object,
        default() {
            return {}
        }
    },
})

const payload = ref({})
const errors = ref({})

const save = async () => {
    let url = '/therapies'
    const loader = $loading.show()
    if (props.item)
        url += '/' + props.item.id

    try {
        const response = await axios.post(url, payload.value)
        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#terapy-form-modal').modal('hide')
            emit('updateData')
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

onMounted(() => {
    // eslint-disable-next-line no-undef
    $('#terapy-form-modal').on('shown.bs.modal', function () {
        if (props.item) {
            payload.value.name = props.item.name
            payload.value.price = props.item.price
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        emit('deselectItem')
    })
})

</script>
