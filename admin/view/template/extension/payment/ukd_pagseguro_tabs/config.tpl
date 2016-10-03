<div class="form-group">
  <label class="col-sm-2 control-label" for="input-status">
    <?php echo $entry_status; ?>
  </label>
  <div class="col-sm-8">
    <select name="ukd_pagseguro_status" id="input-status" class="form-control">
      <?php if ($ukd_pagseguro_status) { ?>
      <option value="1" selected="selected">
        <?php echo $text_enabled; ?>
      </option>
      <option value="0">
        <?php echo $text_disabled; ?>
      </option>
      <?php } else { ?>
      <option value="1">
        <?php echo $text_enabled; ?>
      </option>
      <option value="0" selected="selected">
        <?php echo $text_disabled; ?>
      </option>
      <?php } ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-status">
    <?php echo 'Sandbox'; ?>
  </label>
  <div class="col-sm-8">
    <select name="ukd_pagseguro_sandbox_enabled" id="input-status" class="form-control">
      <?php if ($ukd_pagseguro_sandbox_enabled) { ?>
      <option value="1" selected="selected">
        <?php echo $text_enabled; ?>
      </option>
      <option value="0">
        <?php echo $text_disabled; ?>
      </option>
      <?php } else { ?>
      <option value="1">
        <?php echo $text_enabled; ?>
      </option>
      <option value="0" selected="selected">
        <?php echo $text_disabled; ?>
      </option>
      <?php } ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-order-status">
    <?php echo $entry_order_status; ?>
  </label>
  <div class="col-sm-8">
    <select name="ukd_pagseguro_order_status_id" id="input-order-status" class="form-control">
      <?php foreach ($order_statuses as $order_status) { ?>
      <?php if ($order_status['order_status_id'] == $ukd_pagseguro_order_status_id) { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
        <?php echo $order_status['name']; ?>
      </option>
      <?php } else { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>">
        <?php echo $order_status['name']; ?>
      </option>
      <?php } ?>
      <?php } ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-sort-order">
    <?php echo $entry_sort_order; ?>
  </label>
  <div class="col-sm-8">
    <input type="text" name="ukd_pagseguro_sort_order" value="<?php echo $ukd_pagseguro_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
  </div>
</div>

<!-- Token -->
<div class="form-group required">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_min_amount ?>"><?php echo 'Total' ?></span></label>
  <div class="col-sm-8">
    <input name="ukd_pagseguro_min_amount" type="text" class="form-control" value="<?php echo $ukd_pagseguro_min_amount ?>" />
    <?php if ($error_min_amount) { ?>
    <div class="text-danger">
      <?php
      echo $error_min_amount;
      $error_config_tab = true;
      ?>
    </div>
    <?php } ?>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
  <div class="col-sm-8">
    <select name="ukd_pagseguro_geo_zone_id" id="input-geo-zone" class="form-control">
      <option value="0"><?php echo $text_all_zones; ?></option>
      <?php foreach ($geo_zones as $geo_zone) { ?>
      <?php if ($geo_zone['geo_zone_id'] == $ukd_pagseguro_geo_zone_id) { ?>
      <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
  </div>
</div>
