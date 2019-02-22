function dateSelected(event) {
    event.preventDefault();
    var element = event.target;
    var elems = document.getElementsByClassName('day');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        elem.classList.remove('select');
    }
    elems = document.getElementsByClassName('day-timeslots');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        elem.classList.add('is-hidden');
    }

    element.classList.add('select');
    var dayId = element.getAttribute('data-day');
    var dayTimeslotElem = document.getElementById(dayId);
    dayTimeslotElem.classList.remove('is-hidden');

    var radios = document.getElementsByName("timeslot");
    for (var i = 0; i < radios.length; i++) {
        radios[i].checked = false;
    }
    document.getElementById("confirm").disabled = true;
}

function timeslotSelected(element) {
    document.getElementById("confirm").disabled = false;
}
