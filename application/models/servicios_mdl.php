<?php
ini_set('max_execution_time', 6000); 
ini_set('memory_limit', -1);
class Servicios_mdl extends CI_Model {

	function Servicios_mdl() {
		parent::__construct(); //llamada al constructor de Model.
		$this->load->database();
	}

	function getServicios(){
 		$this->db->select("*");
 		$this->db->from("servicios");

	 	$canales = $this->db->get();
	 	return $canales->result();
	}


}