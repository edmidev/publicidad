<template>
    <div>
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="page-title">Otros</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="mb-1">¿Falleció algún familiar o amigo últimamente?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.recent_deaths"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿Usted guarda algún resentimiento, ira, rencor, o tiene problemas con alguien?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.emotional_issues"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿Cómo se siente usted en su trabajo?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.job_satisfaction"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿A qué le teme usted?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.fears" placeholder=""
                                rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿Qué aspecto le produce inseguridad?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.areas_of_insecurity"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿Usted prefiere la soledad?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.preference_for_solitude"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿Cuál es su comportamiento ante las penas?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.behavior_with_sorrows"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">¿A qué causas le atribuye la desesperación que le agobia?</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.causes_of_desperation"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">Impresión diagnóstica</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.diagnostic_impression"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">Exámenes complementarios</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.complementary_exams"
                                placeholder="" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">MÉDICO QUE REFIERE</label>
                            <textarea class="form-control h-auto" id="allergies" v-model="payload.referring_physician"
                                placeholder="" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <div class="card-footer">
            <div class="card-footer">
                <ul class="list-inline wizard mb-0">
                    <li class="previous list-inline-item disabled">
                        <button type="button" class="btn btn-warning" @click="emit('stepBack')">
                        <i class="ri-arrow-left-line me-1"></i>Atras
                    </button>
                    </li>
                    <li class="next list-inline-item float-end">
                        <a @click="save" class="btn btn-info">Guardar y finalizar <i
                                class="ri-arrow-right-line ms-1"></i></a>
                    </li>
                </ul>
            </div>
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
    },
    patientSex: {
        type: String,
        default: 'M'
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
        payload.value.recent_deaths = props.clinicHistory.recent_deaths
        payload.value.emotional_issues = props.clinicHistory.emotional_issues
        payload.value.job_satisfaction = props.clinicHistory.job_satisfaction
        payload.value.fears = props.clinicHistory.fears
        payload.value.areas_of_insecurity = props.clinicHistory.areas_of_insecurity
        payload.value.preference_for_solitude = props.clinicHistory.preference_for_solitude
        payload.value.behavior_with_sorrows = props.clinicHistory.behavior_with_sorrows
        payload.value.causes_of_desperation = props.clinicHistory.causes_of_desperation
        payload.value.diagnostic_impression = props.clinicHistory.diagnostic_impression
        payload.value.complementary_exams = props.clinicHistory.complementary_exams
        payload.value.therapeutic_approach = props.clinicHistory.therapeutic_approach
        payload.value.referring_physician = props.clinicHistory.referring_physician
    }
})

</script>
