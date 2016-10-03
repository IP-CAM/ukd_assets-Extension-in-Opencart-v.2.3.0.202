/* ukd_pagseguro_209004f2 */
if (!window.ukd_pagseguro) {
    window.ukd_pagseguro = [];
}

var errorMsgInteval = null;

if (window.ukd_pagseguro.form_payment_cc) {

    console.log('loading record...');

    $('#payment_pagseguro_method').val(window.ukd_pagseguro.payment_method);

    if (window.ukd_pagseguro.cc.brand.name) {

        window.ukd_pagseguro.cc.errors = 0

        with(window.ukd_pagseguro) {

            var cardOwner = form_payment_cc['cardOwner'];
            var cardNumber = form_payment_cc['cardNumberWithSpaces'];
            var expirationMonth = form_payment_cc['expirationMonth'];
            var expirationYear = form_payment_cc['expirationYear'];
            var cvc = form_payment_cc['cvc'];
            var installments = form_payment_cc['installments'];

        }
        $('#card_brand').attr('src', window.ukd_pagseguro.images[window.ukd_pagseguro.cc.brand.name]['s']).show();
        $('#card_label').hide();

        getInstallments(installments);

        if (cardOwner) {
            $('#payment-cc-form input[name=cardOwner]').val(cardOwner);
        }
        if (cardNumber) {
            $('#payment-cc-form input[name=cardNumber]').val(cardNumber);
        }
        if (expirationMonth && expirationYear) {
            $('#payment-cc-form input[name=cardExpiry]').val(expirationMonth + "/" + expirationYear);
        }
        if (cvc) {
            $('#payment-cc-form input[name=cardCVC]').val(cvc);
        }

        $('#collapse-payment-method div#loading').hide();
        $('#collapse-payment-method div#ukd_content').show('400');

    }

} else {

    with(window) {
        ukd_pagseguro.form_payment_cc = [];
        ukd_pagseguro.fn = [];
        ukd_pagseguro.cc = [];
        ukd_pagseguro.cc.brand = [];
        ukd_pagseguro.cc.brand.name = null;
        ukd_pagseguro.images = [];
        ukd_pagseguro.payment_method = 'boleto';
    }

    with(window.ukd_pagseguro.cc) {
        brand.config = []
        brand.config.acceptedLengths = [16];
        brand.config.cvcSize = 3;
    }

    window.ukd_pagseguro.cc.errors = 0;
}

function getVal(name) {
    return $('#payment-cc-form *[name=' + name + ']').val();
}

//-----------------------------------------------------------------------------------------------------------------
// GET PAYMENT METHODS
//-----------------------------------------------------------------------------------------------------------------

function getPaymentMethodsCallback(resp) {

    for (var paymentMethods in resp['paymentMethods']) {

        if (resp['paymentMethods'][paymentMethods].name == 'BOLETO') {

            showBoleto(resp['paymentMethods'][paymentMethods]);

        } else if (resp['paymentMethods'][paymentMethods].name == 'ONLINE_DEBIT') {

            showOnlineDebit(resp['paymentMethods'][paymentMethods]);

        } else if (resp['paymentMethods'][paymentMethods].name == 'CREDIT_CARD') {

            showCreditCards(resp['paymentMethods'][paymentMethods]);

        }

    }

}

function showBoleto(o) {

    // Object o.options.BOLETO {
    //   name: "BOLETO",
    //   displayName: "Boleto",
    //   status: "AVAILABLE",
    //   code: 202,
    //   images: Object
    // }
    //console.log("###### BOLETO ######");

    //console.log(o.options['BOLETO'].displayName);
    //console.log(o.options['BOLETO'].status);
    //console.log("---------------------------");

}

