<?php
require_once '../security.php';
?>
<div class="col-xs-12 col-sm-6 col-md-5 " style="padding:0; margin:0">
<div class="panel panel-default" >
  <div class="panel-heading display-table" style="background-color:#cef">
    <div class="row display-tr">
        <span style="font-size:12px; margin-left:20px">CARTÃO DE CRÉDITO</span>
    </div>
  </div>
  <div style="padding:10px">
    <form role="form" id="cc_form" method="POST" action="javascript:void(0);">

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="cardOwner">NOME DO TITULAR</label>
            <input type="text" class="form-control onlyname" name="cardOwner" placeholder="Nome" value="Frederico Ukita" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="cardOwner">CPF DO TITULAR</label>
            <input type="text" class="form-control numeric" name="cpf" placeholder="CPF" maxlength="11" value="96456590515" />
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="cardOwner">DATA DE NASCIMENTO</label>
            <input type="text" class="form-control date" name="birthDate" placeholder="Dia / Mês / Ano" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label for="cardNumber"><span class="hidden-xs">NÚMERO DO CARTÃO DE CRÉDITO</span><span class="visible-xs-inline">N° DO CARTÃO</span></label>
            <div class="input-group">
              <input type="text" class="form-control creditcard numeric" name="cardNumber" maxlength="19" placeholder="Valid Card Number" value="4111111111111111" />
              <span class="input-group-addon"><img id="card_brand" src="" style="display:none" /><i id="card_label" class="fa fa-credit-card"></i></span>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="cardExpiry"><span class="hidden-xs">DATA DE EXPIRAÇÃO</span><span class="visible-xs-inline">DATA DE EXP.</span></label>
            <input type="tel" class="form-control date2" name="cardExpiry" placeholder="Mês / Ano" />
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
              <label for="cardCVC"><span class="hidden-sm">CÓDIGO DE SEGURANÇA</span><span class="visible-sm-inline">C. DE SEGURANÇA</span></label>
            <input type="tel" class="form-control numeric" name="cardCVC" maxlength="3" placeholder="CVC" value="123" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label for="installments">PARCELAMENTO</label>
            <select name="installments" class="form-control" disabled="disabled">
              <option id="installments" value="0" selected>Selecione o número de parcelas</option>
            </select>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<div class="col-sm-6 col-md-7">
  DIV
</div>
<br />
<script>

window.options = [];

var card_img = '';

var ccdata = [];

var cur_ccn = 0;

var selected_installment = '';

$('.date').mask('dM/mM/YMMM', {
    'translation': {
        d: {
            pattern: /[0-3]/
        },
        m: {
            pattern: /[0-1]/
        },
        M: {
            pattern: /[0-9]/
        },
        Y: {
            pattern: /[1-2]/
        }
    }
});

$('.date2').mask('mM/YMMM', {
    'translation': {
        m: {
            pattern: /[0-1]/
        },
        M: {
            pattern: /[0-9]/
        },
        Y: {
            pattern: /[1-2]/
        }
    }
});

function validate(){
  return true;
}

var dt = new Date();

$('#cc_form input[name=birthDate]').datetimepicker({
    format: 'DD/MM/YYYY',
    maxDate: new Date(dt.setYear(dt.getYear() - 18)),
    defaultDate: dt,
    pickTime: false,
    locale: 'br'
});

dt = new Date();

$('#cc_form input[name=cardExpiry]').datetimepicker({
    format: 'MM/YYYY',
    minDate: new Date(dt.setMonth(dt.getMonth() + 1)),
    defaultDate: dt,
    startView: "months",
    minViewMode: "months",
    pickTime: false,
    locale: 'br'
});

$('#cc_form input[readonly]').css('background-color', '#fff');

$("#cc_form input[name=cardNumber]").keyup(function(e) {

    var cc = $(this).val().replace(/ /g, '');
    var len = cc.length;

    if (len == 6 || len > 12) {
        getCardBrand(cc);
    } else if (len < 6) {
        $('#card_label').show();
        $('#card_brand').hide();
        removeInstallments();
        $('#cc_form input[name=cardCVC]').attr('maxlength', 3).val('');
    }

}).click(function(event) {
    $(this).attr('maxlength', 19);
});

function getCardBrand(val) {

    PagSeguroDirectPayment.getBrand({
        cardBin: val,
        error: function(res) {
            console.log('Get Brand Error', res, val);
        },
        success: function(res) {
            getCardBrandCallback(res);
        },
        complete: function() {
            $("#cc_form input, #cc_form select").attr('disabled', false);
        }
    });

}

function getCardBrandCallback(res) {

    ccdata['name'] = res.brand.name;
    ccdata['displayName'] = window.options[ccdata['name'].toUpperCase()]['displayName'];
    ccdata['cvvSize'] = res.brand.cvvSize;
    ccdata['expirable'] = res.brand.expirable;
    ccdata['international'] = res.brand.international;

    card_img = window.options[ccdata['name'].toUpperCase()]['images']['SMALL']['path'];

    var max = Math.max.apply(null, res.brand.config.acceptedLengths);

    var val = $('#cc_form input[name=cardNumber]').val();

    if (max) {

        val = val.substr(0, max);

        $('#cc_form input[name=cardNumber]').val(val).attr('maxlength', max);

    }

    max = ccdata['cvvSize'];

    val = $('#cc_form input[name=cardCVC]').val();

    if (max) {

        val = val.substr(0, max);

        $('#cc_form input[name=cardCVC]').val(val).attr('maxlength', max);

    }

    $('#card_brand')
        .attr('src', 'https://stc.pagseguro.uol.com.br/' + card_img)
        .attr('title', ccdata['displayName'])
        .load(
            function() {
                $(this).show();
                $('#card_label').hide();
            });

    getInstallments();

}

