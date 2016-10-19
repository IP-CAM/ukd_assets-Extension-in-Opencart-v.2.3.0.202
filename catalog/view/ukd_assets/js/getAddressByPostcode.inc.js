function getAddressByPostcode(form, autosave = true) {

    address_autofill($(form + ' input[name=\'postcode\']'));

    if (autosave) {
        $(form + ' input:not([type=radio]), ' + form + ' select').blur(function(event) {
            window[form][$(this).attr('name')] = $(this).val();
        });

        if (!window[form]) {
            window[form] = [];
        } else {
            for (i in window[form]) {
                $(form + ' *[name=\'' + i + '\']').val(window[form][i]);
            }
        }
    }

    $(form + ' input[name=\'postcode\']').keyup(function() {

        address_autofill($(this));

    }).attr('maxlength', 8);

    function address_autofill(el) {
        if (el.val().length == 8) {
            $.ajax({
                url: 'https://viacep.com.br/ws/' + el.val() + '/json/',
                dataType: 'json',
                beforeSend: function(resp) {
                    window.setReadonly(['address_1', 'address_2', 'city', 'zone_id'], true);
                },
                success: function(json) {

                    if (json['logradouro']) {
                        $(form + ' input[name=\'address_1\']').val(json['logradouro']);
                    }
                    if (json['bairro']) {
                        $(form + ' input[name=\'address_2\']').val(json['bairro']);
                    }

                    if (json['localidade']) {
                        $(form + ' input[name=\'city\']').val(json['localidade']).attr('readonly', true);
                    } else {
                        $(form + ' input[name=\'city\']').attr('readonly', false);
                    }

                    if (json['uf']) {
                        $(form + ' select[name=\'zone_id\']').attr('disabled', true).find('option[data-sigla=' + json['uf'] + ']').prop('selected', true);
                    } else {
                        $(form + ' select[name=\'zone_id\']').attr('disabled', false);
                    }

                },
                complete: function(resp) {
                    window.setReadonly(['address_1', 'address_2'], false);

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    window.setReadonly(['address_1', 'address_2', 'city', 'zone_id'], false);
                }
            })
        } else {
            $(form + ' input[name=\'city\']').val('').attr('readonly', false);
            $(form + ' select[name=\'zone_id\']').attr('disabled', false).find('option[data-sigla=none]').prop('selected', true);
        }
    }

}