let tblUser = $('#tbl-users').DataTable({
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
        targets: 5,
        visible: false
    }]
});

$(function() {
    $('#tbl-users tbody').on('click', 'tr', function() {
        var data = tblUser.row(this).data();
        $('#user_wholename').val(data[1] + ' (' + data[0] + ')');
        $('#user_document').val(data[3]);
        $('#user_id').val(data[5]);
        $('#mdl-sch-user').modal('hide');
    });

    $('#btn-sch-user').click(function() {
        let pre = $('#_method').val() ? '../' : '';
        let document = $('#user_document').val().trim();
        if (document) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.ajax({
                type: 'get',
                url: pre + '../users.getByDocument/' + document,
                success: function(data) {
                    if (data) {
                        $('#user_id').val(data.id);
                        $('#user_wholename').val(data.name + ' (' + data.code + ')');
                    }
                    else{
                        $('#user_id').val('');
                        $('#user_wholename').val('');
                    }
                    $('body').loadingModal('destroy');
                },
                error: function(msg) {
                    $('body').loadingModal('destroy');
                    $('#fail-msg-user').text(JSON.stringify(msg));
                    $('#fail-div-user').css('display', 'block');
                }
            });
        }
        else {
            $('#mdl-sch-user').modal('show');
            clearFormUser();
        }
    });
 
    $('#frm-sch-user').submit(function(e) {
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
                tblUser.clear().draw();
                let dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.name,
                        this.documentType,
                        this.document,
                        this.email,
                        this.id
                    ]);
                });
                tblUser.rows.add(dataSet).draw();
                $('#fail-msg-user').text('');
                $('#fail-div-user').css('display','none');
                $('body').loadingModal('destroy');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-user').text(JSON.stringify(msg));
                $('#fail-div-user').css('display', 'block');
            }
        });
    });
});

function clearFormUser() {
    $('#user_name').val('');
    $('#user_code').val('');
    $('#user_email').val('');
    $('#user_doc').val('');
    $('#user_doc_type_id').val('');
    $('#fail-msg-user').text('');
    $('#fail-div-user').css('display','none');
    tblUser.clear().draw();
}

function clearDataUser() {
    $('#user_id').val('');
    $('#user_document').val('');
    $('#user_wholename').val('');
}