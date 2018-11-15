<?php
 class Liquidacion_mdl extends CI_Model {

 function Liquidacion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getLiquidaciones(){
		$this->db->select("LD.liqdetalleid, S.num_orden_atencion, S.fecha_atencion, PR.nombre_comercial_pr, LD.liqdetalle_numfact,  LD.liqdetalle_aprovpago, ");
		$this->db->from("liquidacion_detalle LD");
		$this->db->join("liquidacion L","L.liquidacionId = LD.liquidacionId");
		$this->db->join("siniestro S","S.idsiniestro = L.idsiniestro");		
		$this->db->join("proveedor PR","PR.idproveedor = LD.idproveedor");
		$this->db->join("asegurado A","A.aseg_id = S.idasegurado");	
		$this->db->where("liqdetalle_aprovpago",1);
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