<?php
 class Atencion_mdl extends CI_Model {

 function Atencion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getAtenciones($data){
		$this->db->select("s.idsiniestro,num_orden_atencion, '-' as fecha_reserva, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_numDoc, cert_num, pl.nombre_plan, cl.nombre_comercial_cli, s.idcita, COALESCE(l.liquidacion_estado,0) as liquidacion_estado, estado_siniestro, case when fecha_atencion_act is null then 'activar' else (case when fecha_atencion_act<fecha_atencion then 'restablecer' else 'ninguna' end) end as activacion");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=c.plan_id");	
		$this->db->join("cliente_empresa cl","cl.idclienteempresa=pl.idclienteempresa");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");	
		$this->db->join("proveedor pr","pr.idproveedor=s.idproveedor");	
		$this->db->join("liquidacion l","l.idsiniestro=s.idsiniestro","left");
		$this->db->where("estado_siniestro in(1,2) and estado_atencion='O' and fecha_atencion>='".$data['fecinicio']."' and fecha_atencion<='".$data['fecfin']."'");
		$this->db->order_by("idsiniestro", "asc");

	$atenciones = $this->db->get();
	return $atenciones->result();
	}

	function getPreOrden(){
		$this->db->select("idsiniestro,num_orden_atencion, '-' as fecha_reserva, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_numDoc, cert_num, pl.nombre_plan, cl.nombre_comercial_cli, s.idcita, idsiniestro");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=c.plan_id");	
		$this->db->join("cliente_empresa cl","cl.idclienteempresa=pl.idclienteempresa");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");	
		$this->db->join("proveedor pr","pr.idproveedor=s.idproveedor");	
		$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='P'");
		$this->db->order_by("idsiniestro", "desc");

	$preorden = $this->db->get();
	 return $preorden->result();
	}


	function getAtenciones_asegurado($aseg_id){
		$this->db->select("idsiniestro,num_orden_atencion, '-' as fecha_reserva, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_numDoc, cert_num, pl.nombre_plan, cl.nombre_comercial_cli, s.idcita");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=c.plan_id");	
		$this->db->join("cliente_empresa cl","cl.idclienteempresa=pl.idclienteempresa");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");	
		$this->db->join("proveedor pr","pr.idproveedor=s.idproveedor");	
		$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O' and a.aseg_id=$aseg_id");
		$this->db->order_by("idsiniestro", "asc");

	$atenciones = $this->db->get();
	 return $atenciones->result();
	}

	function getPreOrden_asegurado($aseg_id){
		$this->db->select("idsiniestro,num_orden_atencion, '-' as fecha_reserva, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_numDoc, cert_num, pl.nombre_plan, cl.nombre_comercial_cli, s.idcita, idsiniestro");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=c.plan_id");	
		$this->db->join("cliente_empresa cl","cl.idclienteempresa=pl.idclienteempresa");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");	
		$this->db->join("proveedor pr","pr.idproveedor=s.idproveedor");	
		$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='P' and a.aseg_id=$aseg_id");
		$this->db->order_by("idsiniestro", "desc");

	$preorden = $this->db->get();
	 return $preorden->result();
	}

	function orden($id,$est){
		$data = array(
			'estado_atencion' => $est
		);
		$this->db->where('idsiniestro',$id);
		return $this->db->update('siniestro', $data);
	}

	function getProveedor() {
        $data = array();
        $this->db->select('*');
        $this->db->from('proveedor');
        $this->db->order_by('nombre_comercial_pr');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row){
                    $data[] = $row;
                }
        }
        $query->free_result();
        return $data;
    }

    function getDatosDni($dni) {
    	$this->db->select("concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,'')) as apellidos, concat(coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,'')) as nombres, aseg_id");
    	$this->db->from("asegurado");
    	$this->db->where('aseg_numDoc',$dni);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	return $query->result();
    }

    function getProveedores(){
		$this->db->select("idproveedor, nombre_comercial_pr");
		$this->db->from("proveedor");
		$this->db->where("estado_pr",1);
		$this->db->order_by("nombre_comercial_pr");

	$proveedores=$this->db->get();
	return $proveedores->result();
	}

	function getPlanes($dni){
		$this->db->select("concat(nombre_plan, case when c.cert_estado=1 then ' (Vigente)' else ' (Cancelado)' end) as nombre_plan, certase_id");
		$this->db->from("plan p");
		$this->db->join("certificado c","p.idplan=c.plan_id");
		$this->db->join("certificado_asegurado ca","ca.cert_id=c.cert_id");
		$this->db->join("asegurado a","a.aseg_id=ca.aseg_id");
		$this->db->where("aseg_numDoc",$dni);
		//$this->db->group_by("nombre_plan");

		$query = $this->db->get();
		return $query->result();
	}

	function getEspecialidad($id){
		$this->db->select("pr.idespecialidad, descripcion_prod");
		$this->db->from("producto pr");
		$this->db->join("producto_detalle prd","pr.idproducto=prd.idproducto");
		$this->db->join("plan_detalle pd","pd.idplandetalle=prd.idplandetalle");
		$this->db->join("plan p","pd.idplan=p.idplan");
		$this->db->join("certificado c","c.plan_id=p.idplan");
		$this->db->join("certificado_asegurado ca","ca.cert_id=c.cert_id");
		$this->db->where("pd.idvariableplan",1);
		$this->db->where("certase_id",$id);
		$query =  $this->db->get();
		return $query->result();
	}

	function num_orden_atencion(){
		$this->db->select("lpad((num_orden_atencion +1),6,'0') as num_orden_atencion");
		$this->db->from("siniestro");
		$this->db->order_by("idsiniestro","desc");
		$this->db->limit(1);
		$num = $this->db->get();
		return $num->result();
	}

	function certase_id($id){
		$this->db->select("aseg_id, cert_id");
		$this->db->from("certificado_asegurado");
		$this->db->where("certase_id",$id);
		$query = $this->db->get();
		return $query->result();
	}

	function historia($aseg_id){
		$this->db->select("idhistoria");
		$this->db->from("historia");
		$this->db->where("idasegurado",$aseg_id);
		$query = $this->db->get();
		return $query->result();
	}

	function inHistoria($aseg_id){
		$array = array('idasegurado' => $aseg_id );
		$this->db->insert("historia",$array);
	}

	function reg_siniestro($data){
		$array = array
		(
			'idasegurado' => $data['aseg_id'],
			'idcertificado' => $data['cert_id'],
			'idareahospitalaria' => 1,
			'idproveedor' => $data['idproveedor'],
			'idespecialidad' => $data['idespecialidad'],
			'fecha_atencion' => $data['fecha'],
			'num_orden_atencion' => $data['num'],
			'estado_siniestro' => 1,
			'idhistoria' => $data['historia'],
			'usuario_crea' => $data['idusuario'],
			'estado_atencion' => 'O'
		);
		$this->db->insert("siniestro",$array);
	}

	function anular_siniestro($id,$idusuario){
		$array = array('estado_siniestro' => 0, 'usuario_anula' => $idusuario );
		$this->db->where("idsiniestro",$id);
		$this->db->update("siniestro",$array);
	}

	function getFechas($data){
		$this->db->select("fecha_atencion_act, fecha_atencion");
		$this->db->from("siniestro");
		$this->db->where("idsiniestro", $data['id']);
		$query = $this->db->get();
		return $query->result();
	}

	function reactivar_siniestro($data){
		$array = array('fecha_atencion' => $data['hoy'], 'fecha_atencion_act' => $data['fecha'], 'usuario_activa' => $data['idusuario'] );
		$this->db->where("idsiniestro",$data['id']);
		$this->db->update("siniestro",$array);
	}

	function restablecer_siniestro($data){
		$array = array('fecha_atencion' => $data['fecha_act'], 'fecha_atencion_act' => $data['fecha'] );
		$this->db->where("idsiniestro",$data['id']);
		$this->db->update("siniestro",$array);
	}

	function getEventos($id){
		$query = $this->db->query("select idperiodo, vez_actual from siniestro s 
				inner join siniestro_detalle sd on s.idsiniestro=sd.idsiniestro
				inner join certificado_asegurado ca on ca.aseg_id=s.idasegurado and ca.cert_id=s.idcertificado
				inner join periodo_evento pe on pe.certase_id=ca.certase_id and sd.idplandetalle=pe.idplandetalle
				where s.idsiniestro=$id");
		return $query->result();
	}

	function upPeriodo($data){
		$array = array('vez_actual' => $data['vez_actual'] );
		$this->db->where('idperiodo', $data['idperiodo']);
		$this->db->update('periodo_evento', $array);
	}
}
?>