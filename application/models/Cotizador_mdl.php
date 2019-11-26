<?php
 class Cotizador_mdl extends CI_Model {

 function Cotizador_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCotizaciones(){
 		$planes = $this->db->query("select distinct c.idcotizacion,  nombre_comercial_cli, cd.nombre_cotizacion, 
				(select cd.idcotizaciondetalle from cotizacion_detalle cd where cd.idcotizacion=c.idcotizacion order by cd.idcotizaciondetalle desc limit 1) as idcotizaciondetalle,
				(select cd2.estado from cotizacion_detalle cd2 where cd2.idcotizacion=c.idcotizacion order by cd2.idcotizaciondetalle desc limit 1) as estado, cu.idusuario
				from cotizacion c 
				inner join cotizacion_detalle cd on c.idcotizacion=cd.idcotizacion
				inner join cliente_empresa ce on ce.idclienteempresa=c.idclienteempresa
				inner join cotizacion_usuario cu on cu.idcotizaciondetalle=(select cd.idcotizaciondetalle from cotizacion_detalle cd where cd.idcotizacion=c.idcotizacion order by cd.idcotizaciondetalle desc limit 1)");
	 	return $planes->result();
 	}
 	
 	function getCotizaciones2(){
 		$planes = $this->db->query("select distinct c.idcotizacion,  nombre_comercial_cli, c.nombre_cotizacion, 
				(select cd.idcotizaciondetalle from cotizacion_detalle cd where cd.idcotizacion=c.idcotizacion order by cd.idcotizaciondetalle desc limit 1) as idcotizaciondetalle,
				(select cd2.estado from cotizacion_detalle cd2 where cd2.idcotizacion=c.idcotizacion order by cd2.idcotizaciondetalle desc limit 1) as estado
				from cotizacion c 
				inner join cliente_empresa ce on ce.idclienteempresa=c.idclienteempresa");
		return $planes->result();
 	}

 	function getCotizaciones3(){
 		$planes = $this->db->query("select c.idcotizacion,  nombre_comercial_cli, cd.nombre_cotizacion, cd.estado, u.username, prima_titular, prima_adicional,
				(select cd.idcotizaciondetalle from cotizacion_detalle cd where cd.idcotizacion=c.idcotizacion order by cd.idcotizaciondetalle desc limit 1) as idcotizaciondetalle
				from cotizacion c 
				inner join cotizacion_detalle cd on c.idcotizacion=cd.idcotizacion
				inner join cliente_empresa ce on ce.idclienteempresa=c.idclienteempresa
				inner join cotizacion_usuario cu on cu.idcotizaciondetalle=cd.idcotizaciondetalle
				inner join usuario u on u.idusuario=cu.idusuario
				where cd.estado=2");
		return $planes->result();
 	}

 	function getClientes(){
 		$this->db->select('*');
 		$this->db->from('cliente_empresa');
 		$this->db->order_by("nombre_comercial_cli");

 	$clientes = $this->db->get();
 	return $clientes->result();
 	}


 	function getCobertura($id)
 	{
 		$this->db->select("dp.idcotizacioncobertura, dp.texto_web, dp.idcotizaciondetalle,dp.idvariableplan,dp.texto_web, dp.visible, dp.estado_pd, dp.tiempo, dp.num_eventos, vp.nombre_var, case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num11, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo11, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num22, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo22, cobertura");
 		$this->db->from('cotizacion_cobertura dp'); 		
 		$this->db->join('variable_plan vp','dp.idvariableplan=vp.idvariableplan');
 		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idcotizacioncobertura from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
 			where pc.estado=1 group by idcotizacioncobertura)a",'a.idcotizacioncobertura=dp.idcotizacioncobertura','left');
 		$this->db->where('dp.idcotizaciondetalle',$id); 		
 		$this->db->order_by("dp.idcotizacioncobertura ");

 	$cobertura = $this->db->get();
 	return $cobertura->result();
 	}

 	function getItems(){
 		$this->db->select('*');
 		$this->db->from('variable_plan');
 		$this->db->order_by("nombre_var");

 	$items = $this->db->get();
 	return $items->result();
 	}

 	function get_operador()
 	{
 		$this->db->select('*');
 		$this->db->from('operador');

 		$query=$this->db->get();
 		return $query->result();
 	}

 	function get_productos($item){
 		$this->db->select("idproducto,descripcion_prod");
 		$this->db->from("producto p");
 		$this->db->join("variable_plan vp","p.idvariableplan=vp.idvariableplan");
 		$this->db->where("vp.idvariableplan",$item);
 		$this->db->where("estado_prod","1");
 		$this->db->order_by("descripcion_prod","asc");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function get_precio($id){
 		$query = $this->db->query("select coalesce(precio_sugerido,0)as precio_sugerido from variable_plan where idvariableplan=$id");
 		return $query->row_array();
 	}

 	function in_Cotizacion($data){
 		$array = array(
 			'idclienteempresa' => $data['cliente'], 
 			'nombre_cotizacion' => $data['nombre_plan'],
 			'estado_cotizacion' => 1
 		);
 		$this->db->insert("cotizacion",$array);
 	}

 	function in_CotDetalle($data){
 		$array = array(
 			'idcotizacion' => $data['idcotizacion'],
 			'estado' => 1,
 			'nombre_cotizacion' => $data['nombre_plan'],
 			'dias_carencia' => $data['carencia'],
 			'dias_mora' => $data['mora'],
 			'dias_atencion' => $data['atencion'],
 			'num_afiliados' => $data['num_afiliados'],
 			'tipo_plan' => $data['tipo_plan'],
 			'tipo_cotizacion' => $data['tipo_cotizacion']
 		);
 		$this->db->insert("cotizacion_detalle",$array);
 	}

 	function inCotUsuario($data){
 		$array = array(
 			'idcotizaciondetalle' => $data['idCotDetalle'], 
 			'idusuario' => $data['idusuario'],
 			'tipo_responsable' => $data['tiporesponsable']
 		);
 		$this->db->insert("cotizacion_usuario",$array);
 	}

 	function getEstado($id){
 		$query = $this->db->query("select estado from cotizacion_detalle where idcotizaciondetalle=$id");
 		return $query->row_array();
 	}

 	function insert_cobertura($data){
 		$array = array(
				 'idcotizaciondetalle' => $data['id'],
				 'idvariableplan' => $data['item'],
				 'texto_web' => $data['descripcion'],
				 'visible' => $data['visible'],
				 'iniVig' => $data['iniVig'],
				 'finVig' => $data['finVig'],
				 'precio' => $data['precio'],
				 'eventos_titular' => $data['e_titular'],
				 'eventos_adicional' => $data['e_adicional']			 
 				 );
		$this->db->insert('cotizacion_cobertura',$array);
 	}

 	function insert_proddet($data){
 		$array = array(
 			'idcotizaciondetalle' => $data['iddet'],
 			'idproducto' => $data['idprod'] 
 			);

 	$this->db->insert('cotizacion_producto',$array);
 	}

 	function getVariables($id){
 		$query = $this->db->query("select * from cotizacion_detalle where idcotizaciondetalle=$id");
 		return $query->row_array();
 	}

 	function up_variables($data){
 		$array = array(
 			'poblacion' => $data['poblacion'], 
 			'siniestralidad_mensual' => $data['siniestralidad'],
 			'gastos_administrativos' => $data['g_administrativos'],
 			'gastos_marketing' => $data['marketing'],
 			'reserva' => $data['reserva'],
 			'inflacion_medica' => $data['inflacion'],
 			'remanente' => $data['remanente'],
 			'costos_administrativos' => $data['c_administrativos']
 		);
 		$this->db->where('idcotizaciondetalle',$data['idcotizaciondetalle']);
 		$this->db->update('cotizacion_detalle',$array);
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

 	function getCoberturaDetalle($id){
 		$query = $this->db->query("select * from cotizacion_detalle where idcotizaciondetalle=$id");
 		return $query->result();
 	}

 	function getCoberturasCalculo($id){
 		$query = $this->db->query("select cc.*, nombre_var, texto_web, coalesce(pc1.valor,0) as valor1, coalesce(pc2.valor,0) as valor2, coalesce(pc3.valor,0) as valor3, coalesce(pc1.idoperador,0) as cobertura, coalesce(pc2.idoperador,0) as copago, coalesce(pc3.idoperador,0) as hasta
									from cotizacion_detalle cd
									inner join cotizacion_cobertura cc on cd.idcotizaciondetalle=cc.idcotizaciondetalle
									inner join variable_plan vp on cc.idvariableplan=vp.idvariableplan
									LEFT JOIN cotizacion_coaseguro pc1 on pc1.idcotizacioncobertura=cc.idcotizacioncobertura and pc1.idoperador=1 and pc1.estado=1
									LEFT JOIN cotizacion_coaseguro pc2 on pc2.idcotizacioncobertura=cc.idcotizacioncobertura and pc2.idoperador=2 and pc2.estado=1
									LEFT JOIN cotizacion_coaseguro pc3 on pc3.idcotizacioncobertura=cc.idcotizacioncobertura and pc3.idoperador=3 and pc3.estado=1
									where cc.idcotizaciondetalle=$id and precio>0 and precio is not null");
 		return $query->result();
 	}

 	function upPrimas($data){
 		$array = array(
 			'prima_titular' => $data['titular'],
 			'prima_adicional' => $data['adicional']
 		);
 		$this->db->where("idcotizaciondetalle",$data['idcotizaciondetalle']);
 		$this->db->update("cotizacion_detalle",$array);
 	}

 	function getEventos($id){
 		$query = $this->db->query("select * from cotizacion_detalle cd 
									inner join cotizacion_cobertura pd on cd.idcotizaciondetalle=pd.idcotizaciondetalle 
									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan 
									where idcotizacioncobertura=$id");
 		return $query->row_array();
 	}

 	function upEventos($data){
 		$array = array
 		(
 			'num_eventos' => $data['num_eventos'], 
 			'tiempo' => $data['tiempo']
 		);
 		$this->db->where("idcotizacioncobertura",$data['id']);
 		$this->db->update("cotizacion_cobertura",$array);
 	}

 	function getCoberturas2($id)
 	{
 		$query = $this->db->query("select dp.idcotizacioncobertura, dp.texto_web, dp.idcotizaciondetalle,dp.idvariableplan,dp.texto_web, dp.visible, dp.estado_pd, dp.tiempo, dp.num_eventos, vp.nombre_var, 
				case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num11, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo11, 
				case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num22, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo22, cobertura
				from cotizacion_cobertura dp
				join variable_plan vp on dp.idvariableplan=vp.idvariableplan
				inner join (select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idcotizacioncobertura from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
				 			where pc.estado=1 group by idcotizacioncobertura)a on a.idcotizacioncobertura=dp.idcotizacioncobertura
				where dp.idcotizaciondetalle=$id	-- and coalesce(precio,0)>0
				order by dp.idcotizacioncobertura");
 		return $query->result();
 	}

 	function getCoberturas3($id)
 	{
 		$query = $this->db->query("select dp.idcotizacioncobertura, dp.texto_web, dp.idcotizaciondetalle,dp.idvariableplan,dp.visible, dp.estado_pd, dp.tiempo, dp.num_eventos, vp.nombre_var
				from cotizacion_cobertura dp
				join variable_plan vp on dp.idvariableplan=vp.idvariableplan
				where dp.idcotizaciondetalle=$id	and dp.idcotizacioncobertura not in (select idcotizacioncobertura from cotizacion_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
				 			where pc.estado=1 group by idcotizacioncobertura)
				order by dp.idcotizacioncobertura");
 		return $query->result();
 	}

 	function upEstadoCot($data)
 	{
 		$array = array('estado' => $data['estado']);
 		$this->db->where('idcotizaciondetalle',$data['id']);
 		$this->db->update('cotizacion_detalle',$array);
 	}

 	function upEstadoCot2($data)
 	{
 		$array = array('estado' => $data['estado'], 'observacion' => $data['motivo']);
 		$this->db->where('idcotizaciondetalle',$data['id']);
 		$this->db->update('cotizacion_detalle',$array);
 	}

 	function upEstadoCot3($data)
 	{
 		$array = array('estado' => $data['estado'], 'observacion_cliente' => $data['motivo']);
 		$this->db->where('idcotizaciondetalle',$data['id']);
 		$this->db->update('cotizacion_detalle',$array);
 	}

 	function getCotizacionDetalle($id){
 		$query = $this->db->query("select * from cotizacion_detalle cd inner join cotizacion c on c.idcotizacion=cd.idcotizacion where idcotizaciondetalle=$id");
 		return $query->result();
 	}

 	function upCotizacion($data){
 		$array = array(
 			'nombre_cotizacion' => $data['nombre_plan'] 
 		);
 		$this->db->where('idcotizacion',$data['idcotizacion']);
 		$this->db->update('cotizacion', $array);
 	}

 	function upCotDetalle($data){
 		$array = array(
 			'nombre_cotizacion' => $data['nombre_plan'],
 			'dias_carencia' => $data['carencia'],
 			'dias_mora' => $data['mora'],
 			'dias_atencion' => $data['atencion'],
 			'num_afiliados' => $data['num_afiliados'],
 			'tipo_plan' => $data['tipo_plan'],
 			'tipo_cotizacion' => $data['tipo_cotizacion']
 		);
 		$this->db->where("idcotizaciondetalle",$data['idCotDetalle']);
 		$this->db->update("cotizacion_detalle",$array);
 	}

 	function getCotizacion($id){
 		$query = $this->db->query("select nombre_comercial_cli, razon_social_cli, descripcion_car, cd.nombre_cotizacion, nombres_col, ap_paterno_col, ap_materno_col, tipo_plan, tipo_cotizacion, dias_carencia, dias_mora, dias_atencion, num_afiliados, DATE_FORMAT(cd.createdat,'%d/%m/%Y') as creacion, observacion, observacion_cliente from cotizacion_detalle cd
				inner join cotizacion c on c.idcotizacion=cd.idcotizacion
				inner join cliente_empresa ce on c.idclienteempresa=ce.idclienteempresa
				inner join cotizacion_usuario cu on cu.idcotizaciondetalle=cd.idcotizaciondetalle and tipo_responsable='P'
				inner join usuario u on u.idusuario=cu.idusuario
				inner join colaborador co on co.idusuario=u.idusuario
				left join cargo ca on co.idcargo=ca.idcargo
				where cd.idcotizaciondetalle=$id");
 		return $query->row_array();
 	}

 	function in_CotDetalle2($data)
 	{
 		$array = array(
 			'idcotizacion' => $data['idcotizacion'],
 			'estado' => $data['estado'],
 			'nombre_cotizacion' => $data['nombre_cotizacion'],
 			'dias_carencia' => $data['dias_carencia'],
 			'dias_mora' => $data['dias_mora'],
 			'dias_atencion' => $data['dias_atencion'],
 			'num_afiliados' => $data['num_afiliados'],
 			'tipo_plan' => $data['tipo_plan'],
 			'poblacion' => $data['poblacion'],
 			'siniestralidad_mensual' => $data['siniestralidad_mensual'],
 			'gastos_administrativos' => $data['gastos_administrativos'],
 			'gastos_marketing' => $data['gastos_marketing'],
 			'reserva' => $data['reserva'],
 			'inflacion_medica' => $data['inflacion_medica'],
 			'remanente' => $data['remanente'],
 			'costos_administrativos' => $data['costos_administrativos'],
 			'prima_titular' => $data['prima_titular'],
 			'prima_adicional' => $data['prima_adicional'],
 			'tipo_cotizacion' => $data['tipo_cotizacion']
 		);
 		$this->db->insert('cotizacion_detalle',$array);
 	}

 	function getCotCoberturas($id)
 	{
 		$query = $this->db->query("select * from cotizacion_cobertura where idcotizaciondetalle=$id");
 		return $query->result();
 	}

 	function getCotCoaseguro($id)
 	{
 		$query = $this->db->query("select * from cotizacion_coaseguro where idcotizacioncobertura=$id");
 		return $query->result();
 	}

 	function cotProducto($id)
 	{
 		$query = $this->db->query("select * from cotizacion_producto where idcotizaciondetalle=$id");
 		return $query->result();
 	}

 	function in_cotCobeturas($data)
 	{
 		$array = array(
 			'idcotizaciondetalle' => $data['idcotizaciondetalle'],
 			'idvariableplan' => $data['idvariableplan'],
 			'texto_web' => $data['texto_web'],
 			'visible' => $data['visible'],
 			'tiempo' => $data['tiempo'],
 			'num_eventos' => $data['num_eventos'],
 			'iniVig' => $data['iniVig'],
 			'finVig' => $data['finVig'],
 			'precio' => $data['precio'],
 			'eventos_titular' => $data['eventos_titular'],
 			'eventos_adicional' => $data['eventos_adicional']
 		);
 		$this->db->insert('cotizacion_cobertura',$array);
 	}

 	function inCoaseguro2($data)
 	{
 		$array = array(
 			'idcotizacioncobertura' => $data['idcotizacioncobertura2'],
 			'idoperador' => $data['idoperador'],
 			'valor' => $data['valor'],
 			'usuario_crea' => $data['usuario_crea']
 		);
 		$this->db->insert('cotizacion_coaseguro',$array);
 	}

 	function inCotProducto($data)
 	{
 		$array = array(
 			'idproducto' => $data['idproducto'],
 			'idcotizaciondetalle' => $data['idcotizacioncobertura2'] 
 		);
 		$this->db->insert('cotizacion_producto',$array);
 	}

 	function getCoberturasActivas($id){
 		$query = $this->db->query("select idcotizacioncobertura, concat(nombre_var,' (',texto_web,')') as nombre_var 
 									from cotizacion_cobertura pd 
 									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan 
 									where idcotizaciondetalle=(select idcotizaciondetalle from cotizacion_cobertura where idcotizacioncobertura=$id) and idcotizacioncobertura<>$id");
 		return $query->result();
 	}

 	function getBloqueos($id){
 		$query = $this->db->query("select idbloqueo, DATE_FORMAT(fecha_hora,'%d/%m/%y %h:%i')as fecha, nombre_var, username 
 									from cotizacion_bloqueo pb
									inner join cotizacion_cobertura pd on pb.idcobertura_bloqueado=pd.idcotizacioncobertura
									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan
									inner join usuario u on u.idusuario=pb.idusuario
									where idcobertura_bloquea=$id");
 		return $query->result();
 	}

 	function reg_bloqueo($data){
 		$array = array(
 			'idcobertura_bloquea' => $data['id'], 
 			'idcobertura_bloqueado' => $data['cob_bloqueada'],
 			'idusuario' => $data['idusuario'],
 			'fecha_hora' => $data['hoy']
 		);
 		$this->db->insert("cotizacion_bloqueo",$array);
 	}

 	function delete_bloqueo($idbloqueo){
 		$this->db->where("idbloqueo",$idbloqueo);
 		$this->db->delete("cotizacion_bloqueo");
 	}

 	function selecionar_cobertura($id){
 		$this->db->select("pd.*, case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num1, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo1, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num2, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo2");
 		$this->db->from("cotizacion_cobertura pd");
 		$this->db->where("idcotizacioncobertura",$id);
 	$cobertura = $this->db->get();
	return $cobertura->result();
 	}

 	function get_productos2($iddet){
 		$this->db->select("pr.idproducto,descripcion_prod, case when pr.idproducto=pd.idproducto then 'checked' else '' end as checked, case when pr.idproducto=pd.idproducto then 'eliminar_producto_cot' else 'insertar_producto_cot' end as funcion");
 		$this->db->from("producto pr");
 		$this->db->join("cotizacion_producto pd","pr.idproducto=pd.idproducto and pd.idcotizaciondetalle=".$iddet,"left");
 		$this->db->where("idvariableplan=(select idvariableplan from cotizacion_cobertura where idcotizacioncobertura=".$iddet.")");
 		$this->db->order_by("descripcion_prod","asc");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function eliminar_producto($idprod,$iddet){
 		$this->db->where('idproducto', $idprod);
 		$this->db->where('idcotizaciondetalle',$iddet);
		$this->db->delete('cotizacion_producto');
 	}

}
?>