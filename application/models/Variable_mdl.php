<?php
 class Variable_mdl extends CI_Model {

 function Variable_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getVariables(){
	 $canal = $this->db->get('variable_plan');
	 return $canal->result();
 	}

 	function variable_anular($id){
 		$array = array(
 			'estado_vapl' => 0  
 			);
 		$this->db->where("idvariableplan",$id);
 		$this->db->update("variable_plan",$array);
 	}

 	function variable_activar($id){
 		$array = array(
 			'estado_vapl' => 1  
 			);
 		$this->db->where("idvariableplan",$id);
 		$this->db->update("variable_plan",$array);
 	}

 	function getVariable($id){
 		$this->db->select('*');
 		$this->db->from('variable_plan');
 		$this->db->where("idvariableplan",$id);

	 $canal = $this->db->get();
	 return $canal->result();
 	}

 	function insert_variable($data){
 		$array = array(
 			'nombre_var' => $data['nombre_var'],
			'observaciones' => $data['descripcion'],
			'estado_vapl' => 1
			);
 		$this->db->insert('variable_plan',$array);
 	}

 	function update_variable($data){
 		$array = array(
 			'nombre_var' => $data['nombre_var'],
			'observaciones' => $data['descripcion']
		);
 		$this->db->where('idvariableplan',$data['idvariable']);
 		$this->db->update('variable_plan',$array);
 	}

 	function getProductos($id){
 		$this->db->select("*");
 		$this->db->from("producto");
 		$this->db->where("idvariableplan",$id);
 		$this->db->order_by("descripcion_prod");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getProducto($id){
 		$this->db->select("*");
 		$this->db->from("producto");
 		$this->db->where("idproducto",$id);

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function insert_producto($data){
 		$array = array
 		(
 			'descripcion_prod' => $data['descripcion_prod'] , 
 			'idvariableplan' => $data['idvariableplan'],
 			'idespecialidad' => 4,
 			'idtipoproducto' => 1
 		);

 		$this->db->insert("producto",$array);
 	}

 	function up_producto($data){
 		$array = array
 		(
 			'descripcion_prod' => $data['descripcion_prod'] , 
 			'idvariableplan' => $data['idvariableplan']
 		);

 		$this->db->where("idproducto", $data['idproducto']);
 		$this->db->update("producto", $array);
 	}

 	function validar_producto($id){ 
 		$this->db->select("*");
 		$this->db->from("producto_detalle");		
 		$this->db->where('idproducto',$id);

 	$query = $this->db->get();
 	return $query->result();
 	}

 	function delete_detalle($id){
 		$this->db->where("idproducto",$id);
 		$this->db->delete("producto");
 	}
}
?>