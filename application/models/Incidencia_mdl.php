<?php
 class Incidencia_mdl extends CI_Model {

 function Incidencia_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 function getUsuarios($id)
 {
 	$this->db->select("c.idusuario, concat(ap_paterno_col,' ',ap_materno_col,' ',nombres_col) as colaborador");
 	$this->db->from("colaborador c");
 	$this->db->join("usuario u","u.idusuario=c.idusuario");
 	$this->db->where("estado_us",1);
 	$this->db->where("u.idusuario <> $id");
 	$this->db->order_by("colaborador");

 	$query = $this->db->get();
 	return $query->result();
 }

 function up_derivacion($data){
 	$array = array
 	(
 		'estado' => 1 
 	);
 	$this->db->where("idincidencia",$data['id']);
 	$this->db->update("incidencia_deriva",$array);
 }

 function reg_derivacion($data){
 	$array = array
 		(
 			'idincidencia' => $data['id'], 
 			'idusuario_deriva' => $data['idusuario_deriva'],
 			'idusuario_recepciona' => $data['idusuario_recepciona'],
 			'comentario' => $data['desc']
 		);

 	$this->db->insert("incidencia_deriva", $array);
 }

 function up_estado($data){
 	$array = array('estado_deriva' => 1 );
 	$this->db->where('idincidencia',$data['id']);
 	$this->db->update('incidencia',$array);
 }

 function getMisPendientes($id){
 	$this->db->select("LPAD(idincidencia,6,'0')as id, b.*, cert_num, aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,''))as afiliado");
 	$this->db->from("(select * from incidencia where idusuario_registra=$id and estado_deriva=0 union select i.* from incidencia i inner join incidencia_deriva id on i.idincidencia=id.idincidencia where id.idusuario_recepciona=$id and estado=0)b");
 	$this->db->join("certificado c","c.cert_id=b.cert_id");
 	$this->db->join("asegurado a","a.aseg_id=b.idasegurado");
 	$this->db->where("idusuario_soluciona is null");

 	$query = $this->db->get();
 	return $query->result();
 }

 function save_solucion($data){
 	$array = array
 		(
 			'respuesta' => $data['respuesta'],
 			'idusuario_soluciona' => $data['idusuario_soluciona'],
 			'estado_deriva' =>2,
 			'fecha_solucion' => $data['fecha']
 		);
 	$this->db->where('idincidencia', $data['id']);
 	$this->db->update('incidencia', $array);
 }

 function up_solucion($data){
 	$array = array('estado' => 1 );
 	$this->db->where('idincidencia',$data['id']);
 	$this->db->where('estado',0);
 	$this->db->update("incidencia_deriva",$array);
 }

 function getHistorial($id){
 	$this->db->select(" fech_reg, concat(username,': ',i.descripcion) as colaborador, 'Registró Incidencia' as accion from incidencia i inner join usuario c on i.idusuario_registra=c.idusuario where idincidencia=$id union select fecha_hora as fech_reg, concat(c.username,': ',coalesce(i.comentario,'')) as colaborador, case when estado=2 then 'Registró Evento' else 'Derivó' end as accion from incidencia_deriva i inner join usuario c on i.idusuario_deriva=c.idusuario where idincidencia=$id union select fecha_solucion as fech_reg, concat(username,': ',i.respuesta) as colaborador, 'Solucionó' as accion from incidencia i inner join usuario c on i.idusuario_soluciona=c.idusuario where idincidencia=$id");
 	$query=$this->db->get();
 	return $query->result();
 }

 function getResueltas(){
 	$this->db->select("LPAD(idincidencia,6,'0')as id, b.*, cert_num, aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,''))as afiliado");
 	$this->db->from("(select * from incidencia where estado_deriva=2)b");
 	$this->db->join("certificado c","c.cert_id=b.cert_id");
 	$this->db->join("asegurado a","a.aseg_id=b.idasegurado");

 	$query=$this->db->get();
 	return $query->result();
 }

 function getOtrosPendientes($id){
 	$this->db->select("LPAD(idincidencia, 6, '0')as id, b.*, cert_num, aseg_numDoc, concat(aseg_ape1, ' ', aseg_ape2, ' ', aseg_nom1, ' ', coalesce(aseg_nom2, ''))as afiliado, username");
 	$this->db->from("(select i.*, i.idusuario_registra as id_usu from incidencia i where idusuario_registra<>$id and estado_deriva=0 )b");
 	$this->db->join("certificado c","c.cert_id=b.cert_id");
 	$this->db->join("asegurado a","a.aseg_id=b.idasegurado");
 	$this->db->join("usuario u","u.idusuario=b.id_usu");
 	$this->db->where("idusuario_soluciona is null");
 	$query = $this->db->get();
 	return $query->result();
 }

 function getOtrosPendientes2($id){
 	$this->db->select("LPAD(idincidencia, 6, '0')as id, b.*, cert_num, aseg_numDoc, concat(aseg_ape1, ' ', aseg_ape2, ' ', aseg_nom1, ' ', coalesce(aseg_nom2, ''))as afiliado, username");
 	$this->db->from("(select i.*, id.idusuario_recepciona as id_usu from incidencia i inner join incidencia_deriva id on id.idincidencia=i.idincidencia where idusuario_recepciona<>$id and estado=0 and estado_deriva<>2)b");
 	$this->db->join("certificado c","c.cert_id=b.cert_id");
 	$this->db->join("asegurado a","a.aseg_id=b.idasegurado");
 	$this->db->join("usuario u","u.idusuario=b.id_usu");

 	$query = $this->db->get();
 	return $query->result();
 } 

 function contenido_mail($data){
 	$this->db->select("nombre_comercial_cli, nombre_plan, cert_num, aseg_numDoc, concat(aseg_ape1, ' ', aseg_ape2, ' ', aseg_nom1, ' ', aseg_nom2)as afiliado, aseg_telf, aseg_email, fech_reg, tipoincidencia, descripcion");
 	$this->db->from("incidencia i");
 	$this->db->join("incidencia_deriva id","i.idincidencia=id.idincidencia");
 	$this->db->join("asegurado a","i.idasegurado=a.aseg_id");
 	$this->db->join("certificado c","i.cert_id=c.cert_id");
 	$this->db->join("plan p","c.plan_id=p.idplan");
 	$this->db->join("cliente_empresa ce","ce.idclienteempresa=p.idclienteempresa");
 	$this->db->where("i.idincidencia",$data['id']);

 	$query = $this->db->get();
 	return $query->result();
 }

 function destinatario($data){
 	$this->db->select("*");
 	$this->db->from("colaborador");
 	$this->db->where("idusuario",$data['idusuario_recepciona']);

 	$query = $this->db->get();
 	return $query->result();
 }

 function getUsuarioAsignado($id){
 	$query = $this->db->query("select idusuario, concat(nombres_col,' ',ap_paterno_col,' ',ap_materno_col) as colaborador, correo_laboral from colaborador c
								where idusuario in (select case when estado_deriva=0 then idusuario_registra else (select idusuario_recepciona from incidencia_deriva id where id.idincidencia=i.idincidencia and estado=0 order by idderivacion desc limit 1) end as idusuario 
								from incidencia i
								where fecha_solucion is null and idincidencia=$id )");
 	return $query->row_array();
 }

 function reg_evento($data){
 	$array = array(
 		'idincidencia' => $data['idincidente'],
 		'idusuario_deriva' => $data['idusuario_deriva'],
 		'idusuario_recepciona' => $data['idusuario_asignado'],
 		'fecha_hora' => $data['hoy'],
 		'comentario' => $data['comentario'],
 		'estado' => 2);
 	$this->db->insert("incidencia_deriva",$array);
 }

 function historial_incidencias($cert_id, $aseg_id){
 	$query = $this->db->query("select a.*, u1.username as registra, u2.username as recepciona from 
							(select i.*, case when (select idusuario_recepciona from incidencia_deriva ud where  ud.idincidencia=i.idincidencia order by ud.idincidencia desc limit 1)is null then idusuario_registra 
							else (select idusuario_recepciona from incidencia_deriva ud where  ud.idincidencia=i.idincidencia order by ud.idincidencia desc limit 1) end as usuario_asignado,
							case when idusuario_soluciona is null then 'Pendiente' else 'Resuelta' end as estado, case when idusuario_soluciona is null then 'label label-danger label-white middle' else 'label label-success label-white middle' end as class
							from incidencia i 
							where cert_id=$cert_id and idasegurado=$aseg_id)a
							inner join usuario u1 on u1.idusuario=a.idusuario_registra
							inner join usuario u2 on u2.idusuario=a.usuario_asignado");
 	return $query->result();
 }

 function save_solucionincidencia($data){
 	$array = array('idusuario_soluciona' => $data['idusuario'], 'fecha_solucion' => $data['hoy'], 'respuesta' => $data['respuesta'], 'estado_deriva'=>2);
 	$this->db->where('idincidencia' , $data['idincidente']);
 	$this->db->update('incidencia',$array);
 }
}
?>