function showOnlineDebit(o) {

    //Get bank list and image sources
    //Object "options[key]"
    //.displayName
    //.images [Object] .MEDIUM, .SMALL (.path, .size)
    //.status
    //.code

    //console.log("###### ONLINE DEBIT ######");

    var opts = o.options;

    for (key in opts) {

        //console.log(key, opts[key].displayName);
        //console.log(key, opts[key].status);
        //console.log(key, "https://stc.pagseguro.uol.com.br/" + opts[key].images.MEDIUM.path);
        //console.log("---------------------------");

    }

}

function showCreditCards(o) {

    //console.log("###### CREDIT CARD ######");

    var opts = o.options;

    var brands = 0;

    for (key in opts) {

        //console.log(key, opts[key].displayName);
        //console.log(key, opts[key].status);
        //console.log(key, "https://stc.pagseguro.uol.com.br/" + opts[key].images.MEDIUM.path);
        //console.log("---------------------------");
        brands++;
        var k = key.toLowerCase();

        with(window) {
            ukd_pagseguro.images[k] = [];
            ukd_pagseguro.images[k]['m'] = "https://stc.pagseguro.uol.com.br/" + opts[key].images.MEDIUM.path;
            ukd_pagseguro.images[k]['s'] = "https://stc.pagseguro.uol.com.br/" + opts[key].images.SMALL.path;
        }

    }

    //console.log("TOTAL BRANDS : " + brands);

}

//-----------------------------------------------------------------------------------------------------------------
// GET CREDIT CARD BRAND
//-----------------------------------------------------------------------------------------------------------------

// Object resp.brand  {
//   name: "mastercard",
//   bin: 531169, cvcSize: 3,
//   securityFieldLength
//   expirable: true,
//   international: false,
//   validationAlgorithm: "LUHN",
//   config: Object
// }

function getCardBrandCallback(resp) {

    if (resp.brand) {

        window.ukd_pagseguro.cc = resp;

        console.log(resp);

        $("#payment-cc-form input[name=cardNumber]").attr('maxlength', Math.max.apply(Math, resp.brand.config.acceptedLengths));

        // $('#payment-cc-form input[name=cardExpiry]').attr("disabled", false);
        //
        // if (!resp.brand.expirable) {
        //
        //     $('#payment-cc-form input[name=cardExpiry]').attr("disabled", true);
        //
        // }

        removeErrorClass('cardOwner')('cardNumber')('cardExpiry')('cardCVC')('installments');

        $('#card_brand')
            .attr('src', window.ukd_pagseguro.images[resp.brand.name]['s'])
            .load(
                function() {
                    $(this).show();
                    $('#card_label').hide();
                });

        getInstallments();

    } else {
        //$('input#brand').val('');
        $('#card_brand').attr('src', '').hide();
        $('#card_label').show();
        $('#payment-cc-form select[name=installments]').attr("disabled", true);
        $("#payment-cc-form select[name=installments] option:selected").prop("selected", false);
        $("#payment-cc-form select[name=installments] option:first").prop("selected", "selected");
    }

}

function getCardBrand(val) {

    PagSeguroDirectPayment.getBrand({
        cardBin: val,
        error: function(resp) {
            console.log('Get Brand Error', resp, val);
            window.ukd_pagseguro.cc.brand.name = null;
        },
        success: function(resp) {
            getCardBrandCallback(resp);
        },
        complete: function() {
            $("#payment-cc-form input, #payment-cc-form input select").attr('disabled', false);
        }
    });

    //console.log(val);
}

//-----------------------------------------------------------------------------------------------------------------
// CREATE CARD TOKEN
//-----------------------------------------------------------------------------------------------------------------

function createCardTokenCallback(resp) {

    if (resp.card) {

        if (window.ukd_pagseguro.form_payment_cc['installments'] > 0) {

            window.ukd_pagseguro.form_payment_cc['token'] = resp.card.token;

            $('#button-payment-method').click();


        } else {

            showErrorMessage('Selecione o número de parcelas!', 'installments');
            removeInstallments();
            getInstallments();

        }

    }

}