function getInstallments() {

    //alert(amount)

    var brand = ccdata['name'];

    if (brand) {

        PagSeguroDirectPayment.getInstallments({
            amount: amount,
            brand: brand,
            //maxInstallmentNoInterest: 1,
            success: function(res) {
                getInstallmentsCallback(res.installments[brand]);
                //console.log(res);
            },
            error: function(res) {
                console.log('Error on getInstallments', res);
            }
        });

    } else {

        console.log('Error on getInstallments');

    }

}

function getInstallmentsCallback(installments) {

    if (installments) {

        removeInstallments();

        var s = '#cc_form select[name=installments]';

        var el = $('#cc_form select[name=installments]').attr("disabled", false);

        for (i in installments) {

            installmentAmount = toFloat(installments[i].installmentAmount);
            totalAmount = toFloat(installments[i].totalAmount);
            quantity = installments[i].quantity;

            el.append($("<option></option>")
                .attr("value", quantity)
                .attr("disabled", false)
                .data('totalAmount', totalAmount)
                .data('installmentAmount', installmentAmount)
                .data('quantity', quantity)
                .text(quantity + 'x de R$' + installmentAmount + ' = R$' + totalAmount));
        }

        if (selected_installment) {
            if ($(s + ' option[value=' + selected_installment + ']').val()) {
                $(s).val(selected_installment);
            }
        }

    }

    $("#cc_form input[name=cardCVC]").attr('maxlength', ccdata['cvvSize']);

}

function removeInstallments() {
    var opt = $("#cc_form option:first");

    $('#cc_form select[name=installments]')
        .empty()
        .append(opt)
        .attr('disabled', true);
}

function startPayment() {

    var cardOwner = getVal('cardOwner').replace(/  /g, ' ').trim();
    var cardNumber = getVal('cardNumber');
    var cardExpiry = getVal('cardExpiry').split('/');
    var cvc = getVal('cardCVC');

    var data = [];

    data['brand'] = ccdata['name'];
    data['cardOwner'] = cardOwner;
    data['cardNumber'] = cardNumber.replace(/ /g, '');
    data['expirationMonth'] = cardExpiry[0];
    data['expirationYear'] = cardExpiry[1];
    data['cvc'] = cvc;

    if (!window.token) {
        createCardToken(data);
    } else createCardTokenCallback(window.token);

}

function createCardToken(data) {

    PagSeguroDirectPayment.createCardToken({
        cardNumber: data['cardNumber'],
        brand: data['brand'],
        cvv: data['cvc'],
        expirationMonth: data['expirationMonth'],
        expirationYear: data['expirationYear'],
        success: function(res) {
            createCardTokenCallback(res.card.token);
        },
        error: function(res) {
            console.log('Error on createCardToken', res);
            processError(res['errors']);
        },
        complete: function() {
            $('#button-confirm').button('reset');
        }
    });
}

function createCardTokenCallback(token) {

    window.token = token;

    var installments = $('#cc_form select[name=installments]').find('option:selected');
    //var totalAmount = installments.data('totalAmount');
    var installmentAmount = installments.data('installmentAmount');
    var quantity = installments.data('quantity');

    $('#form_pagseguro input[name=creditCardToken]').val(token);

    $('#form_pagseguro input[name=installmentQuantity]').val(quantity);
    $('#form_pagseguro input[name=installmentValue]').val(installmentAmount);

    $('#form_pagseguro input[name=creditCardHolderName]').val(getVal('cardOwner'));
    $('#form_pagseguro input[name=creditCardHolderCPF]').val(getVal('cpf'));
    $('#form_pagseguro input[name=creditCardHolderBirthDate]').val(getVal('birthDate'));

    $('#form_pagseguro input[name=creditCardHolderAreaCode]').val($('#form_pagseguro input[name=senderAreaCode]').val());
    $('#form_pagseguro input[name=creditCardHolderPhone]').val($('#form_pagseguro input[name=senderPhone]').val());

    $('#form_pagseguro input[name=billingAddressStreet]').val($('#collapse-payment-address input[name=address_1]').val());
    $('#form_pagseguro input[name=billingAddressNumber]').val($('#collapse-payment-address input[name=address_2]').val());
    $('#form_pagseguro input[name=billingAddressComplement]').val($('#collapse-payment-address input[name=address_2]').val());
    $('#form_pagseguro input[name=billingAddressDistrict]').val($('#collapse-payment-address input[name=address_2]').val());
    $('#form_pagseguro input[name=billingAddressPostalCode]').val($('#collapse-payment-address input[name=postcode]').val());
    $('#form_pagseguro input[name=billingAddressCity]').val($('#collapse-payment-address input[name=city]').val());
    $('#form_pagseguro input[name=billingAddressState]').val($('#collapse-payment-address select[name=zone_id]').find('option:selected').data('sigla'));

    process();

}

$('#cc_form select[name=installments]').change(function(event) {

    selected_installment = $(this).val();

});

$('.numeric').on('input', function(event) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('.onlyname').on('input', function(event) {
    var val = this.value.toUpperCase();
    this.value = val.replace(/[^A-Z|a-z| ]/g, '');
});

function getVal(name) {
    return $('#cc_form *[name=' + name + ']').val();
}

function toFloat(x) {
    return x.toFixed(2);
}
</script>