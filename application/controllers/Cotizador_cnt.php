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
			$idcotizaciondetalle = $_POST['idcotizaciondetalle'];
			$data['idcotizaciondetalle'] = $idcotizaciondetalle;
			$data['idusuario'] = $idusuario;
			$data['tiporesponsable'] = 'P';
			$data['cliente'] = $_POST['cliente'];
			$nombre_plan = $_POST['nombre_plan'];
			$data['nombre_plan'] = $nombre_plan;
			$data['carencia'] = $_POST['carencia'];
			$data['mora'] = $_POST['mora'];
			$data['atencion'] = $_POST['atencion'];
			$data['num_afiliados'] = $_POST['num_afiliados'];
			$data['tipo_plan'] = $_POST['tipo_plan'];
			$data['tipo_cotizacion'] = $_POST['tipo_cotizacion'];
			$estado = $_POST['estado'];

			if($idcotizaciondetalle == 0){
				$this->cotizador_mdl->in_Cotizacion($data);
				$data['idcotizacion'] = $this->db->insert_id();
				$this->cotizador_mdl->in_CotDetalle($data);
				$idCotDetalle = $this->db->insert_id();
				$data['idCotDetalle'] = $idCotDetalle;
				$this->cotizador_mdl->inCotUsuario($data);
			}else{
				$data['idcotizacion'] = $_POST['idcotizacion'];				
				$data['idCotDetalle'] = $idcotizaciondetalle;
				$idCotDetalle = $idcotizaciondetalle;
				$this->cotizador_mdl->upCotizacion($data);
				$this->cotizador_mdl->upCotDetalle($data);
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
			
			$data['estado'] = 0;
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

			$CotDetalle = $this->cotizador_mdl->getEstado($idCotDetalle);
			$data['estado'] = $CotDetalle['estado'];

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
			$CotDetalle = $this->cotizador_mdl->getEstado($id);
			$data['estado'] = $CotDetalle['estado'];
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

			$CotDetalle = $this->cotizador_mdl->getEstado($idcotizaciondetalle);
			$data['estado'] = $CotDetalle['estado'];

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
		$id = $_POST['idcotizaciondetalle'];
		$data['id'] = $id;				
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
 	
 	public function editar_cotizacion($idcotizaciondetalle,$estado){
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

			$data['estado'] = $estado;
			$data['id'] = $idcotizaciondetalle;

			$clientes = $this->cotizador_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['cotizacion'] = $this->cotizador_mdl->getCotizacionDetalle($idcotizaciondetalle);

			$data['accion'] = 'Nueva Cotización';

			$this->load->view('dsb/html/cotizador/cotizacion_plan.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function desaprobarCot($id){
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

			$data['id'] = $id;

			$CotDetalle = $this->cotizador_mdl->getEstado($id);
			$data['estado'] = $CotDetalle['estado'];

 			$this->load->view('dsb/html/cotizador/desaprobarCot.php',$data);
 		}
		else{
			redirect('/');
		}
 	}

 	public function aprobCot($id){
 		$data['id'] = $id;
 		$data['estado'] = 4;
		$this->cotizador_mdl->upEstadoCot($data);
		echo "<script>
				alert('Se aprobó la cotización con éxito.');
				location.href='".base_url()."index.php/cot_pendientes';
				</script>";	
 	}

 	public function revision_propuesta($id,$nom,$tit,$ad){
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
			/*
			$id = $_POST['idcotizaciondetalle'];
			$nom = $_POST['nom'];*/
			$data['idcotizaciondetalle'] = $id;
			$data['nom'] = $nom;
			$data['titular'] = $tit;
			$data['adicional'] = $ad;
			$CotDetalle = $this->cotizador_mdl->getEstado($id);
			$data['estado'] = $CotDetalle['estado'];
			$data['cot_detalle'] = $this->cotizador_mdl->getCoberturaDetalle($id);
			$data['cot_cobertura'] = $this->cotizador_mdl->getCoberturasCalculo($id);
			$data['coberturas'] = $this->cotizador_mdl->getCoberturas2($id);
			$data['coberturas2'] = $this->cotizador_mdl->getCoberturas3($id);
			$encabezado = $this->cotizador_mdl->getCotizacion($id);
			$data['cliente'] = $encabezado['nombre_comercial_cli'];
			$data['plan'] = $encabezado['nombre_cotizacion'];
			if($encabezado['tipo_plan']==1){
				$tipo_plan = 'Compulsivo';
			}else{
				$tipo_plan = 'Optativo';
			}
			$data['tipo_plan'] = $tipo_plan;
			if($encabezado['tipo_cotizacion']==1){
				$tipo_cotizacion = 'Mensual';
			}else{
				$tipo_cotizacion = 'Anual';
			}
			$data['tipo_cotizacion'] = $tipo_cotizacion;

			$data['carencia'] = $encabezado['dias_carencia'];
			$data['mora'] = $encabezado['dias_mora'];
			$data['atencion'] = $encabezado['dias_atencion'];
			$data['afiliados'] = $encabezado['num_afiliados'];
			$data['fecha_creacion'] = $encabezado['creacion'];
			$data['ejecutiva'] = $encabezado['nombres_col'].' '.$encabezado['ap_paterno_col'];

			$this->load->view('dsb/html/cotizador/revision_propuesta.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function guardar_desaprobacion()
 	{
 		$data['id'] = $_POST['id'];
 		$data['motivo'] = $_POST['motivo'];
 		if($_POST['estado']==3){ 			
 			$data['estado'] = 3;
 			$this->cotizador_mdl->upEstadoCot2($data);
			echo "<script>
					alert('Se enviaron los motivos de desaprobación de la cotización con éxito.');
					location.href='".base_url()."index.php/cot_pendientes';
				</script>";	
 		}else{
 			$data['estado'] = 5;
 			$this->cotizador_mdl->upEstadoCot3($data);
			echo "<script>
					alert('Se enviaron los motivos de rechazo del cliente con éxito.');
					location.href='".base_url()."index.php/cotizador';
				</script>";	
 		}
		
 	}

 	public function cotizacion_rechazo($id)
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
			$data['id'] = $id;
			$CotDetalle = $this->cotizador_mdl->getEstado($id);
			$data['estado'] = $CotDetalle['estado'];
			$encabezado = $this->cotizador_mdl->getCotizacion($id);
			$data['nom'] = $encabezado['nombre_cotizacion'];
			$data['observacion'] = $encabezado['observacion'];
			$data['observacion_cliente'] = $encabezado['observacion_cliente'];
			$this->load->view('dsb/html/cotizador/cotizacion_rechazo.php',$data);
 		}
		else{
			redirect('/');
		} 		
 	}

 	public function duplicar_data($id, $estado)
 	{
 		$user = $this->session->userdata('user');
		extract($user);
 		$cotizacion_det = $this->cotizador_mdl->getCotizacionDetalle($id);
 		$cotizacion_coberturas = $this->cotizador_mdl->getCotCoberturas($id);
 		foreach ($cotizacion_det as $cd) {
 			$data['idcotizacion'] = $cd->idcotizacion;
 			$data['estado'] = 1;
 			$data['nombre_cotizacion'] = $cd->nombre_cotizacion;
 			$data['dias_carencia'] = $cd->dias_carencia;
 			$data['dias_mora'] = $cd->dias_mora;
 			$data['dias_atencion'] = $cd->dias_atencion;
 			$data['num_afiliados'] = $cd->num_afiliados;
 			$data['tipo_plan'] = $cd->tipo_plan;
 			$data['poblacion'] = $cd->poblacion;
 			$data['siniestralidad_mensual'] = $cd->siniestralidad_mensual;
 			$data['gastos_administrativos'] = $cd->gastos_administrativos;
 			$data['gastos_marketing'] = $cd->gastos_marketing;
 			$data['reserva'] = $cd->reserva;
 			$data['inflacion_medica'] = $cd->inflacion_medica;
 			$data['remanente'] = $cd->remanente;
 			$data['costos_administrativos'] = $cd->costos_administrativos;
 			$data['prima_titular'] = $cd->prima_titular;
 			$data['prima_adicional'] = $cd->prima_adicional;
 			$data['tipo_cotizacion'] = $cd->tipo_cotizacion; 			
  		}
  		$this->cotizador_mdl->in_CotDetalle2($data);  		
  		$idcotizaciondetalle = $this->db->insert_id();
  		$data['idcotizaciondetalle'] = $idcotizaciondetalle;
  		$data['idCotDetalle'] = $idcotizaciondetalle;
  		$data['idusuario'] = $idusuario;
  		$data['tiporesponsable'] = 'P';

  		$this->cotizador_mdl->inCotUsuario($data);

  		foreach ($cotizacion_coberturas as $cc) {
  			$idcotizacioncobertura= $cc->idcotizacioncobertura;
  			$data['idvariableplan'] = $cc->idvariableplan;
  			$data['texto_web'] = $cc->texto_web;
  			$data['visible'] = $cc->visible;
  			$data['tiempo'] = $cc->tiempo;
  			$data['num_eventos'] = $cc->num_eventos;
  			$data['iniVig'] = $cc->iniVig;
  			$data['finVig'] = $cc->finVig;
  			$data['precio'] = $cc->precio;
  			$data['eventos_titular'] = $cc->eventos_titular;
  			$data['eventos_adicional'] = $cc->eventos_adicional;
  			$this->cotizador_mdl->in_cotCobeturas($data);
  			$idcotizacioncobertura2 = $this->db->insert_id();
  			$data['idcotizacioncobertura2'] = $idcotizacioncobertura2;

  			$cotCoaseguro = $this->cotizador_mdl->getCotCoaseguro($idcotizacioncobertura);
  			$cotProducto = $this->cotizador_mdl->cotProducto($idcotizacioncobertura);
  			foreach ($cotCoaseguro as $coa) {
  				$data['idoperador'] = $coa->idoperador;
  				$data['valor'] = $coa->valor;
  				$data['usuario_crea'] = $coa->$idusuario;
  				$this->cotizador_mdl->inCoaseguro2($data);
  			}

  			foreach ($cotProducto as $cp) {
  				$data['idproducto'] = $cp->idproducto;
  				$this->cotizador_mdl->inCotProducto($data);
  			}
  		}

  		redirect('index.php/editar_cotizacion/'.$idcotizaciondetalle.'/1');
 	}

 	public function cotizacion_aprobacion($id,$estado){
 		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;

			$data['id'] = $id;
			$data['estado'] = $estado;
			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;
			$encabezado = $this->cotizador_mdl->getCotizacion($id);
			$data['cliente'] = $encabezado['nombre_comercial_cli'];
			$data['plan'] = $encabezado['nombre_cotizacion'];
			if($encabezado['tipo_plan']==1){
				$tipo_plan = 'Compulsivo';
			}else{
				$tipo_plan = 'Optativo';
			}
			$data['tipo_plan'] = $tipo_plan;
			if($encabezado['tipo_cotizacion']==1){
				$tipo_cotizacion = 'Mensual';
			}else{
				$tipo_cotizacion = 'Anual';
			}
			$data['tipo_cotizacion'] = $tipo_cotizacion;
			$data['carencia'] = $encabezado['dias_carencia'];
			$data['mora'] = $encabezado['dias_mora'];
			$data['atencion'] = $encabezado['dias_atencion'];
			$data['afiliados'] = $encabezado['num_afiliados'];
			$data['fecha_creacion'] = $encabezado['creacion'];
			$data['ejecutiva'] = $encabezado['nombres_col'].' '.$encabezado['ap_paterno_col'];

			$this->load->view('dsb/html/cotizador/cotizacion_aprobacion.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function generar_cotPdf($id)
 	{
 	// aquí empieza
		date_default_timezone_set('America/Lima');
		$hoy = date('d/m/y H:i');
		$anio = date('Y');
		$fecha = date('Y-m-d');
		$fecha = substr($fecha, 0, 10);
	 	$numeroDia = date('d', strtotime($fecha));
	  	$dia = date('l', strtotime($fecha));
	  	$mes = date('F', strtotime($fecha));
	  	$anio = date('Y', strtotime($fecha));
	  	$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	  	$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	  	$nombredia = str_replace($dias_EN, $dias_ES, $dia);
	  	$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	  	$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	  	$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
	  //return $nombredia." ".$numeroDia." de ".$nombreMes;

		$CotDetalle = $this->cotizador_mdl->getEstado($id);
		$estado = $CotDetalle['estado'];
		$cot_detalle = $this->cotizador_mdl->getCoberturaDetalle($id);
		$cot_cobertura = $this->cotizador_mdl->getCoberturasCalculo($id);
		$coberturas = $this->cotizador_mdl->getCoberturas2($id);
		$coberturas2 = $this->cotizador_mdl->getCoberturas3($id);
		$encabezado = $this->cotizador_mdl->getCotizacion($id);
		$cliente = $encabezado['razon_social_cli'];
		$plan = $encabezado['nombre_cotizacion'];
		if($encabezado['tipo_plan']==1){
			$tipo_plan = 'Compulsivo';
		}else{
			$tipo_plan = 'Optativo';
		}
		$tipo_plan = $tipo_plan;
		if($encabezado['tipo_cotizacion']==1){
			$tipo_cotizacion = 'MENSUAL';
		}else{
			$tipo_cotizacion = 'ANUAL';
		}
		$tipo_cotizacion = $tipo_cotizacion;

		$carencia = $encabezado['dias_carencia'];
		$mora = $encabezado['dias_mora'];
		$atencion = $encabezado['dias_atencion'];
		$afiliados = $encabezado['num_afiliados'];
		$fecha_creacion = $encabezado['creacion'];
		$ejecutiva = $encabezado['nombres_col'].' '.$encabezado['ap_paterno_col'].' '.$encabezado['ap_materno_col'];
		$cargo = $encabezado['descripcion_car'];

		$this->load->library('Pdf');
	    $this->pdf = new Pdf();
	    // página 1
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Ln(25); 		
		$this->pdf->SetFont('Arial','I',11);
		$this->pdf->MultiCell(190,6,utf8_decode("Miraflores, ".$numeroDia." de ".$nombreMes." de ".$anio),0,'R');	
		$this->pdf->Ln();
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("Sres. ".$cliente),0,'J');	
		$this->pdf->Ln(); 	
		$this->pdf->MultiCell(190,6,utf8_decode("Propuesta: ".$plan),0,'J');	
		$this->pdf->Ln(); 
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->MultiCell(190,6,utf8_decode("Estimado: "),0,'J');	
		$this->pdf->Ln();
		$this->pdf->MultiCell(190,6,utf8_decode("Nos es grato saludarlo cordialmente y agradecerle la oportunidad de presentar nuestra empresa de asistencias médicas con la red en servicios ambulatorios de salud más grande del país."),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("Nuestros planes de salud son elaborados de acuerdo al perfil de nuestros afiliados y bajo los lineamientos e información que nos proporciona cada uno de nuestros clientes. En la actualidad trabajamos con los principales retails y empresas del país."),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("Cubrimos el 85% de las enfermedades más recurrentes de un hogar contando para ello, con una potente red médica de más de 120 clínicas y centros médicos distribuidos estratégicamente a nivel nacional."),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("Estamos convencidos de la competitividad de nuestras propuestas y de la excelente calidad de nuestros servicios. Estaremos atentos a podernos reunir pronto y otorgarle la mejor información, agradezco su atención prestada."),0,'J');
		$this->pdf->Ln(15); 
		$this->pdf->MultiCell(190,6,utf8_decode("Saludos Cordiales."),0,'J');
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode($ejecutiva),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode($cargo),0,'J');
		
		//página 2
		$this->pdf->AddPage();				    
		$this->pdf->AliasNbPages();	
		$this->pdf->Ln(20); 	
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("I. ANTECEDENTES DE LA EMPRESA"),0,'J');	
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->MultiCell(190,6,utf8_decode("Red Salud es una empresa que forma parte de Affinity International Holding. Nuestra labor está orientada básicamente a la creación, gestión, administración y control de productos y servicios masivos de salud, diseñados de acuerdo a las necesidades específicas de nuestros clientes y al canal mediante el cual se va a dirigir el producto o servicio."),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("En el Perú, somos la única empresa que cuenta con respaldo internacional y la infraestructura tecnológica para liquidación de siniestros masivos de salud. Contamos con más de 110 profesionales médicos y más de 100 centros médicos afiliados a nivel nacional."),0,'J');		
		$this->pdf->Ln();
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("II. SERVICIOS QUE OFRECEMOS"),0,'J');	
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->MultiCell(190,6,utf8_decode("En el Perú somos una nueva alternativa de servicios para compañías de seguros, reaseguradores, grupos autoasegurados, compañías de retail y productos affinity, que buscan crear valor agregado a sus productos y/o servicios."),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("Nuestras principales líneas de negocios son las siguientes:"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Exámenes Médicos Ocupacionales"),0,'J');		
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Campañas Preventivas de Salud Empresarial"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Chequeo General Adulto y/o infantil"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Médico a domicilio"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Asistencia escolar y universitaria."),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Asistencia integral para adultos mayores."),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Auditorias médicas."),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Tópicos médicos y de enfermería"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Ambulancia"),0,'J');
		$this->pdf->Ln(); 
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("III. NUESTROS CLIENTES"),0,'J');	
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->MultiCell(190,6,utf8_decode("En Red Salud trabajamos diariamente para contribuir al desarrollo de la sociedad en el sector salud, aportando una metodología empresarial moderna, que permita la sinergia social y comercial, para lo cual contamos con clientes como:"),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->MultiCell(190,6,utf8_decode("Nuestras principales líneas de negocios son las siguientes:"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Banco Internacional del Perú – Interbank"),0,'J');		
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Banco Cencosud (Tiendas Metro, Wong y Paris)"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Corredores de Seguros Falabella"),0,'J');
		$this->pdf->MultiCell(190,6,utf8_decode("      -	Banco Scotiabank"),0,'J');
		$this->pdf->Ln(); 

		//página 3
		$this->pdf->AddPage();				    
		$this->pdf->AliasNbPages();	
		$this->pdf->Ln(8); 	
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("IV. PROPUESTA COMERCIAL"),0,'J');	
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->MultiCell(190,6,utf8_decode("Mediante el presente documento, Red Salud presenta las siguiente propuesta de salud:"),0,'J');		
		$this->pdf->Ln(); 
		$this->pdf->SetFillColor(0,32,96);
		$this->pdf->SetTextColor(255,255,255); 
	    $this->pdf->SetFont('Arial','B',10);
	    $this->pdf->MultiCell(190,6,utf8_decode($plan),0,'C',true);
	    $this->pdf->Ln(3);	  
	    $this->pdf->SetFillColor(142,169,219);
		$this->pdf->SetTextColor(255,255,255); 
	    $this->pdf->SetFont('Arial','B',10);
	    $this->pdf->MultiCell(190,6,utf8_decode("COBERTURAS DE SALUD"),0,'C',true);
	    $this->pdf->Cell(110,6,utf8_decode("DESCRIPCIÓN"),0,0,'L',true);
		$this->pdf->Cell(50,6,utf8_decode("COASEGURO"),0,0,'C',true);
		$this->pdf->Cell(30,6,utf8_decode("EVENTOS"),0,0,'C',true);
		$this->pdf->Ln();
		foreach($coberturas as $c){ 	
			switch ($c->tiempo) {
				case '1 day':
					$tiempo = $c->num_eventos." diario(s)";
				break;
				case '1 month':
					$tiempo = $c->num_eventos." mensual(es)";
				break;
				case '1 year':
					$tiempo = $c->num_eventos." anual(s)";
				break;	
				default:
					$tiempo = "Ilimitados";
				break;
			}
			$this->pdf->SetFillColor(213,220,218);
			$this->pdf->SetTextColor(0,0,0); 
		    $this->pdf->SetFont('Arial','BI',10);
		    $this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var),0,'L',true);   
		    $this->pdf->SetFillColor(255,255,255);
			$this->pdf->SetTextColor(0,0,0); 
		    $this->pdf->SetFont('Arial','',10); 	
			$x=$this->pdf->GetX(); 
			$y=$this->pdf->GetY();
			$this->pdf->MultiCell(110,6,utf8_decode($c->texto_web),0,'J',true);
			$this->pdf->SetXY($x+110,$y);
			$this->pdf->MultiCell(50,6,utf8_decode($c->cobertura),0,'C',true);
			$this->pdf->SetXY($x+160,$y);
			$this->pdf->MultiCell(30,6,utf8_decode($tiempo),0,'C',true);
			$this->pdf->Ln();
		}

		//página 4
		$this->pdf->AddPage();				    
		$this->pdf->AliasNbPages();	
		$this->pdf->Ln(8); 	
		$this->pdf->SetFillColor(142,169,219);
		$this->pdf->SetTextColor(255,255,255); 
	    $this->pdf->SetFont('Arial','B',10);
	    $this->pdf->MultiCell(190,6,utf8_decode("COTIZACIÓN DE APORTES"),0,'C',true);
		$this->pdf->SetFillColor(213,220,218);
		$this->pdf->SetTextColor(0,0,0); 
		$this->pdf->SetFont('Arial','',10);
	    $this->pdf->Cell(120,6,utf8_decode("CANTIDAD MÍNIMA DE AFILIADOS"),0,0,'C',true);
		$this->pdf->Cell(70,6,utf8_decode("PRECIO DEL PLAN ".$tipo_cotizacion." (Inc. IGV)"),0,0,'C',true);
	    $this->pdf->Ln();	

	   	foreach($cot_detalle as $cd){
			$titular = $cd->prima_titular;
			$adicional = $cd->prima_adicional;
		} 
		for($i=0; $i<$afiliados; $i++){
			if($i==0){ 
				$desc = 'Titular';
			}else{
				$desc = "Titular + ".$i." dependiente(s)";
			}
			$prima = $titular + ($adicional*$i);
			$prima =  number_format((float)$prima, 2, '.', '');
			$this->pdf->SetFillColor(255,255,255);
			$this->pdf->SetTextColor(0,0,0); 
		    $this->pdf->SetFont('Arial','',10);
		    $this->pdf->Cell(120,6,utf8_decode($desc),0,0,'C',true);
			$this->pdf->Cell(70,6,utf8_decode("S/. ".$prima),0,0,'C',true);
		    $this->pdf->Ln();
		}     
		$y=$this->pdf->GetY();
		$this->pdf->Line(10, $y , 200, $y);  
		$this->pdf->Ln(10);
		$this->pdf->SetFont('Arial','B',11);
		$this->pdf->MultiCell(190,6,utf8_decode("V. CONDICIONES DE LA PROPUESTA"),0,'J');    
		$this->pdf->Ln();
		$this->pdf->SetFont('Arial','',11);
		foreach ($coberturas2 as $c2) {
			$this->pdf->MultiCell(190,6,utf8_decode($c2->nombre_var.": ".$c2->texto_web),0,'J');			
		}	
		$this->pdf->Output("Propuesta_".$id.".pdf", 'D');
 	}

 	public function bloqueo_cot($id){
		$eventos = $this->cotizador_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['id'] = $id;
		$data['coberturas'] = $this->cotizador_mdl->getCoberturasActivas($id);
		$data['bloqueos'] = $this->cotizador_mdl->getBloqueos($id);
		$this->load->view('dsb/html/cotizador/bloqueos.php',$data);
	}

	public function reg_bloqueo_cot(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$id = $_POST['id'];
		$data['id'] = $id;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['cob_bloqueada'] = $_POST['cob_bloqueada'];
		$this->cotizador_mdl->reg_bloqueo($data);

		echo "<script>
				alert('Se bloqueó la cobertura con éxito.');
				location.href='".base_url()."index.php/bloqueo_cot/".$id."';
				</script>";
 	}

 	public function anular_bloqueo_cot($idbloqueo,$id){
 		$this->cotizador_mdl->delete_bloqueo($idbloqueo);
 		echo "<script>
				alert('Se eliminó el bloqueo con éxito.');
				location.href='".base_url()."index.php/bloqueo_cot/".$id."';
				</script>";
 	}

 	function seleccionar_cobertura_cot($id, $nom, $iddet)
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

			$data['iddet'] = $iddet;
	 		$data['nom'] = $nom;
			$data['id'] = $id;

	 		$detalle = $this->cotizador_mdl->selecionar_cobertura($iddet);
			$data['detalle'] = $detalle;

			$cobertura = $this->cotizador_mdl->getCobertura($id);
			$data['cobertura'] = $cobertura;

			$items = $this->cotizador_mdl->getItems();
			$data['items'] = $items;

			$operador=$this->cotizador_mdl->get_operador();
			$data['operador'] = $operador;


			$CotDetalle = $this->cotizador_mdl->getEstado($id);
			$data['estado'] = $CotDetalle['estado'];

			$productos = $this->cotizador_mdl->get_productos2($iddet);
			if(!empty($productos)){
			$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
						<div class="col-sm-9">
							<table style="font-size:12px;"  width="100%">							
								<tr><td colspan="2"><input type="checkbox" id="checkTodos" /><b>Marcar/Desmarcar todos</b></td></tr>';
				$cont1=2;
				$cont2=0;
				foreach ($productos as $pr) {

					$link="window.location.href='".base_url()."index.php/".$pr->funcion."/".$pr->idproducto."/".$iddet."/".$id."/".$nom."'";

					if($cont1==2){
					$cadena.='<tr>';
					}
						$cadena.='<td width="1%"><input type="checkbox" onclick="'.$link.'" '.$pr->checked.'></td>
								<td width="49%">'.$pr->descripcion_prod.'</td>';

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
				$cadena="";
			}
			$data['cadena'] = $cadena;

			$this->load->view('dsb/html/cotizador/cotizador_cobertura.php',$data);
		}
		else{
			redirect('/');
		}
 		
 	}

 	public function eliminar_producto($idprod,$iddet,$id,$nom)
	{
		$this->cotizador_mdl->eliminar_producto($idprod,$iddet);
		redirect("index.php/seleccionar_cobertura_cot/".$id."/".$nom."/".$iddet);
	}

	public function insertar_producto($idprod,$iddet,$id,$nom)
	{
		$data['iddet'] = $iddet;
		$data['idprod'] = $idprod;

		$this->cotizador_mdl->insert_proddet($data);
		redirect("index.php/seleccionar_cobertura_cot/".$id."/".$nom."/".$iddet);
	}

 	public function aceptar_cliente($id){
 		
 	}

}