function createCardToken(data) {

    PagSeguroDirectPayment.createCardToken({
        cardNumber: data['cardNumber'],
        brand: data['brand'],
        cvv: data['cvc'],
        expirationMonth: data['expirationMonth'],
        expirationYear: data['expirationYear'],
        success: function(resp) {
            createCardTokenCallback(resp);
        },
        error: function(resp) {

            $('#payment-cc-form #button_check_payment').button('reset');

            var errors = resp.errors;

            if (errors[30400] || errors[10000] || errors[10001]) {
                window.ukd_pagseguro.cc.errors++;
                showErrorMessage('Número do Cartão de Crédito inváido!', 'cardNumber');
            } else if (errors[30405] || errors[10002]) {
                showErrorMessage('Data de Expiração inválida!', 'cardExpiry');
            } else if (errors[10004] || errors[10006]) {
                showErrorMessage('Número de segurança inválido!', 'cardCVC');
            } else {
                window.ukd_pagseguro.cc.errors++;
                showErrorMessage('Erro desconhecido!');
            }

            if (window.ukd_pagseguro.cc.errors == 5) {
                window.location.reload();
            }

            console.log('Error on createCardToken', errors);

        }
    });
}

//-----------------------------------------------------------------------------------------------------------------
// GET INSTALLMENTS
//-----------------------------------------------------------------------------------------------------------------

function getInstallmentsCallback(installments, selected) {

    if (installments) {

        removeInstallments();

        var s = '#payment-cc-form select[name=installments]';

        var el = $('#payment-cc-form select[name=installments]').attr("disabled", false);

        for (i in installments) {

            el.append($("<option></option>")
                .attr("value", installments[i].quantity)
                .attr("disabled", false)
                .text(installments[i].quantity + 'x de R$' + installments[i].installmentAmount + ' = R$' + installments[i].totalAmount));
        }

        if (selected) {
            var v = ukd_pagseguro.form_payment_cc['installments'];

            if ($(s + ' option[value=' + v + ']').val()) {
                $(s).val(v);
            }
        }

    }

    $("#payment-cc-form input[name=cardCVC]").attr('maxlength', window.ukd_pagseguro.cc.brand.cvvSize);
}

function getInstallments(selected) {

    var brand = window.ukd_pagseguro.cc.brand.name;

    if (brand) {

        PagSeguroDirectPayment.getInstallments({
            amount: window.ukd_pagseguro.total_data['total'],
            brand: brand,
            //maxInstallmentNoInterest: 1,
            success: function(resp) {
                getInstallmentsCallback(resp.installments[brand], selected);
            },
            error: function(resp) {
                console.log('Error on getInstallments');
            }
        });

    } else {

        console.log('Error on getInstallments');

    }

}

function removeInstallments() {
    var opt = $("#payment-cc-form option:first");

    $('#payment-cc-form select[name=installments]')
        .empty()
        .append(opt)
        .attr('disabled', true);
}

//-----------------------------------------------------------------------------------------------------------------
// SHOW ERROR MESSAGE
//-----------------------------------------------------------------------------------------------------------------

function showErrorMessage(msg, name) {

    var alert = '<div  class="alert alert-danger" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + msg + '</div>';

    $('html, body').animate({
        scrollTop: $("#payment_method_alerts").offset().top - 10
    }, 400);

    $('#payment_method_alerts > div:not(:last)').hide('400', function() {

        $(this).remove();
        $('#payment_method_alerts > div:first').hide('400', function() {
            $(this).remove();
        })

    });

    $('#payment_method_alerts').append(alert).find('div').show('400', function() {

        var last = $('#payment_method_alerts > div');

        setTimeout(function() {
            $(last).hide('400', function() {
                $(this).remove();

            });
        }, 5000);

    });

    if (name) $('#payment-cc-form *[name=' + name + ']').focus().parent('div').addClass('has-error');

    return showErrorMessage;

}

function removeErrorClass(name) {
    $('#payment-cc-form *[name=' + name + ']').parent('div').removeClass('has-error');
    return removeErrorClass;
}


//-----------------------------------------------------------------------------------------------------------------

