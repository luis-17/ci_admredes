<?php
 class Login_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

public function login($email, $password){
	$query = $this->db->get_where('usuario', array('emailusuario'=>$email, 'password_view'=>$password, 'estado_us'=> 1));
	return $query->row_array();
}

public function atenciones(){
	$this->db->select("idsiniestro, idcita");
	$this->db->from("siniestro");
	$this->db->where("fecha_atencion< now()-1 and estado_atencion='P' and estado_siniestro=1");
$query=$this->db->get();
return $query->result();
}

public function eliminar_cita($data){
	$array = array(
		'estado_cita' => 0
 	);
$this->db->where('idcita',$data['idcita']);
return $this->db->update('cita', $array);
}

public function eliminar_orden($data){
	$array = array(
		'estado_siniestro' => 0
	);
$this->db->where('idsiniestro',$data['idsiniestro']);
return $this->db->update('siniestro', $array);
}

}
?>