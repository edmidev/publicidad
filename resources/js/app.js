import './bootstrap'

import {createApp} from 'vue'

import App from './App.vue'

import vSelect  from 'vue-select'
import 'vue-select/dist/vue-select.css'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import VueApexCharts from 'vue3-apexcharts'
import {LoadingPlugin} from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css'

const app = createApp(App)
app.component('v-select', vSelect)
app.component('VueDatePicker', VueDatePicker)

import DropZone from 'dropzone-vue'
import 'dropzone-vue/dist/dropzone-vue.common.css'

import { createPinia } from 'pinia'

import router from './router'
import utils from './utils'
// import vSelect from 'vue-select';
// import 'vue-select/dist/vue-select.css';
// import { initializeApp } from 'firebase/app';
// import axios from './services/axios'

// const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(DropZone)
app.use(VueApexCharts)
app.use(LoadingPlugin)
// app.component('v-select', vSelect)

// globalComponents(app);

utils(app)

// const firebaseConfig = {
//     apiKey: 'AIzaSyDGIRTYYIUtsk3oYrUUJIJwhUi8YLtLcSU',
//     authDomain: 'slot-solutions.firebaseapp.com',
//     projectId: 'slot-solutions',
//     storageBucket: 'slot-solutions.appspot.com',
//     messagingSenderId: '192726559714',
//     appId: '1:192726559714:web:d42d2e9055a9cab37ba7bb'
// };


// const appfcm = initializeApp(firebaseConfig);

app.mount('#app')
