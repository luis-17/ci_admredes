<?php
ini_set('max_execution_time', 6000);
ini_set('memory_limit', -1);
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
date_default_timezone_set('America/Lima');
defined('BASEPATH') OR exit('No direct script access allowed');
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use PhpZip\ZipFileInterface;
use PhpZip\ZipFile;

class ventas_cnt extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->model('menu_mdl');
        $this->load->model('comprobante_pago_mdl');
        $this->load->library('My_PHPMailer');
        $this->load->library('Numero_Letras');
        //$this->load->library('zip/zip');
        //$this->load->library('My_ZipFile');

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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

			$data['idclienteempresa'] = 0;
			$data['idserie'] = 0;
			$data['fechaDoc'] = date('Y-m-d');
			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['fecAct'] = date('Y-m-d');
			$data['correlativoDoc'] = "";
			//$data['fecemisi'] = date('Y-m-d');
			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;

			$canales = $this->comprobante_pago_mdl->getCanales();
			$data['canales'] = $canales;

			$serie = $this->comprobante_pago_mdl->getSerie();
			$data['serie'] = $serie;

			$planes = $this->comprobante_pago_mdl->getPlanesSelect();
			$data['planes'] = $planes;

			$this->load->view('dsb/html/comprobante/generar_comp.php',$data);

		} else {
			redirect('/');
		}
	}

	public function mostrarDocumento(){

		$html = null;
		$iddocumento = null;

		$canales=$_POST['canales'];
		$data['canales'] = $canales;

		$clienteempresa = $this->comprobante_pago_mdl->getTipoDocumento($canales);
		$data['clienteempresa'] = $clienteempresa;

		//$this->load->view('dsb/html/comprobante/generar_comp.php',$data);

		foreach ($clienteempresa as $c):

			if ($iddocumento==$c->idtipodocumentomov) {
				$estp= 'selected';
			} else {
				$estp='';
			}

			if ($canales == 0) {
				$html .= "<option id='numeroSerie' name='numeroSerie' value='0' ".$estp." >Seleccione</option>";
			} else {
				$html .= "<option id='numeroSerie' name='numeroSerie' value='0' ".$estp." >Seleccione</option>";
				$html .= "<option id='numeroSerie' name='numeroSerie' value='".$c->numero_serie."' ".$estp." >".$c->descripcion_tdm."</option>";
			}
				//$html .="<input type='text' class='hidden' id='numeroSerieCor' name='numeroSerieCor' value='".$c->numero_serie."'>";
		endforeach;

		echo json_encode($html);
	}

	public function mostrarCorrelativo(){

		$html=null;
		$serie=$_POST['documento'];

		/*if (substr($serie, 0, 1) == 'B') {
			$correlativoRes=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUnoRes($serie);

			foreach ($correlativoRes as $cr):

				$html.="<input class='form-control' name='correlativoActual' type='hidden' value='".$cr->nume_corre_res."' id='correlativoActual'>";

			endforeach;	
		} elseif (substr($serie, 0, 1) == 'F') {*/
			$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($serie);
		
			foreach ($correlativo as $c):

				$html.="<input class='form-control' name='correlativoActual' type='text' value='".$c->correlativo."' id='correlativoActual'>";
				//print_r($c->correlativo);

			endforeach;	
		//}

		echo json_encode($html);
	}

	public function mostrarDatos(){
		
		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');

		$fecemi = $data['fechaEmision'];

		$canales=$_POST['canales'];
		$datos['canales'] = $canales;

		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];

		$data['documento'] = $_POST['documento'];
		$serie = $data['documento'];

		$idserie=$_POST['documento'];

		if (substr($serie, 0, 1) == 'B') {

			//html de tabla dinámica que se va a generar
			$html .= "<hr>";
			$boletaSuma = $this->comprobante_pago_mdl->getDatosSumaBoleta($inicio, $fin, $canales, $serie);
			foreach ($boletaSuma as $bs):
				$suma=$bs->suma;
				$sumaDos=number_format((float)$suma, 2, '.', ',');
				$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Boletas: S/. ".$sumaDos."</b></span></H1>";
			endforeach;
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover' style='width:100%'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha de Cobro</th>";
							$html .="<th>N° de certificado</th>";
							$html .="<th>Nombres y Apellidos</th>";
							$html .="<th>DNI</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Estado</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

						$boleta = $this->comprobante_pago_mdl->getDatosBoleta($inicio, $fin, $canales, $serie);
						foreach ((array) $boleta as $b):

							$importe = $b->cob_importe;
							$importe = $importe/100;
							$importe2=number_format((float)$importe, 2, '.', ',');
							$newDate = date("d/m/Y", strtotime($b->cob_fechCob));

							$html .= "<tr>";
								$html .= "<td align='left'>".$newDate."<input type='text' class='hidden' id='fechaEmi' name='fechaEmi[]' value='".$b->cob_fechCob."'></td>";

								$html .= "<td align='left'>".$b->cert_num."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->numero_serie."'></td>";

								$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='contratante' name='contratante[]' value='".$b->cont_id."'></td>";

								$html .= "<td align='left'>".$b->cont_numDoc."<input type='text' class='hidden' id='cobro' name='cobro[]' value='".$b->cob_id."'></td>";

								$html .= "<td align='left'>".$b->nombre_plan."<input type='text' class='hidden' id='idplan' name='idplan[]' value='".$b->idplan."'></td>";

								$html .= "<td align='center'>S/. ".$importe2."<input type='text' class='hidden' id='importeTotal' name='importeTotal[]' value='".$importe2."'></td>";

								$html .= "<td align='left'>".$b->descripcion."</td>";
							$html .= "</tr>";

						endforeach;
					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		} elseif (substr($serie, 0, 1) == 'F') {

			$html .= "<hr>";

			$facturaSuma = $this->comprobante_pago_mdl->getDatosSumaFacturas($inicio, $fin, $canales, $serie);
			foreach ($facturaSuma as $fs):
				$suma=$fs->suma;
				$sumaDos=number_format((float)$suma, 2, '.', ',');
				$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Facturas: S/. ".$sumaDos."</b></span></H1>";
			endforeach;

			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha de emisión</th>";
							$html .="<th>Razon Social</th>";
							$html .="<th>Nombre Comercial</th>";
							$html .="<th>RUC</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Cantidad</th>";
							$html .="<th>Estado</th>";
							$html .="<th>Seleccione</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

						$factura = $this->comprobante_pago_mdl->getDatosFacturas($inicio, $fin, $canales, $serie);

						$tot=0; 
						$totcant=0;

						foreach ((array)$factura as $f):

							$importeDos = $f->cob_importe;
											
							$cant=$f->cant;
							$totcant=$totcant+$cant;
							$sub=$cant*$importeDos;
							$tot=$tot+$sub;
							$cant2=number_format((float)$cant, 0, '', ',');
							$importe2=number_format((float)$importeDos, 2, '.', '');
							$sub2=number_format((float)$sub, 2, '.', ''); 

							if ($cant2 > 0) {
								$html .= "<tr>";
									$html .= "<td align='left'><input class='form-control input-mask-date' type='date' id='fechaEmi' name='fechaEmi[]' required='Seleccione una fecha de inicio' value=".$fecemi."></td>";
									
									$html .= "<td align='left'>".$f->razon_social_cli."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$f->numero_serie."'></td>";

									$html .= "<td align='left'>".$f->nombre_comercial_cli."<input type='text' class='hidden' id='empresa' name='empresa[]' value='".$f->idclienteempresa."'></td>";

									$html .= "<td align='left'>".$f->numero_documento_cli."</td>";

									$html .= "<td align='left'>".$f->nombre_plan."<input type='text' class='hidden' id='idplan' name='idplan[]' value='".$f->plan_id."'></td>";

									$html .= "<td align='center'>S/. ".$importe2."<input type='text' class='hidden' id='importeTotal' name='importeTotal[]' value='".$importe2."'></td>";

									$html .= "<td align='center'>".$cant2."</td>";

									$html .= "<td align='left'>Pendiente</td>";

									$html .= "<td align='center'>";
										//$html .= "<div class='form-check'>";
										  $html .= "<input class='form-check-input' type='checkbox' name='checkPlan[]' value='".$f->plan_id."' id='".$f->plan_id."'>";
										//$html .= "</div>";
									$html .= "</td>";

								$html .= "</tr>";

							}
						endforeach;
					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";
		}
		echo json_encode($html);
	}

	public function generarComprobante(){

		//datos obtenidos mediante ajax, de la vista
		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];
		//$estadoCobro = $_POST['nameCheck'];
		$canales=$_POST['canales'];

		$serie = $_POST['numSerie'];
		$idPlan = $_POST['idplan'];
		$idPlanUnique = array_unique($idPlan);
		//$correlativo = $_POST['correlativo'];
		$importeTotal = $_POST['importeTotal'];
		$correlativo = $_POST['correlativoActual'];
		$limit = 1;

    	$numSerieUno = reset($serie);

		if (substr($numSerieUno, 0, 1) == 'B') {

			$idTipoDoc = 3;

			$boleta = $this->comprobante_pago_mdl->getDatosBoleta($inicio, $fin, $canales, $numSerieUno);
			$boletaResArray = json_decode(json_encode($boleta), true);
			$boletaResstring = array_values($boletaResArray)[0];
			$fechaRes = $boletaResstring['cob_fechCob'];
			$serieRes = $boletaResstring['numero_serie'];

			$correlativoRC = $this->comprobante_pago_mdl->getUltimoCorrelativoMasUnoRes($fechaRes);
			$correlativoRCArray = json_decode(json_encode($correlativoRC), true);
			$correlativoRCstring = array_values($correlativoRCArray)[0];
			$correRC = $correlativoRCstring['nume_corre_res'];

			$contadorRC = $this->comprobante_pago_mdl->getUltimoContador($fechaRes, $correRC);
			$contadorRCArray = json_decode(json_encode($contadorRC), true);
			$contadorRCstring = array_values($contadorRCArray)[0];
			$contaRc = $contadorRCstring['contadorCorre'];

			$contador=1;

			if ($contaRc>=$contador) {
				$contador=401;
			} else {
				$contador=1;
			}

			foreach ((array) $boleta as $b){

				$importe = $b->cob_importe;
				$importe = $importe/100;
				$importe2=number_format((float)$importe, 2, '.', ',');
				$newDate = date("d/m/Y", strtotime($b->cob_fechCob));

				if ($fechaRes==$b->cob_fechCob) {
					if ($contador<=400) {
						$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $b->cob_fechCob, $b->numero_serie, $correlativo, $b->cont_id, $importe2, $b->cob_id, $b->idplan, 'RC0'.$canales, $correRC);
						$contador=$contador+1;
					} else {
						$correRC = $correRC+1;
						$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $b->cob_fechCob, $b->numero_serie, $correlativo, $b->cont_id, $importe2, $b->cob_id, $b->idplan, 'RC0'.$canales, $correRC);
						$contador=2;
					}
				} else {
					$fechaRes = $b->cob_fechCob;
					$correlativoRC = $this->comprobante_pago_mdl->getUltimoCorrelativoMasUnoRes($fechaRes);
					$correlativoRCArray = json_decode(json_encode($correlativoRC), true);
					$correlativoRCstring = array_values($correlativoRCArray)[0];
					$correRC = $correlativoRCstring['nume_corre_res'];

					$contadorRC = $this->comprobante_pago_mdl->getUltimoContador($fechaRes, $correRC);
					$contadorRCArray = json_decode(json_encode($contadorRC), true);
					$contadorRCstring = array_values($contadorRCArray)[0];
					$contaRc = $contadorRCstring['contadorCorre'];
					$contador=1;

					if ($contaRc>=$contador) {
						$contador=401;
					} else {
						$contador=1;
					}

					if ($contador<=400) {
						$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $b->cob_fechCob, $b->numero_serie, $correlativo, $b->cont_id, $importe2, $b->cob_id, $b->idplan, 'RC0'.$canales, $correRC);
						$contador=$contador+1;
					} else {
						$correRC = $correRC+1;
						$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $b->cob_fechCob, $b->numero_serie, $correlativo, $b->cont_id, $importe2, $b->cob_id, $b->idplan, 'RC0'.$canales, $correRC);
						$contador=2;
					}
				}

				$correlativo = $correlativo+1;

				$this->comprobante_pago_mdl->updateEstadoCobro($b->cob_fechCob, $b->cob_id);
				
			}

			//for para recorrer los planes obtenidos de la vista y hacer update del idestadocobro
			/*for ($i=0; $i < count(array_unique($idPlan)); $i++) { 

				$this->comprobante_pago_mdl->updateEstadoCobro($inicio, $fin, $idPlan[$i]);

			}*/
		} elseif (substr($numSerieUno, 0, 1) == 'F') {

			$idPlanCheck = $_POST['checkPlan'];
			$fechaEmi = $_POST['fechaEmi'];
			$idEmpresa = $_POST['empresa'];

			$idTipoDoc = 3;

			//for para recorrer los datos de la tabla y hacer el insert en la bd
			for ($i=0; $i < count($idPlanCheck); $i++) {
				
				$this->comprobante_pago_mdl->insertDatosFacturas($inicio, $fin, $fechaEmi[$i], $serie[$i], $correlativo, $idEmpresa[$i], $importeTotal[$i], $idPlanCheck[$i]);

				$correlativo = $correlativo+1;
			}
			//for para recorrer los planes obtenidos de la vista y hacer update del idestadocobro
			for ($i=0; $i < count(array_unique($idPlanCheck)); $i++) { 
				
				$this->comprobante_pago_mdl->updateEstadoCobroFact($inicio, $fin, $idPlanCheck[$i]);

			}
		}
	}

