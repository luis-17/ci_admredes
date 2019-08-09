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

 	function getSolicitudes(){
 		$this->db->select("c.cert_id, c.cert_num, nombre_comercial_cli, nombre_plan, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2)) as contratante, fecha_solicitud, motivo");
 		$this->db->from("cancelado_solicitud cs");
 		$this->db->join("certificado c","c.cert_id=cs.cert_id");
 		$this->db->join("contratante co","c.cont_id=co.cont_id");
 		$this->db->join("plan p","c.plan_id=p.idplan");
 		$this->db->join("cliente_empresa ce","p.idclienteempresa=ce.idclienteempresa");
 		$this->db->where("cs.estado",0);

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getCertificado($id){
 		$this->db->select("cert_num, plan_id");
 		$this->db->from("certificado");
 		$this->db->where("cert_id",$id);
 		$query = $this->db->get();
 		return $query->result();
 	}

 	function RegCancelado($data){
 		$array = array
 		(
 			'cert_id' => $data['cert_id'],
 			'can_cert_num' => $data['cert_num'],
 			'can_estadoCert' => 3,
 			'can_finVig' => $data['fecha'],
 			'plan_id' => $data['plan_id'],
 			'idusuario' => $data['idusuario'] 
 		);

 		$this->db->insert("cancelado",$array);
 	}

 	function upSolicitud($data){
 		$array = array
 		(
 			'idusuario_procesa' => $data['idusuario'],
 			'estado' => 1,
 			'fecha_procesamiento' => $data['fecha'] 
 		);
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->update("cancelado_solicitud",$array);

 	}

 	function upSolicitud2($data){
 		$array = array
 		(
 			'idusuario_procesa' => $data['idusuario'],
 			'estado' => 2,
 			'fecha_procesamiento' => $data['fecha'],
 			'motivo_rechazo' => $data['motivo'] 
 		);
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->update("cancelado_solicitud",$array);
 	}

 	function upCertificado($data){
 		$array = array('cert_estado' => 1);
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->update("certificado",$array);
 	}

 	function upCertificadoAsegurado($data){
 		$array = array('cert_estado' => 1);
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->update("certificado_asegurado",$array);
 	}

 	function getSolicitudesProcesadas(){
 		$this->db->select("c.cert_id, c.cert_num, nombre_comercial_cli, nombre_plan, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2)) as contratante, case when (estado=1) then (motivo) else (motivo_rechazo) end as motivo, case when estado=1 then 'Aceptada' else 'Rechazada' end as estado, case when estado=1 then 'label label-info label-white middle' else 'label label-danger label-white middle' end as class, fecha_procesamiento, username");
 		$this->db->from("cancelado_solicitud cs");
 		$this->db->join("certificado c","c.cert_id=cs.cert_id");
 		$this->db->join("contratante co","c.cont_id=co.cont_id");
 		$this->db->join("plan p","c.plan_id=p.idplan");
 		$this->db->join("cliente_empresa ce","p.idclienteempresa=ce.idclienteempresa");
 		$this->db->join("usuario u","u.idusuario = cs.idusuario_procesa");
 		$this->db->where("cs.estado<>0");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getPlanes(){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");		
    	//$this->db->where("estado_plan",1);
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getPlanes2($id){
 		$this->db->select("idplan, nombre_plan, flg_cancelar");
 		$this->db->from("plan");		
    	//$this->db->where("estado_plan",1);
 		$this->db->where("idclienteempresa",$id);

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getCobros($id,$fecha,$fecha2){
 		$this->db->select("c.cert_id, cert_num, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2,''))as contratante, cant, case when vez_cobro is null then 1 else (vez_cobro)+1 end as vez_cobro, prima_monto, prima_adicional, c.cert_finVig, c.plan_id,	case when vez_cobro is null then 'Nuevo Cobro' else (case when '".$fecha."'>(select co.cob_finCobertura from cobro co where co.cert_id=c.cert_id order by co.cob_id desc limit 1) then 'Nuevo Cobro' else (case when '".$fecha."'=(select co.cob_iniCobertura from cobro co where co.cert_id=c.cert_id order by co.cob_id desc limit 1) and '".$fecha2."'=(select co.cob_finCobertura from cobro co where co.cert_id=c.cert_id order by co.cob_id desc limit 1)then 'Actualizar Cobro' else 'No Disponible' end ) end) end as accion");
 		$this->db->from("certificado c");
 		$this->db->join("contratante co","c.cont_id=co.cont_id");
 		$this->db->join("(select count(certase_id) AS cant, ca.cert_id FROM certificado_asegurado ca inner join certificado c on ca.cert_id=c.cert_id where plan_id=".$id." and c.cert_estado=1 and ca.cert_estado=1 and cert_num not like 'PR%' GROUP BY ca.cert_id)x","x.cert_id=c.cert_id");
 		$this->db->join("(select max(cob_vezCob)as vez_cobro, cert_id from cobro where plan_id=".$id." and cert_num not like 'PR%' GROUP BY cert_id)y","y.cert_id=c.cert_id","left");
 		$this->db->join("plan p","p.idplan=c.plan_id");
 		$this->db->where("c.plan_id=".$id." and cert_estado=1 and cert_num not like 'PR%'");
 		$this->db->limit(5000);
 		$query = $this->db->get();
 		return $query->result();
 	}

 	function regCobro($data){
 		$array = array
 		(
 			'cert_id' => $data['cert_id'], 
 			'cob_fechCob' => $data['fecha_cobro'],
 			'cob_vezCob' => $data['vez_cobro'],
 			'cob_importe' => $data['cob_importe'],
 			'cob_moneda' => 'PEN',
 			'cob_iniCobertura' => $data['fecha_ini'],
 			'cob_finCobertura' => $data['fecha_fin'],
 			'cert_num' => $data['cert_num'],
 			'plan_id' => $data['plan']
 		);
 		$this->db->insert("cobro",$array);
 	}

 	function upCert($data){
 		$array = array('cert_finVig' => $data['fecha_fin'] );
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->update("certificado",$array);
 	}
 	
 	function upCertAseg($data){
 		$array = array('cert_finVig' => $data['fecha_fin'] );
 		$this->db->where("cert_id",$data['cert_id']);
 		$this->db->where("cert_estado",1);
 		$this->db->update("certificado_asegurado",$array);
 	}

 	function upCobro($data){
 		$array = array('cob_importe' => $data['cob_importe'] );
 		$this->db->where('cert_id',$data['cert_id']);
 		$this->db->where('cob_iniCobertura',$data['fecha_ini']);
 		$this->db->where('cob_finCobertura',$data['fecha_fin']);
 		$this->db->update('cobro',$array);
 	}
}
?>