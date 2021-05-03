

<?= $this->extend('base') ?>

<?= $this->section('header') ?>
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
                          <li class="breadcrumb-item active" aria-current="page">Crear Factura</li>
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

<?= $this->section('body') ?>
<div class="content">
  <div class="container-fluid">
    <form id="frmFacturar" method="post">
      <!--encabezado-->
      <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-info">
                <h5 class="card-title">Encabezado</h5>
              </div>
              <div class="card-body">
                <div class="row">

                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-user"></i></span>
                        </div>
                        <select class="form-control" id="id_cliente" name="id_cliente" required>
                          <option value="">Seleccione Cliente</option>
                          <?php foreach ($clientes as $key => $cliente): ?>
                            <option value="<?=$cliente->id_cliente?>"><?=$cliente->razon?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                        </div>
                        <select class="form-control" id="moneda" name="moneda" required>
                          <option value="">Selecione Moneda</option>
                          <option value="CRC">Colones</option>
                          <option value="USD">Dolares</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                        </div>
                        <input class="form-control" id="tipo_cambio" name="tipo_cambio" readonly required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-cash-register"></i></span>
                        </div>
                        <select class="form-control" id="medio_pago" name="medio_pago" required>
                          <option value="">Selecione Medio Pago</option>
                          <option value="01">Efectivo</option>
                          <option value="04">Transferencia</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                        </div>
                        <select class="form-control" id="condicion_venta" name="condicion_venta" required>
                          <option value="">Condicion Venta</option>
                          <option value="01">Contado</option>
                          <option value="02">Credito 30 dias</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>  
      </div>

      <!--Detalles-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header bg-info">
              <h5 class="card-title">Detalles del documento</h5>
              <button type="button" class="btn btn-secondary float-right btn-sm agregarLinea">
                <i class="fas fa-plus"></i> Nueva Linea
              </button>
            </div>
            <div class="card-body">
              <div class="row table-responsive">
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>Codigo</th>
                      <th>Detalle</th>
                      <th>Unid</th>
                      <th>Cantidad</th>
                      <th>Precio</th>
                      <th>Neto</th>
                      <th>Desc. %</th>
                      <th>Monto Desc.</th>
                      <th>Subtotal</th>
                      <th>Imp. %</th>
                      <th>Monto Imp.</th>
                      <th>Total Linea</th>
                      <th width="3px"></th>
                    </tr>
                  </thead>
                  <tbody id="tblDetalles">
                    <tr id="linea1" class="linea">
                      <td>
                        <select class="form-control" name="codigo[]" required>
                          <option value="8344900000000">8344900000000</option>
                        </select>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="detalle[]" required>
                      </td>
                      <td width="6%">
                        <select class="form-control" name="unidad[]" required>
                          <option value="Unid">Unid</option>
                          <option value="Sp">Sp</option>
                        </select>
                      </td>
                      <td width="5%">
                        <input class="form-control cantidad calcular" min="1" type="number" name="cantidad[]" required>
                      </td>
                      <td width="10%">
                        <input class="form-control precio calcular" min="0" type="number" name="precio_unidad[]" required>
                      </td>
                      <td width="10%">
                        <input class="form-control neto" type="number" name="monto_total[]" readonly required>
                      </td>
                      <td width="8%">
                        <input class="form-control descP calcular" max="100" min="0" type="number" value="0">
                      </td>
                      <td width="10%">
                        <input class="form-control descM" type="number" name="monto_descuento[]"  readonly required>
                      </td>
                      <td width="10%">
                        <input class="form-control subtotal" type="number" name="sub_total[]" readonly required>
                      </td>
                      <td width="8%">
                        <input class="form-control impP" type="number"  value="13" name="tarifa[]" readonly required>
                      </td>
                      <td width="10%">
                        <input class="form-control impM" type="number" readonly name="monto_impuesto[]" required>
                      </td>
                      <td width="10%">
                        <input class="form-control totalL" type="number"  readonly  name="total_linea[]" required>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm btn-danger eliminarLinea" disabled>
                          <i class="fas fa-times-circle"></i>
                        </button>
                        </td>
                    </tr>
                  </tbody>
                  <tfoot class="table-sm sinBorde" >
                    <tr>
                      <td colspan="11" align="right">Neto</td>
                      <td align="right" class="lblNeto">0</td>
                    </tr>
                    <tr>
                      <td colspan="11" align="right">Descuentos</td>
                      <td align="right" class="lblDescuentos">0</td>
                    </tr>
                    <tr>
                      <td colspan="11" align="right">Subtotal</td>
                      <td align="right" class="lblSubtotal">0</td>
                    </tr>
                    <tr>
                      <td colspan="11" align="right">IVA</td>
                      <td align="right" class="lblIVA">0</td>
                    </tr>
                    <tr>
                      <td colspan="11" align="right">Exnonerado</td>
                      <td align="right" class="lblExnonerado">0</td>
                    </tr>
                    <tr>
                      <td colspan="10"><input type="text" name="notas" placeholder="Observaciones" class="form-control bg-gray color-palette text-white"></td>
                      <td align="right">Total</td>
                      <td align="right" class="lblTotal">0</td>
                    </tr>
                    
                  </tfoot>
                </table>
              </div>
              <div class="row">
                <div class="col-12" style="text-align: center;">
                  <button  type="submit" class="btn btn-success"> <i class="fas fa-check-circle"></i> Generar Documento</button>
                </div>
              </div>
            </div>
          </div>
        </div>  
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script type="text/javascript">

  $(document).on('change','#moneda',function(){
    var moneda= this.value;
    if (moneda!=""){
      Pace.track(function () {
        $.ajax({
          "url": "https://api.hacienda.go.cr/indicadores/tc/dolar",
          "method": "GET",
          "dataType": "json"
        }).done(function (response) {
          if (moneda=="CRC") {
            $("#tipo_cambio").val(response.compra.valor);
          }else if(moneda=="USD"){
            $("#tipo_cambio").val(response.venta.valor);
          }
          
        });
      });
    }else{
      $("#tipo_cambio").val("");
    }
  });

  $(document).on('keyup change','.calcular',function(){
    var cantidad= $(this).parents(".linea").find(".cantidad").val();
    var precio= $(this).parents(".linea").find(".precio").val();
    
    var neto= cantidad*precio;
    var descP= $(this).parents(".linea").find(".descP").val();
    var descM= (neto*descP)/100;
    var subtotal= neto-descM;
    var impP= $(this).parents(".linea").find(".impP").val();
    var impM= (subtotal*impP)/100;
    var totalL= subtotal+impM;

    $(this).parents(".linea").find(".neto").val(neto);
    $(this).parents(".linea").find(".descM").val(descM);
    $(this).parents(".linea").find(".subtotal").val(subtotal);
    $(this).parents(".linea").find(".impM").val(impM);
    $(this).parents(".linea").find(".totalL").val(totalL);

    totales();
  });

  function totales(){
    neto=0;
    descuentos=0;
    subtotal=0
    IVA=0;
    total=0;

    $(".table tbody .linea").each(function(i, item) {
      neto += parseFloat($(item).find(".neto").val());
      descuentos += parseFloat($(item).find(".descM").val());
      subtotal += parseFloat($(item).find(".subtotal").val());
      IVA += parseFloat($(item).find(".impM").val());
      total += parseFloat($(item).find(".totalL").val());
    });
    $(".lblNeto").text(parseFloat(neto).toFixed(2));
    $(".lblDescuentos").text(parseFloat(descuentos).toFixed(2));
    $(".lblSubtotal").text(parseFloat(subtotal).toFixed(2));
    $(".lblIVA").text(parseFloat(IVA).toFixed(2));
    $(".lblTotal").text(parseFloat(total).toFixed(2));
  }


  $(document).on('click','.agregarLinea',function(){ 
    $(".linea").first().clone().appendTo('#tblDetalles');
    $(".linea").last().find("input, select").prop('value', "");
    $(".linea").last().find(".impP").val('13');
    $(".linea").last().find(".descP").val('0');
    $(".eliminarLinea").prop('disabled', false);
  });

  $(document).on('click','.eliminarLinea',function(){ 
    $(this).parents(".linea").remove();
    if ($(".linea").length == 1) {
      $(".eliminarLinea").removeClass("btn-light-danger").addClass("btn-light-dark");
      $(".eliminarLinea").prop('disabled', true);
    }else{
      $(".eliminarLinea").removeClass("btn-light-dark").addClass("btn-light-danger");
      $(".eliminarLinea").prop('disabled', false);
    }
      totales();
  });



