let tblVisit = $('#tbl-visits').DataTable({
    lengthChange: false,
    searching: false,
    language: {
        'decimal': '',
        'emptyTable': 'No hay información para mostrar',
        'info': 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
        'infoEmpty': 'Mostrando 0 to 0 of 0 entradas',
        'infoFiltered': '(Filtrado de _MAX_ total entradas)',
        'infoPostFix': '',
        'thousands': ',',
        'lengthMenu': 'Mostrar _MENU_ entradas',
        'loadingRecords': 'Cargando...',
        'processing': 'Procesando...',
        'search': 'Buscar ',
        'zeroRecords': 'Sin resultados encontrados',
        'paginate': {
            'first': 'Primero',
            'last': 'Último',
            'next': 'Siguiente',
            'previous': 'Anterior'
        }
    },
    columnDefs: [{
        targets: [6, 7, 8, 9, 10, 11],
        visible: false
    }]
});

$(function() {
    $('#tbl-visits tbody').on('click', 'tr', function() {
        var data = tblVisit.row(this).data();
        $('#aux_code').val(data[4]);
        $('#prop_code').val(data[4]);
        $('#happen_at').val(data[10]);
        $('#customer_id').val($('#visit_cust_id').val());
        $('#customer_ruc').val($('#visit_cust_ruc').val());
        $('#customer_name').val($('#visit_cust_name').val());
        $('#user_id').val(data[6]);
        $('#user_document').val(data[8]);
        $('#user_wholename').val(data[2] + ' ('+ data[7] + ')');
        $('#project_type_id').val(data[9]);
        $('#visit_id').val(data[11]);
        $('#mdl-sch-visit').modal('hide');
        updateMessage();
    });

    $('#frm-sch-visit').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.ajax({
            type: 'get',
            data: $(this).serialize(),
            url: $(this).attr('action'),
            success: function(data) {
                tblVisit.clear().draw();
                let dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.project_type_name,
                        this.user_name,
                        dateFormat(this.happen_at),
                        this.prop_code,
                        this.observation,
                        this.user_id,
                        this.user_code,
                        this.user_document,
                        this.project_type_id,
                        this.happen_at,
                        this.visit_id
                    ]);
                });
                tblVisit.rows.add(dataSet).draw();
                $('#fail-msg-visit').text('');
                $('#fail-div-visit').css('display','none');
                $('body').loadingModal('destroy');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-visit').text(JSON.stringify(msg));
                $('#fail-div-visit').css('display', 'block');
            }
        });
    });

    updateMessage();
});

function clearFormVisit() {
    $('#visit_code').val('');
    $('#visit_prop_code').val('');
    $('#visit_happen_at').val('');
    $('#visit_user_name').val('');
    $('#visit_proj_type_id').val('');
    $('#fail-msg-visit').text('');
    $('#fail-div-visit').css('display','none');
    tblVisit.clear().draw();
}

function sendOnlyCust() {
    $('#visit_id').val('');
    $('#customer_id').val($('#visit_cust_id').val());
    $('#customer_ruc').val($('#visit_cust_ruc').val());
    $('#customer_name').val($('#visit_cust_name').val());
    setProjectCode();
    updateMessage();
}

function updateMessage() {
    if ($('#customer_id').val() && !$('#visit_id').val()) {
        $('#msg_not_visit').html('<b>Nota: Propuesta sin visita previa</b>');
        $('#div_not_visit').css('display','block');
    }
    else {
        $('#msg_not_visit').text('');
        $('#div_not_visit').css('display','none');
    }
}