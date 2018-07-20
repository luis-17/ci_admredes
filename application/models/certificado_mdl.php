<?php
 class Certificado_mdl extends CI_Model {

 function Certificado_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCertificados($doc){
 		$this->db->distinct('cert.cert_id');
 		$this->db->select('cert.cert_id, cont_numDoc, cert_num, nombre_plan, nombre_comercial_cli, cert_upProv, cert.cert_estado');
 		$this->db->from('certificado cert');
 		$this->db->join('certificado_asegurado ca','ca.cert_id=cert.cert_id');
 		$this->db->join('asegurado a','ca.aseg_id=a.aseg_id');
 		$this->db->join('contratante co', 'cert.cont_id=co.cont_id'); 
	 	$this->db->join('plan pl', 'cert.plan_id = pl.idplan'); 	 
	 	$this->db->join('cliente_empresa cl', 'cl.idclienteempresa=pl.idclienteempresa'); 
	 	$this->db->where("aseg_numDoc=$doc  or cont_numDoc=$doc");

	 $certificados = $this->db->get();
	 return $certificados->result();
 	}
	 
	function getCertificado($id) {

	 	$this->db->select("cert_id, cert.cert_num, cert.plan_id, concat(coalesce(co.cont_ape1,''),' ',coalesce(co.cont_ape2,''),' ',coalesce(co.cont_nom1,''),' ',coalesce(co.cont_nom2,''))as contratante, cert.cert_upProv,  cert.cert_estado, cert.cert_iniVig, cert.cert_finVig, cl.nombre_comercial_cli, pl.nombre_plan, pl.prima_monto");
		 $this->db->select("(select cob_fechCob from cobro where cert_id=cert.cert_id order by cob_fechCob desc limit 1) AS ultimo_cobro");
		 $this->db->select("(SELECT MAX(cob_finCobertura) AS ultima_cobertura FROM cobro co WHERE co.cert_id = cert.cert_id LIMIT 1) AS ultima_cobertura");
	 	$this->db->from('certificado cert');
	 	$this->db->join('contratante co', 'cert.cont_id=co.cont_id'); 
	 	$this->db->join('plan pl', 'cert.plan_id = pl.idplan'); 	 
	 	$this->db->join('cliente_empresa cl', 'cl.idclienteempresa=pl.idclienteempresa'); 
	 	$this->db->where('cert_id', $id);

	 $certificado = $this->db->get();
	 return $certificado->result();
	}
	

	function getCertificadoApellidos($ap){

	 	$this->db->select("CONCAT(COALESCE(cont_ape1,''), ' ', COALESCE(cont_ape2,''),' ',COALESCE(cont_nom1,''), ' ', COALESCE(cont_nom2,''))as contratante, cont_numDoc, aseg_numDoc, CONCAT(COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''))as asegurado, nombre_plan, nombre_comercial_cli, cert_num");
		$this->db->from('certificado cert');
 		$this->db->join('certificado_asegurado ca','ca.cert_id=cert.cert_id');
 		$this->db->join('asegurado a','ca.aseg_id=a.aseg_id');
 		$this->db->join('contratante co', 'cert.cont_id=co.cont_id'); 
	 	$this->db->join('plan pl', 'cert.plan_id = pl.idplan'); 	 
	 	$this->db->join('cliente_empresa cl', 'cl.idclienteempresa=pl.idclienteempresa');  
	 	$this->db->where("CONCAT(COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) like '$ap%' OR CONCAT(COALESCE(cont_ape1,''), ' ', COALESCE(cont_ape2,'')) like '$ap%'");
	 	$this->db->order_by("cont_ape1, cont_ape2, aseg_ape1, aseg_ape2");
	 $certificadoap = $this->db->get();
	 return $certificadoap->result();
	}

	function activar_certificado($id){
		$data = array(
			'cert_upProv' => 1
		);
		$this->db->where('cert_id',$id);
		return $this->db->update('certificado', $data);
	}

	function cancelar_certificado($id){
		$data = array(
			'cert_upProv' => 0
		);
		$this->db->where('cert_id',$id);
		return $this->db->update('certificado', $data);
	}

	function getAsegurados($id){
		$this->db->select("ca.certase_id, a.aseg_id, aseg_numDoc, aseg_telf, (now()-aseg_fechNac) as edad, aseg_fechNac, aseg_email, aseg_direcc, concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''),' ',coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''))as asegurado");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito=SUBSTR(aseg_ubg,8,2) )as distrito");
		$this->db->select("(SELECT MAX(fecha_atencion) AS ultima_atencion FROM siniestro WHERE idasegurado=a.aseg_id LIMIT 1) AS ultima_atencion");
		$this->db->from('asegurado a');
		$this->db->join('certificado_asegurado ca','a.aseg_id=ca.aseg_id');
		$this->db->join('certificado c','c.cert_id=ca.cert_id');
		$this->db->where('c.cert_id', $id);

	$asegurados = $this->db->get();
	return $asegurados->result();
	}

	function getCobros($id){
		$this->db->select("cob_fechCob,cob_vezCob,concat(round((cob_importe/100),2),' ',cob_moneda) as importe, cob_iniCobertura,cob_finCobertura");
		$this->db->from("cobro");
		$this->db->where('cert_id', $id);
		$this->db->order_by("cob_fechCob", "desc");

	$cobros = $this->db->get();
	return $cobros->result();
	}

	function getcertificado_calendario($id){
		$this->db->select("cert_id");
		$this->db->from("certificado_asegurado");
		$this->db->where('certase_id', $id);
	$certificado_calendario = $this->db->get();
	return $certificado_calendario->result();
	}

	function getAsegurado($id){
		$this->db->select("a.*, ca.certase_id, nombre_plan, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, timestampdiff(YEAR,aseg_fechNac,now())as edad");
		$this->db->select("(SELECT MAX(fecha_atencion) AS ultima_atencion FROM siniestro sin WHERE sin.idcertificado = ca.cert_id LIMIT 1) AS ultima_atencion");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia=SUBSTR(aseg_ubg,3,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia=SUBSTR(aseg_ubg,3,2) and iddistrito=SUBSTR(aseg_ubg,5,2) )as distrito");
		$this->db->from("certificado_asegurado ca");
		$this->db->join('certificado c','c.cert_id=ca.cert_id');		
		$this->db->join('asegurado a','a.aseg_id=ca.aseg_id');
		$this->db->join('plan pl','pl.idplan=c.plan_id');
		$this->db->where('certase_id', $id);
	$asegurado = $this->db->get();
	return $asegurado->result();
	}

	function getAtenciones($id){

		$this->db->select("num_orden_atencion, fecha_cita, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia");
		$this->db->from("siniestro s");
		$this->db->join('especialidad e','s.idespecialidad=e.idespecialidad');		
		$this->db->join('proveedor pr','pr.idproveedor=s.idproveedor');					
		$this->db->join('cita c','c.idcita=s.idcita','left');
		$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O' and s.idasegurado=$id");
	$atenciones=$this->db->get();
	return $atenciones->result();
	}

	// function getCitas($id){

	// 	$this->db->select("num_orden_atencion, fecha_cita, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia");
	// 	$this->db->from("siniestro s");
	// 	$this->db->join('especialidad e','s.idespecialidad=e.idespecialidad');		
	// 	$this->db->join('proveedor pr','pr.idproveedor=s.idproveedor');					
	// 	$this->db->join('cita c','c.idcita=s.idcita','left');
	// 	$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O' and s.idasegurado=$id");
	// $citas=$this->db->get();
	// return $citas->result();
	// }

	function getAseg_editar($id){

		$this->db->select("CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_id, aseg_numDoc, aseg_fechNac, aseg_sexo,aseg_direcc, aseg_telf,aseg_email");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia=SUBSTR(aseg_ubg,3,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,1,2) and idprovincia=SUBSTR(aseg_ubg,3,2) and iddistrito=SUBSTR(aseg_ubg,5,2) )as distrito");
		$this->db->from('asegurado a');
		$this->db->where('aseg_id', $id);
	$aseg=$this->db->get();
	return $aseg->result();
	}

	function getCitas(){
		$this->db->select("idcita, idasegurado, idproveedor, idespecialidad, observaciones_cita, CONCAT(COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''))as asegurado,year(fecha_cita) as anio, (month(fecha_cita)-1)as mes, day(fecha_cita)as dia, fecha_cita, hora_cita_inicio, hora_cita_fin, estado_cita");
		$this->db->from("cita c ");
		$this->db->join("asegurado a","c.idasegurado=a.aseg_id"); 

	$citas=$this->db->get();
	return $citas->result();
	}

	function getProveedores(){
		$this->db->select("idproveedor, nombre_comercial_pr");
		$this->db->from("proveedor");
		$this->db->order_by("nombre_comercial_pr");

	$proveedores=$this->db->get();
	return $proveedores->result();
	}

	function getProductos(){
		$this->db->select("idespecialidad, descripcion_prod");
		$this->db->from("producto");
		$this->db->where("idtipoproducto",1);
		$this->db->order_by("descripcion_prod");

	$productos=$this->db->get();
	return $productos->result();
	}

	function getNumCitas($data){
		$this->db->select("ci.*");
		$this->db->from("cita ci");
		$this->db->join("certificado_asegurado ca","ca.certase_id=ci.idcertificadoasegurado");
		$this->db->join("certificado c","c.cert_id=ca.cert_id ");
		$this->db->join("plan pl","pl.idplan=c.plan_id ");
		$this->db->where("DATEDIFF(".$data['fecha_cita'].",fecha_cita) <= dias_atencion and idcertificadoasegurado=".$data['certase_id']." and estado_cita<>0");
		$this->db->order_by("idcita","desc");
		$this->db->limit(1);

	$numcitas=$this->db->get();
	return $numcitas->num_rows();
	}

	function getNumAtenciones($data){
		$this->db->select("ci.*");
		$this->db->from("cita ci");
		$this->db->join("certificado_asegurado ca","ca.certase_id=ci.idcertificadoasegurado");
		$this->db->join("certificado c","c.cert_id=ca.cert_id ");
		$this->db->join("plan pl","pl.idplan=c.plan_id ");
		$this->db->where("DATEDIFF(".$data['fecha_cita'].",fecha_cita) <= dias_atencion and idcertificadoasegurado=".$data['certase_id']." and estado_cita<>0");
		$this->db->order_by("idcita","desc");
		$this->db->limit(1);

	$numatenciones=$this->db->get();
	return $numatenciones->num_rows();
	}

	function saveCalendario($data){
		$array = array(
				 'idasegurado' => $data['aseg_id'],
				 'idcertificadoasegurado' => $data['certase_id'],
				 'idproveedor' => $data['idproveedor'],
				 'idusuario' => 2,
				 'idempresaadmin' => 1,
				 'fecha_cita' => $data['fecha_cita'],
				 'idespecialidad' => $data['idespecialidad'],
				 'estado_cita' => $data['estado']
 				 );
		$this->db->insert('cita',$array);
	}
}
?>