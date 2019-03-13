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

 	function getCobros2($datos){
 		$this->db->select("c2.cert_id, c1.cert_num, cob_fechCob, cob_vezCob, cob_importe, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2,'')) as contratante");
 		$this->db->select("(select count(certase_id) from certificado_asegurado ca where ca.cert_id=c1.cert_id group by ca.cert_id) as num_afiliados");
 		$this->db->from('cobro c1');
 		$this->db->join("certificado c2","c1.cert_id=c2.cert_id");
 		$this->db->join("contratante c3","c2.cont_id=c3.cont_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']);
 		$this->db->order_by("cob_fechCob");

 	$cobros = $this->db->get();
	return $cobros->result();
 	} 	

 	function getImportes($datos){
 		$this->db->select("count(c1.cob_id)as cant,cob_importe, case when cob_importe=round(prima_monto*100) then 'Titular' else concat('Titular + ',ROUND((cob_importe-(prima_monto*100))/(prima_adicional*100))) end as descripcion"); 		
 		$this->db->from('cobro c1'); 	
 		$this->db->join("plan p","p.idplan=c1.plan_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']);
 		$this->db->group_by("cob_importe");

 	$importe = $this->db->get();
	return $importe->result();
 	}

 	function getCanales(){
 		$this->db->select("idclienteempresa, nombre_comercial_cli");
 		$this->db->from("cliente_empresa");
        //$this->db->where("estado_cli",1);
 		$this->db->order_by("nombre_comercial_cli");

 	$canal = $this->db->get();
 	return $canal->result();
 	}

	function getPlanes2($id){
 		$this->db->select("idplan, nombre_plan, flg_cancelar");
 		$this->db->from("plan");		
    	//$this->db->where("estado_plan",1);
 		$this->db->where("idclienteempresa",$id);

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getPlanes(){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");		
    	//$this->db->where("estado_plan",1);
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function cons_atenciones($data){
 		$this->db->select("case when s.idcita is null then concat('OA',num_orden_atencion) else concat('PO',num_orden_atencion) end as tipo_atencion, fecha_atencion, aseg_numDoc, concat(COALESCE(a.aseg_ape1,''),' ',COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as asegurado, case when aseg_numDoc=cont_numDoc then 'Titular' else 'Dependiente' end as tipo_afiliado, aseg_telf, p.nombre_comercial_pr, e.nombre_esp, estado_cita, fase_atencion, estado_atencion, estado_siniestro, username");
 		$this->db->from("siniestro s");
 		$this->db->join("cita ci","ci.idcita=s.idcita","left");
 		$this->db->join("usuario u ","u.idusuario=ci.idusuario","left");
 		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
 		$this->db->join("certificado_asegurado ca","ca.aseg_id=a.aseg_id");
 		$this->db->join("certificado c","s.idcertificado=c.cert_id and ca.cert_id=c.cert_id");
 		$this->db->join("contratante co","c.cont_id=co.cont_id");
 		$this->db->join("especialidad e","e.idespecialidad=s.idespecialidad");
 		$this->db->join("proveedor p","s.idproveedor=p.idproveedor");
 		$this->db->where("fecha_atencion>='".$data['inicio']."' and fecha_atencion<='".$data['fin']."' and plan_id=".$data['plan']." and c.cert_num not like 'PR%'");
 		$this->db->order_by("fecha_atencion, aseg_numDoc");
 	$query = $this->db->get();
 	return $query->result();
 	}

 	function cons_afiliados($data){
 		$this->db->select("c.plan_id,c.cert_id, cert_num, a.aseg_numDoc, concat(COALESCE(a.aseg_ape1,''),' ',COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as asegurado, aseg_telf, case when a.aseg_numDoc=co.cont_numDoc then 'Titular' else 'Dependiente' end as tipo, case when ca.idusuario is not null then (select username from usuario where idusuario=ca.idusuario) else case when ca.idusuario_afi is not null then (select username_afi from usuario_afi where idusuario_afi=ca.idusuario_afi)else 'sistemas' end end as username");
 		$this->db->from("asegurado a");
 		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
 		$this->db->join("certificado c","c.cert_id=ca.cert_id");
 		$this->db->join("contratante co","co.cont_id=c.cont_id");
 		$this->db->where("c.plan_id=".$data['plan']." and ca.cert_estado=".$data['tipo']." and c.cert_num not like 'PR%'");
 	$query = $this->db->get();
 	return $query->result();
 	}
}
?>