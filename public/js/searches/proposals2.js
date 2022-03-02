let tblProp = $('#tbl-proposals').DataTable({
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
    $('#tbl-proposals tbody').on('click', 'tr', function() {
        var data = tblProp.row(this).data();
        $('#aux_code').val(data[0]);
        $('#prop_code').val(data[0]);
        $('#happen_at').val(data[10]);
        $('#name').val(data[2]);
        $('#customer_id').val($('#prop_cust_id').val());
        $('#customer_ruc').val($('#prop_cust_ruc').val());
        $('#customer_name').val($('#prop_cust_name').val());
        $('#user_id').val(data[6]);
        $('#user_document').val(data[8]);
        $('#user_wholename').val(data[4] + ' (' + data[7] + ')');
        $('#project_type_id').val(data[9]);
        $('#proposal_id').val(data[11]);
        $('#mdl-sch-prop').modal('hide');
        updateMessage();
    });

    $('#frm-sch-prop').submit(function(e) {
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
                tblProp.clear().draw();
                let dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.project_type_name,
                        this.name,
                        'v' + this.version,
                        this.user_name,
                        dateFormat(this.happen_at),
                        this.user_id,
                        this.user_code,
                        this.user_document,
                        this.project_type_id,
                        this.happen_at,
                        this.proposal_id
                    ]);
                });
                tblProp.rows.add(dataSet).draw();
                $('#fail-msg-prop').text('');
                $('#fail-div-prop').css('display','none');
                $('body').loadingModal('destroy');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-prop').text(JSON.stringify(msg));
                $('#fail-div-prop').css('display', 'block');
            }
        });
    });

    updateMessage();
});

function clearFormProp() {
    $('#prop_code').val('');
    $('#prop_happen_at').val('');
    $('#prop_user_name').val('');
    $('#prop_proj_type_id').val('');
    $('#fail-msg-prop').text('');
    $('#fail-div-prop').css('display','none');
    tblProp.clear().draw();
}

function sendOnlyCust() {
    $('#proposal_id').val('');
    $('#customer_id').val($('#prop_cust_id').val());
    $('#customer_ruc').val($('#prop_cust_ruc').val());
    $('#customer_name').val($('#prop_cust_name').val());
    setProjectCode();
    updateMessage();
}

function updateMessage() {
    if ($('#customer_id').val() && !$('#proposal_id').val()) {
        $('#msg_not_prop').html('<b>Nota: Proyecto sin propuesta previa</b>');
        $('#div_not_prop').css('display','block');
    }
    else {
        $('#msg_not_prop').text('');
        $('#div_not_prop').css('display','none');
    }
}