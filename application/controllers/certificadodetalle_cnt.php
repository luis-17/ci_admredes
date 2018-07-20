<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadodetalle_cnt extends CI_Controller {

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

			$certificado = $this->certificado_mdl->getCertificado($id);	
			$data['certificado'] = $certificado;

			$asegurado = $this->certificado_mdl->getAsegurados($id);	
			$data['asegurado'] = $asegurado;

			$cobros = $this->certificado_mdl->getCobros($id);	
			$data['cobros'] = $cobros;
			$data['doc']=$doc;

			$this->load->view('dsb/html/certificado/certificado_detalle.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function aseg_atenciones($id){

		$atenciones = $this->certificado_mdl->getAtenciones($id);
		$data['atenciones'] = $atenciones;	

		$this->load->view('dsb/html/certificado/aseg_atenciones.php', $data);
	}

	public function aseg_editar($id){

		$asegurado = $this->certificado_mdl->getAseg_editar($id);
		$data['asegurado'] = $asegurado;	

		$this->load->view('dsb/html/certificado/aseg_editar.php', $data);
	}

	public function aseg_save(){
		$datos['genero'] = $_POST['genero'];
		$datos['direccion'] = $_POST['direccion'];
	}

	public function activar_certificado($id,$doc)
	{
		$activar_certificado = $this->certificado_mdl->activar_certificado($id);
		$ruta='certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function cancelar_certificado($id,$doc)
	{
		$cancelar_certificado = $this->certificado_mdl->cancelar_certificado($id);
		$ruta='certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function reservar_cita($id, $idaseg, $doc)
	{
		$data['cert_id'] = $id;
		$data['aseg_id'] =$idaseg;
		$data['doc'] = $doc;

		$asegurado = $this->certificado_mdl->getAsegurado($id);
		$data['asegurado'] = $asegurado;

		$citas = $this->certificado_mdl->getCitas();
		$data['citas'] = $citas;

		$proveedores = $this->certificado_mdl->getProveedores();
		$data['proveedores'] = $proveedores;

		$productos = $this->certificado_mdl->getProductos();
		$data['productos'] = $productos;

		$this->load->view('dsb/html/certificado/reservar_cita.php',$data);
	}

	public function save_cita()
	{
		$aseg_id = $_POST['aseg_id'];		
		$cert_id = $_POST['cert_id'];
		$doc = $_POST['doc'];
		$data['aseg_id'] = $_POST['aseg_id'];
		$data['doc'] = $_POST['doc'];
		$data['idproveedor'] = $_POST['proveedor'];	
		$data['idespecialidad'] = $_POST['producto'];	
		$data['estado'] = $_POST['estado'];	
		$data['fecha_cita'] = $_POST['feccita'];
		$data['obs'] = $_POST['obs'];
	}
}