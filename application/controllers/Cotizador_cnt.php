<?php
ini_set('max_execution_time', 6000);
ini_set('memory_limit', -1);
date_default_timezone_set('America/Lima');
defined('BASEPATH') OR exit('No direct script access allowed');
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizador_cnt extends CI_Controller {

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
	public function cotizador()
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

			$cotizaciones = $this->cotizador_mdl->getCotizaciones();
			$data['cotizaciones'] = $cotizaciones;
			$cotizaciones2 = $this->cotizador_mdl->getCotizaciones2();
			$data['cotizaciones2'] = $cotizaciones2;

			$this->load->view('dsb/html/plan/cotizador.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function cotizador_registrar()
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

			$data['accion'] = 'Registrar Cotización';

			$this->load->view('dsb/html/plan/cotizador_editar.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function cotizador_guardar()
	{
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['tiporesponsable'] = 'P';
		$data['idcotizacion'] = $_POST['idcotizacion'];
		$data['cliente'] = $_POST['cliente'];
		$data['nombre_cotizacion'] = $_POST['nombre_cotizacion'];
		$data['codigo_cotizacion'] = $_POST['codigo_cotizacion'];
		$data['carencia'] = $_POST['carencia'];
		$data['mora'] = $_POST['mora'];
		$data['atencion'] = $_POST['atencion'];
		//$data['prima'] = $_POST['prima'];
		//$data['prima_adicional'] = $_POST['prima_adicional'];
		$data['num_afiliados'] = $_POST['num_afiliados'];
		$data['flg_activar'] = $_POST['flg_activar'];
		$data['flg_cancelar'] = $_POST['flg_cancelar'];
		$data['flg_dependientes'] = $_POST['flg_dependientes'];

		if($_POST['idcotizacion']==0):
			$this->cotizador_mdl->insert_cotizacion($data);
			$data['idcotizacion'] = $this->db->insert_Id();	
			$this->cotizador_mdl->inCotiUsuario($data);
			$idcoti = $data['idcotizacion'];
			$this->cotizador_mdl->insert_preDetalle($idcoti, 1);

			//$asunto="NOTIFICACION: CREACION DE UN PLAN DE SALUD";
			//$accion="creaci&oacute;n";
		else:
			$this->cotizador_mdl->update_plan($data);
			//$asunto="NOTIFICACION: ACTUALIZACION DE PLAN DE SALUD: ".$data['nombre_plan'];
			//$accion="actualizaci&oacute;n";
		endif;
		//redirect('index.php/cotizador_calcular');
	}

	public function cotizador_calcular(){
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

			$data['nom'] = 'Calcular Cotización';

			$items = $this->cotizador_mdl->getItems();
			$data['items'] = $items;	

			/*$cotizaciones = $this->cotizador_mdl->getCotizaciones();
			$data['cotizaciones'] = $cotizaciones;*/
			$cotizaciones = $this->cotizador_mdl->getCotizaciones3();
			foreach ($cotizaciones as $ct) {
				$data['idcotizacion'] = $ct->idcotizacion;
				$idcotizacion = $data['idcotizacion'];
				$data['nombre_cotizacion'] = $ct->nombre_cotizacion;
				$nombre_cotizacion = $data['nombre_cotizacion'];
			}

			$cobertura = $this->cotizador_mdl->getDetalleCobertura($idcotizacion);
			foreach ($cobertura as $c) {
				$data['idcotizaciondetalle'] = $c->idcotizaciondetalle;
				$idcotizaciondetalle = $data['idcotizaciondetalle'];
			}

			$detallecobertura = $this->cotizador_mdl->getCobertura($idcotizaciondetalle);
			foreach ($detallecobertura as $dc) {
				$data['idcotizacioncobertura'] = $dc->idcotizaciondetalle;
				$idcotizacioncobertura = $data['idcotizacioncobertura'];
			}

			$data['cotizaciones'] = $cotizaciones;

			$data['accion'] = 'Nueva Cotización';
			$data['iddet'] = 0;
			$data['cadena'] = "";

			$this->load->view('dsb/html/plan/cotizador_calcular.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function mostrarTabla(){
		$html = null;
		$idcotizacion = $_POST['idcotizacion'];
		$idcotizaciondetalle = $_POST['idcotizaciondetalle'];

		$detallecobertura = $this->cotizador_mdl->getCoberturaVariables($idcotizaciondetalle);
		$cotizacionDetalle = $this->cotizador_mdl->getDetalleCobertura($idcotizaciondetalle);
		$cotizacionDetalleArray = json_decode(json_encode($cotizacionDetalle), true);
		$cotizacionDetallestring = array_values($cotizacionDetalleArray)[0];
		$poblacion = $cotizacionDetallestring['poblacion_persona'];
		$siniestralidadM = $cotizacionDetallestring['siniestralidad_mensual'];

		$user = $this->session->userdata('user');
		extract($user);

		$html .= "<div align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Cobertura</th>";
						$html .= "<th>Descripción</th>";
						$html .= "<th>N° de eventos</th>";
						$html .= "<th>N° de eventos (Adicional)</th>";
						$html .= "<th>Costo por evento (S/.)</th>";
						$html .= "<th>Costo anual (S/.)</th>";
						$html .= "<th>Costo anual adicional (S/.)</th>";
						$html .= "<th>Coaseguro</th>";
						$html .= "<th>Opciones</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";
				$sumaanual = 0;
				$sumaanualadicional = 0;
				foreach ($detallecobertura as $dc) {
					$html .= "<tr>";
						$html .= "<td>".$dc->nombre_var."<input type='text' class='hidden' id='idcotizacioncobertura' name='idcotizacioncobertura[]' value='".$dc->idcotizacioncobertura."'></td>";
						$html .= "<td>".$dc->texto_web."<input type='text' class='hidden' id='texto_web' name='texto_web[]' value='".$dc->texto_web."'></td>";
						if ($dc->idcalcular == null) {
							$html .= "<td><input type='number' id='eventos' name='eventos[]' value='' required><input type='text' class='hidden' id='idcalcular' name='idcalcular[]' value=''></td>";
							$html .= "<td><input type='number' id='eventosAd' name='eventosAd[]' value='' required></td>";
							$html .= "<td><input type='number' id='costo' name='costo[]' value='' required></td>";
							$html .= "<td></td>";
							$html .= "<td></td>";
							if (empty($dc->cobertura)) {
								$html .= "<td><a class='boton fancybox' data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
							} else {
								$html .= "<td><a href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
							}
							$html .= "<td></td>";
						} else {
							$html .= "<td>".$dc->neventos."<input type='text' class='hidden' id='eventos' name='eventos[]' value='".$dc->neventos."'><input type='text' class='hidden' id='idcalcular' name='idcalcular[]' value='".$dc->idcalcular."'></td>";
							$html .= "<td>".$dc->neventosadicional."<input type='text' class='hidden' id='eventosAd' name='eventosAd[]' value='".$dc->neventosadicional."'></td>";
							$html .= "<td>S/. ".$dc->costo."<input type='text' class='hidden' id='costo' name='costo[]' value='".$dc->costo."'></td>";
							$anual = $dc->neventos*$dc->costo;
							$html .= "<td>S/. ".$anual."<input type='text' class='hidden' id='costoAnual' name='costoAnual[]' value='".$anual."'></td>";
							$anualadic = $dc->neventosadicional*$dc->costo;
							$html .= "<td>S/. ".$anualadic."<input type='text' class='hidden' id='costoAnualAdic' name='costoAnualAdic[]' value='".$anualadic."'></td>";
							if (empty($dc->cobertura)) {
							$html .= "<td><a class='boton fancybox' data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
							} else {
								$html .= "<td><a href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
							}
							$html .= "<td></td>";
							$sumaanual = $sumaanual + $anual;
							$sumaanualadicional = $sumaanualadicional + $anualadic;
						}
					$html .= "</tr>";
				}
				if (empty($sumaanual)) {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
				} else {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. ".$sumaanual."</td>";
						$html .= "<td>S/. ".$sumaanualadicional."</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
				}

				if (empty($poblacion)) {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='personas' name='personas' value='' required></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='siniMensual' name='siniMensual' value='' required></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
				} else {

					//prima plan
					$gastoAnualPersona = $sumaanual/12;
					$siniestralidadSoles = $gastoAnualPersona*$siniestralidadM;
					$gastosAdm = $siniestralidadSoles*0.25;
					$siniGastosAdm = $siniestralidadSoles+$gastosAdm;
					$gastosMkt = $siniGastosAdm*0.05;
					$siniGastAdmGastMkt = $siniGastosAdm+$gastosMkt;
					$costosAdm = $siniGastAdmGastMkt*0.05;
					$gastos = $siniGastAdmGastMkt+$costosAdm;
					$reserva = $gastos*0.1;
					$gastosTotal = $gastos+$reserva;
					$primaMinIgv = ($gastosTotal/$poblacion)*1.18;
					$inflMedica = $primaMinIgv*0.05;
					$prima = $primaMinIgv+$inflMedica;
					$primaDec=number_format((float)$prima, 2, '.', ',');

					//prima plan adicional
					$gastoAnualPersonaAd = $sumaanualadicional/12;
					$siniestralidadSolesAd = $gastoAnualPersonaAd*$siniestralidadM;
					$gastosAdmAd = $siniestralidadSolesAd*0.25; 
					$siniGastosAdmAd = $siniestralidadSolesAd+$gastosAdmAd;
					$gastosMktAd = $siniGastosAdmAd*0.05;
					$siniGastAdmGastMktAd = $siniGastosAdmAd+$gastosMktAd;
					$costosAdmAd = $siniGastAdmGastMktAd*0.05;
					$gastosAd = $siniGastAdmGastMktAd+$costosAdmAd;
					$reservaAd = $gastosAd*0.1;
					$gastosTotalAd = $gastosAd+$reservaAd;
					$primaMinIgvAd = ($gastosTotalAd/$poblacion)*1.18;
					$inflMedicaAd = $primaMinIgvAd*0.05;
					$primaAd = $primaMinIgvAd+$inflMedicaAd;
					$primaDecAd=number_format((float)$primaAd, 2, '.', ',');

					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'>".$poblacion."<input type='hidden' id='personas' name='personas' value='".$poblacion."'></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'>".$siniestralidadM."<input type='hidden' id='siniMensual' name='siniMensual' value='".$siniestralidadM."'></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDec."</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDecAd."</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
					$html .= "</tr>";
				}
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		echo json_encode($html);
	}

	public function agregarCobertura(){
		$html = null;
		$idcotizacion = $_POST['idcotizacion'];
		$idcotizaciondetalle = $_POST['idcotizaciondetalle'];
		$idvariableplan = $_POST['item'];
		$texto_web = $_POST['descripcion'];

		$this->cotizador_mdl->insertCobertura($idcotizaciondetalle, $idvariableplan, $texto_web);

		$detallecobertura = $this->cotizador_mdl->getCoberturaVariables($idcotizaciondetalle);
		$cotizacionDetalle = $this->cotizador_mdl->getDetalleCobertura($idcotizaciondetalle);
		$cotizacionDetalleArray = json_decode(json_encode($cotizacionDetalle), true);
		$cotizacionDetallestring = array_values($cotizacionDetalleArray)[0];
		$poblacion = $cotizacionDetallestring['poblacion_persona'];
		$siniestralidadM = $cotizacionDetallestring['siniestralidad_mensual'];

		$user = $this->session->userdata('user');
		extract($user);

		$html .= "<div align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Cobertura</th>";
						$html .= "<th>Descripción</th>";
						$html .= "<th>N° de eventos</th>";
						$html .= "<th>N° de eventos (Adicional)</th>";
						$html .= "<th>Costo por evento (S/.)</th>";
						$html .= "<th>Costo anual (S/.)</th>";
						$html .= "<th>Costo anual adicional (S/.)</th>";
						$html .= "<th>Coaseguro</th>";
						$html .= "<th>Opciones</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";
				$sumaanual = 0;
				$sumaanualadicional = 0;
				foreach ($detallecobertura as $dc) {
					$html .= "<tr>";
						$html .= "<td>".$dc->nombre_var."<input type='text' class='hidden' id='idcotizacioncobertura' name='idcotizacioncobertura[]' value='".$dc->idcotizacioncobertura."'></td>";
						$html .= "<td>".$dc->texto_web."<input type='text' class='hidden' id='texto_web' name='texto_web[]' value='".$dc->texto_web."'></td>";
						if ($dc->idcalcular == null) {
							$html .= "<td><input type='number' id='eventos' name='eventos[]' value='' required><input type='text' class='hidden' id='idcalcular' name='idcalcular[]' value=''></td>";
							$html .= "<td><input type='number' id='eventosAd' name='eventosAd[]' value='' required></td>";
							$html .= "<td><input type='number' id='costo' name='costo[]' value='' required></td>";
							$html .= "<td></td>";
							$html .= "<td></td>";
							if (empty($dc->cobertura)) {
								$html .= "<td><a class='boton fancybox'  data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
							} else {
								$html .= "<td><a class='boton fancybox'  data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
							}
							$html .= "<td></td>";
						} else {
							$html .= "<td>".$dc->neventos."<input type='text' class='hidden' id='eventos' name='eventos[]' value='".$dc->neventos."'><input type='text' class='hidden' id='idcalcular' name='idcalcular[]' value='".$dc->idcalcular."'></td>";
							$html .= "<td>".$dc->neventosadicional."<input type='text' class='hidden' id='eventosAd' name='eventosAd[]' value='".$dc->neventosadicional."'></td>";
							$html .= "<td>S/. ".$dc->costo."<input type='text' class='hidden' id='costo' name='costo[]' value='".$dc->costo."'></td>";
							$anual = $dc->neventos*$dc->costo;
							$html .= "<td>S/. ".$anual."<input type='text' class='hidden' id='costoAnual' name='costoAnual[]' value='".$anual."'></td>";
							$anualadic = $dc->neventosadicional*$dc->costo;
							$html .= "<td>S/. ".$anualadic."<input type='text' class='hidden' id='costoAnualAdic' name='costoAnualAdic[]' value='".$anualadic."'></td>";
							if (empty($dc->cobertura)) {
								$html .= "<td><a class='boton fancybox' data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
							} else {
								$html .= "<td><a href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
							}
							$html .= "<td></td>";
							$sumaanual = $sumaanual + $anual;
							$sumaanualadicional = $sumaanualadicional + $anualadic;
						}
					$html .= "</tr>";
				}
				if (empty($sumaanual)) {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
				} else {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. ".$sumaanual."</td>";
						$html .= "<td>S/. ".$sumaanualadicional."</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
				}

				if (empty($poblacion)) {
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='personas' name='personas' value='' required></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='siniMensual' name='siniMensual' value='' required></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
				} else {

					//prima plan
					$gastoAnualPersona = $sumaanual/12;
					$siniestralidadSoles = $gastoAnualPersona*$siniestralidadM;
					$gastosAdm = $siniestralidadSoles*0.25;
					$siniGastosAdm = $siniestralidadSoles+$gastosAdm;
					$gastosMkt = $siniGastosAdm*0.05;
					$siniGastAdmGastMkt = $siniGastosAdm+$gastosMkt;
					$costosAdm = $siniGastAdmGastMkt*0.05;
					$gastos = $siniGastAdmGastMkt+$costosAdm;
					$reserva = $gastos*0.1;
					$gastosTotal = $gastos+$reserva;
					$primaMinIgv = ($gastosTotal/$poblacion)*1.18;
					$inflMedica = $primaMinIgv*0.05;
					$prima = $primaMinIgv+$inflMedica;
					$primaDec=number_format((float)$prima, 2, '.', ',');

					//prima plan adicional
					$gastoAnualPersonaAd = $sumaanualadicional/12;
					$siniestralidadSolesAd = $gastoAnualPersonaAd*$siniestralidadM;
					$gastosAdmAd = $siniestralidadSolesAd*0.25; 
					$siniGastosAdmAd = $siniestralidadSolesAd+$gastosAdmAd;
					$gastosMktAd = $siniGastosAdmAd*0.05;
					$siniGastAdmGastMktAd = $siniGastosAdmAd+$gastosMktAd;
					$costosAdmAd = $siniGastAdmGastMktAd*0.05;
					$gastosAd = $siniGastAdmGastMktAd+$costosAdmAd;
					$reservaAd = $gastosAd*0.1;
					$gastosTotalAd = $gastosAd+$reservaAd;
					$primaMinIgvAd = ($gastosTotalAd/$poblacion)*1.18;
					$inflMedicaAd = $primaMinIgvAd*0.05;
					$primaAd = $primaMinIgvAd+$inflMedicaAd;
					$primaDecAd=number_format((float)$primaAd, 2, '.', ',');

					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'>".$poblacion."<input type='hidden' id='personas' name='personas' value='".$poblacion."'></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'>".$siniestralidadM."<input type='hidden' id='siniMensual' name='siniMensual' value='".$siniestralidadM."'></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDec."</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDecAd."</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
					$html .= "</tr>";
				}
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		echo json_encode($html);	
	}

	public function calcularCobertura(){
		$html = null;
		$idcotizacion = $_POST['idcotizacion'];
		$idcotizaciondetalle = $_POST['idcotizaciondetalle'];
		$idvariableplan = $_POST['item'];
		$texto_web = $_POST['descripcion'];
		$eventos = $_POST['eventos'];
		$eventosAd = $_POST['eventosAd'];
		$costo = $_POST['costo'];
		$idcalcular = $_POST['idcalcular'];
		$personas = $_POST['personas'];
		$siniMensual = $_POST['siniMensual'];
		$idcotizacioncobertura = $_POST['idcotizacioncobertura'];


		for ($i=0; $i < count($eventos); $i++) { 
			if (empty($idcalcular[$i])) {
				$this->cotizador_mdl->insertVariables($eventos[$i], $eventosAd[$i], $costo[$i], $idcotizacioncobertura[$i]);
			} else {
				$this->cotizador_mdl->updateVariables($eventos[$i], $eventosAd[$i], $costo[$i], $idcotizacioncobertura[$i], $idcalcular[$i]);
			}
		}

		if (empty($personas) || empty($siniMensual)) {
			$this->cotizador_mdl->inserteDetalles($personas, $siniMensual, $idcotizaciondetalle);
		} else {
			$this->cotizador_mdl->updateDetalles($personas, $siniMensual, $idcotizaciondetalle);
		}
		
		$detallecobertura = $this->cotizador_mdl->getCoberturaVariables($idcotizaciondetalle);
		$cotizacionDetalle = $this->cotizador_mdl->getDetalleCobertura($idcotizaciondetalle);
		$cotizacionDetalleArray = json_decode(json_encode($cotizacionDetalle), true);
		$cotizacionDetallestring = array_values($cotizacionDetalleArray)[0];
		$poblacion = $cotizacionDetallestring['poblacion_persona'];
		$siniestralidadM = $cotizacionDetallestring['siniestralidad_mensual'];

		$user = $this->session->userdata('user');
		extract($user);

		$html .= "<div align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Cobertura</th>";
						$html .= "<th>Descripción</th>";
						$html .= "<th>N° de eventos</th>";
						$html .= "<th>N° de eventos (Adicional)</th>";
						$html .= "<th>Costo por evento (S/.)</th>";
						$html .= "<th>Costo anual (S/.)</th>";
						$html .= "<th>Costo anual adicional (S/.)</th>";
						$html .= "<th>Coaseguro</th>";
						$html .= "<th>Opciones</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";
				$sumaanual = 0;
				$sumaanualadicional = 0;
				foreach ($detallecobertura as $dc) {
					$html .= "<tr>";
						$html .= "<td>".$dc->nombre_var."<input type='text' class='hidden' id='nombre_var' name='nombre_var[]' value='".$dc->nombre_var."'></td>";
						$html .= "<td>".$dc->texto_web."<input type='text' class='hidden' id='texto_web' name='texto_web[]' value='".$dc->texto_web."'></td>";
						$html .= "<td>".$dc->neventos."<input type='text' class='hidden' id='eventos' name='eventos[]' value='".$dc->neventos."'></td>";
						$html .= "<td>".$dc->neventosadicional."<input type='text' class='hidden' id='eventosAd' name='eventosAd[]' value='".$dc->neventosadicional."'></td>";
						$html .= "<td>S/. ".$dc->costo."<input type='text' class='hidden' id='costo' name='costo[]' value='".$dc->costo."'></td>";
						$anual = $dc->neventos*$dc->costo;
						$html .= "<td>S/. ".$anual."<input type='text' class='hidden' id='costoAnual' name='costoAnual[]' value='".$anual."'></td>";
						$anualadic = $dc->neventosadicional*$dc->costo;
						$html .= "<td>S/. ".$anualadic."<input type='text' class='hidden' id='costoAnualAdic' name='costoAnualAdic[]' value='".$anualadic."'></td>";
						if (empty($dc->cobertura)) {
							$html .= "<td><a class='boton fancybox' data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
						} else {
							$html .= "<td><a href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
						}
						$html .= "<td></td>";
					$html .= "</tr>";
					$sumaanual = $sumaanual + $anual;
					$sumaanualadicional = $sumaanualadicional + $anualadic;
				}

					//prima plan
					$gastoAnualPersona = $sumaanual/12;
					$siniestralidadSoles = $gastoAnualPersona*$siniestralidadM;
					$gastosAdm = $siniestralidadSoles*0.25;
					$siniGastosAdm = $siniestralidadSoles+$gastosAdm;
					$gastosMkt = $siniGastosAdm*0.05;
					$siniGastAdmGastMkt = $siniGastosAdm+$gastosMkt;
					$costosAdm = $siniGastAdmGastMkt*0.05;
					$gastos = $siniGastAdmGastMkt+$costosAdm;
					$reserva = $gastos*0.1;
					$gastosTotal = $gastos+$reserva;
					$primaMinIgv = ($gastosTotal/$poblacion)*1.18;
					$inflMedica = $primaMinIgv*0.05;
					$prima = $primaMinIgv+$inflMedica;
					$primaDec=number_format((float)$prima, 2, '.', ',');

					//prima plan adicional
					$gastoAnualPersonaAd = $sumaanualadicional/12;
					$siniestralidadSolesAd = $gastoAnualPersonaAd*$siniestralidadM;
					$gastosAdmAd = $siniestralidadSolesAd*0.25; 
					$siniGastosAdmAd = $siniestralidadSolesAd+$gastosAdmAd;
					$gastosMktAd = $siniGastosAdmAd*0.05;
					$siniGastAdmGastMktAd = $siniGastosAdmAd+$gastosMktAd;
					$costosAdmAd = $siniGastAdmGastMktAd*0.05;
					$gastosAd = $siniGastAdmGastMktAd+$costosAdmAd;
					$reservaAd = $gastosAd*0.1;
					$gastosTotalAd = $gastosAd+$reservaAd;
					$primaMinIgvAd = ($gastosTotalAd/$poblacion)*1.18;
					$inflMedicaAd = $primaMinIgvAd*0.05;
					$primaAd = $primaMinIgvAd+$inflMedicaAd;
					$primaDecAd=number_format((float)$primaAd, 2, '.', ',');

					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. ".$sumaanual."</td>";
						$html .= "<td>S/. ".$sumaanualadicional."</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'>".$poblacion."<input type='hidden' id='personas' name='personas' value='".$poblacion."'></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'>".$siniestralidadM."<input type='hidden' id='siniMensual' name='siniMensual' value='".$siniestralidadM."'></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDec."</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. ".$primaDecAd."</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. </td>";
					$html .= "</tr>";
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		echo json_encode($html);	
	}

	public function editarCobertura(){
		$html = null;
		$idcotizacion = $_POST['idcotizacion'];
		$idcotizaciondetalle = $_POST['idcotizaciondetalle'];
		$idvariableplan = $_POST['item'];
		$texto_web = $_POST['descripcion'];
		$eventos = $_POST['eventos'];
		$eventosAd = $_POST['eventosAd'];
		$costo = $_POST['costo'];
		//$idcotizacioncobertura = $_POST['idcotizacioncobertura'];
		$idcalcular = $_POST['idcalcular'];
		$personas = $_POST['personas'];
		$siniMensual = $_POST['siniMensual'];
		
		$detallecobertura = $this->cotizador_mdl->getCoberturaVariables($idcotizaciondetalle);
		$cotizacionDetalle = $this->cotizador_mdl->getDetalleCobertura($idcotizaciondetalle);
		$cotizacionDetalleArray = json_decode(json_encode($cotizacionDetalle), true);
		$cotizacionDetallestring = array_values($cotizacionDetalleArray)[0];
		$poblacion = $cotizacionDetallestring['poblacion_persona'];
		$siniestralidadM = $cotizacionDetallestring['siniestralidad_mensual'];

		$user = $this->session->userdata('user');
		extract($user);

		$html .= "<div align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Cobertura</th>";
						$html .= "<th>Descripción</th>";
						$html .= "<th>N° de eventos</th>";
						$html .= "<th>N° de eventos (Adicional)</th>";
						$html .= "<th>Costo por evento (S/.)</th>";
						$html .= "<th>Costo anual (S/.)</th>";
						$html .= "<th>Costo anual adicional (S/.)</th>";
						$html .= "<th>Coaseguro</th>";
						$html .= "<th>Opciones</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";
				$sumaanual = 0;
				$sumaanualadicional = 0;
				foreach ($detallecobertura as $dc) {
					$html .= "<tr>";
						$html .= "<td>".$dc->nombre_var."<input type='text' class='hidden' id='idcotizacioncobertura' name='idcotizacioncobertura[]' value='".$dc->idcotizacioncobertura."'></td>";
						$html .= "<td>".$dc->texto_web."<input type='text' class='hidden' id='texto_web' name='texto_web[]' value='".$dc->texto_web."'></td>";
						$html .= "<td><input type='number' id='eventos' name='eventos[]' value='".$dc->neventos."' required><input type='text' class='hidden' id='idcalcular' name='idcalcular[]' value='".$dc->idcalcular."'></td>";
						$html .= "<td><input type='number' id='eventosAd' name='eventosAd[]' value='".$dc->neventosadicional."' required></td>";
						$html .= "<td><input type='number' id='costo' name='costo[]' value='".$dc->costo."' required></td>";
						$anual = $dc->neventos*$dc->costo;
						$html .= "<td>S/. 0.00<input type='text' class='hidden' id='costoAnual' name='costoAnual[]' value='".$anual."'></td>";
						$anualadic = $dc->neventosadicional*$dc->costo;
						$html .= "<td>S/. 0.00<input type='text' class='hidden' id='costoAnualAdic' name='costoAnualAdic[]' value='".$anualadic."'></td>";
						if (empty($dc->cobertura)) {
							$html .= "<td><a class='boton fancybox' data-fancybox-width='800' data-fancybox-height='700' href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Agregar Coaseguro'><i class='ace-icon glyphicon glyphicon-plus red'></i></a></td>";
						} else {
							$html .= "<td><a href='".base_url()."index.php/cot_coaseguro/".$dc->idcotizaciondetalle."' title='Eliminar Coaseguro'><i class='ace-icon glyphicon glyphicon-remove blue'></i></a>".$dc->cobertura."</td>";
						}
						$html .= "<td></td>";
					$html .= "</tr>";
					$sumaanual = $sumaanual + $anual;
					$sumaanualadicional = $sumaanualadicional + $anualadic;
				}
					$html .= "<tr>";
						$html .= "<td align='right' colspan='5'><b>Total anual:</b></td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td>S/. 0.00</td>";
						$html .= "<td></td>";
						$html .= "<td></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Población personas:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='personas' name='personas' value='".$poblacion."'></td>";
						$html .= "<td align='right' colspan='3'><b>Siniestralidad mensual:</b></td>";
						$html .= "<td colspan='2'><input type='number' id='siniMensual' name='siniMensual' value='".$siniestralidadM."'></td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Prima:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Prima Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
					$html .= "<tr>";
						$html .= "<td align='right' colspan='2'><b>Copago:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
						$html .= "<td align='right' colspan='3'><b>Copago Adicional:</b></td>";
						$html .= "<td colspan='2'>S/. 0.00</td>";
					$html .= "</tr>";
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		echo json_encode($html);
	}

 	public function cot_coaseguro($id){
 		$eventos = $this->cotizador_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['id'] = $id;
		$data['operador'] = $this->cotizador_mdl->getOperador();
		$data['coaseguros'] = $this->cotizador_mdl->getCoaseguros($id);
		$this->load->view('dsb/html/plan/cot_coaseguros.php',$data);
 	}

  	public function reg_cotcoaseguro(){
 		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['idoperador'] = $_POST['operador'];
		$data['valor'] = $_POST['valor'];
		$id = $_POST['id'];
		$data['id'] = $id;

		$this->cotizador_mdl->inCoaseguro($data);
		echo "<script>
				alert('Se registró el coaseguro con éxito.');
				location.href='".base_url()."index.php/cot_coaseguro/".$id."';
				</script>";
 	}
 	
}