$("#frmFacturar").on('submit', function(event) {
  event.preventDefault();
  $.ajax({
    url: '<?=base_url()?>/facturar/generarFactura',
    type: 'POST',
    dataType: 'json',
    data: $('#frmFacturar').serialize(),
  })
  .done(function(response) {
    if (response.status>=200 && response.status<300) {

      $.ajax({
        url: '<?=base_url()?>/facturar/actualizar_estado_envio_en_bd',
        type: 'POST',
        dataType: 'json',
        data: {"clave": response.clave},
      });

      Swal.fire({
        title: 'La factura fue enviada correctamente',
        text: "¿Desea validarla ahora?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Más Tarde'
      }).then((result) => {
        if (result.isConfirmed) { ///este necesita cambiarse a una función aparte
          $.ajax({
            url: '<?=base_url()?>/facturar/validarXmlDesatendido',
            type: 'POST',
            dataType: 'json',
            data: {"clave": response.clave},
          })
          .done(function(respuesta) {
            if (respuesta.estado=="aceptado") {
                Toast.fire({
                  icon: 'success',
                  title: 'Estado:',
                  text: 'La factura ha sido aceptada por hacienda.'
                });
                $.ajax({
                  url: '<?=base_url()?>/facturar/actualizar_respuesta_hacienda',
                  type: 'POST',
                  dataType: 'json',
                  data: {"clave": response.clave, "ind_estado":1,},
                });                 
            } else if (respuesta.estado=="rechazado") {
                Swal.fire({
                  icon: 'warning',
                  title: 'Estado:',
                  text: respuesta.estado,
                })
                $.ajax({
                  url: '<?=base_url()?>/facturar/actualizar_respuesta_hacienda',
                  type: 'POST',
                  dataType: 'json',
                  data: {"clave": response.clave, "ind_estado":2,},
                }); 
            }
          }); 
        }       
        location.href = "<?=base_url()?>/facturar/listadoFacturas";
      })
    }else{
      Swal.fire({
        title: 'Ocurrió un error al enviar el documento?',
        text: "Por favor, revise el listado de documentos e intente enviarlo de nuevo",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
      }).then((result) => {
        if (result.isConfirmed) {
          location.href = "<?=base_url()?>/facturar/listadoFacturas";
        }
      })
    }

  });
  
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

<?= $this->endSection() ?>


