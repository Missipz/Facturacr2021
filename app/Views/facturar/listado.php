<?= $this->extend('base')?>

<?= $this->section('header')?>
  <!-- ============================================================== -->
  <!-- pageheader  -->
  <!-- ============================================================== -->
  <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="page-header">
              <h2 class="pageheader-title">Zona De Facturación </h2>
              <div class="page-breadcrumb">
                  <nav aria-label="breadcrumb">
                      <ol class="breadcrumb ml-auto">
                          <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Facturación</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Listado</li>
                      </ol>
                  </nav>
              </div>
          </div>
      </div>
  </div>
  <!-- ============================================================== -->
  <!-- end pageheader  -->
  <!-- ============================================================== -->
<?= $this->endSection() ?>


<?= $this->section('body')?>
<div class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header dflex">
        <h3 class="card-title-purple">Listado de Facturas</h3>
        <div id="datatable_buttons" class="ml-auto">
          
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="tabla_listado_facturas" class="table table-bordered table-hover">
          <thead>
            <tr>
                <th>ID</th>
                <th>Tipo de Documento</th>
                <th>Consecutivo</th>
                <th>Clave</th>
                <th>Fecha de emisión</th>
                <th>Cédula Cliente</th>
                <th>Nombre Cliente</th>
                <th>SubTotal Factura</th>
                <th>Descuento</th>
                <th>Subtotal</th>
                <th>Impuestos</th>
                <th>Total</th>
                <th>Estado Envío</th>
                <th>Respuesta</th>
                <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
                <th>ID</th>
                <th>Tipo de Documento</th>
                <th>Consecutivo</th>
                <th>Clave</th>
                <th>Fecha de emisión</th>
                <th>Cédula Cliente</th>
                <th>Nombre Cliente</th>
                <th>SubTotal Factura</th>
                <th>Descuento</th>
                <th>Subtotal</th>
                <th>Impuestos</th>
                <th>Total</th>
                <th>Estado Envío</th>
                <th>Respuesta</th>
                <th>Acciones</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->   
</div> 
<?= $this->endSection() ?>

<?= $this->Section('scripts'); ?>
<script type="text/javascript">

$(document).ready(function() {
  var tabla=$('#tabla_listado_facturas').DataTable({
    "language": {"url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json" },
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,   ////select_opciones_busqueda
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "order": [ [0, "desc"] ],
    "destroy":true,
    "processing":true,
    "ajax": {
        url: '<?= base_url("facturar/obtenerListadoFacturas") ?>',
        type: 'GET',
    },
    "columnDefs": [
          {
                "targets": -1,
                "orderable": false,
                "data": null,
                'render': function(data, type, row, meta) {
                    return '<button type="button" class="btn btn-danger btn-sm ver_pdf" data-toggle="tooltip" data-placement="left" title="Ver PDF" value="'+row[3]+'"><i class="fas fa-file-pdf"></i>\
            </button>\
            <button type="button" class="btn btn-info btn-sm boton_ver_detalles" data-toggle="tooltip" data-placement="top" title="Ver detalles" value="'+row[3]+'"><i class="fas fa-book-open"></i>\
            </button>\
            <button type="button" class="btn btn-danger btn-sm boton_verificar_respuesta" data-toggle="tooltip" data-placement="top" title="Verificar respuesta hacienda" value="'+row[3]+'"><i class="fas fa-tools"></i>\
            </button>'
                }
          }, 
          {
                "targets": 1,
                "orderable": false,
                'render': function(data, type, row, meta) {
                  if(row[1]=="01"){
                    return '<span class="badge badge-pill badge-success">Factura Electrónica</span>';
                  }else if(row[1]=="02"){
                    return '<span class="badge badge-pill badge-primary">Nota de crédito</span>';
                  }
                }
          },
          {
                "targets": 12,
                "orderable": false,
                'render': function(data, type, row, meta) {
                  if(row[12]==0){
                    return '<span class="badge badge-pill badge-danger">No enviado</span>';
                  }else if(row[12]==1){
                    return '<span class="badge badge-pill badge-primary">Enviado</span>';
                  }
                }
          },
          {
                "targets": 13,
                "orderable": false,
                'render': function(data, type, row, meta) {
                  if(row[13]==0){
                    return '<span class="badge badge-pill badge-warning">Procesando</span>';
                  }else if(row[13]==1){
                    return '<span class="badge badge-pill badge-success">Aceptado</span>';
                  }else if(row[13]==2){
                    return '<span class="badge badge-pill badge-danger">Rechazado</span>';
                  }
                }
          },           
        ],
  });
});

$(document).on('click', '.ver_pdf', function(event) {
  event.preventDefault();
  window.open("<?= base_url()?>/facturar/facturaPdf/"+this.value,'','width=800,height=800,left=50,top=50,toolbar=yes').print();
});

$(document).on('click', '.boton_ver_detalles', function(event) {
  event.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
      }
    })
});

$(document).on('click', '.boton_verificar_respuesta', function(event) {
  event.preventDefault();
  $.ajax({
    url: '<?=base_url()?>/facturar/validarXmlDesatendido',
    type: 'POST',
    dataType: 'json',
    data: {"clave": this.value},
  })
  .done(function(res) {
    if (res.estado=="aceptado") {
      actualizar_respuesta_bd(res.clave, 1)
    } else if (res.estado=="rechazado") {
      actualizar_respuesta_bd(res.clave, 2)
    }
  })
});

function actualizar_respuesta_bd(clave, ind_estado){
    $.ajax({
    url: '<?=base_url()?>/facturar/actualizar_respuesta_hacienda',
    type: 'POST',
    dataType: 'json',
    data: {"clave": clave, "ind_estado": ind_estado,},
  }); 
  tabla.ajax.reload();
}

</script>
<?= $this->endSection(); ?>