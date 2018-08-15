<?php 
ini_set('max_execution_time', 600); 
class Comprobante_pago_mdl extends CI_model {

	function Comprobante_pago() {
		parent::__construct(); //llamada al constructor de Model.
		$this->load->database();
	}

	function getCanales(){
 		$this->db->select("idclienteempresa, nombre_comercial_cli");
 		$this->db->from("cliente_empresa");

	 	$canales = $this->db->get();
	 	return $canales->result();
	}

	function getPlanes($canales){
		$this->db->select("p.idplan, p.idclienteempresa, p.nombre_plan, p.codigo_plan, p.estado_plan, c.nombre_comercial_cli, s.numero_serie");
		$this->db->from("plan p");
		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
		$this->db->join("serie s","c.id_serie=s.idserie");
		$this->db->where("p.idclienteempresa=".$canales." and estado_plan=1");

 	$planes = $this->db->get();
 	return $planes->result();
	}

	function getTipoDocumento($clienteempresa){
		$this->db->select("t.idtipodocumentomov, t.descripcion_tdm, s.idserie, s.numero_serie");
		$this->db->from("tipo_documento_mov t");
		$this->db->join("canal_detalle c","t.idtipodocumentomov = c.idtipodocumentomov");
		$this->db->join("cliente_empresa c2","idcanal = c2.idclienteempresa");
		$this->db->join("serie s","c.idserie = s.idserie");
		$this->db->where("idclienteempresa", $clienteempresa);

	 	$documento = $this->db->get();
	 	return $documento->result();
	}

	function getPlanesId($planes){
		$this->db->select("idplan");
		$this->db->from("plan p");
		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
		$this->db->where("idplan",$planes);

	 	$planes = $this->db->get();
	 	return $planes->result();
	}

	function getMostrarCorrelativo($serie){
		$this->db->select("c.correlativo");
		$this->db->from("comprobante_pago c");
		//$this->db->join("tipo_documento_mov t" , "id_tipo_documento_mov=t.idtipodocumentomov");		
		//$this->db->join("canal_detalle c2" , "t.idtipodocumentomov=c2.idtipodocumentomov");		
		//$this->db->join("serie s" , "c2.idserie=s.idserie");
		$this->db->where("serie='".$serie."'");
		$this->db->order_by("c.idcomprobante", "DESC");
		$this->db->limit(1);

		$data = $this->db->get();
 		return $data->result();
	}

//para mostrar los datos de boletas y facturas se ha agregado una tabla "estado_cobro" categoria estado de comprobante
//y un campo "estado_cobro" en la tabla cobros con el id del estado


	function getDatosBoleta($inicio, $fin, $canales, $serie){
 		$this->db->distinct("c2.cert_id");
		$this->db->select("c1.cert_num, c1.cob_fechCob, cob_importe, c3.cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, c3.cont_id, c4.nombre_plan, c4.idplan, c5.idestadocobro, c5.descripcion, c7.idserie, cob_id, c7.numero_serie");
 		$this->db->from("cobro c1");
 		$this->db->join("certificado c2","c1.cert_id=c2.cert_id");
 		$this->db->join("contratante c3","c2.cont_id=c3.cont_id");
 		$this->db->join("plan c4" , "c1.plan_id=c4.idplan");
 		$this->db->join("estado_emision c5" , "c1.idestadocobro=c5.idestadocobro");
 		$this->db->join("cliente_empresa c6","c4.idclienteempresa=c6.idclienteempresa");
		$this->db->join("serie c7","c6.id_serie=c7.idserie");
 		$this->db->where("c1.cob_fechCob>='".$inicio."' and c1.cob_fechCob<='".$fin."' and c4.idclienteempresa=".$canales." and c7.numero_serie='".$serie."' and c1.idestadocobro=1");
 		$this->db->group_by("c1.cob_id");
 		$this->db->order_by("cob_fechCob");
	 	
	 	$data = $this->db->get();
		return $data->result();
	}

