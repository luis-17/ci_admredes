<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cotizador_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('cotizador_mdl');
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

			$planes = $this->cotizador_mdl->getCotizaciones();
			$data['planes'] = $planes;
			$data['planes2'] = $this->cotizador_mdl->getCotizaciones2();

			$this->load->view('dsb/html/cotizador/cotizacion.php',$data);
		}
		else{
			redirect('/');
		}
	}


	public function guardar_cotizacion()
	{
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');
			$user = $this->session->userdata('user');
			extract($user);			
			$idplan = $_POST['idplan'];
			$data['idplan'] = $idplan;
			$data['idusuario'] = $idusuario;
			$data['tiporesponsable'] = 'P';
			$data['idplan'] = $_POST['idplan'];
			$data['cliente'] = $_POST['cliente'];
			$nombre_plan = $_POST['nombre_plan'];
			$data['nombre_plan'] = $nombre_plan;
			$data['carencia'] = $_POST['carencia'];
			$data['mora'] = $_POST['mora'];
			$data['atencion'] = $_POST['atencion'];
			$data['num_afiliados'] = $_POST['num_afiliados'];
			$data['tipo_plan'] = $_POST['tipo_plan'];
			$data['tipo_cotizacion'] = $_POST['tipo_cotizacion'];

			if($idplan == 0){
				$this->cotizador_mdl->in_Cotizacion($data);
				$data['idcotizacion'] = $this->db->insert_id();
				$this->cotizador_mdl->in_CotDetalle($data);
				$idCotDetalle = $this->db->insert_id();
				$data['idCotDetalle'] = $idCotDetalle;
				$this->cotizador_mdl->inCotUsuario($data);
			}else{

			}
 
			redirect('index.php/coberturas_cotizacion/'.$idCotDetalle.'/'.$nombre_plan);
			
		}
		else{
			redirect('/');
		}
		
	}


	public function nueva_cotizacion()
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

			$data['nom'] = 'Nueva Cotización';
			$data['id'] = 0;

			$clientes = $this->cotizador_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['accion'] = 'Nueva Cotización';

			$this->load->view('dsb/html/cotizador/cotizacion_plan.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function coberturas_cotizacion($idCotDetalle,$nombre_plan)
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

			$cobertura = $this->cotizador_mdl->getCobertura($idCotDetalle);
			$data['cobertura'] = $cobertura;

			$items = $this->cotizador_mdl->getItems();
			$data['items'] = $items;	

			$operador=$this->cotizador_mdl->get_operador();
			$data['operador'] = $operador;

			$data['nom'] = $nombre_plan;
			$data['id'] = $idCotDetalle;
			$CotDetalle = $this->cotizador_mdl->getEstado($idCotDetalle);
			$data['estado'] = $CotDetalle['estado'];
			$data['iddet'] = 0;
			$data['cadena'] = "";

			$this->load->view('dsb/html/cotizador/cotizador_cobertura.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function detalle_producto()
	{
		$item = $_POST['id'];

		$productos = $this->cotizador_mdl->get_productos($item);
		if(!empty($productos)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
					<div class="col-sm-9">
						<table style="font-size: 12px;" width="100%">
							<tr><td colspan="2"><input type="checkbox"  id="checkTodos"><b>Marcar/Desmarcar Todos</b></td></tr>';
			$cont1=2;
			$cont2=0;
			foreach ($productos as $pr) {
				if($cont1==2){
				$cadena.='<tr>';
				}
					$cadena.="<td width='1%'><input type='checkbox' name='prod[]' value='".$pr->idproducto."'></td>
							<td width='49%'>".$pr->descripcion_prod."</td>";

				if($cont2==1){
					$cont1=2;
				}else{
					$cont1=0;
				}
				$cont2++;
				if($cont1==2){
				$cadena.='</tr>';
				$cont2=0;	
				}			
			}

		$cadena.='</table></div>';
		$cadena.="<script>
					$('document').ready(function(){
					   $('#checkTodos').change(function () {
					      $('input:checkbox').prop('checked', $(this).prop('checked'));
					  });
					});
					</script>";
		}else{
			$cadena="<input type='hidden' id='prod' name='prod' value=''>";
		}

		$precio = $this->cotizador_mdl->get_precio($item);		
		$precio_sugerido = $precio['precio_sugerido'];
		if($precio_sugerido>0){
			$cadena.='<div class="col-xs-12 alert alert-info">
							<div class="form-group">						
								<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Costo por Evento: </label>
								<div class="col-sm-6">
									<input type="text" id="precio_sugerido" name="precio_sugerido" value="'.$precio_sugerido.'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Eventos a Cotizar (Titular): </label>
								<div class="col-sm-6">
									<input type="text" id="eventost" name="eventost" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Eventos a Cotizar (Adicional): </label>
								<div class="col-sm-6">
									<input type="text" id="eventosa" name="eventosa" value="">
								</div>
							</div>
						</div>';
		}else{
			$cadena.='<input type="hidden" name="precio_sugerido" value="">
						<input type="hidden" name="eventost" value="">
						<input type="hidden" name="eventosa" value="">';
		}
		
		echo $cadena;
	}

	public function guardar_cotcobertura(){
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$accion = $_POST['guardar'];
			if($accion=='guardar'){

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;

			$data['nom'] = $_POST['nom'];
			$data['id'] = $_POST['idcotizaciondetalle'];
			$data['iddet'] = $_POST['idcotizacioncobertura'];
			$data['item'] = $_POST['item'];
			$data['descripcion'] = $_POST['descripcion'];
			$data['visible'] = $_POST['visible'];
			$data['precio'] = $_POST['precio_sugerido'];
			$data['e_titular'] = $_POST['eventost'];
			$data['e_adicional'] = $_POST['eventosa'];

			if($_POST['inicio']==1){
				$num1=$_POST['num'];
				$tiempo1=$_POST['tiempo'];
				$data['iniVig'] = $num1.' '.$tiempo1;
			}else{
				$data['iniVig'] = 0;
			}

			if($_POST['fin']==1){
				$num2=$_POST['num2'];
				$tiempo2=$_POST['tiempo2'];
				$data['finVig'] = $num2.' '.$tiempo2;
			}else{
				$data['finVig'] = 0;
			}

					$caso=1;
					$this->cotizador_mdl->insert_cobertura($data);
					$data['iddet'] = $this->db->insert_id();
					$prod = $_POST['prod'];
					if(!empty($prod)){
					$cont = count($prod);
						for($i=0;$i<$cont;$i++){
						$data['idprod'] = $prod[$i];
						$this->cotizador_mdl->insert_proddet($data);
						
						}
					}
			}/*else{
					$this->cotizador_mdl->update_cobertura($data);
				$caso=2;
			}

			if($caso==2){
				$user = $this->session->userdata('user');
				extract($user);

				$cob = $this->cotizador_mdl->selecionar_cobertura2($data['iddet']);
			}	*/	

			$cobertura = $this->cotizador_mdl->getCobertura($data['id']);
			$data['cobertura'] = $cobertura;
			$items = $this->cotizador_mdl->getItems();
			$data['items'] = $items;
			$data['iddet'] = 0;
			$data['cadena'] = "";
			$operador=$this->cotizador_mdl->get_operador();
			$data['operador'] = $operador;

			$id = $_POST['idcotizaciondetalle'];
			$nom = $_POST['nom'];

			redirect('index.php/coberturas_cotizacion/'.$id.'/'.$nom);
		}
		else{
			redirect('/');
		}		
	}

	public function cotizacion_variables($idCotDetalle, $nom)
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

			$variables = $this->cotizador_mdl->getVariables($idCotDetalle);

			$data['poblacion'] = $variables['poblacion'];
			$data['siniestralidad'] = $variables['siniestralidad_mensual'];
			$data['g_administrativos'] = $variables['gastos_administrativos'];
			$data['marketing'] = $variables['gastos_marketing'];
			$data['reserva'] = $variables['reserva'];
			$data['inflacion'] = $variables['inflacion_medica'];
			$data['remanente'] = $variables['remanente'];
			$data['c_administrativos'] = $variables['costos_administrativos'];
			$data['id'] = $idCotDetalle;
			$data['nom'] = $nom;

			$this->load->view('dsb/html/cotizador/cotizacion_variables.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function guardar_variables(){
		$idcotizaciondetalle = $_POST['idcotizaciondetalle'];
		$nom = $_POST['nom'];
		$data['idcotizaciondetalle'] = $idcotizaciondetalle;
		$data['poblacion'] = $_POST['personast'];
		$data['siniestralidad'] = $_POST['siniestralidadt'];
		$data['g_administrativos'] = $_POST['gastos_administrativost'];
		$data['marketing'] = $_POST['marketingt'];
		$data['reserva'] = $_POST['reservat'];
		$data['inflacion'] = $_POST['inflaciont'];
		$data['remanente'] = $_POST['remanentet'];
		$data['c_administrativos'] = $_POST['costos_administrativost'];

		$this->cotizador_mdl->up_variables($data);
		redirect('index.php/visualizar_cotizacion/'.$idcotizaciondetalle.'/'.$nom);
	}

	public function visualizar_cotizacion($idCotDetalle, $nom){
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
			$data['id'] = $idCotDetalle;

			$data['cot_detalle'] = $this->cotizador_mdl->getCoberturaDetalle($idCotDetalle);
			$data['cot_cobertura'] = $this->cotizador_mdl->getCoberturasCalculo($idCotDetalle);

			$this->load->view('dsb/html/cotizador/cotizacion_calculo.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function guardar_primas()
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
			$id = $_POST['idcotizaciondetalle'];
			$nom = $_POST['nom'];
			$data['idcotizaciondetalle'] = $id;
			$data['nom'] = $nom;
			$data['titular'] = $_POST['titular'];
			$data['adicional'] = $_POST['adicional'];
			$this->cotizador_mdl->upPrimas($data);
			redirect('index.php/propuesta_comercial/'.$id.'/'.$nom);
		}
		else{
			redirect('/');
		}
	}

	public function propuesta_comercial($idcotizaciondetalle, $nom){
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

			$data['idcotizaciondetalle'] = $idcotizaciondetalle;
			$data['nom'] = $nom;
			$data['coberturas'] = $this->cotizador_mdl->getCoberturas2($idcotizaciondetalle);
			$data['coberturas2'] = $this->cotizador_mdl->getCoberturas3($idcotizaciondetalle);
			$data['cot_detalle'] = $this->cotizador_mdl->getCoberturaDetalle($idcotizaciondetalle);

			$this->load->view('dsb/html/cotizador/propuesta_comercial.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function coaseguro_cotizacion($id, $nom){
		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['operador'] = $this->cotizador_mdl->getOperador();
		$data['coaseguros'] = $this->cotizador_mdl->getCoaseguros($id);
		$this->load->view('dsb/html/cotizador/coaseguros.php',$data);
 	}

 	public function reg_cotcoaseguro(){
 		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['idoperador'] = $_POST['operador'];
		$data['valor'] = $_POST['valor'];
		$id = $_POST['id'];
		$nom = $_POST['nom'];
		$data['id'] = $id;

		$this->cotizador_mdl->inCoaseguro($data);
		echo "<script>
				alert('Se registró el coaseguro con éxito.');
				location.href='".base_url()."index.php/coaseguro_cotizacion/".$id."/".$nom."';
				</script>";
 	}

 	public function eventos_cotizacion($id)
	{
		$eventos = $this->cotizador_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['num'] = $eventos['num_eventos'];
		$data['tiempo'] = $eventos['tiempo'];
		$data['id'] = $id;
		$this->load->view('dsb/html/cotizador/eventos.php',$data);
	}

	public function reg_evento_cotizacion()
	{
		$data['id'] = $_POST['id'];		
		$accion = $_POST['guardar'];

		if($accion=="guardar"){
			$data['num_eventos'] = $_POST['numero'];
			$data['tiempo'] = $_POST['periodo'];	
			$this->cotizador_mdl->upEventos($data);
			echo "<script>
				alert('Se actualizó el número de eventos con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";		
		}else{
			$data['num_eventos']=0;
			$data['tiempo'] ="";
			$this->cotizador_mdl->upEventos($data);
			echo "<script>
				alert('Se eliminó el número de eventos con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
		}
		
	}

	public function sol_apGerencia()
	{
		$data['id'] = $_POST['idcotizaciondetalle'];
		$data['estado'] = 2;

		$this->cotizador_mdl->upEstadoCot($data);
		echo "<script>
			alert('Se envió la solicitud de aprobación a Gerencia con éxito.');
			location.href='".base_url()."index.php/cotizador';
			</script>";		
	}

	public function cot_pendientes()
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

			$data['planes2'] = $this->cotizador_mdl->getCotizaciones3();

			$this->load->view('dsb/html/cotizador/cot_pendientes.php',$data);
		}
		else{
			redirect('/');
		}
	}
 	
}