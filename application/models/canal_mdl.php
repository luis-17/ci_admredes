<?php
 class Canal_mdl extends CI_Model {

 function Canal_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCanales(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('cliente_empresa c');
 		$this->db->join("categoria_cliente cc","c.idcategoriacliente=cc.idcategoriacliente");
 		$this->db->order_by("nombre_comercial_cli");

	 $canal = $this->db->get();
	 return $canal->result();
 	}

 	function canal_anular($id){
 		$array = array(
 			'estado_cli' => 0  
 			);
 		$this->db->where("idclienteempresa",$id);
 		$this->db->update("cliente_empresa",$array);
 	}

 	function canal_activar($id){
 		$array = array(
 			'estado_cli' => 1  
 			);
 		$this->db->where("idclienteempresa",$id);
 		$this->db->update("cliente_empresa",$array);
 	}

 	function getCategoria(){
 		$query=$this->db->get("categoria_cliente");
 		return $query->result();
 	}

 	function getCanal($id){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('cliente_empresa c');
 		$this->db->join("categoria_cliente cc","c.idcategoriacliente=cc.idcategoriacliente");
 		$this->db->where("idclienteempresa",$id);
 		$this->db->order_by("nombre_comercial_cli");

	 $canal = $this->db->get();
	 return $canal->result();
 	}

 	function insert_canal($data){
 		$array = array(
 			'idcategoriacliente' => $data['idcategoria'],
			'numero_documento_cli' => $data['ruc'],
			'razon_social_cli' => $data['razon_social'],
			'nombre_comercial_cli' => $data['comercial'],
			'nombre_corto_cli' => $data['nombre_corto'],
			'dni_representante_legal' => $data['dni'],
			'representante_legal' => $data['nombres'],
			'direccion_legal' => $data['direccion'],
			'telefono_cli' => $data['telf'],
			'pagina_web_cli' => $data['web']
			);
 		$this->db->insert('cliente_empresa',$array);
 	}

 	function update_canal($data){
 		$array = array(
 			'idcategoriacliente' => $data['idcategoria'],
			'numero_documento_cli' => $data['ruc'],
			'razon_social_cli' => $data['razon_social'],
			'nombre_comercial_cli' => $data['comercial'],
			'nombre_corto_cli' => $data['nombre_corto'],
			'dni_representante_legal' => $data['dni'],
			'representante_legal' => $data['nombres'],
			'direccion_legal' => $data['direccion'],
			'telefono_cli' => $data['telf'],
			'pagina_web_cli' => $data['web']
			);
 		$this->db->where('idclienteempresa',$data['idcanal']);
 		$this->db->update('cliente_empresa',$array);
 	}
}
?>