<?= $this->extend('base')?>

<?= $this->section('header')?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Usuarios</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item active">Listado</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('body')?>
<div class="row container-fluid">
	<div class="card col-md-12">
		<div class="card-header">
			<h1 class="card-title">Listado de usuarios</h1>
		</div>

		<div class="card-body">
			<table class="table table-hover table-bordered table-sm">
				<thead>
					<tr>
						<th>Cod usuario</th>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Rol</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($usuarios as $key => $usuario): ?>
  						<tr>
							<td><?=$usuario->id_usuario?></td>
							<td><?=$usuario->nombre?></td>
							<td><?=$usuario->correo?></td>
							<td><?=$usuario->rol?></td>
							<?php if($usuario->activo==1): ?>
								<td><span class="badge badge-success"><?=$usuario->estado?></span></td>
							<?php else:; ?>
								<td><span class="badge badge-danger"><?=$usuario->estado?></span></td>
							<?php endif; ?>
							<td>
								<button class="btn btn-warning btn-sm editar" value="<?=$usuario->id_usuario?>">Editar</button>
							</td>
						</tr>
  					<?php endforeach; ?>
					
					
				</tbody>
			</table>
		</div>

	</div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modalEditar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="frmGuardar" method="post">
	      	<div class="modal-body">
	          	<div class="card-body">
	          		<div class="row">
	          			<div class="col-12" hidden>
	          				<label>Id usuario</label>
	          				<input class="form-control" type="hidden" id="id_usuario" name="id_usuario" readonly  required>
	          			</div>
	          			<div class="col-12">
	          				<label>Nombre</label>
	          				<input class="form-control" type="text" id="nombre" name="nombre" required>
	          			</div>
	          			<div class="col-12">
	          				<label>Correo</label>
	          				<input class="form-control" type="text" id="correo" name="correo" required>
	          			</div>
	          		</div>
	          		<div class="row">
	          			<div class="col-12">
	          				<label>Rol</label>
	          				<select class="form-control" id="id_rol" name="id_rol" required>
	          					<?php foreach($roles as $key => $rol): ?>
	          						<option value="<?=$rol->id_rol?>"><?=$rol->rol?></option>
	          					<?php endforeach; ?>
	          				</select>
	          			</div>
	          			<div class="col-12">
	          				<label>Activo</label>
	          				<select class="form-control" id="activo" name="activo" required>
	          					<option value="1">Activo</option>
	          					<option value="0">Inactivo</option>
	          				
	          				</select>
	          			</div>
	          		</div>
	          	</div>
	      	</div>
	    	<div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
		        <button type="submit" class="btn btn-primary">Guardar</button>
	     	</div>
	  	</form>
    </div>
  </div>
</div>


<?= $this->endSection() ?>


<?= $this->section('scripts')?>

<script type="text/javascript">
	$(document).on("click", ".editar", function(){

		$.ajax({
	        "url": "<?=base_url()?>/usuarios/selectUsuarioId",
	        "method": "POST",
	        "data": {"id_usuario": this.value, "mivarible": "hola" },
	        "dataType": "json",
      	}).done(function (respuesta) {
      		$.each(respuesta, function( key, value ){
      			$("#"+key).val(value);
      		})

	        $("#modalEditar").modal('show');
      	});
	});



	$(document).on("submit", "#frmGuardar", function(e){
	    e.preventDefault();
	      $.ajax({
	        "url": "<?=base_url()?>/usuarios/editarUsuario",
	        "method": "POST",
	        "data": $("#frmGuardar").serialize(),
	        "dataType": "json",
	      }).done(function (respuesta) {
	       	if(respuesta==1){
	       		location.reload();
	       	}else{
	       		alert("Nada para actualizar");
	       		$("#modalEditar").modal('hide');
	       	}
	      });
	  });
</script>






<?= $this->endSection() ?>