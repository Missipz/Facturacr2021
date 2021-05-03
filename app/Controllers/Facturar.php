<?php

namespace App\Controllers;
use \DomDocument;
use \App\Libraries\Firmador;
use App\Libraries\pdf;
use App\Libraries\myqr;
use App\Models\ClientesModel;
use App\Models\ConsecutivosModel;
use App\Models\EmpresasModel;
use App\Models\DocumentosModel;
use App\Models\DocumentoDetallesModel;

class Facturar extends BaseController{
	// *************************** Vistas ************************//
		public function crear(){
			if(session()->get('id_usuario')>0){

				$ClientesModel= new ClientesModel();
				$data = array(
					'clientes' => $ClientesModel->selectClientes(), 
				);
				return view("facturar/crear", $data);	
			}else{
				return redirect()->to(base_url('login/login'));
			}
		}

		public function listadoFacturas(){
			if(session()->get('id_usuario')>0){
				return view("facturar/listado");	
			}else{
				return redirect()->to(base_url('login/login'));
			}
		}

	
	// *************************** Finaliza Vistas ************************//


	// *************************** Factura electrónica ************************//

					//Lo dividimos en pasos para entender mejor

	//Paso 1: Generar el xml de la factura en base a los datos obtenidos en la view de facturación.

	    public function generarFactura(){  //Una vez que se crea la factura en la view, se llama esta función
    	$id_cliente= $_POST['id_cliente'];
    	$moneda= $_POST['moneda'];
    	$tipo_cambio= $_POST['tipo_cambio'];
    	$medio_pago= $_POST['medio_pago'];
        $condicion_venta= $_POST['condicion_venta'];
        $notas= $_POST['notas'];
        $dias=0;
        $sucursal="001";
        $pv="00001";
        if ($condicion_venta=="02") {
        	$dias=30;
        }

        //para el consecutivo: Buscamos en la factura el último consecutivo que se generó
        //para asignarlo como el sigiente consecutivo a ingresar
        $id_tipo_documento="01";
        $ConsecutivosModel = new ConsecutivosModel();
        $ConsecutivosModel->setAmbiente(getenv('factura.ambiente'));
        $ConsecutivosModel->setTipoDocumento($id_tipo_documento);

        $selectConsecutivo= $ConsecutivosModel->selectConsecutivo(); //aquí obtenemos el consecutivo a utilizar
        $ConsecutivosModel->setConsecutivo($selectConsecutivo->consecutivo+1); //le sumamos uno y atualizamos la bd para dejarlo listo para la siguiente factura.
        $ConsecutivosModel->actualizarConsecutivo();
        $factura= str_pad($selectConsecutivo->consecutivo,10,"0",STR_PAD_LEFT); //le damos el formato requerido por hacienda a nuestra bd
        $consecutivo= $sucursal.$pv.$id_tipo_documento.$factura; //creamos el consecutivo con los datos exigidos por hacienda
        
        //para la clave: procedemos a crear la clave de la factura con el formato establecido por hacienda
        $EmpresasModel= new EmpresasModel();
        $EmpresasModel->setIdEmpresa(1); //Seleccionamos la empresa desde la bd
        $emisor=  $EmpresasModel->selectEmpresa(); //Nuestro emisor es el resultado de la consulta anterior.

        //procedemos a asignar los datos que necesitamos del emisor en las variables, para utilizarlos más adelante
		$cod=$emisor->codigo_telefono;
		$fecha=date('dmy');
		$cedulaEmisor=str_pad($emisor->identificacion,12,"0",STR_PAD_LEFT); //cedula seteada a 12 digitos, con ceros que se rellenan a la izquierda
		$situacion="1";
		$codigoSeguridad= substr(str_shuffle("0123456789"), 0, 8); //Numero de 8 caracteres generado aleatoriamente

		$clave= $cod.$fecha.$cedulaEmisor.$consecutivo.$situacion.$codigoSeguridad; //clave de 50 caracteres, generada a partir de los datos determininados por hacienda.


		//datos de receptor: Seleccionamos de la bd los datos que corresponden al cliente asignado a la factura en la vista.
		$ClientesModel= new ClientesModel();
        $ClientesModel->setIdCliente($id_cliente);// $id_cliente se recogió via post al principio de este método
        $receptor=  $ClientesModel->selectCliente(); //asignamos los datos del cliente a la variable $receptor

        //calcula totales: Llamamos la función totalesPost y le enviamos todos los datos recogidos en el post de este método. Nota: $_POST hace referencia a todos los datos recogidos via post.
        $totales= json_decode($this->totalesPost($_POST)); //recogemos los resultados generados y los asignamos a la variable totales
        

        //insertalo en la bd: UNA VEZ RECOGIDOS Y GENERADOS TODOS LOS DATOS ANTERIORES, PROCEDEREMOS A GUARDAR EL DOCUMENTO EN LA BASE DE DATOS

        //inserta documento
        $DocumentosModel= new DocumentosModel();
        $DocumentosModel->setConsecutivo($consecutivo);
        $DocumentosModel->setTipoDocumento($id_tipo_documento);
        $DocumentosModel->setClave($clave);
        $DocumentosModel->setCodigoSeguridad($codigoSeguridad);
        $DocumentosModel->setFecha(date('c'));
        $DocumentosModel->setEmisorNombre($emisor->razon);
        $DocumentosModel->setEmisorCedula($emisor->identificacion);
        $DocumentosModel->setEmisorTipo($emisor->id_tipo_identificacion);
        $DocumentosModel->setEmisorComercial($emisor->nombre_comercial);
        $DocumentosModel->setEmisorIdProvincia($emisor->cod_provincia);
        $DocumentosModel->setEmisorIdCanton($emisor->cod_canton);
        $DocumentosModel->setEmisorIdDistrito($emisor->cod_distrito);
        $DocumentosModel->setEmisorIdBarrio($emisor->cod_barrio);
        $DocumentosModel->setEmisorOtrasSenas($emisor->otras_senas);
        $DocumentosModel->setEmisorCod($emisor->codigo_telefono);
        $DocumentosModel->setEmisorTelefono($emisor->telefono);
        $DocumentosModel->setEmisorCorreo($emisor->correo);
        $DocumentosModel->setReceptorNombre($receptor->razon);
        $DocumentosModel->setReceptorCedula($receptor->identificacion);
        $DocumentosModel->setReceptorTipo($receptor->id_tipo_identificacion);
        $DocumentosModel->setReceptorComercial($receptor->nombre_comercial);
        $DocumentosModel->setReceptorIdProvincia($receptor->cod_provincia);
        $DocumentosModel->setReceptorIdCanton($receptor->cod_canton);
        $DocumentosModel->setReceptorIdDistrito($receptor->cod_distrito);
        $DocumentosModel->setReceptorIdBarrio($receptor->cod_barrio);
        $DocumentosModel->setReceptorOtrasSenas($receptor->otras_senas);
        $DocumentosModel->setReceptorCod($receptor->codigo_telefono);
        $DocumentosModel->setReceptorTelefono($receptor->telefono);
        $DocumentosModel->setReceptorCorreo($receptor->correo);
        $DocumentosModel->setCondicionVenta($condicion_venta);
        $DocumentosModel->setPlazoCredito($dias);
        $DocumentosModel->setMedioPago($medio_pago);
        $DocumentosModel->setMoneda($moneda);
        $DocumentosModel->setTipoCambio($tipo_cambio);
        $DocumentosModel->setServiciosGravados($totales->totalServGravados);
        $DocumentosModel->setServiciosExentos($totales->totalServExentos);
        $DocumentosModel->setServiciosExonerados($totales->totalServExonerado);
        $DocumentosModel->setMercanciasGravadas($totales->totalMercanciasGravadas);
        $DocumentosModel->setMercanciasExentas($totales->totalMercanciasExentas);
        $DocumentosModel->setMercanciasExoneradas($totales->totalMercExonerada);
        $DocumentosModel->setTotalGravado($totales->totalGravado);
        $DocumentosModel->setTotalExento($totales->totalExento);
        $DocumentosModel->setTotalExonerado($totales->totalExonerado);
        $DocumentosModel->setTotalVenta($totales->totalVenta);
        $DocumentosModel->setTotalDescuentos($totales->totalDescuentos);
        $DocumentosModel->setTotalVentaNeta($totales->totalVentaNeta);
        $DocumentosModel->setTotalImpuestos($totales->totalImpuesto);
        $DocumentosModel->setTotalComprobante($totales->totalComprobante);
        $DocumentosModel->setNotas($notas);
        $DocumentosModel->setIdUsuario(session()->get('id_usuario'));
        $DocumentosModel->setEnvioAtv(0);
        $DocumentosModel->setValidoAtv(0);
        $id_documento= $DocumentosModel->insertarDocumento();

        //documentos detalles
        if ($id_documento>0) {
        	foreach ($_POST['codigo'] as $key => $linea) {
                $DocumentoDetallesModel= new DocumentoDetallesModel();
                $DocumentoDetallesModel->setIdDocumento($id_documento);
                $DocumentoDetallesModel->setLinea($key+1);
                $DocumentoDetallesModel->setCodigo($_POST['codigo'][$key]);
                $DocumentoDetallesModel->setDetalle($_POST['detalle'][$key]);
                $DocumentoDetallesModel->setUnidadMedida($_POST['unidad'][$key]);
                $DocumentoDetallesModel->setCantidad($_POST['cantidad'][$key]);
                $DocumentoDetallesModel->setPrecioUnidad($_POST['precio_unidad'][$key]);
                $DocumentoDetallesModel->setMontoTotal($_POST['cantidad'][$key] * $_POST['precio_unidad'][$key]);
                $DocumentoDetallesModel->setMontoDescuento($_POST['monto_descuento'][$key]);
                $DocumentoDetallesModel->setMotivoDescuento("Descuento cliente");
                $DocumentoDetallesModel->setSubTotal($_POST['sub_total'][$key]);
                $DocumentoDetallesModel->setCodigoImpuesto("01");
                $DocumentoDetallesModel->setCodigoTarifa("08");
                $DocumentoDetallesModel->setTarifa($_POST['tarifa'][$key]);
                $DocumentoDetallesModel->setMontoImpuesto($_POST['monto_impuesto'][$key]);
                $DocumentoDetallesModel->setImpuestoNeto($_POST['monto_impuesto'][$key]);
                $DocumentoDetallesModel->setTotalLinea($_POST['total_linea'][$key]);
                $DocumentoDetallesModel->insertarDocumentoDetalle();
            }
        }


        //genero el xml
		$stringXML='<?xml version="1.0" encoding="utf-8"?>
		<FacturaElectronica xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica">
		    <Clave>'.$clave.'</Clave>
		    <CodigoActividad>'.$emisor->cod_actividad.'</CodigoActividad>
		    <NumeroConsecutivo>'.$consecutivo.'</NumeroConsecutivo>
		    <FechaEmision>'.date("c").'</FechaEmision>
		    <Emisor>
		        <Nombre>'.$emisor->razon.'</Nombre>
		        <Identificacion>
		            <Tipo>'.$emisor->id_tipo_identificacion.'</Tipo>
		            <Numero>'.$emisor->identificacion.'</Numero>
		        </Identificacion>
		        <NombreComercial>'.$emisor->nombre_comercial.'</NombreComercial>
		        <Ubicacion>
		            <Provincia>'.$emisor->cod_provincia.'</Provincia>
		            <Canton>'.str_pad($emisor->cod_canton,2,"0",STR_PAD_LEFT).'</Canton>
		            <Distrito>'.str_pad($emisor->cod_distrito,2,"0",STR_PAD_LEFT).'</Distrito>
		            <Barrio>'.str_pad($emisor->cod_barrio,2,"0",STR_PAD_LEFT).'</Barrio>
		            <OtrasSenas>La maquina</OtrasSenas>
		        </Ubicacion>
		        <Telefono>
		            <CodigoPais>'.$emisor->codigo_telefono.'</CodigoPais>
		            <NumTelefono>'.$emisor->telefono.'</NumTelefono>
		        </Telefono>
		        <CorreoElectronico>'.$emisor->correo.'</CorreoElectronico>
		    </Emisor>
		    <Receptor>
		       <Nombre>'.$receptor->razon.'</Nombre>
		        <Identificacion>
		            <Tipo>'.$receptor->id_tipo_identificacion.'</Tipo>
		            <Numero>'.$receptor->identificacion.'</Numero>
		        </Identificacion>
		        <NombreComercial>'.$receptor->nombre_comercial.'</NombreComercial>
		        <Ubicacion>
		            <Provincia>'.$receptor->cod_provincia.'</Provincia>
		            <Canton>'.str_pad($receptor->cod_canton,2,"0",STR_PAD_LEFT).'</Canton>
		            <Distrito>'.str_pad($receptor->cod_distrito,2,"0",STR_PAD_LEFT).'</Distrito>
		            <Barrio>'.str_pad($receptor->cod_barrio,2,"0",STR_PAD_LEFT).'</Barrio>
		            <OtrasSenas>La maquina</OtrasSenas>
		        </Ubicacion>
		        <Telefono>
		            <CodigoPais>'.$receptor->codigo_telefono.'</CodigoPais>
		            <NumTelefono>'.$receptor->telefono.'</NumTelefono>
		        </Telefono>
		        <CorreoElectronico>'.$receptor->correo.'</CorreoElectronico>
		    </Receptor>
		    <CondicionVenta>'.$condicion_venta.'</CondicionVenta>
		    <PlazoCredito>'.$dias.'</PlazoCredito>
		    <MedioPago>'.$medio_pago.'</MedioPago>
		    <DetalleServicio>';	



	    	foreach ($_POST['codigo'] as $key => $linea) {
	    		$montoTotal= $_POST['precio_unidad'][$key] * $_POST['cantidad'][$key];
	    		$descuento= $_POST['monto_descuento'][$key];
	    		$subTotal=$_POST['sub_total'][$key];
	    		$montoImpuesto= $_POST['monto_impuesto'][$key];
	    		$montoTotalLinea= $_POST['total_linea'][$key];
	   
	    		$stringXML.='<LineaDetalle>
		            <NumeroLinea>'.($key+1).'</NumeroLinea>
		            <Codigo>'.$_POST['codigo'][$key].'</Codigo>
		            <Cantidad>'.$_POST['cantidad'][$key].'</Cantidad>
		            <UnidadMedida>'.$_POST['unidad'][$key].'</UnidadMedida>
		            <Detalle>'.$_POST['detalle'][$key].'</Detalle>
		            <PrecioUnitario>'.$_POST['precio_unidad'][$key].'</PrecioUnitario>
		            <MontoTotal>'.$montoTotal.'</MontoTotal>';
		            if ($_POST['monto_descuento'][$key]>0) {
		            	$stringXML.='
	                        <Descuento>
	                            <MontoDescuento>'.$_POST['monto_descuento'][$key].'</MontoDescuento>
	                            <NaturalezaDescuento>Descuento cliente</NaturalezaDescuento>
	                        </Descuento>';
		            }
		            $stringXML.='<SubTotal>'.$subTotal.'</SubTotal>
		            <Impuesto>
		                <Codigo>01</Codigo>
		                <CodigoTarifa>08</CodigoTarifa>
		                <Tarifa>'.$_POST['tarifa'][$key].'</Tarifa>
		                <Monto>'.$montoImpuesto.'</Monto>  
		            </Impuesto>
		            
		            <ImpuestoNeto>'.$montoImpuesto.'</ImpuestoNeto>
		            <MontoTotalLinea>'.$montoTotalLinea.'</MontoTotalLinea>
	        	</LineaDetalle>';
	        	////
	    	}


	    	
		    
		    $stringXML.='</DetalleServicio>

		    <ResumenFactura>
		        <CodigoTipoMoneda>
		            <CodigoMoneda>CRC</CodigoMoneda>
		            <TipoCambio>1</TipoCambio>
		        </CodigoTipoMoneda>
		        <TotalServGravados>'.$totales->totalServGravados.'</TotalServGravados>
		        <TotalServExentos>'.$totales->totalServExentos.'</TotalServExentos>
		        <TotalServExonerado>'.$totales->totalServExonerado.'</TotalServExonerado>
		        <TotalMercanciasGravadas>'.$totales->totalMercanciasGravadas.'</TotalMercanciasGravadas>
		        <TotalMercanciasExentas>'.$totales->totalMercanciasExentas.'</TotalMercanciasExentas>
		        <TotalMercExonerada>'.$totales->totalMercExonerada.'</TotalMercExonerada>
		        <TotalGravado>'.$totales->totalGravado.'</TotalGravado>
		        <TotalExento>'.$totales->totalExento.'</TotalExento>
		        <TotalExonerado>'.$totales->totalExonerado.'</TotalExonerado>
		        <TotalVenta>'.$totales->totalVenta.'</TotalVenta>
		        <TotalDescuentos>'.$totales->totalDescuentos.'</TotalDescuentos>
		        <TotalVentaNeta>'.$totales->totalVentaNeta.'</TotalVentaNeta>
		        <TotalImpuesto>'.$totales->totalImpuesto.'</TotalImpuesto>
		        <TotalComprobante>'.$totales->totalComprobante.'</TotalComprobante>
		    </ResumenFactura>
		    <Otros>
		        <OtroTexto></OtroTexto>
		    </Otros>
			</FacturaElectronica>
			';

			//Una vez generado el string del xml, procedemos a pasarlo a formato xml y a guardarlo en la carpeta
			//archivos/para firmar
			$salida= "archivos/xml/p_firmar/".$clave.".xml";
			$doc = new DomDocument();
			$doc->preseveWhiteSpace = false;
			$doc->loadXml($stringXML);
			$doc->save($salida);
			$doc->saveXML();

			//una vez guardado el xml, vamos a proceder a firmarlo. Para ello llamaremos el siguiente método
			return $this->firmarXml($clave); 

    	}
	//

