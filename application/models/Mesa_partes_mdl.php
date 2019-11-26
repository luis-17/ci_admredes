<?php
 class mesa_partes_mdl extends CI_Model {

 function mesa_partes_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	 
	function buscar_orden($data){
		$query = $this->db->query("select idsiniestro, s.idproveedor, nombre_comercial_pr, aseg_numDoc, concat(COALESCE(aseg_ape1,''),' ',COALESCE(aseg_ape2,''),' ', COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as afiliado
			from siniestro s
			inner join proveedor pr on s.idproveedor=pr.idproveedor
			inner join asegurado a on a.aseg_id=s.idasegurado
			where num_orden_atencion=".$data['nro_orden']);
		return $query->result();
	}

	function getProveedores(){
		$query = $this->db->query("select idproveedor, nombre_comercial_pr, razon_social_pr from proveedor where estado_pr=1 order by nombre_comercial_pr asc");
		return $query->result();
	}

	function inRecepcion($data){
		$array = array
		(
			'fecha_recepcion' => $data['fecha_recepcion'], 
			'fecha_emision' => $data['fecha_emision'],
			'usuario_recepciona' => $data['usuario_recepciona'],
			'tipo_documento' => $data['tipodoc'],
			'serie' => $data['serie'],
			'numero' => $data['numero'],
			'importe' => $data['importe'],
			'idsiniestro' => $data['idsiniestro'],
			'tipo_recepcion' => 1,
			'idproveedor' => $data['idproveedor']
		);
		$this->db->insert("mesa_partes",$array);
	}

	function buscar_ruc($data){
		$query = $this->db->query("select idproveedor_int, razon_social_pr, nombre_comercial_pr, numero_documento_pr, concat(coalesce(direccion_pr,'') , coalesce(concat(' - ',udi.descripcion_ubig),''), coalesce(concat(' - ',up.descripcion_ubig),''), coalesce(concat(' - ',ud.descripcion_ubig),'')) as direccion from proveedor_int
			left join ubigeo ud on cod_departamento_pr=ud.iddepartamento and idprovincia='00' and ud.iddistrito='00'
			left join ubigeo up on cod_provincia_pr=up.idprovincia and up.iddistrito='00' and up.iddepartamento=ud.iddepartamento
			left join ubigeo udi on cod_distrito_pr=udi.iddistrito and up.iddepartamento=udi.iddepartamento and udi.idprovincia=up.idprovincia
			where numero_documento_pr=".$data['ruc']);
		return $query->result();
	}

	function inRecepcion2($data){
		$array = array
		(
			'fecha_recepcion' => $data['fecha_recepcion'], 
			'fecha_emision' => $data['fecha_emision'],
			'usuario_recepciona' => $data['usuario_recepciona'],
			'tipo_documento' => $data['tipodoc'],
			'serie' => $data['serie'],
			'numero' => $data['numero'],
			'importe' => $data['importe'],
			'idproveedor_int' => $data['idproveedor_int'],
			'tipo_recepcion' => 2
		);
		$this->db->insert("mesa_partes",$array);
	}

	function getRecibidos($data){
		$query = $this->db->query("select idrecepcion, fecha_recepcion, coalesce(fecha_emision,fecha_recepcion) as fecha_emision, tipo_documento, coalesce(concat(serie,'-',numero), numero) as comprobante, importe, num_orden_atencion, case when tipo_recepcion=1 then 'CENTRO MÉDICO' else  CASE WHEN  tipo_recepcion=2 then 'OTRO PROVEEDOR' else 'OTRO COMPROBANTE' end end as tipo,
			COALESCE(p.nombre_comercial_pr,pi.nombre_comercial_pr, remitente) as proveedor, username
			from mesa_partes mp
			inner join usuario u on u.idusuario=mp.usuario_recepciona
			left join proveedor p on mp.idproveedor=p.idproveedor
			left join proveedor_int pi on pi.idproveedor_int=mp.idproveedor_int
			left join siniestro s on s.idsiniestro=mp.idsiniestro
			where fecha_recepcion>='".$data['fecinicio']."' and fecha_recepcion<='".$data['fecfin']."'");
		return $query->result();
	}

	function getRecibido($id){
		$query = $this->db->query("select idrecepcion, fecha_recepcion, fecha_emision, tipo_documento, serie, numero, importe, num_orden_atencion, tipo_recepcion,
			COALESCE(p.numero_documento_pr,pi.numero_documento_pr) as ruc,
			COALESCE(p.razon_social_pr,pi.razon_social_pr) as razon_social_pr,
 			COALESCE(p.nombre_comercial_pr,pi.nombre_comercial_pr,remitente) as proveedor, descripcion
			from mesa_partes mp
			left join proveedor p on mp.idproveedor=p.idproveedor
			left join proveedor_int pi on pi.idproveedor_int=mp.idproveedor_int
			left join siniestro s on s.idsiniestro=mp.idsiniestro
			where idrecepcion=$id");
		return $query->row_array();
	}

	function upRecepcion($data){
		$array = array
		(
			'fecha_recepcion' => $data['fecha_recepcion'], 
			'fecha_emision' => $data['fecha_emision'],
			'tipo_documento' => $data['tipodoc'],
			'serie' => $data['serie'],
			'numero' => $data['numero'],
			'importe' => $data['importe']
		);
		$this->db->where('idrecepcion',$data['idrecepcion']);
		$this->db->update("mesa_partes",$array);
	}

	function inRecepcion3($data){
		$array = array
		(
			'fecha_recepcion' => $data['recepecion'], 
			'usuario_recepciona' => $data['usuario_recepciona'],
			'tipo_documento' => $data['tipodoc'],
			'numero' => $data['numero'],
			'remitente' => $data['remitente'],
			'descripcion' => $data['asunto'],
 			'tipo_recepcion' => 3
		);
		$this->db->insert("mesa_partes",$array);
	}

	function upRecepcion3($data){
		$array = array
		(
			'fecha_recepcion' => $data['fecha_recepcion'], 
			'tipo_documento' => $data['tipodoc'],
			'numero' => $data['numero'],
			'remitente' => $data['remitente'],
			'descripcion' => $data['asunto']
		);
		$this->db->where('idrecepcion',$data['idrecepcion']);
		$this->db->update("mesa_partes",$array);
	}

	function getRuc($id){
		$query = $this->db->query("select * from proveedor where idproveedor=$id");
		return $query->row_array();
	}
}
?>