<?php
require_once '../security.php';
?>
<style>
/* xs */
.xs-span {
  display:block;
  position: relative;
  left: -40px;
}
.bank-img{
  margin-left: 30px;
  float: left;
  width: 68px;
}
/* sm */
@media only screen and (max-width: 399px) {
    .bank-img {
      display: none;
    }
    .xs-span {
      margin-left: 40px;
      font-size: 90%;
    }

}

@media only screen and (max-width: 330px) {
    .bank-img {
      display: block;
      margin-left: 35%;
    }
    .xs-span {
      display: none;
    }

}
</style>
<div class="panel panel-default">
  <div class="panel-heading">DÉBITO ONLINE</div>
  <div class="panel-body" style="margin-bottom:15px">
    <div>
    Selecione seu Banco para pagamento.
    </div>
    <div id="eft_options"><p><center><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw" style="font-size:48px;color:#ccc"></i></center></p></div>
  </div>
</div>

<script>


function init_eft(){
    var data = window.options;

    $('#eft_options').empty();

    for(i in data){

      if(data[i].status == 'AVAILABLE'){

        //console.log(data[i].displayName);

        var displayName =  data[i].displayName;

        var img =  img_url + data[i].images.MEDIUM.path;

        var name =  data[i].name;

        var content = '<div class="col-sm-6 funkyradio-primary funkyradio" title="' + displayName + '" ><input id=' + name + ' type="radio" name="bankname"  value="'+ name + '" /><label for="'+ name +'"><img class="bank-img" src="' + img + '" /><span class="xs-span ">'+ displayName +'</span></label></div>';

        $('#eft_options').append(content);

      }

    }

    $('#collapse-checkout-confirm input[name=bankname]').click(function(event) {

      $('#form_pagseguro input[name=bankName]').val($(this).val());

    });


}

function startPayment() {

  process();
}

function validate(){

    if (!$('#form_pagseguro input[name=bankName]').val()) {
        alert('Selecione um Banco!');
        return false;
    }

    return true;

}

function onFinishPayment(res){

  // var win = window.open(res['paymentLink'], '_blank');
  // if(win)win.focus();
    window.location = locationURL + '&eft=' + res['paymentLink'];

}

</script>