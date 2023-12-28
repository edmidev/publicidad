<template>
    <div>
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="page-title">Antecedentes personales</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-1">
                            <label class="mb-1" for="allergies">Alergias a medicamentos, alimentos:</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.allergies" placeholder=""
                                rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="fs-15 mt-3 mb-2">Hábitos:</h6>
                        <div class="mt-2">
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch1"
                                    v-model="payload.drink_coffe">
                                <label class="form-check-label" for="customSwitch1">Café</label>
                            </div>
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch2"
                                    v-model="payload.drink_alcohol">
                                <label class="form-check-label" for="customSwitch2">Alcohol</label>
                            </div>
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch3" v-model="payload.smoke">
                                <label class="form-check-label" for="customSwitch3">Tabaco</label>
                            </div>
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch4"
                                    v-model="payload.use_drugs">
                                <label class="form-check-label" for="customSwitch4">Drogas</label>
                            </div>
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch5"
                                    v-model="payload.use_medicine">
                                <label class="form-check-label" for="customSwitch5">Medicamentos</label>
                            </div>
                            <div class="form-check form-switch form-check-inline me-3">
                                <input type="checkbox" class="form-check-input" id="customSwitch6"
                                    v-model="payload.sleep_disorder">
                                <label class="form-check-label" for="customSwitch6">Trastornos del sueño</label>
                            </div>
                        </div>
                        <div class="mb-1 mt-3" v-if="payload.use_medicine">
                            <label class="mb-1">Medicamentos...</label>
                            <textarea class="form-control h-auto" v-model="payload.which_medicine" placeholder=""
                                rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-2 mt-3">
                            <label class="mb-1" for="bowel_movements">¿Cuántas veces hace la deposición al día?</label>
                            <input type="text" class="form-control h-auto" id="bowel_movements" v-model="payload.bowel_movements"
                                placeholder="" rows="1"/>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="page-title">Antecedentes Quirúrgicos y Patológicos</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-1">
                            <label class="mb-1">Antecedentes Quirúrgicos y Patológicos:</label>
                            <textarea class="form-control h-auto" v-model="payload.surgical_medical_history"
                                placeholder="" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="page-title">Caídas y Accidentes</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-1">
                            <label class="mb-1">Antecedentes Quirúrgicos y Patológicos:</label>
                            <textarea class="form-control h-auto" v-model="payload.falls_and_accidents"
                                placeholder="" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <div class="card-footer">
            <ul class="list-inline wizard mb-0">
                <li class="previous list-inline-item disabled">
                    <button type="button" class="btn btn-warning" @click="emit('stepBack')">
                        <i class="ri-arrow-left-line me-1"></i>Atras
                    </button>
                </li>
                <li class="next list-inline-item float-end">
                    <a @click="save" class="btn btn-info">Guardar y continuar <i class="ri-arrow-right-line ms-1"></i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

import { axios } from './../../../../services'
import { ref, inject, watch } from 'vue'

const toast = inject('toast')
const $loading = inject('$loading')
const emit = defineEmits(['stepForward', 'stepBack'])

const props = defineProps({
    clinicHistory: {
        type: Object,
        default() {
            return {}
        }
    }
})

const payload = ref({})
const errors = ref({})

const save = async () => {
    let url = '/clinic/history/' + props.clinicHistory.id
    const loader = $loading.show()
    try {
        const response = await axios.post(url, payload.value)
        const data = response.data
        if (data.status) {
            toast.success(data.message)
            emit('stepForward')
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

watch(() => props.clinicHistory, (clinicHistory) => {
    if (clinicHistory.id) {
        payload.value.allergies = props.clinicHistory.allergies
        payload.value.drink_coffe = Boolean(props.clinicHistory.drink_coffe)
        payload.value.drink_alcohol = Boolean(props.clinicHistory.drink_alcohol)
        payload.value.smoke = Boolean(props.clinicHistory.smoke)
        payload.value.use_drugs = Boolean(props.clinicHistory.use_drugs)
        payload.value.use_medicine = Boolean(props.clinicHistory.use_medicine)
        payload.value.sleep_disorder = Boolean(props.clinicHistory.sleep_disorder)
        payload.value.use_medicine = Boolean(props.clinicHistory.use_medicine)
        payload.value.which_medicine = props.clinicHistory.which_medicine
        payload.value.bowel_movements = props.clinicHistory.bowel_movements
        payload.value.surgical_medical_history = props.clinicHistory.surgical_medical_history
        payload.value.falls_and_accidents = props.clinicHistory.falls_and_accidents
    }
})

</script>
