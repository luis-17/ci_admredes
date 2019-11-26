<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesa_partes_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('mesa_partes_mdl');     
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month2, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['nro_orden'] = '';
			$data['ruc'] = '';

			$data['datos_orden'] = '';
			$data['datos_ruc'] = '';
			$data['mensaje'] = '<a href="'.base_url().'index.php/nueva_orden" > Crear Orden</a>';
			$data['mensaje2'] = '';
			$data['class1'] = ' class="active"';
			$data['class2'] = '';
			$data['class3'] = '';
			$data['class4'] = '';
			$data['class11'] = 'tab-pane fade in active';
			$data['class21'] = 'tab-pane fade';
			$data['class31'] = 'tab-pane fade';
			$data['class41'] = 'tab-pane fade';

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function consultar_orden()
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['nro_orden'] = $_POST['nro_orden'];

			$datos_orden = $this->mesa_partes_mdl->buscar_orden($data);
			$data['datos_orden'] = $datos_orden;

			if(empty($datos_orden)){
				$data['mensaje'] = 'No se encontraron resultados.<a href="'.base_url().'index.php/nueva_orden"> Crear Orden</a>';
			}else{
				$data['mensaje'] = '';
			}

			$data['ruc'] = '';
			$data['datos_ruc'] = '';
			$data['mensaje2'] = '';

			$data['class1'] = ' class="active"';
			$data['class2'] = '';			
			$data['class3'] = '';
			$data['class4'] = '';
			$data['class11'] = 'tab-pane fade in active';
			$data['class21'] = 'tab-pane fade';			
			$data['class31'] = 'tab-pane fade';
			$data['class41'] = 'tab-pane fade';

			$data['getProveedores'] = $this->mesa_partes_mdl->getProveedores();

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function guardar_recepcion(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['fecha_recepcion'] = $_POST['recepcion'];
		$data['fecha_emision'] = $_POST['emision'];
		$data['usuario_recepciona'] = $idusuario;
		$data['tipodoc'] =  $_POST['tipodoc'];
		$data['serie'] = $_POST['serie'];
		$data['numero'] = $_POST['numero'];
		$data['importe'] = $_POST['importe'];
		$data['idproveedor'] = $_POST['idproveedor'];
		$data['idsiniestro'] = $_POST['idsiniestro'];
		$this->mesa_partes_mdl->inRecepcion($data);

		echo "<script>
				alert('Se registró la recepción en mesa de partes con éxito.');
				location.href='".base_url()."index.php/mesa_partes';
				</script>";
	}

	public function consultar_ruc()
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['ruc'] = $_POST['ruc'];

			$datos_ruc = $this->mesa_partes_mdl->buscar_ruc($data);
			$data['datos_ruc'] = $datos_ruc;

			if(empty($datos_ruc)){
				$data['mensaje2'] = 'No se encontraron resultados.<a href="'.base_url().'index.php/nuevo_otro_proveedor"> Agregar Proveedor</a>';
			}else{
				$data['mensaje2'] = '';
			}

			$data['nro_orden'] = '';
			$data['datos_orden'] = '';
			$data['mensaje'] = '<a href="'.base_url().'index.php/nueva_orden"> Crear Orden</a>';

			$data['class1'] = '';
			$data['class2'] = ' class="active"';
			$data['class3'] = '';
			$data['class4'] = '';			
			$data['class11'] = 'tab-pane fade';
			$data['class21'] = 'tab-pane fade in active';
			$data['class31'] = 'tab-pane fade';
			$data['class41'] = 'tab-pane fade';

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function guardar_recepcion2(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['fecha_recepcion'] = $_POST['recepcion'];
		$data['fecha_emision'] = $_POST['emision'];
		$data['usuario_recepciona'] = $idusuario;
		$data['tipodoc'] =  $_POST['tipodoc'];
		$data['serie'] = $_POST['serie'];
		$data['numero'] = $_POST['numero'];
		$data['importe'] = $_POST['importe'];
		$data['idproveedor_int'] = $_POST['idproveedor_int'];
		$this->mesa_partes_mdl->inRecepcion2($data);

		echo "<script>
				alert('Se registró la recepción en mesa de partes con éxito.');
				location.href='".base_url()."index.php/mesa_partes';
				</script>";
	}

	public function mesa_partes2($num)
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['nro_orden'] = $num;

			$datos_orden = $this->mesa_partes_mdl->buscar_orden($data);
			$data['datos_orden'] = $datos_orden;

			if(empty($datos_orden)){
				$data['mensaje'] = 'No se encontraron resultados.<a href="'.base_url().'index.php/nueva_orden"> Crear Orden</a>';
			}else{
				$data['mensaje'] = '';
			}

			$data['ruc'] = '';
			$data['datos_ruc'] = '';
			$data['mensaje2'] = '';

			$data['class1'] = ' class="active"';
			$data['class2'] = '';
			$data['class3'] = '';
			$data['class4'] = '';
			$data['class11'] = 'tab-pane fade in active';
			$data['class21'] = 'tab-pane fade';
			$data['class31'] = 'tab-pane fade';
			$data['class41'] = 'tab-pane fade';

			$data['getProveedores'] = $this->mesa_partes_mdl->getProveedores();

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function mesa_partes3($ruc)
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['ruc'] = $ruc;

			$datos_ruc = $this->mesa_partes_mdl->buscar_ruc($data);
			$data['datos_ruc'] = $datos_ruc;

			if(empty($datos_ruc)){
				$data['mensaje2'] = 'No se encontraron resultados.<a href="'.base_url().'index.php/nuevo_otro_proveedor"> Agregar Proveedor</a>';
			}else{
				$data['mensaje2'] = '';
			}

			$data['nro_orden'] = '';
			$data['datos_orden'] = '';
			$data['mensaje'] = '<a href="'.base_url().'index.php/nueva_orden"> Crear Orden</a>';

			$data['class1'] = '';
			$data['class2'] = ' class="active"';				
			$data['class3'] = '';
			$data['class4'] = '';		
			$data['class11'] = 'tab-pane fade';
			$data['class21'] = 'tab-pane fade in active';
			$data['class31'] = 'tab-pane fade';
			$data['class41'] = 'tab-pane fade';

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function consultar_recepciones_buscar()
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

			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$data['getRecibidos'] = $this->mesa_partes_mdl->getRecibidos($data);

			$data['nro_orden'] = '';
			$data['ruc'] = '';

			$data['datos_orden'] = '';
			$data['datos_ruc'] = '';
			$data['mensaje'] = '<a href="'.base_url().'index.php/nueva_orden" > Crear Orden</a>';
			$data['mensaje2'] = '';
			$data['class1'] = '';
			$data['class2'] = '';
			$data['class3'] = ' class="active"';
			$data['class11'] = 'tab-pane fade';
			$data['class21'] = 'tab-pane fade';
			$data['class31'] = 'tab-pane fade in active';
			$data['class41'] = 'tab-pane fade';

			$this->load->view('dsb/html/mesa_partes/mesa_partes.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function editar_recepcion($id){
		$data['idrecepcion'] = $id;
		$recibido = $this->mesa_partes_mdl->getRecibido($id);
		$data['ruc'] = $recibido['ruc'];
		$data['raz'] = $recibido['razon_social_pr'];
		$data['nom'] = $recibido['proveedor'];
		$data['tipo'] = $recibido['tipo_recepcion'];
		$data['fecha_recepcion'] = $recibido['fecha_recepcion'];
		$data['fecha_emision'] = $recibido['fecha_emision'];
		$data['tipodoc'] = $recibido['tipo_documento'];
		$data['serie'] = $recibido['serie'];
		$data['orden'] = $recibido['num_orden_atencion'];
		$data['numero'] = $recibido['numero'];
		$data['importe'] = $recibido['importe'];
		$data['asunto'] = $recibido['descripcion'];
		$this->load->view('dsb/html/mesa_partes/recepcion_editar.php',$data);
	}

	public function reg_recepcion(){
		$data['idrecepcion'] = $_POST['idrecepcion'];
		$data['fecha_recepcion'] = $_POST['recepcion'];
		$data['fecha_emision'] = $_POST['emision'];
		$data['tipodoc'] =  $_POST['tipodoc'];
		$data['serie'] = $_POST['serie'];
		$data['numero'] = $_POST['numero'];
		$data['importe'] = $_POST['importe'];
		$this->mesa_partes_mdl->upRecepcion($data);

		echo "<script>
				alert('Se actualizó la recepción en mesa de partes con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}

	public function reg_recepcion3(){
		$data['idrecepcion'] = $_POST['idrecepcion'];
		$data['fecha_recepcion'] = $_POST['recepcion'];		
		$data['tipodoc'] =  $_POST['tipodoc'];		
		$data['numero'] = $_POST['numero'];
		$data['asunto'] = $_POST['asunto'];
		$data['remitente'] = $_POST['remitente'];

		$this->mesa_partes_mdl->upRecepcion3($data);
		echo "<script>
				alert('Se actualizó la recepción en mesa de partes con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}


	public function guardar_recepcion3(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['usuario_recepciona'] = $idusuario;
		$data['remitente'] = $_POST['remitente'];
		$data['asunto'] = $_POST['asunto'];
		$data['recepecion'] = $_POST['recepcion'];
		$data['tipodoc'] = $_POST['tipodoc'];
		$data['numero'] = $_POST['numero'];
		$this->mesa_partes_mdl->inRecepcion3($data);

		echo "<script>
				alert('Se registró la recepción en mesa de partes con éxito.');
				location.href='".base_url()."index.php/mesa_partes';
				</script>";
	}

	public function ruc(){
 		$id = $_POST['idproveedor'];
 		$proveedor = $this->mesa_partes_mdl->getRuc($id);
 		$ruc = $proveedor['numero_documento_pr'];
 		$raz_social = $proveedor['razon_social_pr'];

 		$options = '<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">RUC:</label>
						<div class="col-xs-12 col-sm-8">
							<div class="clearfix">
								<input  type="text" id="ruc" name="ruc" disabled class="col-xs-12 col-sm-3" value="'.$ruc.'" >
							</div>
						</div>																
					</div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Razón Social:</label>
						<div class="col-xs-12 col-sm-8">
							<div class="clearfix">
								<input  type="text" id="razon_social" name="razon_social" disabled class="col-xs-12 col-sm-3" value="'.$raz_social.'" >
							</div>
						</div>																
					</div>'
					;
 		echo $options;
 	}
}
