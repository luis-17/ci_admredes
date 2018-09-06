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

		       	if($data['idtriaje']==""){	       		

			 		$this->siniestro_mdl->guardaTriaje($data);

		       	}else{

		       		$this->siniestro_mdl->updateTriaje($data);

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


			$this->load->view('dsb/html/atencion/siniestro.php',$data);			
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


			$this->load->view('dsb/html/atencion/test.php',$data);
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
		
		
			//recogemos los datos obtenidos por POST

			if($_POST['presscheck']==0){

				$data['presscheck'] = $_POST['presscheck'];
				$data['proveedorPrin'] = $_POST['proveedorPrin'];			
				$data['numFact'] = $_POST['numFact'];
				$data['pago0'] = $_POST['pago0'];
				$data['neto1'] = $_POST['neto1'];
				$data['neto2'] = $_POST['neto2'];
				$data['neto3'] = $_POST['neto3'];
				$data['neto4'] = $_POST['neto4'];
				$data['idsiniestro'] = $_POST['idsiniestro'];
				$data['total_neto'] = $_POST['total_neto'];

				$this->siniestro_mdl->guardaLiquidacion($data);
				

				/*$insert = $this->db->insert_id();
				$data['liquidacionId'] = $insert;
				$this->siniestro_mdl->guardaLiquidacionDeta($data);*/

				//$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);

				redirect(base_url()."index.php/siniestro/".$data['idsiniestro']);


			}else if($_POST['presscheck']==1){
				
				$data['presscheck'] = $_POST['presscheck'];
				$data['idsiniestro'] = $_POST['idsiniestro'];
				$data['total_neto'] = $_POST['total_neto'];

				$data['neto1'] = $_POST['neto1'];
				$data['proveedor1'] = $_POST['proveedor1'];
				$data['factura1'] = $_POST['factura1'];
				$data['pago1'] = $_POST['pago1'];

				$data['neto2'] = $_POST['neto2'];
				$data['proveedor2'] = $_POST['proveedor2'];
				$data['factura2'] = $_POST['factura2'];
				$data['pago2'] = $_POST['pago2'];

				$data['neto3'] = $_POST['neto3'];
				$data['proveedor3'] = $_POST['proveedor3'];
				$data['factura3'] = $_POST['factura3'];
				$data['pago3'] = $_POST['pago3'];

				$data['neto4'] = $_POST['neto4'];
				$data['proveedor4'] = $_POST['proveedor4'];
				$data['factura4'] = $_POST['factura4'];
				$data['pago4'] = $_POST['pago4'];

				$this->siniestro_mdl->guardaLiquidacion($data);
			}

		    
			//$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);
			//$this->load->view('dsb/html/atencion/test.php',$data);
			redirect(base_url()."index.php/siniestro/".$data['idsiniestro']);
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


	

}