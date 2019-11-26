<?php
 class Login_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

public function login($email, $password){
	$this->db->select("*");
	$this->db->from("usuario u");
	$this->db->join("colaborador c","u.idusuario=c.idusuario");
	$this->db->where("username",$email);
	$this->db->where("u.estado_us",1);
	$this->db->where("password",md5($password));

	$query=$this->db->get();
	return $query->row_array();
}

public function atenciones(){
	$this->db->select("idsiniestro, idcita");
	$this->db->from("siniestro");
	$this->db->where("fecha_atencion<(DATE_FORMAT(NOW(),'%Y-%m-%d')) and estado_atencion='P' and estado_siniestro=1");
$query=$this->db->get();
return $query->result();
}

public function eliminar_cita($data){
	$array = array(
		'estado_cita' => 0
 	);
$this->db->where('idcita',$data['idcita']);
return $this->db->update('cita', $array);
}

public function eliminar_orden($data){
	$array = array(
		'estado_siniestro' => 0
	);
$this->db->where('idsiniestro',$data['idsiniestro']);
return $this->db->update('siniestro', $array);
}

public function getPlan($data){
	$query = $this->db->query("select nombre_comercial_cli, nombre_plan, dias_carencia, dias_mora, dias_atencion from plan p 
								inner join cliente_empresa ce on p.idclienteempresa=ce.idclienteempresa
								where idplan = ".$data['plan']);
	return $query->row_array();
}

public function getResponsable($data){
	$query = $this->db->query("select GROUP_CONCAT(concat(nombres_col,' ',ap_paterno_col,' (',case when tipo_responsable='P' then 'Principal' else 'Apoyo' end,')')) as responsable from plan_usuario pu
							inner join usuario u  on pu.idusuario=u.idusuario
							inner join colaborador c on c.idusuario=u.idusuario
							where idplan=".$data['plan']);
	return $query->row_array();
}

public function getCoberturasOperador($data){
	$query = $this->db->query("select nombre_var, texto_web, num_eventos, tiempo, cobertura as coaseguro , coalesce(bloqueos,'-') as bloqueos
							FROM variable_plan vp 
							inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
							inner join (select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
													where pc.estado=1 group by idplandetalle)a on a.idplandetalle=pd.idplandetalle
							left join (select idplandetalle_bloquea, GROUP_CONCAT(vp2.nombre_var) as bloqueos from plan_detalle_bloqueo pb 
													inner join plan_detalle  pd2 on pb.idplandetalle_bloqueado=pd2.idplandetalle
													inner join variable_plan vp2 on pd2.idvariableplan=vp2.idvariableplan
													GROUP BY idplandetalle_bloquea)b on b.idplandetalle_bloquea=pd.idplandetalle
							inner join plan p on pd.idplan=p.idplan 
							where estado_pd=1 and p.idplan=".$data['plan']." order by vp.idvariableplan");
	return $query->result();
}

public function getCoberturas($data){
	$query = $this->db->query("select nombre_var, texto_web
								FROM variable_plan vp 
								inner join plan_detalle pd on vp.idvariableplan=pd.idvariableplan
								inner join plan p on pd.idplan=p.idplan 
								where p.idplan=".$data['plan']." and estado_pd=1 and pd.idplandetalle not in (select idplandetalle from plan_coaseguro where estado=1) order by vp.idvariableplan");
	return $query->result();
}

public function getProductosDiagnosticos($id,$plan){
	$query = $this->db->query("select p.idproducto, descripcion_prod, nombre_var
						from producto p
						join diagnostico_producto d on p.idproducto = d.idproducto
						join variable_plan vp on vp.idvariableplan=p.idvariableplan
						join producto_detalle pd on pd.idproducto=p.idproducto
						join plan_detalle det on pd.idplandetalle=det.idplandetalle
						where d.iddiagnostico=$id and det.idplan=$plan");
	return $query->result();
}
}
?>