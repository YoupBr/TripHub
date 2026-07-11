import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('calendar');

    if (!element) {
        return;
    }

    const calendar = new Calendar(element, {
        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
            interactionPlugin,
        ],

        locale: 'nl',
        firstDay: 1,
        nowIndicator: true,
        selectable: true,
        height: 'auto',

        initialView: window.innerWidth < 768
            ? 'listWeek'
            : 'dayGridMonth',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek',
        },

        buttonText: {
            today: 'Vandaag',
            month: 'Maand',
            week: 'Week',
            list: 'Lijst',
        },

        events: element.dataset.eventsUrl,

        dateClick(info) {
            const url = new URL(
                element.dataset.createUrl,
                window.location.origin
            );

            url.searchParams.set('starts_at', info.dateStr);

            window.location.href = url.toString();
        },

        eventClick(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
    });

    calendar.render();
});