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

			$contratante = $this->certificado_mdl->getContratante($id);
			$data['contratante'] = $contratante;

			$asegurado = $this->certificado_mdl->getAsegurados($id);	
			$data['asegurado'] = $asegurado;

			$cobros = $this->certificado_mdl->getCobros($id);	
			$data['cobros'] = $cobros;
			$data['doc']=$doc;
			$data['id2'] = $id;
			$ubigeo=$this->certificado_mdl->ubigeo();
			$data['ubigeo']=$ubigeo;
			$provincia2=$this->certificado_mdl->provincia2($data);
			$data['provincia2']=$provincia2;
			$distrito2=$this->certificado_mdl->distrito2($data);
			$data['distrito2']=$distrito2;

			$this->load->view('dsb/html/certificado/certificado_detalle.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function aseg_atenciones($id,$cert){

		$atenciones = $this->certificado_mdl->getAtenciones($id,$cert);
		$data['atenciones'] = $atenciones;	

		$this->load->view('dsb/html/certificado/aseg_atenciones.php', $data);
	}

	public function aseg_editar($id){
		$ubigeo=$this->certificado_mdl->ubigeo();
		$data['aseg_id'] = $id;
		$data['ubigeo']=$ubigeo;
		$provincia3=$this->certificado_mdl->provincia3($data);
		$data['provincia3']=$provincia3;
		$distrito3=$this->certificado_mdl->distrito3($data);
		$data['distrito3']=$distrito3;
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
		$ruta='index.php/certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function cancelar_certificado($id,$doc)
	{
		$cancelar_certificado = $this->certificado_mdl->cancelar_certificado($id);
		$ruta='index.php/certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function reservar_cita($id, $idaseg, $cita, $certase_id, $fin)
	{
		$data['cert_id'] = $id;
		$data['aseg_id'] =$idaseg;
		$data['cita'] = $cita;
		$data['certase_id'] = $certase_id;
		$data['max'] = $fin;

		$asegurado = $this->certificado_mdl->getAsegurado($id);
		$data['asegurado'] = $asegurado;

		$citas = $this->certificado_mdl->getCitas();
		$data['citas'] = $citas;

		$proveedores = $this->certificado_mdl->getProveedores();
		$data['proveedores'] = $proveedores;

		$productos = $this->certificado_mdl->getProductos();
		$data['productos'] = $productos;

		if($data['cita']==null){
			$data['getcita']="";
		}else{
			$getcita=$this->certificado_mdl->getCita($data);
			$data['getcita']=$getcita; 
		}

		$this->load->view('dsb/html/certificado/reservar_cita.php',$data);
	}

	public function save_cita()
	{
		$aseg_id = $_POST['aseg_id'];		
		$cert_id = $_POST['cert_id'];
		$data['aseg_id'] = $_POST['aseg_id'];
		$data['cert_id'] = $_POST['cert_id'];
		$data['inicio'] = $_POST['inicio'];
		$data['fin'] = $_POST['fin'];
		$data['certase_id'] = $_POST['certase_id'];
		$data['idproveedor'] = $_POST['proveedor'];	
		$data['idespecialidad'] = $_POST['producto'];	
		$data['estado'] = $_POST['estado'];	
		$data['fecha_cita'] = $_POST['feccita'];
		$data['obs'] = $_POST['obs'];
		$data['idusuario'] = $_POST['idusuario'];
		$data['idsiniestro'] = $_POST['idsiniestro'];

		if($data['idsiniestro']==''){
			$this->certificado_mdl->saveCalendario($data);
			$id = $this->db->insert_id();
			$data['idcita'] =  $id;
			$num = $this->certificado_mdl->num_orden_atencion();
			foreach ($num as $n) {
				$numero=$n->num_orden_atencion;
				$data['num'] = $numero;
			}
			$this->certificado_mdl->savePreOrden($data);
			$data['mensaje'] = 2;
		}else{
			$data['idcita'] = $_POST['idcita'];
			$this->certificado_mdl->updateCalendario($data);
			$this->certificado_mdl->updatePreOrden($data);
			$data['mensaje'] = 3;
		}
		
		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function anular_cita($idcita,$aseg,$cert){
		$data['cita'] = $idcita;
		$data['aseg'] = $aseg;
		$data['cert'] = $cert;
		$getcita=$this->certificado_mdl->getCita($data);
		$data['getcita']=$getcita; 
		$this->load->view('dsb/html/certificado/anular_cita.php', $data);
	}

	public function eliminar_cita(){
		$data['mensaje'] = 4;
		$data['idcita'] = $_POST['idcita'];
		$data['idsiniestro'] = $_POST['idsiniestro'];

		$this->certificado_mdl->eliminar_cita($data);
		$this->certificado_mdl->eliminar_orden($data);
		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function cert_cont_save()
	{
		$data['direcc'] = $_POST['direcc'];
		$data['telf'] = $_POST['telf'];
		$data['correo'] = $_POST['correo'];
		$data['ubigeo'] = $_POST['dist'];
		$data['cont_id'] = $_POST['cont_id'];
		$id2 = $_POST['id2'];
		$doc = $_POST['doc'];

		$this->certificado_mdl->cont_save($data);
		redirect("index.php/certificado_detalle/".$id2."/".$doc);
	}

	public function cert_aseg_up(){
		$data['aseg_id'] = $_POST['idaseg'];
		$data['ape1'] = $_POST['ape1'];
		$data['ape2'] = $_POST['ape2'];
		$data['nom1'] = $_POST['nom1'];
		$data['nom2'] = $_POST['nom2'];
		$data['fec_nac'] = $_POST['fec_nac'];
		$data['genero'] = $_POST['genero'];
		$data['dis'] = $_POST['dis'];
		$data['direccion'] = $_POST['direccion'];
		$data['correo'] = $_POST['correo'];
		$data['telf'] = $_POST['telf'];
		$data['ec'] = $_POST['ec'];
		$data['tipomen'] = 1;
		
		$this->certificado_mdl->up_aseg($data);
		$data['mensaje'] = 1;
		$this->load->view('dsb/html/mensaje.php', $data);
	}
}