//Custom field - Complemento
$('#form_pagseguro input[name="shippingAddressComplement"]').val(window.shippingAddressComplement);

//Override functions
var validate = function() {};
var startPayment = function() {};
var onFinishPayment = function() {};

$('#button-confirm').attr('disabled', true);

$('#processModal').modal({
    backdrop: 'static',
    keyboard: true,
    show: false
}).on('hidden.bs.modal', function() {
    $('#processModal .modal-footer').hide();
})

$('#button-confirm').on('click', function() {

    if (validate()) {

        $('#processModal .modal-title').html('Processando pagamento...');

        $('#processModal .modal-body').html('<p><center><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw" style="font-size:64px;color:#ccc"></i></center></p>');

        $('#processModal').modal('show');

        $.ajax({
            type: 'get',
            url: 'index.php?route=extension/payment/ukd_pagseguro/confirm',
            cache: false,
            beforeSend: function() {
                $('#button-confirm').button('loading');
            },
            complete: function(res) {
                //$('#button-confirm').button('reset');
            },
            success: function() {
                //location = '<?php //echo $continue; ?>';
                //process();
                startPayment();
            },
            error: function() {
                for (i in res) {
                    console.log(res[i], i, 'error confirm');
                }
            }
        });
    }
});

var cpf = $('#collapse-payment-address input[placeholder=CPF]').val() || window.customer['cpf'];
$('#form_pagseguro input[name=senderCPF]').val(cpf);

var email = $('#collapse-payment-address input[name=email]').val() || window.customer['email'];
$('#form_pagseguro input[name=senderEmail]').val(email);

var payment_telephone = $('#collapse-payment-address input[name=telephone]').val() || window.customer['telephone'];

payment_telephone = payment_telephone.split(' ');

var ddd = payment_telephone[0].replace(/[^0-9\.]+/g, '');
$('#form_pagseguro input[name=senderAreaCode]').val(ddd);

var telephone = payment_telephone[1].replace(/[^0-9\.]+/g, '');
$('#form_pagseguro input[name=senderPhone]').val(telephone);

$('#form_pagseguro input[name=paymentMethod]').val(pagseguro_method);

$('#div_content').html('<p><center><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw" style="font-size:48px;color:grey"></i></center></p>').show();

$.get('catalog/view/ukd_assets/php/checkout/' + pagseguro_method + '.php')
    .done(function(data) {
        $('#div_content').html(data);
        getSessionId();
    });

var error_count = 0;

function getSessionId() {

    $.ajax({
        dataType: 'json',
        url: 'catalog/view/ukd_assets/php/checkout/getSessionId.php',
        data: $('#form_pagseguro input[name=email], #form_pagseguro input[name=token]'),
        type: 'post',
        cache: false,
        success: function(res) {

            if (res.error) {

                console.log(res.error);

                if (error_count < 3) {

                    setTimeout(getSessionId, 5000);

                } else {

                    alert('FALHA AO CONECTAR SERVIDOR: ' + res.error);

                }

                error_count++;

            } else {

                getPaymentMethods(res.sessionId);

                error_count = 0;

            }

        },
        error: function(res) {
            console.log(res);
            // if (error_count < 5) getSessionId();
            // error_count++;
        }
    })
}

function getPaymentMethods(sessionId) {

    $.getScript(directpayment, function() {

        PagSeguroDirectPayment.setSessionId(sessionId);
        PagSeguroDirectPayment.getPaymentMethods({
            amount: amount,
            success: function(res) {
                getPaymentMethodsCallback(res);
            },
            error: function(res) {
                //console.log(res);
            },
            complete: function(res) {

            }
        });

    });

}

