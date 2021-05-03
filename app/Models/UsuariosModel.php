<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuariosModel extends Model{
		
	private $id_usuario;
	private $nombre;
    private $usuario;
	private $correo;
	private $pass;
	private $id_rol;
	private $activo;

    private $tabla="usuarios";
    private $tabla_view="usuarios_view";


	public function __construct(){
		$this->db=db_connect();
	}

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function setCorreo($correo)
    {
        $this->correo = $correo;
        return $this;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function setIdRol($id_rol)
    {
        $this->id_rol = $id_rol;
        return $this;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
        return $this;
    }

    public function buscarUsuario(){
    	$query = $this->db->table($this->tabla_view);
        $query->where('id_usuario',$this->id_usuario);
        return $query->get()->getRow();
    }

    public function buscarUsuarioCorreo(){
    	$query = $this->db->table($this->tabla);
        $query->where('correo', $this->correo);
        return $query->get()->getRow();
    }

    public function selectUsuarioXUsuario(){
        $query = $this->db->table($this->tabla);
        $query->where('usuario', $this->usuario);
        return $query->get()->getRow();
    }

    public function selectUsuarios(){
        $query = $this->db->table($this->tabla_view);
        return $query->get()->getResult();
    }



    public function insertarUsuario() {
        $builder = $this->db->table($this->tabla);
        $data = array(
            "nombre" => $this->nombre,
            "usuario" => $this->usuario,
            "correo" => $this->correo,
            "pass" => $this->pass,
            "id_rol" => $this->id_rol,
            "activo" => $this->activo,
        );
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editarUsuario()
    {
        $query = $this->db->table($this->tabla);
        $data = array(
            "nombre" => $this->nombre,
            "usuario" => $this->usuario,
            "correo" => $this->correo,
            "id_rol" => $this->id_rol,
            "activo" => $this->activo,
        );
        $query->where('id_usuario', $this->id_usuario);
        $query->update($data);
        return $this->db->affectedRows();
    }


}