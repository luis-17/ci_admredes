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
		$user = $this->session->userdata('user');
		extract($user);

		$anular_proveedor = $this->proveedor_mdl->habilitar($id);
		redirect ('index.php/proveedor');
	}

	public function inhabilitar($id)
	{
		$user = $this->session->userdata('user');
		extract($user);

		$anular_proveedor = $this->proveedor_mdl->inhabilitar($id);
		redirect ('index.php/proveedor');
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
			$data['banco'] = $this->proveedor_mdl->getBancos();
			$data['forma'] =$this->proveedor_mdl->getFormaPago();
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
			$data['banco'] = $this->proveedor_mdl->getBancos();
			$data['forma'] =$this->proveedor_mdl->getFormaPago();
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
			$data['banco'] = $_POST['banco'];
			$data['forma_pago'] = $_POST['forma_pago'];
			$data['cta_corriente'] = $_POST['cta_corriente'];
			$data['cta_detracciones'] = $_POST['cta_detracciones'];
 
			if($data['id']==0){
				$this->proveedor_mdl->in_usuario($data);
				$data['idusuario2'] = $this->db->insert_id();
				$this->proveedor_mdl->in_proveedor($data);	

				echo "<script>
				alert('Los datos del proveedor han sido registrados con éxito.');window.location.assign('".base_url()."index.php/proveedor')
				</script>";

			}else{
				$data['idusuario2'] = $_POST['user_id'];
				$this->proveedor_mdl->up_usuario($data);
				$this->proveedor_mdl->up_proveedor($data);
				echo "<script>
				alert('Los datos del proveedor han sido actualizados con éxito.');window.location.assign('".base_url()."index.php/proveedor')
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
				alert('Los datos del contacto han sido registrados con éxito.');window.location.assign('".base_url()."index.php/proveedor_contactos/".$data['idp']."')
				</script>";

		}else{
			$this->proveedor_mdl->up_contacto($data);
			echo "<script>
				alert('Los datos del contacto han sido actualizados con éxito.');window.location.assign('".base_url()."index.php/proveedor_contactos/".$data['idp']."')
				</script>";
		}

	} 

	public function contacto_anular($idcp,$idp)
	{
		$this->proveedor_mdl->anularc($idcp);
		redirect("index.php/proveedor_contactos/$idp");
	}

	public function contacto_activar($idcp,$idp)
	{
		$this->proveedor_mdl->activarc($idcp);
		redirect("index.php/proveedor_contactos/$idp");
	}


	public function servicios()
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

			$data['servicios'] = $this->proveedor_mdl->getServicios();

			$this->load->view('dsb/html/tablas_maestras/servicios.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function nuevo_servicio()
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

			$data['servicios'] = $this->proveedor_mdl->getServicios();

			$data['id_servicio'] = 0;

			$data['titulo'] = 'Nuevo Servicio';
			$data['descripcion'] = '';
			$data['file'] = '';

			$this->load->view('dsb/html/tablas_maestras/nuevo_servicio.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function editar_servicio($id)
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

			$data['servicios'] = $this->proveedor_mdl->getServicios();

			$data['id_servicio'] = $id;

			$servicio = $this->proveedor_mdl->getServicio($id);

			$data['titulo'] = 'Editar Servicio';
			$data['descripcion'] = $servicio['serv_descripcion'];
			$data['file'] = '';

			$this->load->view('dsb/html/tablas_maestras/nuevo_servicio.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function save_servicio()
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

			$id_servicio = $_POST['id_servicio'];
			$data['id_servicio'] = $id_servicio;
			$data['descripcion'] = $_POST['descripcion'];

			if($id_servicio==0){
				$this->proveedor_mdl->inServicio($data);
				$id_servicio = $this->db->insert_id();
			}else{
				$this->proveedor_mdl->upServicio($data);
			}

	        $config['upload_path'] = './iconos/servicios/';
	        $config['file_name'] = $id_servicio;
	        $config['allowed_types'] = 'png';
	        $config['max_size'] = 500000;
	        $config['max_width'] = 20000;
	        $config['max_height'] = 20000;

	        $this->load->library('upload', $config);
	        
	        if (!$this->upload->do_upload('mi_archivo')) {
	            //*** ocurrio un error
	            $data['uploadError'] = $this->upload->display_errors();
	            echo $this->upload->display_errors();
	            return;
	        }

	        $data['uploadSuccess'] = $this->upload->data();

	        $data['id_servicio'] = 0;

			$data['titulo'] = 'Nuevo Servicio';
			$data['descripcion'] = '';

			$this->load->view('dsb/html/tablas_maestras/nuevo_servicio.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function proveedor_servicios($id)
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

			$data['idproveedor'] = $id;
			$data['servicios'] = $this->proveedor_mdl->getServicios();
			$data['servicios_proveedor'] = $this->proveedor_mdl->getServiciosProveedor($id);

			$this->load->view('dsb/html/proveedor/proveedor_servicios.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function guardar_servicio()
	{
		$idproveedor = $_POST['idproveedor'];
		$data['idproveedor'] = $idproveedor;
		$data['id_servicio'] = $_POST['id_servicio'];
		$data['ini'] = $_POST['ini'];
		$data['fin'] = $_POST['fin'];

		$this->proveedor_mdl->inServicioProveedor($data);

		echo "<script>
					alert('Se registró el servicio con éxito.');
					location.href='".base_url()."index.php/proveedor_servicios/".$idproveedor."';
					</script>";
 	}

 	public function eliminar_servicio($idproveedor_servicio,$idproveedor)
 	{
 		$this->proveedor_mdl->eliminar_servicio($idproveedor_servicio);
 		echo "<script>
					alert('Se eliminó el servicio con éxito.');
					location.href='".base_url()."index.php/proveedor_servicios/".$idproveedor."';
					</script>";
 	}

 	public function capacitaciones()
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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	      	$fecha = date('Y-m-d');
			$nuevafecha = strtotime ( '-2 month' , strtotime ( $fecha ) ) ;
			$month2 = date ( 'm' , $nuevafecha );

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month2, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$data['capacitaciones'] = $this->proveedor_mdl->getCapacitaciones($data);

			$this->load->view('dsb/html/proveedor/capacitaciones.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function capacitaciones2()
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

			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$data['capacitaciones'] = $this->proveedor_mdl->getCapacitaciones($data);

			$this->load->view('dsb/html/proveedor/capacitaciones.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function nueva_capacitacion()
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

			$data['idcapacitacion'] = 0;
	 		$data['accion'] = 'Registrar Capacitación';
	 		$data['fecha'] = date('Y-m-d');
	 		$data['hora'] = '09:00:00';
	 		$data['coordinado'] = '';
	 		$data['idproveedor'] = '';
	 		$data['idusuario_asignado'] = '';
	 		$data['proveedores'] = $this->proveedor_mdl->getProveedoresActivos();
	 		$data['usuarios'] = $this->proveedor_mdl->getUsuarios();

			$this->load->view('dsb/html/proveedor/nueva_capacitacion.php',$data);
		}
		else{
			redirect('/');
		}	
 	}

 	public function editar_capacitacion($id){
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

			$data['idcapacitacion'] = $id;
	 		$data['accion'] = 'Actualizar Capacitación';
	 		$capacitacion = $this->proveedor_mdl->getCapacitacion($id);
	 		$data['fecha'] = $capacitacion['fecha_programada'];
	 		$data['hora'] = $capacitacion['hora_programada'];
	 		$data['coordinado'] = $capacitacion['personal_coordino'];
	 		$data['idproveedor'] = $capacitacion['idproveedor'];
	 		$data['idusuario_asignado'] = $capacitacion['idusuario_asignado'];
	 		$data['proveedores'] = $this->proveedor_mdl->getProveedoresActivos();
	 		$data['usuarios'] = $this->proveedor_mdl->getUsuarios();

			$this->load->view('dsb/html/proveedor/nueva_capacitacion.php',$data);
		}
		else{
			redirect('/');
		}	
 	}

 	public function capacitacion_guardar()
 	{
 		$user = $this->session->userdata('user');
		extract($user);
		$idcapacitacion = $_POST['idcapacitacion'];
		$data['idusuario_registra'] = $idusuario;
		$data['fecha_registra'] = date('Y-d-m H:i:s');
		$data['idusuario_asignado'] = $_POST['idusuario_asignado'];
		$data['fecha_programada'] = $_POST['fecha'];
		$data['hora_programada'] = $_POST['hora'];
		$data['idproveedor'] = $_POST['idproveedor'];
		$data['coordinado'] = $_POST['coordinado'];

		if($idcapacitacion==0){
			$this->proveedor_mdl->inCapacitacion($data);

			echo "<script>
					alert('Se registró la capacitación con éxito.');
					location.href='".base_url()."index.php/capacitaciones';
					</script>";
		}else{
			$data['idcapacitacion'] = $idcapacitacion;
			$this->proveedor_mdl->upCapacitacion($data);

			echo "<script>
					alert('Se reprogramó la capacitación con éxito.');
					location.href='".base_url()."index.php/capacitaciones';
					</script>";
		}		
		
 	}

 	public function cerrar_capacitacion($id, $estado)
 	{
 		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$data['idcapacitacion'] = $id;
			$capacitacion = $this->proveedor_mdl->getCapacitacion($id);
			$data['proveedor'] = $capacitacion['nombre_comercial_pr'];
			$data['capacitador'] = $capacitacion['colaborador'];
			$data['fecha'] = $capacitacion['fecha'];
			$data['hora'] = $capacitacion['hora_programada'];
			$data['comentario'] = $capacitacion['comentario'];
			$data['estado'] = $capacitacion['estado'];
			$data['estado2'] = $estado;
			$this->load->view('dsb/html/proveedor/cerrar_capacitacion.php',$data);
		}
		else{
			redirect('/');
		}	
 	}

 	public function save_finCapacitacion(){
 		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');
			$idcapacitacion = $_POST['idcapacitacion'];
			$data['idcapacitacion'] = $idcapacitacion;
			$data['comentario'] = $_POST['comentario'];
			$estado2 = $_POST['estado2'];
			$data['estado'] = $estado2;

			if($estado2==2){

				$config['upload_path'] = './uploads/capacitaciones/';
		        $config['file_name'] = $idcapacitacion;
		        $config['allowed_types'] = 'pdf';
		        $config['max_size'] = 500000;
		        $config['max_width'] = 20000;
		        $config['max_height'] = 20000;

		        $this->load->library('upload', $config);
		        
		        if (!$this->upload->do_upload('mi_archivo')) {
		            //*** ocurrio un error
		            $data['uploadError'] = $this->upload->display_errors();
		            echo $this->upload->display_errors();
		            return;
		        }
		        $data['uploadSuccess'] = $this->upload->data();
		        $mensaje = "Se registró cierre de la capacitación con éxito";
			}else{
				$mensaje = "Se canceló capacitación con éxito";
			}


	        $this->proveedor_mdl->save_Cap($data);

			echo "<script>
					alert('".$mensaje.".');
					parent.location.reload(true);
					parent.$.fancybox.close();
					location.href='".base_url()."index.php/capacitaciones';
					</script>";
		}
		else{
			redirect('/');
		}	
 	}

}
