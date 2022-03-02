@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>{{ __('Utilitarios > Hoja de tiempo') }}</h6>
        </div>
    </div>
</div>
<div class="fila ind-desktop">
    <div class="columna columna-1">
        <h6 class="title3">{{ __('Indicaciones') }}</h6>
        <p>{{ __('Para agregar una actividad, presione una celda vacía del calendario. Si selecciona una actividad previamente ingresada, podrá visualizar su detalle, modificarla o eliminarla. Puede agregar, editar y eliminar actividades dentro de los últimos ') }} {{ $dates ?? '' }} {{ __(' días calendario y la fecha actual.') }}</p>
    </div>
</div>
<div class="fila ind-mobile">
    <div class="columna columna-1">
        <p class="more">{{ __('Para agregar una actividad, presione una celda vacía del calendario. Si selecciona una actividad previamente ingresada, podrá visualizar su detalle, modificarla o eliminarla. Puede agregar, editar y eliminar actividades dentro de los últimos ') }} {{ $dates ?? '' }} {{ __(' días calendario y la fecha actual.') }}</p>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <div id="calendar"></div>
    </div>
</div>
<div class="fila">
	<div class="space2"></div>
	<div class="columna columna-1">
		<center>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
		</center>
	</div>
</div>
@endsection

@include('activities/create')
@include('activities/edit')
@include('popups/message')
@include('popups/confirm')

@section('script')
<link rel='stylesheet' href="{{ asset('fullcalendar/css/core.min.css') }}">
<link rel='stylesheet' href="{{ asset('fullcalendar/css/daygrid.min.css') }}">
<link rel='stylesheet' href="{{ asset('fullcalendar/css/timegrid.min.css') }}">
<script src="{{ asset('fullcalendar/js/core.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/locales-all.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/interaction.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/daygrid.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/timegrid.min.js') }}"></script>
<script src="{{ asset('js/moretxt.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

var task_id = null;