//----------------------------------------------------------------------------------------------------------------------

	public function mostrarCorrelativoManual(){

		$serie=$_POST['serie'];

		$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($serie);

		foreach ($correlativo as $c) {
			$data['correlativoDoc'] = $c->correlativo;
		}

		echo json_encode($data);

	}

	public function mostrarplanes(){

		$idplan=$_POST['planes'];
		//print_r($serie);
		//exit();
		$planes = $this->comprobante_pago_mdl->getPlanesSelectId($idplan);

		foreach ($planes as $p):

			$data['idplan'] = $p->idplan;
			
		endforeach;		

		echo json_encode($data);		
	}

	public function guardarComprobanteManual(){

		$fechaEmi=$_POST['fechaDoc'];
		$serie=$_POST['serie'];
		$correlativo=$_POST['correlativoDoc'];
		$importeTotal=$_POST['impTotalD'];
		$sustento=$_POST['descripcionManual'];
		$idplan = $_POST['idplanManual'];
		$moneda = $_POST['monedaManual'];

		if (substr($serie, 0, 1) == 'B') {
		//if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {

			$correlativoRC = $this->comprobante_pago_mdl->getUltimoCorrelativoMasUnoRes($fechaEmi);
			$correlativoRCArray = json_decode(json_encode($correlativoRC), true);
			$correlativoRCstring = array_values($correlativoRCArray)[0];
			$correRC = $correlativoRCstring['nume_corre_res']+1;
			
			$tipoDoc=3;

			$this->comprobante_pago_mdl->insertDatosComprobanteManualBoleta($fechaEmi, $serie, $correlativo, $tipoDoc, $importeTotal, $idplan, $moneda, $sustento, $correRC);

		} elseif (substr($serie, 0, 1) == 'F') {
		//} elseif ($serie == 'F001') {
			
			$tipoDoc=2;
			if ($serie == 'F001') {
				$clienteempresa=4;
			} elseif ($serie == 'F002') {
				$clienteempresa=12;
			}
			
			$this->comprobante_pago_mdl->insertDatosComprobanteManualFactura($fechaEmi, $serie, $correlativo, $tipoDoc, $clienteempresa, $importeTotal, $idplan, $moneda, $sustento);
		}
	}

