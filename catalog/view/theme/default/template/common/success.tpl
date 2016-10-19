<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $text_message; ?>
      <hr />
      <div id="btns" class="buttons">
        <div class="pull-right"><a  id="btn_continue" href="<?php echo $continue; ?>" class="btn btn-primary btn-lg" disabled><?php echo $button_continue; ?></a></div>
        <?php if(isset($_GET['boleto'])) { ?>
        <div class="pull-left"><a id="btn_pagseguro" href="<?php echo $_GET['boleto'] ?>" target="_blank" class="btn btn-success btn-lg" disabled>Imprimir Boleto</a></div>
        <?php } ?>
        <?php if(isset($_GET['eft'])) { ?>
        <div class="pull-left"><a id="btn_pagseguro" href="<?php echo $_GET['eft'] ?>" target="_blank" class="btn btn-success btn-lg" disabled>Fazer Débito Online</a></div>
        <?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
window.ukd_fn = window.ukd_fn || [];
window.ukd_fn.push(function() {
  var btn = $('#btn_pagseguro');
  if(btn){
    $(btn).click(function(event) {
      location = '<?php echo $continue; ?>';
    });
  }
  $('#btns > div > a').attr('disabled', false);
});
</script>
<?php echo $footer; ?>