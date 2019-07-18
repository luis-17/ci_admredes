<?php
 class Reserva_mdl extends CI_Model {

 function Reserva_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getMisReservas($id){
		$this->db->select("c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, concat(DATE_FORMAT(c.fecha_cita,'%d/%m/%Y'),' ', date_format(hora_cita_inicio,'%H:%m'),' - ', date_format(hora_cita_fin,'%H:%m')) as fecha, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_cita, case when TIMESTAMPDIFF(minute,c.createdat,now())>59 then  concat(TIMESTAMPDIFF(hour,c.createdat,now()),' hora(s)') else concat(TIMESTAMPDIFF(minute,c.createdat,now()),' minuto(s)') end as tiempo");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");
		$this->db->where("c.idusuario_reserva=$id and estado_atencion='P' and estado_cita=1");
		$this->db->order_by("idcita", "desc");

	$misreservas = $this->db->get();
	 return $misreservas->result();
	}

	function getReservasConfirmadas(){
		$this->db->select("c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, concat(DATE_FORMAT(c.fecha_cita,'%d/%m/%Y'),' ', date_format(hora_cita_inicio,'%H:%m'),' - ', date_format(hora_cita_fin,'%H:%m')) as fecha, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_cita, username, notifica_afiliado, aseg_telf");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");		
		$this->db->join("usuario u","u.idusuario=c.idusuario");
		$this->db->where("estado_atencion='P' and estado_cita=2");
		$this->db->order_by("idcita", "desc");

	$reservas = $this->db->get();
	 return $reservas->result();
	}

	function getOtrasReservas($id){
		$this->db->select("c.idcita, pl.nombre_plan, s.idcertificado, c.idasegurado, c.idcertificadoasegurado, num_orden_atencion, concat(DATE_FORMAT(c.fecha_cita,'%d/%m/%Y'),' ', date_format(hora_cita_inicio,'%H:%m'),' - ', date_format(hora_cita_fin,'%H:%m')) as fecha, nombre_comercial_pr, a.aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,'')) as afiliado, estado_cita, username, case when idtipousuario<>5 then 'ace-icon fa fa-exclamation-triangle red' else '' end as tipo, case when TIMESTAMPDIFF(minute,c.createdat,now())>59 then  concat(TIMESTAMPDIFF(hour,c.createdat,now()),' hora(s)') else concat(TIMESTAMPDIFF(minute,c.createdat,now()),' minuto(s)') end as tiempo, case when idtipousuario<>5 then '1' else '2' end as tipo2, c.createdat");
		$this->db->from("cita c");
		$this->db->join("siniestro s","s.idcita=c.idcita");
		$this->db->join("certificado ce","ce.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=ce.plan_id");
		$this->db->join("especialidad e","c.idespecialidad=e.idespecialidad");
		$this->db->join("proveedor p","p.idproveedor=c.idproveedor");	
		$this->db->join("asegurado a","a.aseg_id=c.idasegurado");
		$this->db->join("usuario u","u.idusuario=c.idusuario_reserva");
		$this->db->where("c.idusuario_reserva<>$id and estado_atencion='P' and estado_cita=1");
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

	function getCita($id){
		$query = $this->db->query("select coalesce(aseg_nom1,aseg_nom2) as nombre, concat(coalesce(coalesce(aseg_nom1,aseg_nom2),''),' ', coalesce(coalesce(aseg_ape1,aseg_ape2),''))as afiliado, nombre_comercial_pr, plan_id, fecha_cita, hora_cita_inicio, nombre_esp, s.idespecialidad, nombre_plan
									from cita c
									inner join asegurado a on a.aseg_id=c.idasegurado
									inner join proveedor p on p.idproveedor=c.idproveedor
									inner join siniestro s on s.idcita=c.idcita
									inner join certificado ce on ce.cert_id=s.idcertificado
									inner join plan pl on pl.idplan=ce.plan_id
									inner join especialidad e on e.idespecialidad=s.idespecialidad
				where c.idcita=$id");
		return $query->row_array();
	}

	function getConsultaMedica($data){
		$query = $this->db->query("select pr.idproducto, concat('La consulta médica en ',descripcion_prod,' tiene ',cobertura) as consulta from plan_detalle pd 
								inner join producto_detalle prd on pd.idplandetalle=prd.idplandetalle
								inner join producto pr on pr.idproducto=prd.idproducto
								inner join (select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
									where pc.estado=1 group by idplandetalle)a on a.idplandetalle=pd.idplandetalle
								where pd.idvariableplan=1 and idplan=".$data['plan_id']." and visible=1 and estado_pd=1 and pr.idespecialidad=".$data['idespecialidad']);
		return $query->row_array();
	}

	function getCoberturasOperador($data){
		$query = $this->db->query("select nombre_var, texto_web, num_eventos, tiempo, cobertura as coaseguro FROM variable_plan vp 
									inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
									inner join (select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
									where pc.estado=1 group by idplandetalle)a on a.idplandetalle=pd.idplandetalle
									inner join plan p on pd.idplan=p.idplan 
									where pd.idvariableplan in (2,3,4) and p.idplan=".$data['plan_id']);
		return $query->result();
	}

	function getCoberturas($data){
		$query = $this->db->query("select nombre_var, texto_web
									FROM variable_plan vp 
									inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
									inner join plan p on pd.idplan=p.idplan
									where pd.idvariableplan<>1 and  p.idplan=".$data['plan_id']);
		return $query->result();
	}

	function upCitaNotificacion($id){
		$array = array('notifica_afiliado' => 1 );
		$this->db->where("idcita",$id);
		$this->db->update("cita",$array);
	}

}
?>