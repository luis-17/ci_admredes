<?php
 class Perfil_mdl extends CI_Model {

 function Perfil_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

	function get_usuario($id)
	{
		$this->db->select("*");
		$this->db->from("usuario");
		$this->db->where("idusuario",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function verifica_oldpass($data)
	{
		$this->db->select("password_view");
		$this->db->from("usuario");
		$this->db->where("password_view",$data['passold']);
		$this->db->where("idusuario",$data['id']);

		$query = $this->db->get();
		return $query->result();
	}

	function actualizar_pass($data)
	{
    	$array = array(
    	'password_view' => $data['passnew'],
    	'password' => md5($data['passnew'])
    	);
    	$this->db->where("password_view",$data['passold']);
		$this->db->where("idusuario",$data['id']);
		return $this->db->update('usuario', $array);
    }
}