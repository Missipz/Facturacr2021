<?php
namespace App\Models;
use CodeIgniter\Model;

class RolesModel extends Model{
	private $id_rol;
	private $rol;

	public function __construct(){
		$this->db=db_connect();
	}
	
    public function setIdRol($id_rol)
    {
        $this->id_rol = $id_rol;
        return $this;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }

    public function buscarRol(){
    	$query = $this->db->table('roles');
        $query->where('id_rol',$this->id_rol);
        return $query->get()->getRow();
    }

    public function selectRoles(){
        $query = $this->db->table('roles');
        return $query->get()->getResult();
    }

    public function insertarRol() {
        $builder = $this->db->table('roles');
        $data = array(
            "rol" => $this->rol,
        );
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editarRol()
    {
        $query = $this->db->table('roles');
        $data = array(
            "rol" => $this->rol,
        );
        $query->where('id_rol', $this->id_rol);
        $query->update($data);
        return $this->db->affectedRows();
    }
}