<?php
 class proveedor_otros_mdl extends CI_Model {

 function proveedor_otros_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	 
	function getProveedores() {
	 $this->db->select('p.*, ud.descripcion_ubig as dep, up.descripcion_ubig as prov, udi.descripcion_ubig as dist');
	 $this->db->from('proveedor_int p');
	 $this->db->join("ubigeo ud","cod_departamento_pr=ud.iddepartamento and idprovincia='00' and ud.iddistrito='00'",'left');
	 $this->db->join("ubigeo up","cod_provincia_pr=up.idprovincia and up.iddistrito='00' and up.iddepartamento=ud.iddepartamento",'left');
	 $this->db->join("ubigeo udi","cod_distrito_pr=udi.iddistrito and up.iddepartamento=udi.iddepartamento and udi.idprovincia=up.idprovincia",'left');
	$proveedores = $this->db->get();
	return $proveedores->result();
	}

	function dataproveedor($id){
		$this->db->select("idproveedor_int, numero_documento_pr, razon_social_pr, nombre_comercial_pr, direccion_pr, referencia_pr, cod_distrito_pr, cod_provincia_pr, cod_departamento_pr, 'editarproveedor' as funcion");
		$this->db->from("proveedor_int pr");
		$this->db->where("idproveedor_int",$id);

		$proveedor = $this->db->get();
	 	return $proveedor->result();
	}

	function departamento(){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo ");
		$this->db->where("idprovincia='00' and iddistrito='00'");

		$departamento = $this->db->get();
	 	return $departamento->result();
	}

	function provincia($id){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT cod_departamento_pr from proveedor_int WHERE idproveedor_int=".$id.") and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia = $this->db->get();
		return $provincia->result();
	}

	function distrito($id){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT cod_departamento_pr from proveedor_int WHERE idproveedor_int=".$id.")  and idprovincia=(SELECT cod_provincia_pr from proveedor_int WHERE idproveedor_int=".$id.") and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito = $this->db->get();
		return $distrito->result();
	}
	

	function in_proveedor($data){
		$array = array
		(
			'idtipodocumentoidentidad' => 3,			
			'razon_social_pr' => $data['razonsocial'],
			'nombre_comercial_pr' => $data['nombrecomercial'],
			'numero_documento_pr' => $data['ruc'],
			'direccion_pr' => $data['direccion'],
			'referencia_pr' => $data['referencia'],
			'cod_distrito_pr' => substr($data['distrito'], 4,2),
			'cod_provincia_pr' => substr($data['provincia'], 2,2),
			'cod_departamento_pr' => $data['departamento'],
			'idusuario'	=> $data['idusuario']
		);

		$this->db->insert('proveedor_int',$array);
	}

	function up_proveedor($data){
		$array = array
		(
			'idtipodocumentoidentidad' => 3,
			'razon_social_pr' => $data['razonsocial'],
			'nombre_comercial_pr' => $data['nombrecomercial'],
			'numero_documento_pr' => $data['ruc'],
			'direccion_pr' => $data['direccion'],
			'referencia_pr' => $data['referencia'],
			'cod_distrito_pr' => substr($data['distrito'], 4,2),
			'cod_provincia_pr' => substr($data['provincia'], 2,2),
			'cod_departamento_pr' => $data['departamento']
		);
		$this->db->where("idproveedor_int",$data['id']);
		return $this->db->update("proveedor_int",$array);
	}
}
?>