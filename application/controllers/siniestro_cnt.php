<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siniestro_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('siniestro_mdl');
        $this->load->helper('form'); 
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

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;		

			$this->load->view('dsb/html/atencion/siniestro.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function siniestro($id)
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

			//buscamos idasegurado en siniestro
		    
		    $buscaIdAsegurado = $this->siniestro_mdl->getIdAseguradoOnSiniestro($id);
		    $num = $this->siniestro_mdl->num_orden($id);
		    foreach ($num as $n) {
		    	$data['num_orden'] = $n->num_orden_atencion;
		    }
		    //$data['idasegurado'] = $buscaIdAsegurado['idasegurado'];


			$triaje = $this->siniestro_mdl->getTriaje($id);
			

			 if ($triaje){	       
		       	$data['idtriaje'] = $triaje['idtriaje'];
		       	$data['idasegurado'] = $buscaIdAsegurado['idasegurado'];
		       	$data['idsiniestro'] = $triaje['idsiniestro'];
		       	$data['motivo_consulta'] = $triaje['motivo_consulta'];
		       	$data['presion_arterial_mm'] = $triaje['presion_arterial_mm'];
		       	$data['frec_cardiaca'] = $triaje['frec_cardiaca'];
		       	$data['frec_respiratoria'] = $triaje['frec_respiratoria'];
		       	$data['peso'] = $triaje['peso'];
		       	$data['talla'] = $triaje['talla'];
				$data['estado_cabeza'] = $triaje['estado_cabeza'];			
		       	$data['piel_faneras'] = $triaje['piel_faneras'];	       	
		       	$data['cv_ruido_cardiaco'] = $triaje['cv_ruido_cardiaco'];
		       	$data['tp_murmullo_vesicular'] = $triaje['tp_murmullo_vesicular'];
		       	$data['estado_abdomen'] = $triaje['estado_abdomen'];	       	
		       	$data['ruido_hidroaereo'] = $triaje['ruido_hidroaereo'];
		       	$data['estado_neurologico'] = $triaje['estado_neurologico'];
		       	$data['estado_osteomuscular'] = $triaje['estado_osteomuscular'];
		       	$data['gu_puno_percusion_lumbar'] = $triaje['gu_puno_percusion_lumbar'];
		       	$data['gu_puntos_reno_uretelares'] = $triaje['gu_puntos_reno_uretelares'];
		       	$data['idespecialidad'] = $triaje['idespecialidad'];
		     }else{
		     	$data['idtriaje'] = "";
		       	$data['idasegurado'] = $buscaIdAsegurado['idasegurado'];
		       	$data['idsiniestro'] = $id;
		       	$data['motivo_consulta'] = "";
		       	$data['presion_arterial_mm'] = "";
		       	$data['frec_cardiaca'] = "";
		       	$data['frec_respiratoria'] = "";
		       	$data['peso'] = "";
		       	$data['talla'] = "";
				$data['estado_cabeza'] = "";			
		       	$data['piel_faneras'] = "";	       	
		       	$data['cv_ruido_cardiaco'] = "";
		       	$data['tp_murmullo_vesicular'] = "";
		       	$data['estado_abdomen'] = "";	       	
		       	$data['ruido_hidroaereo'] = "";
		       	$data['estado_neurologico'] = "";
		       	$data['estado_osteomuscular'] = "";
		       	$data['gu_puno_percusion_lumbar'] = "";
		       	$data['gu_puntos_reno_uretelares'] = "";
		       	$data['idespecialidad'] = "";
		     }

		     $diagnostico = $this->siniestro_mdl->getDiagnostico($id);	
			 $data['diagnostico'] = $diagnostico;

			 $medicamento = $this->siniestro_mdl->getMedicamento($id);	
			 $data['medicamento'] = $medicamento;	

			 $laboratorio = $this->siniestro_mdl->getLaboratorio($id);	
			 $data['laboratorio'] = $laboratorio;

			$infoSiniestro = $this->siniestro_mdl->getInfoSiniestro($id);
			$data['infoSiniestro'] = $infoSiniestro;

			$nomPlan = $this->siniestro_mdl->getPlan($id);
			
			if ($nomPlan){	       
		       	$data['plan_id'] = $nomPlan['plan_id'];
		       }

			//datos para calculo de especialidad

			$calEspe = $this->siniestro_mdl->detalle_plan($id);	
			 
			/* if ($calEspe){
			    $data['idhistoria'] = $calEspe['idhistoria'];
			}*/

			// datos para combo especialidad        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo proveedor
	        $this->load->model('atencion_mdl');
	        $data['proveedor'] = $this->atencion_mdl->getProveedor();

	        $variable = $this->siniestro_mdl->getVariable($id);
	        $data['variable'] = $variable;

	        $texto = '<div style=" width: 45%">
	        			<h4 class="blue">
	        			<i class="ace-icon fa fa-leaf bigger-110"></i>
							Otros Servicios
						</h4>
					</div>
					<div class="space-8"></div>
								<div id="faq-list-3" class="panel-group accordion-style1 accordion-style2">';
	        $cont = 1;
	        $servicios = $this->siniestro_mdl->getVariables_sin($id);
	        $ida=0;
	        $desc="";
	        foreach ($servicios as $s) {
	        	$producto_analisis = $this->siniestro_mdl->getProductos_analisis($s->idplandetalle,$s->idsiniestro);
	        	$analisis_nocubiertos = $this->siniestro_mdl->getAnalisisNo($s->idsiniestro,$s->idplandetalle);
	        	foreach ($analisis_nocubiertos as $nc) {
	        		$ida=$nc->idsiniestroanalisis;
	        		$desc=$nc->analisis_str;
	        	}
	        	$id=$s->idvariableplan;
	        	$tot=$s->total_vez;
	        	$act=$s->vez_actual;
	        	if($tot=='Ilimitados'){
	        		$disabled="";
	        	}elseif($tot>$act){
	        		$disabled="";
	        	}else{
	        		$disabled="disabled";
	        	}

	        	$texto .= '<div class="panel panel-default">
							<div class="panel-heading">
								<a href="#faq-3-'.$cont.'" data-parent="#faq-list-3" data-toggle="collapse" class="accordion-toggle collapsed">
									<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
										'.$s->nombre_var.'
								</a>
							</div>

							<div class="panel-collapse collapse" id="faq-3-'.$cont.'">
								<div class="panel-body">
									<!-- star table -->	
									<div style="float: left;">
										<label>Número de Eventos </label> <button class="btn btn-white btn-danger btn-sm">'.$s->vez_actual2.''.$s->total_vez.'</button>
									</div>	
									<br><br>
									<div class="col-xs-12">
										<div class="form-group">
											<form method="post" action="'.base_url().'index.php/save_siniestro_analisis">
												<input type="hidden" name="sin_id" id="sin_id" value="'.$s->idsiniestro.'">
												<input type="hidden" name="idplandetalle" value="'.$s->idplandetalle.'" id="idplandetalle">
												<table id="example'.$s->idplandetalle.'" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>ID</th>
															<th>Descripción</th>
															<th>Tipo</th>
															<th></th>
														</tr>
													</thead>
													<tbody>';
													foreach ($producto_analisis as $pr) {
												$texto.='<tr>
															<td>'.$pr->idproducto.'</td>
															<td>'.$pr->descripcion_prod.'</td>
															<td>Cubierto</td>
															<td><input type="checkbox" name="chk'.$s->idplandetalle.'[]" id="chk'.$s->idplandetalle.'[]" value="'.$pr->idproducto.'" '.$pr->checked.' '.$disabled.'></td>
														</tr>';
																	}
											$texto.='</tbody>
												</table>
											</div>
											<script>	
												$(document).ready(function() {
													$("#example'.$s->idplandetalle.'").DataTable( {
														"pagingType": "full_numbers"
													} );
													} );
											</script>
										</div>
										<div class="form-group">
											<input type="hidden" name="idnc'.$s->idplandetalle.'" id="idnc'.$s->idplandetalle.'" value='.$ida.' >
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1" align="right">No cubierto: </label>
											<div class="col-sm-6" align="left">
												<input class="form-control input-mask-date" type="text" name="servicio'.$s->idplandetalle.'" id="servicio'.$s->idplandetalle.'" value="'.$desc.'" placeholder="'.$s->nombre_var.' no cubierto por el plan" />
											</div>
										</div>
										<br><br><br>
										<div align="right"><button class="btn btn-info" type="submit">Guardar</button></div>
										</form>
									<!-- end table --> 
									</div>
								</div>
							</div>';
				$cont = $cont+1;
	        }
	        $texto.='</div>';
	        $data['texto'] = $texto;

	        
			$this->load->view('dsb/html/atencion/siniestro.php',$data);
		}
		else{
			redirect('/');
		}
	}



	public function guardaTriaje()
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
		
			//recogemos los datos obtenidos por POST

		       	$data['idtriaje'] = $_POST['idtriaje'];
		       	$data['idasegurado'] = $_POST['idasegurado'];
		       	$data['idsiniestro'] = $_POST['idsiniestro'];
		       	$data['motivo_consulta'] = $_POST['motivo_consulta'];
		       	$data['presion_arterial_mm'] = $_POST['presion_arterial_mm'];
		       	$data['frec_cardiaca'] = $_POST['frec_cardiaca'];
		       	$data['frec_respiratoria'] = $_POST['frec_respiratoria'];
		       	$data['peso'] = $_POST['peso'];
		       	$data['talla'] = $_POST['talla'];
				$data['estado_cabeza'] = $_POST['estado_cabeza'];			
		       	$data['piel_faneras'] = $_POST['piel_faneras'];	       	
		       	$data['cv_ruido_cardiaco'] = $_POST['cv_ruido_cardiaco'];
		       	$data['tp_murmullo_vesicular'] = $_POST['tp_murmullo_vesicular'];
		       	$data['estado_abdomen'] = $_POST['estado_abdomen'];	       	
		       	$data['ruido_hidroaereo'] = $_POST['ruido_hidroaereo'];
		       	$data['estado_neurologico'] = $_POST['estado_neurologico'];
		       	$data['estado_osteomuscular'] = $_POST['estado_osteomuscular'];
		       	$data['gu_puno_percusion_lumbar'] = $_POST['gu_puno_percusion_lumbar'];
		       	$data['gu_puntos_reno_uretelares'] = $_POST['gu_puntos_reno_uretelares'];
		       	$data['idespecialidad'] = $_POST['idespecialidad'];
		       	$data['num_orden'] = $_POST['num_oa'];

		       	if($data['idtriaje']==""){	       		

			 		$this->siniestro_mdl->guardaTriaje($data);
			 		$this->siniestro_mdl->updateSiniestro_tria($data);

		       	}else{

		       		$this->siniestro_mdl->updateTriaje($data);
		       		$this->siniestro_mdl->updateSiniestro_tria($data);

		       	}
		    
		     $id=$data['idsiniestro']; 

		     //$triaje = $this->siniestro_mdl->getTriaje($id);

		     $diagnostico = $this->siniestro_mdl->getDiagnostico($id);	
			 $data['diagnostico'] = $diagnostico;

			 $medicamento = $this->siniestro_mdl->getMedicamento($id);	
			 $data['medicamento'] = $medicamento;	

			 $laboratorio = $this->siniestro_mdl->getLaboratorio($id);	
			 $data['laboratorio'] = $laboratorio;



			$infoSiniestro = $this->siniestro_mdl->getInfoSiniestro($id);
			$data['infoSiniestro'] = $infoSiniestro;

			$nomPlan = $this->siniestro_mdl->getPlan($id);
			
			if ($nomPlan){	       
		       	$data['plan_id'] = $nomPlan['plan_id'];
		       }

			// datos para combo especialidad        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	         // datos para combo proveedor
	        $this->load->model('atencion_mdl');
	        $data['proveedor'] = $this->atencion_mdl->getProveedor();

			redirect(base_url()."index.php/siniestro/".$data['idsiniestro']);			
		}
		else{
			redirect('/');
		}	
	}



	public function guardaDiagP()
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
			
			//recogemos los datos obtenidos por POST
			        
		    $data['idsiniestro'] = $_POST['idsiniestro'];
		    $data['dianostico_temp'] = $_POST['dianostico_temp'];

			//en siniestro		 
			$data['sin_estado'] = 2;
			$data['idsiniestro'] = $_POST['idsiniestro'];		
			$this->siniestro_mdl->updateSiniestro_diag($data);

			 //en siniestro_diagnostico
			$data['idsiniestro'] = $_POST['idsiniestro'];
		    $data['dianostico_temp'] = $_POST['dianostico_temp'];		 
			$data['diag_tipo'] = 1;
			$data['es_principal'] = 1;		
			$this->siniestro_mdl->guardaDiagnosticoSin($data);
			//return $insert;
			$insert = $this->db->insert_id();

			 //en tratamiento

			for($num = 1; $num<=15; $num++)
				{				   
				   $data['diag_id']	= $insert;		
				   
				   if (isset($_POST["sin_trat".$num]) AND $_POST["sin_trat".$num]!==null){
				   	$data['idMedi'] = $_POST['idMedi'.$num];
				   	$data['trat_medi'] = $_POST['sin_trat'.$num];
					$data['trat_cant'] = $_POST['sin_cant'.$num];
					$data['trat_dosis'] = $_POST['sin_dosis'.$num];
					//$data['trat_text'] = "";
					$data['trat_tipo'] = 1;
				   $this->siniestro_mdl->guardaTratamiento($data);
				   }			    
				}

			 // guarda tratamiento no cubierto de Diagnostico Principal

			for($num = 1; $num<=15; $num++)
				{ 
				   if (isset($_POST["mediNC".$num]) AND $_POST["mediNC".$num]!==null){
				   	
				   	$data['trat_medi'] = $_POST['mediNC'.$num];
					$data['trat_cant'] = $_POST['cantNC'.$num];
					$data['trat_dosis'] = $_POST['dosisNC'.$num];
					//$data['trat_text'] = " ";
					$data['trat_tipo'] = 3;
					$data['diag_id']	= $insert;				
				    $this->siniestro_mdl->guardaTratamiento($data);

				   }
				}
				
			echo "<script>
				alert('Se registró el medicamento con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
		}
		else{
			redirect('/');
		}
	}


	public function creaSiniestro()
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

			//recogemos los datos obtenidos por POST
			        
		    $data['idasegurado'] = $_POST['aseg_id'];
		    $data['idcertificado'] = $_POST['plan'];
		    $data['idproveedor'] = $_POST['idproveedor'];
		    $data['idespecialidad'] = $_POST['idespecialidad'];
		    $data['fecha_atencion'] = $_POST['fecha_atencion'];

		    //buscamos idhistoria
		    $idasegurado = $_POST['aseg_id'];
		    $buscaHistoria = $this->siniestro_mdl->getHistoria($idasegurado);

			    if ($buscaHistoria){
			    	$data['idhistoria'] = $buscaHistoria['idhistoria'];

			    }else{
			    	$this->siniestro_mdl->guardaHistoria($idasegurado);
				   	$idhistoria = $this->db->insert_id();
				    $data['idhistoria'] = $idhistoria;
				    //$data['idhistoria'] = 1325;
			    }

			 //buscamos idproducto
		    $idespecialidad = $_POST['idespecialidad'];
		    $buscaProducto = $this->siniestro_mdl->getProducto($idespecialidad);

			    if ($buscaProducto){
			    	$data['idproducto'] = $buscaProducto['idproducto'];
			    }

			//asigna número de orden de atención
			$numOA = $this->siniestro_mdl->getNumOA();
			 
			 $antiguoNum=$numOA['num_orden_atencion'];
			 $nuevoNum=$antiguoNum+1;
			 $final=str_pad($nuevoNum, 6, 0, STR_PAD_LEFT);
			 
			$data['num_orden_atencion'] = $final;

			//$data['num_orden_atencion'] = 25;

			$data['idareahospitalaria'] = 1;
			$data['fase_atencion'] = 0;
		    $data['estado_siniestro'] = 1;
		    $data['sin_labFlag'] = 0;
		    $data['estado_atencion'] = "O";

			$this->siniestro_mdl->guardaSiniestro($data);
			$insert = $this->db->insert_id();

			//$this->load->view('dsb/html/atencion/test.php',$data);
			redirect(base_url()."index.php/siniestro/".$insert);
		}
		else{
			redirect('/');
		}
	}

	public function guardaGasto()
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
		
		
			$data['idsiniestro'] = $_POST['sin_id'];
			$data['liq_total'] = $_POST['total'];
			$data['liq_neto'] = $_POST['total_neto'];
			$liq_id = $_POST['liq_id'];
			$cont = $_POST['cont'];
			$q = $_POST['presscheck'];

			if ($_POST['cerrar_atencion']==1){
				$data['estadoliq'] = 1;
			}else{
				$data['estadoliq'] = 0;
			}

			if($liq_id==0){
					$this->siniestro_mdl->save_liquidacion($data);
					$liq_id2=$this->db->insert_id();
					$data['liq_id']=$liq_id2;					
			}else{
				$liq_id2=$liq_id;	
				$data['liq_id']=$liq_id2;
				$this->siniestro_mdl->up_liquidacion($data);			
			}
			

			for($i=0;$i<$cont;$i++){
				$data['idplandetalle'] = $_POST['idplandetalle'.$i];
				$data['detalle_monto'] = $_POST['monto'.$i];
				$data['detalle_neto'] = $_POST['neto'.$i];
				$data['aprov_pago'] = $_POST['aprovpago'.$i];
				$data['idusuario'] = $idusuario;

				$liqdetalleid = $_POST['liqdetalleid'.$i];
				if($q==1){
					$data['idproveedor'] = $_POST['proveedor'.$i];
					$data['num_fac'] = $_POST['factura'.$i];
				}else{
					$data['idproveedor'] = $_POST['proveedorPrin'];
					$data['num_fac'] = $_POST['numFact'];
				}

					if($liqdetalleid==0){
						$this->siniestro_mdl->save_detalleliquidacion($data);
					}else{
						$data['liqdetalleid'] =$liqdetalleid;
						$this->siniestro_mdl->up_detalleliquidacion($data);
					}			

			}

			if($_POST['cerrar_atencion']==1){
				redirect(base_url()."index.php/atenciones");
			}else{
				redirect(base_url()."index.php/siniestro/".$data['idsiniestro']);
			}
		}
		else{
			redirect('/');
		}
	}



	public function guardaDiagS()
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
		
		
			//recogemos los datos obtenidos por POST
			        
		    $data['idsiniestro'] = $_POST['idsiniestro'];
		    $data['dianostico_temp'] = $_POST['sin_diagnosticoSec'];

			//en siniestro		 
			$data['sin_estado'] = 2;
			$data['idsiniestro'] = $_POST['idsiniestro'];		
			$this->siniestro_mdl->updateSiniestro_diag($data);

			 //en siniestro_diagnostico
			$data['idsiniestro'] = $_POST['idsiniestro'];
		    $data['dianostico_temp'] = $_POST['sin_diagnosticoSec'];		 
			$data['diag_tipo'] = 3;
			$data['es_principal'] = 2;		
			$this->siniestro_mdl->guardaDiagnosticoSin($data);
			//return $insert;
			$insert = $this->db->insert_id();
			 

			 //en tratamiento
			
			 // guarda tratamiento no de Diagnostico Secundario

			for($num = 1; $num<=15; $num++)
				{ 
				   if (isset($_POST["mediNC".$num]) AND $_POST["mediNC".$num]!==null){
				   	
				   	$data['trat_medi'] = $_POST['mediNC'.$num];
					$data['trat_cant'] = $_POST['cantNC'.$num];
					$data['trat_dosis'] = $_POST['dosisNC'.$num];
					//$data['trat_text'] = " ";
					$data['trat_tipo'] = 3;
					$data['diag_id']	= $insert;				
				    $this->siniestro_mdl->guardaTratamiento($data);

				   }
				}


			$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function guardaMediP()
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
		
			//recogemos los datos obtenidos por POST
			        
		    $data['idsiniestrodiagnostico'] = $_POST['idsiniestrodiagnostico'];
		    $data['dianostico_temp'] = $_POST['dianostico_temp'];


			 //en tratamiento

			for($num = 1; $num<=15; $num++)
				{				   
				   $data['diag_id']	= $_POST['idsiniestrodiagnostico'];		
				   
				   if (isset($_POST["sin_trat".$num]) AND $_POST["sin_trat".$num]!==null){
				   	$data['idMedi'] = $_POST['idMedi'.$num];
				   	$data['trat_medi'] = $_POST['sin_trat'.$num];
					$data['trat_cant'] = $_POST['sin_cant'.$num];
					$data['trat_dosis'] = $_POST['sin_dosis'.$num];
					//$data['trat_text'] = "";
					$data['trat_tipo'] = 1;
				   $this->siniestro_mdl->guardaTratamiento($data);

				   }			    
				}

			 // guarda tratamiento no cubierto de Diagnostico Principal

			for($num = 1; $num<=15; $num++)
				{ 
				   if (isset($_POST["mediNC".$num]) AND $_POST["mediNC".$num]!==null){
				   	
				   	$data['trat_medi'] = $_POST['mediNC'.$num];
					$data['trat_cant'] = $_POST['cantNC'.$num];
					$data['trat_dosis'] = $_POST['dosisNC'.$num];
					//$data['trat_text'] = " ";
					$data['trat_tipo'] = 3;
					$data['diag_id']	= $_POST['idsiniestrodiagnostico'];				
				    $this->siniestro_mdl->guardaTratamiento($data);

				   }
				}


			$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function guardaMediS()
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
		
			//recogemos los datos obtenidos por POST
			        
		    $data['idsiniestrodiagnostico'] = $_POST['idsiniestrodiagnostico'];
		    $data['dianostico_temp'] = $_POST['dianostico_temp'];


			 //en tratamiento		

			 // guarda tratamiento no cubierto de Diagnostico Secundario

			for($num = 1; $num<=15; $num++)
				{ 
				   if (isset($_POST["mediNC".$num]) AND $_POST["mediNC".$num]!==null){
				   
				   	$data['trat_medi'] = $_POST['mediNC'.$num];
					$data['trat_cant'] = $_POST['cantNC'.$num];
					$data['trat_dosis'] = $_POST['dosisNC'.$num];
					//$data['trat_text'] = " ";
					$data['trat_tipo'] = 3;
					$data['diag_id']	= $_POST['idsiniestrodiagnostico'];				
				    $this->siniestro_mdl->guardaTratamiento($data);

				   }
				}
				
			$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function edit_medi($idtratamiento, $idsiniestrodiagnostico)
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
		
			//asignamos los datos  

		    $data['idtratamiento'] = $idtratamiento;        
		    $data['idsiniestrodiagnostico'] = $idsiniestrodiagnostico; 
		    
			 //cargamos siniestroDiagnostico

		    $sinDiag = $this->siniestro_mdl->getSiniestroDiagnostico($idsiniestrodiagnostico);
			
			 if ($sinDiag)
		       {
		       	$data['idsiniestrodiagnostico'] = $sinDiag['idsiniestrodiagnostico'];
		       	$data['dianostico_temp'] = $sinDiag['dianostico_temp'];	       	
		       }

			 //cargamos el tratamiento elegido

		    $trata = $this->siniestro_mdl->getTratamiento($idtratamiento);
			
				if ($trata)
		       {
		       	
			       	$data['idtratamiento'] = $trata['idtratamiento'];
			       	$data['idmedicamento'] = $trata['idmedicamento'];
			       	$data['medicamento_temp'] = $trata['medicamento_temp'];
			       	$data['cantidad_trat'] = $trata['cantidad_trat'];
			       	$data['dosis_trat'] = $trata['dosis_trat'];
			       	$data['tipo_tratamiento'] = $trata['tipo_tratamiento'];
			           	
		       }

		     //cargamos los medicamento para el diagnostico
		       $dianostico_temp = $sinDiag['dianostico_temp'];

		        $cadena_buscada = ":";
				//buscamos posicion de :
				$posicion_coincidencia = strpos($dianostico_temp, $cadena_buscada, 0);
				$resultado = substr($dianostico_temp, 4, ($posicion_coincidencia-4));

		       $medixdiag= $this->siniestro_mdl->getMediforDiag($resultado);	
			   $data['medicamento'] = $medixdiag;


			$this->load->view('dsb/html/atencion/edit_medicamento.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function guardaEditMed()
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
		
			//recogemos los datos obtenidos por POST
			        
		    $data['idsiniestrodiagnostico'] = $_POST['idsiniestrodiagnostico'];
		    $data['dianostico_temp'] = $_POST['dianostico_temp'];
		    $data['medicamento_temp'] = $_POST['medicamento_temp'];
		    $data['idtratamiento'] = $_POST['idtratamiento'];

		    $data['idmedi'] = $_POST['medi'];
		    $data['cant'] = $_POST['cant'];
		    $data['dosis'] = $_POST['dosis'];
		    $data['tipo'] = $_POST['tipo'];

		    //actualizamos el tratamiento

		    $this->siniestro_mdl->updateTratamiento($data);
			//$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}	
	}


	public function delete_trata($idtratamiento,$idsiniestro){
		
		$data['idtratamiento'] = $idtratamiento;
		$data['idsiniestro'] = $idsiniestro;

		$this->siniestro_mdl->deleteTratamiento($data);

		 $this->siniestro($data['idsiniestro']);
		//$this->load->view('dsb/html/atencion/add_diagnostico.php', $data);
	}


	public function add_diagnostico($idsiniestro){

		//$atenciones = $this->certificado_mdl->getAtenciones($id);
		//$data['atenciones'] = $atenciones;
		$data['idsiniestro'] = $idsiniestro;	

		$this->load->view('dsb/html/atencion/add_diagnostico.php', $data);
	}

	public function add_diagnosticoSec($idsiniestro){

		//$atenciones = $this->certificado_mdl->getAtenciones($id);
		//$data['atenciones'] = $atenciones;
		$data['idsiniestro'] = $idsiniestro;	

		$this->load->view('dsb/html/atencion/add_diagnosticoSec.php', $data);
	}

	public function add_tratamiento($idsiniestrodiagnostico){

		$sinDiag = $this->siniestro_mdl->getSiniestroDiagnostico($idsiniestrodiagnostico);
		//$data['sinDiag'] = $sinDiag;
		 if ($sinDiag)
	       {
	       	$data['idsiniestrodiagnostico'] = $sinDiag['idsiniestrodiagnostico'];
	       	$data['dianostico_temp'] = $sinDiag['dianostico_temp'];
	       	
	       }

		$data['idsiniestrodiagnostico'] = $idsiniestrodiagnostico;	

		$this->load->view('dsb/html/atencion/add_tratamiento.php', $data);
	}


	public function add_tratamientoSec($idsiniestrodiagnostico){

		$sinDiag = $this->siniestro_mdl->getSiniestroDiagnostico($idsiniestrodiagnostico);
		//$data['sinDiag'] = $sinDiag;
		 if ($sinDiag)
	       {
	       	$data['idsiniestrodiagnostico'] = $sinDiag['idsiniestrodiagnostico'];
	       	$data['dianostico_temp'] = $sinDiag['dianostico_temp'];
	       	
	       }

		$data['idsiniestrodiagnostico'] = $idsiniestrodiagnostico;	

		$this->load->view('dsb/html/atencion/add_tratamientoSec.php', $data);
	}
	
	public function save_siniestro_analisis(){
		$data['sin_id'] = $_POST['sin_id'];
		$idplandetalle = $_POST['idplandetalle'];
		$data['idplandetalle'] = $idplandetalle;
		$idnc = $_POST['idnc'.$idplandetalle];
		$data['servicio'] = $_POST['servicio'.$idplandetalle];

		$cert = $this->siniestro_mdl->getCert($data);

					$data['cert'] = "";
					$vez_actual = 0;
					$cant = 0;
					$cant_tot = 0;

			if(!empty($cert)){
				foreach ($cert as $c) {
					$data['cert'] = $c->cert_id;
					$vez_actual = $c->vez_actual;
					$cant = $c->cant;
					$cant_tot = $c->cant_tot;
					$data['certase_id'] = $c->certase_id;
				}
			}

		if(!empty($_POST['chk'.$idplandetalle])){

				if($cant_tot==0){
				$data['vez_actual'] = $vez_actual;
				}elseif($cant==0){
					$data['vez_actual'] = $vez_actual;
				}else{
					$data['vez_actual'] = $vez_actual-1;
				}	

			$chk = $_POST['chk'.$idplandetalle];		
			$num = count($chk);

			for($i=0;$i<$num;$i++){
				$data['idpr']=$chk[$i];
				$accion = $this->siniestro_mdl->validarProd($data); 
				if(empty($accion)){
					$this->siniestro_mdl->insertar_analisis($data);
				}else{
					$this->siniestro_mdl->activar_analisis($data);
				}
			}

			$this->siniestro_mdl->eliminar_analisis($data);
			$this->siniestro_mdl->actualizar_analisis($data);

			if ($data['servicio']!="") {
				if($idnc==0){
					$this->siniestro_mdl->insertar_NC($data);
				}else{
					$data['idnc'] = $idnc;
					$this->siniestro_mdl->update_NC($data);
				}
			}else{
				if($idnc!=0){
					$data['idnc'] = $idnc;
					$this->siniestro_mdl->eliminar_servicio($data);
				}
			}
		

			echo "<script>
					alert('Se registraron los servicios con éxito.');
					location.href='".base_url()."index.php/siniestro/".$data['sin_id']."';
					</script>";

		}elseif ($data['servicio']!="") {
			
			if($idnc==0){
				$this->siniestro_mdl->insertar_NC($data);
			}else{
				$data['idnc'] = $idnc;
				$this->siniestro_mdl->update_NC($data);
			}
			$this->siniestro_mdl->eliminar_todo($data);

				if($cant_tot==0){
				$data['vez_actual'] = $vez_actual-1;
				}else{
					$data['vez_actual'] = $vez_actual-2;
				}

			echo "<script>
					alert('Se registraron los servicios con éxito.');
					location.href='".base_url()."index.php/siniestro/".$data['sin_id']."';
					</script>";

		}else{
			if($idnc!=0){
				$data['idnc'] = $idnc;
				$this->siniestro_mdl->eliminar_servicio($data);
			}

			$this->siniestro_mdl->eliminar_todo($data);

				if($cant_tot==0){
				$data['vez_actual'] = $vez_actual-1;
				}else{
					$data['vez_actual'] = $vez_actual-2;
				}

			echo "<script>
					alert('No se registró ningún servicio para la atención.');
					location.href='".base_url()."index.php/siniestro/".$data['sin_id']."';
					</script>";
		}

		if(!empty($cert)){
			$this->siniestro_mdl->up_periodo_evento($data);
		}
		
	}

	public function eliminar_diagnostico($id, $ids){
		$medicamentos = $this->siniestro_mdl->get_medicamentos($id);

		if(!empty($medicamentos)){
			echo "<script>
					alert('Error: El diagnóstico cuenta con medicamentos registrados.');
					location.href='".base_url()."index.php/siniestro/".$ids."';
					</script>";
		}else{
			$this->siniestro_mdl->eliminar_diagnostico($id);
			echo "<script>
					alert('El diagnóstico se eliminó con éxito.');
					location.href='".base_url()."index.php/siniestro/".$ids."';
					</script>";
		}
	}

	public function registrar_siniestro(){

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

			$data['mesa_partes'] = $this->siniestro_mdl->getMesaPartes();
			$this->load->view('dsb/html/siniestro/siniestro.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function seleccionar_factura($idrecepcion,$idsiniestro){
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

			$factura = $this->siniestro_mdl->getFactura($idrecepcion);	
			$data['tipo_documento'] = $factura['tipo_documento'];
			$data['serie'] = $factura['serie'];
			$data['numero'] = $factura['numero'];
			$data['importe'] = $factura['importe'];
			$data['usuario'] = $factura['username'];
			$data['dni'] = $factura['aseg_numDoc'];
			$data['afiliado'] = $factura['aseg_ape1'].' '.$factura['aseg_ape2'].' '.$factura['aseg_nom1'].' '.$factura['aseg_nom2'];
			$data['ruc'] = $factura['numero_documento_pr'];
			$data['razon_social'] = $factura['razon_social_pr'];
			$data['nombre_comercial'] = $factura['nombre_comercial_pr'];
			$data['fecha_atencion'] = $factura['fecha_atencion'];
			$data['nombre_plan'] = $factura['nombre_plan'];
			$data['nro_orden'] = $factura['num_orden_atencion'];
			$data['coberturas'] = $this->siniestro_mdl->getCoberturasFactura($idsiniestro);

			$this->load->view('dsb/html/siniestro/siniestro_factura.php',$data);
		}
		else{
			redirect('/');
		}
	}
}