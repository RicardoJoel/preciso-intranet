$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

var totEmp = totFre = totPro = totSel = 0;

let tblEmp = $('#tbl-employees').DataTable({
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
        
        // Hours over all pages
        hours = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        //Update input control
        $('#totalHours').val(hours);
        
        // Total over all pages
        totEmp = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );


        // Update footer
        $( api.column( 4 ).footer() ).html(
            parseFloat(totEmp).toFixed(2)
        );

        calculateTotals();
    }
});

let preEmp = $('#_method').val() ? '../' : '';

$(function() {
    $('#emp_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            tblEmp.clear().draw();
            var dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    this.profile ?? '',
                    this.detail ?? '',
                    '<center>' + (this.hourly_rate ? parseFloat(this.hourly_rate).toFixed(2) : '') + '</center>',
                    '<center>' + (this.num_hours ?? '') + '</center>',
                    '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                    '<center><a name="' + index + '" onclick="editEmployee(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeEmployee(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblEmp.rows.add(dataSet).draw();
            $('body').loadingModal('destroy');
            clearFormEmp();
        })
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            var message = '<b>¡Atención!</b><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            message += '</ul>';
            $('#emp_fail_msg').html(message);
            $('#emp_fail_div').css('display','block');
        });
	});
});

function editEmployee(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: preEmp + '../employees/' + e.name + '/edit',
        success: function(data) {
            var item = JSON.parse(data);
            $('#emp_id').val(item.id);
            $('#emp_profile_id').val(item.profile_id);
            $('#emp_detail').val(item.detail);
            $('#emp_hourly_rate').val(parseFloat(item.hourly_rate).toFixed(2));
            $('#emp_num_hours').val(item.num_hours);
            $('#emp_subtotal').val(parseFloat(item.subtotal).toFixed(2));
            $('#emp_method').val('PATCH');
            $('#emp_submit').text('Actualizar');
            $('#emp_form').attr('action',preEmp + '../employees/' + e.name);
            $('#emp_subt').text('Editar colaborador');
            $('#emp_fail_msg').text('');
            $('#emp_fail_div').css('display','none');
            $('#div-emp').css('display','block');
		    $('#icn-emp').html('<i class="fa fa-minus fa-icon"></i>');
            $('body').loadingModal('destroy');
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#emp_fail_msg').text(JSON.stringify(msg));
            $('#emp_fail_div').css('display', 'block');
        }
    });
}

function removeEmployee(e) {
	if (confirm('¿Realmente desea eliminar el colaborador seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: preEmp + '../employees/' + e.name,
			success: function(data) {
                tblEmp.clear().draw();
                var dataSet = [];
                $(JSON.parse(data)).each(function(index) {
                    dataSet.push([
                        this.profile ?? '',
                        this.detail ?? '',
                        '<center>' + (this.hourly_rate ? parseFloat(this.hourly_rate).toFixed(2) : '') + '</center>',
                        '<center>' + (this.num_hours ?? '') + '</center>',
                        '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                        '<center><a name="' + index + '" onclick="editEmployee(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeEmployee(this)"><i class="fa fa-trash"></i></a></center>'
                    ]);
                });
                tblEmp.rows.add(dataSet).draw();
				$('body').loadingModal('destroy');
                clearFormEmp();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#emp_fail_msg').text(JSON.stringify(msg.responseJSON['errors']));
                $('#emp_fail_div').css('display', 'block');
			}
		});
	}
}

function clearFormEmp() {
    $('#emp_id').val('');
    $('#emp_profile_id').val('');
    $('#emp_detail').val('');
    $('#emp_hourly_rate').val('');
    $('#emp_num_hours').val('');
    $('#emp_subtotal').val('');
    $('#emp_method').val('');
    $('#emp_submit').text('Agregar');
    $('#emp_form').attr('action',preEmp + '../employees');
    $('#emp_subt').text('Nuevo colaborador');
    $('#emp_fail_msg').text('');
    $('#emp_fail_div').css('display','none');
}

$('#emp_hourly_rate').change(function() {
    subtotalEmp();
});

$('#emp_num_hours').change(function() {
    subtotalEmp();
});

function subtotalEmp() {
    var price = $('#emp_hourly_rate').val();
    var hours = $('#emp_num_hours').val();
    $('#emp_subtotal').val(parseFloat(price * hours).toFixed(2));
}

$('#perc_fit').change(function () {
    calculateTotals();
});

function calculateTotals() {
    var prcFit = $('#perc_fit').val();
    if (!$.isNumeric(prcFit)) prcFit = 0;
    //Calculate the total cost
    var result = totEmp + totFre + totPro;
    //Update total cost
    $('#totalCost').val(
        parseFloat(result).toFixed(2)
    );
    //Update final total
    $('#totalFinal').val(
        parseFloat(result * (1 - 0.01 * prcFit) * (1 + 0.01 * totSel)).toFixed(2)
    );
}