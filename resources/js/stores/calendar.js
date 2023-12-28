import { defineStore } from 'pinia'

const useCalendarStore = defineStore('calendar', {
    state: () => ({
        events: []
    }),
    actions: {
        addEvent(event) {
            this.events.push(event)
        },
        addEvents(schedules) {
            schedules.forEach(s => {
                this.events.push({
                    id: (new Date()).getTime(),
                    title: s.client.names,
                    start: s.start,
                    end: s.end,
                })
            })
        }
    }
})

export default useCalendarStore
