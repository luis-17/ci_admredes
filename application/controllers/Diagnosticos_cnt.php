<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class diagnosticos_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('diagnosticos_mdl');

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
 	public function diagnosticos(){
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

			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticos();
			$data['diagnosticos'] = $diagnosticos;

			$this->load->view('dsb/html/tablas_maestras/diagnosticos.php',$data);
		}
		else{
			redirect('/');
		}
 	}

	public function diagnosticos_anular($id){
		$this->diagnosticos_mdl->diagnosticos_anular($id);
		echo "<script>
				alert('Se inactivó el diagnóstico con éxito.');
				location.href='".base_url()."index.php/diagnosticos';
				</script>";
	}

	public function diagnosticos_activar($id){
		$this->diagnosticos_mdl->diagnosticos_activar($id);
		echo "<script>
				alert('Se reactivó el diagnóstico con éxito.');
				location.href='".base_url()."index.php/diagnosticos';
				</script>";
	}

 	public function diagnosticos_detalle($id){
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

			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticosId($id);
			$data['diagnosticos'] = $diagnosticos;

			foreach ($diagnosticos as $m) {
				$data['iddiagnostico'] = $m->iddiagnostico;
				$data['codigo_cie'] = $m->codigo_cie;
				$data['descripcion_cie'] = $m->descripcion_cie;
				if ($m->tipo == 1) {
					$data['tipo'] = 'Capa simple';
				} else {
					$data['tipo'] = 'Preexistente';
				}
			}

			$medicamentos = $this->diagnosticos_mdl->getMedicamentosDiagnosticos($id);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->diagnosticos_mdl->getProductosDiagnosticos($id);
			$data['productos'] = $productos;

			$this->load->view('dsb/html/tablas_maestras/diagnosticos_detalle.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function diagnosticos_editar($id){
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

			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticosId($id);
			$data['diagnosticos'] = $diagnosticos;

			foreach ($diagnosticos as $m) {
				$data['iddiagnostico'] = $m->iddiagnostico;
				$data['codigo_cie'] = $m->codigo_cie;
				$data['descripcion_cie'] = $m->descripcion_cie;
			}

			$medicamentos = $this->diagnosticos_mdl->getMedicamentosChecked($id);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->diagnosticos_mdl->getProductosChecked($id);
			$data['productos'] = $productos;

			$this->load->view('dsb/html/tablas_maestras/diagnosticos_editar.php',$data);
		}
		else{
			redirect('/');
		}
 	}

	public function del_medicamento($id, $iddiagnostico){
		$this->diagnosticos_mdl->del_medicamento($id, $iddiagnostico);
		echo "<script>
				alert('Se eliminó el medicamento con éxito.');
				location.href='".base_url()."index.php/diagnosticos_editar/".$iddiagnostico."';
				</script>";
	}

	public function add_medicamento($id, $iddiagnostico){
		$this->diagnosticos_mdl->add_medicamento($id, $iddiagnostico);
		echo "<script>
				alert('Se agregó el medicamento con éxito.');
				location.href='".base_url()."index.php/diagnosticos_editar/".$iddiagnostico."';
				</script>";
	}

	public function del_producto($id, $iddiagnostico){
		$this->diagnosticos_mdl->del_producto($id, $iddiagnostico);
		echo "<script>
				alert('Se eliminó el producto con éxito.');
				location.href='".base_url()."index.php/diagnosticos_editar/".$iddiagnostico."';
				</script>";
	}

	public function add_producto($id, $iddiagnostico){
		$this->diagnosticos_mdl->add_producto($id, $iddiagnostico);
		echo "<script>
				alert('Se agregó el producto con éxito.');
				location.href='".base_url()."index.php/diagnosticos_editar/".$iddiagnostico."';
				</script>";
	}

	public function act_datos(){

		$codigo_cie = $_POST['codigo_cie'];
		$descripcion_cie = $_POST['descripcion_cie'];
		$iddiagnostico = $_POST['iddiagnostico'];
		$tipo = $_POST['tipo'];

		$this->diagnosticos_mdl->act_datos($codigo_cie, $descripcion_cie, $tipo, $iddiagnostico);
	}

 	public function diagnosticos_guardar(){
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


			/*$medicamentos = $this->diagnosticos_mdl->getMedicamentosChecked($id);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->diagnosticos_mdl->getProductosChecked($id);
			$data['productos'] = $productos;*/

			$this->load->view('dsb/html/tablas_maestras/diagnosticos_guardar.php',$data);
		}
		else{
			redirect('/');
		}
 	}

	public function save_datos(){

		$codigo_cie = $_POST['codigo_cie'];
		$descripcion_cie = $_POST['descripcion_cie'];
		$tipo = $_POST['tipo'];

		$this->diagnosticos_mdl->save_datos($codigo_cie, $descripcion_cie, $tipo);
	}

 	public function diagnosticos_add(){
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

			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticosDatos();
			$data['diagnosticos'] = $diagnosticos;

			foreach ($diagnosticos as $m) {
				$data['iddiagnostico'] = $m->iddiagnostico;
				$data['codigo_cie'] = $m->codigo_cie;
				$data['descripcion_cie'] = $m->descripcion_cie;
			}

			$medicamentos = $this->diagnosticos_mdl->getMedicamentosChecked($data['iddiagnostico']);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->diagnosticos_mdl->getProductosChecked($data['iddiagnostico']);
			$data['productos'] = $productos;

			$this->load->view('dsb/html/tablas_maestras/diagnosticos_add.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function del_medicamento_save($id, $iddiagnostico){
		$this->diagnosticos_mdl->del_medicamento($id, $iddiagnostico);
		echo "<script>
				alert('Se eliminó el medicamento con éxito.');
				location.href='".base_url()."index.php/diagnosticos_add/".$iddiagnostico."';
				</script>";
	}

	public function add_medicamento_save($id, $iddiagnostico){
		$this->diagnosticos_mdl->add_medicamento($id, $iddiagnostico);
		echo "<script>
				alert('Se agregó el medicamento con éxito.');
				location.href='".base_url()."index.php/diagnosticos_add/".$iddiagnostico."';
				</script>";
	}

	public function del_producto_save($id, $iddiagnostico){
		$this->diagnosticos_mdl->del_producto($id, $iddiagnostico);
		echo "<script>
				alert('Se eliminó el producto con éxito.');
				location.href='".base_url()."index.php/diagnosticos_add/".$iddiagnostico."';
				</script>";
	}

	public function add_producto_save($id, $iddiagnostico){
		$this->diagnosticos_mdl->add_producto($id, $iddiagnostico);
		echo "<script>
				alert('Se agregó el producto con éxito.');
				location.href='".base_url()."index.php/diagnosticos_add/".$iddiagnostico."';
				</script>";
	}

 	public function diagnosticos_add_save($iddiagnostico){
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

			$diagnosticos = $this->diagnosticos_mdl->getDiagnosticosDatos();
			$data['diagnosticos'] = $diagnosticos;

			foreach ($diagnosticos as $m) {
				$data['iddiagnostico'] = $m->iddiagnostico;
				$data['codigo_cie'] = $m->codigo_cie;
				$data['descripcion_cie'] = $m->descripcion_cie;
			}

			$medicamentos = $this->diagnosticos_mdl->getMedicamentosChecked($data['iddiagnostico']);
			$data['medicamentos'] = $medicamentos;

			$productos = $this->diagnosticos_mdl->getProductosChecked($data['iddiagnostico']);
			$data['productos'] = $productos;

			$this->load->view('dsb/html/tablas_maestras/diagnosticos_add.php',$data);
		}
		else{
			redirect('/');
		}
 	}

}
