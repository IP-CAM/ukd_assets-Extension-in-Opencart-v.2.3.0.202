<?php
require_once '../security.php';
?>
<div class="container nopadding">


<div class="col-xs-12 col-sm-6 col-md-6 nopadding">
<div class="panel panel-success" >
  <div class="panel-heading display-table">
    <div class="row display-tr">
        <span style="font-size:12px; margin-left:20px">DADOS DO CARTÃO DE CRÉDITO</span>
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
            <label for="cardExpiry"><span class="hidden-xs">DATA DE EXPIRAÇÂO</span><span class="visible-xs-inline">DATA DE EXP.</span></label>
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
<div class="hidden-xs col-sm-6 col-md-6">


<img src="https://stc.pagseguro.uol.com.br/public/img/banners/seguranca/seguranca_125x125.gif" alt="Banner PagSeguro" title="Compre com pagSeguro e fique sossegado">
<img src="https://stc.pagseguro.uol.com.br/public/img/banners/divulgacao/125x125_10X_pagseguro.gif" alt="Banner PagSeguro" title="Parcele suas compras em até 18x">
</div>
</div>



<script>

window.options = [];

var card_img = '';

var ccdata = [];
ccdata['numberSize'] = 13;
ccdata['cvvSize'] = 3;

var selected_installment = '';

var frm_pay = '#form-payment';

//customer_payment_address
if( $(frm_pay + ' input[name=payment_address][type=radio]') ){
  var payment_address_data = $(frm_pay + ' select[name=address_id]').find('option:selected').data('address');
  if(payment_address_data){
    window.customer_payment_address = payment_address_data;
    //console.log(payment_address_data);
  }
}

function validate() {

    $('#cc_form .form-group').removeClass('has-error');
    $('#cc_form .input-group').removeClass('has-error');

    var error = [];

    var cardOwner = $('#cc_form input[name=cardOwner]').val();

    if (cardOwner.length < 5) {
        error['cardOwner'] = 'Nome do titular deve ter no mínimo ' + 5 + ' digitos';
    }
    else if (cardOwner.indexOf(' ') == -1) {
        error['cardOwner'] = 'Nome do titular incompleto';
    }

    var cpf = $('#cc_form input[name=cpf]').val();

    if (cpf.length < 11) {
        error['cpf'] = 'Número do CPF deve ter 11 digitos';
    }else if(!window.validaCPF(cpf)){
        error['cpf'] = 'CPF inválido';
    }

    var dt = new Date();

    dt = new Date(dt.setYear(dt.getYear() - 18));

    var el = $('#cc_form input[name=birthDate]');

    var b = el.val().split('/');

    if (!b[0] || !b[1] || !b[2]) {
        error['birthDate'] = 'Preencha o campo <i>data de nascimento</i>';
    } else if (b[0] > 31) {
        error['birthDate'] = 'Dia de nascimento inválido';
    } else if (b[1] > 12) {
        error['birthDate'] = 'Mês de nascimento inválido';
    } else if (b[2] == dt.getFullYear() && b[1] > dt.getMonth() + 1) {
        error['birthDate'] = 'Mês de nascimento inválido';
    } else if (b[1] == dt.getMonth() + 1 && b[0] > dt.getDate() && b[2] >= dt.getFullYear()) {
        error['birthDate'] = 'É necessário ser maior de idade (18+)';
    } else if (b[2] > dt.getFullYear()) {
        error['birthDate'] = 'É necessário ser maior de idade (18+)';
    } else if (b[2] < new Date().getFullYear() - 100) {
        error['birthDate'] = 'Data de nascimento inválida';
    }

    dt = new Date();

    el = $('#cc_form input[name=cardExpiry]');

    var e = el.val().split('/');

    if (!e[0] || !e[1]) {
        error['cardExpiry'] = 'Preencha o campo <i>data expiração</i>';
    } else if (e[0] > 12) {
        error['cardExpiry'] = 'Data de expiração inválida';
    } else if (e[1] < dt.getFullYear()) {
        error['cardExpiry'] = 'Data de expiração inválida';
    } else if (e[1] == dt.getFullYear() && e[0] < dt.getMonth() + 1) {
        error['cardExpiry'] = 'Data de expiração inválida';
    }

    var numberSize = $('#cc_form input[name=cardNumber]').val();

    if (numberSize.length < ccdata['numberSize']) {
        error['cardNumber'] = 'Número do cartão deve ter no mínimo ' + ccdata['numberSize'] + ' digitos';
    }

    var cvvSize = $('#cc_form input[name=cardCVC]').val();

    if (cvvSize.length < ccdata['cvvSize']) {
        error['cardCVC'] = 'Número de segurança deve ter no mínimo ' + ccdata['cvvSize'] + ' digitos';
    }

    var installments = $('#cc_form select[name=installments]').val();

    if (installments == 0 && ccdata['name']) {
        error['installments'] = 'Selecione o número de parcelas';
    }

    var content = '';

    for (i in error) {
        console.log(error, error[i]);
        content += error[i] + '.<br />';
        $('#cc_form *[name=' + i + ']').parent().addClass('has-error');

    }

    if (content) {
        errorAlert(content);
        return false;
    }

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
    minDate: new Date(dt.setMonth(dt.getMonth())),
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

    ccdata['name'] = ''

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

    var min = Math.min.apply(null, res.brand.config.acceptedLengths);

    ccdata['numberSize'] = min;

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


    //console.log('customer_payment_address', window.customer_payment_address);

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




    if( window.customer_payment_address){

      console.log(window.customer_payment_address);

      var billingAddressStreet = window.customer_payment_address['address_1'];
      var billingAddressNumber = window.customer_payment_address['address_2'];
      var billingAddressComplement = window.customer_payment_address['address_1'];
      var billingAddressDistrict = window.customer_payment_address['address_2'];
      var billingAddressPostalCode = window.customer_payment_address['postcode'];
      var billingAddressCity = window.customer_payment_address['city'];
      var billingAddressState = window.customer_payment_address['zone_code'];

    }else{

      var billingAddressStreet = $(frm_pay + ' input[name=address_1]').val();
      var billingAddressNumber = $(frm_pay + ' input[name=address_2]').val();
      var billingAddressComplement = $(frm_pay + ' input[name=address_1]').val();
      var billingAddressDistrict = $(frm_pay + ' input[name=address_2]').val();
      var billingAddressPostalCode = $(frm_pay + ' input[name=postcode]').val();
      var billingAddressCity = $(frm_pay + ' input[name=city]').val();
      var billingAddressState = $(frm_pay + ' select[name=zone_id]').find('option:selected').data('sigla');

    }

    //Default values for optional fields
    if(!billingAddressNumber) billingAddressNumber = 's/n';
    if(!billingAddressComplement) billingAddressComplement = 'n/a';
    if(!billingAddressDistrict) billingAddressDistrict = 'n/a';

    $('#form_pagseguro input[name=billingAddressStreet]').val(billingAddressStreet);
    $('#form_pagseguro input[name=billingAddressNumber]').val(billingAddressNumber);
    $('#form_pagseguro input[name=billingAddressComplement]').val(billingAddressComplement);
    $('#form_pagseguro input[name=billingAddressDistrict]').val(billingAddressDistrict);
    $('#form_pagseguro input[name=billingAddressPostalCode]').val(billingAddressPostalCode);
    $('#form_pagseguro input[name=billingAddressCity]').val(billingAddressCity);
    $('#form_pagseguro input[name=billingAddressState]').val(billingAddressState);

    process();

}

function onFinishPayment(res){

  console.log('Finished');
  window.location = locationURL;

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