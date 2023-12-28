<template>
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <div class="flex">
                            <a @click="exportClinicHistory" class="btn btn-info mx-1" v-if="clinicHistory.id">
                                Exportar historia clinica
                            </a>
                            <router-link :to="{ name: 'patients'}" class="btn btn-warning">
                                Regresar a pacientes
                            </router-link>
                        </div>
                    </div>
                    <h4 class="page-title" :ref="titleRef">Historia Clínica</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div id="progressbarwizard">
                                <ul class="nav nav-pills nav-justified form-wizard-header mb-3" role="tablist">
                                    <li class="nav-item" role="presentation" @click="step = 1">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 1) ? 'active' : ''">
                                            <i class="ri-account-circle-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Datos Básicos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" @click="step = 2">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 2) ? 'active' : ''">
                                            <i class="ri-profile-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Signos Vitales</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" @click="step = 3">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 3) ? 'active' : ''">
                                            <i class="ri-profile-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Motivo de la consulta</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" @click="step = 4">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 4) ? 'active' : ''">
                                            <i class="ri-check-double-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Antecedentes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" v-if="patient.sex == 'F'" @click="step = 5">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 5) ? 'active' : ''">
                                            <i class="ri-check-double-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Gineco-obstétricos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" v-if="patient.sex == 'M'" @click="step = 5">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 5) ? 'active' : ''">
                                            <i class="ri-check-double-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Descendencia</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" @click="step = 6">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 6) ? 'active' : ''">
                                            <i class="ri-check-double-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Otros</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" @click="step = 7"
                                        v-if="isDrOzono">
                                        <a class="nav-link rounded-0 py-2 pointer" :class="(step == 7) ? 'active' : ''">
                                            <i class="ri-check-double-line fw-normal fs-20 align-middle me-1"></i>
                                            <span class="d-none d-sm-inline">Conducta terapeutica</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content b-0 mb-0">

                                    <div id="bar" class="progress mb-3" style="height: 7px;">
                                        <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"
                                            :style="'width: ' + progressBarWidth"></div>
                                    </div>
                                    <div class="tab-pane" id="basic-data" role="tabpanel" :class="(step == 1) ? 'active show' : ''">
                                        <BasicData :item="patient" @stepForward="stepForward"/>
                                    </div>
                                    <div class="tab-pane" id="section-1" role="tabpanel" :class="(step == 2) ? 'active show' : ''">
                                        <VitalSigns :clinicHistory="clinicHistory" @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>

                                    <div class="tab-pane" id="section-1" role="tabpanel" :class="(step == 3) ? 'active show' : ''">
                                        <ConsultationReason :clinicHistory="clinicHistory" @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>

                                    <div class="tab-pane" id="section-2" role="tabpanel" :class="(step == 4) ? 'active show' : ''">
                                        <PersonalHistory :clinicHistory="clinicHistory" @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>
                                    <div class="tab-pane" id="section-3" role="tabpanel" v-if="patient.sex == 'F'"  :class="(step == 5) ? 'active show' : ''">
                                        <ObstetricGynecologist :clinicHistory="clinicHistory" :patientSex="patient.sex"  @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>
                                    <div class="tab-pane" id="section-3" role="tabpanel" v-if="patient.sex == 'M'"  :class="(step == 5) ? 'active show' : ''">
                                        <Decendents :clinicHistory="clinicHistory" :patientSex="patient.sex"  @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>

                                    <div class="tab-pane" id="section-4" role="tabpanel" :class="(step == 6) ? 'active show' : ''">
                                        <Others :clinicHistory="clinicHistory" @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>

                                    <div class="tab-pane" id="section-5" role="tabpanel"  :class="(step == 7) ? 'active show' : ''">
                                        <TherapeuticBehavior :clinicHistory="clinicHistory"  @stepForward="stepForward" @stepBack="stepBack"/>
                                    </div>
                                </div> <!-- tab-content -->
                            </div> <!-- end #progressbarwizard-->
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
</template>

<script setup>

import { axios } from './../../../services'
import BasicData from './tabs/basic-data.vue'
import ConsultationReason from './tabs/consultation-reason.vue'
import PersonalHistory from './tabs/personal-history.vue'
import ObstetricGynecologist from './tabs/obstetric-gynecologist.vue'
import Others from './tabs/others.vue'
import TherapeuticBehavior from './tabs/therapeutic-behavior.vue'
// import Resume from './tabs/resume.vue'
import Decendents from './tabs/decendents.vue'
import VitalSigns from './tabs/vital-signs.vue'
import { useHelperStore, useUserStore } from '../../../stores'
import { onMounted, ref, computed, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const helperStore = useHelperStore()
const userStore = useUserStore()
// const router = useRouter()
const route = useRoute()
const router = useRouter()
const patient = ref({})
const clinicHistory = ref({})
const step = ref(1)
const titleRef = ref(null)
const $loading = inject('$loading')
const isDrOzono = ref(false)

const progressBarWidth = computed(() => {
    const stepTotal = 7
    const stepSize = 100 / stepTotal
    return (step.value * stepSize) + '%'
})

const stepForward = () => {
    window.scrollTo(0,0)
    if(step.value == 7 || (!isDrOzono.value && step.value == 6))
        router.push({ name: 'patients' })
    else
        step.value += 1
}

const stepBack = () => {
    window.scrollTo(0,0)
    step.value -= 1
}

const fetchClinicHistory = async (id) => {
    const loader = $loading.show()
    try {
        const response = await axios.get('clinic/history/' + id)
        const data = response.data
        if (data.status) {
            clinicHistory.value = data.data
        }
    } catch (error) {
        // console.log(error)
    } finally {
        loader.hide()
    }
}

const fetchPatient = async (id) => {
    const loader = $loading.show()
    try {
        const response = await axios.get('clients/' + id)
        const data = response.data
        if (data.status) {
            patient.value = data.data
        }
    } catch (error) {
        // console.log(error)
    } finally {
        loader.hide()
    }
}

const exportClinicHistory = async () => {
    const loader = $loading.show()
    try {
        const response = await axios.get(`clinic/history/${clinicHistory.value.id}/export/pdf`, {
            responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'Historia clínica '+ patient.value.names +'.pdf')
        document.body.appendChild(link)
        link.click()
    } catch (error) {
        // console.log(error)
    } finally {
        loader.hide()
    }
}

onMounted(() => {
    if (!helperStore.selectedItem || helperStore.itemType != 'patient'){
        fetchPatient(route.params.id)
    }
    patient.value = helperStore.selectedItem
    fetchClinicHistory(route.params.id)
    helperStore.reset()

    if(userStore.user.roles[0].id == 1 || userStore.user.roles[0].id == 5)
        isDrOzono.value = true
})

</script>
