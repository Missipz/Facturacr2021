<?= $this->extend('base')?>

<?= $this->section('header')?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Usuarios</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item active">Agregar</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<?= $this->endSection() ?>


<?= $this->section('body')?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
        	<div class="card-header">
        		<h5 class="card-title">Datos del usuario</h5>
        	</div>
        	<form id="frmRegistro" method="post">
	          	<div class="card-body">
	          		<div class="row">
	          			<div class="col-4">
	          				<label>Nombre</label>
	          				<input class="form-control" type="text" id="nombre" name="nombre" required>
	          			</div>
	          			<div class="col-4">
	          				<label>Correo</label>
	          				<input class="form-control" type="text" id="correo" name="correo" required>
	          			</div>
	          			<div class="col-4">
	          				<label>Pass</label>
	          				<input class="form-control" type="text" id="pass" name="pass" required>
	          			</div>
	          		</div>
	          		<div class="row">
	          			<div class="col-3">
	          				<label>Rol</label>
	          				<select class="form-control" id="id_rol" name="id_rol" required>
	          					<option value="">-</option>
	          					<?php foreach($roles as $key => $rol): ?>
	          						<option value="<?=$rol->id_rol?>"><?=$rol->rol?></option>
	          					<?php endforeach; ?>
	          				</select>
	          			</div>
	          		</div>
	          	</div>
	          	<div class="card-footer" align="right">
	          		<button type="submit" class="btn btn-success">Agregar</button>
	          	</div>
          	</form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts')?>

<script type="text/javascript">
  $(document).on("submit", "#frmRegistro", function(e){
    e.preventDefault();
      $.ajax({
        "url": "<?=base_url()?>/usuarios/nuevoUsuario",
        "method": "POST",
        "data": $("#frmRegistro").serialize(),
        "dataType": "json",
      }).done(function (respuesta) {
        if(respuesta>0){
        	alert("Usuario agregado");
        }else{
          alert("Ha ocurrido un error en el registro");
        }
      });
  });
</script>


<?= $this->endSection() ?>