<?php

namespace App\Controllers;
use App\Libraries\Pdf;


class Inicio extends BaseController{

	public function inicio(){
		if(session()->get('id_usuario')>0){
			return view("inicio/inicio");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function crearPdf(){
		//crear con base a una plantilla

		$pdf = new Pdf();
		$this->response->setHeader('Content-Type', 'application/pdf');


		$data= array(
			"correo" => "hola@gmail.com",
			"usuario" => "Patito",

		);
		$pdf->save_view('inicio/plantillaPDF', $data);
	}

	

}