<?php
 class Plan_mdl extends CI_Model {

 function Plan_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getPlanes(){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('plan pl');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=pl.idclienteempresa');
 		$this->db->order_by("nombre_comercial_cli, nombre_plan");

	 $planes = $this->db->get();
	 return $planes->result();
 	}

 	function getCobertura($id){
 		$this->db->select('*');
 		$this->db->from('plan_detalle dp'); 		
 		$this->db->join('variable_plan vp','dp.idvariableplan=vp.idvariableplan');
 		$this->db->where('idplan',$id); 		
 		$this->db->order_by("nombre_var");

 	$cobertura = $this->db->get();
 	return $cobertura->result();
 	}

 	function getItems(){
 		$this->db->select('*');
 		$this->db->from('variable_plan');
 		$this->db->order_by("nombre_var");

 	$items = $this->db->get();
 	return $items->result();
 	}

 	function getClientes(){
 		$this->db->select('*');
 		$this->db->from('cliente_empresa');
 		$this->db->order_by("nombre_comercial_cli");

 	$clientes = $this->db->get();
 	return $clientes->result();
 	}

 	function getPlan($id){
 		$this->db->select('*');
 		$this->db->from('plan pl');
 		$this->db->where('idplan',$id);

	 $plan = $this->db->get();
	 return $plan->result();
 	}

 	function update_plan($data){
 		$array = array(
			'nombre_plan' => $data['nombre_plan'],
			'codigo_plan' => $data['codigo_plan'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			'prima_monto' => $data['prima'],
			'cuerpo_mail' => $data['contenido']
		);
		$this->db->where('idplan',$data['idplan']);
		return $this->db->update('plan', $array);
 	}

 	function insert_plan($data){
 		$array = array(
			'nombre_plan' => $data['nombre_plan'],
			'codigo_plan' => $data['codigo_plan'],
			'idclienteempresa' => $data['cliente'],
			'dias_carencia' => $data['carencia'],
			'dias_mora' => $data['mora'],
			'dias_atencion' => $data['atencion'],
			'prima_monto' => $data['prima'],
			'cuerpo_mail' => $data['contenido'],
			'idred' => 1
 				 );
		$this->db->insert('plan',$array);
 	}

 	function insert_cobertura($data){
 		$array = array(
				 'idplan' => $data['id'],
				 'idvariableplan' => $data['item'],
				 'texto_web' => $data['descripcion'],
				 'visible' => $data['visible'],
				 'flg_liquidacion' => $data['flg_liqui'],
				 'simbolo_detalle' => $data['operador'],
				 'valor_detalle' => $data['valor']
 				 );
		$this->db->insert('plan_detalle',$array);
 	}

 	function update_cobertura($data){
 		$array = array(
				 'idplan' => $data['id'],
				 'idvariableplan' => $data['item'],
				 'texto_web' => $data['descripcion'],
				 'visible' => $data['visible'],
				 'flg_liquidacion' => $data['flg_liqui'],
				 'simbolo_detalle' => $data['operador'],
				 'valor_detalle' => $data['valor']
 				 );
 		$this->db->where('idplandetalle',$data['iddet']);
		return $this->db->update('plan_detalle', $array);
 	}

 	function plan_anular($id){
 		$array = array(
			'estado_plan' => 0
		);
		$this->db->where('idplan',$id);
		return $this->db->update('plan', $array);
 	}

 	function plan_activar($id){
 		$array = array(
			'estado_plan' => 1
		);
		$this->db->where('idplan',$id);
		return $this->db->update('plan', $array);
 	}

 	function cobertura_anular($id){
 		$array = array(
			'estado_pd' => 0
		);
		$this->db->where('idplandetalle',$id);
		return $this->db->update('plan_detalle', $array);
 	}

 	function cobertura_activar($id){
 		$array = array(
			'estado_pd' => 1
		);
		$this->db->where('idplandetalle',$id);
		return $this->db->update('plan_detalle', $array);
 	}

 	function selecionar_cobertura($id){
 		$this->db->select("*");
 		$this->db->from("plan_detalle");
 		$this->db->where("idplandetalle",$id);
 	$cobertura = $this->db->get();
	return $cobertura->result();
 	}
}
?>