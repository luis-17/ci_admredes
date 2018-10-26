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
		$data['cadena'] = "";

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
				
		if($_POST['eventos']==""){
			$data['num_eventos'] = "";
		}else{
			$data['num_eventos'] = $_POST['num_eventos'];
		}
		
		if($_POST['flg_liqui']=='No'){
			$data['valor'] = '';
			$data['operador'] = '';
			}else{
				$data['valor'] = $_POST['valor'];
				$data['operador'] = $_POST['operador'];
		}

		if($_POST['idplandetalle']==0){
			$this->plan_mdl->insert_cobertura($data);
			$data['iddet'] = $this->db->insert_id();
			$prod = $_POST['prod'];
			if(!empty($prod)){
			$cont = count($prod);
				for($i=0;$i<$cont;$i++){
				$data['idprod'] = $prod[$i];
				$this->plan_mdl->insert_proddet($data);
				}
			}
		}else{
				$this->plan_mdl->update_cobertura($data);
		}

		$cobertura = $this->plan_mdl->getCobertura($data['id']);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;
		$data['iddet'] = 0;
		$data['cadena'] = "";
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

		$productos = $this->plan_mdl->get_productos2($iddet);
		if(!empty($productos)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
					<div class="col-sm-9">
						<table>';
			foreach ($productos as $pr) {

				$link="window.location.href='".base_url()."index.php/".$pr->funcion."/".$pr->idproducto."/".$iddet."/".$id."/".$nom."'";

				$cadena.='<tr>
							<td><input type="checkbox" onclick="'.$link.'" '.$pr->checked.'></td>
							<td>'.$pr->descripcion_prod.'</td>
						</tr>';
			}
			$cadena.='</table></div>';
		}else{
			$cadena="";
		}
		$data['cadena'] = $cadena;

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

 	public function detalle_producto()
	{
		$item = $_POST['id'];

		$productos = $this->plan_mdl->get_productos($item);
		if(!empty($productos)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
					<div class="col-sm-9">
						<table>';
			foreach ($productos as $pr) {
				$cadena.="<tr>
							<td><input type='checkbox' name='prod[]' value='".$pr->idproducto."'></td>
							<td>".$pr->descripcion_prod."</td>
						</tr>";
			}

		$cadena.='</table></div>';
		}else{
			$cadena="<input type='hidden' id='prod' name='prod' value=''>";
		}

		echo $cadena;
	}

	public function eliminar_producto($idprod,$iddet,$id,$nom)
	{
		$this->plan_mdl->eliminar_producto($idprod,$iddet);
		redirect("index.php/seleccionar_cobertura/".$id."/".$nom."/".$iddet);
	}

	public function insertar_producto($idprod,$iddet,$id,$nom)
	{
		$data['iddet'] = $iddet;
		$data['idprod'] = $idprod
		;

		$this->plan_mdl->insert_proddet($data);
		redirect("index.php/seleccionar_cobertura/".$id."/".$nom."/".$iddet);
	}
}