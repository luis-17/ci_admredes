<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificado_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');        
        $this->load->model('certificado_mdl');

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
			$data['id'] = "";	
			$data['nom'] = "";			
			$this->load->view('dsb/html/certificado/certificado.php', $data);
		}
		else{
			redirect('/');
		}
	}

	public function index2($id)
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

			$data['id'] = $id;
			$data['nom'] = "";

			$certificados = $this->certificado_mdl->getCertificados($id);
			$data['certificados'] = $certificados;

			$this->load->view('dsb/html/certificado/certificado.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function consulta_certificado()
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

			$doc = $_POST['doc'];
			$nom = $_POST['nom'];

			$data['id'] = $doc;
			$data['nom'] = $nom;

			if($nom!=''):
				$certificadoap = $this->certificado_mdl->getCertificadoApellidos($nom);
				$data['certificadoap'] = $certificadoap;
				elseif($doc!=''):
					$certificados = $this->certificado_mdl->getCertificados($doc);
					$data['certificados'] = $certificados;
			endif;

			$this->load->view('dsb/html/certificado/certificado.php',$data);
		}
		else{
			redirect('/');
		}

	}

}