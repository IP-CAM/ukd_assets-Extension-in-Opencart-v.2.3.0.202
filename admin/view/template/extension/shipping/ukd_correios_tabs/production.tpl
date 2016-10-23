<!--
********************************************************************************
   UKD - UKITA DEVELOPMENT
--------------------------------------------------------------------------------
   Extension : Ukd Pagseguro
   Ext. Code : ukd_pagseguro_209004f2 
   Filename  : ukd_pagseguro\upload\admin\view\template\extension\payment\ukd_pagseguro_tabs\production.tpl
   Author    : Fred Ukita
   Date      : Tuesday 13th of September 2016 07:52:06 PM 
********************************************************************************
-->
<div class="container-fluid">

  <!-- Email -->
  <div class="form-group required">
    <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_email ?>"><?php echo $entry_email ?></span></label>
    <div class="col-sm-8">
      <input name="ukd_pagseguro_production_email" type="text" class="form-control" value="<?php echo $ukd_pagseguro_production_email ?>" />
      <?php if ($error_production_email) { ?>
      <div class="text-danger">
        <?php
        echo $error_production_email;
        $error_production_tab = true;
        ?>
      </div>
      <?php } ?>
    </div>
  </div>

  <!-- Token -->
  <div class="form-group required">
    <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_token ?>"><?php echo $entry_token ?></span></label>
    <div class="col-sm-8">
      <input name="ukd_pagseguro_production_token" type="text" class="form-control" value="<?php echo $ukd_pagseguro_production_token ?>" />
      <?php if ($error_production_token) { ?>
      <div class="text-danger">
        <?php
        echo $error_production_token;
        $error_production_tab = true;
        ?>
      </div>
      <?php } ?>
    </div>
  </div>

</div>
