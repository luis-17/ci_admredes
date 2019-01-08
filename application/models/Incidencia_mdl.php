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

 function reg_derivacion($data){
 	$array = array
 		(
 			'idincidencia' => $data['id'], 
 			'idusuario_deriva' => $data['idusuario_deriva'],
 			'idusuario_recepciona' => $data['idusuario_recepciona'],
 			'estado_deriva' => $data['estado']
 		);

 	$this->db->insert("incidencia_deriva", $array);
 }

 function getMisPendientes($id){
 	$this->db->select("LPAD(idincidencia,6,'0')as id, b.*, cert_num, aseg_numDoc, concat(aseg_ape1,' ',aseg_ape2,' ',aseg_nom1,' ',coalesce(aseg_nom2,''))as afiliado");
 	$this->db->from("(select * from incidencia where idusuario_registra=$id and estado_deriva=0 union select i.* from incidencia i inner join incidencia_deriva id on i.idincidencia=id.idincidencia where id.idusuario_recepciona=$id and estado=0)b");
 	$this->db->join("certificado c","c.cert_id=b.cert_id");
 	$this->db->join("asegurado a","a.aseg_id=b.idasegurado");

 	$query = $this->db->get();
 	return $query->result();
 }
}
?>