<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atenciones_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('atencion_mdl');

    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month2, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;


			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	

			$ordenes = $this->atencion_mdl->getAtenciones($data);
			$data['ordenes'] = $ordenes;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();


			$this->load->view('dsb/html/atencion/atenciones.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function consultar_atenciones_buscar(){

		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;


			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	

			$ordenes = $this->atencion_mdl->getAtenciones($data);
			$data['ordenes'] = $ordenes;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();


			$this->load->view('dsb/html/atencion/atenciones.php',$data);
		}
		else{
			redirect('/');
		}			
	}

	public function atenciones($aseg_id)
	{
		 
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;


			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	


			$ordenes = $this->atencion_mdl->getAtenciones_asegurado($aseg_id);
			$data['ordenes'] = $ordenes;		

			$preorden = $this->atencion_mdl->getPreOrden_asegurado($aseg_id);
			$data['preorden'] = $preorden;

			
			$this->load->view('dsb/html/atencion/atenciones.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	

	public function orden($id,$est)
	{
		$orden = $this->atencion_mdl->orden($id,$est);
		redirect ('index.php/atenciones');
	}

	public function getPlanesDni(){
		$dni = $_POST['dni'];
		$hoy = date('Y-m-d');

		$datos = $this->atencion_mdl->getDatosDni($dni);

		$options="";

		if(!empty($datos)){
			foreach ($datos as $d) {
			$aseg_id = $d->aseg_id;
			$apellidos = $d->apellidos;
			$nombres = $d->nombres;
			}

			$proveedor = $this->atencion_mdl->getProveedores();
			$planes = $this->atencion_mdl->getPlanes($dni);

			$options.='<div class="row">
						 <div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Nombres:</b>
								<input class="form-control" type="text" name="aseg_nom1" id="aseg_nom1" value="'.$nombres.'" disabled/>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Apellidos:</b>
								<input class="form-control" type="text" name="aseg_ape1" id="aseg_ape1" value="'.$apellidos.'" disabled/>            
							</div>	
						</div>
					</div>
					<div class="row">
						 <div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Plan:</b>
								<select class="form-control" id="plan" name="plan"  required="Seleccionar una opción de la lista">
									<option>Seleccionar</option>';
								foreach ($planes as $pl) {
							$options.='<option value="'.$pl->certase_id.'">'.$pl->nombre_plan.'</option>';
								}
					$options.=' </select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Centro Médico:</b>
								<select class="form-control" id="proveedor" name="proveedor" required="Seleccionar una opción de la lista">
									<option>Seleccionar</option>';

								foreach ($proveedor as $p) {
							$options.='<option value="'.$p->idproveedor.'">'.$p->nombre_comercial_pr.'</option>';
								}

					$options.='	</select>            
							</div>	
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Especialidad:</b>
								<select class="form-control" id="especialidad" name="especialidad"  required="Seleccionar una opción de la lista">
									<option>Seleccionar</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<b class="text-primary">Fecha:</b>
								<input class="form-control" type="date" name="fecha" id="fecha" value="" max="'.$hoy.'" required/>            
							</div>	
						</div>
					</div>
					<div class="row">
						 <div class="col-sm-6">
							<input class="btn btn-info" name="enviar" type="submit" value="Guardar">
						 </div>
					</div>

					<script type="text/javascript">
					$(document).ready(function(){
				       $("#plan").change(function () {
				               $("#plan option:selected").each(function () {
				                plan=$("#plan").val();
				                $.post("'.base_url().'index.php/especialidadPlan", { plan: plan}, function(data){
				                $("#especialidad").html(data);
				                });            
				            });
				       })
				    });
					</script>';
		}else{
			$options.='<div>No se encontraron coincidencias con el DNI ingresado.</div>';
		}	
		echo $options;
	}

	public function especialidadPlan(){
		$certase_id = $_POST['plan'];
		$especialidad = $this->atencion_mdl->getEspecialidad($certase_id);

		$options = '<option>Seleccionar</option>';
		foreach ($especialidad as $e) {
			$options.='<option value="'.$e->idespecialidad.'">'.$e->descripcion_prod.'</option>';
		}
		echo $options;
	}

	public function reg_siniestro(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$certase_id = $_POST['plan'];
		$datos =  $this->atencion_mdl->certase_id($certase_id);
			foreach ($datos as $d) {
				$aseg_id = $d->aseg_id;
				$cert_id = $d->cert_id;
			}

		$data['aseg_id'] = $aseg_id;
		$data['cert_id'] = $cert_id;
		$historia = $this->atencion_mdl->historia($aseg_id);
		if(empty($historia)){
			$this->atencion_mdl->inHistoria($aseg_id);
			$data['historia'] = $this->db->insert_id();
		}else{
			foreach ($historia as $h) {
			$data['historia'] = $h->idhistoria;
			}
		}
		
		$data['idproveedor'] = $_POST['proveedor'];
		$data['fecha'] = $_POST['fecha'];
		$data['idespecialidad'] = $_POST['especialidad'];
		$num = $this->atencion_mdl->num_orden_atencion();
			foreach ($num as $n) {
				$numero=$n->num_orden_atencion;
				$data['num'] = $numero;
			}

		$this->atencion_mdl->reg_siniestro($data);
		echo "<script>
				alert('Se registró la atención con éxito');
				location.href='".base_url()."index.php/atenciones';
				</script>";
	}

	public function anular_siniestro($id, $num){
		$user = $this->session->userdata('user');
		extract($user);

		$eventos = $this->atencion_mdl->getEventos($id);

		$this->atencion_mdl->anular_siniestro($id,$idusuario);

		foreach ($eventos as $e) {
			$vez_actual = $e->vez_actual;
			$data['vez_actual'] = $vez_actual-1;
			$data['idperiodo'] = $e->idperiodo;
			$this->atencion_mdl->upPeriodo($data);
		}
		echo "<script>
				alert('Se anuló la atención OA".$num." con éxito');
				location.href='".base_url()."index.php/atenciones';
				</script>";
	}

	public function reactivar_siniestro($id, $num){
		$user = $this->session->userdata('user');
		extract($user);
		$data['id'] = $id;
		$data['idusuario'] = $idusuario;
		$data['hoy'] = date('Y-m-d');
		$fechas = $this->atencion_mdl->getFechas($data);
		foreach ($fechas as $f) {
			$data['fecha'] = $f->fecha_atencion;
		}
		$this->atencion_mdl->reactivar_siniestro($data);
		echo "<script>
				alert('Se reactivó la atención OA".$num." con éxito');
				location.href='".base_url()."index.php/atenciones';
				</script>";
	}

	public function restablecer_siniestro($id, $num){
		$data['id'] = $id;
		$data['hoy'] = date('Y-m-d');
		$fechas = $this->atencion_mdl->getFechas($data);
		foreach ($fechas as $f) {
			$data['fecha'] = $f->fecha_atencion;
			$data['fecha_act'] = $f->fecha_atencion_act;
		}
		$this->atencion_mdl->restablecer_siniestro($data);
		echo "<script>
				alert('Se restableció la atención OA".$num." con éxito');
				location.href='".base_url()."index.php/atenciones';
				</script>";
	}

	public function nueva_orden(){
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	

			$this->load->view('dsb/html/atencion/crear_atencion.php',$data);
		}
		else{
			redirect('/');
		}			
	}

	public function reg_siniestro2(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$certase_id = $_POST['plan'];
		$datos =  $this->atencion_mdl->certase_id($certase_id);
			foreach ($datos as $d) {
				$aseg_id = $d->aseg_id;
				$cert_id = $d->cert_id;
			}

		$data['aseg_id'] = $aseg_id;
		$data['cert_id'] = $cert_id;
		$historia = $this->atencion_mdl->historia($aseg_id);
		if(empty($historia)){
			$this->atencion_mdl->inHistoria($aseg_id);
			$data['historia'] = $this->db->insert_id();
		}else{
			foreach ($historia as $h) {
			$data['historia'] = $h->idhistoria;
			}
		}
		
		$data['idproveedor'] = $_POST['proveedor'];
		$data['fecha'] = $_POST['fecha'];
		$data['idespecialidad'] = $_POST['especialidad'];
		$num = $this->atencion_mdl->num_orden_atencion();
			foreach ($num as $n) {
				$numero=$n->num_orden_atencion;
				$data['num'] = $numero;
			}

		$this->atencion_mdl->reg_siniestro($data);
		echo "<script>
				alert('Se registró la atención con éxito');							
				location.href='".base_url()."index.php/mesa_partes2/".$numero."';	
				</script>";
	}
}