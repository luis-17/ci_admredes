<?php
 class proveedor_mdl extends CI_Model {

 function proveedor_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	 
	function getProveedores() {
	 $this->db->select('p.*, ud.descripcion_ubig as dep, up.descripcion_ubig as prov, udi.descripcion_ubig as dist');
	 $this->db->from('proveedor p');
	 $this->db->join("ubigeo ud","cod_departamento_pr=ud.iddepartamento and idprovincia='00' and ud.iddistrito='00'",'left');
	 $this->db->join("ubigeo up","cod_provincia_pr=up.idprovincia and up.iddistrito='00' and up.iddepartamento=ud.iddepartamento",'left');
	 $this->db->join("ubigeo udi","cod_distrito_pr=udi.iddistrito and up.iddepartamento=udi.iddepartamento and udi.idprovincia=up.idprovincia",'left');
	$proveedores = $this->db->get();
	return $proveedores->result();
	}

	function habilitar($id){
		$data = array(
			'estado_pr' => 1,
			'updatedat' => date('Y-m-d H:i:s') 
		);
		$this->db->where('idproveedor',$id);
		return $this->db->update('proveedor', $data);
	}

	function inhabilitar($id){
		$data = array(
			'estado_pr' => 0,
			'updatedat' => date('Y-m-d H:i:s') 
		);
		$this->db->where('idproveedor',$id);
		return $this->db->update('proveedor', $data);
	}

	function dataproveedor($id){
		$this->db->select("idproveedor, idtipoproveedor, numero_documento_pr, cod_sunasa_pr, razon_social_pr, nombre_comercial_pr, direccion_pr, referencia_pr, cod_distrito_pr, cod_provincia_pr, cod_departamento_pr, 'editarproveedor' as funcion");
		$this->db->from("proveedor pr");
		$this->db->where("idproveedor",$id);

		$proveedor = $this->db->get();
	 	return $proveedor->result();
	}

	function datatipoproveedor(){
		$this->db->select("tp.idtipoproveedor, descripcion_tpr");
		$this->db->from("tipo_proveedor tp");

		$tipoproveedor = $this->db->get();
	 	return $tipoproveedor->result();
	}

	function departamento(){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo ");
		$this->db->where("idprovincia='00' and iddistrito='00'");

		$departamento = $this->db->get();
	 	return $departamento->result();
	}

	function provincia($id){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo u");
		$this->db->join("proveedor p","u.iddepartamento=p.cod_departamento_pr and  iddistrito='00' and idprovincia!='00'");	
		$this->db->where("iddepartamento",$id);

		$provincia = $this->db->get();
	 	return $provincia->result();
	}
}
?>