<?php
 class Reserva_mdl extends CI_Model {

 function Reserva_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getMisReservas($id){
		$this->db->select("c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, concat(DATE_FORMAT(c.fecha_cita,'%d/%m/%Y'),' ', date_format(hora_cita_inicio,'%H:%m'),' - ', date_format(hora_cita_fin,'%H:%m')) as fecha, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_cita");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");
		$this->db->where("c.idusuario=$id and estado_atencion='P' and estado_cita<>0");
		$this->db->order_by("idcita", "desc");

	$misreservas = $this->db->get();
	 return $misreservas->result();
	}

	function getOtrasReservas($id){
		$this->db->select("c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, concat(DATE_FORMAT(c.fecha_cita,'%d/%m/%Y'),' ', date_format(hora_cita_inicio,'%H:%m'),' - ', date_format(hora_cita_fin,'%H:%m')) as fecha, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_cita, username");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");
		$this->db->join("usuario u","u.idusuario=c.idusuario");
		$this->db->where("c.idusuario<>$id and estado_atencion='P' and estado_cita<>0");
		$this->db->order_by("idcita", "desc");

	$misreservas = $this->db->get();
	 return $misreservas->result();
	}

	function getReservasAtendidas(){
		$this->db->select("s.idsiniestro,c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, DATE_FORMAT(fecha_atencion,'%d/%m/%Y') as fecha_atencion, aseg_telf, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_siniestro, username");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");
		$this->db->join("usuario u","u.idusuario=c.idusuario");
		$this->db->where("estado_atencion='O' and estado_cita<>0");
		$this->db->order_by("idcita", "desc");

	$misreservas = $this->db->get();
	 return $misreservas->result();
	}

	function getAtencionesDirectas(){
		$this->db->select("s.idsiniestro, pl.nombre_plan, s.idcertificado, s.idasegurado, num_orden_atencion, DATE_FORMAT(fecha_atencion,'%d/%m/%Y') as fecha_atencion, aseg_telf, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_siniestro");
		$this->db->from("siniestro s");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=s.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=s.idasegurado");
		$this->db->where("estado_atencion='O' and estado_siniestro<>0");
		$this->db->order_by("idsiniestro", "desc");

	$misreservas = $this->db->get();
	 return $misreservas->result();
	}

}
?>