<?php
 class Reportes_mdl extends CI_Model {

 function Reportes_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCobros($datos){
 		$this->db->select("c2.cert_id, c1.cert_num, cob_fechCob, cob_vezCob, cob_importe, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2,'')) as contratante");
 		$this->db->select("(select count(certase_id) from certificado_asegurado ca where ca.cert_id=c1.cert_id group by ca.cert_id) as num_afiliados");
 		$this->db->from('cobro c1');
 		$this->db->join("certificado c2","c1.cert_id=c2.cert_id");
 		$this->db->join("contratante c3","c2.cont_id=c3.cont_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']." and cob_importe=".$datos['importe']);
 		$this->db->order_by("cob_fechCob");

 	$cobros = $this->db->get();
	return $cobros->result();
 	} 	

 	function getImportes($datos){
 		$this->db->select("count(c1.cob_id)as cant,cob_importe"); 		
 		$this->db->from('cobro c1'); 	
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']);
 		$this->db->group_by("cob_importe");

 	$importe = $this->db->get();
	return $importe->result();
 	}

 	function getPlanes(){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}
}
?>