ok<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=extension/shipping/ukd_correios/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function(resp) {
			$('#button-confirm').button('reset');
      // for( i in resp){
      //   console.log(resp[i], i);
      // }
		},
		success: function() {
			//location = '<?php echo $continue; ?>';
		}
	});
});
//--></script>