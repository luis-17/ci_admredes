<?php
 class PostVenta_mdl extends CI_Model {

 function PostVenta_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 function getAtenciones(){
 	$query = $this->db->query("select idsiniestro, fecha_atencion, nombre_comercial_cli, nombre_plan, aseg_numDoc, aseg_ape1, aseg_ape2, aseg_nom1, aseg_nom2, aseg_telf  from siniestro s
			inner join asegurado a on s.idasegurado=a.aseg_id 
			inner join certificado c on c.cert_id=s.idcertificado
			inner join plan p on p.idplan=c.plan_id 
			inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
			where (TIMESTAMPDIFF(day,fecha_atencion,DATE_FORMAT(now(),'%Y-%m-%d')))>2 and fecha_atencion>'2019-05-12' 
			and idsiniestro not in (select idsiniestro from siniestro_encuesta) and estado_siniestro=1 and estado_atencion='O'");

 	return $query->result();
 }

 function getPreguntas(){
 	$query = $this->db->query("select * from encuesta_pregunta where estado=1");
 	return $query->result();
 }

 function getRespuestas(){
 	$query = $this->db->query("select * from encuesta_respuesta where estado=1");
 	return $query->result();
 }

 function getDatos($id){
 	$query = $this->db->query("select COALESCE(aseg_ape1,aseg_ape2) as apellido, COALESCE(aseg_nom1,aseg_nom2) as nombre, fecha_atencion, nombre_comercial_pr from siniestro s 
							inner join asegurado a on s.idasegurado=a.aseg_id
							inner join proveedor p on p.idproveedor=s.idproveedor
							where s.idsiniestro=$id");
 	return $query->row_array();
 }

 function getServicios($id){
 	$query = $this->db->query("select distinct nombre_var from siniestro_detalle sd
							inner join plan_detalle pd on sd.idplandetalle=pd.idplandetalle
							inner join variable_plan vp on vp.idvariableplan=pd.idvariableplan
							where idsiniestro=$id and vp.idvariableplan in (1,2,3)
							order by vp.idvariableplan");
 	return $query->result();
 }

 function save_encuesta($data){
 	$array = array
 	(
 		'idsiniestro' => $data['idsiniestro'],
 		'comentario' => $data['comentario'],
 		'fecha_hora' => $data['hoy'],
 		'idusuario' => $data['idusuario'],
 		'estado' => $data['estado'] 
 	);
 	$this->db->insert("siniestro_encuesta",$array);
 }

  function save_encuesta2($data){
 	$array = array
 	(
 		'idsiniestro' => $data['idsiniestro'],
 		'comentario' => $data['comentario'],
 		'fecha_hora' => $data['hoy'],
 		'estado' => $data['estado'] 
 	);
 	$this->db->insert("siniestro_encuesta",$array);
 }

 function save_encuesta_detalle($data){
 	$array = array('idencuesta' => $data['idencuesta'], 'idrespuesta' => $data['idrespuesta'] );
 	$this->db->insert("encuesta_detalle", $array);
 }	 
}
?>