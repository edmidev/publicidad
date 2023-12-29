<template>
    <!-- Modal -->
    <div id="ads-form-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="ads-form-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="ads-form-modalLabel">{{ ( payload.id ? 'Editar' : 'Crear') }}
                        publicidad</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="name">Nombre de la publicidad</label>
                                    <input type="text" class="form-control" id="name" placeholder=""
                                        v-model="payload.name" :class="(errors.name) ? 'invalid' : ''">
                                    <div class="invalid-feedback d-block" v-if="errors.name">{{ errors.name[0] }}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="position">Posición de la publicidad</label>
                                    <v-select class="nice-select default-select wide form-control"
                                        v-model="payload.position" placeholder="Selecciona una posición" :options="positionsList"
                                        :reduce="name => name.id" label="name"
                                        :class="(errors.position) ? 'invalid' : ''" ></v-select>
                                    <div class="invalid-feedback d-block" v-if="errors.position">{{ errors.position[0] }}</div>
                                </div>
                                <div class="col-12">
                                    <label for="url">Url objetivo</label>
                                    <input type="url" class="form-control" id="url" placeholder=""
                                        v-model="payload.url" :class="(errors.url) ? 'invalid' : ''">
                                    <div class="invalid-feedback d-block" v-if="errors.url">{{ errors.url[0] }}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="url">Imagen</label>
                                    <DropZone
                                        :maxFiles="1"
                                        :uploadOnDrop="false"
                                        :multipleUpload="false"
                                        :maxFileSize="256000000"
                                        ref="dropzone"
                                        :key="dropzoneKey"
                                        :class="(errors.image) ? 'invalid' : ''"
                                        @addedFile="onFileAdd">
                                        <template v-slot:message>
                                            <br/><br/>
                                            <b>Arrastre y suelte un archivo aqui o haz clic</b>.
                                            <br/><br/><br/>
                                        </template>
                                    </DropZone>
                                </div>
                                <div class="invalid-feedback d-block" v-if="errors.image">{{ errors.image[0] }}</div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="save()">{{ ( payload.id ? 'Editar' : 'Crear') }}
                        publicidad</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script setup>

import { axios } from './../../../services'
import { ref, inject, onMounted } from 'vue'

const emit = defineEmits(['refresh'])
const toast = inject('toast')
const $loading = inject('$loading')

const props = defineProps({
    item: {
        type: Object,
        default() {
            return {}
        }
    },
})

const dropzone = ref(null)
const dropzoneKey = ref(0)
const payload = ref({})
const errors = ref({})
const positionsList = [
    {
        id: 'left',
        name: 'Izquierda'
    },
    {
        id: 'right',
        name: 'Derecha'
    },
    {
        id: 'top',
        name: 'Arriba'
    },
    {
        id: 'bottom',
        name: 'Abajo'
    },
]

const save = async () => {
    let url = '/ads'
    const loader = $loading.show()
    if (payload.value.id)
        url += '/' + payload.value.id

    try {
        const localPayload = new FormData()
        for (let key in payload.value) {
            localPayload.append(key, payload.value[key])
        }
        const response = await axios.post(url, localPayload, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        // loader.hide()
        const data = response.data
        if (data.status) {
            // eslint-disable-next-line no-undef
            $('#ads-form-modal').modal('hide')
            toast.success(data.message)
            emit('refresh')
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
    $('#ads-form-modal').on('shown.bs.modal', function () {
        if (props.item) {
            payload.value.id = props.item.id
            payload.value.name = props.item.name
            payload.value.url = props.item.url
            payload.value.position = props.item.position
            payload.value.imagePath = props.item.image
        }
    }).on('hide.bs.modal', function () {
        payload.value = {}
        errors.value = {}
        dropzoneKey.value += 1
    })
})

const onFileAdd = (event) => {
    payload.value.image = event.file
}

</script>
