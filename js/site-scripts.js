var tamHeight = 0;
var tamWidth = 0;

$(document).ready(function () {
    Inicio();

    $(window).bind('resize', function () {
        Inicio();
    });
    
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $('#calendar').datepicker({
        dateFormat: 'dd/mm/yy',
        showButtonPanel: false,
        changeMonth: false,
        changeYear: false,
        /*showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        minDate: '+1D',
        maxDate: '+3M',*/
        inline: true
    });

});

function Inicio() {

    tamWidth = $(window).width();
    tamHeight = $(window).height();

    $(".content").css('min-height', tamHeight - 74);

    if (tamWidth <= 760) {
        $("body").addClass("rps");
    } else {
        $("body").removeClass("rps");
    }

    $('.slider1').not('.slick-initialized').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: false
    });
};
