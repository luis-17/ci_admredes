<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class persona_juridica_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
         $this->load->model('canal_mdl');

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

			$canal = $this->canal_mdl->getCanales();
			$data['canal'] = $canal;

			$this->load->view('dsb/html/canal/persona_juridica.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function canal_anular($id){
		$this->canal_mdl->canal_anular($id);
		redirect(base_url()."index.php/persona_juridica");
	}

	public function canal_activar($id){
		$this->canal_mdl->canal_activar($id);
		redirect(base_url()."index.php/persona_juridica");
	}

	public function canal_registrar(){

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

			$categoria = $this->canal_mdl->getCategoria();

			$data['categoria'] = $categoria;
			$data['idcategoria'] = "";
			$data['nom'] = "Nuevo Canal";
			$data['accion'] = "Registrar Canal";
			$data['idcanal'] = 0;
			$data['ruc'] = "";
			$data['razon_social'] = "";
			$data['comercial'] ="";
			$data['nombre_corto'] ="";
			$data['dni'] = "";
			$data['nombres'] = "";
			$data['direccion'] = "";
			$data['telf'] = "";
			$data['web'] = "";
			$this->load->view('dsb/html/canal/canal_editar.php',$data);
		}
		else{
			redirect('/');
		}
		
	}

	public function canal_editar($id){

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

			$categoria = $this->canal_mdl->getCategoria();

			$data['categoria'] = $categoria;

			$canal = $this->canal_mdl->getCanal($id);

			foreach ($canal as $c) {
				$data['idcategoria'] = $c->idcategoriacliente;
				$data['nom'] = $c->nombre_comercial_cli;
				$data['accion'] = "Actualizar Canal";
				$data['idcanal'] = $c->idclienteempresa;
				$data['ruc'] = $c->numero_documento_cli;
				$data['razon_social'] = $c->razon_social_cli;
				$data['comercial'] = $c->nombre_comercial_cli;
				$data['nombre_corto'] = $c->nombre_corto_cli;
				$data['dni'] = $c->dni_representante_legal;
				$data['nombres'] = $c->representante_legal;
				$data['direccion'] = $c->direccion_legal;
				$data['telf'] = $c->telefono_cli;
				$data['web'] = $c->pagina_web_cli;
			}

			
			$this->load->view('dsb/html/canal/canal_editar.php',$data);
		}
		else{
			redirect('/');
		}
		
	}

	public function canal_guardar(){
		$data['idcategoria'] = $_POST['idcategoria'];
		$data['ruc'] = $_POST['ruc'];
		$data['razon_social'] = $_POST['razon_social'];
		$data['comercial'] = $_POST['comercial'];
		$data['nombre_corto'] = $_POST['nombre_corto'];
		$data['dni'] = $_POST['dni'];
		$data['nombres'] = $_POST['nombres'];
		$data['direccion'] = $_POST['direccion'];
		$data['telf'] = $_POST['telf'];
		$data['web'] = $_POST['web'];

		if($_POST['idcanal'] == 0 ){
			$this->canal_mdl->insert_canal($data);
		}else{
			$data['idcanal'] = $_POST['idcanal'];
			$this->canal_mdl->update_canal($data);
		}

		redirect(base_url()."index.php/persona_juridica");
	}
}