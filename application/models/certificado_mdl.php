<?php
 class Certificado_mdl extends CI_Model {

 function Certificado_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCertificados($doc){
 		$this->db->distinct('cert.cert_id');
 		$this->db->select('DATE_ADD(cert.cert_iniVig, INTERVAL pl.dias_carencia DAY) as cert_iniVig, DATE_ADD(cert.cert_finVig, INTERVAL pl.dias_mora DAY) AS cert_finVig, cert.cert_id, cont_numDoc, cert_num, nombre_plan, nombre_comercial_cli, cert_upProv, cert.cert_estado');
 		$this->db->from('certificado cert');
 		$this->db->join('certificado_asegurado ca','ca.cert_id=cert.cert_id');
 		$this->db->join('asegurado a','ca.aseg_id=a.aseg_id');
 		$this->db->join('contratante co', 'cert.cont_id=co.cont_id'); 
	 	$this->db->join('plan pl', 'cert.plan_id = pl.idplan'); 	 
	 	$this->db->join('cliente_empresa cl', 'cl.idclienteempresa=pl.idclienteempresa'); 
	 	$this->db->where("aseg_numDoc=$doc  or cont_numDoc=$doc");
	 	$this->db->order_by("cert_id","desc");

	 $certificados = $this->db->get();
	 return $certificados->result();
 	}
	 
	function getCertificado($id) {
	 	$this->db->select("cert.cert_id, cert.cert_num, cert.plan_id, cert.cert_estado, cert.cert_upProv, DATE_ADD(cert.cert_iniVig, INTERVAL pl.dias_carencia DAY) as cert_ini, cert.cert_iniVig, DATE_ADD(cert.cert_finVig, INTERVAL pl.dias_mora DAY) AS cert_fin, cert_finVig, cl.nombre_comercial_cli, pl.nombre_plan, CONCAT(ROUND((pl.prima_monto+(pl.prima_adicional*num)),2),' PEN') as prima_monto, dias_atencion, (select cob_fechCob from cobro where cert_id=cert.cert_id order by cob_fechCob desc limit 1) AS ultimo_cobro, DATE_ADD((SELECT MAX(cob_finCobertura) FROM cobro co WHERE co.cert_id = cert.cert_id LIMIT 1),INTERVAL pl.dias_mora DAY) AS ultima_cobertura, (select can_finVig from cancelado where cert_id=".$id." order by can_id desc limit 1) as fec_can, flg_activar, cl.idclienteempresa");
	 	$this->db->from('certificado cert');
	 	$this->db->join('contratante co', 'cert.cont_id=co.cont_id'); 
	 	$this->db->join('plan pl', 'cert.plan_id = pl.idplan'); 	 
	 	$this->db->join('cliente_empresa cl', 'cl.idclienteempresa=pl.idclienteempresa'); 
	 	$this->db->join("(select count(aseg_id)-1 as num, a.cert_id from certificado_asegurado a where a.cert_id=".$id.")x","cert.cert_id=x.cert_id");
	 	$this->db->where('cert.cert_id', $id);

	 $certificado = $this->db->get();
	 return $certificado->result();
	}
	

	function getCertificadoApellidos($ap){
	 	$this->db->select("cert.cert_id,CONCAT(COALESCE(cont_ape1,''), ' ', COALESCE(cont_ape2,''),' ',COALESCE(cont_nom1,''), ' ', COALESCE(cont_nom2,''))as contratante, cont_numDoc, aseg_numDoc, CONCAT(COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''))as asegurado, nombre_plan, nombre_comercial_cli, cert_num");
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

	function getContratante($id){
		$this->db->select("ce.cert_id, c.cont_id, cont_numDoc, cont_nom1, cont_nom2, cont_ape1, cont_ape2, cont_tipoDoc, cont_direcc, cont_telf, cont_email, coalesce(SUBSTR(cont_ubg, 15,2),'') as dep, coalesce(SUBSTR(cont_ubg, 15,4),'') as prov, coalesce(SUBSTR(cont_ubg, 15,6),'') as dist");
	    $this->db->from("contratante c");
	    $this->db->join("certificado ce","c.cont_id=ce.cont_id");
	    $this->db->where("cert_id",$id);

    $contratante=$this->db->get();
    return $contratante->result();
	}

	function cancelar_certificado($id){
		$data = array(
			'cert_upProv' => 0
		);
		$this->db->where('cert_id',$id);
		return $this->db->update('certificado', $data);
	}

	function getAsegurados($id){
		$this->db->select("ca.certase_id, a.aseg_id, aseg_numDoc, aseg_telf, (now()-aseg_fechNac) as edad, aseg_fechNac, aseg_email, aseg_direcc, concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''),' ',coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''))as asegurado, ca.certase_id, ca.cert_estado, ca.cert_finVig");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito=SUBSTR(aseg_ubg,8,2) )as distrito");
		$this->db->select("(SELECT MAX(fecha_atencion) AS ultima_atencion FROM siniestro WHERE idasegurado=a.aseg_id and estado_siniestro<>0 and idcertificado=".$id." LIMIT 1) AS ultima_atencion");
		$this->db->from('asegurado a');
		$this->db->join('certificado_asegurado ca','a.aseg_id=ca.aseg_id');
		$this->db->join('certificado c','c.cert_id=ca.cert_id');
		$this->db->where('c.cert_id', $id);

	$asegurados = $this->db->get();
	return $asegurados->result();
	}

	function getCobros($id){
		$this->db->select("coalesce(idcomprobante,0) as idcomprobante, cob_id,cob_fechCob,cob_vezCob,concat(round((cob_importe/100),2),' ',cob_moneda) as importe, cob_iniCobertura,cob_finCobertura");
		$this->db->from("cobro co");
		$this->db->join("comprobante_pago cp","co.cob_id=cp.idcobro","left");
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

	function getAtenciones($id,$cert){
		$this->db->select("idsiniestro, s.idasegurado, c.idcertificadoasegurado, idcertificado, c.idcita, num_orden_atencion, estado_atencion, estado_siniestro, estado_cita, fecha_cita, fecha_atencion, nombre_comercial_pr, nombre_esp");
		$this->db->from("siniestro s");
		$this->db->join('especialidad e','s.idespecialidad=e.idespecialidad');		
		$this->db->join('proveedor pr','pr.idproveedor=s.idproveedor');					
		$this->db->join('cita c','c.idcita=s.idcita','left');
		$this->db->where("s.idasegurado=".$id." and idcertificado=".$cert);
		$this->db->order_by("idsiniestro","desc");
	$atenciones=$this->db->get();
	return $atenciones->result();
	}

	function getCita($data){
		$this->db->select("idsiniestro, estado_atencion, num_orden_atencion, c.idproveedor, c.idespecialidad, estado_cita, fecha_cita, hora_cita_inicio, hora_cita_fin, observaciones_cita");
		$this->db->from("cita c");
		$this->db->join("siniestro s","c.idcita=s.idcita");
		$this->db->where("c.idcita",$data['cita']);
	$cita=$this->db->get();
	return $cita->result();
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
		$this->db->select("aseg_nom1,aseg_nom2,aseg_ape1,aseg_ape2, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_id, aseg_direcc, coalesce(SUBSTR(aseg_ubg, 1,2),'') as dep, coalesce(SUBSTR(aseg_ubg, 1,4),'') as prov, coalesce(SUBSTR(aseg_ubg, 1,6),'') as dist, aseg_email, aseg_telf, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2)) as aseg_fechNac, tipoDoc_id, aseg_numDoc, aseg_sexo, aseg_estCiv");
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
		$this->db->select("idproveedor, nombre_comercial_pr, direccion_pr, (select descripcion_ubig from ubigeo where iddepartamento=cod_departamento_pr and idprovincia='00' and iddistrito='00')as dep, (select descripcion_ubig from ubigeo where iddepartamento=cod_departamento_pr and idprovincia= cod_provincia_pr and iddistrito='00')as prov, (select descripcion_ubig from ubigeo where iddepartamento=cod_departamento_pr and idprovincia= cod_provincia_pr and iddistrito=cod_distrito_pr)as dist");
		$this->db->from("proveedor");
		$this->db->where("estado_pr",1);
		$this->db->order_by("nombre_comercial_pr");

	$proveedores=$this->db->get();
	return $proveedores->result();
	}

	function getServicios(){
		$query = $this->db->query("select idproveedor, DATE_FORMAT(hora_ini,'%H:%i') as hora_ini, DATE_FORMAT(hora_fin,'%H:%i') as hora_fin, ps.id_servicio, serv_descripcion from proveedor_servicios ps inner join servicios s on s.id_servicio=ps.id_servicio");
		return $query->result();
	}

	function getProductos($id){
		$productos = $this->db->query("select idespecialidad, descripcion_prod from producto pr 
										inner join producto_detalle prd on pr.idproducto=prd.idproducto
										inner join plan_detalle pd on pd.idplandetalle=prd.idplandetalle
										where pd.idvariableplan=1 and pd.idplan=(select plan_id from certificado where cert_id=$id)");
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
				 'idusuario_reserva' => $data['idusuario'],
				 'hora_cita_inicio' => $data['inicio'],
				 'hora_cita_fin' => $data['fin'],
				 'idempresaadmin' => 1,
				 'fecha_cita' => $data['fecha_cita'],
				 'idespecialidad' => $data['idespecialidad'],
				 'estado_cita' => $data['estado'],
				 'observaciones_cita' => $data['obs'],
				 'idusuario' => $data['idusuario_confirma'],
				 'createdat' => $data['hoy'],
				 'updatedat' => $data['hoy']
 				 );
		$this->db->insert('cita',$array);
	}

	function updateCalendario($data){
		$array = array(
				 'idasegurado' => $data['aseg_id'],
				 'idcertificadoasegurado' => $data['certase_id'],
				 'idproveedor' => $data['idproveedor'],
				 'idusuario' => $data['idusuario'],
				 'hora_cita_inicio' => $data['inicio'],
				 'hora_cita_fin' => $data['fin'],
				 'idempresaadmin' => 1,
				 'fecha_cita' => $data['fecha_cita'],
				 'idespecialidad' => $data['idespecialidad'],
				 'estado_cita' => $data['estado'],
				 'observaciones_cita' => $data['obs'],
				 'updatedat' => $data['hoy']
 				 );
		$this->db->where('idcita',$data['idcita']);
		return $this->db->update('cita', $array);
	}

	function num_orden_atencion(){
		$this->db->select("lpad((num_orden_atencion +1),6,'0') as num_orden_atencion");
		$this->db->from("siniestro");
		$this->db->order_by("idsiniestro","desc");
		$this->db->limit(1);
		$num = $this->db->get();
		return $num->result();
	}

	function savePreOrden($data){
		$array = array(
			'idasegurado' => $data['aseg_id'],
			'idcertificado' => $data['cert_id'],
			'idproveedor' => $data['idproveedor'],
			'fecha_atencion' => $data['fecha_cita'],
			'idespecialidad' => $data['idespecialidad'],
			'idcita' => $data['idcita'],
			'idareahospitalaria' =>1,
			'estado_atencion' => 'P',
			'num_orden_atencion' => $data['num'],
			'fase_atencion' => 0
			);
		$this->db->insert('siniestro',$array);
	}

	function updatePreOrden($data){
		$array = array(
			'idproveedor' => $data['idproveedor'],
			'fecha_atencion' => $data['fecha_cita'],
			'idespecialidad' => $data['idespecialidad']
			);
		$this->db->where('idsiniestro',$data['idsiniestro']);
		return $this->db->update('siniestro', $array);
	}

	function eliminar_cita($data){
		$array = array(
				 'estado_cita' => 0
 				 );
		$this->db->where('idcita',$data['idcita']);
		return $this->db->update('cita', $array);
	}

	function eliminar_orden($data){
		$array = array(
			'estado_siniestro' => 0
			);
		$this->db->where('idsiniestro',$data['idsiniestro']);
		return $this->db->update('siniestro', $array);
	}

	function ubigeo(){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("idprovincia='00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$ubigeo = $this->db->get();
		return $ubigeo->result();
	}

	function provincia2($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(cont_ubg, 15,2),'') from contratante WHERE cont_id=(select cont_id from certificado where cert_id=".$data['id2'].")) and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia2 = $this->db->get();
		return $provincia2->result();
	}

	function distrito2($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(cont_ubg, 15,2),'') from contratante WHERE cont_id=(select cont_id from certificado where cert_id=".$data['id2'].")) and idprovincia=(SELECT coalesce(SUBSTR(cont_ubg, 17,2),'') from contratante WHERE cont_id=(select cont_id from certificado where cert_id=".$data['id2'].")) and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito2 = $this->db->get();
		return $distrito2->result();
	}

	function provincia3($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(aseg_ubg, 1,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia2 = $this->db->get();
		return $provincia2->result();
	}

	function distrito3($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(aseg_ubg, 1,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and idprovincia=(SELECT coalesce(SUBSTR(aseg_ubg, 3,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito2 = $this->db->get();
		return $distrito2->result();
	}


	function cont_save($data){
		$array = array(
			'cont_direcc' => $data['direcc'],
			'cont_ubg' => "00000000000000".$data['ubigeo'],
			'cont_telf' => $data['telf'],
			'cont_email' => $data['correo']
		);
		$this->db->where('cont_id',$data['cont_id']);
		return $this->db->update('contratante', $array);
	}

	function up_aseg($data){
		$array = array(
			'aseg_fechNac' => str_replace("-","",$data['fec_nac']),
			'aseg_direcc' => $data['direccion'],
			'aseg_telf' => $data['telf'],
			'aseg_ubg' => $data['dis'],
			'aseg_estCiv' => $data['ec'],
			'aseg_sexo' => $data['genero'],
			'aseg_email' => $data['correo']
		);
		$this->db->where('aseg_id',$data['aseg_id']);
		return $this->db->update('asegurado', $array);
	}

	function contenido_mail($data){
		$this->db->select("aseg_numDoc, concat(COALESCE(aseg_ape1,''),' ', COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as afiliado, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2)) as aseg_fechNac, nombre_comercial_pr, aseg_email, nombre_plan, e.nombre_esp, fecha_cita, hora_cita_inicio, observaciones_cita, cuerpo_mail,p.idproveedor");
		$this->db->from("cita c");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");
		$this->db->join("especialidad e","e.idespecialidad=c.idespecialidad");
		$this->db->join("certificado_asegurado ca","ca.certase_id=c.idcertificadoasegurado");
		$this->db->join("certificado ce","ce.cert_id=ca.cert_id");
		$this->db->join("asegurado a","a.aseg_id=ca.aseg_id");
		$this->db->join("plan pl","pl.idplan = ce.plan_id");
		$this->db->where("idcita",$data['idcita']);

		$query = $this->db->get();
		return $query->result();
	}

	function destinatarios($data){
		$this->db->select("email_cp, concat(coalesce(nombres_cp,''),' ',coalesce(apellidos_cp,''))as nombres");
		$this->db->from("contacto_proveedor");
		$this->db->where("envio_correo_cita",1);
		$this->db->where("estado_cp",1);
		$this->db->where("idproveedor",$data['idproveedor']);

		$query = $this->db->get();
		return $query->result();
	}

	function save_incidencia($data){
		$array = array
		(
			'tipoincidencia' => $data['tipo'], 
			'cert_id' => $data['cert_id'],
			'idasegurado' => $data['aseg_id'],
			'descripcion' => $data['descripcion'],
			'idusuario_registra' => $data['idusu'],
			'fech_reg' => date('Y-m-d H:i:s')
		);

		$this->db->insert("incidencia",$array);
	}

	function getCoberturasOperador($id){
		$query = $this->db->query("select nombre_var, texto_web, num_eventos, tiempo, concat(o.descripcion,' ',valor_detalle, case when idoperador=1 then '%' else '' end)as coaseguro FROM variable_plan vp 
									inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
									inner join operador o on  pd.simbolo_detalle=o.idoperador
									inner join plan p on pd.idplan=p.idplan 
									inner join certificado c on c.plan_id=p.idplan
									where c.cert_id=$id and estado_pd=1 order by vp.idvariableplan");
		return $query->result();
	}

	function getCoberturas($id){
		$query = $this->db->query("select nombre_var, texto_web
									FROM variable_plan vp 
									inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
									inner join plan p on pd.idplan=p.idplan 
									inner join certificado c on c.plan_id=p.idplan
									where c.cert_id=$id and estado_pd=1 and simbolo_detalle='' order by vp.idvariableplan");
		return $query->result();
	}

	function getAtencionCliente(){
		$query = $this->db->query("select nombres_col, correo_laboral from colaborador c inner join usuario u on c.idusuario=u.idusuario where u.idtipousuario=5 and estado_us=1");
		return $query->result();
	}

	function getNomAfiliado($dni){
		$query = $this->db->query("select concat(coalesce(aseg_nom1,aseg_nom2),' ',coalesce(aseg_ape1,aseg_ape2)) as asegurado, coalesce(aseg_nom1, aseg_nom2) as nombre from asegurado where aseg_numDoc=$dni  limit 1");
		return $query->row_array();
	}
	function getNomAfiliado2($id){
		$query = $this->db->query("select concat(coalesce(aseg_nom1,aseg_nom2),' ',coalesce(aseg_ape1,aseg_ape2)) as asegurado, coalesce(aseg_nom1, aseg_nom2) as nombre from asegurado where aseg_id=$id");
		return $query->row_array();
	}
}
?>