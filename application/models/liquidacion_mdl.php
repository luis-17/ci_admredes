<?php
 class Liquidacion_mdl extends CI_Model {

 function Liquidacion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getLiquidaciones($id){
		$this->db->select("LD.liqdetalleid, S.num_orden_atencion, S.fecha_atencion, PR.nombre_comercial_pr, LD.liqdetalle_numfact,  LD.liqdetalle_aprovpago, liqdetalle_neto, coalesce(nombre_var,'Gastos Aprobados') as nombre_var, CONCAT('(', left(GROUP_CONCAT(descripcion_prod),40), case when CHAR_LENGTH(GROUP_CONCAT(descripcion_prod))>40 then '...)'else ')' end) as concepto, aseg_numDoc");
		$this->db->from("liquidacion_detalle LD");
		$this->db->join("liquidacion L","L.liquidacionId = LD.liquidacionId");
		$this->db->join("plan_detalle pd","pd.idplandetalle=LD.idplandetalle","left");
		$this->db->join("variable_plan vp","vp.idvariableplan=pd.idvariableplan","left");
		$this->db->join("producto_detalle  prd","prd.idplandetalle=pd.idplandetalle","left");
		$this->db->join("producto pr","prd.idproducto=pr.idproducto","left");
		$this->db->join("siniestro S","S.idsiniestro = L.idsiniestro");		
		$this->db->join("proveedor PR","PR.idproveedor = LD.idproveedor");
		$this->db->join("asegurado A","A.aseg_id = S.idasegurado");	
		$this->db->where("liqdetalle_aprovpago",1);
		$this->db->where("LD.idproveedor",$id);
		$this->db->group_by("LD.liqdetalleid");
		//$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O'");
		$this->db->order_by("S.fecha_atencion", "asc");

	$atenciones = $this->db->get();
	 return $atenciones->result();
	}

	function get_liquidaciongrupo(){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida, DATE_FORMAT(fecha_genera,'%d/%m/%Y') as fecha_genera, DATE_FORMAT(fecha_liquida,'%d/%m/%Y') as fecha_liquida, detraccion, pd.idpago, coalesce(pa.usuario_paga,0) as usuario_paga");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lg.liqgrupo_id","left");
		$this->db->join("pago pa","pa.idpago=pd.idpago","left");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("usuario u","lg.usuario_genera=u.idusuario");
		$this->db->join("usuario u2","lg.usuario_liquida=u2.idusuario","left");
		$this->db->group_by("lgd.liqgrupo_id");

		$query=$this->db->get();
		return $query->result();
	}

	function getLiquidacionDet($id){
		$this->db->select("lgd.liqdetallegrupo_id, lgd.liqgrupo_id, lgd.liqdetalleid, nombre_comercial_pr, liqdetalle_numfact, liqdetalle_monto, liqdetalle_neto,coalesce(nombre_var,'Gastos Aprobados') as nombre_var, num_orden_atencion, concat(a.aseg_ape1,' ',a.aseg_ape2,' ',a.aseg_nom1,' ',coalesce(a.aseg_nom2))as afiliado, CONCAT('(', left(GROUP_CONCAT(descripcion_prod),40), case when CHAR_LENGTH(GROUP_CONCAT(descripcion_prod))>40 then '...)'else ')' end) as detalle");
		$this->db->from("liquidacion_grupodetalle lgd");
		$this->db->join("liquidacion_detalle ld","ld.liqdetalleid=lgd.liqdetalleid");
		$this->db->join("liquidacion l","l.liquidacionId=ld.liquidacionId");
		$this->db->join("siniestro s","s.idsiniestro=l.idsiniestro");
		$this->db->join("asegurado a","a.aseg_id=s.idasegurado");
		$this->db->join("plan_detalle pd","ld.idplandetalle=pd.idplandetalle",'left');
		$this->db->join("variable_plan vp","pd.idvariableplan=vp.idvariableplan",'left');
		$this->db->join(" proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("producto_detalle prd","prd.idplandetalle=pd.idplandetalle","left");
		$this->db->join("producto pr","pr.idproducto=prd.idproducto","left");
		$this->db->where("liqgrupo_id",$id);
		$this->db->group_by("ld.liqdetalleid");

		$query = $this->db->get();
		return $query->result();
	}

	function getLiquidacionGrupo($id){
		$this->db->select("username, DATE_FORMAT(p.fecha_pago,'%d/%m/%Y')as fecha_pago, b.descripcion, descripcion_fp, p.numero_operacion, p.email_notifica");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lg.liqgrupo_id");
		$this->db->join("pago p","p.idpago=pd.idpago");
		$this->db->join("proveedor pr","pr.idproveedor=p.idproveedor");
		$this->db->join("banco b","lg.medio_pago=b.idbanco","left");
		$this->db->join("forma_pago fp","fp.idformapago=lg.forma_pago","left");
		$this->db->join("usuario u","u.idusuario=p.usuario_paga");
		$this->db->where('lg.liqgrupo_id',$id);
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

	function get_liquidaciongrupo_p($id){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida,DATE_FORMAT(fecha_genera,'%d/%m/%Y') as fecha_genera, DATE_FORMAT(fecha_liquida,'%d/%m/%Y') as fecha_liquida, detraccion");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->join("usuario u","lg.usuario_genera=u.idusuario");
		$this->db->join("usuario u2","lg.usuario_liquida=u2.idusuario","left");
		$this->db->where("liqgrupo_estado",0);
		$this->db->where("ld.idproveedor",$id);
		$this->db->group_by("lgd.liqgrupo_id");
		$query=$this->db->get();
		return $query->result();
	}

	function get_liquidaciongrupo_c(){
		$this->db->select("LPAD(lg.liqgrupo_id,8,0) as numero, lg.liqgrupo_id, lgd.liqdetalleid, sum(liqdetalle_neto) as total, GROUP_CONCAT(DISTINCT liqdetalle_numfact) as ref, GROUP_CONCAT(DISTINCT nombre_comercial_pr)as proveedor, lg.liqgrupo_estado, u.username as usuario_genera, u2.username as usuario_liquida, DATE_FORMAT(fecha_genera,'%d/%m/%Y') as fecha_genera, DATE_FORMAT(fecha_liquida,'%d/%m/%Y') as fecha_liquida, detraccion");
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
			'numero_operacion' => $data['nro_operacion'],
			'fecha_pago' => $data['fecha'],
			'usuario_paga' => $data['idusuario'],
			'email_notifica' => $data['correo']
		);

		$this->db->where('idpago', $data['idpago']);
		$this->db->update('pago',$array);
	}

	function liquidacionpdf($id){
		$this->db->select("pr.razon_social_pr, pr.numero_documento_pr, sum(liqdetalle_neto) as total, b.descripcion,fp.descripcion_fp, p.numero_operacion, coalesce(pr.cta_corriente,'-') as cta_corriente, coalesce(pr.cta_detracciones,'-') as cta_detracciones, concat(ap_paterno_col,' ',ap_materno_col,' ',nombres_col) as colaborador, p.fecha_pago,lg.detraccion, p.email_notifica");
		$this->db->from("liquidacion l");
		$this->db->join("liquidacion_detalle ld","l.liquidacionId=ld.liquidacionId");
		$this->db->join("liquidacion_grupodetalle lgd","ld.liqdetalleid=lgd.liqdetalleid","left");
		$this->db->join("liquidacion_grupo lg","lg.liqgrupo_id=lgd.liqgrupo_id","left");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lg.liqgrupo_id");
		$this->db->join("pago p","p.idpago=pd.idpago");
		$this->db->join("proveedor pr","pr.idproveedor=p.idproveedor");
		$this->db->join("banco b","b.idbanco=pr.medio_pago","left");
		$this->db->join("forma_pago fp","fp.idformapago=pr.forma_pago","left");
		$this->db->join("colaborador c","c.idusuario=p.usuario_paga","left");
		$this->db->where("lg.liqgrupo_id",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function liquidacionpdf2($id){
		$this->db->select("pr.razon_social_pr, pr.numero_documento_pr, sum(liqdetalle_neto) as total, b.descripcion,fp.descripcion_fp, p.numero_operacion, coalesce(pr.cta_corriente,'-') as cta_corriente, coalesce(pr.cta_detracciones,'-') as cta_detracciones, concat(ap_paterno_col,' ',ap_materno_col,' ',nombres_col) as colaborador, p.fecha_pago,lg.detraccion, p.email_notifica");
		$this->db->from("liquidacion l");
		$this->db->join("liquidacion_detalle ld","l.liquidacionId=ld.liquidacionId");
		$this->db->join("liquidacion_grupodetalle lgd","ld.liqdetalleid=lgd.liqdetalleid","left");
		$this->db->join("liquidacion_grupo lg","lg.liqgrupo_id=lgd.liqgrupo_id","left");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lg.liqgrupo_id","left");
		$this->db->join("pago p","p.idpago=pd.idpago","left");
		$this->db->join("proveedor pr","pr.idproveedor=ld.idproveedor");
		$this->db->join("banco b","b.idbanco=pr.medio_pago","left");
		$this->db->join("forma_pago fp","fp.idformapago=pr.forma_pago","left");
		$this->db->join("colaborador c","c.idusuario=lg.usuario_genera","left");
		$this->db->where("lg.liqgrupo_id",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function liquidacionpdf_detalle($id){
		$this->db->select("ld.liqdetalle_numfact, sum(liqdetalle_neto) as neto, detraccion, GROUP_CONCAT(distinct s.num_orden_atencion) as num_orden_atencion, GROUP_CONCAT(distinct CONCAT(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2)))as afiliado");
		$this->db->from("liquidacion_detalle ld");
		$this->db->join("liquidacion l","l.liquidacionId=ld.liquidacionId");
		$this->db->join("siniestro s","s.idsiniestro = l.idsiniestro");
		$this->db->join("asegurado a","a.aseg_id=s.idasegurado");
		$this->db->join("liquidacion_grupodetalle lgd","ld.liqdetalleid = lgd.liqdetalleid");
		$this->db->join("liquidacion_grupo lg","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->where("lg.liqgrupo_id",$id);
		$this->db->group_by("liqdetalle_numfact");

		$query = $this->db->get();
		return $query->result();
	}

	function calcular_detraccion($data){
		$this->db->select("case when sum(liqdetalle_neto)>700 then sum(liqdetalle_neto)*0.12 else 0 end as detraccion, sum(liqdetalle_neto) as neto");
		$this->db->from("liquidacion_detalle ld");
		$this->db->join("liquidacion_grupodetalle lgd","lgd.liqdetalleid=ld.liqdetalleid");
		$this->db->join("liquidacion_grupo lg","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->where("lg.liqgrupo_id",$data['liq_id']);
		$this->db->group_by("ld.liqdetalle_numfact");

		$query = $this->db->get();
		return $query->result();
	}

	function up_liqgrupo($data)
	{
		$array = array
		(
			'detraccion' => $data['detra'],
			'total' => $data['tot']
		);
		$this->db->where("liqgrupo_id",$data['liq_id']);
		$this->db->update("liquidacion_grupo",$array);
	}

	function gastos_proveedor()
	{
		$this->db->select("PR.numero_documento_pr, PR.razon_social_pr, PR.nombre_comercial_pr,	LD.liqdetalle_aprovpago, sum(liqdetalle_neto) as liqdetalle_neto, count(LD.liqdetalle_numfact), LD.idproveedor");
		$this->db->from("liquidacion_detalle LD");
		$this->db->join("liquidacion L","L.liquidacionId = LD.liquidacionId");
		$this->db->join("plan_detalle pd","pd.idplandetalle = LD.idplandetalle","left");
		$this->db->join("proveedor PR","PR.idproveedor = LD.idproveedor");
		$this->db->where("liqdetalle_aprovpago",1);
		$this->db->group_by("LD.idproveedor");

		$query = $this->db->get();
		return $query->result();
	}

	function getLiquidacionesProveedor()
	{
		$this->db->select("ld.idproveedor, numero_documento_pr, razon_social_pr, nombre_comercial_pr, count(distinct lg.liqgrupo_id)as num_liq");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("liquidacion_grupodetalle lgd","lg.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("liquidacion_detalle ld","ld.liqdetalleid=lgd.liqdetalleid");
		$this->db->join("proveedor p","p.idproveedor=ld.idproveedor");
		$this->db->where("liqgrupo_estado",0);
		$this->db->group_by("ld.idproveedor");

		$query = $this->db->get();
		return $query->result();
	}

	function save_agrupacion($data){
		$array = array
		(
		'idproveedor' => $data['idprov'],
		'usuario_agrupa' => $data['idusuario'],
		'fecha_agrupa' => $data['fecha']
		);
		$this->db->insert('pago',$array);
	}

	function save_pago_detalle($data){
		$array = array
		(
			'idpago' => $data['idpago'],
			'liqgrupo_id' => $data['liq_id']
		);
		$this->db->insert("pago_detalle",$array);
	}

	function upLiqgrupo($data){
		$array = array('liqgrupo_estado' => 1 );
		$this->db->where("liqgrupo_id",$data['liq_id']);
		$this->db->update("liquidacion_grupo",$array);
	}

	function get_importe($data){
		$this->db->select(" sum(total) as importe, sum(detraccion)as detraccion");
		$this->db->from("liquidacion_grupo lg");
		$this->db->join("pago_detalle p","p.liqgrupo_id=lg.liqgrupo_id");
		$this->db->where("idpago",$data['idpago']);

		$query = $this->db->get();
		return $query->result();
	}

	function up_importe($data){
		$array = array
		(
			'importe' => $data['importe'] ,
			'importe_detraccion' => $data['detraccion']
		);
		$this->db->where('idpago',$data['idpago']);
		$this->db->update("pago",$array);
	}

	function getLiqAgrupadas(){
		$this->db->select("concat('GR',LPAD(p.idpago,6,0)) as numero, p.idpago, pr.razon_social_pr, b.nombre_corto as banco, descripcion_fp, cta_corriente, cta_detracciones, importe, coalesce(importe_detraccion,0) as importe_detraccion, num");
		$this->db->from("pago p");
		$this->db->join("proveedor pr","p.idproveedor=pr.idproveedor");
		$this->db->join("banco b","pr.medio_pago=b.idbanco","left");
		$this->db->join("forma_pago fp","fp.idformapago=pr.forma_pago","left");
		$this->db->join("(select count(idpago_detalle) as num, idpago from pago_detalle group by idpago)x","x.idpago=p.idpago");
		$this->db->where("usuario_paga is null");

		$query = $this->db->get();
		return $query->result();
	}

	function getPagospendientes(){
		$this->db->select("pd.idpago,concat('GR',lpad(pd.idpago,8,0))as grupo,  numero_documento_pr, razon_social_pr, GROUP_CONCAT(distinct  concat('L',lpad(lgd.liqgrupo_id,8,0))) as liquidaciones, GROUP_CONCAT(distinct ld.liqdetalle_numfact) as facturas, coalesce(importe,0) as importe, coalesce(importe_detraccion,0) as importe_detraccion");
		$this->db->from("liquidacion_detalle ld");
		$this->db->join("liquidacion_grupodetalle lgd","ld.liqdetalleid=lgd.liqdetalleid");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("pago p","p.idpago=pd.idpago");
		$this->db->join("proveedor pr","pr.idproveedor=p.idproveedor");
		$this->db->where("fecha_pago is null");
		$this->db->group_by("idpago");

		$query = $this->db->get();
		return $query->result();
	}

	function getInfoPago($id){
		$this->db->select("p.idpago, pr.numero_documento_pr, pr.razon_social_pr, b.descripcion as banco, fp.descripcion_fp, pr.cta_corriente, p.importe, cta_detracciones, p.importe_detraccion");
		$this->db->from("pago p");
		$this->db->join("proveedor pr","p.idproveedor=pr.idproveedor");
		$this->db->join("banco b","b.idbanco=pr.medio_pago","left");
		$this->db->join("forma_pago fp","fp.idformapago=pr.forma_pago","left");
		$this->db->where("p.idpago",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function getPagosRealizados(){
		$this->db->select("pd.idpago,concat('GR',lpad(pd.idpago,8,0))as grupo,  numero_documento_pr, razon_social_pr, GROUP_CONCAT(distinct  concat('L',lpad(lgd.liqgrupo_id,8,0))) as liquidaciones, GROUP_CONCAT(distinct ld.liqdetalle_numfact) as facturas, coalesce(importe,0) as importe, coalesce(importe_detraccion,0) as importe_detraccion");
		$this->db->from("liquidacion_detalle ld");
		$this->db->join("liquidacion_grupodetalle lgd","ld.liqdetalleid=lgd.liqdetalleid");
		$this->db->join("pago_detalle pd","pd.liqgrupo_id=lgd.liqgrupo_id");
		$this->db->join("pago p","p.idpago=pd.idpago");
		$this->db->join("proveedor pr","pr.idproveedor=p.idproveedor");
		$this->db->where("fecha_pago is not null");
		$this->db->group_by("idpago");

		$query = $this->db->get();
		return $query->result();
	}

	function getLiqGrupo($id){
		$this->db->select("liqgrupo_id, lpad(liqgrupo_id,8,0) as num");
		$this->db->from("pago_detalle");
		$this->db->where("idpago",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function pagoDet($id){
		$this->db->select("p.idpago, lg.liqgrupo_id, lpad(lg.liqgrupo_id,8,0)as liq_num, lg.fecha_genera, u.username, lg.total, lg.detraccion");
		$this->db->from("pago p");
		$this->db->join("pago_detalle pd","p.idpago=pd.idpago");
		$this->db->join("liquidacion_grupo lg","lg.liqgrupo_id=pd.liqgrupo_id");
		$this->db->join("usuario u","u.idusuario=lg.usuario_genera");
		$this->db->where("p.idpago",$id);
		$query = $this->db->get();
		return $query->result();
	}

}
?>