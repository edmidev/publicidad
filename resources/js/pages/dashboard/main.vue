<template>
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Nuevos pacientes registrados</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class='card'>
                    <div class="card-body">
                        <div id="chart">
                            <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { axios } from '../../services'
import { ref, onMounted, inject } from 'vue'

const $loading = inject('$loading')

const series = ref([
    {
        name: 'Nuevos pacientes',
        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    }
])

const fetchReport = async () => {
    const loader = $loading.show()
    try {
        const response = await axios.post('report/yearly')
        const data = response.data

        if (data.status) {
            series.value = [
                {
                    name: 'Nuevos pacientes',
                    data: data.data
                }]
            data.data
            translateOptions()
        }
    } catch (error) {
        console.log(error)
    } finally {
        loader.hide()
    }
}

const chartOptions = {
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    },
    yaxis: {
        title: {
            text: 'Total nuevos pacientes'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + ' pacientes'
            }
        }
    }
}

const translateOptions = () => {
    setTimeout(() => {
        const downloadSVG = document.querySelector('[title="Download SVG"]') 
        downloadSVG.innerHTML = 'Descargar SVG'

        const downloadPNG = document.querySelector('[title="Download PNG"]') 
        downloadPNG.innerHTML = 'Descargar PNG'
        
        const downloadCSV = document.querySelector('[title="Download CSV"]') 
        downloadCSV.innerHTML = 'Descargar CSV'
        
    }, 1000)
}

onMounted(() => {
    fetchReport()
})

</script>
