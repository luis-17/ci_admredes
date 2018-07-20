<?php
 class Atencion_mdl extends CI_Model {

 function Atencion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getAtenciones(){
		$this->db->select("idsiniestro,num_orden_atencion, '-' as fecha_reserva, fecha_atencion, nombre_comercial_pr, nombre_esp,estado_siniestro, 's' as procedencia, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, aseg_numDoc, cert_num, pl.nombre_plan, cl.nombre_comercial_cli, s.idcita");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan pl","pl.idplan=c.plan_id");	
		$this->db->join("cliente_empresa cl","cl.idclienteempresa=pl.idclienteempresa");
		$this->db->join("especialidad e","s.idespecialidad=e.idespecialidad");	
		$this->db->join("proveedor pr","pr.idproveedor=s.idproveedor");	
		$this->db->where("estado_siniestro in(0,1,2) and estado_atencion='O'");
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
        $query = $this->db->get('proveedor');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row){
                    $data[] = $row;
                }
        }
        $query->free_result();
        return $data;
    }
}
?>