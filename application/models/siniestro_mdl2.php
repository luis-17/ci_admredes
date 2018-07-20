<?php
 class Siniestro_mdl extends CI_Model {

 function Siniestro_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getInfoSiniestro($id){
		$this->db->select("C.plan_id, C.cert_num, P.idproveedor, P.nombre_comercial_pr, S.idsiniestro, A.aseg_id, A.aseg_numDoc, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, S.num_orden_atencion, S.fecha_atencion, SD.dianostico_temp, T.medicamento_temp, T.cantidad_trat, T.dosis_trat, T.tipo_tratamiento, SA.analisis_str, SA.si_cubre");
		$this->db->from("siniestro S");
		$this->db->join("certificado C","C.cert_id=S.idcertificado");
		$this->db->join("asegurado A","A.aseg_id=S.idasegurado");
		$this->db->join("siniestro_diagnostico SD","SD.idsiniestro=S.idsiniestro");
		$this->db->join("tratamiento T","T.idsiniestrodiagnostico=SD.idsiniestrodiagnostico");
		$this->db->join("siniestro_analisis SA","SA.idsiniestro=S.idsiniestro");
		$this->db->join("proveedor P","P.idproveedor=S.idproveedor");
		$this->db->where("S.idsiniestro = $id");		
		

	$infoSiniestro = $this->db->get();
	 return $infoSiniestro->result();
	}

	function getTriaje($id){
		$this->db->select("idtriaje, idasegurado, idsiniestro, motivo_consulta, presion_arterial_mm, frec_cardiaca, frec_respiratoria, peso, talla, estado_cabeza, piel_faneras, cv_ruido_cardiaco, tp_murmullo_vesicular, estado_abdomen, ruido_hidroaereo, estado_neurologico, estado_osteomuscular, gu_puno_percusion_lumbar, gu_puntos_reno_uretelares");
		$this->db->from("triaje");
		$this->db->where("idsiniestro = $id");

	$triaje = $this->db->get();
	 return $triaje->row_array();	

	}

	function getDiagnostico($id){
		$this->db->select("idsiniestrodiagnostico, idsiniestro, tipo_diagnostico, es_principal, dianostico_temp");
		$this->db->from("siniestro_diagnostico");
		$this->db->where("idsiniestro = $id");

	$diagnostico = $this->db->get();
	 return $diagnostico->result();
	}


	function getMedicamento($id){
		$this->db->select("idtratamiento, idsiniestrodiagnostico, cantidad_trat, dosis_trat, medicamento_temp, tipo_tratamiento");
		$this->db->from("tratamiento");
		$this->db->where("idsiniestrodiagnostico in (select idsiniestrodiagnostico from siniestro_diagnostico where idsiniestro =  $id)");

	$medicamento = $this->db->get();
	 return $medicamento->result();	

	}


	function getLaboratorio($id){
		$this->db->select("idsiniestroanalisis, idsiniestro, idproducto, analisis_str, si_cubre");
		$this->db->from("siniestro_analisis");
		$this->db->where("idsiniestro=$id");

	$laboratorio = $this->db->get();
	 return $laboratorio->result();	

	}

	
}
?>