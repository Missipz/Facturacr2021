<?php

namespace App\Controllers;


class Ajustes extends BaseController{

	public function articulos(){
		if(session()->get('id_usuario')>0){
			return view("ajustes/articulos");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function clientes(){
		if(session()->get('id_usuario')>0){
			return view("ajustes/clientes");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function usuarios(){
		if(session()->get('id_usuario')>0){
			return view("ajustes/usuarios");	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}



	

}