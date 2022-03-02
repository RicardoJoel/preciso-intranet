let tblCust = $('#tbl-customers').DataTable({
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
    $('#tbl-customers tbody').on('click', 'tr', function() {
        var data = tblCust.row(this).data();
        $('#visit_cust_name').val(data[1] + ' (' + data[0] + ')');
        $('#visit_cust_code').val(data[2]);
        $('#visit_cust_ruc').val(data[3]);
        $('#visit_cust_id').val(data[5]);
        $('#frm-sch-visit').submit();
        $('#mdl-sch-cust').modal('hide');
        $('#mdl-sch-visit').modal('show');
    });

    $('#btn-sch-cust').click(function() {
        let pre = $('#_method').val() ? '../' : '';
        let doc = $('#customer_ruc').val().trim();
        if (doc) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.ajax({
                type: 'get',
                url: pre + '../customers.getByDocument/' + doc,
                success: function(data) {
                    if (data) {
                        $('#visit_cust_name').val(data.name + ' (' + data.code + ')');
                        $('#visit_cust_code').val(data.code);
                        $('#visit_cust_ruc').val(data.ruc);
                        $('#visit_cust_id').val(data.id);
                        $('#frm-sch-visit').submit();
                        $('#mdl-sch-visit').modal('show');
                    }
                    else{
                        $('#customer_id').val('');
                        $('#customer_name').val('');
                    }
                    $('body').loadingModal('destroy');
                    setProjectCode();
                },
                error: function(msg) {
                    $('body').loadingModal('destroy');
                    $('#fail-msg-cust').text(JSON.stringify(msg));
                    $('#fail-div-cust').css('display', 'block');
                }
            });
        }
        else {
            $('#mdl-sch-cust').modal('show');
            clearFormCust();
        }
    });
 
    $('#frm-sch-cust').submit(function(e) {
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
                tblCust.clear().draw();
                let dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.name,
                        this.alias,
                        this.ruc,
                        this.business,
                        this.id
                    ]);
                });
                tblCust.rows.add(dataSet).draw();
                $('#fail-msg-cust').text('');
                $('#fail-div-cust').css('display','none');
                $('body').loadingModal('destroy');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-cust').text(JSON.stringify(msg));
                $('#fail-div-cust').css('display', 'block');
            }
        });
    });
});

function clearFormCust() {
    $('#cust_ruc').val('');
    $('#cust_code').val('');
    $('#cust_name').val('');
    $('#cust_alias').val('');
    $('#cust_business_id').val('');
    $('#fail-msg-cust').text('');
    $('#fail-div-cust').css('display','none');
    tblCust.clear().draw();
}

function clearDataCust() {
    $('#customer_id').val('');
    $('#customer_ruc').val('');
    $('#customer_name').val('');
}