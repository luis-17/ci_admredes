<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 class Medicamentos_mdl extends CI_Model {

 	function Menu_mdl() {
 		parent::__construct(); //llamada al constructor de Model.
 		$this->load->database();
 	}

 	function getMedicamentos(){
 		$this->db->select('idmedicamento, nombre_med, presentacion_med, estado_med');
 		$this->db->from('medicamento');

	 $medicamentos = $this->db->get();
	 return $medicamentos->result();
 	}

 	function medicamentos_anular($id){
 		$this->db->set('estado_med', 0 );
 		$this->db->where("idmedicamento=".$id);
 		$this->db->update("medicamento");
 	}

 	function medicamentos_activar($id){
 		$this->db->set('estado_med', 1 );
 		$this->db->where("idmedicamento=".$id);
 		$this->db->update("medicamento");
 	}

 	function getMedicamentosId($id){
 		$this->db->select('idmedicamento, nombre_med, presentacion_med, estado_med');
 		$this->db->from('medicamento');
 		$this->db->where('idmedicamento='.$id);

	 $medicamentos = $this->db->get();
	 return $medicamentos->result();
 	}

 	 function medicamentos_update($nombre, $presentacion, $id){
 		$this->db->set('nombre_med', $nombre);
 		$this->db->set('presentacion_med', $presentacion);
 		$this->db->where("idmedicamento=".$id);
 		$this->db->update("medicamento");
 	}

	function insertar_med($nombre_med, $presentacion_med){
		$this->db->set('nombre_med', $nombre_med);
		$this->db->set('presentacion_med', $presentacion_med);
		$this->db->insert('medicamento');
	}
}
?>