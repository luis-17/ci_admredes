<?php
 class Plan_mdl extends CI_Model {

 function Plan_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getPlanes(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('plan pl');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=pl.idclienteempresa');
 		$this->db->join('plan_usuario pu','pu.idplan=pl.idplan','left');
 		$this->db->order_by("nombre_comercial_cli, nombre_plan");

	 $planes = $this->db->get();
	 return $planes->result();
 	}
 	function getPlanes2(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->select("(select GROUP_CONCAT(concat(case when tipo_responsable='P' then 'Principal: ' else 'Apoyo: ' end, nombres_col)) from plan p
						inner join plan_usuario pu on p.idplan=pu.idplan
						inner join usuario u on u.idusuario=pu.idusuario
						inner join colaborador c on c.idusuario=u.idusuario where p.idplan=pl.idplan) as responsable");
 		$this->db->from('plan pl');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=pl.idclienteempresa');
 		$this->db->order_by("nombre_comercial_cli, nombre_plan");

	 $planes = $this->db->get();
	 return $planes->result();
 	}

 	function getCobertura($id){
 		$this->db->select("dp.idplandetalle, dp.idplan,dp.idvariableplan,dp.valor_detalle,dp.simbolo_detalle, dp.texto_web, dp.visible, dp.estado_pd, dp.flg_liquidacion, dp.tiempo, dp.num_eventos, vp.nombre_var, case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num11, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo11, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num22, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo22, cobertura");
 		$this->db->from('plan_detalle dp'); 		
 		$this->db->join('variable_plan vp','dp.idvariableplan=vp.idvariableplan');
 		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
 			where pc.estado=1 group by idplandetalle)a",'a.idplandetalle=dp.idplandetalle','left');
 		$this->db->where('dp.idplan',$id); 		
 		$this->db->order_by("dp.idplandetalle ");

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

 	function getClientes(){
 		$this->db->select('*');
 		$this->db->from('cliente_empresa');
 		$this->db->order_by("nombre_comercial_cli");

 	$clientes = $this->db->get();
 	return $clientes->result();
 	}

 	function getPlan($id){
 		$this->db->select('*');
 		$this->db->from('plan pl');
 		$this->db->join('cliente_empresa ce','pl.idclienteempresa=ce.idclienteempresa');
 		$this->db->where('idplan',$id);

	 $plan = $this->db->get();
	 return $plan->result();
 	}

 	function update_plan($data){
 		$array = array(
			'nombre_plan' => $data['nombre_plan'],
			'codigo_plan' => $data['codigo_plan'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			'prima_monto' => $data['prima'],
			'prima_adicional' => $data['prima_adicional'],
			'num_afiliados' => $data['num_afiliados'],
			'flg_activar' => $data['flg_activar'],
			'flg_dependientes' => $data['flg_dependientes'],
			'flg_cancelar' => $data['flg_cancelar']
		);
		$this->db->where('idplan',$data['idplan']);
		return $this->db->update('plan', $array);
 	}

 	function insert_plan($data){
 		$array = array(
			'nombre_plan' => $data['nombre_plan'],
			'codigo_plan' => $data['codigo_plan'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			'prima_monto' => $data['prima'],
			'prima_adicional' => $data['prima_adicional'],
			'num_afiliados' => $data['num_afiliados'],
			'flg_activar' => $data['flg_activar'],
			'flg_dependientes' => $data['flg_dependientes'],
			'flg_cancelar' => $data['flg_cancelar'],
			'idred' => 1
 				 );
		$this->db->insert('plan',$array);
 	}

 	function insert_cobertura($data){
 		$array = array(
				 'idplan' => $data['id'],
				 'idvariableplan' => $data['item'],
				 'texto_web' => $data['descripcion'],
				 'visible' => $data['visible'],
				 'iniVig' => $data['iniVig'],
				 'finVig' => $data['finVig']			 
 				 );
		$this->db->insert('plan_detalle',$array);
 	}

 	function update_cobertura($data){
 		$array = array(
				 'idplan' => $data['id'],
				 'idvariableplan' => $data['item'],
				 'texto_web' => $data['descripcion'],
				 'visible' => $data['visible'],
				 'iniVig' => $data['iniVig'],
				 'finVig' => $data['finVig']	
 				 );
 		$this->db->where('idplandetalle',$data['iddet']);
		return $this->db->update('plan_detalle', $array);
 	}

 	function plan_anular($id){
 		$array = array(
			'estado_plan' => 0
		);
		$this->db->where('idplan',$id);
		return $this->db->update('plan', $array);
 	}

 	function plan_activar($id){
 		$array = array(
			'estado_plan' => 1
		);
		$this->db->where('idplan',$id);
		return $this->db->update('plan', $array);
 	}

 	function getSiniestroDetalle($id){
 		$query = $this->db->query("select * from siniestro_detalle where idplandetalle=$id");
 		return $query->result();
 	}

 	function cobertura_anular($id){
 		$array = array(
			'estado_pd' => 0
		);
		$this->db->where('idplandetalle',$id);
		return $this->db->update('plan_detalle', $array);
 	}

 	function cobertura_activar($id){
 		$array = array(
			'estado_pd' => 1
		);
		$this->db->where('idplandetalle',$id);
		return $this->db->update('plan_detalle', $array);
 	}

 	function selecionar_cobertura($id){
 		$this->db->select("pd.*, case when iniVig =0 then 0 else SUBSTRING_INDEX(iniVig,' ',1)end as num1, case when iniVig=0 then '' else SUBSTRING_INDEX(iniVig,' ',-1)end as tiempo1, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num2, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo2");
 		$this->db->from("plan_detalle pd");
 		$this->db->where("idplandetalle",$id);
 	$cobertura = $this->db->get();
	return $cobertura->result();
 	}

 	function selecionar_cobertura2($id){
 		$this->db->select("pd.*,vp.*,pl.*,o.*, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num1, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo1, case when finVig =0 then 0 else SUBSTRING_INDEX(finVig,' ',1)end as num2, case when finVig=0 then '' else SUBSTRING_INDEX(finVig,' ',-1)end as tiempo2");
 		$this->db->from("plan_detalle pd");
 		$this->db->join("variable_plan vp","vp.idvariableplan=pd.idvariableplan");
 		$this->db->join("plan pl","pd.idplan=pl.idplan");
 		$this->db->join("operador o","pd.simbolo_detalle=o.idoperador","left");
 		$this->db->where("idplandetalle",$id);
 	$cobertura = $this->db->get();
	return $cobertura->result();
 	}

 	function save_mail($data){
 		$array = array
 		(
 			'cuerpo_mail' => $data['cuerpo_mail'] 
 		);
 		$this->db->where('idplan',$data['idplan']);
 		$this->db->update('plan',$array);
 	}

 	function get_operador(){
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

 	function insert_proddet($data){
 		$array = array(
 			'idplandetalle' => $data['iddet'],
 			'idproducto' => $data['idprod'] 
 			);

 	$this->db->insert('producto_detalle',$array);
 	}

 	function get_productos2($iddet){
 		$this->db->select("pr.idproducto,descripcion_prod, case when pr.idproducto=pd.idproducto then 'checked' else '' end as checked, case when pr.idproducto=pd.idproducto then 'eliminar_producto' else 'insertar_producto' end as funcion");
 		$this->db->from("producto pr");
 		$this->db->join("producto_detalle pd","pr.idproducto=pd.idproducto and pd.idplandetalle=".$iddet,"left");
 		$this->db->where("idvariableplan=(select idvariableplan from plan_detalle where idplandetalle=".$iddet.")");
 		$this->db->order_by("descripcion_prod","asc");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function eliminar_producto($idprod,$iddet){
 		$this->db->where('idproducto', $idprod);
 		$this->db->where('idplandetalle',$iddet);
		$this->db->delete('producto_detalle');
 	}

 	function getEventos($id){
 		$query = $this->db->query("select * from plan_detalle pd inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan where idplandetalle=$id");
 		return $query->row_array();
 	}

 	function upEventos($data){
 		$array = array
 		(
 			'num_eventos' => $data['num_eventos'], 
 			'tiempo' => $data['tiempo']
 		);
 		$this->db->where("idplandetalle",$data['id']);
 		$this->db->update("plan_detalle",$array);
 	}

 	function getCoberturasActivas($id){
 		$query = $this->db->query("select idplandetalle, nombre_var 
 									from plan_detalle pd 
 									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan 
 									where idplan=(select idplan from plan_detalle where idplandetalle=$id) and estado_pd=1");
 		return $query->result();
 	}

 	function getBloqueos($id){
 		$query = $this->db->query("select idbloqueo, DATE_FORMAT(fecha_hora,'%d/%m/%y %h:%i')as fecha, nombre_var, username 
 									from plan_detalle_bloqueo pb
									inner join plan_detalle pd on pb.idplandetalle_bloqueado=pd.idplandetalle
									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan
									inner join usuario u on u.idusuario=pb.idusuario
									where idplandetalle_bloquea=$id");
 		return $query->result();
 	}

 	function reg_bloqueo($data){
 		$array = array(
 			'idplandetalle_bloquea' => $data['id'], 
 			'idplandetalle_bloqueado' => $data['cob_bloqueada'],
 			'idusuario' => $data['idusuario'],
 			'fecha_hora' => $data['hoy']
 		);
 		$this->db->insert("plan_detalle_bloqueo",$array);
 	}

 	function getOperador(){
 		$query = $this->db->query("select idoperador, descripcion from operador");
 		return $query->result();
 	}

 	function getCoaseguros($id){
 		$query = $this->db->query("select pc.idcoaseguro,pc.idoperador, descripcion, valor from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador where idplandetalle=$id and estado=1");
 		return $query->result();
 	}

 	function inCoaseguro($data){
 		$array = array
 		(
 			'idplandetalle' => $data['id'], 
 			'idoperador' => $data['idoperador'],
 			'valor' => $data['valor'],
 			'fecha_crea' => $data['hoy'],
 			'usuario_crea' => $data['idusuario']
 		);
 		$this->db->insert("plan_coaseguro",$array);
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

 	function delete_bloqueo($idbloqueo){
 		$this->db->where("idbloqueo",$idbloqueo);
 		$this->db->delete("plan_detalle_bloqueo");
 	}

 	function up_cc($data){
 		$array = array(
 			'centro_costo' => $data['cc'] );
 		$this->db->where('idplan',$data['idplan']);
 		$this->db->update('plan',$array);
 	}

 	function inPlanUsuario($data){
 		$array = array('idusuario' => $data['idusuario'], 'tipo_responsable' => $data['tiporesponsable'], 'idplan' => $data['idplan']);
 		$this->db->insert('plan_usuario',$array);
 	}

 	function getPersonalResponsable($id){
 		$query = $this->db->query("select u.idusuario, concat(nombres_col,' ',ap_paterno_col) as colaborador from usuario u inner join colaborador c on u.idusuario=c.idusuario and u.idtipousuario=3 and u.idusuario not in (select idusuario from plan_usuario where idplan=$id) and u.estado_us=1");
 		return $query->result();
 	}

 	function getPersonalAsignado($id){
 		$query = $this->db->query("select pu.idplanusuario, pu.idusuario, concat(nombres_col,' ',ap_paterno_col) as colaborador, tipo_responsable from plan_usuario pu inner join colaborador c on pu.idusuario=c.idusuario where pu.idplan=$id");
 		return $query->result();
 	}

 	function eliminar_responsable($id){
 		$this->db->where('idplanusuario',$id);
 		$this->db->delete('plan_usuario');
 	}

 	function getProveedorChecked($id){
 		$this->db->select("p.idproveedor, p.nombre_comercial_pr, case when d.idproveedor= p.idproveedor then 'checked' else '' end as checked, d.idplanproveedor");
 		$this->db->from("proveedor p");
 		$this->db->join("(SELECT idplan, idproveedor, idplanproveedor FROM plan_proveedor WHERE idplan=".$id." and estado=1) d","p.idproveedor = d.idproveedor","left");
 		$this->db->order_by("idproveedor");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getPlanProveedor($id){
 		$this->db->select('*');
 		$this->db->from('plan_proveedor');
 		$this->db->where('idplan='.$id);

	 $querys = $this->db->get();
	 return $querys->result();
 	}

 	function getPlanProveedorChecked($id){
 		$this->db->select('pr.idproveedor, pr.razon_social_pr, pr.nombre_comercial_pr, pr.direccion_pr');
 		$this->db->from('plan_proveedor pp');
 		$this->db->join('proveedor pr','pp.idproveedor = pr.idproveedor');
 		$this->db->where('idplan='.$id.' and estado=1');

	 $querys = $this->db->get();
	 return $querys->result();
 	}

 	function select_planprov($idproveedor, $idplan){
 		$this->db->select("idplanproveedor");
 		$this->db->from("plan_proveedor");
 		$this->db->where("idproveedor",$idproveedor);
 		$this->db->where("idplan",$idplan);
 		$query = $this->db->get();
 		return $query->result();
 	}

 	function activar_planprov($idproveedor, $idplan){
 		$this->db->set('estado', 1 );
 		$this->db->where("idproveedor",$idproveedor);
 		$this->db->where("idplan",$idplan);
 		$this->db->update("plan_proveedor");
 	}

 	function insertar_planprov($idproveedor, $idplan){
		$this->db->set("idproveedor",$idproveedor);
		$this->db->set("idplan",$idplan);
		$this->db->set('estado', 1);
		$this->db->insert('plan_proveedor');
 	}

 	function cambiar_planprov($idproveedor, $idplan){
 		$this->db->set('estado', 0 );
 		$this->db->where("idproveedor",$idproveedor);
 		$this->db->where("idplan",$idplan);
 		$this->db->update("plan_proveedor");
 	}

 	function cambiar_planprov_todos(){
 		$this->db->set('estado', 0 );
 		$this->db->update("plan_proveedor");
 	}

 	function eliminar_planprov($idproveedor, $idplan){		
 		$this->db->where("idproveedor",$idproveedor);
 		$this->db->where("idplan",$idplan);
 		$this->db->where("estado",1);
		$this->db->delete("plan_proveedor");
 	}

}
?>