<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('atencion_mdl');
        $this->load->model('liquidacion_mdl');

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

			$liquidaciones = $this->liquidacion_mdl->getLiquidaciones();
			$data['liquidaciones'] = $liquidaciones;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();

			$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);
		}
		else{
			redirect('/');
		}	
	}


	public function registraPago()
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
			        
		    $data['liqdetalleid'] = $_POST['liqdetalleid'];
		    $liqdetalleid =  $data['liqdetalleid'];
		    
		    $data['pagoFecha'] = $_POST['pagoFecha'];
		    $data['pagoForma'] = $_POST['pagoForma'];
		    $data['pagoBanco'] = $_POST['pagoBanco'];
		    $data['pagoNoperacion'] = $_POST['pagoNoperacion'];
		    $data['pagoNfactura'] = $_POST['pagoNfactura'];	    

		    $buscaLiqdetalleid = $this->liquidacion_mdl->buscaLiqDeta($liqdetalleid);

		    if ($buscaLiqdetalleid){
		    	$this->liquidacion_mdl->updateLiqdetalle($data);
		    }else {
		    	$this->liquidacion_mdl->guardaLiqdetalle($data);
		    }

		    $this->liquidacion_mdl->updateEstadoLiqdetalle($data);

			//$this->load->view('dsb/html/liquidacion',$data);
			redirect(base_url()."liquidacion/");

			//$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	

	public function orden($id,$est)
	{
		$orden = $this->atencion_mdl->orden($id,$est);
		redirect ('atenciones');
	}

}