var submited = false;

function onSubmitButton(element) {
    if (submited)
        return;

    element.classList.add('is-loading');

    var elems = document.getElementsByClassName("button");
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        //elem.classList.add('is-loading');
        if (elem != element) {
            elem.disabled = true;
            elem.classList.add('disabled');
        }
    }

    elems = document.getElementsByName("button");
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        //elem.classList.add('is-loading');
        if (elem != element)
            elem.disabled = true;
    }

    setTimeout(function() {
        element.disabled = true;
    }, 0);

    submited = true;
}


function recalcMapInputValue(inputElementId) {
    var inputElement = document.getElementById(inputElementId);
    var elems = document.getElementsByClassName("map-key-element");
    var value = "";

    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        if (!elem.id.startsWith(inputElementId))
            continue;

        var key = elem.id.split("|")[1];
        if (elem.checked) {
            if (value != "") value += "|";
            value += key
        }
    }
    inputElement.value = value;
}
