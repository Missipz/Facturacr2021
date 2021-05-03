<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar | Clase4</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a class="h1">
        <img src="<?=base_url()?>/plantilla/dist/img/logo.png" width="50%">
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">
        Si usted olvido su contraseña, digite el correo asociado a su cuenta, se le enviará un link para recuperarla.
      </p>
      <form method="post" id="frmRecuperar">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo" name="correo" id="correo">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Recuperar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="<?=base_url()?>/login/login">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url()?>/plantilla/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>/plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>/plantilla/dist/js/adminlte.min.js"></script>

<script type="text/javascript">
  $(document).on("submit", "#frmRecuperar", function(e){
    e.preventDefault();
    $.ajax({
      "url": "<?=base_url()?>/usuarios/recuperar",
      "method": "POST",
      "data": $("#frmRecuperar").serialize(),
      "dataType": "json",
    }).done(function (respuesta) {
      if(respuesta==1){
       alert("correo enviado")
      }else{
        alert("Error al enviar el correo");
      }
    });
  });
</script>

</body>
</html>
