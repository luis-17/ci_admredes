<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class variables_plan_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('variable_mdl');
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

			$data['variable'] = $this->variable_mdl->getVariables();

			$this->load->view('dsb/html/variable/variables_plan.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function variable_anular($id){
		$this->variable_mdl->variable_anular($id);
		echo "<script>
				alert('Se anuló la cobertura con éxito.');
				location.href='".base_url()."index.php/variables_plan';
				</script>";
	}

	public function variable_activar($id){
		$this->variable_mdl->variable_activar($id);
		echo "<script>
				alert('Se reactivó la cobertura con éxito.');
				location.href='".base_url()."index.php/variables_plan';
				</script>";
	}

	public function variable_registrar(){

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

			$data['idvariable'] = 0;
			$data['nombre_variable'] = "";
			$data['descripcion'] = "";
			$data['accion'] = "Registrar Cobertura";
			$data['nom'] = "Nueva Cobertura";

			$this->load->view('dsb/html/variable/variable_editar.php',$data);
		}
		else{
			redirect('/');
		}			
	}

	public function editar_variable($id){

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

			$variable = $this->variable_mdl->getVariable($id);
			foreach ($variable as $v) {
				$data['idvariable'] = $v->idvariableplan;
				$data['nombre_variable'] = $v->nombre_var;
				$data['descripcion'] = $v->observaciones;
				$data['nom'] = $v->nombre_var;
			}			
			$data['accion'] = "Actualizar Cobertura";			

			$this->load->view('dsb/html/variable/variable_editar.php',$data);
		}
		else{
			redirect('/');
		}			
	}

	public function variable_guardar(){
		$data['idvariable'] = $_POST['idvariable'];
		$data['nombre_var'] = $_POST['nombre'];
		$data['descripcion'] = $_POST['descripcion'];

		if($data['idvariable']==0){
			$this->variable_mdl->insert_variable($data);
			echo "<script>
				alert('Se registró la cobertura con éxito.');
				location.href='".base_url()."index.php/variables_plan';
				</script>";
		}else{
			$this->variable_mdl->update_variable($data);
			echo "<script>
				alert('Se actualizó la cobertura con éxito.');
				location.href='".base_url()."index.php/variables_plan';
				</script>";
		}
	}

	public function detalle_variable($id){
		$data['id'] = $id;
		$data['idprod'] = 0;
		$data['descripcion'] = "";
		$data['accion'] = "Agregar Detalle";
 		$data['productos'] = $this->variable_mdl->getProductos($id);
		$this->load->view('dsb/html/variable/productos_var.php',$data);
	}

	public function seleccionar_detalle($id){
		$producto = $this->variable_mdl->getProducto($id);

		foreach ($producto as $p) {
			$data['id'] = $p->idvariableplan;
			$data['idprod'] = $p->idproducto;
			$data['descripcion'] = $p->descripcion_prod;
			$data['accion'] = "Actualizar Detalle";
		}

		$data['productos'] = $this->variable_mdl->getProductos($data['id']);
		$this->load->view('dsb/html/variable/productos_var.php',$data);

	}

	public function save_producto(){
		$data['idvariableplan'] = $_POST['idvar'];
		$idvar = $_POST['idvar'];
		$id = $_POST['idproducto'];
		$data['descripcion_prod'] = $_POST['descripcion'];
		$descripcion = $_POST['descripcion']; 
		$data['idespecialidad'] = 4;


		if($id==0){
			if($idvar=1){
				$descripcion = strtoupper($descripcion);
				$data['descripcion'] = $descripcion;
				$this->variable_mdl->inEspecialidad($data);
				$data['idespecialidad'] = $this->db->insert_id();
			}

			$this->variable_mdl->insert_producto($data);
			echo "<script>
				alert('Se registró el detalle con éxito.');
				location.href='".base_url()."index.php/detalle_variable/".$data['idvariableplan']."';
				</script>";
		}
		else{
			$data['idproducto'] = $id;
			$this->variable_mdl->up_producto($data);
			echo "<script>
				alert('Se actualizó el detalle con éxito.');
				location.href='".base_url()."index.php/detalle_variable/".$data['idvariableplan']."';
				</script>";
		}
	}

	public function delete_producto($id,$idvar){
		$prod = $this->variable_mdl->validar_producto($id);

		if(count($prod)>0){
			echo "<script>
				alert('Error: El detalle seleccionado está vigente para una de las coberturas de los planes activos.');
				location.href='".base_url()."index.php/detalle_variable/".$idvar."';
				</script>";
		}else{
			$this->variable_mdl->delete_detalle($id);
			echo "<script>
				alert('Se eliminó el detalle seleccionado con éxito.');
				location.href='".base_url()."index.php/detalle_variable/".$idvar."';
				</script>";
		}
	}
}