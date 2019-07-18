<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 class Diagnosticos_mdl extends CI_Model {

 	function Menu_mdl() {
 		parent::__construct(); //llamada al constructor de Model.
 		$this->load->database();
 	}

 	function getDiagnosticos(){
 		$this->db->select('*');
 		$this->db->from('diagnostico');

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}

  	function getDiagnosticosId($id){
 		$this->db->select('*');
 		$this->db->from('diagnostico');
 		$this->db->where('iddiagnostico='.$id);

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}

 	function diagnosticos_anular($id){
 		$this->db->set('estado_cie', 0 );
 		$this->db->where("iddiagnostico=".$id);
 		$this->db->update("diagnostico");
 	}

 	function diagnosticos_activar($id){
 		$this->db->set('estado_cie', 1 );
 		$this->db->where("iddiagnostico=".$id);
 		$this->db->update("diagnostico");
 	}

 	function getMedicamentosDiagnosticos($id){
 		$this->db->select('m.idmedicamento, m.nombre_med, m.presentacion_med');
 		$this->db->from('medicamento m');
 		$this->db->join('diagnostico_medicamento d','m.idmedicamento = d.idmedicamento');
 		$this->db->where('d.iddiagnostico='.$id);

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}

 	function getProductosDiagnosticos($id){
 		$this->db->select('p.idproducto, descripcion_prod');
 		$this->db->from('producto p');
 		$this->db->join('diagnostico_producto d','p.idproducto = d.idproducto');
 		$this->db->where('d.iddiagnostico='.$id);

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}

  	function getMedicamentos(){
 		$this->db->select('idmedicamento, nombre_med, presentacion_med, estado_med');
 		$this->db->from('medicamento');

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}

 	function getMedicamentosChecked($id){
 		$this->db->select("m.idmedicamento, m.nombre_med, m.presentacion_med, case when d.idmedicamento= m.idmedicamento then 'checked' else '' end as checked, d.iddiagnosticomedicamento");
 		$this->db->from("medicamento m");
 		$this->db->join("(SELECT idmedicamento, iddiagnosticomedicamento FROM diagnostico_medicamento WHERE iddiagnostico =".$id.") d","m.idmedicamento = d.idmedicamento","left");
 		$this->db->order_by("checked");

 		$diagnostico = $this->db->get();
 		return $diagnostico->result();
 	}

	function add_medicamento($id, $iddiagnostico){
		$this->db->set('iddiagnostico', $iddiagnostico);
		$this->db->set('idmedicamento', $id);
		$this->db->set('estado_dime', 1);
		$this->db->insert('diagnostico_medicamento');
	}

	function del_medicamento($id, $iddiagnostico){
		$this->db->where("iddiagnostico=".$iddiagnostico." and idmedicamento=".$id);
		$this->db->delete("diagnostico_medicamento");
	}

 	function getProductosChecked($id){
 		$this->db->select("p.idproducto, p.descripcion_prod, case when d.idproducto= p.idproducto then 'checked' else '' end as checked, d.iddiagnosticoproducto");
 		$this->db->from("producto p");
 		$this->db->join("(SELECT idproducto, iddiagnosticoproducto FROM diagnostico_producto WHERE iddiagnostico =".$id.") d","p.idproducto = d.idproducto","left");
 		$this->db->order_by("checked DESC");

 		$diagnostico = $this->db->get();
 		return $diagnostico->result();
 	}

	function add_producto($id, $iddiagnostico){
		$this->db->set('iddiagnostico', $iddiagnostico);
		$this->db->set('idproducto', $id);
		$this->db->set('estado', 1);
		$this->db->insert('diagnostico_producto');
	}

	function del_producto($id, $iddiagnostico){
		$this->db->where("iddiagnostico=".$iddiagnostico." and idproducto=".$id);
		$this->db->delete("diagnostico_producto");
	}

 	function act_datos($codigo_cie, $descripcion_cie, $tipo, $id){
 		$this->db->set('codigo_cie', $codigo_cie );
 		$this->db->set('descripcion_cie', $descripcion_cie );
 		$this->db->set('tipo', $tipo );
 		$this->db->where("iddiagnostico=".$id);
 		$this->db->update("diagnostico");
 	}

	function save_datos($codigo_cie, $descripcion_cie, $tipo){
		$this->db->set('codigo_cie', $codigo_cie);
		$this->db->set('descripcion_cie', $descripcion_cie);
		$this->db->set('cubierto', 'si');
		$this->db->set('tipo', $tipo);
		$this->db->insert('diagnostico');
	}

  	function getDiagnosticosDatos(){
 		$this->db->select('iddiagnostico, codigo_cie, descripcion_cie, estado_cie');
 		$this->db->from('diagnostico');
 		$this->db->order_by("iddiagnostico DESC");
 		$this->db->limit(1);

	 $diagnosticos = $this->db->get();
	 return $diagnosticos->result();
 	}
}
?>