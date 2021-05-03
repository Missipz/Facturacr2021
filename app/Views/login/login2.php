<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Clase4</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/dist/css/adminlte.min.css">
  <link rel="icon" href="<?=base_url()?>/plantilla/dist/img/logo.png" sizes="16x16" type="image/png">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a class="h1">
        <img src="<?=base_url()?>/plantilla/dist/img/logo.png" width="50%">
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Inicio de sesion</p>

      <form method="post" id="frmLogin">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Correo" id="correo" name="correo">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" id="pass" name="pass">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Iniciar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      

      <p class="mb-1">
        <a href="<?=base_url()?>/login/recuperar">Olvide mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="<?=base_url()?>/login/registro" class="text-center">Registrarme</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url()?>/plantilla/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>/plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>/plantilla/dist/js/adminlte.min.js"></script>

<script type="text/javascript">
  $(document).on("submit", "#frmLogin", function(e){
    e.preventDefault();
    $.ajax({
      "url": "<?=base_url()?>/login/verificar",
      "method": "POST",
      "data": $("#frmLogin").serialize(),
      "dataType": "json",
    }).done(function (respuesta) {
      if(respuesta==1){
        window.location.href = "<?=base_url()?>/inicio/inicio";
      }else{
        alert("Usuario o pass no validos");
      }
    });
  });
</script>

</body>
</html>
