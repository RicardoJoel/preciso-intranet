$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

let tblCnt = $('#tbl-contacts').DataTable({
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
    ordering: false
});

let pre = $('#_method').val() ? '../' : '';
	
$(function() {
    $('#contact_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            tblCnt.clear().draw();
            let dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    '<center>' + this.type + '</center>',
                    '<center>' + this.fullname + '</center>',
                    '<center>' + dateFormat(this.birthdate) + '</center>',
                    '<center>' + this.position + '</center>',
                    '<center>' + this.email + '</center>',
                    '<center>' + this.mobile + '</center>',
                    '<center>' + (this.phone ?? '') + (this.annex ? ' #' + this.annex : '') + '</center>',
                    '<center><a name="' + index + '" onclick="editContact(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeContact(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblCnt.rows.add(dataSet).draw();
            $('body').loadingModal('destroy');
            $('#div-lst').css('display','block');
            $('#icn-lst').html('<i class="fa fa-minus fa-icon"></i>');
            clearForm();
        })  
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            let message = '<b>¡Atención!</b><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            message += '</ul>';
            $('#contact_fail_msg').html(message);
            $('#contact_fail_div').css('display','block');
        });
	});
});

function editContact(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: pre + '../contacts/' + e.name + '/edit',
        success: function(data) {
            let item = JSON.parse(data);
            $('#id').val(item.id);
            $('#contact_type_id').val(item.type_id);
            $('#contact_fullname').val(item.fullname);
            $('#contact_birthdate').val(item.birthdate);
            $('#contact_position').val(item.position);
            $('#contact_country_id').val(item.country_id);
            $('#contact_mobile').val(item.mobile);
            $('#contact_phone').val(item.phone);
            $('#contact_annex').val(item.annex);
            $('#contact_email').val(item.email);
            $('#contact_method').val('PATCH');
            $('#contact_submit').text('Actualizar');
            $('#contact_form').attr('action',pre + '../contacts/' + e.name);
            $('#subtitle').text('Editar contacto');
            $('#contact_fail_msg').text('');
            $('#contact_fail_div').css('display','none');
            $('#div-dep').css('display','block');
		    $('#icn-dep').html('<i class="fa fa-minus fa-icon"></i>');
            $('body').loadingModal('destroy');
            animacion();
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#contact_fail_msg').text(JSON.stringify(msg));
            $('#contact_fail_div').css('display', 'block');
        }
    });
}

function removeContact(e) {
	if (confirm('¿Realmente desea eliminar el contacto seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: pre + '../contacts/' + e.name,
			success: function(data) {
				tblCnt.clear().draw();
				let dataSet = [];			
				$(JSON.parse(data)).each(function(index) {
					dataSet.push([
                        '<center>' + this.type + '</center>',
                        '<center>' + this.fullname + '</center>',
                        '<center>' + dateFormat(this.birthdate) + '</center>',
                        '<center>' + this.position + '</center>',
                        '<center>' + this.email + '</center>',
                        '<center>' + this.mobile + '</center>',
                        '<center>' + (this.phone ?? '') + (this.annex ? ' #' + this.annex : '') + '</center>',
                        '<center><a name="' + index + '" onclick="editContact(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeContact(this)"><i class="fa fa-trash"></i></a></center>'
					]);
				});
				tblCnt.rows.add(dataSet).draw();
				$('#contact_fail_msg').text('');
				$('#contact_fail_div').css('display', 'none');
				$('body').loadingModal('destroy');
                clearForm();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#contact_fail_msg').text(JSON.stringify(msg.responseJSON['errors']));
                $('#contact_fail_div').css('display', 'block');
			}
		});
	}
}

function clearForm() {
    $('#contact_id').val('');
    $('#contact_type_id').val('');
    $('#contact_fullname').val('');
    $('#contact_birthdate').val('');
    $('#contact_position').val('');
    $('#contact_country_id').val(164);
    $('#contact_mobile').val('');
    $('#contact_phone').val('');
    $('#contact_annex').val('');
    $('#contact_email').val('');
    $('#contact_method').val('');
    $('#contact_submit').text('Agregar');
    $('#contact_form').attr('action',pre + '../contacts');
    $('#subtitle').text('Agregar contacto');
    $('#contact_fail_msg').text('');
    $('#contact_fail_div').css('display','none');
}