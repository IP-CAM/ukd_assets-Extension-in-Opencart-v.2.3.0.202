<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ukd-pagseguro" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
          <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
          </a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <!-- Success -->
    <?php if ($success_warning) {  ?>
    <div class="alert alert-success">
      <i class="fa fa-exclamation-circle"></i>
      <?php echo $success_warning ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } elseif ($error_warning) { ?>
    <div class="alert alert-danger">
      <i class="fa fa-exclamation-circle"></i>
      <?php echo $error_warning ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
  </div>

  <div class="container-fluid">

    <!-- Nav -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a data-toggle="tab" id="config_tab" href="#config" role="tab" data-toggle="tab">
          <?php echo 'Config' ?>
        </a>
      </li>
      <!-- <li role="presentation">
        <a data-toggle="tab" id="production_tab" href="#production" role="tab" data-toggle="tab">
          <?php echo 'Production' ?>
        </a>
      </li>
      <li role="presentation">
        <a data-toggle="tab" id="sandbox_tab" href="#sandbox" role="tab" data-toggle="tab">
          <?php echo 'Sandbox' ?>
        </a>
      </li> -->
    </ul>

  </div>

  <div class="container-fluid">
    <!-- <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div> -->
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ukd-pagseguro" class="form-horizontal">

        <?php
          $url = str_replace('/', DIRECTORY_SEPARATOR,'view/template/extension/shipping/ukd_correios_tabs/');
          $config_tab = $url.'config.tpl';
          $production_tab = $url.'production.tpl';
          $sandbox_tab = $url.'sandbox.tpl';
        ?>

        <div class="tab-content">

          <div id="config" role="tabpanel" class="tab-pane active">
            <?php include $config_tab; ?>
          </div>

          <div id="production" role="tabpanel" class="tab-pane">
            <?php //include $production_tab ?>
          </div>

          <div id="sandbox" role="tabpanel" class="tab-pane">
            <?php //include $sandbox_tab ?>
          </div>

        </div>

        <input id='current_tab' name='current_tab' type="hidden" value="<?php echo $current_tab ?>"  />


      </form>
    </div>
  </div>
  <!-- </div> -->
</div>
<script type="text/javascript">


  $('#<?php echo $current_tab ?>').tab('show');

  <?php if (isset($error_sandbox_tab)) { ?>

  $('#sandbox_tab').append('<span class="text-danger glyphicon glyphicon-asterisk" aria-hidden="true"></span>').tab('show');

  <?php } ?>

  <?php if (isset($error_production_tab)) { ?>

  $('#production_tab').append('<span class="text-danger glyphicon glyphicon-asterisk" aria-hidden="true"></span>').tab('show');

  <?php } ?>

  <?php if (isset($error_config_tab)) { ?>

  $('#config_tab').append('<span class="text-danger glyphicon glyphicon-asterisk" aria-hidden="true"></span>').tab('show');

  <?php } ?>

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $(".alert").slideUp(500);
    $("#current_tab").val($(this).attr('id'));
  })


</script>
<?php echo $footer; ?>
