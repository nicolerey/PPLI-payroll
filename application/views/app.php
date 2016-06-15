<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $tab_title ?> HRSO </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <?= plugin_css('bootstrap/css/bootstrap.min.css')?>
    <!-- Font Awesome -->
    <?= plugin_css('font-awesome/css/font-awesome.min.css')?>
    <!-- Theme style -->
    <?= css('AdminLTE.min.css')?>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <?= css('skins/skin-red.min.css')?>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css')?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-datetimepicker-smalot/css/bootstrap-datetimepicker.min.css')?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/x_editable/bootstrap3-editable/css/bootstrap-editable.css')?>"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>Payroll</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Payroll</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->

  
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="<?= base_url('assets/img/display-photo-placeholder.png')?>" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?= user_full_name()  ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="<?= base_url('assets/img/display-photo-placeholder.png')?>" class="img-circle" alt="User Image">
                    <p>
                      <?= user_full_name() ?>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?= base_url('logout')?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <?php $this->load->view('blocks/sidebar', ['active_nav' => $active_nav])?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?= $content ?>

        <!-- Main content -->
        
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Default to the left -->
        Payroll System &copy; 2016
      </footer>

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <?= plugin_script('jQuery/jQuery-2.1.4.min.js')?>
    <!-- Bootstrap 3.3.5 -->
    <?= plugin_script('bootstrap/js/bootstrap.min.js')?>
    <!-- Slimscroll -->
    <?= plugin_script('slimScroll/jquery.slimscroll.min.js')?>
    <!-- FastClick -->
    <?= plugin_script('fastclick/fastclick.min.js')?>
    <!-- AdminLTE App -->
    <?= script('app.min.js')?>

    <!-- Page level plugins  -->
    <?php if(!empty($plugin_scripts)):?>
        <?php foreach($plugin_scripts AS $script):?>
            <?= plugin_script($script)?>
        <?php endforeach;?>
    <?php endif;?>

    <!-- Page level scripts  -->
    <?php if(!empty($page_scripts)):?>
        <?php foreach($page_scripts AS $script):?>
            <?= script($script)?>
        <?php endforeach;?>
    <?php endif;?>



  </body>
</html>
