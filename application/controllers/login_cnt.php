<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros'));
          $this->load->model('menu_mdl');
          $this->load->model('login_mdl');          
          $this->load->model('reportes_mdl');              
          $this->load->model('diagnosticos_mdl');
          //$this->load->helper('form');
          //$this->load->library('form_validation');
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

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$this->home();
		}
		else{
			$this->load->view('dsb/html/login');
		}		
	}

	public function home(){
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
			$data['id'] = "";	
			$data['nom'] = "";
			$atenciones = $this->login_mdl->atenciones();
			foreach ($atenciones as $a) {
				$data['idcita'] = $a->idcita;
				$data['idsiniestro'] = $a->idsiniestro;
				$this->login_mdl->eliminar_cita($data);
				$this->login_mdl->eliminar_orden($data);
			}			
			$this->load->view('dsb/html/index.php', $data);
		}
		else{
			redirect('/');
		}		
	}

 	function start_sesion()
    {
    	//load session library
		$this->load->library('session');

		$email = $_POST['email'];
		$password = $_POST['password'];

		$data = $this->login_mdl->login($email, $password);
		
		if($data){
			$this->session->set_userdata('user', $data);
			$this->home();
		}
		else{
			header('location:'.base_url().$this->index());
			$this->session->set_flashdata('error','Invalid login. User not found');
		} 
        //$this->load->view('dsb/html/index');
    }


    public function logout(){
		//load session library
		$this->load->library('session');
		$this->session->unset_userdata('user');
		redirect('/');
	}

	public function denegado($desc){
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
			$data['id'] = "";	
			$data['nom'] = $desc;
			$this->load->view('dsb/html/denegado.php', $data);
		}
		else{
			redirect('/');
		}		
	}

	public function consultas(){
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
			$planes = $this->reportes_mdl->getPlanes();
			$data['planes'] = $planes;
			$canales = $this->reportes_mdl->getCanales();
			$data['canales'] = $canales;
			$data['canal'] = '';
			$data['plan'] = '';
			$data['estado'] = 0;
			$this->load->view('dsb/html/consulta/consultas.php', $data);
		}
		else{
			redirect('/');
		}		
	}

	public function detalle_plan(){
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
			$canal = $_POST['canal'];
			$planes = $this->reportes_mdl->getPlanes2($canal);
			$data['planes'] = $planes;
			$canales = $this->reportes_mdl->getCanales();
			$data['canales'] = $canales;
			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$plan = $this->login_mdl->getPlan($data);
			$responsable = $this->login_mdl->getResponsable($data);
			$data['responsable'] = $responsable['responsable'];
			$data['nombre_comercial_cli'] = $plan['nombre_comercial_cli'];
			$data['nombre_plan'] = $plan['nombre_plan'];
			$data['carencia'] = $plan['dias_carencia'];
			$data['mora'] = $plan['dias_mora'];
			$data['atencion'] = $plan['dias_atencion'];
			$data['estado'] = 1;
			$data['cobertura_operador'] = $this->login_mdl->getCoberturasOperador($data);
			$data['coberturas'] = $this->login_mdl->getCoberturas($data);
			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticos();
			$data['diagnosticos'] = $diagnosticos;
			$this->load->view('dsb/html/consulta/consultas.php', $data);
		}
		else{
			redirect('/');
		}		
	}

	public function diagnosticos_detalle2($id,$plan){
		$diagnosticos = $this->diagnosticos_mdl->getDiagnosticosId($id);

			foreach ($diagnosticos as $m) {
				$data['iddiagnostico'] = $m->iddiagnostico;
				$data['codigo_cie'] = $m->codigo_cie;
				$data['descripcion_cie'] = $m->descripcion_cie;
				if ($m->tipo == 1) {
					$data['tipo'] = 'Capa simple';
				} else {
					$data['tipo'] = 'Preexistente';
				}
			}

			$medicamentos = $this->diagnosticos_mdl->getMedicamentosDiagnosticos($id);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->login_mdl->getProductosDiagnosticos($id,$plan);
			$data['productos'] = $productos;
		$this->load->view('dsb/html/consulta/detalle_diagnostico.php', $data);
	}


}