$(function() {
    moment.locale('es');
    var color = '#EA5E24';
    var dates = parseInt(@json($dates));
    var begin = new Date(moment(new Date()).subtract(dates+1,'days'));
    begin.setHours(23,59,59);
    var ended = new Date(moment(new Date()).add(1,'days'));
    ended.setHours(0,0,0);
    var project = null;
    var message = 'Solo puedes action actividades dentro de los últimos '+dates+' días calendario y la fecha actual.';
    var separator = '\n---------------\n';
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        plugins: ['interaction','timeGrid','dayGrid'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: window.matchMedia("(max-width: 767px)").matches ? 540 : 570,
        locale: 'es',
        navLinks: true,
        editable: true,
        eventLimit: true,
        selectable: true,
        allDaySlot: false,
        buttonIcons: true,
        selectMirror: true,
        nowIndicator: true,
        eventOverlap: false,
        scrollTime: '09:00:00',
        defaultView: 'timeGridWeek',
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '09:00',
            endTime: '19:00',
        },
        select: (info) => {
            if (begin < info.start && info.end < ended) {
                $('#frm-create #date').text(moment(info.start).format('dddd, DD [de] MMMM'));
                $('#frm-create #aux-ini').val(moment(info.start).format());
                $('#frm-create #aux-fin').val(moment(info.end).format());
                $('#frm-create #start_at').val(moment(info.start).format('LT').padStart(5, 0));
                $('#frm-create #end_at').val(moment(info.end).format('LT').padStart(5, 0));
                $('#frm-create #project_id').val('');
                $('#frm-create #description').val('');
                $('#frm-create #comment').val('');
                $('#frm-create #color').val(color);
                $('#frm-create #aux-cod').val('');
                project = null;
                $('#frm-create #description').prop('placeholder','Descripción de la actividad');
                $('#frm-create #description').prop('disabled',false);
                $('#mdl-create #div-span-act').css('display','none');
                $('#mdl-create').modal('show');
            }
            else {
                $('#txt-detalle').html(message.replace('action','agregar'));
                $('#mdl-message').modal('show');
            }
        },
        eventClick: (info) => {
            $('#frm-edit #date').text(moment(info.event.start).format('dddd, DD [de] MMMM'));
            $('#frm-edit #aux-ini').val(moment(info.event.start).format());
            $('#frm-edit #aux-fin').val(moment(info.event.end).format());
            $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
            $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
            $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
            $('#frm-edit #description').val(info.event.extendedProps.description);
            $('#frm-edit #comment').val(info.event.extendedProps.comment);
            $('#frm-edit #color').val(info.event.backgroundColor);
            $('#frm-edit #aux-cod').val(info.event.id);
            if (begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #start_at').attr('readonly', false);
                $('#frm-edit #end_at').attr('readonly', false);
                $('#frm-edit #project_id').attr('disabled', false);
                $('#frm-edit #description').attr('readonly', false);
                $('#frm-edit #comment').attr('readonly', false)
                $('#frm-edit #color').attr('disabled', false);
                $('#lbl-titulo').text('Editar actividad');
                $('#frm-edit #btn-cancel').text('Cancelar');
                $('#frm-edit #btn-borrar').show();
                $('#frm-edit #btn-editar').show();
            }
            else {
                $('#frm-edit #start_at').attr('readonly', true);
                $('#frm-edit #end_at').attr('readonly', true);
                $('#frm-edit #project_id').attr('disabled', true);
                $('#frm-edit #description').attr('readonly', true);
                $('#frm-edit #comment').attr('readonly', true);
                $('#frm-edit #color').attr('disabled', true);
                $('#lbl-titulo').text('Detalle de la actividad');
                $('#frm-edit #btn-cancel').text('Cerrar');
                $('#frm-edit #btn-borrar').hide();
                $('#frm-edit #btn-editar').hide();
            }
            project = info.event.extendedProps.project;
            if (project.code.substr(0,12) === 'OTR000000000'){
                $('#frm-edit #description').val('');
                $('#frm-edit #description').prop('placeholder','No necesita descripción.');
                $('#frm-edit #description').prop('disabled',true);
            }
            else{
                $('#frm-edit #description').prop('placeholder','Descripción de la actividad');
                $('#frm-edit #description').prop('disabled',false);
            }
            $('#mdl-edit #div-span-act').css('display', 'none');
            $('#mdl-edit').modal('show');
        },
        eventResize: (info) => {
            if (begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #aux-ini').val(moment(info.event.start).format());
                $('#frm-edit #aux-fin').val(moment(info.event.end).format());
                $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
                $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
                $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
                $('#frm-edit #description').val(info.event.extendedProps.description);
                $('#frm-edit #comment').val(info.event.extendedProps.comment);
                $('#frm-edit #color').val(info.event.backgroundColor);
                $('#frm-edit #aux-cod').val(info.event.id);
                project = info.event.extendedProps.project;
                $('#frm-edit').submit();
            }
            else {
                info.revert();
                $('#txt-detalle').html(message.replace('action','redimensionar'));
                $('#mdl-message').modal('show');
            }
        },
        eventDrop: (info) => { // event drag and drop
            if (begin < info.oldEvent.start && info.oldEvent.end < ended && begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #aux-ini').val(moment(info.event.start).format());
                $('#frm-edit #aux-fin').val(moment(info.event.end).format());
                $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
                $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
                $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
                $('#frm-edit #description').val(info.event.extendedProps.description);
                $('#frm-edit #comment').val(info.event.extendedProps.comment);
                $('#frm-edit #color').val(info.event.backgroundColor);
                $('#frm-edit #aux-cod').val(info.event.id);
                project = info.event.extendedProps.project;
                $('#frm-edit').submit();
            }
            else {
                info.revert();
                $('#txt-detalle').html(message.replace('action','mover'));
                $('#mdl-message').modal('show');
            }
        }
    });
    
    /* 
     * Load activities to calendar
     */
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $(@json($activities)).each(function() {
        calendar.addEvent({
            id: this.id,
            title: this.project.name + (this.project.code.substr(0,12) !== 'OTR000000000' ? separator + this.description : ''),
            start: this.start_at,
            end: this.end_at,
            description: this.description,
            comment: this.comment,
            backgroundColor: this.color,
            project: this.project
        });
    });
    $('body').loadingModal('destroy');
    calendar.render();

    /* 
     * Load selected project
     */
    $('#frm-create #project_id').change(() => { 
        $.ajax({
            type: 'get',
            url: 'getProject/' + $('#frm-create #project_id').val(),
            success: (data) => {
                project = data.project;
                $('.sltdiv').remove();
                if (project.code.substr(0,12) === 'OTR000000000'){
                    $('#frm-create #description').val('');
                    $('#frm-create #description').prop('placeholder','No necesita descripción.');
                    $('#frm-create #description').prop('disabled',true);
                }
                else {
                    if (data.tasks.length) {
                        $('#frm-create #description').prop('placeholder','Descripción de la actividad');
                        $('#frm-create #description').prop('disabled',false);
                        $('#project_id').parent().parent().append(
                            '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-hand-o-right fa-2x fa-icon"></i></div>' +
                            '<div class="columna columna-10c"><select id="seltsk1" name="1" onchange="createSelect(this)" required>' +
                            '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                        $(data.tasks).each(function() {
                            var option = $(document.createElement('option'));
                            option.val(this.id);
                            option.text(this.name);
                            $('#seltsk1').append(option);
                        });
                    }
                }
            },
            error: (msg) => {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-create').modal('hide');
                $('#mdl-message').modal('show');
            }
        });
    });

    /* 
     * Load selected project
     */
    $('#frm-edit #project_id').change(() => { 
        $.ajax({
            type: 'get',
            url: 'getProject/' + $('#frm-edit #project_id').val(),
            success: (data) => {
                project = data;
                if (project.code.substr(0,12) === 'OTR000000000'){
                    $('#frm-edit #description').val('');
                    $('#frm-edit #description').prop('placeholder','No necesita descripción.');
                    $('#frm-edit #description').prop('disabled',true);
                }
                else{
                    $('#frm-edit #description').prop('placeholder','Descripción de la actividad');
                    $('#frm-edit #description').prop('disabled',false);
                }
            },
            error: (msg) => {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-edit').modal('hide');
                $('#mdl-message').modal('show');
            }
        });
    });

    /* 
     * Add event submit 
     */
    $('#frm-create').submit(function(e) {
        e.preventDefault();
        
        var hrIni = $('#frm-create #start_at').val().trim();
        var hrFin = $('#frm-create #end_at').val().trim();
        var start = $('#frm-create #aux-ini').val().substr(0, 10) + ' ' + hrIni + ':00';
        var final = $('#frm-create #aux-fin').val().substr(0, 10) + ' ' + hrFin + ':00';
        var descr = $('#frm-create #description').val().trim();
        var cmmnt = $('#frm-create #comment').val().trim();
        var color = $('#frm-create #color').val().trim();
        var isPrj = project.code.substr(0,12) !== 'OTR000000000';

        if (hrIni && hrFin && start < final && project && (descr || !isPrj)) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.post($(this).attr('action'), {
                'project_id': project.id,
                'task_id': task_id,
                'start_at': start,
                'end_at': final,
                'description': descr,
                'comment': cmmnt,
                'color': color
            })
            .done(function(data) {
                calendar.addEvent({
                    id: data.id,
                    title: project.name + (isPrj ? separator + descr : ''),
                    start: new Date(start),
                    end: new Date(final),
                    description: descr,
                    comment: cmmnt,
                    backgroundColor: color,
                    project: project
                });
                $('#mdl-create').modal('hide');
                $('body').loadingModal('destroy');
            })
            .fail(function(msg) {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
                $('#mdl-create').modal('hide');
                $('body').loadingModal('destroy');
            });
        }
        else {
            $('#msg-span-act').empty();
            if (!hrIni || !hrFin) $('#mdl-create #msg-span-act').append('<li>Debes ingresar el inicio y término de la actividad.</li>');
            if (start >= final) $('#mdl-create #msg-span-act').append('<li>La hora de inicio debe ser menor a la hora final.</li>');
            if (!project) $('#mdl-create #msg-span-act').append('<li>Debes seleccionar un proyecto.</li>');
            if (!task_id) $('#mdl-edit #msg-span-act').append('<li>Debes seleccionar una tarea.</li>');
            if (isPrj && !descr) $('#mdl-create #msg-span-act').append('<li>Debes ingresar una descripción de la actividad.</li>');
            $('#mdl-create #div-span-act').css('display', 'block');
        }
    });

    /* 
     * Update event submit
     */
    $('#frm-edit').submit(function(e) {
        e.preventDefault();

        var codex = $('#frm-edit #aux-cod').val();
        var hrIni = $('#frm-edit #start_at').val().trim();
        var hrFin = $('#frm-edit #end_at').val().trim();
        var start = $('#frm-edit #aux-ini').val().substr(0, 10) + ' ' + hrIni + ':00';
        var final = $('#frm-edit #aux-fin').val().substr(0, 10) + ' ' + hrFin + ':00';
        var descr = $('#frm-edit #description').val().trim();
        var cmmnt = $('#frm-edit #comment').val().trim();
        var color = $('#frm-edit #color').val().trim();
        var isPrj = project.code.substr(0,12) !== 'OTR000000000';

        if (hrIni && hrFin && start < final && project && (descr || !isPrj)) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.post($(this).attr('action'), {
                'id': codex,
                'project_id': project.id,
                'task_id': task_id,
                'start_at': start,
                'end_at': final,
                'description': descr,
                'comment': cmmnt,
                'color': color
            })
            .done(function(data) {
                calendar.getEventById(codex).remove();
                calendar.addEvent({
                    id: codex,
                    title: project.name + (isPrj ? separator + descr : ''),
                    start: new Date(start),
                    end: new Date(final),
                    description: descr,
                    comment: cmmnt,
                    backgroundColor: color,
                    project: project
                });
                $('#mdl-edit').modal('hide');
                $('body').loadingModal('destroy');
            })
            .fail(function(msg) {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
                $('#mdl-edit').modal('hide');
                $('body').loadingModal('destroy');
            });
        }
        else {
            $('#msg-span-act').empty();
            if (!hrIni || !hrFin) $('#mdl-edit #msg-span-act').append('<li>Debes ingresar el inicio y término de la actividad.</li>');
            if (start >= final) $('#mdl-edit #msg-span-act').append('<li>La hora de inicio debe ser menor a la hora final.</li>');
            if (!project) $('#mdl-edit #msg-span-act').append('<li>Debes seleccionar un proyecto.</li>');
            if (!task_id) $('#mdl-edit #msg-span-act').append('<li>Debes seleccionar una tarea.</li>');
            if (isPrj && !descr) $('#mdl-edit #msg-span-act').append('<li>Debes ingresar una descripción de la actividad.</li>');
            $('#mdl-edit #div-span-act').css('display', 'block');
        }
    });
    
    /* 
     * Delete event clicked
     */
    $('#btn-borrar').click(() => {
        $('#txt-confirm').html('¿Realmente desea eliminar esta actividad?');
        $('#mdl-confirm').modal('show');
        $('#mdl-edit').modal('hide');
    });

    $('#btn-yes').click(() => {
        var code = $('#frm-edit #aux-cod').val();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.ajax({
            type: 'get',
            url: 'activities.destroy/' + code ,
            success: function (data) {
                if (data) {
                    calendar.getEventById(code).remove();
                }
                else {
                    $('#txt-detalle').html('Lo sentimos, no se pudo eliminar la actividad seleccionada. Vuelva a intentarlo en unos minutos. De persistir el problema, contacte al administrador del sistema.');
                    $('#mdl-message').modal('show');
                }
                $('body').loadingModal('destroy');
            },
            error: (msg) => {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
                $('body').loadingModal('destroy');
            }
        });
    });
});
    
$('#mdl-create').on('hidden.bs.modal', function () {
    $('.sltdiv').remove();
});

function createSelect(e) {
    task_id = e.value;
    //Primero eliminamos selects innecesarios
    var divs = document.getElementsByClassName('sltdiv');
    for (var i=e.name; i<divs.length; i++)
        divs[i].remove();
    //Luego creamos el nuevo select
    var lvl = parseInt(e.name) + 1;
    $.ajax({
        type: 'get',
        url: 'tasks.getByParent/' + e.value,
        success: function(data) {
            if (data.length) {
                $(e).parent().parent().append(
                    '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-hand-o-right fa-2x fa-icon"></i></div>' +
                    '<div class="columna columna-10c"><select id="seltsk'+lvl+'" name="'+lvl+'" onchange="createSelect(this)" required>' +
                    '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                $(data).each(function() {
                    var option = $(document.createElement('option'));
                    option.val(this.id);
                    option.text(this.name);
                    $('#seltsk'+lvl).append(option);
                });
            }
        },
        error: function(msg) {
            $('#txt-detalle').html(msg.responseJSON['message']);
            $('#mdl-create').modal('hide');
            $('#mdl-message').modal('show');
        }
    });
}
</script>
@endsection