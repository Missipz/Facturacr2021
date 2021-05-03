<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro | Clase4</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>/plantilla/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?=base_url()?>/plantilla/index2.html" class="h1">
        <img src="<?=base_url()?>/plantilla/dist/img/logo.png" width="50%">
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Registro</p>

      <form id="frmResgistro" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Nombre" id="nombre" name="nombre" required >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo" id="correo" name="correo" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" id="pass" name="pass" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirme contraseña" id="repass" name="repass" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="icheck-primary">
              <input type="checkbox" id="acepto" name="acepto" value="agree" required>
              <label for="acepto">
               Estoy de acuerdo con los <a href="#">Terminos</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Registrarme</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      
      <a href="<?=base_url()?>/login/login" class="text-center">Ya estoy registrado</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="<?=base_url()?>/plantilla/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>/plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>/plantilla/dist/js/adminlte.min.js"></script>

<script type="text/javascript">
  $(document).on("submit", "#frmResgistro", function(e){
    e.preventDefault();
    //validar
    var pass= $("#pass").val();
    var repass= $("#repass").val();
    if(pass==repass){
      $.ajax({
        "url": "<?=base_url()?>/login/registrarme",
        "method": "POST",
        "data": $("#frmResgistro").serialize(),
        "dataType": "json",
      }).done(function (respuesta) {
        if(respuesta>0){
          window.location.href = "<?=base_url()?>/inicio/inicio";
        }else{
          alert("Ha ocurrido un error en el registro");
        }
      });
    }else{
       alert("Error al verificar contraseñas");
    }
  });
</script>

</body>
</html>
