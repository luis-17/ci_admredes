<?php
ini_set('max_execution_time', 6000); 
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
date_default_timezone_set('America/Lima');
defined('BASEPATH') OR exit('No direct script access allowed');
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Greenter\XMLSecLibs\Sunat\SignedXml;

class verificar_cnt extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->model('menu_mdl');
        $this->load->model('comprobante_pago_mdl');
        $this->load->library('My_PHPMailer');

    }

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
			$canales = $this->comprobante_pago_mdl->getCanales();
			$data['canales'] = $canales;

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

			$data['idclienteempresa'] = 0;

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$this->load->view('dsb/html/comprobante/verificar_comp.php',$data);

		} else {
			redirect('/');
		}
	}


	public function generarLista(){

		//se declara la variable para generar el html dinámico
		$html = null;

		$canales=$_POST['canales'];
		$data['canales'] = $canales;

		$planes = $this->comprobante_pago_mdl->getPlanes($canales);

		//print_r($planes);
		//exit();

		//se generan los checkbox dinámicos a través de ajax
		$html .= "<div class='col-sm-12'>";
			foreach ($planes as $p):
				$html .= "<div class='form-check form-check-inline'>";
				$html .= "<input class='form-check-input' type='checkbox' name='nameCheck[]' id='". $p->idplan ."' value='". $p->idplan ."'>";
				$html .= "<label class='form-check-label right' for='". $p->idplan ."'>". $p->nombre_comercial_cli ." - ". $p->nombre_plan."</label>";
				$html .="<input type='text' class='hidden' id='numeroSerie' name='numeroSerie' value='".$p->numero_serie."'>";
				$html .= "</div>";
			endforeach;
		$html .= "</div>";
		$html .= "<hr>";

		echo json_encode($html);
	}

	public function mostrarDatosComprobantesEmitidos(){

		$this->zip = new ZipArchive();

		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
		//date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$fecemi = $data['fechaEmision'];

		$canales=$_POST['canales'];
		$datos['canales'] = $canales;

		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];

		$serie = $_POST['numeroSerie'];	
		$data['nameCheck'] = $_POST['nameCheck'];
		$idPlan = $data['nameCheck'];


		if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {

			//html de tabla dinámica que se va a generar
			//$html .="<hr>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha de Emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Nombres y Apellidos</th>";
							$html .="<th>DNI</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Mensaje Sunat</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					for ($i=0; $i < count($idPlan); $i++) {

							$boleta = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serie, $idPlan[$i]);

						foreach ((array) $boleta as $b):

							$nameDoc=$b->serie."-".$b->correlativo;
							$filename="20600258894-03-".$b->serie."-".$b->correlativo;
							$filecdr=$b->mesanio.'-cdrboletas';
							$carpetaCdr = 'adjunto/xml/boletas/'.$filecdr;

							//descomprimir zip y leer xml
							if (file_exists($carpetaCdr.'/R-'.$filename.'.zip')) {
								if ($this->zip->open($carpetaCdr.'/R-'.$filename.'.zip') === TRUE) {
								    $this->zip->extractTo($carpetaCdr.'/');
								    $this->zip->close();
								}
								unlink($carpetaCdr.'/R-'.$filename.'.zip');
							}

							$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
							$DOM = new DOMDocument('1.0', 'utf-8');
							$DOM->loadXML($xml);
							$respuesta = $DOM->getElementsByTagName('Description');

							foreach ($respuesta as $r) {
								$descripcion = $r->nodeValue;
							}

							$importe = $b->importe_total;
							$importe2=number_format((float)$importe, 2, '.', ',');

							$html .= "<tr>";
								$html .= "<td align='left'>".$b->fecha_emision."</td>";
								$html .= "<td align='left'>".$b->serie." - ".$b->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->serie."'></td>";
								$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value='".$b->idcomprobante."'></td>";
								$html .= "<td align='left'>".$b->cont_numDoc."</td>";
								$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
								$html .= "<td align='center'>S/. ".$importe2."</td>";
								if ($descripcion == 'La Boleta numero '.$nameDoc.', ha sido aceptada') {
									$html .= "<td align='left' class='success'>".$descripcion."</td>";
								} else {
									$this->comprobante_pago_mdl->updateEstadocobroComprobante();
									$html .= "<td align='left' class='danger'>".$descripcion."</td>";
								}
							$html .= "</tr>";

						endforeach;

					}
				
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		} elseif ($canales == 4) {

			$html .="<hr>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha para emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Razon Social</th>";
							$html .="<th>RUC</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Mensaje Sunat</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					for ($i=0; $i < count($idPlan); $i++) {

							$factura = $this->comprobante_pago_mdl->getDatosFacturaEmitidaFinal($inicio, $fin, $serie, $idPlan[$i]);

						foreach ((array)$factura as $f):

							$nameDoc=$f->serie."-".$f->correlativo;
							$filename="20600258894-01-".$f->serie."-".$f->correlativo;
							$filecdr=$f->mesanio.'-cdrfacturas';
							$carpetaCdr = 'adjunto/xml/facturas/'.$filecdr;

							//descomprimir zip y leer xml
							if (file_exists($carpetaCdr.'/R-'.$filename.'.zip')) {
								if ($this->zip->open($carpetaCdr.'/R-'.$filename.'.zip') === TRUE) {
								    $this->zip->extractTo($carpetaCdr.'/');
								    $this->zip->close();
								}
								unlink($carpetaCdr.'/R-'.$filename.'.zip');
							}

							$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
							$DOM = new DOMDocument('1.0', 'utf-8');
							$DOM->loadXML($xml);
							$respuesta = $DOM->getElementsByTagName('Description');

							foreach ($respuesta as $r) {
								$descripcion = $r->nodeValue;
							}

							$importeDos = $f->importe_total;
							$importe2=number_format((float)$importeDos, 2, '.', ',');
							$estado = $f->idestadocobro;
								$html .= "<tr>";
									$html .= "<td align='left'>".$f->fecha_emision."</td>";
									$html .= "<td align='center'>".$f->serie." - ".$f->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie' value='".$f->serie."'></td>";
									$html .= "<td align='left'>".$f->razon_social_cli."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$f->idcomprobante."'></td>";
									$html .= "<td align='left'>".$f->numero_documento_cli."</td>";
									$html .= "<td align='left'>".$f->nombre_plan."</td>";
									$html .= "<td align='center'>S/. ".$importe2."</td>";
									if ($descripcion == 'La Factura numero '.$nameDoc.', ha sido aceptada') {
										$html .= "<td align='left' class='success'>".$descripcion."</td>";
									} else {
										$this->comprobante_pago_mdl->updateEstadocobroComprobante();
										$html .= "<td align='left' class='danger'>".$descripcion."</td>";
									}
								$html .= "</tr>";

						endforeach;
					
					}

				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";
		}

		echo json_encode($html);
	} 

}