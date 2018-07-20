<?php
 class Plan_mdl extends CI_Model {

 function Plan_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function count_all()
     {
      $query = $this->db->get("plan");
      return $query->num_rows();
     }

 	function getPlanes($limit, $start){
 		$output = '';
 		$this->db->select('*');
 		$this->db->from('plan pl');
 		$this->db->join('cliente_empresa ce','ce.idclienteempresa=pl.idclienteempresa');
 		$this->db->order_by("nombre_comercial_cli, nombre_plan");

	 $planes = $this->db->get();
	 $output .= '
     <div class="col-xs-12">
		<table id="simple-table" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Cliente</th>
						<th>Plan</th>
						<th>Carencia</th>
						<th>Mora</th>
						<th>Atención</th>
						<th>Prima</th>
						<th>Estado</th>
						<th>
					</th>
				</tr>
			</thead>

		<tbody>
      ';
      foreach($planes->result() as $p)
      {
      	if($p->estado_plan==1):
			$estado='Activo';
			$titulo='Anular Plan';
			$boton='ace-icon glyphicon glyphicon-trash';
			$funcion='plan_anular';
		else:
			$estado='Inactivo';
			$titulo='Reactivar Plan';
			$boton='ace-icon glyphicon glyphicon-ok';
			$funcion='plan_activar';
		endif;
      
       $output .= '
       <tr>
			<td>'.$p->nombre_comercial_cli.'</td>
			<td>'.$p->nombre_plan.'</td>
			<td>'.$p->dias_carencia.' días</td>
			<td>'.$p->dias_mora.' días</td>
			<td>cada '.$p->dias_atencion.' días</td>
			<td>S/.'.$p->prima_monto.'</td>
			<td>'.$estado.'</td>
			<td>
				<div class="hidden-sm hidden-xs btn-group">
					<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
					<a class="boton fancybox" href="'.base_url().'plan_cobertura/'.$p->idplan.'/'.$p->nombre_plan.'" data-fancybox-width="950" data-fancybox-height="690">
						<i class="ace-icon fa fa-eye bigger-120"></i>
					</a>
				</div>
				<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
					&nbsp;<a href="'.base_url().'plan_editar/'.$p->idplan.'/'.$p->nombre_plan.'">
						<i class="ace-icon fa fa-pencil bigger-120"></i>
					</a>
				</div>
				<div title="'.$titulo.'" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
					&nbsp;<a href="'.base_url().''.$funcion.'/'.$p->idplan.'">
						<i class="'.$boton.'"></i>
					</a>
				</div>
				</div>

					<div class="hidden-md hidden-lg">
						<div class="inline pos-rel">
							<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
								<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
							</button>

							<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
									<li>
										<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											<a class="boton fancybox" href="'.base_url().'plan_cobertura/'.$p->idplan.'/'.$p->nombre_plan.'" data-fancybox-width="950" data-fancybox-height="690">
												<i class="ace-icon fa fa-eye bigger-120"></i>
											</a>
										</div>
									</li>
									<li>
										<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a href="'.base_url().'plan_editar/'.$p->idplan.'/'.$p->nombre_plan.'">
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>
										</div>
									</li>
									<li>
										<div title="'.$titulo.'" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a href="'.base_url().''.$funcion.'/'.$p->idplan.'">
												<i class="'.$boton.'"></i>
											</a>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
       ';
      }
      $output .= '</tbody>
				</table>
			</div>';
      return $output;
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