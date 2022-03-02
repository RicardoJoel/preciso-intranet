$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

moment.locale('es');

let tblPro = $('#tbl-providers').DataTable({
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
        totPro = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
        $( api.column( 4 ).footer() ).html(
            parseFloat(totPro).toFixed(2)
        );

        calculateTotals();
    }
});

let prePro = $('#_method').val() ? '../' : '';

$(function() {
    $('#pro_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            tblPro.clear().draw();
            var dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    this.profile ?? '',
                    this.detail ?? '',
                    '<center>' + (this.currency ?? '') + '</center>',
                    '<center>' + (this.price ? parseFloat(this.price).toFixed(2) : '') + '</center>',
                    '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                    '<center><a name="' + index + '" onclick="editProvider(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeProvider(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblPro.rows.add(dataSet).draw();
            $('body').loadingModal('destroy');
            clearFormPro();
        })
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            var message = '<b>¡Atención!</b><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            message += '</ul>';
            $('#pro_fail_msg').html(message);
            $('#pro_fail_div').css('display','block');
        });
	});
});

function editProvider(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: prePro + '../providers/' + e.name + '/edit',
        success: function(data) {
            var item = JSON.parse(data);
            $('#pro_id').val(item.id);
            $('#pro_profile_id').val(item.profile_id);
            $('#pro_detail').val(item.detail);
            $('#pro_currency_id').val(item.currency_id);
            $('#pro_rate_value').val(item.rate_value);
            $('#pro_rate_id').val(item.rate_id);
            $('#pro_price').val(parseFloat(item.price).toFixed(2));
            $('#pro_subtotal').val(parseFloat(item.subtotal).toFixed(2));
            $('#pro_method').val('PATCH');
            $('#pro_submit').text('Actualizar');
            $('#pro_form').attr('action',prePro + '../providers/' + e.name);
            $('#pro_subt').text('Editar proveedor');
            $('#pro_fail_msg').text('');
            $('#pro_fail_div').css('display','none');
            $('#div-pro').css('display','block');
		    $('#icn-pro').html('<i class="fa fa-minus fa-icon"></i>');
            $('body').loadingModal('destroy');
            if (item.rate_id) {
                $('#pro_rate_msg').text('*Tipo de cambio al ' + 
                    moment(new Date(item.rate_date)).format('LL') + ': ' + 
                    parseFloat(item.rate_value).toFixed(3) + ' PEN/USD');
                $('#pro_rate_div').css('display','block');
            }
            else {
                $('#pro_rate_div').css('display','none');
            }
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#pro_fail_msg').text(JSON.stringify(msg));
            $('#pro_fail_div').css('display', 'block');
        }
    });
}

function removeProvider(e) {
	if (confirm('¿Realmente desea eliminar el proveedor seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: prePro + '../providers/' + e.name,
			success: function(data) {
				tblPro.clear().draw();
				var dataSet = [];			
				$(JSON.parse(data)).each(function(index) {
                    dataSet.push([
                        this.profile ?? '',
                        this.detail ?? '',
                        '<center>' + (this.currency ?? '') + '</center>',
                        '<center>' + (this.price ? parseFloat(this.price).toFixed(2) : '') + '</center>',
                        '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                        '<center><a name="' + index + '" onclick="editProvider(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeProvider(this)"><i class="fa fa-trash"></i></a></center>'
                    ]);
                });
				tblPro.rows.add(dataSet).draw();
				$('body').loadingModal('destroy');
                clearFormPro();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#pro_fail_msg').text(JSON.stringify(msg));
                $('#pro_fail_div').css('display', 'block');
			}
		});
	}
}

function clearFormPro() {
    $('#pro_id').val('');
    $('#pro_detail').val('');
    $('#pro_profile_id').val('');
    $('#pro_currency_id').val(1);
    $('#pro_rate_value').val(1);
    $('#pro_rate_date').val('');
    $('#pro_rate_id').val('');
    $('#pro_price').val('');
    $('#pro_subtotal').val('');
    $('#pro_method').val('');
    $('#pro_submit').text('Agregar');
    $('#pro_form').attr('action',prePro + '../providers');
    $('#pro_subt').text('Nuevo proveedor');
    $('#pro_fail_msg').text('');
    $('#pro_fail_div').css('display','none');
    $('#pro_rate_msg').text('');
    $('#pro_rate_div').css('display','none');
}

$('#pro_currency_id').change(function() {
    if ($(this).val() == 1) {
        $('#pro_rate_msg').text('');
        $('#pro_rate_div').css('display','none');
        $('#pro_rate_value').val(1);
        $('#pro_rate_date').val('');
        $('#pro_rate_id').val('');
        pricePro();
    }
    else {
        $.ajax({
            type: 'get',
            url: prePro + '../currentRate',
            success: (data) => {
                $('#pro_rate_msg').text('*Tipo de cambio al ' + 
                    moment(new Date(data.date)).format('LL') + ': ' + 
                    parseFloat(data.value).toFixed(3) + ' PEN/USD');
                $('#pro_rate_div').css('display','block');
                $('#pro_rate_value').val(data.value);
                $('#pro_rate_date').val(data.date);
                $('#pro_rate_id').val(data.id);
                pricePro();
            },
            error: function(msg) {
                $('#pro_fail_msg').text(JSON.stringify(msg));
                $('#pro_fail_div').css('display', 'block');
            }
        });
    }
});

$('#pro_price').change(function() {
    pricePro();
});

function pricePro() {
    var xrate = $('#pro_rate_value').val();
    var price = $('#pro_price').val();
    $('#pro_subtotal').val(parseFloat(xrate * price).toFixed(2));
}