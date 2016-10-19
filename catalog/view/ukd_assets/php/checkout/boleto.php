<?php
require_once '../security.php';
?>
<script>

function validate(){
  return true;
}
function startPayment() {
  process();
}

function onFinishPayment(res){

  window.location = locationURL + '&boleto=' + res['paymentLink'];
  //alert(locationURL + '&link=' + res['paymentLink']);

}

</script>