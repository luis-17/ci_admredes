<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostVenta_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('PostVenta_mdl');

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
			$data['getAtenciones'] = $this->PostVenta_mdl->getAtenciones();
			$this->load->view('dsb/html/post_venta/post_venta.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function encuesta($id){
		$data['idsiniestro'] = $id;
		$data['preguntas'] = $this->PostVenta_mdl->getPreguntas();
		$data['respuestas'] = $this->PostVenta_mdl->getRespuestas();
		$getDatos = $this->PostVenta_mdl->getDatos($id);
		$data['apellido'] = $getDatos['apellido'];
		$data['nombre'] = $getDatos['nombre'];
		$data['fecha_atencion'] = $getDatos['fecha_atencion'];
		$data['proveedor'] = $getDatos['nombre_comercial_pr'];
		$data['servicios'] = $this->PostVenta_mdl->getServicios($id);
		$this->load->view('dsb/html/post_venta/encuesta.php',$data);
	}

	public function encuesta_mail($id){
		$data['idsiniestro'] = $id;
		$data['preguntas'] = $this->PostVenta_mdl->getPreguntas();
		$data['respuestas'] = $this->PostVenta_mdl->getRespuestas();
		$getDatos = $this->PostVenta_mdl->getDatos($id);
		$data['apellido'] = $getDatos['apellido'];
		$data['nombre'] = $getDatos['nombre'];
		$data['fecha_atencion'] = $getDatos['fecha_atencion'];
		$data['proveedor'] = $getDatos['nombre_comercial_pr'];
		$data['servicios'] = $this->PostVenta_mdl->getServicios($id);
		$this->load->view('dsb/html/post_venta/encuesta_mail.php',$data);
	}

	function save_sincalificar(){
		$user = $this->session->userdata('user');
		extract($user);
		date_default_timezone_set('America/Lima');
		$data['idsiniestro'] = $_POST['idsiniestro'];
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['comentario'] = 'No desea realizar la calificación de la atención';
		$data['idusuario'] = $idusuario;
		$data['estado'] = 0;

		$this->PostVenta_mdl->save_encuesta($data);
		echo "<script>
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";

	}

	function save_calificar(){
		$user = $this->session->userdata('user');
		extract($user);
		date_default_timezone_set('America/Lima');
		$data['idsiniestro'] = $_POST['idsiniestro'];
		$data['nombre'] = $_POST['nombre'];
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['comentario'] = $_POST['comentario'];
		$data['idusuario'] = $idusuario;
		$data['estado'] = 1;
		$this->PostVenta_mdl->save_encuesta($data);
		$data['idencuesta'] = $this->db->insert_id();
		$preguntas = $this->PostVenta_mdl->getPreguntas();
		$data['tipo_mensaje'] =1;

		foreach ($preguntas as $p) {
			$idpregunta = $p->idpregunta;
			if(isset( $_POST['radio'.$idpregunta])){
				$data['idrespuesta'] = $_POST['radio'.$idpregunta];
				$this->PostVenta_mdl->save_encuesta_detalle($data);
			}
		}
		$this->load->view('dsb/html/post_venta/mensaje.php',$data);
	}

	function save_calificar_mail(){
		date_default_timezone_set('America/Lima');
		$data['idsiniestro'] = $_POST['idsiniestro'];
		$data['nombre'] = $_POST['nombre'];
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['comentario'] = $_POST['comentario'];
		$data['estado'] = 1;
		$this->PostVenta_mdl->save_encuesta2($data);
		$data['idencuesta'] = $this->db->insert_id();
		$preguntas = $this->PostVenta_mdl->getPreguntas();
		$data['tipo_mensaje'] =2;

		foreach ($preguntas as $p) {
			$idpregunta = $p->idpregunta;
			if(isset( $_POST['radio'.$idpregunta])){
				$data['idrespuesta'] = $_POST['radio'.$idpregunta];
				$this->PostVenta_mdl->save_encuesta_detalle($data);
			}
		}
		$this->load->view('dsb/html/post_venta/mensaje.php',$data);
	}

	public function no_contesta($id){
		$user = $this->session->userdata('user');
		extract($user);
		date_default_timezone_set('America/Lima');
		$data['idsiniestro'] = $id;
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['comentario'] = 'No contestó la llamada';
		$data['idusuario'] = $idusuario;
		$data['estado'] = 2;

		$this->PostVenta_mdl->save_encuesta($data);

		echo "<script>
				alert('Se registró la llamada de post-venta como NO ATENDIDA');
				location.href='".base_url()."index.php/post_venta';
				</script>";
	}

}