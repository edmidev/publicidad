<template>
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Calendario de citas</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class='card'>
                    <div class="card-body">
                        <div class='demo-app'>
                            <div class='demo-app-main'>
                                <FullCalendar
                                    class='demo-app-calendar'
                                    :options='calendarOptions' >
                                    <template v-slot:eventContent='arg'>
                                        <b><small>{{ formatTime(arg.timeText) }}</small></b>
                                        <i>{{ arg.event.title }}</i>
                                    </template>
                                </FullCalendar>
                                <FormSchedule
                                    :selectedStartDate="selectedStartDate"
                                    :selectedEndDate="selectedEndDate"
                                    :selectedClient="selectedClient"
                                    :selectedEvent="selectedEvent"
                                    :services="services"
                                    @updateCalendar="fetchShedules"
                                    @clear="clearSelected"
                                    :event="event"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
import FormSchedule from './../form/main.vue'
import { axios } from '../../../services'

export default defineComponent({
    components: {
        FullCalendar,
        FormSchedule,
    },
    data() {
        return {
            calendar: {},
            calendarOptions: {
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin // needed for dateClick
                ],
                locale: esLocale,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                editable: false,
                initialEvents: this.events,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                    meridiem: 'short',
                },
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                /* you can update a remote database when these fire:
                eventsSet: this.handleEvents,
                eventAdd:
                eventChange:
                eventRemove:
                */
            },
            events: [],
            selectedStartDate: null,
            selectedEndDate: null,
            selectedClient: null,
            services: [],
            selectedEvent: null,
            event: {}
        }
    },
    beforeMount() {
        this.$data.calendarOptions.events =[] // this.currentEvents
    },
    methods: {
        async fetchShedules() {
            const vm = this
            const response = await axios.get('scheduler')
            const data = response.data
            vm.$data.calendarOptions.events = []
            if (data.status){
                data.data.forEach(item => {
                    vm.$data.calendarOptions.events.push({
                        id: item.id,
                        title: item.client.names,
                        start: item.start,
                        end: item.end,
                        client: item.client_id,
                        services: item.services,
                        status: item.status,
                        discount: item.discount,
                        backgroundColor: '#000'
                    })
                })
            }
        },
        handleWeekendsToggle() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
        },
        clearSelected() {
            const vm = this
            vm.selectedStartDate = null
            vm.selectedEndDate = null
            vm.selectedClient = null
            vm.services = null
            vm.selectedEvent = null
            vm.event = null
        },
        handleDateSelect(selectInfo) {
            const vm = this
            vm.selectedStartDate = selectInfo.start
            vm.selectedEndDate = selectInfo.end
            vm.selectedClient = null
            // vm.selectedService = null
            vm.selectedEvent = null
            // eslint-disable-next-line no-undef
            $('#schedule-form-modal').modal('show')
        },
        handleEventClick(clickInfo) {
            const vm = this
            // console.log(clickInfo.event.extendedProps)
            vm.selectedStartDate = clickInfo.event.start
            vm.selectedEndDate = clickInfo.event.end
            vm.selectedClient = clickInfo.event.extendedProps.client
            vm.services = clickInfo.event.extendedProps.services
            vm.selectedEvent = Number(clickInfo.event.id)
            vm.event = clickInfo.event._def
            // eslint-disable-next-line no-undef
            $('#schedule-form-modal').modal('show')
        },
        formatTime(time) {
            if (!time)
                return ''

            const timeArray = time.split(':')
            let hour = Number(timeArray[0])
            let minute = (timeArray[1]) ? timeArray[1] : '00'
            let post = (hour >= 12) ? 'p.m.' : 'a.m.'
            return hour + ':' + minute + post
        }
    },
    mounted() {
        this.fetchShedules()
    }
})

</script>

<style lang='css' scoped>
.processed  {
    background-color: green m !important;
}

h2 {
    margin: 0;
    font-size: 16px;
}

ul {
    margin: 0;
    padding: 0 0 0 1.5em;
}

li {
    margin: 1.5em 0;
    padding: 0;
}

b {
    /* used for event dates/times */
    margin-right: 3px;
}

.demo-app {
    display: flex;
    min-height: 100%;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
}

.demo-app-sidebar {
    width: 300px;
    line-height: 1.5;
    background: #eaf9ff;
    border-right: 1px solid #d3e2e8;
}

.demo-app-sidebar-section {
    padding: 2em;
}

.demo-app-main {
    flex-grow: 1;
    padding: 0;
}

.fc {
    /* the calendar root */
    max-width: 1100px;
    margin: 0 auto;
}
</style>
