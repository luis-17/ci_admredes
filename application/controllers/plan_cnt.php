<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class plan_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('plan_mdl');
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

			$planes = $this->plan_mdl->getPlanes();
			$data['planes'] = $planes;

			$this->load->view('dsb/html/plan/plan.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_cobertura($id,$nom)
	{
		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$operador=$this->plan_mdl->get_operador();
		$data['operador'] = $operador;

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
	}

	public function plan_editar($id,$nom)
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

			$data['nom'] = $nom;
			$data['id'] = $id;

			$clientes = $this->plan_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['accion'] = 'Editar Plan';

			if($id>0):
				$plan = $this->plan_mdl->getPlan($id);
				$data['plan'] = $plan;	
			endif;

			$this->load->view('dsb/html/plan/plan_editar.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_registrar()
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

			$data['nom'] = 'Nuevo Plan';
			$data['id'] = 0;

			$clientes = $this->plan_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['accion'] = 'Registrar Plan';

			$this->load->view('dsb/html/plan/plan_editar.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_guardar()
	{
		$data['idplan'] = $_POST['idplan'];
		$data['cliente'] = $_POST['cliente'];
		$data['nombre_plan'] = $_POST['nombre_plan'];
		$data['codigo_plan'] = $_POST['codigo_plan'];
		$data['carencia'] = $_POST['carencia'];
		$data['mora'] = $_POST['mora'];
		$data['atencion'] = $_POST['atencion'];
		$data['prima'] = $_POST['prima'];
		$data['prima_adicional'] = $_POST['prima_adicional'];
		$data['num_afiliados'] = $_POST['num_afiliados'];
		$data['flg_activar'] = $_POST['flg_activar'];
		$data['flg_cancelar'] = $_POST['flg_cancelar'];
		$data['flg_dependientes'] = $_POST['flg_dependientes'];

		if($_POST['idplan']==0):
			$this->plan_mdl->insert_plan($data);
			else:
				$this->plan_mdl->update_plan($data);
		endif;
		redirect('index.php/plan');
	}

	function guardar_cobertura()
	{
		$data['nom'] = $_POST['nom'];
		$data['id'] = $_POST['idplan'];
		$data['iddet'] = $_POST['idplandetalle'];
		$data['item'] = $_POST['item'];
		$data['descripcion'] = $_POST['descripcion'];
		$data['visible'] = $_POST['visible'];
		$data['flg_liqui'] = $_POST['flg_liqui'];
		$data['tiempo'] = $_POST['eventos'];
		$data['num_eventos'] = $_POST['num_eventos'];
		if($_POST['flg_liqui']=='No'){
			$data['valor'] = '';
			$data['operador'] = '';
			}else{
				$data['valor'] = $_POST['valor'];
				$data['operador'] = $_POST['operador'];
		}

		if($_POST['idplandetalle']==0):
			$this->plan_mdl->insert_cobertura($data);
			else:
				$this->plan_mdl->update_cobertura($data);
		endif;

		$data['iddet'] = 0;
		$cobertura = $this->plan_mdl->getCobertura($data['id']);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;
		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function plan_anular($id)
 	{
 		$this->plan_mdl->plan_anular($id);
 		redirect('index.php/plan');
 	}

 	function plan_activar($id)
 	{
 		$this->plan_mdl->plan_activar($id);
 		redirect('index.php/plan');
 	}

 	function cobertura_anular($id, $nom, $iddet)
 	{
 		$data['idplandetalle'] = $iddet;
 		$this->plan_mdl->cobertura_anular($iddet);
 		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function cobertura_activar($id, $nom, $iddet)
 	{
 		$data['idplandetalle'] = $iddet;
 		$this->plan_mdl->cobertura_activar($iddet);

 		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function seleccionar_cobertura($id, $nom, $iddet)
 	{
 		$data['iddet'] = $iddet;
 		$data['nom'] = $nom;
		$data['id'] = $id;

 		$detalle = $this->plan_mdl->selecionar_cobertura($iddet);
		$data['detalle'] = $detalle;

		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;

		$operador=$this->plan_mdl->get_operador();
		$data['operador'] = $operador;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function plan_email($id,$nom)
 	{
 		$data['idplan'] = $id;
 		$data['nom'] = $nom;
 		$plan = $this->plan_mdl->getPlan($id);
		$data['plan'] = $plan;	

 		$this->load->view('dsb/html/plan/plan_email.php',$data);
 	}

 	function guardar_email()
 	{
 		$data['cuerpo_mail'] = $_POST['cuerpo_mail'];
 		$data['idplan'] = $_POST['idplan'];

 		$this->plan_mdl->save_mail($data);

 		echo "<script>
				alert('El contenido del email ha sido actualizado con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
 	}
}