<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DniPrueba_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('tablas_mdl');
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

			$data['dnis'] = $this->tablas_mdl->getDnisPrueba();
			$this->load->view('dsb/html/tablas_maestras/dni_prueba.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function nuevo_afiliado(){
		$data['aseg_id']="";
		$data['canal'] = "";
		$data['plan'] = "";
		$data['doc'] = "";
		$data["tipdoc"] ="";
		$data['cert_ver'] = "";
		$data['aseg_ver'] = "";
		$user = $this->session->userdata('user');
		extract($user);
		$planes = $this->tablas_mdl->getPlanes($data['canal']);
		$data['planes'] = $planes;
		$data['estilo'] = "none";	
		$canales = $this->tablas_mdl->getCanales($data['canal'] );
		$data['canales'] = $canales;

		$ubigeo=$this->tablas_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia3=$this->tablas_mdl->provincia3($data);
		$data['provincia3']=$provincia3;
		$distrito3=$this->tablas_mdl->distrito3($data);
		$data['distrito3']=$distrito3;

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

			$data['dnis'] = $this->tablas_mdl->getDnisPrueba();
			$this->load->view('dsb/html/tablas_maestras/cont_nuevo.php',$data);
		}
		else{
			redirect('/');
		}

	}

	public function verifica_dni_in(){
		$data['doc'] = $_POST['doc_copy'];	
		$data['tipdoc'] = $_POST['tipodoc_copy'];
		$data['canal'] = $_POST['canal_copy'];
		$data['plan'] = $_POST['plan_copy'];
		$user = $this->session->userdata('user');
		extract($user);

		$ubigeo=$this->tablas_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia2=$this->tablas_mdl->provincia2($data);
		$data['provincia3']=$provincia2;
		$distrito2=$this->tablas_mdl->distrito2($data);
		$data['distrito3']=$distrito2;
		$data['asegurado'] = "";
		$planes = $this->tablas_mdl->getPlanes($data['canal'] );
		$data['planes'] = $planes;
		$data['estilo'] = "none";	
		$canales = $this->tablas_mdl->getCanales();
		$data['canales'] = $canales;

			$aseg_ver = $this->tablas_mdl->verifica_dni($data);
			$data['aseg_ver'] = $aseg_ver;
			$cert_ver = $this->tablas_mdl->vertifica_cert($data);
			$data['cert_ver'] = "";
			$cont = $this->tablas_mdl->verifica_contratante($data);
			$data['cont'] = $cont;
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

			$data['dnis'] = $this->tablas_mdl->getDnisPrueba();
			$this->load->view('dsb/html/tablas_maestras/cont_nuevo.php',$data);
		}
		else{
			redirect('/');
		}

	}

	public function cont_save(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['aseg_id'] = $_POST['aseg_id'];
		$id = $_POST['aseg_id'];
		$data['id'] = $_POST['aseg_id'];
		$data['canal'] = $_POST['canal'];
		$data['plan'] = $_POST['plan'];
		$data['tipodoc'] = $_POST['tipodoc'];
		$data['doc'] = $_POST['doc'];
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
		$op = $_POST['tipoop'];
		$data['tipomen'] = 3;
		$cont = $_POST['cont_id'];
		$res_afi = $_POST['cont'];
		$hoy=date('Y-m-d');

		$month = date('m');
		$month2 = date('m')+6;
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month, 0, $year));
        $data['inicio'] = $_POST['inivig'];
        $data['fin'] = $_POST['finvig'];

		$cert_num=$this->tablas_mdl->get_cert_num($data);
		foreach ($cert_num as $c) {
			$data['cert_num'] = "PR".$c->cert_num;
		}

		if($cont==0){
			$this->tablas_mdl->in_contratante($data);
			$data['cont_id'] = $this->db->insert_id();
		}else{
			$data['cont_id'] = $cont;
		}


		switch ($op) {
			case 2:				
				$this->tablas_mdl->in_certificado($data);
				$cert = $this->db->insert_id();
				$data['cert'] = $cert;
				if($res_afi==1){
					$this->tablas_mdl->up_aseg($data);
					$this->tablas_mdl->in_certase($data);
				}				
				break;

			case 3:				
				$this->tablas_mdl->in_certificado($data);
				$cert = $this->db->insert_id();
				$data['cert'] = $cert;
				if($res_afi==1){
					$this->tablas_mdl->in_asegurado($data);
					$id = $this->db->insert_id();
					$data['id'] = $id;
					$this->tablas_mdl->in_certase($data);
				}
				break;
			}

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

			$data['dnis'] = $this->tablas_mdl->getDnisPrueba();
			redirect(base_url()."index.php/dni_prueba/");
		}
		else{
			redirect("/");
		}

	}

	public function baja($cert_id, $certase_id){
		$this->tablas_mdl->baja_certasegurado($certase_id);
		$num_afiliados = $this->tablas_mdl->num_afiliados($cert_id);
		if(empty($num_afiliados)){
			$cert = $this->tablas_mdl->getCertificado($cert_id);
			foreach ($cert as $c) {
				$data['cert_num'] = $c->cert_num;
				$data['cert_id'] = $cert_id;
				$data['plan_id'] = $c->plan_id;
			}

			$this->tablas_mdl->baja_certificado($cert_id);
			$this->tablas_mdl->cancelar_cert($data);
		}

		echo "<script>
					alert('Se dió de baja al DNI con éxito.');
					location.href='".base_url()."index.php/dni_prueba';
					</script>";
	}

}