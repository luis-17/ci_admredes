<?php 
ini_set('max_execution_time', 6000); 
ini_set('memory_limit', -1);
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

	function getSerieVerificar(){
		$this->db->select("s.idserie, s.numero_serie, s.descripcion_ser, c.idclienteempresa");
		$this->db->from("serie s");
		$this->db->join("cliente_empresa c" , "s.idserie=c.id_serie" , "left");
		$this->db->where("idserie >= 3");

		$data = $this->db->get();
		return $data->result();
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

	function getPlanesSelect(){
		$this->db->select("p.idplan, p.nombre_plan, p.idclienteempresa, c.nombre_comercial_cli");
		$this->db->from("plan p");
		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
		$this->db->where("estado_plan=1");

 	$planes = $this->db->get();
 	return $planes->result();
	}

	function getPlanesSelectId($idplan){
		$this->db->select("p.idplan, p.nombre_plan, p.idclienteempresa, c.nombre_comercial_cli");
		$this->db->from("plan p");
		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
		$this->db->where("estado_plan=1 and p.idplan=".$idplan);

 	$planes = $this->db->get();
 	return $planes->result();
	}


	/*function getTipoDocumento($clienteempresa){
		$this->db->select("t.idtipodocumentomov, t.descripcion_tdm, s.idserie, s.numero_serie");
		$this->db->from("tipo_documento_mov t");
		$this->db->join("canal_detalle c","t.idtipodocumentomov = c.idtipodocumentomov");
		$this->db->join("cliente_empresa c2","idcanal = c2.idclienteempresa");
		$this->db->join("serie s","c.idserie = s.idserie");
		$this->db->where("idclienteempresa", $clienteempresa);

	 	$documento = $this->db->get();
	 	return $documento->result();
	}*/

	function getTipoDocumento($clienteempresa){
		$this->db->select("t.idtipodocumentomov, t.descripcion_tdm, s.idserie, s.numero_serie");
		$this->db->from("serie s");
		$this->db->join("tipo_documento_mov t","s.tipo_documento = t.idtipodocumentomov");
		$this->db->join("cliente_empresa c","s.idserie = c.id_serie");
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

	function getUltimoCorrelativo($serie){
		$this->db->select("case when max(correlativo) is null then 0 else max(correlativo) end as correlativo");
		$this->db->from("comprobante_pago");
		$this->db->where("serie='".$serie."'");

		$data = $this->db->get();
 		return $data->result();		
	}

	function getUltimoCorrelativoMasUno($serie){
		$this->db->select("case when max(correlativo) is null then 1 else max(correlativo)+1 end as correlativo");
		$this->db->from("comprobante_pago");
		$this->db->where("serie='".$serie."'");

		$data = $this->db->get();
 		return $data->result();		
	}

		function getUltimoCorrelativoMasUnoRc($fecha_emision){
		$this->db->select("case when max(correlativo) is null then 1 else max(correlativo)+1 end as correlativo");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision='".$fecha_emision."'");

		$data = $this->db->get();
 		return $data->result();		
	}

	function getUltimoCorrelativoMasUnoRes($fecha_emision){
		$this->db->select("case when max(nume_corre_res) is null then 1 else max(nume_corre_res) end as nume_corre_res");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision='".$fecha_emision."'");

		$data = $this->db->get();
 		return $data->result();		
	}

	function getUltimoContador($fecha_emision, $correlativo){
		$this->db->select("COUNT(nume_corre_res) as contadorCorre");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision='".$fecha_emision."' and nume_corre_res=".$correlativo);

		$data = $this->db->get();
 		return $data->result();		
	}

	function getUltimoCorrelativoMasUnoFecha($serie, $inicio, $fin){
		$this->db->select("case when max(correlativo) is null then 1 else max(correlativo)+1 end as correlativo");
		$this->db->from("comprobante_pago");
		$this->db->where("serie='".$serie."' and fecha_emision>='".$inicio."' and fecha_emision<='".$fin."'");

		$data = $this->db->get();
 		return $data->result();		
	}

	function getSerie(){
		$this->db->select("idserie, numero_serie, descripcion_ser");
		$this->db->from("serie");
		$this->db->where("idserie not in (1, 2, 9, 10, 11, 12)");
		$this->db->order_by("numero_serie");

		$data = $this->db->get();
 		return $data->result();
	}

	function getSerieCredito(){
		$this->db->select("idserie, numero_serie, descripcion_ser");
		$this->db->from("serie");
		$this->db->where("idserie in (9, 11)");
		$this->db->order_by("numero_serie");

		$data = $this->db->get();
 		return $data->result();
	}

	function getSerieEmitir(){
		$this->db->select("idserie, numero_serie, descripcion_ser");
		$this->db->from("serie");
		$this->db->where("idserie in (9, 10, 11, 12)");
		$this->db->order_by("numero_serie");

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
 		//$this->db->group_by("c1.cob_id");
 		$this->db->order_by("cob_fechCob");
	 	
	 	$data = $this->db->get();
		return $data->result();
	}

	function getDatosSumaBoleta($inicio, $fin, $canales, $serie){
		$this->db->select("case when TRUNCATE(SUM(cob_importe/100),2) is null then 0 else TRUNCATE(SUM(cob_importe/100),2) end as suma");
		$this->db->from("cobro c1");
		$this->db->join("plan p1" , "c1.plan_id=p1.idplan");
		$this->db->join("cliente_empresa c2" , "p1.idclienteempresa=c2.idclienteempresa");
		$this->db->join("serie s1" , "c2.id_serie=s1.idserie");
		$this->db->where("c1.cob_fechCob>='".$inicio."' and c1.cob_fechCob<='".$fin."' and p1.idclienteempresa=".$canales." and s1.numero_serie='".$serie."' and c1.idestadocobro=1");

		$data = $this->db->get();
		return $data->result();	
	}

	function getDatosSumaEmiSoles($inicio, $canales, $serie){
		$this->db->select("case when TRUNCATE(SUM(importe_total),2) is null then 0 else TRUNCATE(SUM(importe_total),2) end as sumas");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision='".$inicio."' and serie='".$serie."' and idestadocobro=2 and tipo_moneda='PEN'");

		$data = $this->db->get();
		return $data->result();	
	}

	function getDatosSumaEmiDolares($inicio, $canales, $serie){
		$this->db->select("case when TRUNCATE(SUM(importe_total),2) is null then 0 else TRUNCATE(SUM(importe_total),2) end as sumad");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision='".$inicio."' and serie='".$serie."' and idestadocobro=2 and tipo_moneda='USD'");

		$data = $this->db->get();
		return $data->result();	
	}

	function getDatosSumaDecSoles($inicio, $fin, $canales, $serie){
		$this->db->select("case when TRUNCATE(SUM(importe_total),2) is null then 0 else TRUNCATE(SUM(importe_total),2) end as sumas");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie='".$serie."' and idestadocobro=3 and tipo_moneda='PEN'");

		$data = $this->db->get();
		return $data->result();	
	}

	function getDatosSumaDecDolares($inicio, $fin, $canales, $serie){
		$this->db->select("case when TRUNCATE(SUM(importe_total),2) is null then 0 else TRUNCATE(SUM(importe_total),2) end as sumad");
		$this->db->from("comprobante_pago");
		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie='".$serie."' and idestadocobro=3 and tipo_moneda='USD'");

		$data = $this->db->get();
		return $data->result();	
	}

	function getDatosFacturas($inicio, $fin, $canales, $serie){
		$this->db->select("COUNT(cob_id) as cant, SUM(cob_importe)/100 as cob_importe, plan_id, c2.nombre_plan, c3.razon_social_cli, c3.nombre_comercial_cli, c3.idclienteempresa, c3.numero_documento_cli, c4.idestadocobro, c5.idserie, c5.numero_serie");
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

 	function getDatosSumaFacturas($inicio, $fin, $canales, $serie){
 		$this->db->select("case when TRUNCATE(SUM(cob_importe/100),2) is null then 0 else TRUNCATE(SUM(cob_importe/100),2) end as suma");
 		$this->db->from("cobro c1");
 		$this->db->join("plan p1" , "c1.plan_id=p1.idplan");
 		$this->db->join("cliente_empresa c2" , "p1.idclienteempresa=c2.idclienteempresa");
 		$this->db->join("serie s1" , "c2.id_serie=s1.idserie");
 		$this->db->where("c1.cob_fechCob>='".$inicio."' and c1.cob_fechCob<='".$fin."' and p1.idclienteempresa=".$canales." and s1.numero_serie='".$serie."' and c1.idestadocobro=1");

 		$data = $this->db->get();
 		return $data->result();
 	}

 	function insertDatosBoletas($inicio, $fin, $fechaEmi, $serie, $correlativo, $idContratante, $importeTotal, $cobro, $idPlan, $serieRes, $correRes){
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
 		$this->db->set("idestadocobro" , 2);
 		$this->db->set("num_serie_res" , $serieRes);
 		$this->db->set("nume_corre_res" , $correRes);
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
 		$this->db->set("idestadocobro" , 2);
 		$this->db->insert("comprobante_pago");
 		$this->db->group_by("fecha_emision");
 	}

 	function updateEstadoCobro($fecha_emision, $cob_id){
 		$this->db->set("idestadocobro", 2);
		$this->db->where("cob_fechCob='".$fecha_emision."' and cob_id=".$cob_id." and idestadocobro=1");
		$this->db->update("cobro"); 
 	}

 	function updateEstadoCobroFact($inicio, $fin, $idPlanCheck){
 		$this->db->set("idestadocobro", 2);
		$this->db->where("cob_fechCob>='".$inicio."' and cob_fechCob<='".$fin."' and plan_id=".$idPlanCheck." and idestadocobro=1");
		$this->db->update("cobro"); 
 	}

 	function getDatosBoletaEmitida($inicio, $serie){
 		$this->db->select("c1.idcomprobante, c1.id_contratante, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio, c1.tipo_moneda");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("contratante c2" , "c1.id_contratante=c2.cont_id" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "c1.idcobro=c3.cob_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision='".$inicio."' and c1.serie='".$serie."' and c1.idestadocobro=2");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosBoletaAnulacion($inicio, $fin, $serie){
 		$this->db->select("c1.idcomprobante, c1.id_contratante, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("contratante c2" , "c1.id_contratante=c2.cont_id" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "c1.idcobro=c3.cob_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and c1.idestadocobro in (3, 4)");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosBoletaDeclarada($inicio, $fin, $serie){
 		$this->db->select("c1.idcomprobante, c1.id_contratante, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio, c1.tipo_moneda");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("contratante c2" , "c1.id_contratante=c2.cont_id" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "c1.idcobro=c3.cob_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and c1.idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosBoletaReporte($inicio, $fin, $serie){
 		$this->db->select("c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '00000000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c3.cert_num");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("contratante c2" , "c1.id_contratante=c2.cont_id" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "c1.idcobro=c3.cob_id" , "left");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and c1.idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosFacturaEmitida($inicio, $serie){
 		$this->db->distinct("c1.idcomprobante");
 		$this->db->select("c1.idcomprobante, c1.id_cliente_empresa, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, c2.razon_social_cli, c2.numero_documento_cli, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio, c1.tipo_moneda");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("cliente_empresa c2" , "c1.id_cliente_empresa=c2.idclienteempresa" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "p.idplan=c3.plan_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision='".$inicio."' and c1.serie='".$serie."' and c1.idestadocobro=2");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosFacturaAnulacion($inicio, $fin, $serie){
 		$this->db->distinct("c1.idcomprobante");
 		$this->db->select("c1.idcomprobante, c1.id_cliente_empresa, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, c2.razon_social_cli, c2.numero_documento_cli, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("cliente_empresa c2" , "c1.id_cliente_empresa=c2.idclienteempresa" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "p.idplan=c3.plan_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and c1.idestadocobro in (3,4)");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosFacturaDeclarada($inicio, $fin, $serie){
 		$this->db->distinct("c1.idcomprobante");
 		$this->db->select("c1.idcomprobante, c1.id_cliente_empresa, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, c2.razon_social_cli, c2.numero_documento_cli, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio, c1.tipo_moneda");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("cliente_empresa c2" , "c1.id_cliente_empresa=c2.idclienteempresa" , "left");
 		$this->db->join("plan p" , "c1.idplan=p.idplan" , "left");
 		$this->db->join("cobro c3" , "p.idplan=c3.plan_id" , "left");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro" , "left");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and c1.idestadocobro=3");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosContratante($inicio, $fin, $serie){
 		$this->db->distinct("cont_numDoc");
 		$this->db->select("cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as nombre, cont_direcc");
 		$this->db->from("contratante c");
 		$this->db->join("comprobante_pago c1" , "c.cont_id=c1.id_contratante");
 		$this->db->join("cobro c2" , "c1.idcobro=c2.cob_id");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and serie = '".$serie."'");
 		//$this->db->limit(3);

		$data = $this->db->get();
		return $data->result();
 	}

 	function getCentroCosto($canales){
 		$this->db->select("c.centro_costo");
		$this->db->from("centro_costo c");
		$this->db->join("plan p" , "c.idcentrocosto=p.idplan");
		$this->db->where("p.idplan=".$canales);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getSumaExcel($inicio, $fin, $serie){
 		$this->db->select("SUM(importe_total) as suma");
 		$this->db->from("comprobante_pago c");
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result(); 		
 	}

 	function getDatosExcelBoletas($inicio, $fin, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(fecha_emision)) ) , MONTH(fecha_emision)) as mes, CONCAT(REPEAT( '0', 4 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, p.idplan, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, c3.centro_costo");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");
 		$this->db->join("centro_costo c3" , "p.idplan=c3.idcentrocosto" , "left");
 		//$this->db->join("cobro c3","c.idcobro=c3.cob_id");
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."' and idestadocobro=3");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosExcelFacturas($inicio, $fin, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(fecha_emision)) ) , MONTH(fecha_emision)) as mes, CONCAT(REPEAT( '0', 4 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, p.idplan, c2.numero_documento_cli");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosExcelBoletasNota($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(fecha_emision)) ) , MONTH(fecha_emision)) as mes, CONCAT(REPEAT( '0', 4 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, p.nombre_plan, p.idplan, case when c2.cont_numDoc is null then c.num_doc_manual else CONCAT(c2.cont_numDoc) end as cont_numDoc, c.serie_doc, CONCAT(REPEAT( '0', 4 - LENGTH( correlativo_doc) ) , correlativo_doc) as correlativo_doc, c.fecha_doc");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");
 		$this->db->join("cobro c3" , "c.idcobro=c3.cob_id" , "left");
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosExcelFacturasNota($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(fecha_emision)) ) , MONTH(fecha_emision)) as mes, CONCAT(REPEAT( '0', 4 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, p.nombre_plan, p.idplan, c.serie_doc, CONCAT(REPEAT( '0', 4 - LENGTH( c.correlativo_doc) ) , c.correlativo_doc) as correlativo_doc, c.fecha_doc,case when c2.numero_documento_cli is null then c.num_doc_manual else CONCAT(c2.numero_documento_cli) end as numero_documento_cli");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosPdfBoletas($idcomprobante){
 		$this->db->select("c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, 		CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, importe_total as total, (importe_total/1.18) as neto,(importe_total - (importe_total/1.18)) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, case when c2.cont_numDoc is null then '' else c2.cont_numDoc end as cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, c2.cont_direcc, c.tipo_moneda, c.id_contratante, c.sustento_nota");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	 function getDatosPdfFacturas($idcomprobante){
 		$this->db->select("c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c2.numero_documento_cli, c2.razon_social_cli, c2.numero_documento_cli, c2.direccion_legal, c.sustento_nota, c.tipo_moneda, c.numero_letras");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosPdfBoletasNota($idcomprobante){
 		$this->db->select("CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, CONCAT('DEVOLUCIÓN DE COBRO POR ', p.nombre_plan) as nombre_plan, case when c2.cont_numDoc is null then num_doc_manual else CONCAT(c2.cont_numDoc) end as cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then c.nombre_manual else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, c2.cont_direcc, UPPER(c.sustento_nota) as sustento_nota, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo_doc)),correlativo_doc)) as seriecorrelativoDoc, c.tipo_moneda, c.fecha_doc, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo_doc)) as seriecorrelativo_doc");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	 function getDatosPdfFacturasNota($idcomprobante){
 		$this->db->select("CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, CONCAT('DEVOLUCIÓN DE COBRO POR ', p.nombre_plan) as nombre_plan, c2.numero_documento_cli, case when c2.razon_social_cli  is null then c.nombre_manual else CONCAT(c2.razon_social_cli) end as razon_social_cli, case when c2.numero_documento_cli is null then num_doc_manual else CONCAT(c2.numero_documento_cli) end as numero_documento_cli, c2.direccion_legal, UPPER(c.sustento_nota) as sustento_nota, c.serie_doc, c.correlativo_doc, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo_doc)) as seriecorrelativo_doc, c.tipo_moneda, c.fecha_doc, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo_doc)) as seriecorrelativo_doc");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosPdfFacturasNotaDebito($idcomprobante){
 		$this->db->select("CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, c.idcomprobante, CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.fecha_emision, importe_total as total, (importe_total/1.18) as neto, (importe_total - (importe_total/1.18)) as igv, c.serie, CONCAT('INCREMENTO DE COBRO POR ', p.nombre_plan) as nombre_plan, c2.numero_documento_cli, case when c2.razon_social_cli  is null then c.nombre_manual else CONCAT(c2.razon_social_cli) end as razon_social_cli, case when c2.numero_documento_cli is null then num_doc_manual else CONCAT(c2.numero_documento_cli) end as numero_documento_cli, c2.direccion_legal, UPPER(c.sustento_nota) as sustento_nota, c.serie_doc, c.correlativo_doc, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo_doc)) as seriecorrelativo_doc, c.tipo_moneda, c.fecha_doc, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo_doc)) as seriecorrelativo_doc");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("c.idcomprobante=".$idcomprobante);
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlBoletas($inicio, $nume_corre_res, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total/1.18)*0.18 ,2) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c2.cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, nume_corre_res, c.tipo_moneda");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."' and nume_corre_res=".$nume_corre_res." and idestadocobro=2");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlBoletasAgrupadas($inicio, $serie){
 		$this->db->select("fecha_emision, CONCAT(SUBSTRING(fecha_emision,6,2),SUBSTRING(fecha_emision,1,4)) as mesanio, serie, nume_corre_res, correlativo, idestadocobro");
 		$this->db->from("comprobante_pago");		
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."' and idestadocobro=2");
 		 $this->db->group_by("nume_corre_res");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlBoletasAnulacion($inicio, $fin, $nume_corre_res, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total/1.18)*0.18 ,2) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c2.cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, nume_corre_res, c.tipo_moneda");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2","c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and nume_corre_res =".$nume_corre_res." and serie = '".$serie."' and idestadocobro=3");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlBoletasAnulacionAgrupadas($inicio, $fin, $serie){
 		$this->db->select("fecha_emision, CONCAT(SUBSTRING(fecha_emision,6,2),SUBSTRING(fecha_emision,1,4)) as mesanio, serie, nume_corre_res, correlativo, idestadocobro");
 		$this->db->from("comprobante_pago");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."' and idestadocobro=3");
 		 $this->db->group_by("nume_corre_res");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlResumenBoletas($inicio, $fin){
 		$this->db->select("fecha_emision, SUM(importe_total) as total, MAX(correlativo) as max, MIN(correlativo) as min, TRUNCATE((SUM(importe_total)/1.18)*0.18 ,2) as igv, TRUNCATE((SUM(importe_total)/1.18) ,2) as neto, CONCAT(SUBSTRING(fecha_emision,6,2),SUBSTRING(fecha_emision,1,4)) as mesanio, CONCAT(REPEAT( '0', 8 - LENGTH( MAX(correlativo)) ) , MAX(correlativo)) as correlativoMaxCero, CONCAT(REPEAT( '0', 8 - LENGTH( MIN(correlativo)) ) , MIN(correlativo)) as correlativoMinCero");
 		$this->db->from("comprobante_pago");	
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."'");
 		$this->db->group_by("fecha_emision");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlFacturas($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, c.fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total - TRUNCATE((importe_total/1.18) ,2)) ,2) as igv, c.serie, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c2.numero_documento_cli, c2.razon_social_cli, c2.nombre_comercial_cli, c2.direccion_legal, c.tipo_moneda");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1","p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2","c.id_cliente_empresa=c2.idclienteempresa" , "left");	
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function updateEstadoCobroEmitido($fecha_emision, $correlativo, $serie){
 		$this->db->set("idestadocobro", 3);
		$this->db->where("fecha_emision='".$fecha_emision."' and nume_corre_res=".$correlativo." and serie='".$serie."' and idestadocobro=2");
		$this->db->update("comprobante_pago");
 	}

 	function updateEstadoCobroEmitidoManual($fecha_emision, $correlativo, $serie){
 		$this->db->set("idestadocobro", 3);
		$this->db->where("fecha_emision='".$fecha_emision."' and correlativo=".$correlativo." and serie='".$serie."' and idestadocobro=2");
		$this->db->update("comprobante_pago");
 	}

 	function updateEstadoCobroAnulacion($fecha_emision, $correlativo, $serie){
 		$this->db->set("idestadocobro", 4);
		$this->db->where("fecha_emision='".$fecha_emision."' and nume_corre_res=".$correlativo." and serie='".$serie."' and idestadocobro=3");
		$this->db->update("comprobante_pago");
 	}


 	function getDatosFacturaEmitidaFinal($inicio, $fin, $serie, $idPlan){
 		$this->db->distinct("c1.idcomprobante");
 		$this->db->select("c1.idcomprobante, c1.id_cliente_empresa, c1.fecha_emision, c1.serie, CONCAT(REPEAT( '0', 8 - LENGTH( c1.correlativo) ) , c1.correlativo) as correlativo, c1.importe_total, c1.idplan, c2.razon_social_cli, c2.numero_documento_cli, p.nombre_plan, c1.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c1.fecha_emision,6,2),SUBSTRING(c1.fecha_emision,1,4)) as mesanio");
 		$this->db->from("comprobante_pago c1");
 		$this->db->join("cliente_empresa c2" , "c1.id_cliente_empresa=c2.idclienteempresa");
 		$this->db->join("plan p" , "c1.idplan=p.idplan");
 		$this->db->join("cobro c3" , "p.idplan=c3.plan_id");
 		$this->db->join("estado_emision e" , "c1.idestadocobro=e.idestadocobro");
 		$this->db->where("c1.fecha_emision>='".$inicio."' and c1.fecha_emision<='".$fin."' and c1.serie='".$serie."' and plan_id=".$idPlan." and c1.idestadocobro=3");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	//--------------------------------------------------------------------------------------------------------------------------

 	function getDatosBoletaNota($serie, $correlativo, $fecha_emision){
 		$this->db->select("CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, importe_total, tipo_moneda, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, c1.cont_numDoc, id_contratante, idplan");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c1","c.id_contratante=c1.cont_id");
		$this->db->where("serie='".$serie."' and correlativo=".$correlativo." and fecha_emision='".$fecha_emision."' and idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosFacturaNota($serie, $correlativo, $fecha_emision){
 		$this->db->select("CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, importe_total, tipo_moneda, c1.nombre_comercial_cli, c1.numero_documento_cli, id_cliente_empresa, idplan");
		$this->db->from("comprobante_pago c");
		$this->db->join("cliente_empresa c1","c.id_cliente_empresa=c1.idclienteempresa");
		$this->db->where("serie='".$serie."' and correlativo=".$correlativo." and fecha_emision='".$fecha_emision."' and idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function insertDatosBoletasNotas($fechaEmi, $serie, $correlativo, $idCliente, $tipoDocumento, $importeTotal, $idPlan, $serieDoc, $correlativoDoc, $sustento, $fechaDoc, $correRes){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_contratante " , $idCliente);
 		$this->db->set("id_tipo_documento_mov", $tipoDocumento);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idPlan);
 		$this->db->set("tipo_moneda" , "PEN");
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->set("serie_doc", $serieDoc);
 		$this->db->set("correlativo_doc", $correlativoDoc);
 		$this->db->set("sustento_nota", $sustento);
 		$this->db->set("idestadocobro" , 2);
 		$this->db->set("fecha_doc", $fechaDoc);
 		$this->db->set("nume_corre_res", $correRes);
 		$this->db->insert("comprobante_pago");
 	}

 	function insertDatosBoletasNotasManuales($fechaEmi, $serie, $correlativo, $tipoDocumento, $importeTotal, $idPlan, $serieDoc, $correlativoDoc, $sustento, $fechaDoc, $nombreManual, $numDocManual, $impManual){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		//$this->db->set("id_contratante " , $idCliente);
 		$this->db->set("id_tipo_documento_mov", $tipoDocumento);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idPlan);
 		$this->db->set("tipo_moneda" , "PEN");
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->set("serie_doc", $serieDoc);
 		$this->db->set("correlativo_doc", $correlativoDoc);
 		$this->db->set("sustento_nota", $sustento);
 		$this->db->set("idestadocobro" , 2);
 		$this->db->set("fecha_doc", $fechaDoc);
 		$this->db->set("nombre_manual", $nombreManual);
 		$this->db->set("num_doc_manual", $numDocManual);
 		$this->db->set("importe_manual", $impManual);
 		$this->db->set("nume_corre_res", 2);
 		$this->db->insert("comprobante_pago");
 	}

 	function insertDatosFacturasNotas($fechaEmi, $serie, $correlativo, $idCliente, $tipoDocumento, $importeTotal, $idPlan, $serieDoc, $correlativoDoc, $sustento, $fechaDoc){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_cliente_empresa " , $idCliente);
 		$this->db->set("id_tipo_documento_mov" , $tipoDocumento);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idPlan);
 		$this->db->set("tipo_moneda" , "PEN");
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->set("serie_doc" , $serieDoc);
		$this->db->set("correlativo_doc" , $correlativoDoc);
		$this->db->set("sustento_nota" , $sustento);
 		$this->db->set("idestadocobro" , 2);
 		$this->db->set("fecha_doc", $fechaDoc);
 		$this->db->insert("comprobante_pago");
 		$this->db->group_by("fecha_emision");
 	}

 	function getDatosNotasFacturas($serie, $inicio){
 		$this->db->select("fecha_emision, CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, importe_total, tipo_moneda, c1.nombre_comercial_cli, c1.numero_documento_cli, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo_doc)),correlativo)) as seriecorrelativoDoc, nombre_plan, c.idplan, idestadocobro, sustento_nota, idcomprobante");
		$this->db->from("comprobante_pago c");
		$this->db->join("cliente_empresa c1","c.id_cliente_empresa=c1.idclienteempresa" , "left");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
		$this->db->where("serie='".$serie."' and fecha_emision='".$inicio."'");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosNotasBoletas($serie, $inicio, $fin){
 		$this->db->select("fecha_emision, CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, importe_total, tipo_moneda, case when CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) is null then UPPER(c.nombre_manual) else CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) end as contratante, case when c1.cont_numDoc is null then c.num_doc_manual else CONCAT(c1.cont_numDoc,'') end as cont_numDoc, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo_doc)),correlativo)) as seriecorrelativoDoc, nombre_plan, c.idplan, idestadocobro, sustento_nota, idcomprobante");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c1","c.id_contratante=c1.cont_id" , "left");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
		$this->db->where("serie='".$serie."' and fecha_emision>='".$inicio."' and fecha_emision<='".$fin."'");

		$data = $this->db->get();
		return $data->result();
 	}

 	function updateEstadocobroComprobante($idcomprobante){
 		$this->db->set("idestadocobro", 2);
		$this->db->where("idestadocobro=3 and idcomprobante=".$idcomprobante);
		$this->db->update("comprobante_pago"); 
 	}

 	function insertDatosComprobanteManualBoleta($fechaEmi, $serie, $correlativo, $tipoDoc, $importeTotal, $idplan, $moneda, $sustento, $correRes){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_tipo_documento_mov", $tipoDoc);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idplan);
 		$this->db->set("tipo_moneda" , $moneda);
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->set("sustento_nota" , $sustento);
 		$this->db->set("idestadocobro" , 2);
 		$this->db->set("nume_corre_res" , $correRes);
 		$this->db->insert("comprobante_pago");
 	}

  	function insertDatosComprobanteManualFactura($fechaEmi, $serie, $correlativo, $tipoDoc, $clienteempresa, $importeTotal, $idplan, $moneda, $sustento){
 		$this->db->set("fecha_emision" , $fechaEmi);
 		$this->db->set("serie" , $serie);
 		$this->db->set("correlativo" , $correlativo);
 		$this->db->set("id_tipo_documento_mov", $tipoDoc);
 		$this->db->set("id_cliente_empresa" , $clienteempresa);
 		$this->db->set("importe_total" , $importeTotal);
 		$this->db->set("idplan" , $idplan);
 		$this->db->set("tipo_moneda" , $moneda);
 		$this->db->set("unidad_medida" , "Unidad");
 		$this->db->set("cantidad" , 1);
 		$this->db->set("num_orden" , 1);
 		$this->db->set("sustento_nota" , $sustento);
 		$this->db->set("idestadocobro" , 2);
 		$this->db->insert("comprobante_pago");
 	}

 	function getDatosXmlNotasBoleta($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, c.nume_corre_res, fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total/1.18)*0.18 ,2) as igv, c.serie, p.nombre_plan, case when c2.cont_numDoc is null then c.num_doc_manual else CONCAT(c2.cont_numDoc) end as cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then UPPER(c.nombre_manual) else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo_doc) ) , correlativo_doc) as correlativo_doc, c.sustento_nota, c.fecha_doc, c.serie_doc, c.idestadocobro");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1" , "p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."' and idestadocobro=2");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlNotasBoletaAnulacion($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, c.nume_corre_res, fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total/1.18)*0.18 ,2) as igv, c.serie, p.nombre_plan, case when c2.cont_numDoc is null then c.num_doc_manual else CONCAT(c2.cont_numDoc) end as cont_numDoc, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then UPPER(c.nombre_manual) else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo_doc) ) , correlativo_doc) as correlativo_doc, c.sustento_nota, c.fecha_doc, c.serie_doc, c.idestadocobro");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1" , "p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."' and serie = '".$serie."' and c.idestadocobro=3");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function getDatosXmlNotasFactura($inicio, $serie){
 		$this->db->select("CONCAT(REPEAT( '0', 2 - LENGTH( MONTH(CURDATE())) ) , MONTH(CURDATE())) as mes, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo) ) , correlativo) as correlativo, c.correlativo as corre, c.nume_corre_res, fecha_emision, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, TRUNCATE(importe_total,2) as total, TRUNCATE((importe_total/1.18) ,2) as neto, TRUNCATE((importe_total/1.18)*0.18 ,2) as igv, c.serie, p.nombre_plan, case when c2.numero_documento_cli is null then c.num_doc_manual else CONCAT(c2.numero_documento_cli) end as numero_documento_cli, case when c2.razon_social_cli is null then c.nombre_manual else CONCAT(c2.razon_social_cli) end as razon_social_cli, CONCAT(REPEAT( '0', 8 - LENGTH( correlativo_doc) ) , correlativo_doc) as correlativo_doc, c.sustento_nota, c.fecha_doc, c.serie_doc, c.idestadocobro");
 		$this->db->from("comprobante_pago c");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
 		//$this->db->join("centro_costo c1" , "p.idcentrocosto=c1.idcentrocosto" , "left");	
 		$this->db->join("cliente_empresa c2" , "c.id_cliente_empresa=c2.idclienteempresa" , "left");		
 		$this->db->where("fecha_emision='".$inicio."' and serie = '".$serie."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	 function getMesanio($inicio, $fin){
 		$this->db->select("DISTINCT CONCAT(SUBSTRING(fecha_emision,6,2),SUBSTRING(fecha_emision,1,4)) as mesanios");
 		$this->db->from("comprobante_pago");		
 		$this->db->where("fecha_emision>='".$inicio."' and fecha_emision<='".$fin."'");
 		
		$data = $this->db->get();
		return $data->result();
 	}

 	function mostrarBoletaCert($certNum){
 		$this->db->select("CONCAT(c.serie,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo)),c.correlativo)) as seriecorrelativo, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo)) as seriecorrelativoDoc, c.idcomprobante, c.fecha_emision, c.serie, c.id_contratante, CONCAT(REPEAT( '0', 8 - LENGTH( c.correlativo) ) , c.correlativo) as correlativo, c.importe_total, c.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, c.sustento_nota");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
		$this->db->join("cobro c3" , "c.idcobro=c3.cob_id" , "left");
		$this->db->join("estado_emision e" , "c.idestadocobro=e.idestadocobro" , "left");
		$this->db->where("c3.cert_num=".$certNum." and c.idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function mostrarBoletaDni($dni){
 		$this->db->select("CONCAT(c.serie,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo)),c.correlativo)) as seriecorrelativo, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo)) as seriecorrelativoDoc, c.idcomprobante, c.fecha_emision, c.serie, c.id_contratante, CONCAT(REPEAT( '0', 8 - LENGTH( c.correlativo) ) , c.correlativo) as correlativo, c.importe_total, c.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, c.sustento_nota");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
		$this->db->join("cobro c3" , "c.idcobro=c3.cob_id" , "left");
		$this->db->join("estado_emision e" , "c.idestadocobro=e.idestadocobro" , "left");
		$this->db->where("c2.cont_numDoc=".$dni." and c.id_tipo_documento_mov=3 and c.idestadocobro=3");

		$data = $this->db->get();
		return $data->result();
 	}

 	function mostrarBoletaRuc($dni){
 		$this->db->select("CONCAT(c.serie,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo)),c.correlativo)) as seriecorrelativo, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo)) as seriecorrelativoDoc, c.idcomprobante, c.fecha_emision, c.serie, c.id_contratante, CONCAT(REPEAT( '0', 8 - LENGTH( c.correlativo) ) , c.correlativo) as correlativo, c.importe_total, c.idplan, nombre_comercial_cli as contratante, c2.numero_documento_cli as cont_numDoc, p.nombre_plan as nombre_plan, c.idestadocobro, e.descripcion, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, c.sustento_nota");
		$this->db->from("comprobante_pago c");
		$this->db->join("cliente_empresa c2" , "c.id_cliente_empresa=c2.idclienteempresa" , "left");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
		$this->db->join("cobro c3" , "c.idcobro=c3.cob_id" , "left");
		$this->db->join("estado_emision e" , "c.idestadocobro=e.idestadocobro" , "left");
		$this->db->where("c2.numero_documento_cli=".$dni." and c.id_tipo_documento_mov=2");

		$data = $this->db->get();
		return $data->result();
 	}

 	function mostrarBoletaIdComprobante($idcomprobante){
 		$this->db->select("CONCAT(c.serie,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo)),c.correlativo)) as seriecorrelativo, CONCAT(c.serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( c.correlativo_doc)),c.correlativo)) as seriecorrelativoDoc, c.idcomprobante, c.fecha_emision, c.serie, c.id_contratante, CONCAT(REPEAT( '0', 8 - LENGTH( c.correlativo) ) , c.correlativo) as correlativo, c.importe_total, c.idplan, case when CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) is null then 'CLIENTES VARIOS' else CONCAT(c2.cont_ape1,' ',c2.cont_ape2,' ',c2.cont_nom1,' ',c2.cont_nom2) end as contratante, case when c2.cont_numDoc is null then '0000' else c2.cont_numDoc end as cont_numDoc, case when p.nombre_plan is null then 'OTROS INGRESOS' else p.nombre_plan end as nombre_plan, c.idestadocobro, CONCAT(SUBSTRING(c.fecha_emision,6,2),SUBSTRING(c.fecha_emision,1,4)) as mesanio, c.sustento_nota, CONCAT(c.serie,'-',CONCAT(REPEAT( '0', 8 - LENGTH( c.correlativo) ) , c.correlativo),' con fecha ', c.fecha_emision) as seriefecha, c3.cob_vezCob, c3.cob_iniCobertura, c3.cob_finCobertura, c3.cert_id");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c2" , "c.id_contratante=c2.cont_id" , "left");
		$this->db->join("plan p" , "c.idplan=p.idplan" , "left");
		$this->db->join("cobro c3" , "c.idcobro=c3.cob_id" , "left");
		$this->db->where("c.idcomprobante=".$idcomprobante);

		$data = $this->db->get();
		return $data->result();
 	}

	function iniVig_cert($iniVig, $cert_id){
		$this->db->set("cert_finVig", $iniVig);
		$this->db->where("cert_id=".$cert_id);
		$this->db->update("certificado"); 
	}

	function iniVig_cert_aseg($iniVig, $cert_id){
		$this->db->set("cert_finVig", $iniVig);
		$this->db->where("cert_id=".$cert_id);
		$this->db->update("certificado_asegurado");
	}

 	//-----------------------------------------------------------------------------------------------------------------------------------
 	//nota de crédito masiva 

 	function mostrarCertCuotas($inicio, $fin){
 		$this->db->select("dev_id, cert_num, cuotas, cont_num, fecha_dev, importe");
 		$this->db->from("devoluciones");
		$this->db->where("fecha_dev>='".$inicio."' and fecha_dev<='".$fin."' and cert_num is not null");

 		$data = $this->db->get();
 		return $data->result();
 	}

 	function mostrarDev($dev_id, $fecha){
 		$this->db->select("cert_num, cuotas, cont_num, fecha_dev, importe, fecha_inicio, fecha_fin");
 		$this->db->from("devoluciones");
		$this->db->where("dev_id =".$dev_id." and fecha_dev='".$fecha."'");

 		$data = $this->db->get();
 		return $data->result();
 	}

	function mostrarCobroDevolucionCert($cert, $canal, $limit, $inicio, $fin, $fechaIn, $fechaFi){
 		$this->db->select("d.importe, d.fecha_dev, d.cert_num, d.cont_num, d.dev_id, c.cob_fechCob, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, p.nombre_plan, p.idplan, c.cob_id, p.idclienteempresa, c1.cont_id, c.idestadocobro, c3.serie, c3.correlativo, c3.fecha_emision");
		$this->db->from("devoluciones d");
		$this->db->join("cobro c" , "d.cert_num = c.cert_num");
		$this->db->join("contratante c1" , "d.cont_num=c1.cont_numDoc");
		$this->db->join("plan p" , "c.plan_id=p.idplan");
		$this->db->join("cliente_empresa c2" , "p.idclienteempresa=c2.idclienteempresa");
		$this->db->join("comprobante_pago c3" , "c1.cont_id=c3.id_contratante");
		$this->db->where("d.cert_num=".$cert." and p.idclienteempresa=".$canal." and estado_dev = 1 and d.estado = 1 and d.fecha_dev BETWEEN '".$inicio."' and '".$fin."' and c.cob_finCobertura BETWEEN '".$fechaIn."' and  '".$fechaFi."'");
		//$this->db->order_by("cob_fechCob DESC");
		$this->db->limit($limit);

 		$data = $this->db->get();
 		return $data->result();		
 	}

 	 function mostrarCobroDevolucionCertdev($cert, $canal, $limit, $inicio, $fin, $fechaIn, $fechaFi){
 		$this->db->select("c.cob_id, c.cert_num, c.cob_fechCob, cob_importe, c3.cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, c3.cont_id, c4.nombre_plan, c4.idplan, c5.idestadocobro, c5.descripcion, cob_id, d.fecha_dev, c7.numero_serie, d.dev_id");
		$this->db->from("devoluciones d");
		$this->db->join("cobro c" , "d.cert_num = c.cert_num");
		$this->db->join("certificado c2" , "c.cert_id=c2.cert_id");
		$this->db->join("contratante c3" , "c2.cont_id=c3.cont_id");
		$this->db->join("plan c4" , "c.plan_id=c4.idplan");
		$this->db->join("estado_emision c5" , "c.idestadocobro=c5.idestadocobro");
		$this->db->join("cliente_empresa c6" , "c4.idclienteempresa=c6.idclienteempresa");
		$this->db->join("serie c7" , "c6.id_serie=c7.idserie");
		$this->db->where("d.cert_num=".$cert." and c4.idclienteempresa=".$canal." and estado_dev = 1 and d.estado = 1 and d.fecha_dev BETWEEN '".$inicio."' and '".$fin."' and c.cob_finCobertura BETWEEN '".$fechaIn."' and  '".$fechaFi."'");
		$this->db->order_by("cob_fechCob DESC");
		$this->db->limit($limit);

 		$data = $this->db->get();
 		return $data->result();		
 	}

 	function mostrarCobroDevolucionDni($dni, $canal, $importe, $limit, $inicio, $fin, $fecha){
 		//$this->db->distinct("c.cob_fechCob");
 		$this->db->select("c.cert_num, c.cob_fechCob, cob_importe, c3.cont_numDoc, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, c4.nombre_plan, d.fecha_dev, c7.numero_serie");
		$this->db->from("cobro c");
		$this->db->join("certificado c2" , "c.cert_id=c2.cert_id");
		$this->db->join("contratante c3" , "c2.cont_id=c3.cont_id");
		$this->db->join("devoluciones d" , "c3.cont_numDoc = d.cont_num");
		$this->db->join("plan c4" , "c.plan_id=c4.idplan");
		$this->db->join("cliente_empresa c6" , "c4.idclienteempresa=c6.idclienteempresa");
		$this->db->join("serie c7" , "c6.id_serie=c7.idserie");
		$this->db->where("d.cont_num='".$dni."' and c4.idclienteempresa=".$canal." and cob_importe=".$importe." and estado_dev = 1 and d.estado = 1 and d.fecha_dev>='".$inicio."' and d.fecha_dev<='".$fin."' and cob_fechCob <= '".$fecha."'");
		$this->db->order_by("cob_fechCob DESC");
		$this->db->limit($limit);

 		$data = $this->db->get();
 		return $data->result();		
 	}

 	function mostrarCobroDevolucion($canal, $limit, $inicio, $fin, $fecha){
 		$this->db->select("cob_importe, c.cob_fechCob, d.fecha_dev, c.cert_num, c3.cont_numDoc, c4.nombre_plan, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, c7.numero_serie");
		$this->db->from("cobro c");
		$this->db->join("devoluciones d" , "c.cert_num = d.cert_num" , "right");
		$this->db->join("contratante c3" , "d.cont_num=c3.cont_numDoc" , "right");
		$this->db->join("plan c4" , "c.plan_id=c4.idplan" , "right");
		$this->db->join("cliente_empresa c6" , "c4.idclienteempresa=c6.idclienteempresa" , "right");
		$this->db->join("serie c7" , "c6.id_serie=c7.idserie" , "right");
		$this->db->where("c4.idclienteempresa=".$canal." and estado_dev = 1 and d.estado = 1 and d.fecha_dev>='".$inicio."' and d.fecha_dev<='".$fin."' and cob_fechCob <= '".$fecha."'");
		$this->db->order_by("cob_fechCob DESC");
		$this->db->limit($limit);

 		$data = $this->db->get();
 		return $data->result();		
 	}

 	function updateEstadoDevCobro($cob_id){
 		$this->db->set("estado_dev", 2);
		$this->db->where("cob_id=".$cob_id);
		$this->db->update("cobro"); 
 	}

 	function updateEstadoDevCero($cuotas, $dev_id){
 		$this->db->set("cuotas", $cuotas);
 		$this->db->set("estado", 2);
		$this->db->where("dev_id=".$dev_id);
		$this->db->update("devoluciones"); 
 	}

 	function updateEstadoDev($cuotas, $dev_id){
 		$this->db->set("cuotas", $cuotas);
 		$this->db->set("estado", 3);
		$this->db->where("dev_id=".$dev_id);
		$this->db->update("devoluciones"); 
 	}

 	function updateEstadoDevId($dev_id){
 		$this->db->set("estado", 4);
		$this->db->where("dev_id=".$dev_id);
		$this->db->update("devoluciones"); 
 	}

 	function updateEstadoDevUno($inicio, $fin){
 		$this->db->set("estado", 3);
		$this->db->where("fecha_dev BETWEEN '".$inicio."' and '".$fin."' and estado = 1");
		$this->db->update("devoluciones"); 
 	}

 	function getDatosNotasBoletasMasivas($serie, $inicio){
 		$this->db->select("fecha_emision, CONCAT(serie,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo)),correlativo)) as seriecorrelativo, importe_total, tipo_moneda, case when CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) is null then UPPER(c.nombre_manual) else CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) end as contratante, case when c1.cont_numDoc is null then c.num_doc_manual else CONCAT(c1.cont_numDoc,'') end as cont_numDoc, CONCAT(serie_doc,'-',CONCAT(REPEAT('0', 8-LENGTH( correlativo_doc)),correlativo_doc)) as seriecorrelativoDoc, nombre_plan, c.idplan, idestadocobro, sustento_nota, idcomprobante");
		$this->db->from("comprobante_pago c");
		$this->db->join("contratante c1","c.id_contratante=c1.cont_id" , "left");
		$this->db->join("plan p","c.idplan=p.idplan" , "left");
		$this->db->where("serie='".$serie."' and fecha_emision='".$inicio."'");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDevolucionesSinCobro($canal, $inicio, $fin){
 		$this->db->select("d.cert_num, d.cuotas, d.estado, d.cont_num, d.fecha_dev, d.importe, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, p.nombre_plan");
		$this->db->from("devoluciones d");
		$this->db->join("contratante c" , "d.cont_num=c.cont_numDoc" , "left");
		$this->db->join("certificado c1" , "d.cert_num=c1.cert_num" , "left");
		$this->db->join("plan p" , "c1.plan_id=p.idplan" , "left");
		$this->db->join("cliente_empresa c3" , "p.idclienteempresa=c3.idclienteempresa" , "left");
		$this->db->where("estado = 3 and p.idclienteempresa=".$canal." and fecha_dev>='".$inicio."' and fecha_dev<='".$fin."'");

		$data = $this->db->get();
		return $data->result();
 	}

 	function getDevolucionesSinCobroDos($inicio, $fin){
 		$this->db->select("d.cert_num, d.cuotas, d.estado, d.cont_num, d.fecha_dev, d.importe, CONCAT(cont_ape1,' ',cont_ape2,' ',cont_nom1,' ',cont_nom2) as contratante, p.nombre_plan");
		$this->db->from("devoluciones d");
		$this->db->join("contratante c" , "d.cont_num=c.cont_numDoc" , "left");
		$this->db->join("certificado c1" , "d.cert_num=c1.cert_num" , "left");
		$this->db->join("plan p" , "c1.plan_id=p.idplan" , "left");
		$this->db->join("cliente_empresa c3" , "p.idclienteempresa=c3.idclienteempresa" , "left");
		$this->db->where("estado = 4 and fecha_dev>='".$inicio."' and fecha_dev<='".$fin."'");

		$data = $this->db->get();
		return $data->result();
 	}
}
?>