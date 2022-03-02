$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

let tblSel = $('#tbl-sellers').DataTable({
    lengthChange: false,
    searching: false,
    order: false,
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
    footerCallback: function () {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ? i.replace(/[<center>,</center>]/g, '')*1 :
                   typeof i === 'number' ? i : 0;
        };
        
        // Total over all pages
        totSel = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        //Update total cost
        $('#totalCommission').val(
            parseFloat(totSel).toFixed(2)
        );

        // Update footer
        $( api.column( 3 ).footer() ).html(
            parseFloat(totSel).toFixed(2)
        );

        calculateTotals();
    }
});

let preSel = $('#_method').val() ? '../' : '';

$(function() {
    $('#sel_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            tblSel.clear().draw();
            var dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    this.user ?? '',
                    this.code ?? '',
                    this.detail ?? '',
                    '<center>' + parseFloat(this.commission).toFixed(2) + '</center>',
                    '<center><a name="' + index + '" onclick="editSeller(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeSeller(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblSel.rows.add(dataSet).draw();
            $('body').loadingModal('destroy');
            clearFormSel();
        })
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            var message = '<b>¡Atención!</b><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            message += '</ul>';
            $('#sel_fail_msg').html(message);
            $('#sel_fail_div').css('display','block');
        });
	});
});

function editSeller(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: preSel + '../sellers/' + e.name + '/edit',
        success: function(data) {
            var item = JSON.parse(data);
            $('#sel_id').val(item.id);
            $('#sel_user_id').val(item.user_id);
            $('#sel_code').val(item.code);
            $('#sel_detail').val(item.detail);
            $('#sel_commission').val(parseFloat(item.commission).toFixed(2));
            $('#sel_method').val('PATCH');
            $('#sel_submit').text('Actualizar');
            $('#sel_form').attr('action',preSel + '../sellers/' + e.name);
            $('#sel_subt').text('Editar vendedor');
            $('#sel_fail_msg').text('');
            $('#sel_fail_div').css('display','none');
            $('#div-sel').css('display','block');
		    $('#icn-sel').html('<i class="fa fa-minus fa-icon"></i>');
            $('body').loadingModal('destroy');
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#sel_fail_msg').text(JSON.stringify(msg));
            $('#sel_fail_div').css('display', 'block');
        }
    });
}

function removeSeller(e) {
	if (confirm('¿Realmente desea eliminar el vendedor seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: preSel + '../sellers/' + e.name,
			success: function(data) {
                tblSel.clear().draw();
                var dataSet = [];
                $(JSON.parse(data)).each(function(index) {
                    dataSet.push([
                        this.user ?? '',
                        this.code ?? '',
                        this.detail ?? '',
                        '<center>' + parseFloat(this.commission).toFixed(2) + '</center>',
                        '<center><a name="' + index + '" onclick="editSeller(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeSeller(this)"><i class="fa fa-trash"></i></a></center>'
                    ]);
                });
                tblSel.rows.add(dataSet).draw();
				$('body').loadingModal('destroy');
                clearFormSel();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#sel_fail_msg').text(JSON.stringify(msg.responseJSON['errors']));
                $('#sel_fail_div').css('display', 'block');
			}
		});
	}
}

function clearFormSel() {
    $('#sel_id').val('');
    $('#sel_user_id').val('');
    $('#sel_code').val('');
    $('#sel_detail').val('');
    $('#sel_commission').val('');
    $('#sel_method').val('');
    $('#sel_submit').text('Agregar');
    $('#sel_form').attr('action',preSel + '../sellers');
    $('#sel_subt').text('Nuevo vendedor');
    $('#sel_fail_msg').text('');
    $('#sel_fail_div').css('display','none');
}

$('#sel_user_id').change(function() {
    $.ajax({
        type: 'get',
        url: preSel + '../user/' + $(this).val(),
        success: (data) => {
            $('#sel_code').val(data.code);
            $('#sel_commission').val(parseFloat(data.commission).toFixed(2));
        },
        error: function(msg) {
            $('#sel_fail_msg').text(JSON.stringify(msg));
            $('#sel_fail_div').css('display', 'block');
        }
    });
});