	// Paso 2: Firmar el xml con los datos del emisor
		private function firmarXml($clave){
	        $Firmador = new Firmador();
	        $p12= getenv("factura.p12");
	        $pin= getenv("factura.pin");
	        $input= "archivos/xml/p_firmar/".$clave.".xml";
	        $salida= "archivos/xml/firmados/".$clave."_f.xml";
	        $xml64= $Firmador->firmarXml($p12,$pin,$input,$Firmador::TO_XML_FILE,$salida);
	        //Una vez firmado el documento, nos devoverá el xml codificado en base64, utlilizaremeos este objeto y se lo enviaremos a hacienda llamando el siguiente método.
	        return $this->enviarXml($xml64);
	    }
	//

    //Paso 3: Enviar el xml a hacienda y recibir respuesta si fue enviado o no.
	    private function enviarXml($xml64){
	    	//Para este método necesitamos dos datos, el xml firmado y codificado en base64 (el cual ya obtuvimos en el paso 2), y generar un token de hacienda, para eso necesitamos proveer los siguientes arrays:
	    	$json= json_decode(json_encode(simplexml_load_string(base64_decode($xml64)))); //decodifica el xml en base64, para poder utlizar sus atributos
	    	$clave=$json->Clave;
    		//Este array es para el curl de envío a hacienda
	    	$data= json_encode(array(    
	    		"clave" => $clave,
	    		"fecha" => date('c'),
	    		"emisor"=>array(
	    			"tipoIdentificacion" => $json->Emisor->Identificacion->Tipo,
	    			"numeroIdentificacion" => $json->Emisor->Identificacion->Numero
	    		),
	    		"receptor"=>array(
	    			"tipoIdentificacion" => $json->Receptor->Identificacion->Tipo,
	    			"numeroIdentificacion" => $json->Receptor->Identificacion->Numero
	    		),
	    		"comprobanteXml" => $xml64
	    	));

	    	//Este array es la el formato en el que debe de enviarse el token.
	    	//$this->token() nos devuelve el token de acceso que necesitamos
	    	//todo este header es adjuntado al curl de envío a hacienda junto con el array anterior.
	    	$header= array(   
	    		"Authorization: bearer ".$this->token(),
	            "Content-Type: application/json"
	    	);

	    	//curl de envío a hacienda
	    	$curl = curl_init(getenv('factura.urlRecepcion'));
	    	curl_setopt($curl, CURLOPT_HEADER, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	        //ejecutar el curl y obtener las respuestas
	        $respuesta = curl_exec($curl);
	        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	        curl_close($curl);

	        //retornar la respuesta
	        return json_encode( array('respuesta' =>$respuesta , "status"=> $status, "clave"=>$json->Clave, ));

	    }
    //

    // Paso 4: Si la factura electrónica se pudo enviar correctamente en el paso 3, entonces podemos validar que haya sido aceptada o no
	    public function validarXmlDesatendido(){  //Para validar la factura en hacienda.
	    	
	    	$clave=$_POST["clave"];
	    	$header= array(
	    		"Authorization: bearer ".$this->token(),
	            "Content-Type: application/json"
	    	);
	    	//curl
	    	$curl = curl_init(getenv('factura.urlRecepcion')."/".$clave);
	    	curl_setopt($curl, CURLOPT_HEADER, false);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

	         //ejecutar el curl y obtener las respuestas
	        $respuesta = curl_exec($curl);
	        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	        curl_close($curl);

	        $xml= json_decode( $respuesta , true);

			return json_encode(array('estado' =>$xml["ind-estado"], 'status'=>$status, "clave"=> $clave));

	    }
    //


    //************************************* Subprocesos importantes *********************************
		private function totales($detalles){
			$totalServGravados=0;
	        $totalServExentos=0;
	        $totalServExonerado=0;
	        $totalMercanciasGravadas=0;
	        $totalMercanciasExentas=0;
	        $totalMercExonerada=0;
	        $totalGravado=0;
	        $totalExento=0;
	        $totalExonerado=0;
	        $totalVenta=0;
	        $totalDescuentos=0;
	        $totalVentaNeta=0;
	        $totalImpuesto=0;
	        $totalComprobante=0;

	        foreach ($detalles as $key => $linea) {

	        	$netoLinea= $linea->precio * $linea->cantidad;
	        	//servicios
	        	if ($linea->unidad=="Sp" || $linea->unidad=="Spe") {
	        		//se asume que todo es gravado
	        		$totalServGravados+= $netoLinea;
	        	//si no son mercancias	
	        	}else{
	        		//se asume que todo es gravado
					$totalMercanciasGravadas+= $netoLinea;
	        	}
	        	$impuestoLinea= ($netoLinea * $linea->tarifa)/100;
	        	$totalLinea= $netoLinea +  $impuestoLinea;

	        	//se asume que todo es gravado
	        	$totalGravado+= $netoLinea;
	        	$totalVenta+= $netoLinea;
		        $totalVentaNeta+=$netoLinea;
		        $totalImpuesto+=$impuestoLinea;
		        $totalComprobante+= $totalLinea;
	        }

	        return json_encode(array(
			    "totalServGravados"=>  $totalServGravados,
			    "totalServExentos"=>  $totalServExentos,
			    "totalServExonerado"=>  $totalServExonerado,
			    "totalMercanciasGravadas"=> $totalMercanciasGravadas,
			    "totalMercanciasExentas"=>$totalMercanciasExentas,
			    "totalMercExonerada"=> $totalMercExonerada,
			    "totalGravado"=> $totalGravado,
			    "totalExento"=> $totalExento,
			    "totalExonerado"=> $totalExonerado,
			    "totalVenta"=> $totalVenta,
			    "totalDescuentos"=> $totalDescuentos,
			    "totalVentaNeta"=> $totalVentaNeta,
			    "totalImpuesto"=> $totalImpuesto,
			    "totalComprobante"=> $totalComprobante,
	        ));

		}
		private function totalesPost($post){
			$totalServGravados=0;
	        $totalServExentos=0;
	        $totalServExonerado=0;
	        $totalMercanciasGravadas=0;
	        $totalMercanciasExentas=0;
	        $totalMercExonerada=0;
	        $totalGravado=0;
	        $totalExento=0;
	        $totalExonerado=0;
	        $totalVenta=0;
	        $totalDescuentos=0;
	        $totalVentaNeta=0;
	        $totalImpuesto=0;
	        $totalComprobante=0;

	        foreach ($post['codigo'] as $key => $linea) {
	        	//servicios
	        	if ($post['unidad'][$key] =="Sp" || $post['unidad'][$key] =="Spe") {
	        		//se asume que todo es gravado
	        		$totalServGravados+= $post['monto_total'][$key];
	        	//si no son mercancias	
	        	}else{
	        		//se asume que todo es gravado
					$totalMercanciasGravadas+= $post['monto_total'][$key];
	        	}
	    
	        	//se asume que todo es gravado
	        	$totalGravado+= $post['monto_total'][$key];
	        	$totalVenta+= $post['monto_total'][$key];
	        	$totalDescuentos+=$post['monto_descuento'][$key];
		        $totalVentaNeta+=$post['sub_total'][$key];
		        $totalImpuesto+=$post['monto_impuesto'][$key];
		        $totalComprobante+= $post['total_linea'][$key];
	        }
	        return json_encode(array(
			    "totalServGravados"=>  $totalServGravados,
			    "totalServExentos"=>  $totalServExentos,
			    "totalServExonerado"=>  $totalServExonerado,
			    "totalMercanciasGravadas"=> $totalMercanciasGravadas,
			    "totalMercanciasExentas"=>$totalMercanciasExentas,
			    "totalMercExonerada"=> $totalMercExonerada,
			    "totalGravado"=> $totalGravado,
			    "totalExento"=> $totalExento,
			    "totalExonerado"=> $totalExonerado,
			    "totalVenta"=> $totalVenta,
			    "totalDescuentos"=> $totalDescuentos,
			    "totalVentaNeta"=> $totalVentaNeta,
			    "totalImpuesto"=> $totalImpuesto,
			    "totalComprobante"=> $totalComprobante,
	        ));
		}

	    private function token(){
	    	$data= array(
	    		'client_id' => getenv('factura.clientID'),
	            'client_secret' => '',
	            'grant_type' => 'password',
	            'username' => getenv('factura.userToken'),
	            'password' => getenv('factura.userPass'),
	    	);

	    	//curl
	    	$curl = curl_init(getenv('factura.tokenURL'));
	    	//configura
	    	curl_setopt($curl, CURLOPT_HEADER, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_HEADER, 'Content-Type: application/x-www-form-urlencoded');
	        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	        //final
	        $response = curl_exec($curl);
	        $respuesta = json_decode($response);
	        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	        curl_close($curl);

	        return $respuesta->access_token;
	    }

 	//************************************* Finaliza Subprocesos importantes ***************  

	//************************************* Otros métodos *********************************
	    
	    public function enviarXmlDesatendido(){  //por si el archivo por algún error no se pudo enviar, para poder enviarlo manualmente desde una view

	    	$clave= $_POST['clave'];
	    	$data= json_encode(array(
	    		"clave" => $clave,
	    		"fecha" => date('c'),
	    		"emisor"=>array(
	    			"tipoIdentificacion" => "01",
	    			"numeroIdentificacion" => "402160653"
	    		),
	    		"receptor"=>array(
	    			"tipoIdentificacion" => "02",
	    			"numeroIdentificacion" => "3101143237"
	    		),
	    		"comprobanteXml" => $this->encodeXml()
	    	));


	    	$header= array(
	    		"Authorization: bearer ".$this->token(),
	            "Content-Type: application/json"
	    	);

	    	//curl
	    	$curl = curl_init(getenv('factura.urlRecepcion'));
	    	curl_setopt($curl, CURLOPT_HEADER, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	        //ejecutar el curl y obtener las respuestas
	        $respuesta = curl_exec($curl);
	        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	        curl_close($curl);

	        	        //retornar la respuesta
	        return json_encode( array('respuesta' =>$respuesta , "status"=> $status, "clave"=>$json->Clave, ));

	    }

	    //Genera un pdf de la factura
	    public function facturaPdf(){
			
	    	$clave= $this->request->uri->getSegment(3);
	    	$DocumentosModel = new DocumentosModel();
	    	$DocumentosModel->setClave($clave);
	    	$documento= $DocumentosModel->selectDocumentoxClave();

	    	$DocumentoDetallesModel = new DocumentoDetallesModel();
	    	$DocumentoDetallesModel->setIdDocumento($documento->id_documento);
	    	$detalles= $DocumentoDetallesModel->selectDocumentosDetalles();

			$dataQR= array(
	           "url"=> base_url()."/facturar/verificarFactura/".$documento->clave,
	        );
	        
	        $qr= new myqr();

	        $logoImg=  file_get_contents(base_url()."/plantilla/dist/img/logo.png");


	    	$data= array(
	    		"nombre_archivo" => "pdf/facturas/".$documento->clave.".pdf",
	    		"documento"=> $documento,
	    		"detalles"=>$detalles,
	    		"qr"=> $qr->codigoQR($dataQR),
	    		"logo" => base64_encode($logoImg),
	    		"url"=> base_url()."/facturar/verificarFactura/".$documento->clave,
	    	);

	    	$pdf= new Pdf();
	    	$this->response->setHeader('Content-Type','application/pdf');
	    	$pdf->load_view('pdfs/facturaPDF', $data);
	    	$pdf->save_view('pdfs/facturaPDF', $data);
	    }


	    //Para verificar la Factura
	    public function verificarFactura(){
	        $clave= $this->request->uri->getSegment(3);
	        $DocumentosModel= new DocumentosModel();
	        $DocumentosModel->setClave($clave);
	        $documento= $DocumentosModel->selectDocumentoxClave();
	        if ($documento) {
	            $this->response->setHeader('Content-Type', 'application/pdf');
	            readfile("archivos/pdf/facturas/".$documento->clave.".pdf");
	        }else{
	            echo "Documento no existe";
	        }

	    }

	    public function obtenerListadoFacturas(){
	    	$DocumentosModel= new DocumentosModel();
	    	$facturas=$DocumentosModel-> selectDocumentos();
	    	$data=[];
	    	foreach ($facturas as $factura) {
	    		$data[]=array(
	    			$factura->id_documento,
	    			$factura->tipo_documento,
					$factura->consecutivo,
					$factura->clave,
					$factura->fecha,
					$factura->receptor_cedula,
					$factura->receptor_nombre,
					$factura->total_venta,
					$factura->total_descuentos,
					$factura->total_venta_neta,
					$factura->total_impuestos,
					$factura->total_comprobante,
					$factura->envio_atv,
					$factura->valido_atv,
	    		);
	    	}
	    	return json_encode(array("data"=>$data));
	    }



	    public function actualizar_estado_envio_en_bd(){
	        //este if actualiza el estado del envío en la bd
	        $clave=$_POST["clave"];
        	$DocumentosModel= new DocumentosModel();
        	$DocumentosModel->setClave($clave);
        	$documento=$DocumentosModel->selectDocumentoxClave();
        	$id_documento=$documento->id_documento;
        	$DocumentosModel2 = new DocumentosModel();
        	$DocumentosModel2->setEnvioAtv(1);
        	$DocumentosModel2 -> setIdDocumento($id_documento);
        	$DocumentosModel-> setFechaEnvio(date('c'));
        	$DocumentosModel2->actualizaEnviado();

	    }

	    public function actualizar_respuesta_hacienda(){
	        $clave=$_POST["clave"];
	        $estado=$_POST["ind_estado"];
        	$DocumentosModel= new DocumentosModel();
        	$DocumentosModel->setClave($clave);
        	$id_documento=$DocumentosModel->selectDocumentoxClave()->id_documento;
        	$DocumentosModel2 = new DocumentosModel();
        	$DocumentosModel2 -> setIdDocumento($id_documento);
        	$DocumentosModel2->setValidoAtv($estado);
        	$DocumentosModel-> setFechaValido(date("c"));
        	$DocumentosModel2->actualizaValidado();
	    }

	    public function actualizar(){
	        $clave=$_GET["clave"];
	        $estado=$_GET["ind_estado"];
        	$DocumentosModel= new DocumentosModel();
        	$DocumentosModel->setClave($clave);
        	$id_documento=$DocumentosModel->selectDocumentoxClave()->id_documento;
        	$DocumentosModel2 = new DocumentosModel();
        	$DocumentosModel2 -> setIdDocumento($id_documento);
        	$DocumentosModel2->setValidoAtv($estado);
        	$DocumentosModel-> setFechaValido(date("c"));
        	$DocumentosModel2->actualizaValidado();
	    }	    

    //************************************* Fin Otros métodos *********************************
	

}