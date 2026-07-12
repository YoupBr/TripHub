import './bootstrap';

import Alpine from 'alpinejs';
import './calendar';

window.Alpine = Alpine;

Alpine.start();

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', () => {
    const calendarElement = document.getElementById('calendar');

    if (!calendarElement) {
        return;
    }

    const calendar = new Calendar(calendarElement, {
        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
            interactionPlugin,
        ],

        locale: 'nl',
        firstDay: 1,
        height: 'auto',

        initialView: window.innerWidth < 640
            ? 'listMonth'
            : 'dayGridMonth',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth',
        },

        buttonText: {
            today: 'Vandaag',
            month: 'Maand',
            week: 'Week',
            list: 'Lijst',
        },

        events: window.calendarEventsUrl,

        eventClick(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },

        eventDidMount(info) {
            const location = info.event.extendedProps.location;

            if (location) {
                info.el.title = `${info.event.title} – ${location}`;
            }
        },
    });

    calendar.render();
});