//----------------------------------------------------------------------------------------------------------------------

	public function generarLista(){

		$canales=$_POST['canales'];

		$planes = $this->comprobante_pago_mdl->getPlanes($canales);

		foreach ($planes as $p) {
			$data['numeroSerie'] = $p->numero_serie;
		}

		echo json_encode($data);
	}

	public function generarListaDeclarada(){

		$canales=$_POST['canales'];

		$planes = $this->comprobante_pago_mdl->getPlanes($canales);

		foreach ($planes as $p) {
			$data['numeroSerieDeclarado'] = $p->numero_serie;
		}

		echo json_encode($data);
	}

	public function mostrarDatosComprobantes(){

		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');

		$fecemi = $data['fechaEmision'];

		$canales=$_POST['canalesDos'];
		$datos['canalesDos'] = $canales;

		$inicio = $_POST['fechainicioDos'];
		//$fin = $_POST['fechafinDos'];

		$serie = $_POST['numeroSerie'];	
		//$data['nameCheck'] = $_POST['nameCheck'];
		//$idPlan = $data['nameCheck'];


		if (substr($serie, 0, 1) == 'B') {

			$boletaSumaSoles = $this->comprobante_pago_mdl->getDatosSumaEmiSoles($inicio, $canales, $serie);
			$boletaSumaSolesArray = json_decode(json_encode($boletaSumaSoles), true);
			$boletaSumaSolesstring = array_values($boletaSumaSolesArray)[0];
			$sumaS = $boletaSumaSolesstring['sumas'];
			$sumaDosS=number_format((float)$sumaS, 2, '.', ',');

			$boletaSumaDolares = $this->comprobante_pago_mdl->getDatosSumaEmiDolares($inicio, $canales, $serie);
			$boletaSumaDolaresArray = json_decode(json_encode($boletaSumaDolares), true);
			$boletaSumaDolaresstring = array_values($boletaSumaDolaresArray)[0];
			$sumaD = $boletaSumaDolaresstring['sumad'];

			$sumaDosd=number_format((float)$sumaD, 2, '.', ',');

			$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Soles: S/. ".$sumaDosS."</b></span>&nbsp;<span class='label label-succes label-white middle'><b>Total de cobros Dólares: $ ".$sumaDosd."</b></span></H1>";

			//html de tabla dinámica que se va a generar
			$html .= "<br>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							//$html .="<th>Id Comprobante</th>";
							$html .="<th>Fecha de Emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Nombres y Apellidos</th>";
							$html .="<th>DNI</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

				//	for ($i=0; $i < count($idPlan); $i++) {

					$boleta = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $serie);

					foreach ((array) $boleta as $b):

						$importe = $b->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $b->idestadocobro;
						$newDate = date("d/m/Y", strtotime($b->fecha_emision));

						$html .= "<tr>";
							//$html .= "<td align='left'>".$$b->idcomprobante."</td>";
							$html .= "<td align='left'>".$newDate."</td>";
							$html .= "<td align='left'>".$b->serie." - ".$b->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->serie."'></td>";
							$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value='".$b->idcomprobante."'></td>";
							$html .= "<td align='left'>".$b->cont_numDoc."</td>";
							$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
							if ($b->tipo_moneda == 'PEN') {
								$html .= "<td align='center'>S/. ".$importe2."</td>";
							} elseif ($b->tipo_moneda == 'USD') {
								$html .= "<td align='center'>$ ".$importe2."</td>";
							}
							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>".$b->descripcion."</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							} elseif ($estado == 3) {
								$html .= "<td align='center' class='success'>".$b->descripcion."</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							}
							
						$html .= "</tr>";

					endforeach;

					//}
				
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		} elseif (substr($serie, 0, 1) == 'F') {


			$boletaSumaSoles = $this->comprobante_pago_mdl->getDatosSumaEmiSoles($inicio, $canales, $serie);
			$boletaSumaSolesArray = json_decode(json_encode($boletaSumaSoles), true);
			$boletaSumaSolesstring = array_values($boletaSumaSolesArray)[0];
			$sumaS = $boletaSumaSolesstring['sumas'];
			$sumaDosS=number_format((float)$sumaS, 2, '.', ',');

			$boletaSumaDolares = $this->comprobante_pago_mdl->getDatosSumaEmiDolares($inicio, $canales, $serie);
			$boletaSumaDolaresArray = json_decode(json_encode($boletaSumaDolares), true);
			$boletaSumaDolaresstring = array_values($boletaSumaDolaresArray)[0];
			$sumaD = $boletaSumaDolaresstring['sumad'];
			$sumaDosd=number_format((float)$sumaD, 2, '.', ',');

			$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Soles: S/. ".$sumaDosS."</b></span>&nbsp;<span class='label label-succes label-white middle'><b>Total de cobros Dólares: $ ".$sumaDosd."</b></span></H1>";


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
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

					$factura = $this->comprobante_pago_mdl->getDatosFacturaEmitida($inicio, $serie);

					foreach ((array)$factura as $f):

						$importeDos = $f->importe_total;
						$importe2=number_format((float)$importeDos, 2, '.', ',');
						$estado = $f->idestadocobro;
						$newDate = date("d/m/Y", strtotime($f->fecha_emision));

							$html .= "<tr>";
								$html .= "<td align='left'>".$newDate."</td>";
								$html .= "<td align='center'>".$f->serie." - ".$f->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie' value='".$f->serie."'></td>";
								$html .= "<td align='left'>".$f->razon_social_cli."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$f->idcomprobante."'></td>";
								$html .= "<td align='left'>".$f->numero_documento_cli."</td>";
								$html .= "<td align='left'>".$f->nombre_plan."</td>";
								if ($f->tipo_moneda == 'PEN') {
									$html .= "<td align='center'>S/. ".$importe2."</td>";
								} elseif ($f->tipo_moneda == 'USD') {
									$html .= "<td align='center'>$ ".$importe2."</td>";
								}
								if ($estado == 2) {
									$html .= "<td align='center' class='danger'>".$f->descripcion."</td>";
									$html .= "<td align='left'>";
										$html .= "<ul class='ico-stack'>";
											$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$f->idcomprobante."/".$canales."/F001' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
										$html .= "</ul>";
									$html .="</td>";
								} elseif ($estado == 3) {
									$html .= "<td align='center' class='success'>".$f->descripcion."</td>";
									$html .= "<td align='left'>";
										$html .= "<ul class='ico-stack'>";
											$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$f->idcomprobante."/".$canales."/F001' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
										$html .= "</ul>";
									$html .="</td>";
								}
							$html .= "</tr>";

					endforeach;
					
					//}

				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";
		}

		echo json_encode($html);
	}

	public function mostrarDatosComprobantesDeclarados(){

		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$fecemi = date('Y-m-d');

		$canales=$_POST['canalesDeclarado'];

		$inicio = $_POST['fechainicioDeclarado'];
		$fin = $_POST['fechafinDeclarado'];

		$serie = $_POST['numeroSerieDeclarado'];	

		if (substr($serie, 0, 1) == 'B') {
			//html de tabla dinámica que se va a generar

			$boletaSumaSoles = $this->comprobante_pago_mdl->getDatosSumaDecSoles($inicio, $fin, $canales, $serie);
			$boletaSumaSolesArray = json_decode(json_encode($boletaSumaSoles), true);
			$boletaSumaSolesstring = array_values($boletaSumaSolesArray)[0];
			$sumaS = $boletaSumaSolesstring['sumas'];
			$sumaDosS=number_format((float)$sumaS, 2, '.', ',');

			$boletaSumaDolares = $this->comprobante_pago_mdl->getDatosSumaDecDolares($inicio, $fin, $canales, $serie);
			$boletaSumaDolaresArray = json_decode(json_encode($boletaSumaDolares), true);
			$boletaSumaDolaresstring = array_values($boletaSumaDolaresArray)[0];
			$sumaD = $boletaSumaDolaresstring['sumad'];

			$sumaDosd=number_format((float)$sumaD, 2, '.', ',');

			$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Soles: S/. ".$sumaDosS."</b></span>&nbsp;<span class='label label-succes label-white middle'><b>Total de cobros Dólares: $ ".$sumaDosd."</b></span></H1>";

			//html de tabla dinámica que se va a generar
			$html .= "<br>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							//$html .="<th>Id Comprobante</th>";
							$html .="<th>Fecha de Emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Nombres y Apellidos</th>";
							$html .="<th>DNI</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

				//	for ($i=0; $i < count($idPlan); $i++) {

					$boleta = $this->comprobante_pago_mdl->getDatosBoletaDeclarada($inicio, $fin, $serie);

					foreach ((array) $boleta as $b):

						if ($b->tipo_moneda=='PEN') {
							$moneda = 'S/. ';
						} elseif ($b->tipo_moneda=='USD') {
							$moneda = '$ ';
						}

						$importe = $b->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $b->idestadocobro;
						$newDate = date("d/m/Y", strtotime($b->fecha_emision));

						$html .= "<tr>";
							//$html .= "<td align='left'>".$$b->idcomprobante."</td>";
							$html .= "<td align='left'>".$newDate."</td>";
							$html .= "<td align='left'>".$b->serie." - ".$b->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->serie."'></td>";
							$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value='".$b->idcomprobante."'></td>";
							$html .= "<td align='left'>".$b->cont_numDoc."</td>";
							$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
							$html .= "<td align='center'>".$moneda.$importe2."</td>";
							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>".$b->descripcion."</td>";
								$html .= "<td align='left'></td>";
							} elseif ($estado == 3) {
								$html .= "<td align='center' class='success'>".$b->descripcion."</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							}
							
						$html .= "</tr>";

					endforeach;

					//}
				
					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";
		} elseif (substr($serie, 0, 1) == 'F') {

			$boletaSumaSoles = $this->comprobante_pago_mdl->getDatosSumaDecSoles($inicio, $fin, $canales, $serie);
			$boletaSumaSolesArray = json_decode(json_encode($boletaSumaSoles), true);
			$boletaSumaSolesstring = array_values($boletaSumaSolesArray)[0];
			$sumaS = $boletaSumaSolesstring['sumas'];
			$sumaDosS=number_format((float)$sumaS, 2, '.', ',');

			$boletaSumaDolares = $this->comprobante_pago_mdl->getDatosSumaDecDolares($inicio, $fin, $canales, $serie);
			$boletaSumaDolaresArray = json_decode(json_encode($boletaSumaDolares), true);
			$boletaSumaDolaresstring = array_values($boletaSumaDolaresArray)[0];
			$sumaD = $boletaSumaDolaresstring['sumad'];

			$sumaDosd=number_format((float)$sumaD, 2, '.', ',');

			$html .="<H1><span class='label label-succes label-white middle'><b>Total de cobros Soles: S/. ".$sumaDosS."</b></span>&nbsp;<span class='label label-succes label-white middle'><b>Total de cobros Dólares: $ ".$sumaDosd."</b></span></H1>";

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
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

					$factura = $this->comprobante_pago_mdl->getDatosFacturaDeclarada($inicio, $fin, $serie);

					foreach ((array)$factura as $f):

						if ($f->tipo_moneda=='PEN') {
							$moneda = 'S/. ';
						} elseif ($f->tipo_moneda=='USD') {
							$moneda = '$ ';
						}

						$importeDos = $f->importe_total;
						$importe2=number_format((float)$importeDos, 2, '.', ',');
						$estado = $f->idestadocobro;
						$newDate = date("d/m/Y", strtotime($f->fecha_emision));

							$html .= "<tr>";
								$html .= "<td align='left'>".$newDate."</td>";
								$html .= "<td align='center'>".$f->serie." - ".$f->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie' value='".$f->serie."'></td>";
								$html .= "<td align='left'>".$f->razon_social_cli."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$f->idcomprobante."'></td>";
								$html .= "<td align='left'>".$f->numero_documento_cli."</td>";
								$html .= "<td align='left'>".$f->nombre_plan."</td>";
								$html .= "<td align='center'>".$moneda.$importe2."</td>";
								if ($estado == 2) {
									$html .= "<td align='center' class='danger'>".$f->descripcion."</td>";
									$html .= "<td align='left'></td>";
								} elseif ($estado == 3) {
									$html .= "<td align='center' class='success'>".$f->descripcion."</td>";
									$html .= "<td align='left'>";
										$html .= "<ul class='ico-stack'>";
											$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/generarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."index.php/ventas_cnt/enviarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
										$html .= "</ul>";
									$html .="</td>";
								}
							$html .= "</tr>";

					endforeach;
					
					//}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";
		}

		echo json_encode($html);
	}


	public function generarExcelReporte(){

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$fecemi = date('Y-m-d');

		$canales=$_POST['canalesDeclarado'];

		$inicio = $_POST['fechainicioDeclarado'];
		$fin = $_POST['fechafinDeclarado'];

		$serie = $_POST['numeroSerieDeclarado'];	

		//Se carga la librería de excel
		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Hoja 1');

		if (substr($serie, 0, 1) == 'B') {

			$estiloBorde = array(
			   'borders' => array(
			     'allborders' => array(
			       'style' => PHPExcel_Style_Border::BORDER_THIN
			     )
			   )
			);

			$this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloBorde);
			unset($styleArray); 

			$estiloCentrar = array( 
			 	'alignment' => array( 
			  		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
			  		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
			  	) 
			);

			$this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloCentrar);
			$this->excel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(true);

			$estiloColor = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'F4A900')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloColor);

			$this->excel->getActiveSheet()->getRowDimension('A1:H1')->setRowHeight(20);

			//Definimos cabecera de la tabla excel
			$this->excel->getActiveSheet()->setCellValue("A1", "FECHA DE EMISION")->getColumnDimension('A')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("B1", "CERTIFICADO")->getColumnDimension('B')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("C1", "CONTRATANTE")->getColumnDimension('C')->setWidth(50);
			$this->excel->getActiveSheet()->setCellValue("D1", "DOCUMENTO DE IDENTIDAD")->getColumnDimension('D')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("E1", "NOMBRE DE PLAN")->getColumnDimension('E')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("F1", "SERIE")->getColumnDimension('F')->setWidth(10);
			$this->excel->getActiveSheet()->setCellValue("G1", "CORRELATIVO")->getColumnDimension('G')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("H1", "IMPORTE")->getColumnDimension('H')->setWidth(15);

			$contador = 2;

			$dev = $this->comprobante_pago_mdl->getDatosBoletaReporte($inicio, $fin, $serie);

			foreach ($dev as $d) {

				$importe = $d->importe_total;
				$importe2=number_format((float)$importe, 2, '.', ',');

				//Informacion de las filas de la consulta.
				$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estiloBorde);
				unset($styleArray); 
				$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estiloCentrar);
				$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->getAlignment()->setWrapText(true);

				$this->excel->getActiveSheet()->setCellValue("A{$contador}",$d->fecha_emision);
				$this->excel->getActiveSheet()->setCellValue("B{$contador}",$d->cert_num);
				$this->excel->getActiveSheet()->setCellValue("C{$contador}",$d->contratante);
				$this->excel->getActiveSheet()->setCellValue("D{$contador}",$d->cont_numDoc);
				$this->excel->getActiveSheet()->setCellValue("E{$contador}",$d->nombre_plan);
				$this->excel->getActiveSheet()->setCellValue("F{$contador}",$d->serie);
				$this->excel->getActiveSheet()->setCellValue("G{$contador}",$d->correlativo);
				$this->excel->getActiveSheet()->setCellValue("H{$contador}",$importe2);

				$contador = $contador + 1;

			}	

			$contador = $contador + 2;

			$boletaSuma = $this->comprobante_pago_mdl->getDatosSumaDec($inicio, $fin, $canales, $serie);

			foreach ($boletaSuma as $bs) {
				$suma = $bs->suma;
				
				$this->excel->getActiveSheet()->getStyle("G{$contador}:H{$contador}")->applyFromArray($estiloBorde);
				unset($styleArray); 
				$this->excel->getActiveSheet()->getStyle("G{$contador}:H{$contador}")->applyFromArray($estiloCentrar);
				$this->excel->getActiveSheet()->getStyle("G{$contador}:H{$contador}")->getAlignment()->setWrapText(true);
				$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($estiloColor);
				$this->excel->getActiveSheet()->setCellValue("G{$contador}","Total");
				$this->excel->getActiveSheet()->setCellValue("H{$contador}","S/. ".$suma);
			}

		} elseif (substr($serie, 0, 1) == 'F') {

			$estiloBorde = array(
			   'borders' => array(
			     'allborders' => array(
			       'style' => PHPExcel_Style_Border::BORDER_THIN
			     )
			   )
			);

			$this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloBorde);
			unset($styleArray); 

			$estiloCentrar = array( 
			 	'alignment' => array( 
			  		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
			  		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
			  	) 
			);

			$this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloCentrar);
			$this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setWrapText(true);

			$estiloColor = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'F4A900')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloColor);

			$this->excel->getActiveSheet()->getRowDimension('A1:G1')->setRowHeight(20);

			//Definimos cabecera de la tabla excel
			$this->excel->getActiveSheet()->setCellValue("A1", "FECHA DE EMISION")->getColumnDimension('A')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("B1", "EMPRESA")->getColumnDimension('B')->setWidth(50);
			$this->excel->getActiveSheet()->setCellValue("C1", "RUC")->getColumnDimension('C')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("D1", "NOMBRE DE PLAN")->getColumnDimension('D')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("E1", "SERIE")->getColumnDimension('E')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("F1", "CORRELATIVO")->getColumnDimension('F')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("G1", "IMPORTE")->getColumnDimension('G')->setWidth(15);

			$contador = 2;

			$dev = $this->comprobante_pago_mdl->getDatosFacturaDeclarada($inicio, $fin, $serie);

			foreach ($dev as $d) {

				$importe = $d->importe_total;
				$importe2=number_format((float)$importe, 2, '.', ',');

				//Informacion de las filas de la consulta.
				$this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estiloBorde);
				unset($styleArray); 
				$this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estiloCentrar);
				$this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->getAlignment()->setWrapText(true);

				$this->excel->getActiveSheet()->setCellValue("A{$contador}",$d->fecha_emision);
				$this->excel->getActiveSheet()->setCellValue("B{$contador}",utf8_decode($d->razon_social_cli));
				$this->excel->getActiveSheet()->setCellValue("C{$contador}",$d->numero_documento_cli);
				$this->excel->getActiveSheet()->setCellValue("D{$contador}",utf8_decode($d->nombre_plan));
				$this->excel->getActiveSheet()->setCellValue("E{$contador}",$d->serie);
				$this->excel->getActiveSheet()->setCellValue("F{$contador}",$d->correlativo);
				$this->excel->getActiveSheet()->setCellValue("G{$contador}",$importe2);

				$contador = $contador + 1;
			}

			$contador = $contador + 2;

			$boletaSuma = $this->comprobante_pago_mdl->getDatosSumaDec($inicio, $fin, $canales, $serie);

			foreach ($boletaSuma as $bs) {
				$suma = $bs->suma;
				
				$this->excel->getActiveSheet()->getStyle("F{$contador}:G{$contador}")->applyFromArray($estiloBorde);
				unset($styleArray); 
				$this->excel->getActiveSheet()->getStyle("F{$contador}:G{$contador}")->applyFromArray($estiloCentrar);
				$this->excel->getActiveSheet()->getStyle("F{$contador}:G{$contador}")->getAlignment()->setWrapText(true);
				$this->excel->getActiveSheet()->getStyle("F{$contador}")->applyFromArray($estiloColor);
				$this->excel->getActiveSheet()->setCellValue("F{$contador}","Total");
				$this->excel->getActiveSheet()->setCellValue("G{$contador}","S/. ".$suma);
			}			
		}

	  	//Le ponemos un nombre al archivo que se va a generar.
	    $objWriter = new PHPExcel_Writer_Excel5($this->excel);
		ob_start();
		$objWriter->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();
		
		$response =  array(
	        'op' => 'ok',
	        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
	    );

		die(json_encode($response));
	}

	public function generarExcel(){

		$mail = new PHPMailer();

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$fecemi = date('Y-m-d');
		$fecemiNombre = str_replace("-" , "" ,  $fecemi);

		$canales = $_POST['canales'];

		$fechainicio = $_POST['fechainicio'];
		$fechafin = $_POST['fechafin'];

		$numeroSerie = $_POST['numeroSerie'];	
		$correlativoConcar = $_POST['concar'];
			
		$boletas = $this->comprobante_pago_mdl->getDatosExcelBoletas($fechainicio, $fechafin, $numeroSerie);
		$facturas = $this->comprobante_pago_mdl->getDatosExcelFacturas($fechainicio, $fechafin, $numeroSerie);

			//Se carga la librería de excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle('Hoja 1');

			//contador de filas
			$estiloBorde = array(
			   'borders' => array(
			     'allborders' => array(
			       'style' => PHPExcel_Style_Border::BORDER_THIN
			     )
			   )
			 );

			$this->excel->getActiveSheet()->getStyle('A1:AN3')->applyFromArray($estiloBorde);
			unset($styleArray); 

			$estiloCentrar = array( 
			 	'alignment' => array( 
			  		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
			  		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
			  	) 
			); 

			$this->excel->getActiveSheet()->getStyle('A1:AN3')->applyFromArray($estiloCentrar);
			$this->excel->getActiveSheet()->getStyle('A1:AN3')->getAlignment()->setWrapText(true);

			$estiloColor = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'FFFF00')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('A1:A3')->applyFromArray($estiloColor);

			$estiloColorDos = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'F4A900')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('B1:AN1')->applyFromArray($estiloColorDos);

			$this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
			$this->excel->getActiveSheet()->getRowDimension(2)->setRowHeight(120);
			
			//Definimos los títulos de la cabecera Y ancho de columnas.
			$this->excel->getActiveSheet()->setCellValue("A1", "Campo")->getColumnDimension('A')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("B1", "Sub Diario")->getColumnDimension('B')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("C1", "Número de Comprobante")->getColumnDimension('C')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("D1", "Fecha de Comprobante")->getColumnDimension('D')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("E1", "Código de Moneda")->getColumnDimension('E')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("F1", "Glosa Principal")->getColumnDimension('F')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("G1", "Tipo de Cambio")->getColumnDimension('G')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("H1", "Tipo de Conversión")->getColumnDimension('H')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("I1", "Flag de Conversión de Moneda")->getColumnDimension('I')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("J1", "Fecha Tipo de Cambio")->getColumnDimension('J')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("K1", "Cuenta Contable")->getColumnDimension('K')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("L1", "Código de Anexo")->getColumnDimension('L')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("M1", "Código de Centro de Costo")->getColumnDimension('M')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("N1", "Debe / Haber")->getColumnDimension('N')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("O1", "Importe Original")->getColumnDimension('O')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("P1", "Importe en Dólares")->getColumnDimension('P')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("Q1", "Importe en Soles")->getColumnDimension('Q')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("R1", "Tipo de Documento")->getColumnDimension('R')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("S1", "Número de Documento")->getColumnDimension('S')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("T1", "Fecha de Documento")->getColumnDimension('T')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("U1", "Fecha de Vencimiento")->getColumnDimension('U')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("V1", "Código de Area")->getColumnDimension('V')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("W1", "Glosa Detalle")->getColumnDimension('W')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("X1", "Código de Anexo Auxiliar")->getColumnDimension('X')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("Y1", "Medio de Pago")->getColumnDimension('Y')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("Z1", "Tipo de Documento de Referencia")->getColumnDimension('Z')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AA1", "Número de Documento Referencia")->getColumnDimension('AA')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AB1", "Fecha Documento Referencia")->getColumnDimension('AB')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("AC1", "Nro Máq. Registradora Tipo Doc. Ref.")->getColumnDimension('AC')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AD1", "Base Imponible Documento Referencia")->getColumnDimension('AD')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AE1", "IGV Documento Provisión")->getColumnDimension('AE')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AF1", "Tipo Referencia en estado MQ")->getColumnDimension('AF')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AG1", "Número Serie Caja Registradora")->getColumnDimension('AG')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AH1", "Fecha de Operación")->getColumnDimension('AH')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AI1", "Tipo de Tasa")->getColumnDimension('AI')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AJ1", "Tasa Detracción/Percepción")->getColumnDimension('AJ')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AK1", "Importe Base Detracción/Percepción Dólares")->getColumnDimension('AK')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AL1", "Importe Base Detracción/Percepción Soles")->getColumnDimension('AL')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AM1", "Tipo Cambio para 'F'")->getColumnDimension('AM')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AN1", "Importe de IGV sin derecho crédito fiscal")->getColumnDimension('AN')->setWidth(30);

			//Restricciones del formato del CONCAR.
			$this->excel->getActiveSheet()->setCellValue("A2", "Restricciones");
			$this->excel->getActiveSheet()->setCellValue("B2", "Ver T.G. 02");
			$this->excel->getActiveSheet()->setCellValue("C2", "Los dos primeros dígitos son el mes y los otros 4 siguientes un correlativo");
			$this->excel->getActiveSheet()->setCellValue("D2", "");
			$this->excel->getActiveSheet()->setCellValue("E2", "Ver T.G. 03");
			$this->excel->getActiveSheet()->setCellValue("F2", "");
			$this->excel->getActiveSheet()->setCellValue("G2", "Llenar  solo si Tipo de Conversión es 'C'. Debe estar entre >=0 y <=9999.999999");
			$this->excel->getActiveSheet()->setCellValue("H2", "Solo: 'C'= Especial, 'M'=Compra, 'V'=Venta , 'F' De acuerdo a fecha");
			$this->excel->getActiveSheet()->setCellValue("I2", "Solo: 'S' = Si se convierte, 'N'= No se convierte");
			$this->excel->getActiveSheet()->setCellValue("J2", "Si  Tipo de Conversión 'F'");
			$this->excel->getActiveSheet()->setCellValue("K2", "Debe existir en el Plan de Cuentas");
			$this->excel->getActiveSheet()->setCellValue("L2", "Si Cuenta Contable tiene seleccionado Tipo de Anexo, debe existir en la tabla de Anexos");
			$this->excel->getActiveSheet()->setCellValue("M2", "Si Cuenta Contable tiene habilitado C. Costo, Ver T.G. 05");
			$this->excel->getActiveSheet()->setCellValue("N2", " 'D' ó 'H'");
			$this->excel->getActiveSheet()->setCellValue("O2", "Importe original de la cuenta contable. Obligatorio, debe estar entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("P2", "Importe de la Cuenta Contable en Dólares. Obligatorio si Flag de Conversión de Moneda esta en 'N', debe estar entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("Q2", "Importe de la Cuenta Contable en Soles. Obligatorio si Flag de Conversión de Moneda esta en 'N', debe estra entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("R2", "Si Cuenta Contable tiene habilitado el Documento Referencia Ver T.G. 06");
			$this->excel->getActiveSheet()->setCellValue("S2", "Si Cuenta Contable tiene habilitado el Documento Referencia Incluye Serie y Número");
			$this->excel->getActiveSheet()->setCellValue("T2", "Si Cuenta Contable tiene habilitado el Documento Referencia");
			$this->excel->getActiveSheet()->setCellValue("U2", "Si Cuenta Contable tiene habilitada la Fecha de Vencimiento");
			$this->excel->getActiveSheet()->setCellValue("V2", "Si Cuenta Contable tiene habilitada el Area. Ver T.G. 26");
			$this->excel->getActiveSheet()->setCellValue("W2", "");
			$this->excel->getActiveSheet()->setCellValue("X2", "Si Cuenta Contable tiene seleccionado Tipo de Anexo Referencia");
			$this->excel->getActiveSheet()->setCellValue("Y2", "Si Cuenta Contable tiene habilitado Tipo Medio Pago. Ver T.G. 'S1'");
			$this->excel->getActiveSheet()->setCellValue("Z2", "Si Tipo de Documento es 'NA' ó 'ND' Ver T.G. 06");
			$this->excel->getActiveSheet()->setCellValue("AA2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND', incluye Serie y Número");
			$this->excel->getActiveSheet()->setCellValue("AB2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AC2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'. Solo cuando el Tipo Documento de Referencia 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AD2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AE2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AF2", "Si la Cuenta Contable tiene Habilitado Documento Referencia 2 y  Tipo de Documento es 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AG2", "Si la Cuenta Contable teien Habilitado Documento Referencia 2 y  Tipo de Documento es 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AH2", "Si la Cuenta Contable tiene Habilitado Documento Referencia 2. Cuando Tipo de Documento es 'TK', consignar la fecha de emision del ticket");
			$this->excel->getActiveSheet()->setCellValue("AI2", "Si la Cuenta Contable tiene configurada la Tasa:  Si es '1' ver T.G. 28 y '2' ver T.G. 29");
			$this->excel->getActiveSheet()->setCellValue("AJ2", "Si la Cuenta Contable tiene conf. en Tasa:  Si es '1' ver T.G. 28 y '2' ver T.G. 29. Debe estar entre >=0 y <=999.99");
			$this->excel->getActiveSheet()->setCellValue("AK2", "Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99");
			$this->excel->getActiveSheet()->setCellValue("AL2", "Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99");
			$this->excel->getActiveSheet()->setCellValue("AM2", "Especificar solo si Tipo Conversión es 'F'. Se permite 'M' Compra y 'V' Venta.");
			$this->excel->getActiveSheet()->setCellValue("AN2", "Especificar solo para comprobantes de compras con IGV sin derecho de crédito Fiscal. Se detalle solo en la cuenta 42xxxx");

			//Definimos formato de los campos.
			$this->excel->getActiveSheet()->setCellValue("A3", "Tamaño/Formato");
			$this->excel->getActiveSheet()->setCellValue("B3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("C3", "6 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("D3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("E3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("F3", "40 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("G3", "Numérico 11, 6");
			$this->excel->getActiveSheet()->setCellValue("H3", "1 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("I3", "1 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("J3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("K3", "8 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("L3", "18 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("M3", "6 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("N3", "1 Carácter");
			$this->excel->getActiveSheet()->setCellValue("O3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("P3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("Q3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("R3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("S3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("T3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("U3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("V3", "3 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("W3", "30 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("X3", "18 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("Y3", "8 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("Z3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AA3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AB3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("AC3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AD3", "Numérico 14,2 ");
			$this->excel->getActiveSheet()->setCellValue("AE3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AF3", " 'MQ'");
			$this->excel->getActiveSheet()->setCellValue("AG3", "15 caracteres");
			$this->excel->getActiveSheet()->setCellValue("AH3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("AI3", "5 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AJ3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AK3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AL3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AM3", "1 Caracter");
			$this->excel->getActiveSheet()->setCellValue("AN3", "Numérico 14,2");

			$contador1=5;
			$contador2=6;
			$contador3=7;

			if (substr($numeroSerie, 0, 1) == 'B') {

				//Definimos la data del cuerpo.        
		        foreach($boletas as $b){

		        	//$plan=$b->idplan;

					$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($b->idplan);	
					$centroCosto_array = json_decode(json_encode($centroCosto), true);
					$centroCosto_string = array_values($centroCosto_array)[0];
					$centCosto = $centroCosto_string['centro_costo'];				

					$fechaDocumento = date("d/m/Y", strtotime($fecemi));
		        	$formatoFecha = date("d/m/Y", strtotime($b->fecha_emision));
		        	$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);

		        	$igv = $b->total-$b->neto;
		    		$tot=$b->total;
		    		$nt=$b->neto;
		    		$total = number_format((float)$tot, 2, '.', '');
		    		$neto = number_format((float)$nt, 2, '.', '');
		    		$igvfinal=number_format((float)$igv, 2, '.', '');
		        	
		          	//Informacion de las filas de la consulta.
		          	if ($numeroSerie=='B001') {
		          		$subdiario="05";
		          	} elseif ($numeroSerie=='B002') {
		          		$subdiario="06";
		          	} elseif ($numeroSerie=='B003') {
		          		$subdiario="07";
		          	} elseif ($numeroSerie=='B005') {
		          		$subdiario="08";
		          	} else{
		          		$subdiario="07";
		          	}
		          	$this->excel->getActiveSheet()->setCellValue("B{$contador1}",$subdiario);
					$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$b->mes."".$correConcar);
					$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$fechaDocumento);
					$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
					$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$centCosto);
					$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
					$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
					$this->excel->getActiveSheet()->setCellValue("R{$contador1}","BV");
					$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$b->nombre_plan);


		          	$this->excel->getActiveSheet()->setCellValue("B{$contador2}",$subdiario);
					$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$b->mes."".$correConcar);
					$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$fechaDocumento);
					$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
					$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$centCosto);
					$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$igvfinal);
					$this->excel->getActiveSheet()->setCellValue("R{$contador2}","BV");
					$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$b->nombre_plan);

		          	$this->excel->getActiveSheet()->setCellValue("B{$contador3}",$subdiario);
					$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$b->mes."".$correConcar);
					$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$fechaDocumento);
					$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
					$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$centCosto);
					$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$neto);
					$this->excel->getActiveSheet()->setCellValue("R{$contador3}","BV");
					$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$b->nombre_plan);

		            //Incrementamos filas, para ir a la siguiente.
		            $contador1=$contador1+3;
		            $contador2=$contador2+3;
		            $contador3=$contador3+3;
		            $correlativoConcar = $correlativoConcar+1;

		        }

        		//Le ponemos un nombre al archivo que se va a generar.
		        $objWriter = new PHPExcel_Writer_Excel5($this->excel);
				ob_start();
				$objWriter->save("php://output");
				$xlsData = ob_get_contents();
				ob_end_clean();

				$response =  array(
			        'op' => 'ok',
			        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			    );

				//die(json_encode($response));
				file_put_contents('adjunto/dbf/VENTAS'.$numeroSerie.'.xls', $xlsData);

				//Definición de la base de datos  
				$def = array( 
					array("AVANEXO", "C", 1),
					array("ACODANE", "C", 18), 
					array("ADESANE", "C", 40), 
					array("AREFANE", "C", 50), 
					array("ARUC", "C", 18),
					array("ACODMON", "C", 2),
					array("AESTADO", "C", 1),
					array("ADATE", "D", 8),
					array("AHORA", "C", 6),
					array("AVERETE", "C", 1),
					array("APORRE", "N", 10, 3)
				);

				// creación
				dbase_create('adjunto/dbf/CAN03.dbf', $def);

				$db = dbase_open('adjunto/dbf/CAN03.dbf', 2);
				$anexos = $this->comprobante_pago_mdl->getDatosContratante($fechainicio, $fechafin, $numeroSerie);

				foreach ($anexos as $a) {
					dbase_add_record($db, array('C', $a->cont_numDoc, $a->nombre, $a->cont_direcc, $a->cont_numDoc, '', 'V', '', '', 'S', 0));
				}

				dbase_close($db);
				$mail->isSMTP();
		        //$mail->Host     = 'relay-hosting.secureserver.net';
		        $mail->Host = 'localhost';
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
			    $mail->SMTPSecure = false;
		        $mail->Port     = 25;
		        $mail->SetFrom('aespinoza@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('aespinoza@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo DBF con los anexos y Excel de Ventas con serie ".$numeroSerie.". <br>";
		        $mail->AltBody    = "Se adjunta archivo DBF con los anexos y Excel de Ventas serie ".$numeroSerie.".";
		        $mail->AddAddress('aespinoza@red-salud.com', 'RED SALUD');

		       	$mail->AddAttachment("adjunto/dbf/CAN03.dbf", "CAN03.dbf");
		       	$mail->AddAttachment("adjunto/dbf/VENTAS".$numeroSerie.".xls", "VENTAS".$numeroSerie.".xls");
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send();
		        if($estadoEnvio) {
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

		       	//unlink("adjunto/dbf/VENTAS".$numeroSerie.".xls");
				//header("Content-disposition: attachment; filename=anexos.dbf");
				//header("Content-type: MIME");

		    } elseif (substr($numeroSerie, 0, 1) == 'F') {

		    	//Definimos la data del cuerpo.        
		        foreach($facturas as $f){

		        	$plan=$f->idplan;

					$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($f->idplan);

					foreach($centroCosto as $c){

			        	$formatoFecha = date("d/m/Y", strtotime($f->fecha_emision));
			        	$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);
			        	$fechaDocumento = date("d/m/Y", strtotime($fecemi));
			        	$igv = $f->total-$f->neto;
			    		$tot=$f->total;
			    		$nt=$f->neto;
			    		$total = number_format((float)$tot, 2, '.', '');
			    		$neto = number_format((float)$nt, 2, '.', '');
			    		$igvfinal=number_format((float)$igv, 2, '.', '');

			          	//Informacion de las filas de la consulta.
						$this->excel->getActiveSheet()->setCellValue("B{$contador1}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$fechaDocumento);
						$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
						$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$c->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
						$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
						$this->excel->getActiveSheet()->setCellValue("R{$contador1}","FT");
						$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$f->nombre_plan);

						$this->excel->getActiveSheet()->setCellValue("B{$contador2}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$fechaDocumento);
						$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
						$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$c->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$igvfinal);
						$this->excel->getActiveSheet()->setCellValue("R{$contador2}","FT");
						$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$f->nombre_plan);

						$this->excel->getActiveSheet()->setCellValue("B{$contador3}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$fechaDocumento);
						$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
						$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$c->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$neto);
						$this->excel->getActiveSheet()->setCellValue("R{$contador3}","FT");
						$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$f->nombre_plan);

			            //Incrementamos filas, para ir a la siguiente.
			            $contador1=$contador1+3;
			            $contador2=$contador2+3;
			            $contador3=$contador3+3;
				        $correlativoConcar = $correlativoConcar+1;
			        }
		        }

		        //Le ponemos un nombre al archivo que se va a generar.
		        $objWriter = new PHPExcel_Writer_Excel5($this->excel);
				ob_start();
				$objWriter->save("php://output");
				$xlsData = ob_get_contents();
				ob_end_clean();

				$response =  array(
				        'op' => 'ok',
				        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
				    );

				//die(json_encode($response));
				file_put_contents('adjunto/dbf/VENTAS'.$numeroSerie.'.xls', $xlsData);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('aespinoza@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('aespinoza@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo Excel de Ventas con serie ".$numeroSerie.". <br>";
		        $mail->AltBody    = "Se adjunta archivo Excel de Ventas con serie ".$numeroSerie.".";
		        $mail->AddAddress('aespinoza@red-salud.com', 'RED SALUD');

		       	//$mail->AddAttachment("adjunto/dbf/CAN03.dbf", "CAN03.dbf");
		       	$mail->AddAttachment("adjunto/dbf/VENTAS".$numeroSerie.".xls", "VENTAS".$numeroSerie.".xls");
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send();
		        if($estadoEnvio){
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

		        //unlink("adjunto/dbf/VENTAS".$numeroSerie.".xls");
				//header("Content-disposition: attachment; filename=anexos.dbf");
				//header("Content-type: MIME");
		    }
	}

	public function generarExcelCob(){

		$mail = new PHPMailer();

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');

		$fecemi = $data['fechaEmision'];

		$canales = $_POST['canales'];

		$fechainicio = $_POST['fechainicio'];
		$fechafin = $_POST['fechafin'];

		$numeroSerie = $_POST['numeroSerie'];	
		$correlativoConcar = $_POST['concar'];
		$fechaConcar = $_POST['fechaConcar'];
			
		$boletas = $this->comprobante_pago_mdl->getDatosExcelBoletas($fechainicio, $fechafin, $numeroSerie);
		$facturas = $this->comprobante_pago_mdl->getDatosExcelFacturas($fechainicio, $fechafin, $numeroSerie);

			//Se carga la librería de excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle('Hoja 1');

			//contador de filas
			$estiloBorde = array(
			   'borders' => array(
			     'allborders' => array(
			       'style' => PHPExcel_Style_Border::BORDER_THIN
			     )
			   )
			 );

			$this->excel->getActiveSheet()->getStyle('A1:AN3')->applyFromArray($estiloBorde);
			unset($styleArray); 

			$estiloCentrar = array( 
			 	'alignment' => array( 
			  		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
			  		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
			  	) 
			); 

			$this->excel->getActiveSheet()->getStyle('A1:AN3')->applyFromArray($estiloCentrar);
			$this->excel->getActiveSheet()->getStyle('A1:AN3')->getAlignment()->setWrapText(true);

			$estiloColor = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'FFFF00')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('A1:A3')->applyFromArray($estiloColor);

			$estiloColorDos = array(
		    	'fill' => array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'color' => array('rgb' => 'F4A900')
		    	)
		    );

			$this->excel->getActiveSheet()->getStyle('B1:AN1')->applyFromArray($estiloColorDos);

			$this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
			$this->excel->getActiveSheet()->getRowDimension(2)->setRowHeight(120);
			
			//Definimos los títulos de la cabecera Y ancho de columnas.
			$this->excel->getActiveSheet()->setCellValue("A1", "Campo")->getColumnDimension('A')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("B1", "Sub Diario")->getColumnDimension('B')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("C1", "Número de Comprobante")->getColumnDimension('C')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("D1", "Fecha de Comprobante")->getColumnDimension('D')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("E1", "Código de Moneda")->getColumnDimension('E')->setWidth(15);
			$this->excel->getActiveSheet()->setCellValue("F1", "Glosa Principal")->getColumnDimension('F')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("G1", "Tipo de Cambio")->getColumnDimension('G')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("H1", "Tipo de Conversión")->getColumnDimension('H')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("I1", "Flag de Conversión de Moneda")->getColumnDimension('I')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("J1", "Fecha Tipo de Cambio")->getColumnDimension('J')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("K1", "Cuenta Contable")->getColumnDimension('K')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("L1", "Código de Anexo")->getColumnDimension('L')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("M1", "Código de Centro de Costo")->getColumnDimension('M')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("N1", "Debe / Haber")->getColumnDimension('N')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("O1", "Importe Original")->getColumnDimension('O')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("P1", "Importe en Dólares")->getColumnDimension('P')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("Q1", "Importe en Soles")->getColumnDimension('Q')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("R1", "Tipo de Documento")->getColumnDimension('R')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("S1", "Número de Documento")->getColumnDimension('S')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("T1", "Fecha de Documento")->getColumnDimension('T')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("U1", "Fecha de Vencimiento")->getColumnDimension('U')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("V1", "Código de Area")->getColumnDimension('V')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("W1", "Glosa Detalle")->getColumnDimension('W')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("X1", "Código de Anexo Auxiliar")->getColumnDimension('X')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("Y1", "Medio de Pago")->getColumnDimension('Y')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("Z1", "Tipo de Documento de Referencia")->getColumnDimension('Z')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AA1", "Número de Documento Referencia")->getColumnDimension('AA')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AB1", "Fecha Documento Referencia")->getColumnDimension('AB')->setWidth(20);
			$this->excel->getActiveSheet()->setCellValue("AC1", "Nro Máq. Registradora Tipo Doc. Ref.")->getColumnDimension('AC')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AD1", "Base Imponible Documento Referencia")->getColumnDimension('AD')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AE1", "IGV Documento Provisión")->getColumnDimension('AE')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AF1", "Tipo Referencia en estado MQ")->getColumnDimension('AF')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AG1", "Número Serie Caja Registradora")->getColumnDimension('AG')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AH1", "Fecha de Operación")->getColumnDimension('AH')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AI1", "Tipo de Tasa")->getColumnDimension('AI')->setWidth(25);
			$this->excel->getActiveSheet()->setCellValue("AJ1", "Tasa Detracción/Percepción")->getColumnDimension('AJ')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AK1", "Importe Base Detracción/Percepción Dólares")->getColumnDimension('AK')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AL1", "Importe Base Detracción/Percepción Soles")->getColumnDimension('AL')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AM1", "Tipo Cambio para 'F'")->getColumnDimension('AM')->setWidth(30);
			$this->excel->getActiveSheet()->setCellValue("AN1", "Importe de IGV sin derecho crédito fiscal")->getColumnDimension('AN')->setWidth(30);

			//Restricciones del formato del CONCAR.
			$this->excel->getActiveSheet()->setCellValue("A2", "Restricciones");
			$this->excel->getActiveSheet()->setCellValue("B2", "Ver T.G. 02");
			$this->excel->getActiveSheet()->setCellValue("C2", "Los dos primeros dígitos son el mes y los otros 4 siguientes un correlativo");
			$this->excel->getActiveSheet()->setCellValue("D2", "");
			$this->excel->getActiveSheet()->setCellValue("E2", "Ver T.G. 03");
			$this->excel->getActiveSheet()->setCellValue("F2", "");
			$this->excel->getActiveSheet()->setCellValue("G2", "Llenar  solo si Tipo de Conversión es 'C'. Debe estar entre >=0 y <=9999.999999");
			$this->excel->getActiveSheet()->setCellValue("H2", "Solo: 'C'= Especial, 'M'=Compra, 'V'=Venta , 'F' De acuerdo a fecha");
			$this->excel->getActiveSheet()->setCellValue("I2", "Solo: 'S' = Si se convierte, 'N'= No se convierte");
			$this->excel->getActiveSheet()->setCellValue("J2", "Si  Tipo de Conversión 'F'");
			$this->excel->getActiveSheet()->setCellValue("K2", "Debe existir en el Plan de Cuentas");
			$this->excel->getActiveSheet()->setCellValue("L2", "Si Cuenta Contable tiene seleccionado Tipo de Anexo, debe existir en la tabla de Anexos");
			$this->excel->getActiveSheet()->setCellValue("M2", "Si Cuenta Contable tiene habilitado C. Costo, Ver T.G. 05");
			$this->excel->getActiveSheet()->setCellValue("N2", " 'D' ó 'H'");
			$this->excel->getActiveSheet()->setCellValue("O2", "Importe original de la cuenta contable. Obligatorio, debe estar entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("P2", "Importe de la Cuenta Contable en Dólares. Obligatorio si Flag de Conversión de Moneda esta en 'N', debe estar entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("Q2", "Importe de la Cuenta Contable en Soles. Obligatorio si Flag de Conversión de Moneda esta en 'N', debe estra entre >=0 y <=99999999999.99 ");
			$this->excel->getActiveSheet()->setCellValue("R2", "Si Cuenta Contable tiene habilitado el Documento Referencia Ver T.G. 06");
			$this->excel->getActiveSheet()->setCellValue("S2", "Si Cuenta Contable tiene habilitado el Documento Referencia Incluye Serie y Número");
			$this->excel->getActiveSheet()->setCellValue("T2", "Si Cuenta Contable tiene habilitado el Documento Referencia");
			$this->excel->getActiveSheet()->setCellValue("U2", "Si Cuenta Contable tiene habilitada la Fecha de Vencimiento");
			$this->excel->getActiveSheet()->setCellValue("V2", "Si Cuenta Contable tiene habilitada el Area. Ver T.G. 26");
			$this->excel->getActiveSheet()->setCellValue("W2", "");
			$this->excel->getActiveSheet()->setCellValue("X2", "Si Cuenta Contable tiene seleccionado Tipo de Anexo Referencia");
			$this->excel->getActiveSheet()->setCellValue("Y2", "Si Cuenta Contable tiene habilitado Tipo Medio Pago. Ver T.G. 'S1'");
			$this->excel->getActiveSheet()->setCellValue("Z2", "Si Tipo de Documento es 'NA' ó 'ND' Ver T.G. 06");
			$this->excel->getActiveSheet()->setCellValue("AA2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND', incluye Serie y Número");
			$this->excel->getActiveSheet()->setCellValue("AB2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AC2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'. Solo cuando el Tipo Documento de Referencia 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AD2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AE2", "Si Tipo de Documento es 'NC', 'NA' ó 'ND'");
			$this->excel->getActiveSheet()->setCellValue("AF2", "Si la Cuenta Contable tiene Habilitado Documento Referencia 2 y  Tipo de Documento es 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AG2", "Si la Cuenta Contable teien Habilitado Documento Referencia 2 y  Tipo de Documento es 'TK'");
			$this->excel->getActiveSheet()->setCellValue("AH2", "Si la Cuenta Contable tiene Habilitado Documento Referencia 2. Cuando Tipo de Documento es 'TK', consignar la fecha de emision del ticket");
			$this->excel->getActiveSheet()->setCellValue("AI2", "Si la Cuenta Contable tiene configurada la Tasa:  Si es '1' ver T.G. 28 y '2' ver T.G. 29");
			$this->excel->getActiveSheet()->setCellValue("AJ2", "Si la Cuenta Contable tiene conf. en Tasa:  Si es '1' ver T.G. 28 y '2' ver T.G. 29. Debe estar entre >=0 y <=999.99");
			$this->excel->getActiveSheet()->setCellValue("AK2", "Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99");
			$this->excel->getActiveSheet()->setCellValue("AL2", "Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99");
			$this->excel->getActiveSheet()->setCellValue("AM2", "Especificar solo si Tipo Conversión es 'F'. Se permite 'M' Compra y 'V' Venta.");
			$this->excel->getActiveSheet()->setCellValue("AN2", "Especificar solo para comprobantes de compras con IGV sin derecho de crédito Fiscal. Se detalle solo en la cuenta 42xxxx");

			//Definimos formato de los campos.
			$this->excel->getActiveSheet()->setCellValue("A3", "Tamaño/Formato");
			$this->excel->getActiveSheet()->setCellValue("B3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("C3", "6 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("D3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("E3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("F3", "40 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("G3", "Numérico 11, 6");
			$this->excel->getActiveSheet()->setCellValue("H3", "1 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("I3", "1 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("J3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("K3", "8 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("L3", "18 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("M3", "6 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("N3", "1 Carácter");
			$this->excel->getActiveSheet()->setCellValue("O3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("P3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("Q3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("R3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("S3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("T3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("U3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("V3", "3 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("W3", "30 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("X3", "18 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("Y3", "8 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("Z3", "2 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AA3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AB3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("AC3", "20 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AD3", "Numérico 14,2 ");
			$this->excel->getActiveSheet()->setCellValue("AE3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AF3", " 'MQ'");
			$this->excel->getActiveSheet()->setCellValue("AG3", "15 caracteres");
			$this->excel->getActiveSheet()->setCellValue("AH3", "dd/mm/aaaa");
			$this->excel->getActiveSheet()->setCellValue("AI3", "5 Caracteres");
			$this->excel->getActiveSheet()->setCellValue("AJ3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AK3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AL3", "Numérico 14,2");
			$this->excel->getActiveSheet()->setCellValue("AM3", "1 Caracter");
			$this->excel->getActiveSheet()->setCellValue("AN3", "Numérico 14,2");

 
			$contador1=5;
			$contador2=null;

			if (substr($numeroSerie, 0, 1) == 'B') {

				//Definimos la data del cuerpo.        
		        foreach($boletas as $b){

		        	//$plan=$b->idplan;

					/*$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($canales);					

					foreach($centroCosto as $c){
						$centCosto = $c->centro_costo;
					}*/

					$sumaTotal = $this->comprobante_pago_mdl->getSumaExcel($fechainicio, $fechafin, $numeroSerie);					

					foreach($sumaTotal as $st){
						$totalExcel = $st->suma;
					}

		        	$formatoFecha = date("d/m/Y", strtotime($b->fecha_emision));
		        	$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);
		        	$concarFecha = date("d/m/Y", strtotime($fechaConcar));
		        	$serieCorre = $b->serie."-".$b->correlativo;
		        	$nombrePlan=$b->nombre_plan;
		        	$fechaDocumento = date("d/m/Y", strtotime($fecemi));
		        	$igv = $b->total-$b->neto;
		    		$tot=$b->total;
		    		$nt=$b->neto;
		    		$total = number_format((float)$tot, 2, '.', '');
		    		$neto = number_format((float)$nt, 2, '.', '');
		    		$igvfinal=number_format((float)$igv, 2, '.', '');
		        	
		          	//Informacion de las filas de la consulta.
					$this->excel->getActiveSheet()->setCellValue("B{$contador1}","21");
					$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$b->mes."".$correConcar);
					$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$fechaDocumento);
					$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
					$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$b->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador1}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
					$this->excel->getActiveSheet()->setCellValue("R{$contador1}","BV");
					$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$b->nombre_plan);

		            //Incrementamos filas, para ir a la siguiente.
		            $contador1=$contador1+1;
		        }

		        $contador2=$contador1;
		        //$correConcar=$correConcar+1;
		        //$correConcarDos = str_pad($correConcar, 4, "0", STR_PAD_LEFT);

				$this->excel->getActiveSheet()->setCellValue("B{$contador2}","21");
				$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$b->mes."".$correConcar);
				$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$fechaDocumento);
				$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
				$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$b->nombre_plan);
				$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
				$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
				if ($canales == 1) {
					$this->excel->getActiveSheet()->setCellValue("K{$contador2}","1041011");
					$this->excel->getActiveSheet()->setCellValue("L{$contador2}","011");
					$this->excel->getActiveSheet()->setCellValue("X{$contador2}","011");
				} else {
					$this->excel->getActiveSheet()->setCellValue("K{$contador2}","1041022");
					$this->excel->getActiveSheet()->setCellValue("L{$contador2}","014");
					$this->excel->getActiveSheet()->setCellValue("X{$contador2}","014");
				}
				$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$b->centro_costo);
				$this->excel->getActiveSheet()->setCellValue("N{$contador2}","D");
				$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$totalExcel);
				$this->excel->getActiveSheet()->setCellValue("R{$contador2}","TI");
				$this->excel->getActiveSheet()->setCellValue("S{$contador2}","00000001");
				$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$concarFecha);
				$this->excel->getActiveSheet()->setCellValue("V{$contador2}","010");
				$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$nombrePlan);
				$this->excel->getActiveSheet()->setCellValue("Y{$contador2}","003");

        		//Le ponemos un nombre al archivo que se va a generar.
		        $objWriter = new PHPExcel_Writer_Excel5($this->excel);
				ob_start();
				$objWriter->save("php://output");
				$xlsData = ob_get_contents();
				ob_end_clean();

				$response =  array(
			        'op' => 'ok',
			        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			    );

				die(json_encode($response));
				/*file_put_contents('adjunto/dbf/COBRANZAS'.$numeroSerie.'.xls', $xlsData);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('contabilidad@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('contabilidad@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo Excel de Cobranzas con serie ".$numeroSerie.". <br>";
		        $mail->AltBody    = "Se adjunta archivo Excel de Cobranzas con serie ".$numeroSerie.".";
		        $mail->AddAddress('contabilidad@red-salud.com', 'RED SALUD');

		       	$mail->AddAttachment("adjunto/dbf/COBRANZAS".$numeroSerie.".xls", "COBRANZAS".$numeroSerie.".xls");
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send();
		        if($estadoEnvio) {
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

		       	//unlink("adjunto/dbf/VENTAS".$numeroSerie.".xls");
				//header("Content-disposition: attachment; filename=anexos.dbf");
				//header("Content-type: MIME");*/

		    } elseif (substr($numeroSerie, 0, 1) == 'F') {

		    	//Definimos la data del cuerpo.        
		        foreach($facturas as $f){

		        	$plan=$f->idplan;

					$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($plan);

					foreach($centroCosto as $c){

			        	$formatoFecha = date("d/m/Y", strtotime($f->fecha_emision));
			        	$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);
		        		$fechaDocumento = date("d/m/Y", strtotime($fecemi));
			        	$igv = $f->total-$f->neto;
			    		$tot=$f->total;
			    		$nt=$f->neto;
			    		$total = number_format((float)$tot, 2, '.', '');
			    		$neto = number_format((float)$nt, 2, '.', '');
			    		$igvfinal=number_format((float)$igv, 2, '.', '');
		        		$concarFecha = date("d/m/Y", strtotime($fechaConcar));

			          	//Informacion de las filas de la consulta.
						$this->excel->getActiveSheet()->setCellValue("B{$contador1}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$fechaDocumento);
						$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
						$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$f->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
						$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
						$this->excel->getActiveSheet()->setCellValue("R{$contador1}","FT");
						$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$f->nombre_plan);

						$this->excel->getActiveSheet()->setCellValue("B{$contador2}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$fechaDocumento);
						$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
						$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$f->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$igvfinal);
						$this->excel->getActiveSheet()->setCellValue("R{$contador2}","FT");
						$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$concarFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$f->nombre_plan);

		            //Incrementamos filas, para ir a la siguiente.
		            $contador1=$contador1+2;
		            $contador2=$contador2+2;
			        }
		        }

		        //Le ponemos un nombre al archivo que se va a generar.
		        $objWriter = new PHPExcel_Writer_Excel5($this->excel);
				ob_start();
				$objWriter->save("php://output");
				$xlsData = ob_get_contents();
				ob_end_clean();

				$response =  array(
				        'op' => 'ok',
				        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
				    );

				//die(json_encode($response));
				file_put_contents('adjunto/dbf/COBRANZAS'.$numeroSerie.'.xls', $xlsData);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('contabilidad@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('contabilidad@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo Excel de Cobranzas con serie ".$numeroSerie.". <br>";
		        $mail->AltBody    = "Se adjunta archivo Excel de Cobranzas con serie ".$numeroSerie.".";
		        $mail->AddAddress('contabilidad@red-salud.com', 'RED SALUD');

		       	//$mail->AddAttachment("adjunto/dbf/CAN03.dbf", "CAN03.dbf");
		       	$mail->AddAttachment("adjunto/dbf/COBRANZAS".$numeroSerie.".xls", "COBRANZAS".$numeroSerie.".xls");
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send();
		        if($estadoEnvio){
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

		        //unlink("adjunto/dbf/VENTAS".$numeroSerie.".xls");
				//header("Content-disposition: attachment; filename=anexos.dbf");
				//header("Content-type: MIME");
		    }
	}

	public function generarPdf($idcomprobante, $canales){

		include ('./application/libraries/phpqrcode/qrlib.php');

		$numLet = new NumeroALetras();

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
			
		$boletas = $this->comprobante_pago_mdl->getDatosPdfBoletas($idcomprobante);
		$boletasArray = json_decode(json_encode($boletas), true);
		$boletasstring = array_values($boletasArray)[0];
		$serie = $boletasstring['serie'];

		$facturas = $this->comprobante_pago_mdl->getDatosPdfFacturas($idcomprobante);

		//Carga la librería que agregamos
        $this->load->library('pdf');

        $this->pdf = new Pdf();
	    $this->pdf->AddPage();
	    $this->pdf->AliasNbPages();
	    //$this->pdf->Image(base_url().'/public/assets/avatars/user.jpg','0','0','150','150','JPG');

	    if (substr($serie, 0, 1) == 'B') {
	    	foreach ($boletas as $b){

	    		if ($b->tipo_moneda == 'PEN') {
	    			$moneda = 'S/. ';
	    			$nomMoneda = 'SOLES';
	    		} elseif ($b->tipo_moneda == 'USD') {
	    			$moneda = '$ ';
	    			$nomMoneda = utf8_decode('DÓLARES');
	    		}

	    		$fechaFormato = date("d/m/Y", strtotime($b->fecha_emision));

	    		$igv = $b->total-$b->neto;
	    		$tot=$b->total;
	    		$nt=$b->neto;
	    		$total = number_format((float)$tot, 2, '.', ',');
	    		$neto = number_format((float)$nt, 2, '.', ',');
	    		$igvfinal=number_format((float)$igv, 2, '.', ',');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$content = "20600258894 | ".$b->serie."-".$b->correlativo." | ".$b->fecha_emision." | ".$b->total." | ".$igvfinal." | ".$b->cont_numDoc;
				QRcode::png($content , 'adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.".png" , QR_ECLEVEL_L , 10 , 2);
				//exit();x

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('BOLETA DE VENTA ELECTRÓNICA')."\n"."RUC:20600258894"."\n"."Nro: ".$b->serie."-".$b->correlativo,1,'C', false);
	            $this->pdf->Ln('10');
	          	$this->pdf->SetFont('Arial','B',12); 
	            $this->pdf->Cell(0,0,utf8_decode($b->contratante),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"DNI: ".$b->cont_numDoc,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").utf8_decode($b->cont_direcc),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"fecha: ".$fechaFormato,0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',8);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(25,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(30,10,"Precio Unitario",1,0,'C', true);
	            $this->pdf->Cell(25,10,"Descuento",1,0,'C', true);
	            $this->pdf->Cell(30,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',8);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(25,10,"1",1,0,'C');
	            if ($b->id_contratante == NULL) {
	            	$this->pdf->Cell(80,10,utf8_decode($b->nombre_plan).' - '.utf8_decode($b->sustento_nota),1,0,'C');
	            } else {
	           		$this->pdf->Cell(80,10,utf8_decode($b->nombre_plan),1,0,'C');
	            }
	            $this->pdf->Cell(30,10,$moneda.$total,1,0,'C');
	            $this->pdf->Cell(25,10,$moneda."0.00",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$total,1,0,'C');
	            $this->pdf->Ln('15');
	            //$this->pdf->Cell(80);

	            $this->pdf->SetFont('Arial', '',8);
	            $this->pdf->Cell(100,10,"  SON ".$numLet->convertir($totalSinDec, 'CON')." ".$totalDec."/100".$nomMoneda,0,0,'L');
	            $this->pdf->Image(base_url().'/adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png',24,135,30,0);
	            /*$this->pdf->Cell(60,10,"Operaciones gravadas",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$neto." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones inafectas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones exoneradas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones gratuitas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Total de Descuentos",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"IGV",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$igvfinal." ",1,0,'R');*/
	            $this->pdf->Ln('5');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$total." ",1,0,'R');
	            $this->pdf->Ln('35');
	            $this->pdf->Cell(100,10,utf8_decode("Representación de Boleta de venta electrónica."),0,0,'L');


	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($b->mes."".$b->serie."".$b->correlativo.".pdf", 'I');

		        unlink('adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png');

	    	}
	    } elseif (substr($serie, 0, 1) == 'F') {
	    	foreach ($facturas as $f){

	    		if ($f->tipo_moneda == 'PEN') {
	    			$moneda = 'S/. ';
	    			$nomMoneda = 'SOLES';
	    		} elseif ($f->tipo_moneda == 'USD') {
	    			$moneda = '$ ';
	    			$nomMoneda = utf8_decode('DÓLARES');
	    		}

	    		$igv = $f->total-$f->neto;
	    		$tot=$f->total;
	    		$nt=$f->neto;
	    		$total = number_format((float)$tot, 2, '.', ',');
	    		$neto = number_format((float)$nt, 2, '.', ',');
	    		$igvfinal=number_format((float)$igv, 2, '.', ',');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$fechaFormato = date("d/m/Y", strtotime($f->fecha_emision));

	    		$content = "20600258894 | ".$f->serie."-".$f->correlativo." | ".$f->fecha_emision." | ".$f->total." | ".$igvfinal." | ".$f->numero_documento_cli;
				QRcode::png($content , 'adjunto/qr/'.$f->mes."".$f->serie."".$f->correlativo.".png" , QR_ECLEVEL_L , 10 , 2);
				//exit();

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('FACTURA ELECTRÓNICA')."\n"."RUC:20600258894"."\n"."Nro: ".$f->serie."-".$f->correlativo,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,utf8_decode($f->razon_social_cli),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"RUC: ".$f->numero_documento_cli,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").utf8_decode($f->direccion_legal),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"fecha: ".$fechaFormato,0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(25,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(30,10,"Precio Unitario",1,0,'C', true);
	            $this->pdf->Cell(25,10,"Descuento",1,0,'C', true);
	            $this->pdf->Cell(30,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(25,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,utf8_decode($f->sustento_nota),1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$neto,1,0,'C');
	            $this->pdf->Cell(25,10,$moneda."0.00",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$neto,1,0,'C');
	            $this->pdf->Ln('15');
	            //$this->pdf->Cell(80);

				$this->pdf->SetFont('Arial', '',8);
				if ($f->numero_letras == NULL) {
					$this->pdf->Cell(120,10,"  SON ".$numLet->convertir($totalSinDec, 'CON')." ".$totalDec."/100 ".$nomMoneda,0,0,'L');
				} else {
					$this->pdf->Cell(120,10,"  SON ".$f->numero_letras,0,0,'L');
				}
	            
	            $this->pdf->Image(base_url().'/adjunto/qr/'.$f->mes."".$f->serie."".$f->correlativo.'.png',50,140,30,0);

	            $this->pdf->Cell(40,10,"Operaciones gravadas",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$neto." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"Operaciones inafectas",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda."0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"Operaciones exoneradas",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda."0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"Operaciones gratuitas",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda."0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"Total de Descuentos",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda."0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"IGV",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$igvfinal." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(120);
	            $this->pdf->Cell(40,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,$moneda.$total." ",1,0,'R');
	            $this->pdf->Ln('-20');
	            $this->pdf->Cell(25);
	            $this->pdf->Cell(175,10,utf8_decode("Representación de Factura de venta electrónica."),0,1,'L');

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($f->mes."".$f->serie."".$f->correlativo.".pdf", 'I');

		        unlink('adjunto/qr/'.$f->mes."".$f->serie."".$f->correlativo.'.png');

	    	}
	    }
	}

	public function enviarPdf($idcomprobante, $canalesDos){

		$data['idcomprobante'] = $idcomprobante;
		$data['canalesDos'] = $canalesDos;

		$this->load->view('dsb/html/comprobante/enviar_pdf.php', $data);
	}

	public function envioEmail(){

		include ('./application/libraries/phpqrcode/qrlib.php');

		$numLet = new NumeroALetras();

		$idcomprobante=$_POST['idcomprobante'];
		$canalesDos=$_POST['canales'];

		include ('./application/libraries/xmldsig/src/XMLSecurityDSig.php');
    	include ('./application/libraries/xmldsig/src/XMLSecurityKey.php');
    	include ('./application/libraries/xmldsig/src/Sunat/SignedXml.php');

    	$this->xml = new XMLWriter();
		
		$mail = new PHPMailer();

		$email = $this->input->post('correo');

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
			
		$boletas = $this->comprobante_pago_mdl->getDatosPdfBoletas($idcomprobante);
		$facturas = $this->comprobante_pago_mdl->getDatosPdfFacturas($idcomprobante);

		//Carga la librería que agregamos
        $this->load->library('pdf');

        $this->pdf = new Pdf();
	    $this->pdf->AddPage();
	    $this->pdf->AliasNbPages();

	    if ($canalesDos == 1 || $canalesDos == 2 || $canalesDos == 3 || $canalesDos == 6 || $canalesDos == 7 || $canalesDos == 8 || $canalesDos == 9 || $canalesDos == 10) {
	    	foreach ($boletas as $b){

	    		$fechaFormato = date("d/m/Y", strtotime($b->fecha_emision));

	    		$igv = $b->total-$b->neto;
	    		$tot=$b->total;
	    		$nt=$b->neto;
	    		$total = number_format((float)$tot, 2, '.', '');
	    		$neto = number_format((float)$nt, 2, '.', '');
	    		$igvfinal=number_format((float)$igv, 2, '.', '');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$content = "20600258894 | ".$b->serie."-".$b->correlativo." | ".$b->fecha_emision." | ".$b->total." | ".$igvfinal." | ".$b->cont_numDoc;
				QRcode::png($content , 'adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.".png" , QR_ECLEVEL_L , 10 , 2);
	    		          
	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('BOLETA DE VENTA ELECTRÓNICA')."\n"."RUC:20600258894"."\n"."Nro: ".$b->serie."-".$b->correlativo,1,'C', false);
	            $this->pdf->Ln('10');
	          	$this->pdf->SetFont('Arial','B',12); 
	            $this->pdf->Cell(0,0,$b->contratante,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"DNI: ".$b->cont_numDoc,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").$b->cont_direcc,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"fecha: ".$fechaFormato,0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(25,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(30,10,"Precio Unitario",1,0,'C', true);
	            $this->pdf->Cell(25,10,"Descuento",1,0,'C', true);
	            $this->pdf->Cell(30,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(25,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,$b->nombre_plan,1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$neto,1,0,'C');
	            $this->pdf->Cell(25,10,"S/. 0.00",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$neto,1,0,'C');
	            $this->pdf->Ln('15');

				$this->pdf->SetFont('Arial', '',8);
	            $this->pdf->Cell(100,10,"   ".$numLet->convertir($totalSinDec, 'Y')." ".$totalDec."/100",0,0,'L');
	            $this->pdf->Image(base_url().'/adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png',24,135,30,0);

	            $this->pdf->Ln('5');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$total." ",1,0,'R');
	            $this->pdf->Ln('35');
	            $this->pdf->Cell(100,10,utf8_decode("Representación de Boleta de venta electrónica."),0,0,'L');

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf", 'F');

		        unlink('adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png');

		        $filename="20600258894-03-".$b->serie."-".$b->correlativo;

	    		$datos = '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ccts="urn:un:unece:uncefact:documentation:2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
         xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent>
      </ext:ExtensionContent>
    </ext:UBLExtension>
  </ext:UBLExtensions>
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ProfileID schemeName="SUNAT:Identificador de Tipo de Operación" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
  <cbc:ID>'.$b->serie.'-'.$b->correlativo.'</cbc:ID>
  <cbc:IssueDate>'.$b->fecha_emision.'</cbc:IssueDate>
  <!--<cbc:IssueTime>15:20:30</cbc:IssueTime>-->
  <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">03</cbc:InvoiceTypeCode>
  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">PEN</cbc:DocumentCurrencyCode>
  <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
  <cac:Signature>
    <cbc:ID>SignIMM</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
        <cbc:ID>20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
        <cbc:URI>#SignIMM</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>
  <cac:AccountingCustomerParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="1"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$b->cont_numDoc.'</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>'.$b->contratante.'</cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingCustomerParty>
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">'.$neto.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
      <cac:TaxCategory>
        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
        <cac:TaxScheme>
          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="PEN">'.$total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">'.$neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">'.$total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">'.$neto.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>'.$b->nombre_plan.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';

				$doc = new DOMDocument(); 
				$doc->loadxml($datos);
				$doc->save('adjunto/comprobantes/'.$filename.'.xml');

				$xmlPath = 'adjunto/comprobantes/'.$filename.'.xml';
				$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 

				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);

				$xmlSigned = $signer->signFromFile($xmlPath);

				file_put_contents('adjunto/comprobantes/'.$filename.'.xml', $xmlSigned);

				$mail->isSMTP();
		        $mail->Host = 'localhost';
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
			    $mail->SMTPSecure = false;
		        $mail->Port     = 25;
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Comprobante de pago";
		        $mail->Body 	  = "Se adjunta boleta de venta. <br>";
		        $mail->AltBody    = "Se adjunta boleta de venta.";
		        $mail->AddAddress($email);

		       	$mail->AddAttachment("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf", $b->mes."".$b->serie."".$b->correlativo.".pdf", 'base64', 'application/pdf');
		       	$mail->AddAttachment("adjunto/comprobantes/".$filename.".xml", $filename.".xml", 'base64', 'application/xml');
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send(); 
				if($estadoEnvio){
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

			}

		    unlink("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf");
			unlink("adjunto/comprobantes/".$filename.".xml");
	    
	    } elseif ($canalesDos == 4) {
	    	foreach ($facturas as $f){

	    		$fechaFormato = date("d/m/Y", strtotime($f->fecha_emision));

	    		$igv = $f->total-$f->neto;
	    		$tot=$f->total;
	    		$nt=$f->neto;
	    		$total = number_format((float)$tot, 2, '.', '');
	    		$neto = number_format((float)$nt, 2, '.', '');
	    		$igvfinal=number_format((float)$igv, 2, '.', '');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$content = "20600258894 | ".$f->serie."-".$f->correlativo." | ".$f->fecha_emision." | ".$f->total." | ".$igvfinal." | ".$f->numero_documento_cli;
				QRcode::png($content , 'adjunto/qr/'.$f->mes."".$f->serie."".$f->correlativo.".png" , QR_ECLEVEL_L , 10 , 2);

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('FACTURA ELECTRÓNICA')."\n"."RUC:20600258894"."\n"."Nro: ".$f->serie."-".$f->correlativo,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,$f->razon_social_cli,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"RUC: ".$f->numero_documento_cli,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").$f->direccion_legal,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"fecha: ".$fechaFormato,0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(25,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(30,10,"Precio Unitario",1,0,'C', true);
	            $this->pdf->Cell(25,10,"Descuento",1,0,'C', true);
	            $this->pdf->Cell(30,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(25,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,$f->nombre_plan,1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$neto,1,0,'C');
	            $this->pdf->Cell(25,10,"S/. 0.00",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$neto,1,0,'C');
	            $this->pdf->Ln('20');
	            //$this->pdf->Cell(80);
	            
	            $this->pdf->SetFont('Arial', '',8);
	            $this->pdf->Cell(100,10,"   ".$numLet->convertir($totalSinDec, 'Y')." ".$totalDec."/100",0,0,'L');
	            $this->pdf->Image(base_url().'/adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png',24,135,30,0);

	            $this->pdf->Cell(60,10,"Operaciones gravadas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$neto." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones inafectas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones exoneradas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Operaciones gratuitas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Total de Descuentos",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"IGV",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$igvfinal." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(100);
	            $this->pdf->Cell(60,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$total." ",1,0,'R');
	            $this->pdf->Ln('-20');
	            $this->pdf->Cell(100,10,utf8_decode("Representación de Factura de venta electrónica."),0,0,'L');

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output("adjunto/comprobantes/".$f->mes."".$f->serie."".$f->correlativo.".pdf", 'F');

		        $filename="20600258894-01-".$f->serie."-".$f->correlativo;

					$datos = '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ccts="urn:un:unece:uncefact:documentation:2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
         xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent>
      </ext:ExtensionContent>
    </ext:UBLExtension>
  </ext:UBLExtensions>
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ProfileID schemeName="SUNAT:Identificador de Tipo de Operación" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
  <cbc:ID>'.$f->serie.'-'.$f->correlativo.'</cbc:ID>
  <cbc:IssueDate>'.$f->fecha_emision.'</cbc:IssueDate>
  <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">01</cbc:InvoiceTypeCode>
  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">PEN</cbc:DocumentCurrencyCode>
  <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
  <cac:Signature>
    <cbc:ID>SignIMM</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
        <cbc:ID>20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
        <cbc:URI>#SignIMM</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
        <cac:RegistrationAddress>
			<cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>
  <cac:AccountingCustomerParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$f->numero_documento_cli.'</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>'.$f->razon_social_cli.'</cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingCustomerParty>
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">'.$total.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
      <cac:TaxCategory>
        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
        <cac:TaxScheme>
          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="PEN">'.$total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">'.$neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">'.$total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">'.$total.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">'.$igvfinal.'</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>'.$f->nombre_plan.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';
				$doc = new DOMDocument(); 
				$doc->loadxml($datos);
				$doc->save('adjunto/comprobantes/'.$filename.'.xml');

				$xmlPath = 'adjunto/comprobantes/'.$filename.'.xml';
				$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 

				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);

				$xmlSigned = $signer->signFromFile($xmlPath);

				file_put_contents('adjunto/comprobantes/'.$filename.'.xml', $xmlSigned);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Comprobante de pago";
		        $mail->Body 	  = "Se adjunta factura de venta. <br>";
		        $mail->AltBody    = "Se adjunta factura de venta.";
		        $mail->AddAddress($email);

		       	$mail->AddAttachment("adjunto/comprobantes/".$f->mes."".$f->serie."".$f->correlativo.".pdf", $f->mes."".$f->serie."".$f->correlativo.".pdf", 'base64', 'application/pdf');
		       	$mail->AddAttachment("adjunto/comprobantes/".$filename.".xml", $filename.".xml", 'base64', 'application/xml');
		       	$mail->IsHTML(true);

		        $estadoEnvio = $mail->Send(); 
				if($estadoEnvio){
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}

			}

			unlink("adjunto/comprobantes/".$f->mes."".$f->serie."".$f->correlativo.".pdf");
			unlink("adjunto/comprobantes/".$filename.".xml");
    	}
    }

    public function crearXml(){

    	include ('./application/libraries/xmldsig/src/XMLSecurityDSig.php');
    	include ('./application/libraries/xmldsig/src/XMLSecurityKey.php');
    	include ('./application/libraries/xmldsig/src/Sunat/SignedXml.php');
    	include ('./application/libraries/CustomHeaders.php');
    	//include ('./application/libraries/phpqrcode/qrlib.php');

		$numLet = new NumeroALetras();

        $this->load->library('pdf');
		$this->pdf = new Pdf();
	    $this->pdf->AddPage();
	    $this->pdf->AliasNbPages();

    	$this->xml = new XMLWriter();
    	$mail = new PHPMailer();

    	//$datos = null;
    	$numeroSeriePost = $_POST['numSerie'];
    	$numSerie = $this->input->post('numSerie');
    	$idPlan = $this->input->post('nameCheck');
    	$canales = $this->input->post('canalesDos');
    	$fecinicio = $this->input->post('fechainicioDos');
    	//$fecfin = $this->input->post('fechafinDos');
    	//$numSerieU = reset($numSerie);
    	//$numSerieUno = reset($numeroSeriePost);
    	$serieUno = array_values($numSerie)[0];
    	//$serieInicial=substr($serieUno, 0, 1);

    	if (substr($serieUno, 0, 1) == 'B') {

    		$numSerieUno = reset($numeroSeriePost);

    		$boletasAgr = $this->comprobante_pago_mdl->getDatosXmlBoletasAgrupadas($fecinicio, $serieUno);

	    	foreach ($boletasAgr as $ba){

	    		$linea=1;

	    		$fechanombre = str_replace ("-" , "", $ba->fecha_emision);
	    		$filename="20600258894-RC-".$fechanombre."-".$ba->nume_corre_res;
	    		//agregar -'.$b->nume_corre_res.' en ID en caso no funcione el envío.
	    		$datos = '<?xml version="1.0" encoding="UTF-8"?>
<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" 
xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" 
xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<ext:UBLExtensions>
	<ext:UBLExtension>
      	<ext:ExtensionContent>

      	</ext:ExtensionContent>
	</ext:UBLExtension>
	</ext:UBLExtensions>
<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
<cbc:CustomizationID>1.1</cbc:CustomizationID>
<cbc:ID>RC-'.$fechanombre.'-'.$ba->nume_corre_res.'</cbc:ID>
<cbc:ReferenceDate>'.$ba->fecha_emision.'</cbc:ReferenceDate>
<cbc:IssueDate>'.$ba->fecha_emision.'</cbc:IssueDate>
<cac:AccountingSupplierParty>
	<cbc:CustomerAssignedAccountID>20600258894</cbc:CustomerAssignedAccountID>
	<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
	<cac:Party>
		<cac:PartyLegalEntity>
			<cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
		</cac:PartyLegalEntity>
	</cac:Party>
</cac:AccountingSupplierParty>';
			$boletas = $this->comprobante_pago_mdl->getDatosXmlBoletas($fecinicio, $ba->nume_corre_res, $numSerieUno);
			foreach ($boletas as $b) {

				if ($b->tipo_moneda=='PEN') {
					$moneda = 'PEN';
				} elseif ($b->tipo_moneda=='USD') {
					$moneda = 'USD';
				}

					$datos.='<sac:SummaryDocumentsLine>
		<cbc:LineID>'.$linea.'</cbc:LineID>
		<cbc:DocumentTypeCode>03</cbc:DocumentTypeCode>
		<cbc:ID>'.$numSerieUno.'-'.$b->correlativo.'</cbc:ID>
		<cac:Status>
			<cbc:ConditionCode>1</cbc:ConditionCode>
		</cac:Status>
		<sac:TotalAmount currencyID="'.$moneda.'">'.$b->total.'</sac:TotalAmount>
		<sac:BillingPayment>
			<cbc:PaidAmount currencyID="'.$moneda.'">'.$b->neto.'</cbc:PaidAmount>
			<cbc:InstructionID>01</cbc:InstructionID>
		</sac:BillingPayment>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID="'.$moneda.'">'.$b->igv.'</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxAmount currencyID="'.$moneda.'">'.$b->igv.'</cbc:TaxAmount>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:ID>1000</cbc:ID>
						<cbc:Name>IGV</cbc:Name>
						<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID="'.$moneda.'">0.00</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxAmount currencyID="'.$moneda.'">0.00</cbc:TaxAmount>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:ID>2000</cbc:ID>
						<cbc:Name>ISC</cbc:Name>
						<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
	</sac:SummaryDocumentsLine>';
				$linea=$linea+1;

			}
$datos.='</SummaryDocuments>';

				$nameDoc='RC-'.$fechanombre.'-'.$ba->nume_corre_res;
				$filecdr=$ba->mesanio.'-cdrboletas'.$ba->serie;
				$fileBoleta=$ba->mesanio.'-boletas'.$ba->serie;
				$carpetaCdr = 'adjunto/xml/boletas/'.$filecdr;
		    	$carpetaBoleta = 'adjunto/xml/boletas/'.$fileBoleta;
				if (!file_exists($carpetaCdr)) {
				    mkdir($carpetaCdr, 0777, true);
				}

				if (!file_exists($carpetaBoleta)) {
				    mkdir($carpetaBoleta, 0777, true);
				}

				$doc = new DOMDocument();
				$doc->loadxml($datos);
				$doc->save('adjunto/xml/boletas/'.$fileBoleta.'/'.$filename.'.xml');
				$xmlPath = 'adjunto/xml/boletas/'.$fileBoleta.'/'.$filename.'.xml';
				$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);
				$xmlSigned = $signer->signFromFile($xmlPath);
				file_put_contents($filename.'.xml', $xmlSigned);
				//echo $xmlSigned;

			    $this->load->library('zip');

			    $this->zip->add_data($filename.'.xml', $xmlSigned);
				$this->zip->archive('adjunto/xml/boletas/'.$fileBoleta.'/'.$filename.'.zip');
				$this->zip->clear_data();

				unlink($filename.".xml");
				unlink($carpetaBoleta.'/'.$filename.".xml");

				$zipXml = $filename.'.zip'; 

				$service = 'adjunto/wsdl/billService.wsdl';
				//$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
				//usuarios
				/*20600258894MODDATOS MODDATOS
				  20600258894DCACEDA2 DCACE716186*/

				$WSHeader = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
								<wsse:UsernameToken>
									<wsse:Username>20600258894DCACEDA2</wsse:Username>
									<wsse:Password>DCACE716186</wsse:Password>
								</wsse:UsernameToken>
							</wsse:Security>';

				$headers = new SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', new SoapVar($WSHeader, XSD_ANYXML));
				$client = new SoapClient($service, array(
					'cache_wsdl' => WSDL_CACHE_NONE,
					'trace' => TRUE,
					//'soap_version' => SOAP_1_2
				));

				$params = array(
					'fileName' => $zipXml, 
					'contentFile' => file_get_contents('adjunto/xml/boletas/'.$fileBoleta.'/'.$zipXml) 
				);
				
				$client->__soapCall('sendSummary', array("parameters"=>$params), null, $headers);

				$status = $client->__getLastResponse();

		    	$carpeta = 'adjunto/xml/boletas/'.$filecdr;
				if (!file_exists($carpeta)) {
				    mkdir($carpeta, 0777, true);
				}

		    	//Descargamos el Archivo Response
				$archivo = fopen('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml','w+');
				fputs($archivo, $status);
				fclose($archivo);
				//LEEMOS EL ARCHIVO XML
				$responsexml = simplexml_load_file('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml');

				$xml = file_get_contents($carpetaCdr.'/C'.$filename.'.xml');
				$DOM = new DOMDocument('1.0', 'utf-8');
				$DOM->loadXML($xml);
				$respuesta = $DOM->getElementsByTagName('ticket');
				foreach ($respuesta as $r) {
					$ticket = $r->nodeValue;
				}
					
				
			}

			sleep(5);

			foreach ($boletasAgr as $ba) {

	    		$fechanombre = str_replace ("-" , "", $ba->fecha_emision);
	    		$filename="20600258894-RC-".$fechanombre."-".$ba->nume_corre_res;

				$filecdr=$ba->mesanio.'-cdrboletas'.$ba->serie;

				$serviceCdr = 'adjunto/wsdl/billService.wsdl';

				$WSHeaderCdr = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
								<wsse:UsernameToken>
									<wsse:Username>20600258894DCACEDA2</wsse:Username>
									<wsse:Password>DCACE716186</wsse:Password>
								</wsse:UsernameToken>
							</wsse:Security>';

				$headersCdr = new SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', new SoapVar($WSHeaderCdr, XSD_ANYXML));

				$clientCdr = new SoapClient($serviceCdr, array(
					'cache_wsdl' => WSDL_CACHE_NONE,
					'trace' => TRUE,
					//'soap_version' => SOAP_1_2
				));

				$estado = array(
					'ticket' => $ticket
				);
				
				$prueba = $clientCdr->__soapCall('getStatus', array("parameters"=>$estado), null, $headersCdr);
				$estadoArray = (array)$prueba;
				$contenido = (array)$estadoArray['status'];
				$statucode = $contenido['statusCode'];

				print_r($statucode." ");

				$statusCdr = $clientCdr->__getLastResponse();

				//$comparar = (string)$statusCdr;

				/*if ($comparar = '0098' || $comparar == '0') {
					print_r($comparar."  ");
				}*/

				$this->load->library('zip');
				$this->zip->add_data('R-'.$filename.'.xml', $statusCdr);
				$this->zip->archive('adjunto/xml/boletas/'.$filecdr.'/'.'R-'.$filename.'.zip');
				$archivo2 = chmod('adjunto/xml/boletas/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);

					//unlink('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml');

				//print_r($comparar);

				if ($statucode = '0098' || $statucode == '0'){

					//print_r($statusCdr."  ");
					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($ba->fecha_emision, $ba->nume_corre_res, $ba->serie);
					
				}

					//Descargamos el Archivo Response
					/*$archivoCdr = fopen('adjunto/xml/boletas/'.$filecdr.'/'.'R-'.$filename.'.xml','w+');
					fputs($archivoCdr, $statusCdr);
					fclose($archivoCdr);*/
			}



    	} elseif (substr($numSerie, 0, 1) == 'F') {
	    		
		    	$facturas = $this->comprobante_pago_mdl->getDatosXmlFacturas($fecinicio, $numSerie);
		    	

		    	foreach ($facturas as $f){

		    		if ($f->tipo_moneda=='PEN') {
						$moneda = 'PEN';
					} elseif ($f->tipo_moneda=='USD') {
						$moneda = 'USD';
					}

		    		$filename="20600258894-01-".$f->serie."-".$f->correlativo;

					$datos = '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ccts="urn:un:unece:uncefact:documentation:2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
         xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent>
      </ext:ExtensionContent>
    </ext:UBLExtension>
  </ext:UBLExtensions>
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ProfileID schemeName="SUNAT:Identificador de Tipo de Operación" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
  <cbc:ID>'.$f->serie.'-'.$f->correlativo.'</cbc:ID>
  <cbc:IssueDate>'.$f->fecha_emision.'</cbc:IssueDate>
  <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">01</cbc:InvoiceTypeCode>
  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">'.$moneda.'</cbc:DocumentCurrencyCode>
  <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
  <cac:Signature>
    <cbc:ID>SignIMM</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
        <cbc:ID>20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
        <cbc:URI>#SignIMM</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20600258894</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>RED SALUD</cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
        <cac:RegistrationAddress>
			<cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>
  <cac:AccountingCustomerParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6"
                schemeName="SUNAT:Identificador de Documento de Identidad"
                schemeAgencyName="PE:SUNAT"
                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$f->numero_documento_cli.'</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>'.$f->razon_social_cli.'</cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingCustomerParty>
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="'.$moneda.'">'.$f->igv.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="'.$moneda.'">'.$f->neto.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="'.$moneda.'">'.$f->igv.'</cbc:TaxAmount>
      <cac:TaxCategory>
        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
        <cac:TaxScheme>
          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="'.$moneda.'">'.$f->total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="'.$moneda.'">'.$f->neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="'.$moneda.'">'.$f->total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="'.$moneda.'">'.$f->igv.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="'.$moneda.'">'.$f->neto.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="'.$moneda.'">'.$f->igv.'</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>'.$f->nombre_plan.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="'.$moneda.'">'.$f->neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';
					$nameDoc=$f->serie."-".$f->correlativo;
					$filecdr=$f->mesanio.'-cdrfacturas'.$f->serie;
					$fileFactura=$f->mesanio.'-facturas'.$f->serie;
					$carpetaCdr = 'adjunto/xml/facturas/'.$filecdr;
			    	$carpetaFactura = 'adjunto/xml/facturas/'.$fileFactura;

					if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaFactura)) {
					    mkdir($carpetaFactura, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml');

					$xmlPath = 'adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 

					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);

					$xmlSigned = $signer->signFromFile($xmlPath);

					file_put_contents($filename.'.xml', $xmlSigned);
					//echo $xmlSigned;

					 $this->load->library('zip');

				    $this->zip->add_data($filename.'.xml', $xmlSigned);
					$this->zip->archive('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.zip');
					$this->zip->clear_data();

					//unlink($filename.".xml");
					//unlink('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml');

					//$service = 'adjunto/wsdl/billService.wsdl'; 
					$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
					
			    	//$headers = new CustomHeaders('20600258894MODDATOS', 'MODDATOS');
			    	$headers = new CustomHeaders('20600258894DCACEDA2', 'DCACE716186'); 

			    	
			    	$client = new SoapClient($service, array(
			    		'cache_wsdl' => WSDL_CACHE_NONE,
			    		'trace' => TRUE,
			    		//'soap_version' => SOAP_1_2
			    	));

			    	$client->__setSoapHeaders([$headers]); 
			    	$fcs = $client->__getFunctions();

			    	$zipXml = $filename.'.zip'; 
			    	$params = array( 
			    		'fileName' => $zipXml, 
			    		'contentFile' => file_get_contents('adjunto/xml/facturas/'.$fileFactura.'/'.$zipXml) 
			    	); 

			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();
			    	
			    	$carpeta = 'adjunto/xml/facturas/'.$filecdr;
					if (!file_exists($carpeta)) {
					    mkdir($carpeta, 0777, true);
					}

			    	//Descargamos el Archivo Response
					$archivo = fopen('adjunto/xml/facturas/'.$filecdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);
					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file('adjunto/xml/facturas/'.$filecdr.'/'.'C'.$filename.'.xml');
					
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }
					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen('adjunto/xml/facturas/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);

					$archivo2 = chmod('adjunto/xml/facturas/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);
	
					unlink('adjunto/xml/facturas/'.$filecdr.'/'.'C'.$filename.'.xml');

					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($f->fecha_emision, $f->corre, $f->serie);

				}
			unlink('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml');
    	} 
					
	}

}
