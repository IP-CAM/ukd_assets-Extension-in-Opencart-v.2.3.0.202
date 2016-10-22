<!-- ukd_pagseguro v1.0 -->
<p>
  <?php echo $text_payment_method; ?>
</p>

<div class="container col-sm-12 ">
  <div class="row">
    <div class="col-sm-4 funkyradio-primary funkyradio" title="Boleto Bancário">
      <input type="radio" name="pagseguro_method" id="blt" value="boleto" checked="checked" />
      <label for="blt">Boleto Bancário</label>
    </div>
    <div class="col-sm-4 funkyradio-primary funkyradio" title="Cartão de Crédito">
      <input type="radio" name="pagseguro_method" id="cc" value="creditCard" />
      <label for="cc">Cartão de Crédito</label>
    </div>
    <div class="col-sm-4 funkyradio-primary funkyradio" title="Débito Online">
      <input type="radio" name="pagseguro_method" id="tt" value='eft' />
      <label for="tt">Débito Online</label>
    </div>
  </div>

  <center class="visible-sm visible-md visible-lg" style="margin-top:40px">
    <img src="https://stc.pagseguro.uol.com.br/public/img/banners/pagamento/todos_animado_550_50.gif" alt="Logotipos de meios de pagamento do PagSeguro" title="Este site aceita pagamentos com Visa, MasterCard, Diners, American Express, Hipercard, Aura, Elo, PLENOCard, PersonalCard, BrasilCard, FORTBRASIL, Cabal, Mais!, Avista, Grandcard, Sorocred, Bradesco, Itaú, Banco do Brasil, Banrisul, Banco HSBC, saldo em conta PagSeguro e boleto.">
  </center>
  <div class="row">
    <hr />
    <input class="hidden" name="payment_method" value="ukd_pagseguro" checked="checked" type="radio" hidden />
    <textarea name="comment" rows="8" class="hidden form-control" hidden><?php echo $comment; ?></textarea>
    <input class="hidden" type="checkbox" name="agree" value="1" checked="checked" hidden />
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>
<hr />
<script>
if(!window.pagseguro_method){
  window.pagseguro_method = $('#collapse-payment-method input[name=pagseguro_method]:checked').val();
}else{
  $('#collapse-payment-method input[name=pagseguro_method]').each(function(index, el) {

    if($(this).val() ==  window.pagseguro_method){
      $(this).prop('checked', true);
    }else{
      $(this).prop('checked', false);
    }

  });

}

$('#collapse-payment-method input[name=pagseguro_method]').click(function(event) {

  window.pagseguro_method = $(this).val();

});

</script>