jQuery(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.modal').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
    });

    $(".modal").on('shown.bs.modal', function () {
        $(this).find("input:visible:first").focus();
    });

    $('.autoF').focus();

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
    });

    $('.datepicker-today').datepicker({
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        changeMonth: true,
        changeYear: true,
        maxDate: 0,
        yearRange: 'c-60:c+0'
    });

    $('.datepicker-from-today').datepicker({
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        changeMonth: true,
        changeYear: true,
        minDate: 0,
    });

    $('#from').datepicker({
        changeMonth: true,
        changeYear: true,
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        dateFormat: 'yy-mm-dd',
        showAnim: 'drop',
        onSelect: function(selectedDate) {
            $("#to").datepicker("option", "minDate", selectedDate);
        }
    });
    
    $('#to').datepicker({
        changeMonth: true,
        changeYear: true,
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        dateFormat: 'yy-mm-dd',
        showAnim: 'drop',
        onSelect: function(selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
        }
    });

    $('.modal').on('show.bs.modal', function(){
        $.fn.modal.Constructor.prototype.enforceFocus = function () { };
    });
});

jQuery.fn.valInt = function() {
    if( isNaN(parseInt($(this).val())) )
        return 0;
    else
        return parseInt($(this).val());
}

jQuery.fn.valFloat = function() {
    if( isNaN(parseFloat($(this).val())) )
        return 0;
    else
        return parseFloat($(this).val());
}

jQuery.fn.reset = function () {
    $(this).each(function() {
        this.reset();
    });
}

function number_format(number, decimals, dec_point, thousands_sep)
{
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    
    var n = !isFinite(+number) ? 0 : + number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        
        return '' + (Math.round(n * k) / k).toFixed(prec);
    };
    
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    
    if (s[0].length > 3)
    {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    
    if ((s[1] || '').length < prec)
    {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    
    return s.join(dec);
}

function format_number_to_separator(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function format_number_from_separator(x) {
    return x.replace(/,/g, '');
}

function isEmail(email) {
    const emailRegex = /^[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]+)*\.[a-z]{2,}$/;
    return emailRegex.test(email);
}