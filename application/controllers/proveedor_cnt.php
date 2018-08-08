<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');        
        $this->load->model('proveedor_mdl');
        $this->load->library("pagination");
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

			$proveedores = $this->proveedor_mdl->getProveedores();
			$data['proveedores'] = $proveedores;	

			$this->load->view('dsb/html/proveedor/proveedor.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function habilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->habilitar($id);
		redirect ('proveedor');
	}

	public function inhabilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->inhabilitar($id);
		redirect ('proveedor');
	}

	public function nuevo()
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
			$id=0;
			$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
			$data['data_tipoproveedor'] = $datatipoproveedor;
			$departamento = $this->proveedor_mdl->departamento();
			$data['departamento'] = $departamento;
			$data['nom'] = "Nuevo Proveedor";
			$data['accion'] = "Registrar Proveedor";
			$this->load->view('dsb/html/proveedor/form_proveedor.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_editar($id,$nom)
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

			$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
			$data['data_tipoproveedor'] = $datatipoproveedor;
			$departamento = $this->proveedor_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$data['accion'] = "Actualizar Proveedor";
			$data['nom'] = "";
			$this->load->view('dsb/html/proveedor/form_proveedor.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_guardar()
	{

	}


}
