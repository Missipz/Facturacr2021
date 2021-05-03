<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Login extends BaseController{

	public function login(){
		if(session()->get('id_usuario')>0){
			return redirect()->to(base_url('inicio/inicio'));
		}else{
			return view("login/login");
		}
	}

	public function registro(){
		return view("login/registro");
	}

	public function recuperar(){
		return view("login/recuperar");
	}

	public function verificar(){
		$user= $_POST['user'];
		$pass= $_POST['pass'];
		$existe=0;

		$UsuariosModel= new UsuariosModel();

		$UsuariosModel->setUsuario($user);

		$usuario = $UsuariosModel->selectUsuarioXUsuario();

		if($usuario){
			if($usuario->pass == $pass){
				$existe=1;
				$dataSession= array(
					"id_usuario" => $usuario->id_usuario,
					"correo" => $usuario->correo,
					"nombre" => $usuario->nombre,
					"id_rol" => $usuario->id_rol,
				);
				session()->set($dataSession);
			}
		}
		return json_encode($existe);	
	}

	public function registrarme(){
		if($_POST['pass'] == $_POST['repass']){

			$UsuariosModel= new UsuariosModel();

			$UsuariosModel->setNombre($_POST['nombre']);
			$UsuariosModel->setCorreo($_POST['correo']);
			$UsuariosModel->setPass($_POST['pass']);
			$UsuariosModel->setIdRol(2);
			$UsuariosModel->setActivo(1);

			$id_usuario = $UsuariosModel->insertarUsuario();

			if ($id_usuario>0) {
				$dataSession= array(
					"id_usuario" => $id_usuario,
					"correo" => $_POST['correo'],
					"nombre" => $_POST['nombre'],
					"id_rol" => 2,
				);
				session()->set($dataSession);
			}
			return json_encode($id_usuario);
		}else{
			return json_encode(0);
		}
	}

	public function salir(){
		session()->destroy();
		return redirect()->to(base_url('login/login'));
	}

	

}