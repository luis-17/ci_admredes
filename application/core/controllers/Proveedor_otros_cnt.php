<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_otros_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');        
        $this->load->model('proveedor_otros_mdl'); 
        $this->load->library("pagination");        
        $this->load->library('My_PHPMailer');
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

			$proveedores = $this->proveedor_otros_mdl->getProveedores();
			$data['proveedores'] = $proveedores;	

			$this->load->view('dsb/html/proveedor_otros/proveedor_otros.php',$data);
		}
		else{
			redirect('/');
		}
	}

	
	public function nuevo()
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
			$id=0;
			$dataproveedor = $this->proveedor_otros_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$departamento = $this->proveedor_otros_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_otros_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$distrito = $this->proveedor_otros_mdl->distrito($id);
			$distrito['distrito'] = $distrito;
			$data['nom'] = "Nuevo Proveedor";
			$data['accion'] = "Registrar Proveedor";
			$data['mensaje'] = "";
			$this->load->view('dsb/html/proveedor_otros/form_proveedor_otros.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_editar($id)
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

			$dataproveedor = $this->proveedor_otros_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$departamento = $this->proveedor_otros_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_otros_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$distrito = $this->proveedor_otros_mdl->distrito($id);
			$data['distrito'] = $distrito;
			$data['accion'] = "Actualizar Centro Médico";
			$this->load->view('dsb/html/proveedor_otros/form_proveedor_otros.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_guardar()
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

			$data['ruc'] = $_POST['ruc'];
			$data['id'] = $_POST['idproveedor'];
			$data['razonsocial'] = $_POST['razonsocial'];
			$data['nombrecomercial'] = $_POST['nombrecomercial'];
			$nombrecomercial = $_POST['nombrecomercial'];
			$data['idusuario'] = $idusuario;
			$data['direccion'] = $_POST['direccion'];
			$data['referencia'] = $_POST['referencia'];
			$data['departamento'] = $_POST['dep'];
			$data['provincia'] = $_POST['prov'];
			$data['distrito'] = $_POST['dist'];
			
 
			if($data['id']==0){
				$this->proveedor_otros_mdl->in_proveedor($data);	

				echo "<script>
				alert('Los datos del proveedor han sido registrados con éxito.');
				window.location.assign('".base_url()."index.php/otros_proveedores')
				</script>";

			}else{
				$this->proveedor_otros_mdl->up_proveedor($data);
				echo "<script>
				alert('Los datos del proveedor han sido actualizados con éxito.');
				window.location.assign('".base_url()."index.php/otros_proveedores')
				</script>";
			}				
		}
		else{
			redirect('/');
		}
	}

	public function provincia()
	{
		$dep = $_POST['dep'];

		$options='<option value="">Seleccionar</option>';
		$provincia = $this->afiliacion_mdl->provincia($dep);

		foreach ($provincia as $p) {
			$options.='<option value="'.$p->idprovincia.'">'.$p->descripcion_ubig.'</option>';
		}		

		echo $options;
	}

	public function distrito()
	{
		$prov = $_POST['prov'];

		$options='<option value="">Seleccionar</option>';
		$distrito = $this->afiliacion_mdl->distrito($prov);

		foreach ($distrito as $d) {
			$options.='<option value="'.$d->iddistrito.'">'.$d->descripcion_ubig.'</option>';
		}		

		echo $options;
	}

	public function nuevo_otro_proveedor()
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
			$id=0;
			$dataproveedor = $this->proveedor_otros_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$departamento = $this->proveedor_otros_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_otros_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$distrito = $this->proveedor_otros_mdl->distrito($id);
			$distrito['distrito'] = $distrito;
			$data['nom'] = "Nuevo Proveedor";
			$data['accion'] = "Registrar Proveedor";
			$data['mensaje'] = "";
			$this->load->view('dsb/html/proveedor_otros/proveedor_otros_nuevo.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_otros_guardar2()
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

			$ruc = $_POST['ruc'];
			$data['ruc'] = $ruc;
			$data['id'] = $_POST['idproveedor'];
			$data['razonsocial'] = $_POST['razonsocial'];
			$data['nombrecomercial'] = $_POST['nombrecomercial'];
			$nombrecomercial = $_POST['nombrecomercial'];
			$data['idusuario'] = $idusuario;
			$data['direccion'] = $_POST['direccion'];
			$data['referencia'] = $_POST['referencia'];
			$data['departamento'] = $_POST['dep'];
			$data['provincia'] = $_POST['prov'];
			$data['distrito'] = $_POST['dist'];
			
				$this->proveedor_otros_mdl->in_proveedor($data);	

				echo "<script>
				alert('Los datos del proveedor han sido registrados con éxito.');
				location.href='".base_url()."index.php/mesa_partes3/".$ruc."';
				</script>";
		}
		else{
			redirect('/');
		}
	}

}
