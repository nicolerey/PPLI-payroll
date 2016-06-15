<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HRSO | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <?= plugin_css('bootstrap/css/bootstrap.min.css')?>
    <!-- Theme style -->
    <?= css('AdminLTE.min.css')?>
    <!-- custom style -->
    <?= css('custom.css')?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a ><b>SEA</b> Olympus</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">HR Online System</p>
        <div class="alert alert-danger hidden">
          <ul class="list-unstyled">
            
          </ul>
        </div>
        <form data-action="<?= base_url('login/attempt')?>">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="ID Number" name="id_number">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <button type="submit" class="btn btn-danger btn-block btn-flat">Log in</button>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <?= plugin_script('jQuery/jQuery-2.1.4.min.js')?>
    <!-- Bootstrap 3.3.5 -->
  	<?= plugin_script('bootstrap/js/bootstrap.min.js')?>
    <script type="text/javascript">

    (function($){
      $(document).ready(function(){
        $('form').submit(function(e){
          e.preventDefault();
          var that = $(this);
          var messageBox = $('.alert');
          $('[type=submit]').attr('disabled', 'disabled');
          $.post(that.data('action'), that.serialize())
          .done(function(response){
            if(response.result){
              window.location.href = '<?= base_url('home')?>';
            }else{
              messageBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
            }
          })
          .fail(function(){
            alert('An internal error has occured. Please try again in a few moments.');
          })
          .always(function(){
            $('[type=submit]').removeAttr('disabled');
            $('[type=password]').val('');
          });
        });
      })
    })(jQuery)

    </script>
  </body>
</html>
