<?php
//ukd_25c60571
ob_implicit_flush (1);
ob_end_flush();
?>
  <!DOCTYPE html>
  <!--[if IE]><![endif]-->
  <!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
  <!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
  <!--<![endif]-->

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
      <?php echo $title; ?>
    </title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>

    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <!-- <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> -->
    <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
    <link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
    <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <!-- <script src="catalog/view/javascript/common.js" type="text/javascript"></script> -->
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>

    <?php foreach ($analytics as $analytic) { ?>
    <?php echo $analytic; ?>
    <?php } ?>
  </head>
  <?php ?>

  <body class="<?php echo $class; ?>">
    <datalist id='scripts'><?php foreach ($scripts as $script) { ?><option value="<?php echo $script; ?>"></option><?php } ?><option value="catalog/view/javascript/common.js"></option></datalist>
    <nav id="top">
      <!-- <div class="container"> -->
      <?php echo $currency; ?>
      <?php echo $language; ?>

      <div id="top-links" class="nav pull-right">
        <ul class="list-inline">

          <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-diamond"></i> <span class="hidden-xs"><?php echo $text_account; ?></span> <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <?php if ($logged) { ?>
              <li>
                <a href="<?php echo $account; ?>">
                  <?php echo $text_account; ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $order; ?>">
                  <?php echo $text_order; ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $transaction; ?>">
                  <?php echo $text_transaction; ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $download; ?>">
                  <?php echo $text_download; ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $logout; ?>">
                  <?php echo $text_logout; ?>
                </a>
              </li>
              <?php } else { ?>
              <li>
                <a href="<?php echo $register; ?>">
                  <?php echo $text_register; ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $login; ?>">
                  <?php echo $text_login; ?>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs"><?php echo $text_wishlist; ?></span></a></li>
          <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-bag"></i> <span class="hidden-xs"><?php echo $text_shopping_cart; ?></span></a></li>
          <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs"><?php echo $text_checkout; ?></span></a></li>
        </ul>
      </div>
      <!-- </div> -->
    </nav>

    <header>
      <div class="container">
        <div class="col-md-3">
          <div id="logo">
            <?php if ($logo) { ?>
            <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-9 ">
          <div class="col-sm-7 ">
            <?php echo $search; ?>
          </div>
          <div class="col-sm-4  ">
            <center>
              <?php echo $cart; ?>
            </center>
          </div>
          <div class="col-sm-1 ">
          <?php if ($logged) { ?>
          <a href="<?php echo $logout; ?>"><i class="fa fa-sign-out" style="color:#FFB2DB; font-size:44px" aria-hidden="true"></i></a>
          <?php } else { ?>
          <a href="<?php echo $login; ?>"><i class="fa fa-sign-in" style="color:#FFB2DB; font-size:44px" aria-hidden="true"></i></a>
          <?php } ?>

          </div>
        </div>
      </div>
    </header>

    <?php if ($categories) { ?>
    <div style="background-color:#592B56">
      <nav id="menu" class="container navbar">
        <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
          <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav">
            <?php foreach ($categories as $category) { ?>
            <?php if ($category['children']) { ?>
            <li class="dropdown">
              <a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo $category['name']; ?>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-inner">
                  <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                  <ul class="list-unstyled">
                    <?php foreach ($children as $child) { ?>
                    <li>
                      <a href="<?php echo $child['href']; ?>">
                        <?php echo $child['name']; ?>
                      </a>
                    </li>
                    <?php } ?>
                  </ul>
                  <?php } ?>
                </div>
                <a href="<?php echo $category['href']; ?>" class="see-all">
                  <?php echo $text_all; ?>
                  <?php echo $category['name']; ?>
                </a>
              </div>
            </li>
            <?php } else { ?>
            <li>
              <a href="<?php echo $category['href']; ?>">
                <?php echo $category['name']; ?>
              </a>
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
      </nav>
    </div>
    <?php } ?>
    <div id="home-info" class="container hide">

      <div class="col-md-3 col-sm-4 col-xs-4 hidden-xs2 nopadding">
        <i class="col-sm-4 fa fa-truck hidden-xs2" aria-hidden="true"></i>
        <div class="col-sm-8 nopadding"><strong>Frete grátis</strong><br /><span><a href="#">Veja as condições aqui</a></span></div>
      </div>

      <div class="col-md-3 hidden-sm hidden-xs hidden-xs2 nopadding">
        <i class="col-sm-4 fa fa-facebook-official hidden-xs2" aria-hidden="true"></i>
        <div class="col-sm-8 nopadding"><strong>Facebook</strong><br /><span><a href="#">Curta nossa página</a></span></div>
      </div>

      <div class="col-md-3 col-sm-4 col-xs-4  col-xs2-6 hidden-xs3 nopadding">
        <i class="col-sm-4 fa fa-whatsapp hidden-xs2" aria-hidden="true"></i>
        <div class="col-sm-8 nopadding"><strong>Whatsapp</strong><br /><span><a href="tel:+5571987186507">(71) 98719 6507</a></span></div>
      </div>


      <?php if (!$logged) { ?>
      <div class="col-md-3 col-sm-4 col-xs-4  col-xs2-6 col-xs3-12 nopadding">
        <i class="col-sm-4 fa fa-sign-in hidden-xs2" aria-hidden="true"></i>
        <div class="col-sm-8 nopadding"><strong>Casdastre-se já!</strong><br /><span><a href="<?php echo $login; ?>">Acesse sua conta</a></span></div>
      </div>
      <?php }else{ ?>
      <div class="col-md-3 col-sm-4 col-xs-4  col-xs2-6 col-xs3-12 nopadding">
         <!-- <i class="col-sm-4 fa fa-diamond hidden-xs2 " aria-hidden="true"></i>  -->
        <div id="login-info" class="col-sm-8 nopadding pull-right"><span>Olá, <?php echo $username ?><br /><a href="<?php echo $account; ?>"><?php echo $text_account ?></a> | <a href="<?php echo $logout; ?>">Sair</a></span></div>
      </div>
      <?php } ?>

    </div>