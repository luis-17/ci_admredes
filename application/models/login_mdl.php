<?php
 class Login_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

public function login($email, $password){
	$query = $this->db->get_where('usuario', array('emailusuario'=>$email, 'password_view'=>$password));
	return $query->row_array();
}


}
?>