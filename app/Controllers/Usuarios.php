<?php

namespace App\Controllers;

use App\Models\RolesModel;
use App\Models\UsuariosModel;
use App\Libraries\Mailer;

class Usuarios extends BaseController{

	public function agregar(){
		if(session()->get('id_usuario')>0){
			$RolesModel= new RolesModel();
			
			//variable para guardar y consultar los roles que existen
			$data = array(
				"roles" => $RolesModel->selectRoles(),
			);
			//enviarlos a la view

			return view("usuarios/agregar", $data);	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function listado(){
		if(session()->get('id_usuario')>0){
			$UsuariosModel= new UsuariosModel();
			$RolesModel= new RolesModel();
			
			$data = array(
				"usuarios" => $UsuariosModel->selectUsuarios(),
				"roles" => $RolesModel->selectRoles(),
			);
			return view("usuarios/listado",$data);	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}
	public function listadoDT(){
		if(session()->get('id_usuario')>0){
			$UsuariosModel= new UsuariosModel();
			$RolesModel= new RolesModel();
			
			$data = array(
				"usuarios" => $UsuariosModel->selectUsuarios(),
				"roles" => $RolesModel->selectRoles(),
			);
			return view("usuarios/listadoDT",$data);	
		}else{
			return redirect()->to(base_url('login/login'));
		}
	}

	public function nuevoUsuario(){
		$UsuariosModel= new UsuariosModel();

		$UsuariosModel->setNombre($_POST['nombre']);
		$UsuariosModel->setCorreo($_POST['correo']);
		$UsuariosModel->setPass($_POST['pass']);
		$UsuariosModel->setIdRol($_POST['id_rol']);
		$UsuariosModel->setActivo(1);

		$id_usuario = $UsuariosModel->insertarUsuario();
		
		return json_encode($id_usuario);
	}

	public function selectUsuarioId(){
		$UsuariosModel= new UsuariosModel();
		$UsuariosModel->setIdUsuario($_POST['id_usuario']);
		$usuario= $UsuariosModel->buscarUsuario();
		return json_encode($usuario);
		
	}

	public function editarUsuario(){
		$UsuariosModel= new UsuariosModel();

		//update
		$UsuariosModel->setNombre($_POST['nombre']);
		$UsuariosModel->setCorreo($_POST['correo']);
		$UsuariosModel->setIdRol($_POST['id_rol']);
		$UsuariosModel->setActivo($_POST['activo']);
		//where
		$UsuariosModel->setIdUsuario($_POST['id_usuario']);

		$afectadas = $UsuariosModel->editarUsuario();
		return json_encode($afectadas);
	}

	public function selectUsuariosDT(){
		$UsuariosModel= new UsuariosModel();
		$usuarios= $UsuariosModel->selectUsuarios();
		$data=[];
		foreach($usuarios as $usuario){
			$data[]= array(
				$usuario->id_usuario,
				$usuario->nombre,
				$usuario->correo,
				$usuario->rol,
				$usuario->estado,
			);
		}
		echo json_encode( array( "data"=> $data ) );
	}

	public function recuperar(){
		//cual es el correo
		$correo= $_POST['correo'];

		$UsuariosModel= new UsuariosModel();
		$UsuariosModel->setCorreo($correo);
		$usuario = $UsuariosModel->buscarUsuarioCorreo();


		if ($usuario) {
			$mail = new Mailer();

			$cuerpo= "<h1 style='color:red'>Hola ". $usuario->nombre. "</h1> 
					<p>le recordamos que su pass es: <b>". $usuario->pass ."<b></p><br>
					<a href='".base_url()."'>Acceder</a>";

			$data= array(
				"from" => "banconacional@bn.cr",
				"name" => "Banco nacional de Costa Rica",
				"correo" => $usuario->correo,
				"asunto" => "Hola",
				"cuerpo" => $cuerpo,
				"adjunto" =>"bd estructura.pdf",
			);

			$enviar= $mail->enviarCorreo($data);
			if ($enviar) {
			 	echo 1;
			}else{
			 	echo 0;
			}
		}else{
			echo 0;
		}

	}


}