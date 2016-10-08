/*  Load Mask Lib */
require(["mask"], function() {
  $('.phone_mask').mask('(00) 000000000', {placeholder: "(DDD) Número do telefone"});
  $('.postcode_mask').mask('00000000', {placeholder: "Somente números. Ex.: 42850000"});
})

$(collapse_id + ' input[name=\'postcode\']').keyup(function(event) {

    window.restoreAddressFields($(this));

}).attr('maxlength', 8);

window.restoreAddressFields = function(el, init) {

    if (el.val().length == 8) {
        $.ajax({
            url: 'https://viacep.com.br/ws/' + el.val() + '/json/',
            dataType: 'json',
            beforeSend: function(resp) {
                $(collapse_id + ' input[name=\'address_1\']').attr('disabled', true);
                $(collapse_id + ' input[name=\'address_2\']').attr('disabled', true);
                $(collapse_id + ' input[name=\'city\']').attr('readonly', true);
                $(collapse_id + ' select[name=\'zone_id\']').attr('readonly', true);
            },
            success: function(json) {

                if (!init) {
                    if (json['logradouro']) {
                        $(collapse_id + ' input[name=\'address_1\']').val(json['logradouro']);
                    }
                    if (json['bairro']) {
                        $(collapse_id + ' input[name=\'address_2\']').val(json['bairro']);
                    }
                }

                if (json['localidade']) {
                    $(collapse_id + ' input[name=\'city\']').val(json['localidade']).attr('readonly', true);
                } else {
                    $(collapse_id + ' input[name=\'city\']').attr('readonly', false);
                }

                if (json['uf']) {
                    $(collapse_id + ' select[name=\'zone_id\']').attr('readonly', true).find('option[data-sigla=' + json['uf'] + ']').prop('selected', true);
                } else {
                    $(collapse_id + ' select[name=\'zone_id\']').attr('readonly', false);
                }

            },
            complete: function(resp) {
                $(collapse_id + ' input[name=\'address_1\']').attr('disabled', false);
                $(collapse_id + ' input[name=\'address_2\']').attr('disabled', false);
                //$(collapse_id + ' input[name=\'city\']').attr('disabled', false);
                //$(collapse_id + ' input[name=\'zone_id\']').attr('disabled', false)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $(collapse_id + ' input[name=\'address_1\']').attr('disabled', false);
                $(collapse_id + ' input[name=\'address_2\']').attr('disabled', false);
                $(collapse_id + ' input[name=\'city\']').attr('readonly', false);
                $(collapse_id + ' select[name=\'zone_id\']').attr('readonly', false)
                    //load();
            }
        })
    } else {
        // $(collapse_id + ' input[name=\'address_1\']').val('');
        // $(collapse_id + ' input[name=\'address_2\']').val('');
        $(collapse_id + ' input[name=\'city\']').val('').attr('readonly', false);
        $(collapse_id + ' select[name=\'zone_id\']').attr('readonly', false).find('option[data-sigla=none]').prop('selected', true);
    }
}


$(document).delegate(button_id , 'click', function() {

    $(collapse_id + ' .has-error').removeClass('has-error');

})

// $(document).delegate(collapse_id + ' select[name=\'zone_id\']', 'change', function() {
//     $(collapse_id + ' input[name=\'postcode\']').val('');
//     $(collapse_id + ' input[name=\'address_1\']').val('');
//     $(collapse_id + ' input[name=\'address_2\']').val('');
//     $(collapse_id + ' input[name=\'city\']').val('');
// })

window.restoreAddressFields($(collapse_id + ' input[name=\'postcode\']'), true);