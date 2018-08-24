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
	 $this->db->order_by("p.idproveedor","desc");
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
		$this->db->select("idproveedor, idtipoproveedor, numero_documento_pr, cod_sunasa_pr, razon_social_pr, nombre_comercial_pr, direccion_pr, referencia_pr, cod_distrito_pr, cod_provincia_pr, cod_departamento_pr, 'editarproveedor' as funcion, pr.idusuario, username, password_view");
		$this->db->from("proveedor pr");
		$this->db->join("usuario u","pr.idusuario=u.idusuario");
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
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT cod_departamento_pr from proveedor WHERE idproveedor=".$id.") and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia = $this->db->get();
		return $provincia->result();
	}

	function distrito($id){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT cod_departamento_pr from proveedor WHERE idproveedor=".$id.")  and idprovincia=(SELECT cod_provincia_pr from proveedor WHERE idproveedor=".$id.") and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito = $this->db->get();
		return $distrito->result();
	}


	function in_usuario($data){
		$array = array
		(
			'idtipousuario' => 4,
			'emailusuario' => $data['usuario'],
			'username' => $data['usuario'],
			'password' => md5($data['contrasena']),
			'password_view' => $data['contrasena']
		);
		$this->db->insert('usuario',$array);
	}

	function in_proveedor($data){
		$array = array
		(
			'idtipoproveedor' => $data['tipoproveedor'], 
			'idtipodocumentoidentidad' => 3,
			'idusuario' => $data['idusuario'],
			'razon_social_pr' => $data['razonsocial'],
			'nombre_comercial_pr' => $data['nombrecomercial'],
			'numero_documento_pr' => $data['ruc'],
			'direccion_pr' => $data['direccion'],
			'referencia_pr' => $data['referencia'],
			'cod_distrito_pr' => substr($data['distrito'], 4,2),
			'cod_provincia_pr' => substr($data['provincia'], 2,2),
			'cod_departamento_pr' => $data['departamento'],
			'cod_sunasa_pr' => $data['codigosunasa']
		);

		$this->db->insert('proveedor',$array);
	}

	function up_proveedor($data){
		$array = array
		(
			'idtipoproveedor' => $data['tipoproveedor'], 
			'idtipodocumentoidentidad' => 3,
			'idusuario' => $data['idusuario'],
			'razon_social_pr' => $data['razonsocial'],
			'nombre_comercial_pr' => $data['nombrecomercial'],
			'numero_documento_pr' => $data['ruc'],
			'direccion_pr' => $data['direccion'],
			'referencia_pr' => $data['referencia'],
			'cod_distrito_pr' => substr($data['distrito'], 4,2),
			'cod_provincia_pr' => substr($data['provincia'], 2,2),
			'cod_departamento_pr' => $data['departamento'],
			'cod_sunasa_pr' => $data['codigosunasa']
		);
		$this->db->where("idproveedor",$data['id']);
		return $this->db->update("proveedor",$array);
	}

	function up_usuario($data){
		$array = array
		(
			'idtipousuario' => 4,
			'emailusuario' => $data['usuario'],
			'username' => $data['usuario'],
			'password' => md5($data['contrasena']),
			'password_view' => $data['contrasena']
		);
		$this->db->where('idusuario',$data['idusuario']);
		return $this->db->update("usuario",$array);
	}

	function get_contactos($id){
		$this->db->select("idproveedor,idcontactoproveedor, estado_cp, nombres_cp, apellidos_cp, telefono_fijo_cp, anexo_cp, telefono_movil_cp, email_cp, c.idcargocontacto, envio_correo_cita, descripcion_ctc");
		$this->db->from("contacto_proveedor c");
		$this->db->join("cargo_contacto cc","c.idcargocontacto=cc.idcargocontacto");
		$this->db->where("idproveedor",$id);

	$contactos = $this->db->get();
	return $contactos->result();
	}
	
	function get_contacto($id){
		$this->db->select("idproveedor, idcontactoproveedor, estado_cp, nombres_cp, apellidos_cp, telefono_fijo_cp, anexo_cp, telefono_movil_cp, email_cp, c.idcargocontacto, envio_correo_cita, descripcion_ctc");
		$this->db->from("contacto_proveedor c");
		$this->db->join("cargo_contacto cc","c.idcargocontacto=cc.idcargocontacto");
		$this->db->where("idcontactoproveedor",$id);

	$contacto = $this->db->get();
	return $contacto->result();
	}

	function get_cargos(){
		$this->db->select("idcargocontacto, descripcion_ctc");
		$this->db->from("cargo_contacto");
		$this->db->where("estado_ctc",1);
		$this->db->order_by("descripcion_ctc");
	$query = $this->db->get();
	return $query->result();
	}

	function add_contacto($data){
		$array = array(
			'idproveedor' => $data['idp'],
			'nombres_cp' => $data['nombres'],
			'apellidos_cp' => $data['apellidos'],
			'telefono_fijo_cp' => $data['telf'],
			'anexo_cp' => $data['anexo'],
			'telefono_movil_cp' => $data['movil'],
			'email_cp' => $data['email'],
			'envio_correo_cita' => $data['envio'],
			'idcargocontacto' => $data['idcargo'] 
			);
		$this->db->insert("contacto_proveedor",$array);
	}

	function up_contacto($data){
		$array = array(
			'nombres_cp' => $data['nombres'],
			'apellidos_cp' => $data['apellidos'],
			'telefono_fijo_cp' => $data['telf'],
			'anexo_cp' => $data['anexo'],
			'telefono_movil_cp' => $data['movil'],
			'email_cp' => $data['email'],
			'envio_correo_cita' => $data['envio'],
			'idcargocontacto' => $data['idcargo'] 
			);
		$this->db->where("idcontactoproveedor",$data['idcp']);
		$this->db->update("contacto_proveedor",$array);

	}

	function anularc($idcp){
		$array = array('estado_cp' => 0 );		
		$this->db->where("idcontactoproveedor",$idcp);
		$this->db->update("contacto_proveedor",$array);
	}

	function activarc($idcp){
		$array = array('estado_cp' => 1 );		
		$this->db->where("idcontactoproveedor",$idcp);
		$this->db->update("contacto_proveedor",$array);
	}
}
?>