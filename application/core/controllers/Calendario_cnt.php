<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario_cnt extends CI_Controller {

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
	public function index($id,$doc)
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

			$certificado = $this->certificado_mdl->getcertificado_calendario($id);
			$data['certificado'] = $certificado;

			$asegurado = $this->certificado_mdl->getAsegurado($id);
			$data['asegurado'] = $asegurado;

			$citas = $this->certificado_mdl->getCitas();
			$data['citas'] = $citas;

			$proveedores = $this->certificado_mdl->getProveedores();
			$data['proveedores'] = $proveedores;

			$productos = $this->certificado_mdl->getProductos();
			$data['productos'] = $productos;

			$data['doc'] = $doc;
			$data['estilo_mensaje'] = 'none';
			$data['mensaje'] = '';

			$this->load->view('dsb/html/certificado/calendario.php',$data);

		}
		else{
			redirect('/');
		}
	}

	public function calendario_guardar()
	{
		$aseg_id = $_POST['aseg_id'];		
		$certase_id = $_POST['certase_id'];
		$doc = $_POST['doc'];
		$data['aseg_id'] = $_POST['aseg_id'];
		$data['certase_id'] = $_POST['certase_id'];
		$data['doc'] = $_POST['doc'];
		$data['idproveedor'] = $_POST['proveedor'];	
		$data['idespecialidad'] = $_POST['producto'];	
		$data['estado'] = $_POST['estado'];	
		$data['fecha_cita'] = $_POST['feccita'];
		$data['obs'] = $_POST['obs'];

		$numcitas = $this->certificado_mdl->getNumCitas($data);
		$numatenciones = $this->certificado_mdl->getNumAtenciones($data);
		
		if($numcitas>0):
			if($numatenciones>0):
				$data['estilo_mensaje'] = 'block';
				$data['mensaje'] = 'La última atención del usuario aún no supera el periodo de latencia';
				redirect('calendario/'.$certase_id.'/'.$doc);
				else:
					$data['estilo_mensaje'] = 'block';
					$data['mensaje'] = 'La última cita del usuario aún no supera el periodo de latencia';
					redirect('calendario/'.$certase_id.'/'.$doc);
			endif;
			else:

				$this->certificado_mdl->saveCalendario($data);
				$data['estilo_mensaje'] = 'none';
				$data['mensaje'] = '';
				redirect('calendario/'.$certase_id.'/'.$doc);
		endif;		
	}

}