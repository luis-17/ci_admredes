<?php
 class Liquidacion_mdl extends CI_Model {

 function Liquidacion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getLiquidaciones(){
		$this->db->select("LD.liqdetalleid, S.num_orden_atencion, S.fecha_atencion, PR.nombre_comercial_pr, LD.liqdetalle_numfact,  LD.liqdetalle_aprovpago, liqdetalle_neto, nombre_var, CONCAT('(', left(GROUP_CONCAT(descripcion_prod),40), case when CHAR_LENGTH(GROUP_CONCAT(descripcion_prod))>40 then '...)'else ')' end) as concepto");
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

	function buscaLiqDeta($liqdetalleid){
	 $this->db->select('*');
	 $this->db->from('liquidacion_detalle');	 
	 $this->db->where('liqdetalleid', $liqdetalleid);	 
	 $this->db->order_by('liqdetalleid');	  	 
	 
	 $liqdetalleid = $this->db->get();
	 return $liqdetalleid->result_array();
		
	}

	function updateLiqdetalle($data){
		$this->db->set('pagoFecha', $data['pagoFecha']);
		$this->db->set('pagoForma', $data['pagoForma']);
		$this->db->set('pagoBanco', $data['pagoBanco']);
		$this->db->set('pagoNoperacion', $data['pagoNoperacion']);
		$this->db->set('pagoNfactura', $data['pagoNfactura']);
		$this->db->where('liqdetalleid', $data['liqdetalleid']);
		$this->db->update('pago');

	}

	function guardaLiqdetalle($data){
		$this->db->set('liqdetalleid', $data['liqdetalleid']);
		$this->db->set('pagoFecha', $data['pagoFecha']);
		$this->db->set('pagoForma', $data['pagoForma']);
		$this->db->set('pagoBanco', $data['pagoBanco']);
		$this->db->set('pagoNoperacion', $data['pagoNoperacion']);
		$this->db->set('pagoNfactura', $data['pagoNfactura']);		
		$this->db->insert('pago');
	}

	function updateEstadoLiqdetalle($data){		

		$this->db->set('liqdetalle_aprovpago', '2');
		$this->db->where('liqdetalleid', $data['liqdetalleid']);
		$this->db->update('liquidacion_detalle');
	}


	
	
}
?>