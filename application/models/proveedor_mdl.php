<?php
 class proveedor_mdl extends CI_Model {

 function proveedor_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function count_all()
     {
      $query = $this->db->get("proveedor");
      return $query->num_rows();
     }
	 
	function getProveedores($limit, $start) {
	 $output = '';
	 $this->db->select('p.*, ud.descripcion_ubig as dep, up.descripcion_ubig as prov, udi.descripcion_ubig as dist');
	 $this->db->from('proveedor p');
	 $this->db->join("ubigeo ud","cod_departamento_pr=ud.iddepartamento and idprovincia='00' and ud.iddistrito='00'",'left');
	 $this->db->join("ubigeo up","cod_provincia_pr=up.idprovincia and up.iddistrito='00' and up.iddepartamento=ud.iddepartamento",'left');
	 $this->db->join("ubigeo udi","cod_distrito_pr=udi.iddistrito and up.iddepartamento=udi.iddepartamento and udi.idprovincia=up.idprovincia",'left');
	 //$this->db->order_by("p.estado_pr", "asc");
	 $proveedores = $this->db->get();
	 $output .= '
     <div class="col-xs-12">
		<table id="simple-table" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>DNI</th>
						<th>Razón Social</th>
						<th>Nombre Comercial</th>
						<th>Dirección</th>
						<th>Ubigeo</th>
						<th>Estado</th>
						<th>
					</th>
				</tr>
			</thead>
		<tbody>
      ';
      foreach($proveedores->result() as $pr)
      {
      	$dep=$pr->dep;
		$prov=$pr->prov;
		$dist=$pr->dist;
		$direcc=$pr->direccion_pr;
		if($cont==2):
			$estilorow="ui-widget-content jqgrow ui-row-ltr ui-priority-secondary";
			$cont=1;
			else:
				$estilorow="ui-widget-content jqgrow ui-row-ltr";
				$cont++;
		endif;

		if($pr->estado_pr==1):
			$estilo="ui-icon ui-icon-cancel";
			$titulo="Inhabilitar";
			$funcion="inhabilitar_proveedor";
			$estado="Activo";
			else:
				$estilo="ui-icon ui-icon-disk";
				$titulo="Habilitar";
				$funcion="habilitar_proveedor";
				$estado="Inactivo";
		endif;
      
       $output .= '
       <tr>
			<td>'.$pr->numero_documento_pr.'</td>
			<td>'.$pr->razon_social_pr.'</td>
			<td>'.$pr->nombre_comercial_pr.' días</td>
			<td>'.$direcc.' días</td>
			<td>cada '.$dep.'-'.$prov.'-'.$dist.' días</td>
			<td>S/.'.$estado.'</td>
			<td>
				<div class="hidden-sm hidden-xs btn-group">
					<div title="Contactos" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
						<a class="boton fancybox" href="'.base_url().'proveedor_contactos/'.$pr->idproveedor.'/'.$pr->nombre_comercial_pr.'" data-fancybox-width="950" data-fancybox-height="690">
							<i class="ace-icon fa fa-eye bigger-120"></i>
						</a>
					</div>
					<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
						&nbsp;<a href="'.base_url().'proveedor_editar/'.$pr->idproveedor.'/'.$pr->nombre_comercial_pr.'">
							<i class="ace-icon fa fa-pencil bigger-120"></i>
						</a>
					</div>
					<div title="'.$titulo.'" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
						&nbsp;<a href="'.base_url().''.$funcion.'/'.$pr->idproveedor.'">
							<i class="'.$estilo.'"></i>
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
										<div title="Contactos" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											<a class="boton fancybox" href="'.base_url().'proveedor_contactos/'.$pr->idproveedor.'/'.$pr->nombre_comercial_pr.'" data-fancybox-width="950" data-fancybox-height="690">
												<i class="ace-icon fa fa-eye bigger-120"></i>
											</a>
										</div>
									</li>
									<li>
										<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a href="'.base_url().'proveedor_editar/'.$pr->idproveedor.'/'.$pr->nombre_comercial_pr.'">
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>
										</div>
									</li>
									<li>
										<div title="'.$titulo.'" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a href="'.base_url().''.$funcion.'/'.$pr->idproveedor.'">
												<i class="'.$estilo.'"></i>
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

	function habilitar($id){
		$data = array(
			'estado_pr' => 1,
			'updatedat' => date('Y-m-d H:i:s') 
		);
		$this->db->where('idproveedor',$id);
		return $this->db->update('proveedor', $data);
	}

	function inhabilitar($id){
		$data = array(
			'estado_pr' => 0,
			'updatedat' => date('Y-m-d H:i:s') 
		);
		$this->db->where('idproveedor',$id);
		return $this->db->update('proveedor', $data);
	}

	function dataproveedor($id){
		$this->db->select("idproveedor, idtipoproveedor, numero_documento_pr, cod_sunasa_pr, razon_social_pr, nombre_comercial_pr, direccion_pr, referencia_pr, cod_distrito_pr, cod_provincia_pr, cod_departamento_pr, 'editarproveedor' as funcion");
		$this->db->from("proveedor pr");
		$this->db->where("idproveedor",$id);

		$proveedor = $this->db->get();
	 	return $proveedor->result();
	}

	function datatipoproveedor(){
		$this->db->select("tp.idtipoproveedor, descripcion_tpr");
		$this->db->from("tipo_proveedor tp");

		$tipoproveedor = $this->db->get();
	 	return $tipoproveedor->result();
	}

	function departamento(){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo ");
		$this->db->where("idprovincia='00' and iddistrito='00'");

		$departamento = $this->db->get();
	 	return $departamento->result();
	}

	function provincia($id){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo u");
		$this->db->join("proveedor p","u.iddepartamento=p.cod_departamento_pr and  iddistrito='00' and idprovincia!='00'");	
		$this->db->where("iddepartamento",$id);

		$provincia = $this->db->get();
	 	return $provincia->result();
	}
}
?>