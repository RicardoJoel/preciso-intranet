$("#mobile").inputmask({"mask":"999 999 999"});
$("#phone").inputmask({"mask":"99 999 9999"});
$("#account").inputmask({"mask":"9999-9999-99999999999"});
$("#cci").inputmask({"mask":"999-999-999999999999-99"});

$('#bank_id').change(function() {
    setBankAccount();
});

if ($('#bank_id').val()) {
    setBankAccount();
}

function setBankAccount() {
    $('#account').prop('disabled',false);
    $('#cci').prop('disabled',false);
}

$('#profile_id').change(function() {
    setOtherOccup();
});

if ($('#profile_id').val()) {
    setOtherOccup();
}

function setOtherOccup() {
    if ($('#profile_id').val() == 49)
        $('#other').prop('disabled',false);
    else
        $('#other').val('').prop('disabled',true);
}

$('#document_type_id').change(function() {
    setDocFormat();
});

if ($('#document_type_id').val()) {
    setDocFormat();
}

function setDocFormat() {
    var pre = $('#_method').val() ? '../' : '';
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: pre + '../documentType/' + $('#document_type_id').val(),
        success: function(data) {
            let value = $('#document').val();
            $('#document')
                .prop('disabled',false)
                .prop('maxlength',data.length)
                .attr('onkeypress','return check' + (data.is_number ? 'Number' : 'AlNum') + '(event)')
                .val((data.is_number ? getNumbersInString(value) : value).substr(0, data.length));
            $('#doc_pattern').val(data.is_exact ? '[0-9]{' + data.length + '}' : '[A-Za-z0-9]{1,' + data.length + '}');
            $('body').loadingModal('destroy');
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            alert(JSON.stringify(msg.responseJSON['errors']));
        }
    });
}