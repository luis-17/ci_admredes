<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');        
        $this->load->model('proveedor_mdl');
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

			$proveedores = $this->proveedor_mdl->getProveedores();
			$data['proveedores'] = $proveedores;	

			$this->load->view('dsb/html/proveedor/proveedor.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function habilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->habilitar($id);
		redirect ('proveedor');
	}

	public function inhabilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->inhabilitar($id);
		redirect ('proveedor');
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
			$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
			$data['data_tipoproveedor'] = $datatipoproveedor;
			$departamento = $this->proveedor_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$distrito = $this->proveedor_mdl->distrito($id);
			$distrito['distrito'] = $distrito;
			$data['nom'] = "Nuevo Proveedor";
			$data['accion'] = "Registrar Proveedor";
			$data['mensaje'] = "";
			$this->load->view('dsb/html/proveedor/form_proveedor.php',$data);
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

			$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
			$data['data_general'] = $dataproveedor;
			$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
			$data['data_tipoproveedor'] = $datatipoproveedor;
			$departamento = $this->proveedor_mdl->departamento();
			$data['departamento'] = $departamento;
			$provincia = $this->proveedor_mdl->provincia($id);
			$data['provincia'] = $provincia;
			$distrito = $this->proveedor_mdl->distrito($id);
			$data['distrito'] = $distrito;
			$data['accion'] = "Actualizar Proveedor";
			$this->load->view('dsb/html/proveedor/form_proveedor.php',$data);
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

			$data['tipoproveedor'] = $_POST['tipoproveedor'];
			$data['ruc'] = $_POST['ruc'];
			$data['codigosunasa'] = $_POST['codigosunasa'];
			$data['razonsocial'] = $_POST['razonsocial'];
			$data['nombrecomercial'] = $_POST['nombrecomercial'];
			$data['direccion'] = $_POST['direccion'];
			$data['referencia'] = $_POST['referencia'];
			$data['departamento'] = $_POST['dep'];
			$data['provincia'] = $_POST['prov'];
			$data['distrito'] = $_POST['dist'];
			$data['usuario'] = $_POST['usuario'];
			$data['contrasena'] = $_POST['contrasena'];
			$data['id'] = $_POST['idproveedor'];

			if($data['id']==0){
				$this->proveedor_mdl->in_usuario($data);
				$data['idusuario'] = $this->db->insert_id();
				$this->proveedor_mdl->in_proveedor($data);	

				echo "<script>
				alert('Los datos del proveedor han sido registrados con éxito.');window.location.assign('".base_url()."proveedor')
				</script>";

			}else{
				$data['idusuario'] = $_POST['idusuario'];
				$this->proveedor_mdl->up_usuario($data);
				$this->proveedor_mdl->up_proveedor($data);
				echo "<script>
				alert('Los datos del proveedor han sido actualizados con éxito.');window.location.assign('".base_url()."proveedor')
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

	public function proveedor_contactos($id)
	{
		$contactos = $this->proveedor_mdl->get_contactos($id);
		$data['id'] = $id;
		$data['contactos'] = $contactos;
		$data['contacto'] = "";
		$cargos = $this->proveedor_mdl->get_cargos();
		$data['cargos'] = $cargos;

		$this->load->view('dsb/html/proveedor/contactos_pr.php',$data);
	}

	public function seleccionar_contacto($id,$idp)
	{
		$contactos = $this->proveedor_mdl->get_contactos($idp);
		$data['contactos'] = $contactos;
		$contacto = $this->proveedor_mdl->get_contacto($id);
		$data['contacto'] = $contacto;
		$cargos = $this->proveedor_mdl->get_cargos();
		$data['cargos'] = $cargos;

		$this->load->view('dsb/html/proveedor/contactos_pr.php',$data);
	}

	public function guardar_contacto()
	{
		$data['idcp'] = $_POST['idcp'];
		$data['idp'] = $_POST['idp'];
		$data['nombres'] = $_POST['nombres'];
		$data['apellidos'] = $_POST['apellidos'];
		$data['telf'] = $_POST['telf'];
		$data['anexo'] = $_POST['anexo'];
		$data['movil'] = $_POST['movil'];
		$data['email'] = $_POST['email'];
		$data['envio'] = $_POST['envio'];
		$data['idcargo'] = $_POST['idcargo'];

		if($data['idcp']==0){
			$this->proveedor_mdl->add_contacto($data);
			echo "<script>
				alert('Los datos del contacto han sido registrados con éxito.');window.location.assign('".base_url()."proveedor_contactos/".$data['idp']."')
				</script>";

		}else{
			$this->proveedor_mdl->up_contacto($data);
			echo "<script>
				alert('Los datos del contacto han sido actualizados con éxito.');window.location.assign('".base_url()."proveedor_contactos/".$data['idp']."')
				</script>";
		}

	} 

	public function contacto_anular($idcp,$idp)
	{
		$this->proveedor_mdl->anularc($idcp);
		redirect("proveedor_contactos/$idp");
	}

	public function contacto_activar($idcp,$idp)
	{
		$this->proveedor_mdl->activarc($idcp);
		redirect("proveedor_contactos/$idp");
	}

}
