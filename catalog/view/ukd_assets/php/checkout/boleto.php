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
  var url = res['paymentLink'];
window.open(url, '_blank');
}

</script>