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
      <div id="eft_options"></div>
    </div>
  </div>
</div>
<br />
<?php
$jsonString = json_encode( $_REQUEST['jsonString'] );
?>

<script>
// name: "HSBC", displayName: "Banco HSBC", status: "AVAILABLE", code: 307, images: Object ['MEDIUM':{path:x, size:x},'SMALL':{path:x, size:x}]

var data = JSON.parse(<?php echo $jsonString ?>);

//console.log(data);

for(i in data){

  if(data[i].status == 'AVAILABLE'){

    //console.log(data[i].displayName);

    var displayName =  data[i].displayName;

    var img =  data[i].images.MEDIUM.path;

    var name =  data[i].name;

    var content = '<div class="col-sm-6 funkyradio-primary funkyradio" title="' + displayName + '" ><input id=' + name + ' type="radio" name="bankname"  value="'+ name + '" /><label for="'+ name +'"><img class="bank-img" src="' + img_url + img + '" /><span class="xs-span ">'+ displayName +'</span></label></div>';

    $('#eft_options').append(content);

  }

}

$('#collapse-checkout-confirm input[name=bankname]').click(function(event) {

  $('#form_pagseguro input[name=bankName]').val($(this).val());

});

</script>