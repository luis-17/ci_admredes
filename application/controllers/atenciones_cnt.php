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

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;


			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	

			$ordenes = $this->atencion_mdl->getAtenciones();
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

}