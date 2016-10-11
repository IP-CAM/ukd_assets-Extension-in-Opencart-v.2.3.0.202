window.clps = []; //collapses

$('.panel-title').each(function(i) {

    $(this).data('id', i)

    window.clps.push($(this));

});

$('.collapse').on('show.bs.collapse', function() {

    var c = window.clps;

    var id = $(this).parent('div').find('h4').data('id');

    for (i in c) {
        if (id < i && c[i].html().indexOf('<') == 0) {
            c[i].find('a').css('pointer-events', 'none');
            c[i].find('i').remove();
        }

    }

});

$('#collapse-checkout-confirm').on('hide.bs.collapse', function() {

    $('#collapse-checkout-confirm .panel-body').empty();

});


function getAddressByPostcode(form, autosave = true) {

    address_autofill($(form + ' input[name=\'postcode\']'));

    if (autosave) {
        $(form + ' input, ' + form + ' select').blur(function(event) {
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
                        $(form + ' select[name=\'zone_id\']').attr('readonly', true).find('option[data-sigla=' + json['uf'] + ']').prop('selected', true);
                    } else {
                        $(form + ' select[name=\'zone_id\']').attr('readonly', false);
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
            $(form + ' select[name=\'zone_id\']').attr('readonly', false).find('option[data-sigla=none]').prop('selected', true);
        }
    }

}

function addressForm(id) {
    $('#form-' + id + ' input[value=existing]').on('click', function() {

        $('#form-' + id + ' #' + id + '-existing').show();
        $('#form-' + id + ' #' + id + '-new').hide();
        window[id + '_address']['radio_address'] = 'existing';
        //$(id + ' input[name=' + id + '_address]').val('existing');
        console.log('existing_address');

    })

    $('#form-' + id + ' input[value=new]').on('click', function() {

        $('#form-' + id + ' #' + id + '-existing').hide();
        $('#form-' + id + ' #' + id + '-new').show();
        window[id + '_address']['radio_address'] = 'new';
        //$(id + ' input[name=' + id + '_address]').val('new');
        console.log('new');

    })

    // Sort the custom fields
    $('#collapse-' + id + '-address .form-group[data-sort]').detach().each(function() {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-' + id + '-address .form-group').length - 2) {
            $('#collapse-' + id + '-address .form-group').eq(parseInt($(this).attr('data-sort')) + 2).before(this);
        }

        if ($(this).attr('data-sort') > $('#collapse-' + id + '-address .form-group').length - 2) {
            $('#collapse-' + id + '-address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') == $('#collapse-' + id + '-address .form-group').length - 2) {
            $('#collapse-' + id + '-address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('#collapse-' + id + '-address .form-group').length - 2) {
            $('#collapse-' + id + '-address .form-group:first').before(this);
        }
    });

    $('#collapse-' + id + '-address button[id^=\'button-' + id + '-custom-field\']').on('click', function() {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(node).button('loading');
                    },
                    complete: function() {
                        $(node).button('reset');
                    },
                    success: function(json) {
                        $(node).parent().find('.text-danger').remove();

                        if (json['error']) {
                            $(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input[name^=\'custom_field\']').val(json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });

    $('.date').datetimepicker({
        pickTime: false
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });

    //ukd

    if (window[id + '_address']) {
        //console.log(window[id+'_address']);
        for (i in window[id + '_address']) {
            if (i == 'radio_address') {
                $('#form-' + id + ' input[value="' + window[id + '_address'][i] + '"]').click();
            } else {
                $('#form-' + id + '> *[name=\'' + i + '\']').val(window[id + '_address'][i]);
            }
        }
    } else {
        window[id + '_address'] = [];
    }

    var el = $('#form-' + id + ' input, #form-' + id + ' select');

    el.blur(function(event) {

        el.each(function() {

            window[id + '_address'][$(this).attr('name')] = $(this).val();

        });

    });

    $('#form-' + id + ' select[name=\'address_id\']').change(function(event) {
        window[id + '_address'] = $(this).find('option:selected').data('address');
    }).trigger('change');


}

var cpfValidation = function(cpf) {

    var S;
    var R;
    S = 0;
    if (cpf == "00000000000") return false;

    for (i = 1; i <= 9; i++) S = S + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    R = (S * 10) % 11;

    if ((R == 10) || (R == 11)) R = 0;
    if (R != parseInt(cpf.substring(9, 10))) return false;

    S = 0;
    for (i = 1; i <= 10; i++) S = S + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    R = (S * 10) % 11;

    if ((R == 10) || (R == 11)) R = 0;
    if (R != parseInt(cpf.substring(10, 11))) return false;
    return true;

}

var cnpjfValidation = function(cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g, '');

    if (cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0, tamanho);
    digitos = cnpj.substring(tamanho);
    S = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        S += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = S % 11 < 2 ? 0 : 11 - S % 11;
    if (resultado != digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    S = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        S += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = S % 11 < 2 ? 0 : 11 - S % 11;
    if (resultado != digitos.charAt(1))
        return false;

    return true;

}