function getPaymentMethodsCallback(res) {

    //console.log(res);

    if (pagseguro_method == 'boleto') {

        if (res['paymentMethods']['BOLETO']['options']['BOLETO']['status'] == 'AVAILABLE') {
            $('#button-confirm').attr('disabled', false);
        } else {
            alert('Pagamento via Boleto Bancário temporariamente indisponível.');
        }

    } else if (pagseguro_method == 'creditCard') {

        window.options = res['paymentMethods']['CREDIT_CARD']['options'];

        if (window.options) {
            $('#button-confirm').attr('disabled', false);
            //init_cc();
        } else {
            alert('Pagamento via Cartão de Crédito está temporariamente indisponível.');
        }

    } else if (pagseguro_method == 'eft') {

        window.options = res['paymentMethods']['ONLINE_DEBIT']['options'];

        if (window.options) {
            $('#button-confirm').attr('disabled', false);
            init_eft();
        } else {
            alert('Pagamento via Débito Online está temporariamente indisponível.');
        }

    } else {

        alert('O gateway de pagamento está temporariamente indisponível.');

    }

}

function process() {

    var error = false;

    if (!$("#form_pagseguro input[name=senderHash]").val()) {

        $("#form_pagseguro input[name=senderHash]").val(PagSeguroDirectPayment.getSenderHash());

    }


    $.ajax({
        type: "POST",
        url: "catalog/view/ukd_assets/php/checkout/process.php?url=" + transactions_url,
        data: $("#form_pagseguro").serialize(),
        dataType: "json",
        cache: false,
        success: function(res) {

            if (res) {

                if (res['error']) {

                    console.log(res, 'error');

                    error = true;

                    processError(res['error']);

                } else {
                    console.log('processed...', res);
                    onFinishPayment(res)
                }
            } else {
                process();
            }

        },
        error: function(jqxhr) {

            alert(jqxhr.resonseText);

        },
        complete: function() {

            if (!error) $('#processModal').modal('hide');
            $('#button-confirm').button('reset');

        }

    })

}

function processError(error) {

    $('.form-group').removeClass('has-error');
    $('.input-group').removeClass('has-error');

    //console.log(error);
    //window.token = null;

    var content = '';

    if (error['code']) {

        content += error['code'] + ' - ' + error['message'];
        filterError(error['code']);

    } else {
        if (error[0]) {
            for (i in error) {

                content += error[i]['code'] + ' - ' + error[i]['message'] + '<br />';

                filterError(error[i]['code']);

            }
        }
    }

    if (content == '') {

        for (i in error) {

            content += i + ' - ' + error[i] + '<br />';

            filterError(i);

        }

    }

    $('#processModal .modal-title').html('Erro ao processar pagamento');

    $('#processModal .modal-footer').show();

    $('#processModal .modal-body').html('<span style="text-transform: uppercase">' + content + '</span>');

    //$('#processModal').modal('show');

}

function filterError(code) {

    if (code == 10000 || code == 10001) {
        $('#cc_form input[name=cardNumber]').parent('.input-group').addClass('has-error');
    }
    if (code == 10004 || code == 10006) {
        $('#cc_form input[name=cardCVC]').parent('.form-group').addClass('has-error');
    }
    if (code == 53042 || code == 53044) {
        $('#cc_form input[name=cardOwner]').parent('.form-group').addClass('has-error');
    }
    if (code == 53038 || code == 53040) {
        $('#cc_form select[name=installments]').parent('.form-group').addClass('has-error');
    }
    if (code == 53045 || code == 53046) {
        $('#cc_form input[name=cpf]').parent('.form-group').addClass('has-error');
    }
    if (code == 53047 || code == 53048) {
        $('#cc_form input[name=birthDate]').parent('.form-group').addClass('has-error');
    }
    if (code == 10002 || code == 30405) {
        $('#cc_form input[name=cardExpiry]').parent('.form-group').addClass('has-error');
    }
    if (code == 30400) {
        $('#cc_form input[name=cardNumber]').parent('.input-group').addClass('has-error');
        //window.token = null;
    }

    //53021 - sender phone invalid value

    console.log(code, 'filterError');

}

function errorAlert(content) {

    $('#processModal .modal-title').html('Atenção');

    $('#processModal .modal-footer').show();

    $('#processModal .modal-body').html('<span style="text-transform: uppercase">' + content + '</span>');

    $('#processModal').modal('show');

}