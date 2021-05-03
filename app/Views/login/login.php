<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?=base_url()?>/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url()?>/assets/libs/css/style.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a href="<?=base_url()?>/index.html"><img class="logo-img" src="<?=base_url()?>/assets/images/logo.png" alt="logo"></a><span class="splash-description">Ingrese su información de usuario.</span></div>
            <div class="card-body">
                <form method="post" id="frmLogin">
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="user" name="user" type="text" placeholder="Usuario" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="pass" name="pass" type="password" placeholder="Contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Iniciar Sesión</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Crear Una Cuenta</a></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Olvidé Mi Pass</a>
                </div>
            </div>
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="<?=base_url()?>/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="<?=base_url()?>/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
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