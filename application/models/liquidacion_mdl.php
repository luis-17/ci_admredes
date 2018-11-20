<?php
 class Liquidacion_mdl extends CI_Model {

 function Liquidacion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getLiquidaciones(){
		$this->db->select("LD.liqdetalleid, S.num_orden_atencion, S.fecha_atencion, PR.nombre_comercial_pr, LD.liqdetalle_numfact,  LD.liqdetalle_aprovpago, liqdetalle_neto, nombre_var, CONCAT('(', left(GROUP_CONCAT(descripcion_prod),40), case when CHAR_LENGTH(GROUP_CONCAT(descripcion_prod))>40 then '...)'else ')' end) as concepto, aseg_numDoc");
		$this->db->from("liquidacion_detalle LD");
		$this->db->join("liquidacion L","L.liquidacionId = LD.liquidacionId");
		$this->db->join("plan_detalle pd","pd.idplandetalle=LD.idplandetalle");
		$this->db->join("variable_plan vp","vp.idvariableplan=pd.idvariableplan");
		$this->db->join("producto_detalle  prd","prd.idplandetalle=pd.idplandetalle","left");
		$this->db->join("producto pr","prd.idproducto=pr.idproducto","left");
		$this->db->join("siniestro S","S.idsiniestro = L.idsiniestro");		
		$this->db->join("proveedor PR","PR.idproveedor = LD.idproveedor");
		$this->db->join("asegurado A","A.aseg_id = S.idasegurado");	
		$this->db->where("liqdetalle_aprovpago",1);
		$this->db->group_by("LD.liqdetalleid");
		//$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O'");
		$this->db->order_by("S.fecha_atencion", "asc");

	$atenciones = $this->db->get();
	 return $atenciones->result();
	}

	function get_liquidaciongrupo(){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida, fecha_genera, fecha_liquida");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("usuario u","lg.usuario_genera=u.idusuario");
		$this->db->join("usuario u2","lg.usuario_liquida=u2.idusuario","left");
		$this->db->group_by("lgd.liqgrupo_id");

		$query=$this->db->get();
		return $query->result();
	}

	function getLiquidacionDet($id){
		$this->db->select("lgd.liqdetallegrupo_id, lgd.liqgrupo_id, lgd.liqdetalleid, nombre_comercial_pr, liqdetalle_numfact, liqdetalle_monto, liqdetalle_neto, nombre_var, CONCAT('(', left(GROUP_CONCAT(descripcion_prod),40), case when CHAR_LENGTH(GROUP_CONCAT(descripcion_prod))>40 then '...)'else ')' end) as detalle, num_orden_atencion, ");
		$this->db->from("liquidacion_grupodetalle lgd");
		$this->db->join("liquidacion_detalle ld","ld.liqdetalleid=lgd.liqdetalleid");
		$this->db->join("liquidacion l","l.liquidacionId=ld.liquidacionId");
		$this->db->join("siniestro s","s.idsiniestro=l.idsiniestro");
		$this->db->join("plan_detalle pd","ld.idplandetalle=pd.idplandetalle");
		$this->db->join("variable_plan vp","pd.idvariableplan=vp.idvariableplan");
		$this->db->join(" proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("producto_detalle prd","prd.idplandetalle=pd.idplandetalle","left");
		$this->db->join("producto pr","pr.idproducto=prd.idproducto","left");
		$this->db->where("liqgrupo_id",$id);
		$this->db->group_by("lgd.liqdetalleid");

		$query = $this->db->get();
		return $query->result();
	}

	function save_liquidacion($data){
		$array = array
		(
			'fecha_genera' => date('Y-m-d H:i:s') , 
			'usuario_genera' => $data['idusuario'],
			'liqgrupo_estado' => 0
		);

		$this->db->insert('liquidacion_grupo',$array);
	}
	
	function save_liqdetalle_grupo($data){
		$array = array
		(
			'liqgrupo_id' => $data['liq_id'],
			'liqdetalleid' => $data['liq_det']
		);

		$this->db->insert('liquidacion_grupodetalle', $array);
	}

	function up_liqdetalle($data){
		$array = array
		(
			'liqdetalle_aprovpago' => 2
		);
		$this->db->where("liqdetalleid",$data['liq_det']);
		$this->db->update("liquidacion_detalle",$array);
	}

	function get_liquidaciongrupo_p(){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida, fecha_genera, fecha_liquida");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("usuario u","lg.usuario_genera=u.idusuario");
		$this->db->join("usuario u2","lg.usuario_liquida=u2.idusuario","left");
		$this->db->where("liqgrupo_estado",0);
		$this->db->group_by("lgd.liqgrupo_id");

		$query=$this->db->get();
		return $query->result();
	}


	function get_liquidaciongrupo_c(){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida, fecha_genera, fecha_liquida");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("usuario u","lg.usuario_genera=u.idusuario");
		$this->db->join("usuario u2","lg.usuario_liquida=u2.idusuario","left");
		$this->db->where("liqgrupo_estado",1);
		$this->db->group_by("lgd.liqgrupo_id");

		$query=$this->db->get();
		return $query->result();
	}

	function getBancos(){
		$query = $this->db->get('banco');
		return $query->result();
	}
	
	function getFormaPago(){
		$query = $this->db->get('forma_pago');
		return $query->result();
	}

	function save_Pago($data){
		$array = array
		(
			'forma_pago' => $data['forma'], 
			'medio_pago' => $data['idbanco'],
			'num_operacion' => $data['nro_operacion'],
			'fecha_liquida' => $data['fecha'],
			'usuario_liquida' => $data['idusuario'],
			'liqgrupo_estado' => 1,
			'email_notifica' => $data['correo']
		);

		$this->db->where('liqgrupo_id', $data['liqgrupoid']);
		$this->db->update('liquidacion_grupo',$array);
	}
}
?>