	function getDatosFacturas($inicio, $fin, $canales, $serie){
		$this->db->select("COUNT(cob_id) as cant, SUM(cob_importe)/100 as cob_importe, plan_id, c2.nombre_plan, c3.razon_social_cli, c3.nombre_comercial_cli, c3.idclienteempresa, c3.numero_documento_cli, c4.idestadocobro, c4.descripcion, c5.idserie, c5.numero_serie");
		$this->db->from("cobro c1");
		$this->db->join("plan c2","c1.plan_id=c2.idplan");
		$this->db->join("cliente_empresa c3","c2.idclienteempresa=c3.idclienteempresa");
		$this->db->join("estado_emision c4","c1.idestadocobro=c4.idestadocobro");
		$this->db->join("serie c5","c3.id_serie=c5.idserie");
		$this->db->where("cob_fechCob>='".$inicio."' and cob_fechCob<='".$fin."' and c2.idclienteempresa=".$canales." and c5.numero_serie='".$serie."' and c1.idestadocobro=1");
		$this->db->group_by("plan_id");

		$data = $this->db->get();
		return $data->result();
 	} 

 	function insertDatosBoletas($inicio, $fin, $fechaEmi, $serie, $correlativo, $idContratante, $importeTotal, $cobro, $idPlan){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_contratante " , $idContratante);
 		$this->db->set("id_tipo_documento_mov", 3);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idcobro" , $cobro);
 		$this->db->set("idplan" , $idPlan);
 		$this->db->set("tipo_moneda" , "PEN");
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->insert("comprobante_pago");
 	}

 	function insertDatosFacturas($inicio, $fin, $fechaEmi, $serie, $correlativo, $idEmpresa, $importeTotal, $idPlan){

 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_cliente_empresa " , $idEmpresa);
 		$this->db->set("id_tipo_documento_mov", 2);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idPlan);
 		$this->db->set("tipo_moneda" , "PEN");
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->insert("comprobante_pago");
 		$this->db->group_by("fecha_emision");
 	}

 	function updateEstadoCobro($inicio, $fin, $idPlan){
 		$this->db->set("idestadocobro", 2);
		$this->db->where("cob_fechCob>='".$inicio."' and cob_fechCob<='".$fin."' and plan_id=".$idPlan." and idestadocobro=1");
		$this->db->update("cobro"); 
 	}

 	function getDatosBoletaEmitida($inicio, $fin, $serie, $idPlan){
 		$this->db->select("c1.idcomprobante, c1.id_contratante, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) as contratante, c2.cont_numDoc, p.nombre_plan, c3.idestadocobro, e.descripcion");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("contratante c2" , "c1.id_contratante=c2.cont_id");
 		$this->db->join("plan p" , "c1.idplan=p.idplan");
 		$this->db->join("cobro c3" , "c1.idcobro=c3.cob_id");
 		$this->db->join("estado_emision e" , "c3.idestadocobro=e.idestadocobro");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and plan_id=".$idPlan);

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosFacturaEmitida($inicio, $fin, $serie, $idPlan){
 		$this->db->distinct("c1.idcomprobante");
 		$this->db->select("c1.idcomprobante, c1.id_cliente_empresa, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, c2.razon_social_cli, c2.numero_documento_cli, p.nombre_plan, c3.idestadocobro, e.descripcion");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("cliente_empresa c2" , "c1.id_cliente_empresa=c2.idclienteempresa");
 		$this->db->join("plan p" , "c1.idplan=p.idplan");
 		$this->db->join("cobro c3" , "p.idplan=c3.plan_id");
 		$this->db->join("estado_emision e" , "c3.idestadocobro=e.idestadocobro");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and plan_id=".$idPlan);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosContratante(){
 		$this->db->select("cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as nombre, cont_direcc");
 		$this->db->from("contratante");
 		$this->db->where("cont_nom1 = 'DANIEL' and cont_direcc != ''");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosExcelBoletas($inicio, $fin, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.cont_numDoc");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosExcelFacturas($inicio, $fin, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.numero_documento_cli");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa");	
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosPdfBoletas($idcomprobante){
 		$this->db->select("c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.cont_numDoc, CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) as contratante, c2.cont_numDoc");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id");		
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	 function getDatosPdfFacturas($idcomprobante){
 		$this->db->select("c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.numero_documento_cli, c2.razon_social_cli, c2.numero_documento_cli");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa");	
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlBoletas($inicio, $fin, $serie, $idPlan){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."' and c.idPlan=".$idPlan);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	 function getDatosXmlFacturas($inicio, $fin, $serie, $idPlan){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, p.nombre_plan, c1.descripcion, c1.centro_costo, c2.numero_documento_cli, c2.razon_social_cli, c2.nombre_comercial_cli");
 		$this->db->from("Comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan");
 		$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa");	
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."' and c.idPlan=".$idPlan);
 		
		$data = $this->db->get();
		return $data->result();
 	}
}
?>