function getCardNumberAccLengths() {
    var ac_len = window.ukd_pagseguro.cc.brand.config.acceptedLengths;
    var max = Math.max.apply(Math, ac_len);
    var min = Math.min.apply(Math, ac_len);
    var mid = min;
    for (i in ac_len) {
        var j = ac_len[i];
        if (j != min && j != max) {
            mid = ac_len[i];
        }
    }
    return [min, mid, max];
}

//-----------------------------------------------------------------------------------------------------------------
// ON INIT
//-----------------------------------------------------------------------------------------------------------------
window.ukd_pagseguro.timeout = null;

$(function() {

    window.ukd_pagseguro.fn.init = function(getSessionId) {

        $.ajax({
            type: "GET",
            url: "system/library/ukd_pagseguro_app/sessionId.php",
            cache: false,
            success: function(resp) {

                console.log('session id: ', resp);

                if (resp.indexOf('error') > -1) {
                    alert('Falha na conexão. Por favor, verifique sua internet.')
                    window.location.reload();
                    return false;
                }

                PagSeguroDirectPayment.setSessionId(resp);

                PagSeguroDirectPayment.getPaymentMethods({
                    amount: window.ukd_pagseguro.total_data['total'],
                    error: function(resp) {
                        console.log('Error on Init', resp);
                        window.ukd_pagseguro.fn.init();
                    },
                    success: function(resp) {
                        ////console.log(resp);
                        $('#collapse-payment-method div#loading').hide();

                        $('#collapse-payment-method div#ukd_content').show('slow/400/fast', function() {

                            getPaymentMethodsCallback(resp);

                        });

                    }

                });

            },
            error: function(jqxhr) {

                console.log('Error on Init: get session ID !!!');
                window.ukd_pagseguro.fn.init();

            }

        })

    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        window.ukd_pagseguro.payment_method = $(this).data('payment-method');
        $('#payment_pagseguro_method').val($(this).data('payment-method'));
        $(this).blur();
    })

    $('a[data-toggle="tab"]').each(function(index, el) {
        if ($(el).data('payment-method') == window.ukd_pagseguro.payment_method) {
            $(el).tab('show');
            $('#payment_pagseguro_method').val($(el).data('payment-method'));
        }
    });

    $('#payment-cc-form #button_check_payment').on('click', function(event) {

        var error = null;

        if (!window.ukd_pagseguro.timeout) $('#payment_method_success').hide();

        removeErrorClass('cardOwner')('cardNumber')('cardExpiry')('cardCVC')('installments');

        with(window.ukd_pagseguro) {

            var cardOwner = getVal('cardOwner').replace(/  /g, ' ').trim();
            var cardNumber = getVal('cardNumber');
            var cardExpiry = getVal('cardExpiry').split('/');
            var cvc = getVal('cardCVC');
            var installments = getVal('installments');

            form_payment_cc['cardOwner'] = cardOwner;
            form_payment_cc['cardNumberWithSpaces'] = cardNumber;
            form_payment_cc['cardNumber'] = cardNumber.replace(/ /g, '');
            form_payment_cc['expirationMonth'] = cardExpiry[0];
            form_payment_cc['expirationYear'] = cardExpiry[1];
            form_payment_cc['cvc'] = cvc;
            form_payment_cc['installments'] = installments;

        }

        var d = new Date();
        var currYear = d.getFullYear();
        var currMouth = d.getMonth() + 1;

        var ccAccLen = getCardNumberAccLengths();

        if (cardOwner.length < 6) {

            error = showErrorMessage('Nome do titular do Cartão de Crédito inválido!', 'cardOwner');

        } else if (cardNumber.length != ccAccLen[0] && cardNumber.length != ccAccLen[1] && cardNumber.length != ccAccLen[2]) {

            error = showErrorMessage('Número do Cartão de Crédito inválido!', 'cardNumber');
            removeInstallments();

        } else if (!cardExpiry[1] || cardExpiry[0].length < 2 || cardExpiry[1].length < 4) {

            error = showErrorMessage('Data de Expiração inválida!', 'cardExpiry');

        } else if (cardExpiry[0] > 12 || cardExpiry[0] == 00 || cardExpiry[1] < currYear) {

            error = showErrorMessage('Data de Expiração inválida!', 'cardExpiry');

        } else if (cardExpiry[1] == currYear) {

            if (cardExpiry[0] < currMouth) {
                error = showErrorMessage('Data de Expiração inválida!', 'cardExpiry');
            }

        } else if (cvc.length < 3) {

            error = showErrorMessage('Número de segurança inválido!', 'cardCVC');

        } else if (installments == 0 && window.ukd_pagseguro.cc.brand.name) {

            error = showErrorMessage('Selecione o número de parcelas!', 'installments');

        }

        if (!error) {
            createCardToken(window.ukd_pagseguro.form_payment_cc);
            $(this).button('loading');
        }

    });

    $("#payment-cc-form input[name=cardNumber]").keyup(function(e) {

        var cc = $(this).val().replace(/ /g, '');
        var len = cc.length;
        var ccAccLen = getCardNumberAccLengths();

        if (len == ccAccLen[0] || len == ccAccLen[1] || len == ccAccLen[2]) {
            window.ukd_pagseguro.cc.brand.name = null;
            $("#payment-cc-form input, #payment-cc-form select").attr('disabled', true);
            $("#payment-cc-form input[name=cardNumber]").attr('disabled', false);
            removeInstallments();
            getCardBrand(cc);
            // if (ccAccLen[0] == 16 && ccAccLen[1] == 16 && ccAccLen[2] == 16) {
            //
            //     $('.creditcard').mask('0000 0000 0000 0000');
            //
            // }
        } else if (len > 5 && !window.ukd_pagseguro.cc.brand.name) {
            if (e.keyCode != 8) {
                var cc = $(this).val().replace(/ /g, '');
                window.ukd_pagseguro.cc.brand.name = null;
                removeInstallments();
                getCardBrand(cc);
            }
        } else if (len < 5) {
            $('#card_brand').hide();
            $('#card_label').show();
            window.ukd_pagseguro.cc.brand.name = null;
            removeInstallments();
        }

    });

    //$( "#mydiv" ).hasClass( "quux" )

    $('#payment-cc-form input[name=cardExpiry]').focus(function(event) {
        var p = $(this).parent('div');
        if (p.hasClass('has-error')) {
            $(this).val('');
            p.removeClass('has-error');
        }
    });

    $('#payment-cc-form input, #payment-cc-form select').focus(function(event) {

        if ($(this).attr('name') != 'cardExpiry') $(this).parent('div').removeClass('has-error');

    });

    $('#payment-cc-form select[name=installments]').attr("disabled", true);

    function capitalize(s) {
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    $('.onlyname').on('input', function(event) {
        var val = this.value;

        var l = val.lastIndexOf(' ') + 1;

        var s1 = capitalize(val.slice(0, l)).replace(/  /g, ' ');

        var s2 = capitalize(val.slice(l).toLowerCase());

        val = s1 + s2;

        //val = val.replace(/\.+/g, '. ');

        this.value = val.replace(/[^A-Z|a-z| ]/g, '');
    });

    $('.numeric').on('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    //$('.creditcard').mask('0000 0000 0000 0000');

    $('.date').mask('00/0000');

    // $('.date').mask('nM/czYY', {
    //     'translation': {
    //         n: {
    //             pattern: /[0-1]/
    //         },
    //         M: {
    //             pattern: /[0-9]/
    //         },
    //         c: {
    //             pattern: /[2-2]/
    //         },
    //         Y: {
    //             pattern: /[0-9]/
    //         },
    //         z: {
    //             pattern: /[0]/
    //         }
    //     }
    // });



    if (!window.ukd_pagseguro.cc.brand.name) window.ukd_pagseguro.fn.init();


})