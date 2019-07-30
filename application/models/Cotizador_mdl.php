<?php
 class Cotizador_mdl extends CI_Model {

 function Cotizador_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCotizaciones(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('cotizacion c');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=c.idclienteempresa');
 		$this->db->join('cotizacion_usuario cu','cu.idcotizacion=c.idcotizacion','left');
 		$this->db->order_by("nombre_comercial_cli, nombre_cotizacion");

	 $planes = $this->db->get();
	 return $planes->result();
 	}

 	function getCotizaciones2(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->select("(select GROUP_CONCAT(concat(case when tipo_responsable='P' then 'Principal: ' else 'Apoyo: ' end, nombres_col)) from cotizacion c
						inner join cotizacion_usuario cu on c.idcotizacion=cu.idcotizacion
						inner join usuario u on u.idusuario=cu.idusuario
						inner join colaborador co on co.idusuario=u.idusuario where c.idcotizacion=ct.idcotizacion) as responsable");
 		$this->db->from('cotizacion ct');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=ct.idclienteempresa');
 		$this->db->order_by("ct.nombre_cotizacion, ct.nombre_cotizacion");

	 $planes = $this->db->get();
	 return $planes->result();
 	}

 	function getCotizaciones3(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->select("(select GROUP_CONCAT(concat(case when tipo_responsable='P' then 'Principal: ' else 'Apoyo: ' end, nombres_col)) from cotizacion c
						inner join cotizacion_usuario cu on c.idcotizacion=cu.idcotizacion
						inner join usuario u on u.idusuario=cu.idusuario
						inner join colaborador co on co.idusuario=u.idusuario where c.idcotizacion=ct.idcotizacion) as responsable");
 		$this->db->from('cotizacion ct');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=ct.idclienteempresa');
 		$this->db->order_by("idcotizacion DESC");
 		$this->db->limit(1);

	 $planes = $this->db->get();
	 return $planes->result();
 	}

 	function getClientes(){
 		$this->db->select('*');
 		$this->db->from('cliente_empresa');
 		$this->db->order_by("nombre_comercial_cli");

 	$clientes = $this->db->get();
 	return $clientes->result();
 	}

 	function insert_cotizacion($data){
 		$array = array(
			'nombre_cotizacion' => $data['nombre_cotizacion'],
			'codigo_cotizacion' => $data['codigo_cotizacion'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			//'prima_monto' => $data['prima'],
			//'prima_adicional' => $data['prima_adicional'],
			'num_afiliados' => $data['num_afiliados'],
			'flg_activar' => $data['flg_activar'],
			'flg_dependientes' => $data['flg_dependientes'],
			'flg_cancelar' => $data['flg_cancelar'],
			'idred' => 1,
			'estado_cotizacion' => 1
 				 );
		$this->db->insert('cotizacion',$array);
 	}

 	function getCotDetalle($idcotizacion){
 		$this->db->select('*');
 		$this->db->from('cotizacion c');
 		$this->db->join('cotizacion_detalle cd','c.idcotizacion = cd.idcotizacion');
 		$this->db->where('c.idcotizacion',$idcotizacion);

 	$items = $this->db->get();
 	return $items->result();
 	}

 	function insert_preDetalle($idcotizacion, $numero_cot){
		$this->db->set('idcotizacion', $idcotizacion);
		$this->db->set('numero_cot', $numero_cot);
		$this->db->set('estado', 0);
		$this->db->insert('cotizacion_detalle');
 	}

 	function inCotiUsuario($data){
 		$array = array('idusuario' => $data['idusuario'], 'tipo_responsable' => $data['tiporesponsable'], 'idcotizacion' => $data['idcotizacion']);
 		$this->db->insert('cotizacion_usuario',$array);
 	}

  	function update_cotizacion($data){
 		$array = array(
			'nombre_cotizacion' => $data['nombre_cotizacion'],
			'codigo_cotizacion' => $data['codigo_cotizacion'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			//'prima_monto' => $data['prima'],
			//'prima_adicional' => $data['prima_adicional'],
			'num_afiliados' => $data['num_afiliados'],
			'flg_activar' => $data['flg_activar'],
			'flg_dependientes' => $data['flg_dependientes'],
			'flg_cancelar' => $data['flg_cancelar']
		);
		$this->db->where('idcotizacion',$data['idcotizacion']);
		return $this->db->update('cotizacion', $array);
 	}

 	function getItems(){
 		$this->db->select('*');
 		$this->db->from('variable_plan');
 		$this->db->order_by("nombre_var");

 	$items = $this->db->get();
 	return $items->result();
 	}

 	function getCobertura($id){
 		$this->db->select("dp.idcotizacioncobertura, dp.idcotizaciondetalle, dp.idvariableplan, dp.valor_detalle, dp.simbolo_detalle, dp.texto_web, dp.visible, dp.estado_pd, dp.flg_liquidacion, dp.tiempo, dp.num_eventos, vp.nombre_var, case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num11, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo11, case when finVig=0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num22, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo22, cobertura");
 		$this->db->from('cotizacion_cobertura dp');
 		$this->db->join('variable_plan vp','dp.idvariableplan=vp.idvariableplan');
 		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idcotizacioncobertura from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador WHERE pc.estado=1 group by idcotizacioncobertura)a",'a.idcotizacioncobertura=dp.idcotizacioncobertura','left');
 		$this->db->where('dp.idcotizaciondetalle',$id); 		
 		$this->db->order_by("dp.idcotizacioncobertura ");

 	$cobertura = $this->db->get();
 	return $cobertura->result();
 	}

 	function getCoberturaVariables($id){
 		$this->db->select("dp.idcotizacioncobertura, dp.idcotizaciondetalle, dp.idvariableplan, dp.valor_detalle, dp.simbolo_detalle, dp.texto_web, dp.visible, dp.estado_pd, dp.flg_liquidacion, dp.tiempo, dp.num_eventos, vp.nombre_var, cv.neventos, cv.neventosadicional, cv.costo, cv.idcalcular, cobertura");
 		$this->db->from('cotizacion_cobertura dp');
 		$this->db->join('variable_plan vp','dp.idvariableplan=vp.idvariableplan');
 		$this->db->join('cotizacion_detalle cd','dp.idcotizaciondetalle = cd.idcotizaciondetalle');
 		$this->db->join('cotizacion_variables cv','dp.idcotizacioncobertura = cv.idcotizacioncobertura','left');
 		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idcotizacioncobertura from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador WHERE pc.estado=1 group by idcotizacioncobertura)a",'a.idcotizacioncobertura=dp.idcotizacioncobertura','left');
 		$this->db->where('dp.idcotizaciondetalle',$id); 		
 		$this->db->order_by("dp.idcotizacioncobertura");

 	$cobertura = $this->db->get();
 	return $cobertura->result();
 	}

 	function getDetalleCobertura($id){
 		$this->db->select("*");
 		$this->db->from("cotizacion_detalle");
 		$this->db->where("idcotizacion",$id);

 		$cobertura = $this->db->get();
 		return $cobertura->result();
 	}

 	function insertCobertura($idcotizaciondetalle, $idvariableplan, $texto_web){
		$this->db->set('idcotizaciondetalle', $idcotizaciondetalle);
		$this->db->set('idvariableplan', $idvariableplan);
		$this->db->set('texto_web', $texto_web);
		$this->db->insert('cotizacion_cobertura');
 	}

  	function insertVariables($neventos, $neventosadicional, $costo, $idcotizacioncobertura){
		$this->db->set('neventos', $neventos);
		$this->db->set('neventosadicional', $neventosadicional);
		$this->db->set('costo', $costo);
		$this->db->set('idcotizacioncobertura', $idcotizacioncobertura);
		$this->db->insert('cotizacion_variables');
 	}

	function updateVariables($neventos, $neventosadicional, $costo, $idcotizacioncobertura, $idcalcular){
		$this->db->set("neventos", $neventos);
		$this->db->set("neventosadicional", $neventosadicional);
		$this->db->set("costo", $costo);
		$this->db->set("idcotizacioncobertura", $idcotizacioncobertura);
		$this->db->where("idcalcular=".$idcalcular);
		$this->db->update("cotizacion_variables"); 
	}

	function inserteDetalles($pobloacion_persona, $siniestralidad_mensual, $idcotizaciondetalle){
		$this->db->set("poblacion_persona", $pobloacion_persona);
		$this->db->set("siniestralidad_mensual", $siniestralidad_mensual);
		$this->db->where("idcotizaciondetalle=".$idcotizaciondetalle);		
		$this->db->insert("cotizacion_detalle"); 
	}

	function updateDetalles($pobloacion_persona, $siniestralidad_mensual, $idcotizaciondetalle){
		$this->db->set("poblacion_persona", $pobloacion_persona);
		$this->db->set("siniestralidad_mensual", $siniestralidad_mensual);
		$this->db->where("idcotizaciondetalle=".$idcotizaciondetalle);
		$this->db->update("cotizacion_detalle"); 
	}

 	function getEventos($id){
 		$query = $this->db->query("select * from cotizacion_cobertura cc inner join variable_plan vp on cc.idvariableplan=vp.idvariableplan where cc.idcotizacioncobertura=$id");
 		return $query->row_array();
 	}

 	function getOperador(){
 		$query = $this->db->query("select idoperador, descripcion from operador");
 		return $query->result();
 	}

 	function getCoaseguros($id){
 		$query = $this->db->query("select pc.idcoaseguro,pc.idoperador, descripcion, valor from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador where idcotizacioncobertura=$id and estado=1");
 		return $query->result();
 	}

 	function inCoaseguro($data){
 		$array = array
 		(
 			'idcotizacioncobertura' => $data['id'], 
 			'idoperador' => $data['idoperador'],
 			'valor' => $data['valor'],
 			'fecha_crea' => $data['hoy'],
 			'usuario_crea' => $data['idusuario']
 		);
 		$this->db->insert("cotizacion_coaseguro",$array);
 	}

  	function upCoaseguro($data){
 		$array = array
 		(
 			'usuario_anula' => $data['idusuario'],
 			'fecha_anula' => $data['hoy'],
 			'estado' => 0 
 		);
 		$this->db->where("idcoaseguro",$data['idcoaseguro']);
 		$this->db->update("plan_coaseguro",$array);
 	}

}
?>