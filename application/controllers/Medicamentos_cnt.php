<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class medicamentos_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('medicamentos_mdl');

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
 	public function medicamentos(){
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

			$medicamentos = $this->medicamentos_mdl->getMedicamentos();
			$data['medicamentos'] = $medicamentos;

			$this->load->view('dsb/html/tablas_maestras/medicamentos.php',$data);
		}
		else{
			redirect('/');
		}
 	}

	public function medicamentos_anular($id){
		$this->medicamentos_mdl->medicamentos_anular($id);
		echo "<script>
				alert('Se inactivó el medicamento con éxito.');
				location.href='".base_url()."index.php/medicamentos';
				</script>";
	}

	public function medicamentos_activar($id){
		$this->medicamentos_mdl->medicamentos_activar($id);
		echo "<script>
				alert('Se reactivó el medicamento con éxito.');
				location.href='".base_url()."index.php/medicamentos';
				</script>";
	}

	public function medicamentos_editar($id){
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

			$medicamentosId = $this->medicamentos_mdl->getMedicamentosId($id);

			foreach ($medicamentosId as $m) {
				$data['nombre_med'] = $m->nombre_med;
				$data['presentacion_med'] = $m->presentacion_med;
				$data['idmedicamento'] = $m->idmedicamento;
			}

			$data['accion'] = "Actualizar medicamentos";

			$this->load->view('dsb/html/tablas_maestras/medicamentos_editar.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function act_medicamentos(){
		$id = $_POST['idmedicamento'];
		$nombre = $_POST['nombre_med'];
		$presentacion = $_POST['presentacion_med'];

		$this->medicamentos_mdl->medicamentos_update($nombre, $presentacion, $id);

	}

	public function medicamentos_guardar(){
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

			$this->load->view('dsb/html/tablas_maestras/medicamentos_guardar.php', $data);
		}
		else{
			redirect('/');
		}	
	}

	public function save_medicamentos(){
		$nombre = $_POST['nombre_med'];
		$presentacion = $_POST['presentacion_med'];

		$this->medicamentos_mdl->insertar_med($nombre, $presentacion);

	}

}
