document.addEventListener('DOMContentLoaded', function () {

    console.log("APP.JS CARGADO");

    let calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 650
        });

        calendar.render();
    }

});
