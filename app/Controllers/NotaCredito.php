<?php

namespace App\Controllers;


class NotaCredito extends BaseController{

	public function crear(){
		if(session()->get('id_usuario')>0){
			return view("notaCredito/crear");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function listado(){
		if(session()->get('id_usuario')>0){
			return view("notaCredito/listado");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}



	

}