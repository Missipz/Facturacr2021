<!DOCTYPE html>
<html>
<head>
    <title>Factura # <?=$documento->clave;?></title>
    <link rel="stylesheet" type="text/css" href="">
</head>

<body>

    <table width="100%">
        <tr>
            <td width="33.3%">
                <img src="data:image/png;base64,'<?=$logo?>'" width="100px">
            </td>
            <td align="center" width="33.3%"><h1>Factura Electronica</h1></td>
            <td align="right">
                <h2>Factura # <?=$documento->consecutivo;?></h2>
                <h4>Fecha: <?=$documento->fecha;?> </h4>
               <img src="data:image/png;base64,'<?=$qr?>'" width="80px" />
            </td>
        </tr>
    </table>

    <table width="100%" >
        <tr><td><u>Emisor</u></td></tr>
        <tr>
            <td><b>Cedula</b>: <?=$documento->emisor_cedula;?></td>
            <td><b>Nombre</b>: <?=$documento->emisor_nombre;?></td>
            <td><b>Telefono</b>: <?=$documento->emisor_telefono;?></td>
        </tr>
        <tr>
            <td><b>Correo</b>: <?=$documento->emisor_correo;?></td>
            <td colspan="2"><b>Dirección</b>: <?=$documento->emisor_otras_senas;?> </td>
        </tr>
        <tr><td><u>Receptor</u></td></tr>
        <tr>
            <td><b>Cedula</b>:  <?=$documento->receptor_cedula;?></td>
            <td><b>Nombre</b>: <?=$documento->receptor_nombre;?></td>
            <td><b>Telefono</b>:  <?=$documento->receptor_telefono;?></td>
        </tr>
        <tr>
            <td><b>Correo</b>: <?=$documento->receptor_correo;?></td>
            <td colspan="2"><b>Dirección</b>: <?=$documento->receptor_otras_senas;?> </td>
        </tr>
    </table>
    <br><br>


    <table width="100%" style="font-size: 0.7rem">
        <thead>
            <tr>
                <td>#</td>
                <td>Detalle</td>
                <td>Unid</td>
                <td>Cantidad</td>
                <td>Precio</td>
                <td>Descuento</td>
                <td>Subtotal</td>
                <td>IVA</td>
                <td>Total Linea</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $key => $detalle): ?>
                <tr>
                    <td><?=$detalle->linea?></td>
                    <td><?=$detalle->detalle?></td>
                    <td><?=$detalle->unidad_medida?></td>
                    <td><?=number_format($detalle->cantidad,2,",",".")?></td>
                    <td><?=number_format($detalle->precio_unidad,2,",",".")?></td>
                    <td><?=number_format($detalle->monto_descuento,2,",",".")?></td>
                    <td><?=number_format($detalle->sub_total,2,",",".")?></td>
                    <td><?=number_format($detalle->impuesto_neto,2,",",".")?></td>
                    <td align="right"><?=number_format($detalle->total_linea,2,",",".")?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr >
                <td colspan="8" align="right"><b>Sub Total</b></td>
                <td align="right">¢<?=number_format($documento->total_venta,2,",",".") ?></td>
            </tr>
             <tr >
                <td colspan="8" align="right"><b>Descuento</b></td>
                <td align="right">¢<?=number_format($documento->total_descuentos,2,",",".") ?></td>
            </tr>
            <tr >
                <td colspan="8" align="right"><b>IVA (13%) </b></td>
                <td align="right">¢<?=number_format($documento->total_impuestos,2,",",".") ?> </td>
            </tr>
            <tr >
                <td colspan="8" align="right"><b>Total</b></td>
                <td align="right">¢<?=number_format($documento->total_comprobante,2,",",".") ?> </td>
            </tr>
        </tfoot>
        
    </table>

    <small>Clave: <?=$documento->clave;?></small><br>
    <small>Moneda: <?=$documento->moneda;?></small><br>
    <small>Tipo Cambio: <?=number_format($documento->tipo_cambio,2,",",".") ?></small><br>
    <small>Notas: <?= $documento->notas;?></small>
    <br>
    <small><u>Monto a pagar en Dolares= <?=number_format(($documento->total_comprobante/$documento->tipo_cambio),2,",",".") ?></u></small>

    <small>Para verificar <?=$url?></small>

</body>
</html>