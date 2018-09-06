<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
		$this->load->library('export_excel');
        $this->load->model('menu_mdl');
        $this->load->model('reportes_mdl');
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

		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = '';

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_cobros.php',$data);

		}
		else{
			redirect('/');
		}
	}

	public function buscar()
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

			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$datos['canal'] = $_POST['canal'];
			$datos['plan'] = $_POST['plan'];
			$datos['inicio'] = $_POST['fechainicio'];
			$datos['fin'] = $_POST['fechafin'];

			$data['plan_id'] = $_POST['plan'];		
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$cobros = $this->reportes_mdl->getImportes($datos);
			$data['cobros'] = $cobros;	

			$data['canales'] = $this->reportes_mdl->getCanales();
			$planes = $this->reportes_mdl->getPlanes2($datos['canal']);
			$data['planes'] = $planes;

			$data['estilo'] = 'block';


			$this->load->view('dsb/html/reportes/consultar_cobros.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function detalle_cobros($importe,$plan,$inicio,$fin){
		$datos['plan'] = $plan;		
		$datos['inicio'] = $inicio;
		$datos['fin'] = $fin;
		$datos['importe'] = $importe;

		$cobros = $this->reportes_mdl->getCobros($datos);
		$data['cobros'] = $cobros;


		$this->load->view('dsb/html/reportes/detalle_cobros.php',$data);

	}

	public function exc_cobros($plan,$inicio,$fin)
	{
		$datos['plan'] = $plan;
		$datos['inicio'] = $inicio;
		$datos['fin'] = $fin;
		$hoy = date('d-m-Y');

		$result = $this->reportes_mdl->getCobros($datos);
		$this->export_excel->to_excel($result, 'cobros');
	}

	public function consultar_afiliados()
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

		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = '';

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_afiliados.php',$data);

		}
		else{
			redirect('/');
		}

	}

	public function consultar_atenciones()
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

		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = '';

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_atenciones.php',$data);

		}
		else{
			redirect('/');
		}

	}

	public function consultar_atenciones_buscar()
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

			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$datos['canal'] = $_POST['canal'];
			$datos['plan'] = $_POST['plan'];
			$datos['inicio'] = $_POST['fechainicio'];
			$datos['fin'] = $_POST['fechafin'];

			$data['plan_id'] = $_POST['plan'];		
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$atenciones = $this->reportes_mdl->cons_atenciones($datos);
			$data['atenciones'] = $atenciones;	

			$data['canales'] = $this->reportes_mdl->getCanales();
			$planes = $this->reportes_mdl->getPlanes2($datos['canal']);
			$data['planes'] = $planes;

			$data['estilo'] = 'block';


			$this->load->view('dsb/html/reportes/consultar_atenciones.php',$data);
		}
		else{
			redirect('/');
		}
	}
}