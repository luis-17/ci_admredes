<?php
ini_set('max_execution_time', 6000); 
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
date_default_timezone_set('America/Lima');
defined('BASEPATH') OR exit('No direct script access allowed');
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Greenter\XMLSecLibs\Sunat\SignedXml;

class notas_cnt extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->model('menu_mdl');
        $this->load->model('comprobante_pago_mdl');
        $this->load->library('My_PHPMailer');
        $this->load->library('Numero_Letras');

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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

	      	$data['idserie'] = 0;
			$data['fechaDoc'] = date('Y-m-d');
			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$data['correlativoDoc'] = "";
			$data['certNotaCredito'] = "";
			$data['docNotaCredito'] = "";

			$html = null;
			$iddocumento = null;

			$serie = $this->comprobante_pago_mdl->getSerie();
			$data['serie'] = $serie;

			$serieDos = $this->comprobante_pago_mdl->getSerieEmitir();
			$data['serieDos'] = $serieDos;

			$serieCredito = $this->comprobante_pago_mdl->getSerieCredito();
			$data['serieCredito'] = $serieCredito;

			$planes = $this->comprobante_pago_mdl->getPlanesSelect();
			$data['planes'] = $planes;

			$canales = $this->comprobante_pago_mdl->getCanales();
			$data['canales'] = $canales;

			$canalesDev = $this->comprobante_pago_mdl->getCanales();
			$data['canalesDev'] = $canalesDev;


			$this->load->view('dsb/html/comprobante/notas_comp.php',$data);

		} else {
			redirect('/');
		}
	}

	public function mostrarListaNotaCredito()
	{

		$html = null;

		$certNotaCredito = $_POST['certNotaCredito'];
		$docNotaCredito = $_POST['docNotaCredito'];

		//print_r($certNotaCredito);
		//exit();
		if ($docNotaCredito == NULL || $docNotaCredito == '') {
			$boletaCert = $this->comprobante_pago_mdl->mostrarBoletaCert($certNotaCredito);

			$html .="<hr>";
				$html .= "<div  align='center' class='col-xs-12'>";
					$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
						$html .= "<thead>";
							$html .= "<tr>";
								$html .= "<th>Fecha de Emisión</th>";
								$html .= "<th>N° de comprobante</th>";
								$html .= "<th>Nombres y Apellidos</th>";
								$html .= "<th>DNI</th>";
								$html .= "<th>Plan</th>";
								$html .= "<th>Importe (S/.)</th>";
								$html .= "<th>Seleccionar</th>";
							$html .= "</tr>";
						$html .= "</thead>";
						$html .= "<tbody>";

					foreach ($boletaCert as $bc) {
						$importe = $bc->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $bc->idestadocobro;
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$bc->fecha_emision."</td>";
							$html .= "<td align='left'>".$bc->seriecorrelativo."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$bc->idcomprobante."'></td>";
							$html .= "<td align='left'>".utf8_decode($bc->contratante)."</td>";
							$html .= "<td align='left'>".$bc->cont_numDoc."</td>";
							$html .= "<td align='left'>".utf8_decode($bc->nombre_plan)."</td>";
							$html .= "<td align='center'>S/. ".$importe2."</td>";
							$html .= "<td align='center'>";
								$html .= "<div class='form-check'>";
								  $html .= "<input class='form-check-input' type='checkbox' name='checkComprobante[]' value='".$bc->idcomprobante."' id='checkComprobante".$bc->idcomprobante."'>";
								$html .= "</div>";
							$html .= "</td>";
						$html .= "</tr>";
					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";
		} elseif ($certNotaCredito == NULL || $certNotaCredito == '') {

			$html .="<hr>";
				$html .= "<div  align='center' class='col-xs-12'>";
					$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
						$html .= "<thead>";
							$html .= "<tr>";
								$html .= "<th>Fecha de Emisión</th>";
								$html .= "<th>N° de comprobante</th>";
								$html .= "<th>Nombres y Apellidos</th>";
								$html .= "<th>DNI</th>";
								$html .= "<th>Plan</th>";
								$html .= "<th>Importe (S/.)</th>";
								$html .= "<th>Seleccionar</th>";
							$html .= "</tr>";
						$html .= "</thead>";
						$html .= "<tbody>";
					if (strlen($docNotaCredito)==8) {

						$boletaDni = $this->comprobante_pago_mdl->mostrarBoletaDni($docNotaCredito);

						foreach ($boletaDni as $bd) {
							$importe = $bd->importe_total;
							$importe2=number_format((float)$importe, 2, '.', ',');

							$estado = $bd->idestadocobro;
							
							$html .= "<tr>";
								$html .= "<td align='left'>".$bd->fecha_emision."</td>";
								$html .= "<td align='left'>".$bd->seriecorrelativo."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$bd->idcomprobante."'></td>";
								$html .= "<td align='left'>".utf8_decode($bd->contratante)."</td>";
								$html .= "<td align='left'>".$bd->cont_numDoc."</td>";
								$html .= "<td align='left'>".utf8_decode($bd->nombre_plan)."</td>";
								$html .= "<td align='center'>S/. ".$importe2."</td>";
								$html .= "<td align='center'>";
									$html .= "<div class='form-check'>";
									  $html .= "<input class='form-check-input' type='checkbox' name='checkComprobante[]' value='".$bd->idcomprobante."' id='checkComprobante".$bd->idcomprobante."'>";
									$html .= "</div>";
								$html .= "</td>";
							$html .= "</tr>";
						}

					} elseif (strlen($docNotaCredito)==11) {

						$boletaRuc = $this->comprobante_pago_mdl->mostrarBoletaRuc($docNotaCredito);

						foreach ($boletaRuc as $br) {
							$importe = $br->importe_total;
							$importe2=number_format((float)$importe, 2, '.', ',');

							$estado = $br->idestadocobro;
							
							$html .= "<tr>";
								$html .= "<td align='left'>".$br->fecha_emision."</td>";
								$html .= "<td align='left'>".$br->seriecorrelativo."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$br->idcomprobante."'></td>";
								$html .= "<td align='left'>".utf8_decode($br->contratante)."</td>";
								$html .= "<td align='left'>".$br->cont_numDoc."</td>";
								$html .= "<td align='left'>".utf8_decode($br->nombre_plan)."</td>";
								$html .= "<td align='center'>S/. ".$importe2."</td>";
								$html .= "<td align='center'>";
									$html .= "<div class='form-check'>";
									  $html .= "<input class='form-check-input' type='checkbox' name='checkComprobante[]' value='".$br->idcomprobante."' id='checkComprobante".$br->idcomprobante."'>";
									$html .= "</div>";
								$html .= "</td>";
							$html .= "</tr>";
						}

					}		

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";
		}
		

		echo json_encode($html);

	}

	public function mostrarCorrelativoMasivo(){

		$html=null;
		$serie='B006';

		$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($serie);

		foreach ($correlativo as $c):

			$html.="<input class='form-control' name='correGen' type='text' value='".$c->correlativo."' id='correGen'>";

		endforeach;			

		echo json_encode($html);
	}

	public function mostrarListaNotaCreditoMasiva(){
		$html = null;

		$canales=$_POST['canales'];
		$inicio = $_POST['fechainicioMas'];
		$fin = $_POST['fechafinMas'];
		$month = date('m');
      	$year = date('Y');
      	$day = date('d');

		$fechaAct= date('Y-m-d');

		$certCuotas = $this->comprobante_pago_mdl->mostrarCertCuotas($inicio, $fin);

		$html .="<hr>";
		$html .= "<div  align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Fecha de devolución</th>";
						$html .= "<th>N° de certificado</th>";
						$html .= "<th>Nombres y Apellidos</th>";
						$html .= "<th>DNI</th>";
						$html .= "<th>Plan</th>";
						$html .= "<th>Importe (S/.)</th>";
						$html .= "<th>Fecha de cobro</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";

				foreach ($certCuotas as $cc) {

					$certificado = $cc->cert_num;
					$importeDev = $cc->importe;
					$importeDev2 = $importeDev*100;
					$fechaIn = $cc->fecha_inicio;
					$fechaFi = $cc->fecha_fin;

					$cobros = $this->comprobante_pago_mdl->mostrarCobroDevolucionCert($certificado, $canales, $cc->cuotas, $inicio, $fin, $fechaIn, $fechaFi);

					foreach ($cobros as $cb) {

						$importe = $cb->importe;
						$importe = $importe;
						$importe2=number_format((float)$importe, 2, '.', ',');
						$newDate = date("d/m/Y", strtotime($cb->fecha_dev));
						$newDate2 = date("d/m/Y", strtotime($cb->cob_fechCob));

						$html .= "<tr>";
							$html .= "<td align='left'>".$newDate."<input type='text' class='hidden' id='fechEmiNota' name='fechEmiNota[]' value='".$cb->fecha_dev."'></td>";
							$html .= "<td align='left'>".$cb->cert_num."</td>";
							$html .= "<td align='left'>".utf8_decode($cb->contratante)."<input type='text' class='hidden' id='contratante' name='contratante[]' value=''></td>";
							$html .= "<td align='left'>".$cb->cont_num."<input type='text' class='hidden' id='cobro' name='cobro[]' value=''></td>";
							$html .= "<td align='left'>".utf8_decode($cb->nombre_plan)."<input type='text' class='hidden' id='idplan' name='idplan[]' value=''></td>";
							$html .= "<td align='center'>S/. ".$importe2."<input type='text' class='hidden' id='impNota' name='impNota[]' value='".$importe2."'></td>";
							$html .= "<td align='left'>".$newDate2."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='B00".$cb->idclienteempresa."'></td>";
						$html .= "</tr>";

					}

				}

				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		$html .= "<hr>";
		$html .= "<div class='form-row'>";
		  	$html .= "<div class='form-group col-md-4'>";
		    	$html .= "<b class='text-primary'>Serie de Referencia</b>";
		    	$html .= "<input type='text' class='form-control' name='numSerieRefMasivo' id='numSerieRefMasivo' value=''>";
			$html .= "</div>";

			$html .= "<div class='form-group col-md-4'>";
		    	$html .= "<b class='text-primary'>Correlativo de Referencia</b>";
		   		$html .= "<input type='number' class='form-control' name='CorreRefMasivo' id='CorreRefMasivo' required='Ingrese importe de la nota de crédito' value=''>";
			$html .= "</div>";

			$html .= "<div class='form-group col-md-4'>";
		    	$html .= "<b class='text-primary'>Fecha Comprobante de Referencia</b>";
		   		$html .= "<input type='date' class='form-control' name='fechEmiRefMasivo' id='fechEmiRefMasivo' required='Ingrese importe de la nota de crédito' value='".$fechaAct."'>";
			$html .= "</div>";
		$html .= "</div>";
		$html .= "<div class='form-row'>";
		  	$html .= "<div class='form-group col-md-12'>";
		    	$html .= "<b class='text-primary'>Motivo de Nota de Crédito</b>";
		   		$html .= "<textarea type='textarea' class='form-control' name='motivoMasivo' id='motivoManual' value='' required='Ingrese el motivo de la nota de crédito'></textarea>";
			$html .= "</div>";							
		$html .= "</div>";


		echo json_encode($html);

	}

	public function guardarNotaCreditoMasiva(){
		
		//$fechEmiNota = $_POST['fechEmiNota'];
		//$impTotal = $_POST['impNota'];
		$idTipoDoc = 5;
		$canales = $_POST['canales'];
		$numSerie = $_POST['numSerie'];
		$correGen = $_POST['correGen'];
		$inicio = $_POST['fechainicioMas'];
		$fin = $_POST['fechafinMas'];
		//$serieRef = 'BC01';
		$correRef = 1;
		//$fechaRef = '2018-01-31';
		$sustento = 'reporte';

		$certCuotas = $this->comprobante_pago_mdl->mostrarCertCuotas($inicio, $fin);

		foreach ($certCuotas as $cc) { 
	
			$certificado = $cc->cert_num;
			$cuotas = $cc->cuotas;
			$dni = $cc->cont_num;
			$importeDev = $cc->importe;
			$dev_id = $cc->dev_id;
			$fechaIn = $cc->fecha_inicio;
			$fechaFi = $cc->fecha_fin;
			$importeDev2 = $importeDev*100;

			$cobros = $this->comprobante_pago_mdl->mostrarCobroDevolucionCert($certificado, $canales, $cuotas, $inicio, $fin, $fechaIn, $fechaFi);
			$contar = count($cobros);
			$validar = $cuotas - $contar;

			foreach ((array)$cobros as $cb) {

				if ($contar == 0) {
					$this->comprobante_pago_mdl->updateEstadoDevId($dev_id);
				} else {
					$import = $cb->importe;
					$this->comprobante_pago_mdl->insertDatosBoletasNotas($cb->fecha_dev, 'BC01', $correGen, $cb->cont_id, $idTipoDoc, $import, $cb->idplan, $cd->serie, $cd->correlativo, $sustento, $fechaIn, 2);

					$dev_id_cobro = $cb->dev_id;
					$this->comprobante_pago_mdl->updateEstadoDevCobro($cb->cob_id);

					if ($validar == 0) {
						$correGen = $correGen+1;
						$this->comprobante_pago_mdl->updateEstadoDevCero($validar, $dev_id);
					} else {
						$this->comprobante_pago_mdl->updateEstadoDev($validar, $dev_id);
					}
				}
	
			}
		}
		$this->comprobante_pago_mdl->updateEstadoDevUno($inicio, $fin);

	}

	public function devolucionesSinCobros(){
		$html = null;

		$canales=$_POST['canales'];
		$inicio = $_POST['fechainicioDev'];
		$fin = $_POST['fechafinDev'];

		$html .="<hr>";
		$html .= "<div  align='center' class='col-xs-12'>";
			$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th>Fecha de devolución</th>";
						$html .= "<th>N° de certificado</th>";
						$html .= "<th>Nombres y Apellidos</th>";
						$html .= "<th>DNI</th>";
						$html .= "<th>Plan</th>";
						$html .= "<th>Importe (S/.)</th>";
						$html .= "<th>Cuotas</th>";
						$html .= "<th>Estado</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				$html .= "<tbody>";

				if ($canales == 0) {
					$dev = $this->comprobante_pago_mdl->getDevolucionesSinCobroDos($inicio, $fin);

					foreach ($dev as $d) {

						$importe = $d->importe;
						$importe2=number_format((float)$importe, 2, '.', ',');
						$newDate = date("d/m/Y", strtotime($d->fecha_dev));
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$newDate."<input type='text' class='hidden' id='fechEmiNota' name='fechEmiNota[]' value='".$d->fecha_dev."'></td>";

							$html .= "<td align='left'>".$d->cert_num."<input type='text' class='hidden' id='cert_num' name='cert_num[]' value='".$d->cert_num."'></td>";

							$html .= "<td align='left'>".utf8_decode($d->contratante)."<input type='text' class='hidden' id='contratante' name='contratante[]' value='".utf8_decode($d->contratante)."'></td>";

							$html .= "<td align='left'>".$d->cont_num."<input type='text' class='hidden' id='dni' name='dni[]' value='".$d->cont_num."'></td>";

							$html .= "<td align='left'>".utf8_decode($d->nombre_plan)."<input type='text' class='hidden' id='plan' name='plan[]' value='".utf8_decode($d->nombre_plan)."'></td>";

							$html .= "<td align='center'>S/. ".$importe2."<input type='text' class='hidden' id='impDev' name='impDev[]' value='".$importe2."'></td>";

							$html .= "<td align='left'>".$d->cuotas."<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->cuotas."'></td>";
							if ($d->estado = 3) {
								$html .= "<td align='left'>Sin Cobro<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->estado."'></td>";
							} elseif ($d->estado = 4) {
								$html .= "<td align='left'>Certificado no registrado<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->estado."'></td>";
							}
								
						$html .= "</tr>";
					}

				} else {
					$dev = $this->comprobante_pago_mdl->getDevolucionesSinCobro($canales, $inicio, $fin);

					foreach ($dev as $d) {

						$importe = $d->importe;
						$importe2=number_format((float)$importe, 2, '.', ',');
						$newDate = date("d/m/Y", strtotime($d->fecha_dev));
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$newDate."<input type='text' class='hidden' id='fechEmiNota' name='fechEmiNota[]' value='".$d->fecha_dev."'></td>";

							$html .= "<td align='left'>".$d->cert_num."<input type='text' class='hidden' id='cert_num' name='cert_num[]' value='".$d->cert_num."'></td>";

							$html .= "<td align='left'>".utf8_decode($d->contratante)."<input type='text' class='hidden' id='contratante' name='contratante[]' value='".utf8_decode($d->contratante)."'></td>";

							$html .= "<td align='left'>".$d->cont_num."<input type='text' class='hidden' id='dni' name='dni[]' value='".$d->cont_num."'></td>";

							$html .= "<td align='left'>".utf8_decode($d->nombre_plan)."<input type='text' class='hidden' id='plan' name='plan[]' value='".utf8_decode($d->nombre_plan)."'></td>";

							$html .= "<td align='center'>S/. ".$importe2."<input type='text' class='hidden' id='impDev' name='impDev[]' value='".$importe2."'></td>";

							$html .= "<td align='left'>".$d->cuotas."<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->cuotas."'></td>";
							if ($d->estado = 3) {
								$html .= "<td align='left'>Sin Cobro<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->estado."'></td>";
							} elseif ($d->estado = 4) {
								$html .= "<td align='left'>Certificado no registrado<input type='text' class='hidden' id='cuotas' name='cuotas[]' value='".$d->estado."'></td>";
							}
								
						$html .= "</tr>";
					}
				}

				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		echo json_encode($html);
	}

	public function generarExcelDevSinCobros(){

		$inicio = $_POST['fechainicioDev'];
		$fin = $_POST['fechafinDev'];

		$canales = $_POST['canales'];

		//Se carga la librería de excel
		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Hoja 1');

		$estiloBorde = array(
		   'borders' => array(
		     'allborders' => array(
		       'style' => PHPExcel_Style_Border::BORDER_THIN
		     )
		   )
		 );

		$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloBorde);
		unset($styleArray); 

		$estiloCentrar = array( 
		 	'alignment' => array( 
		  		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
		  		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
		  	) 
		);

		$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloCentrar);
		$this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setWrapText(true);

		$estiloColor = array(
	    	'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		        'color' => array('rgb' => 'F4A900')
	    	)
	    );

		$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloColor);

		$this->excel->getActiveSheet()->getRowDimension('A1:I1')->setRowHeight(20);

		//Definimos cabecera de la tabla excel
		$this->excel->getActiveSheet()->setCellValue("A1", "FECHA DE DEVOLUCIÓN")->getColumnDimension('A')->setWidth(15);
		$this->excel->getActiveSheet()->setCellValue("B1", "CERTIFICADO")->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->setCellValue("C1", "CONTRATANTE")->getColumnDimension('C')->setWidth(50);
		$this->excel->getActiveSheet()->setCellValue("D1", "DOCUMENTO DE IDENTIDAD")->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->setCellValue("E1", "NOMBRE DE PLAN")->getColumnDimension('E')->setWidth(30);
		$this->excel->getActiveSheet()->setCellValue("F1", "IMPORTE")->getColumnDimension('F')->setWidth(15);
		$this->excel->getActiveSheet()->setCellValue("G1", "CUOTAS")->getColumnDimension('G')->setWidth(10);
		$this->excel->getActiveSheet()->setCellValue("H1", "ESTADO")->getColumnDimension('H')->setWidth(40);
		$this->excel->getActiveSheet()->setCellValue("I1", "TOTAL")->getColumnDimension('I')->setWidth(15);

		$contador = 2;

		if ($canales == 0) {

			$dev = $this->comprobante_pago_mdl->getDevolucionesSinCobroDos($canales, $inicio, $fin);

			foreach ($dev as $d) {

				$importe = $d->importe;
				$importe2=number_format((float)$importe, 2, '.', ',');
				$newDate = date("d/m/Y", strtotime($d->fecha_dev));
				$total = $importe * $d->cuotas;

				//Informacion de las filas de la consulta.
				$this->excel->getActiveSheet()->setCellValue("A{$contador}",$newDate);
				$this->excel->getActiveSheet()->setCellValue("B{$contador}",$d->cert_num);
				$this->excel->getActiveSheet()->setCellValue("C{$contador}","No se encontró en el sistema");
				$this->excel->getActiveSheet()->setCellValue("D{$contador}",$d->cont_num);
				$this->excel->getActiveSheet()->setCellValue("E{$contador}","No se encontró en el sistema");
				$this->excel->getActiveSheet()->setCellValue("F{$contador}",$importe2);
				$this->excel->getActiveSheet()->setCellValue("G{$contador}",$d->cuotas);
				if ($d->estado = 3) {
					$this->excel->getActiveSheet()->setCellValue("H{$contador}",utf8_decode("Sin Cobro"));
				} elseif ($d->estado = 4) {
					$this->excel->getActiveSheet()->setCellValue("H{$contador}",utf8_decode("Certificado no registrado"));
				}
				$this->excel->getActiveSheet()->setCellValue("I{$contador}",$total);

				$contador = $contador + 1;
			}	

		} else {

			$dev = $this->comprobante_pago_mdl->getDevolucionesSinCobro($canales, $inicio, $fin);

			foreach ($dev as $d) {

				$importe = $d->importe;
				$importe2=number_format((float)$importe, 2, '.', ',');
				$newDate = date("d/m/Y", strtotime($d->fecha_dev));
				$total = $importe * $d->cuotas;

				//Informacion de las filas de la consulta.
				$this->excel->getActiveSheet()->setCellValue("A{$contador}",$newDate);
				$this->excel->getActiveSheet()->setCellValue("B{$contador}",$d->cert_num);
				$this->excel->getActiveSheet()->setCellValue("C{$contador}",utf8_decode($d->contratante));
				$this->excel->getActiveSheet()->setCellValue("D{$contador}",$d->cont_num);
				$this->excel->getActiveSheet()->setCellValue("E{$contador}",utf8_decode($d->nombre_plan));
				$this->excel->getActiveSheet()->setCellValue("F{$contador}",$importe2);
				$this->excel->getActiveSheet()->setCellValue("G{$contador}",$d->cuotas);
				if ($d->estado = 3) {
					$this->excel->getActiveSheet()->setCellValue("H{$contador}",utf8_decode("Sin Cobro"));
				} elseif ($d->estado = 4) {
					$this->excel->getActiveSheet()->setCellValue("H{$contador}",utf8_decode("Certificado no registrado"));
				}
				$this->excel->getActiveSheet()->setCellValue("I{$contador}",$total);

				$contador = $contador + 1;
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

	public function mostrarDocumentoNotaCredito(){

		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$fecinicio=$data['fecinicio'];
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
		$fecfin=$data['fecfin'];
		$certNotaCredito = $_POST['certNotaCredito'];
		$docNotaCredito = $_POST['docNotaCredito'];

		if ($docNotaCredito == NULL || $docNotaCredito == '') {

			$boletasCert = $this->comprobante_pago_mdl->mostrarBoletaCert($certNotaCredito);

			foreach ((array)$boletasCert as $bc) {
				$data['contratante'] = $bc->contratante;
				$data['cont_numDoc'] = $bc->cont_numDoc;
				$data['id_contratante'] = $bc->id_contratante;
				$data['idplan'] = $bc->idplan;
				$data['serie'] = 'BC01';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
				}
			}

		} elseif ($certNotaCredito == NULL || $certNotaCredito == '') {

			if (strlen($docNotaCredito)==8) {

				$boletasDni = $this->comprobante_pago_mdl->mostrarBoletaDni($docNotaCredito);

				foreach ((array)$boletasDni as $bd) {
					$data['contratante'] = $bd->contratante;
					$data['cont_numDoc'] = $bd->cont_numDoc;
					$data['id_contratante'] = $bd->id_contratante;
					$data['idplan'] = $bd->idplan;
					$data['serie'] = 'BC01';

					$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
					foreach ($correlativo as $c) {
						$data['correlativo'] = $c->correlativo;
					}
				}

			} elseif (strlen($docNotaCredito)==11) {

				$boletasRuc = $this->comprobante_pago_mdl->mostrarBoletaRuc($docNotaCredito);

				foreach ((array)$boletasRuc as $br) {
					$data['contratante'] = $br->contratante;
					$data['cont_numDoc'] = $br->cont_numDoc;
					$data['id_contratante'] = $br->id_contratante;
					$data['idplan'] = $br->idplan;
					$data['serie'] = 'FC01';

					$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
					foreach ($correlativo as $c) {
						$data['correlativo'] = $c->correlativo;
					}
				}

			}

		}

		echo json_encode($data);
	}

	public function guardarDocumentoNotaCredito()
	{
		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
		$fechaEmiNota = $_POST['fechEmiNota'];
		$impTotal = $_POST['impNota'];
		$motivo = $_POST['motivo'];
		$idTipoDoc = 5;

		$numSerie = $_POST['numSerie'];
		$correGen = $_POST['correGen'];
		$idcomprobante = $_POST['checkComprobante'];
		//print_r($idcomprobante);
		//exit();
		

		$idcomprobanteMayor = max((array) $idcomprobante);
		$boletaMayor = $this->comprobante_pago_mdl->mostrarBoletaIdComprobante($idcomprobanteMayor);

		foreach ($boletaMayor as $bm) {

			for ($i=0; $i < count($idcomprobante); $i++) { 
			$boletas = $this->comprobante_pago_mdl->mostrarBoletaIdComprobante($idcomprobante[$i]);

				foreach ((array)$boletas as $b) {
					$seriecorre[]=$b->seriefecha;
					$fechaEmi[] = $b->fecha_emision;
				}
			}

			$serieCadena = implode(" , ", $seriecorre);
			$sustento = $motivo.": ".$serieCadena;
			//print_r($sustento);
			//exit();

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmiNota, $numSerie, $correGen, $bm->id_contratante, $idTipoDoc, $impTotal, $bm->idplan, $bm->serie, $bm->correlativo, $sustento, $bm->fecha_emision, 2);

			$this->comprobante_pago_mdl->iniVig_cert($bm->cob_iniCobertura, $bm->cert_id);
			$this->comprobante_pago_mdl->iniVig_cert_aseg($bm->cob_iniCobertura, $bm->cert_id);

		}
	}

	public function mostrarCorrelativo(){

		$serie=$_POST['serie'];
		//print_r($serie);
		//exit();
		$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($serie);

		foreach ($correlativo as $c):

			$data['correlativo'] = $c->correlativo;

		endforeach;			

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

	public function guardarDocumentoNotaCreditoManual()
	{
		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
		$numSerieRefManual = $_POST['numSerieRefManual'];
		$CorreRefManual = $_POST['CorreRefManual'];
		$fechEmiRefManual = $_POST['fechEmiRefManual'];
		$nomClienteManual = $_POST['nomClienteManual'];
		$idTipoDoc = 5;
		$impTotalManual = $_POST['impTotalManual'];
		$dniRucManual = $_POST['dniRucManual'];
		$serieCredito = $_POST['serieCredito'];
		$correGenManual = $_POST['correGenManual'];
		$impNotaManual = $_POST['impNotaManual'];
		$fechEmiNotaManual = $_POST['fechEmiNotaManual'];
		$motivoManual = $_POST['motivoManual'];
		$idplan = $_POST['idplanManual'];
		//print_r($serieCredito);
		//exit();

		$correlativoRC = $this->comprobante_pago_mdl->getUltimoCorrelativoMasUnoRes($fechaEmi);
		$correlativoRCArray = json_decode(json_encode($correlativoRC), true);
		$correlativoRCstring = array_values($correlativoRCArray)[0];
		$correRC = $correlativoRCstring['nume_corre_res'];

		$this->comprobante_pago_mdl->insertDatosBoletasNotasManuales($fechEmiNotaManual, $serieCredito, $correGenManual, $idTipoDoc, $impNotaManual, $idplan, $numSerieRefManual, $CorreRefManual, $motivoManual, $fechEmiRefManual, $nomClienteManual, $dniRucManual, $impTotalManual);
	}

	public function mostrarDocumentoNotaDebito()
	{
		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$fecinicio=$data['fecinicio'];
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
		$fecfin=$data['fecfin'];

		$fecha_emision = $_POST['fechaDoc'];
		$correlativo = $_POST['correlativoDoc'];
		$serie = $_POST['serie'];

		if (substr($serie, 0, 1) == 'B') {

			$boletas = $this->comprobante_pago_mdl->getDatosBoletaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$boletas as $b) {
				$data['seriecorrelativo'] = $b->seriecorrelativo;
				$data['importe_total'] = $b->importe_total;
				$data['tipo_moneda'] = $b->tipo_moneda;
				$data['nombre_comercial_cli'] = $b->contratante;
				$data['numero_documento_cli'] = $b->cont_numDoc;
				$data['id_contratante'] = $b->id_contratante;
				$data['idplan'] = $b->idplan;
				$data['serie'] = 'BD01';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
				}
			}

		} elseif (substr($serie, 0, 1) == 'F') {

			$facturas = $this->comprobante_pago_mdl->getDatosFacturaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$facturas as $f) {
				$data['seriecorrelativo'] = $f->seriecorrelativo;
				$data['importe_total'] = $f->importe_total;
				$data['tipo_moneda'] = $f->tipo_moneda;
				$data['nombre_comercial_cli'] = $f->nombre_comercial_cli;
				$data['numero_documento_cli'] = $f->numero_documento_cli;
				$data['id_contratante'] = $f->id_cliente_empresa;
				$data['idplan'] = $f->idplan;
				$data['serie'] = 'FD01';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
				}
			}

		}

		echo json_encode($data);
	}

	public function guardarDocumentoNotaDebito()
	{
		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
		$fechaEmi = $_POST['fechEmiNotaD'];
		$numSerie = $_POST['numSeriesD'];
		$correGen = $_POST['numCorreD'];
		$impTotal = $_POST['impNotaD'];
		$serie = $_POST['serie'];
		$correlativoDoc = $_POST['correlativoDoc'];
		$motivo = $_POST['motivoD'];
		$idTipoDoc = 6;
		$fechaDoc = $_POST['fechaDoc'];

		if (substr($serie, 0, 1) == 'B') {
			
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo, $fechaDoc);

		} elseif (substr($serie, 0, 1) == 'F') {
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosFacturasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo, $fechaDoc);
		}
	}

	public function mostrarDocumentoGenerado(){
		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');

		$fecemi = $data['fechaEmision'];

		$canales=$_POST['canales'];
		//$datos['canales'] = $canales;

		$inicio = $_POST['fechainicio'];
		//$fin = $_POST['fechafin'];

		if ($canales == 'BC01') {

			$html .="<hr>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .= "<tr>";
							$html .= "<th>Fecha de Emisión</th>";
							$html .= "<th>N° de comprobante</th>";
							$html .= "<th>Nombres y Apellidos</th>";
							$html .= "<th>DNI</th>";
							$html .= "<th>Plan</th>";
							$html .= "<th>N° de comprobante afectado</th>";
							$html .= "<th>Importe (S/.)</th>";
							$html .= "<th>Sustento</th>";
							$html .= "<th>Estado</th>";
							$html .= "<th>Opciones</th>";
						$html .= "</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					$boletas = $this->comprobante_pago_mdl->getDatosNotasBoletasMasivas($canales, $inicio);

					foreach ((array)$boletas as $b) {

						$importe = $b->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $b->idestadocobro;

						$html .= "<tr>";
							$html .= "<td align='left'>".$b->fecha_emision."</td>";
							$html .= "<td align='left'>".$b->seriecorrelativo."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$b->idcomprobante."'></td>";
							$html .= "<td align='left'>".utf8_decode($b->contratante)."</td>";
							$html .= "<td align='left'>".$b->cont_numDoc."</td>";
							$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
							$html .= "<td align='left'>".$b->seriecorrelativoDoc."</td>";
							$html .= "<td align='center'>S/. ".$importe2."</td>";
							$html .= "<td align='left'>	".$b->sustento_nota."</td>";

							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>Generado</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/notas_cnt/generarPdfNota/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							} else {
								$html .= "<td align='center' class='success'>Emitido</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/notas_cnt/generarPdfNota/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							}
								
						$html .= "</tr>";

					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		} elseif (substr($canales, 0, 1) == 'F') {

			$html .="<hr>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha de Emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Razón social</th>";
							$html .="<th>RUC</th>";
							$html .="<th>Plan</th>";
							$html .="<th>N° de comprobante afectado</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Sustento</th>";
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					$facturas = $this->comprobante_pago_mdl->getDatosNotasFacturas($canales, $inicio);

					foreach ((array)$facturas as $f) {

						$importe = $f->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $f->idestadocobro;
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$f->fecha_emision."</td>";
							$html .= "<td align='left'>".$f->seriecorrelativo."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante' value='".$f->idcomprobante."'></td>";
							$html .= "<td align='left'>".utf8_decode($f->nombre_comercial_cli)."</td>";
							$html .= "<td align='left'>".$f->numero_documento_cli."</td>";
							$html .= "<td align='left'>".utf8_decode($f->nombre_plan)."</td>";
							$html .= "<td align='left'>".$f->seriecorrelativoDoc."</td>";
							$html .= "<td align='center'>S/. ".$importe2."</td>";
							$html .= "<td align='left'>".$f->sustento_nota."</td>";
							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>Generado</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/notas_cnt/generarPdfNota/".$f->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							} else {
								$html .= "<td align='center' class='success'>Emitido</td>";
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."index.php/notas_cnt/generarPdfNota/".$f->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
							}
						$html .= "</tr>";

					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		}
		echo json_encode($html);
	}

	public function generarExcel(){
		$mail = new PHPMailer();

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');

		$fecemi = $data['fechaEmision'];

		//$canales = $_POST['canales'];
		$canales = 'BC01';
		$canalFac = 'FC01';
		$fechainicio = $_POST['fechainicio'];
		//$fechafin = $_POST['fechafin'];

		//$numeroSerie = $_POST['numeroSerie'];	
		$correlativoConcar = $_POST['concar'];
		//print_r($canales);
		//exit();
			
		$boletas = $this->comprobante_pago_mdl->getDatosExcelBoletasNota($fechainicio, $canales);
		$facturas = $this->comprobante_pago_mdl->getDatosExcelFacturasNota($fechainicio, $canalFac);

		//if ($canales == "BC01" || $canales == "FC01") {
			$tipo = "NA";
			$nomArchivo = "NOTACREDITO".$canales;
		/*} elseif ($canales == "BD01" || $canales == "FD01") {
			$tipo = "ND";
			$nomArchivo = "NOTADEBITO".$canales;
		}*/

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

			if (substr($canales, 0, 1) == 'B') {

				//Definimos la data del cuerpo.        
		        foreach($boletas as $b){

		        	$plan=$b->idplan;

					$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($plan);					

					foreach($centroCosto as $c){
						$centCosto = $c->centro_costo;

						$formatoFecha = date("d/m/Y", strtotime($b->fecha_emision));
						//$correlativoConcar = $correlativoConcar+1;
						$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);

						$igv = $b->total-$b->neto;
						$tot=$b->total;
						$nt=$b->neto;
						$total = number_format((float)$tot, 2, '.', '');
						$neto = number_format((float)$nt, 2, '.', '');
						$igvfinal=number_format((float)$igv, 2, '.', '');

						//Informacion de las filas de la consulta.
						$this->excel->getActiveSheet()->setCellValue("B{$contador1}","05");
						$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$b->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
						$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$b->cont_numDoc);
						$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$centCosto);
						$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
						$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
						$this->excel->getActiveSheet()->setCellValue("R{$contador1}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$b->serie."-".$b->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador1}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador1}",$b->serie_doc."-".$b->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador1}",$b->fecha_doc);
						$this->excel->getActiveSheet()->setCellValue("AD{$contador1}","35.59");
						$this->excel->getActiveSheet()->setCellValue("AE{$contador1}","6.41");

						$this->excel->getActiveSheet()->setCellValue("B{$contador2}","05");
						$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$b->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
						$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$b->cont_numDoc);
						$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$centCosto);
						$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$igvfinal);
						$this->excel->getActiveSheet()->setCellValue("R{$contador2}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$b->serie."-".$b->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador2}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador2}",$b->serie_doc."-".$b->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador2}",$b->fecha_doc);
						$this->excel->getActiveSheet()->setCellValue("AD{$contador2}","35.59");
						$this->excel->getActiveSheet()->setCellValue("AE{$contador2}","6.41");

						$this->excel->getActiveSheet()->setCellValue("B{$contador3}","05");
						$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$b->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
						$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$b->cont_numDoc);
						$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$centCosto);
						$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$neto);
						$this->excel->getActiveSheet()->setCellValue("R{$contador3}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$b->serie."-".$b->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$b->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador3}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador3}",$b->serie_doc."-".$b->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador3}",$b->fecha_doc);
						$this->excel->getActiveSheet()->setCellValue("AD{$contador3}","35.59");
						$this->excel->getActiveSheet()->setCellValue("AE{$contador3}","6.41");

			            //Incrementamos filas, para ir a la siguiente.
			            $contador1=$contador1+3;
			            $contador2=$contador2+3;
			            $contador3=$contador3+3;

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
				file_put_contents('adjunto/dbf/'.$nomArchivo.'.xls', $xlsData);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo Excel con serie ".$canales.". <br>";
		        $mail->AltBody    = "Se adjunta archivo Excel con serie ".$canales.".";
		        $mail->AddAddress('dcayo@red-salud.com', 'RED SALUD');

		       	$mail->AddAttachment("adjunto/dbf/".$nomArchivo.".xls", $nomArchivo.".xls");
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

		    } elseif (substr($canales, 0, 1) == 'F') {

		    	//Definimos la data del cuerpo.
		        foreach($facturas as $f){

		        	$plan=$f->idplan;

					$centroCosto = $this->comprobante_pago_mdl->getCentroCosto($plan);

					foreach($centroCosto as $c){

			        	$formatoFecha = date("d/m/Y", strtotime($f->fecha_emision));
			        	$correlativoConcar = $correlativoConcar+1;
			        	$correConcar = str_pad($correlativoConcar, 4, "0", STR_PAD_LEFT);

			        	$igv = $f->total-$f->neto;
			    		$tot=$f->total;
			    		$nt=$f->neto;
			    		$total = number_format((float)$tot, 2, '.', '');
			    		$neto = number_format((float)$nt, 2, '.', '');
			    		$igvfinal=number_format((float)$igv, 2, '.', '');

			          	//Informacion de las filas de la consulta.
						$this->excel->getActiveSheet()->setCellValue("B{$contador1}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
						$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$f->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
						$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$total);
						$this->excel->getActiveSheet()->setCellValue("R{$contador1}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador1}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador1}",$f->serie_doc."-".$f->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador1}",$f->fecha_doc);

						$this->excel->getActiveSheet()->setCellValue("B{$contador2}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
						$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$f->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$igvfinal);
						$this->excel->getActiveSheet()->setCellValue("R{$contador2}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador2}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador2}",$f->serie_doc."-".$f->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador2}",$f->fecha_doc);

						$this->excel->getActiveSheet()->setCellValue("B{$contador3}","04");
						$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$f->mes."".$correConcar);
						$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
						$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
						$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
						$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
						$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$f->numero_documento_cli);
						$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$c->centro_costo);
						$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
						$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$neto);
						$this->excel->getActiveSheet()->setCellValue("R{$contador3}","NA");
						$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$f->serie."-".$f->correlativo);
						$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
						$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$f->nombre_plan);
						$this->excel->getActiveSheet()->setCellValue("Z{$contador3}",$tipo);
						$this->excel->getActiveSheet()->setCellValue("AA{$contador3}",$f->serie_doc."-".$f->correlativo_doc);
						$this->excel->getActiveSheet()->setCellValue("AB{$contador3}",$f->fecha_doc);

			            //Incrementamos filas, para ir a la siguiente.
			            $contador1=$contador1+3;
			            $contador2=$contador2+3;
			            $contador3=$contador3+3;
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

				file_put_contents('adjunto/dbf/'.$nomArchivo.'.xls', $xlsData);

				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Archivo CONCAR";
		        $mail->Body 	  = "Se adjunta archivo Excel con serie ".$canales.". <br>";
		        $mail->AltBody    = "Se adjunta archivo Excel con serie ".$canales.".";
		        $mail->AddAddress('dcayo@red-salud.com', 'RED SALUD');

		       	$mail->AddAttachment("adjunto/dbf/".$nomArchivo.".xls", $nomArchivo.".xls");
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

	public function generarPdfNota($idcomprobante, $canales){

		include ('./application/libraries/phpqrcode/qrlib.php');
		//$canales=$_POST['canales'];
		//$idcomprobante=$_POST['idcomprobante'];
		$numLet = new NumeroALetras();

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
			
		$boletas = $this->comprobante_pago_mdl->getDatosPdfBoletasNota($idcomprobante);
		$facturas = $this->comprobante_pago_mdl->getDatosPdfFacturasNota($idcomprobante);
		$facturasDebito = $this->comprobante_pago_mdl->getDatosPdfFacturasNotaDebito($idcomprobante);

		//Carga la librería que agregamos
        $this->load->library('pdf');

        $this->pdf = new Pdf();
	    $this->pdf->AddPage();
	    $this->pdf->AliasNbPages();
	    //$this->pdf->Image(base_url().'/public/assets/avatars/user.jpg','0','0','150','150','JPG');

	    if (substr($canales, 0, 1) == 'B') {
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
	            $this->pdf->MultiCell(64,6,utf8_decode('NOTA DE CRÉDITO ELECTRÓNICA')."\n"."Nro: ".$b->serie."-".$b->correlativo."\n"."fecha: ".$fechaFormato,1,'C', false);
	            $this->pdf->Ln('10');
	          	$this->pdf->SetFont('Arial','B',12); 
	            $this->pdf->Cell(0,0,utf8_decode($b->contratante),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"DNI: ".$b->cont_numDoc,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").utf8_decode($b->cont_direcc),0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(20,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(25,10,"Tipo Documento",1,0,'C', true);
	            $this->pdf->Cell(25,10,utf8_decode("Num. Documento"),1,0,'C', true);
	            $this->pdf->Cell(25,10,"Fecha de Doc.",1,0,'C', true);
	            $this->pdf->Cell(15,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',7);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(20,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,utf8_decode($b->nombre_plan),1,0,'C');
	            $this->pdf->Cell(25,10,utf8_decode("Boleta"),1,0,'C');
	            $this->pdf->Cell(25,10,$b->seriecorrelativo_doc,1,0,'C');
	            $this->pdf->Cell(25,10,$b->fecha_doc,1,0,'C');
	            $this->pdf->Cell(15,10,"S/. ".$neto,1,0,'C');
	            //--
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(190,10,utf8_decode("Motivo de Devolución"),1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial', '',7);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->MultiCell(190,10, utf8_decode($b->sustento_nota)."\n"."Documento de Referencia: ".$b->seriecorrelativoDoc,1, 'C');
	            $this->pdf->Ln('5');
	            //--
	            $this->pdf->SetFont('Arial', '',8);
	            $this->pdf->Cell(100,10,"  SON ".$numLet->convertir($totalSinDec, 'Y')." ".$totalDec."/100 SOLES",0,0,'L');
	            $this->pdf->Image(base_url().'/adjunto/qr/'.$b->mes."".$b->serie."".$b->correlativo.'.png',24,170,30,0);
	            //--
	            $this->pdf->SetFont('Arial', '',7);
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

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($b->mes."".$b->serie."".$b->correlativo.".pdf", 'I');

	    	}
	    } elseif ($canales == 'FC01') {
	    	foreach ($facturas as $f){

	    		$igv = $f->total-$f->neto;
	    		$tot=$f->total;
	    		$nt=$f->neto;
	    		$total = number_format((float)$tot, 2, '.', '');
	    		$neto = number_format((float)$nt, 2, '.', '');
	    		$igvfinal=number_format((float)$igv, 2, '.', '');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$fechaFormato = date("d/m/Y", strtotime($f->fecha_emision));

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('NOTA DE CRÉDITO')."\n"."Nro: ".$f->serie."-".$f->correlativo."\n"."fecha: ".$fechaFormato,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,utf8_decode($f->razon_social_cli),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"RUC: ".utf8_decode($f->numero_documento_cli),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").utf8_decode($f->direccion_legal),0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005);
	            $this->pdf->Cell(20,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(25,10,"Tipo Documento",1,0,'C', true);
	            $this->pdf->Cell(25,10,utf8_decode("Num. Documento"),1,0,'C', true);
	            $this->pdf->Cell(25,10,"Fecha de Doc.",1,0,'C', true);
	            $this->pdf->Cell(15,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',7);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(20,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,utf8_decode($f->nombre_plan),1,0,'C');
	            $this->pdf->Cell(25,10,utf8_decode("Factura"),1,0,'C');
	            $this->pdf->Cell(25,10,$f->seriecorrelativo_doc,1,0,'C');
	            $this->pdf->Cell(25,10,$f->fecha_doc,1,0,'C');
	            $this->pdf->Cell(15,10,"S/. ".$neto,1,0,'C');

	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(190,10,utf8_decode("Motivo de Referencia"),1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->MultiCell(190,10, utf8_decode($f->sustento_nota),1, 'C');
	            $this->pdf->Ln('5');

	            $this->pdf->SetFont('Arial', '',8);
	            $this->pdf->Cell(100,10,"  SON ".$numLet->convertir($totalSinDec, 'Y')." ".$totalDec."/100 SOLES",0,0,'L');

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

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($f->mes."".$f->serie."".$f->correlativo.".pdf", 'I');

	    	} 
	    } elseif ($canales == 'FD01') {
	    	foreach ($facturasDebito as $fd){

	    		$igv = $fd->total-$fd->neto;
	    		$tot=$fd->total;
	    		$nt=$fd->neto;
	    		$total = number_format((float)$tot, 2, '.', '');
	    		$neto = number_format((float)$nt, 2, '.', '');
	    		$igvfinal=number_format((float)$igv, 2, '.', '');
	    		$totalSinDec = substr ($total, 0, -3);
	    		$totalDec = substr ($total, -2);

	    		$fdechaFormato = date("d/m/Y", strtotime($fd->fecha_emision));

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('NOTA DE CRÉDITO')."\n"."Nro: ".$fd->serie."-".$fd->correlativo."\n"."fecha: ".$fdechaFormato,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,utf8_decode($fd->razon_social_cli),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"RUC: ".utf8_decode($fd->numero_documento_cli),0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,utf8_decode("Dirección: ").utf8_decode($fd->direccion_legal),0,0,'L');
	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005);
	            $this->pdf->Cell(20,10,"Cantidad",1,0,'C', true);
	            $this->pdf->Cell(80,10,utf8_decode("Descripción"),1,0,'C', true);
	            $this->pdf->Cell(35,10,"Tipo Documento",1,0,'C', true);
	            $this->pdf->Cell(25,10,utf8_decode("Num. Documento"),1,0,'C', true);
	            $this->pdf->Cell(15,10,"Fecha de Doc.",1,0,'C', true);
	            $this->pdf->Cell(15,10,"Total",1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',7);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->Cell(20,10,"1",1,0,'C');
	            $this->pdf->Cell(80,10,utf8_decode($fd->nombre_plan),1,0,'C');
	            $this->pdf->Cell(35,10,utf8_decode("Factura"),1,0,'C');
	            $this->pdf->Cell(25,10,$fd->seriecorrelativo_doc,1,0,'C');
	            $this->pdf->Cell(15,10,$fd->fecha_doc,1,0,'C');
	            $this->pdf->Cell(15,10,"S/. ".$neto,1,0,'C');

	            $this->pdf->Ln('15');
	            $this->pdf->SetFont('Arial','B',7);
	            $this->pdf->SetTextColor(255,255,255);
	            $this->pdf->SetFillColor(204, 006, 005); 
	            $this->pdf->Cell(190,10,utf8_decode("Motivo de Referencia"),1,0,'C', true);
	            $this->pdf->Ln('10');
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->SetTextColor(000,000,000);
	            $this->pdf->MultiCell(190,10, utf8_decode($fd->sustento_nota),1, 'C');
	            $this->pdf->Ln('5');

	            $this->pdf->SetFont('Arial', '',8);
	            if ($totalSinDec == 0) {
	            	$this->pdf->Cell(100,10,"  SON CERO Y ".$totalDec."/100 SOLES",0,0,'L');
	            } else {
	            	$this->pdf->Cell(100,10,"  SON ".$numLet->convertir($totalSinDec, 'Y')." ".$totalDec."/100 SOLES",0,0,'L');
	            }

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

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($fd->mes."".$fd->serie."".$fd->correlativo.".pdf", 'I');

	    	}
	    }
	}

	public function generarXmlNotaCredito(){

		include ('./application/libraries/xmldsig/src/XMLSecurityDSig.php');
    	include ('./application/libraries/xmldsig/src/XMLSecurityKey.php');
    	include ('./application/libraries/xmldsig/src/Sunat/SignedXml.php');
    	include ('./application/libraries/CustomHeaders.php');
    	include ('./application/libraries/phpqrcode/qrlib.php');

    	//$this->zip = new ZipArchive();
		$numLet = new NumeroALetras();

        $this->load->library('pdf');
		$this->pdf = new Pdf();
	    $this->pdf->AddPage();
	    $this->pdf->AliasNbPages();

    	$this->xml = new XMLWriter();
    	$mail = new PHPMailer();
    	$numSerie = $_POST['canales'];

    	//$idPlan = $this->input->post('nameCheck');
    	$canales = $this->input->post('canales');
    	$fecinicio = $this->input->post('fechainicio');
    	$fecfin = $this->input->post('fechafin');
    	//$numSerieUno = reset($numSerie);

		if ($canales == 'BC01') {
			$notaCredito = $this->comprobante_pago_mdl->getDatosXmlBoletasAgrupadas($fecinicio, $numSerie);

			foreach ($notaCredito as $nc) {

				$linea=1;

				$numeroConCeros = str_pad($nc->correlativo, 5, "0", STR_PAD_LEFT);
	    		$fechanombre = str_replace ("-" , "", $nc->fecha_emision);
				$idestadocobro = $nc->idestadocobro;

				if ($idestadocobro == 2) {

					$filename="20600258894-RC-".$fechanombre."-".$nc->nume_corre_res;
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
<cbc:ID>RC-'.$fechanombre.'-'.$nc->nume_corre_res.'</cbc:ID>
<cbc:ReferenceDate>'.$nc->fecha_emision.'</cbc:ReferenceDate>
<cbc:IssueDate>'.$nc->fecha_emision.'</cbc:IssueDate>
<cac:AccountingSupplierParty>
	<cbc:CustomerAssignedAccountID>20600258894</cbc:CustomerAssignedAccountID>
	<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
	<cac:Party>
		<cac:PartyLegalEntity>
			<cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
		</cac:PartyLegalEntity>
	</cac:Party>
</cac:AccountingSupplierParty>';
		$notas = $this->comprobante_pago_mdl->getDatosXmlNotasBoleta($fecinicio, $canales);
		foreach ($notas as $n) {
			$datos.='<sac:SummaryDocumentsLine>
	<cbc:LineID>'.$linea.'</cbc:LineID>
	<cbc:DocumentTypeCode>07</cbc:DocumentTypeCode>
	<cbc:ID>'.$n->serie.'-'.$n->correlativo.'</cbc:ID>
	<cac:BillingReference>
		<cac:InvoiceDocumentReference>
			<cbc:ID>'.$n->serie_doc.'-'.$n->correlativo_doc.'</cbc:ID>
			<cbc:DocumentTypeCode>03</cbc:DocumentTypeCode>
		</cac:InvoiceDocumentReference> 
	</cac:BillingReference>
	<cac:Status>
		<cbc:ConditionCode>1</cbc:ConditionCode>
	</cac:Status>
	<sac:TotalAmount currencyID="PEN">'.$n->total.'</sac:TotalAmount>
	<sac:BillingPayment>
		<cbc:PaidAmount currencyID="PEN">'.$n->neto.'</cbc:PaidAmount>
		<cbc:InstructionID>01</cbc:InstructionID>
	</sac:BillingPayment>
	<cac:TaxTotal>
		<cbc:TaxAmount currencyID="PEN">'.$n->igv.'</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxAmount currencyID="PEN">'.$n->igv.'</cbc:TaxAmount>
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
		<cbc:TaxAmount currencyID="PEN">0.00</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxAmount currencyID="PEN">0.00</cbc:TaxAmount>
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

					$nameDoc='RC-'.$fechanombre.'-'.$nc->nume_corre_res;
					$filecdr=$nc->mesanio.'-cdrNotaCreditoBoleta'.$nc->serie;
					$fileNota=$nc->mesanio.'-NotaCreditoBoleta'.$nc->serie;
					$carpetaCdr = 'adjunto/xml/notasdecredito/'.$filecdr;
			    	$carpetaNota = 'adjunto/xml/notasdecredito/'.$fileNota;

			    	$tipodocumento = 'Nota de Credito';

			    	if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaNota)) {
					    mkdir($carpetaNota, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save($carpetaNota.'/'.$filename.'.xml');
					$xmlPath = $carpetaNota.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);
					$xmlSigned = $signer->signFromFile($xmlPath);
					file_put_contents($filename.'.xml', $xmlSigned);

					$this->load->library('zip');

				    $this->zip->add_data($filename.'.xml', $xmlSigned);
					$this->zip->archive('adjunto/xml/notasdecredito/'.$fileNota.'/'.$filename.'.zip');
					$this->zip->clear_data();

					unlink($filename.".xml");
					unlink($carpetaNota.'/'.$filename.".xml");

					$service = 'adjunto/wsdl/billService.wsdl'; 
					//$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
					
			    	//$headers = new CustomHeaders('20600258894MODDATOS', 'MODDATOS');
			    	$headers = new CustomHeaders('20600258894DCACEDA2', 'DCACE716186'); 

			    	$client = new SoapClient($service, array(
			    		'cache_wsdl' => WSDL_CACHE_NONE,
			    		'trace' => TRUE,
			    		//'soap_version' => SOAP_1_2
			    	));
					//print_r($client);
			    	//exit();
			    	$client->__setSoapHeaders([$headers]);
			    	$fcs = $client->__getFunctions();
			    	$zipXml = $filename.'.zip'; 
			    	$params = array(
			    		'fileName' => $zipXml,
			    		'contentFile' => file_get_contents($carpetaNota.'/'.$zipXml) 
			    	); 
			    	
			    	$client->sendSummary($params);
			    	$status = $client->__getLastResponse();

			    	$carpeta = 'adjunto/xml/notasdecredito/'.$filecdr;
					if (!file_exists($carpeta)) {
					    mkdir($carpeta, 0777, true);
					}

			    	//Descargamos el Archivo Response
					$archivo = fopen($carpetaCdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);

					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file($carpetaCdr.'/'.'C'.$filename.'.xml');
					
					$xml = file_get_contents($carpetaCdr.'/C'.$filename.'.xml');
					$DOM = new DOMDocument('1.0', 'utf-8');
					$DOM->loadXML($xml);
					$respuesta = $DOM->getElementsByTagName('ticket');
					foreach ($respuesta as $r) {
						$ticket = $r->nodeValue;
					}

					$estado = $client->getStatus(array('ticket' => $ticket));
					$estadoArray = (array)$estado;
					$contenido = (array)$estadoArray['status'];
					//print_r($contenido['content']);
					$archivo = fopen('adjunto/xml/notasdecredito/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$contenido['content']);
					fclose($archivo);

					$archivo2 = chmod('adjunto/xml/notasdecredito/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);

					unlink('adjunto/xml/notasdecredito/'.$filecdr.'/'.'C'.$filename.'.xml');
					
					$this->comprobante_pago_mdl->updateEstadoCobroEmitidoManual($nc->fecha_emision, $nc->correlativo, 'BC01');
					//verificar respuesta SUNAT
					/*if (file_exists($carpetaCdr.'/R-'.$filename.'.zip')) {
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
					
					if ($descripcion == 'La '.$tipodocumento.' numero '.$nameDoc.', ha sido aceptada') {*/
					//if (!file_exists('adjunto/xml/notasdecredito/'.$filecdr.'/'.'R-'.$filename.'.zip')) {
						//$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nc->fecha_emision, $nc->corre, 'BC01');
					//}
					//}
				}
			}

		} elseif ($canales == 'FC01') {

			$notaCredito = $this->comprobante_pago_mdl->getDatosXmlNotasFactura($fecinicio, $canales);


			foreach ($notaCredito as $nc) {

				$linea=1;

				$numeroConCeros = str_pad($nc->corre, 5, "0", STR_PAD_LEFT);
	    		$fechanombre = str_replace ("-" , "", $nc->fecha_emision);
				$idestadocobro = $nc->idestadocobro;

				if ($idestadocobro == 2) {

					//$filename="20600258894-RC-".$fechanombre."-".$nc->nume_corre_res;

	    		/*$datos = '<?xml version="1.0" encoding="UTF-8"?>
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
<cbc:ID>RC-'.$fechanombre.'-'.$nc->nume_corre_res.'</cbc:ID>
<cbc:ReferenceDate>'.$nc->fecha_emision.'</cbc:ReferenceDate>
<cbc:IssueDate>'.$nc->fecha_emision.'</cbc:IssueDate>
<cac:AccountingSupplierParty>
	<cbc:CustomerAssignedAccountID>20600258894</cbc:CustomerAssignedAccountID>
	<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
	<cac:Party>
		<cac:PartyLegalEntity>
			<cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
		</cac:PartyLegalEntity>
	</cac:Party>
</cac:AccountingSupplierParty>
<sac:SummaryDocumentsLine>
	<cbc:LineID>'.$linea.'</cbc:LineID>
	<cbc:DocumentTypeCode>07</cbc:DocumentTypeCode>
	<cbc:ID>'.$nc->serie.'-'.$nc->correlativo.'</cbc:ID>
	<cac:BillingReference>
		<cac:InvoiceDocumentReference>
			<cbc:ID>'.$nc->serie_doc.'-'.$nc->correlativo_doc.'</cbc:ID>
			<cbc:DocumentTypeCode>01</cbc:DocumentTypeCode>
		</cac:InvoiceDocumentReference> 
	</cac:BillingReference>
	<cac:Status>
		<cbc:ConditionCode>1</cbc:ConditionCode>
	</cac:Status>
	<sac:TotalAmount currencyID="PEN">'.$nc->total.'</sac:TotalAmount>
	<sac:BillingPayment>
		<cbc:PaidAmount currencyID="PEN">'.$nc->neto.'</cbc:PaidAmount>
		<cbc:InstructionID>01</cbc:InstructionID>
	</sac:BillingPayment>
	<cac:TaxTotal>
		<cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
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
		<cbc:TaxAmount currencyID="PEN">0.00</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxAmount currencyID="PEN">0.00</cbc:TaxAmount>
			<cac:TaxCategory>
				<cac:TaxScheme>
					<cbc:ID>2000</cbc:ID>
					<cbc:Name>ISC</cbc:Name>
					<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>
		</cac:TaxSubtotal>
	</cac:TaxTotal>
</sac:SummaryDocumentsLine>
</SummaryDocuments>';*/

					$filename="20600258894-07-".$nc->serie."-".$nc->correlativo;

					$datos = '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
	            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
	            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
	            xmlns:ccts="urn:un:unece:uncefact:documentation:2"
	            xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
	            xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
	            xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
	            xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
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
	  <cbc:ID>'.$nc->serie.'-'.$nc->correlativo.'</cbc:ID>
	  <cbc:IssueDate>'.$nc->fecha_emision.'</cbc:IssueDate>
	  <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
	  <cac:DiscrepancyResponse>
	    <cbc:ReferenceID>'.$nc->serie_doc.'-'.$nc->correlativo_doc.'</cbc:ReferenceID>
	    <cbc:ResponseCode>07</cbc:ResponseCode>
	    <cbc:Description>'.$nc->sustento_nota.'</cbc:Description>
	  </cac:DiscrepancyResponse>
	  <cac:BillingReference>
	    <cac:InvoiceDocumentReference>
	      <cbc:ID>'.$nc->serie_doc.'-'.$nc->correlativo_doc.'</cbc:ID>
	      <cbc:DocumentTypeCode>01</cbc:DocumentTypeCode>
	    </cac:InvoiceDocumentReference>
	  </cac:BillingReference>
	  <cac:Signature>
	    <cbc:ID>LlamaSign</cbc:ID>
	    <cac:SignatoryParty>
	      <cac:PartyIdentification>
	        <cbc:ID>20600258894</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyName>
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	    </cac:SignatoryParty>
	    <cac:DigitalSignatureAttachment>
	      <cac:ExternalReference>
	        <cbc:URI>#LlamaSign</cbc:URI>
	      </cac:ExternalReference>
	    </cac:DigitalSignatureAttachment>
	  </cac:Signature>
	  <cac:AccountingSupplierParty>
	    <cac:Party>
	      <cac:PartyIdentification>
	        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20600258894</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyName>
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
	        <cac:RegistrationAddress>
	          <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
	        </cac:RegistrationAddress>
	      </cac:PartyLegalEntity>
	    </cac:Party>
	  </cac:AccountingSupplierParty>
	  <cac:AccountingCustomerParty>
	    <cac:Party>
	      <cac:PartyIdentification>
	        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$nc->numero_documento_cli.'</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>'.$nc->razon_social_cli.'</cbc:RegistrationName>
	      </cac:PartyLegalEntity>
	    </cac:Party>
	  </cac:AccountingCustomerParty>
	  <cac:TaxTotal>
	    <cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
	    <cac:TaxSubtotal>
	      <cbc:TaxableAmount currencyID="PEN">'.$nc->neto.'</cbc:TaxableAmount>
	      <cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
	      <cac:TaxCategory>
	        <cac:TaxScheme>
	          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
	          <cbc:Name>IGV</cbc:Name>
	          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	        </cac:TaxScheme>
	      </cac:TaxCategory>
	    </cac:TaxSubtotal>
	  </cac:TaxTotal>
	  <cac:LegalMonetaryTotal>
	    <cbc:PayableAmount currencyID="PEN">'.$nc->total.'</cbc:PayableAmount>
	  </cac:LegalMonetaryTotal>
	  <cac:CreditNoteLine>
	    <cbc:ID>1</cbc:ID>
	    <cbc:CreditedQuantity unitCode="NIU">1</cbc:CreditedQuantity>
	    <cbc:LineExtensionAmount currencyID="PEN">'.$nc->neto.'</cbc:LineExtensionAmount>
	    <cac:PricingReference>
	      <cac:AlternativeConditionPrice>
	        <cbc:PriceAmount currencyID="PEN">'.$nc->total.'</cbc:PriceAmount>
	        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
	      </cac:AlternativeConditionPrice>
	    </cac:PricingReference>
	    <cac:TaxTotal>
	      <cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
	      <cac:TaxSubtotal>
	        <cbc:TaxableAmount currencyID="PEN">'.$nc->neto.'</cbc:TaxableAmount>
	        <cbc:TaxAmount currencyID="PEN">'.$nc->igv.'</cbc:TaxAmount>
	        <cac:TaxCategory>
	          <cbc:Percent>18.00</cbc:Percent>
	          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
	          <cac:TaxScheme>
	            <cbc:ID>1000</cbc:ID>
	            <cbc:Name>IGV</cbc:Name>
	            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	          </cac:TaxScheme>
	        </cac:TaxCategory>
	      </cac:TaxSubtotal>
	    </cac:TaxTotal>
	    <cac:Item>
	      <cbc:Description>'.utf8_decode($nc->nombre_plan).'</cbc:Description>
	    </cac:Item>
	    <cac:Price>
	      <cbc:PriceAmount currencyID="PEN">'.$nc->neto.'</cbc:PriceAmount>
	    </cac:Price>
	  </cac:CreditNoteLine>
	</CreditNote>';

					$nameDoc=$nc->serie."-".$nc->correlativo;
					$filecdr=$nc->mesanio.'-cdrNotaCreditoFactura';
					$fileNota=$nc->mesanio.'-NotaCreditoFactura';
					$carpetaCdr = 'adjunto/xml/notasdecredito/'.$filecdr;
			    	$carpetaNota = 'adjunto/xml/notasdecredito/'.$fileNota;

			    	$tipodocumento = 'Nota de Credito';

			    	if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaNota)) {
					    mkdir($carpetaNota, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save($carpetaNota.'/'.$filename.'.xml');
					$xmlPath = $carpetaNota.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);
					$xmlSigned = $signer->signFromFile($xmlPath);
					file_put_contents($filename.'.xml', $xmlSigned);

					$this->load->library('zip');

				    $this->zip->add_data($filename.'.xml', $xmlSigned);
					$this->zip->archive('adjunto/xml/notasdecredito/'.$fileNota.'/'.$filename.'.zip');
					$this->zip->clear_data();

					unlink($filename.".xml");
					unlink($carpetaNota.'/'.$filename.".xml");

					$service = 'adjunto/wsdl/billService.wsdl';
					//$service = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
					//$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
					
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
			    		'contentFile' => file_get_contents($carpetaNota.'/'.$zipXml) 
			    	); 
			    	
			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();

			    	$carpeta = 'adjunto/xml/notasdecredito/'.$filecdr;
					if (!file_exists($carpeta)) {
					    mkdir($carpeta, 0777, true);
					}

					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nc->fecha_emision, $nc->corre, $nc->serie);

			    	//Descargamos el Archivo Response
					$archivo = fopen($carpetaCdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);

					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file($carpetaCdr.'/'.'C'.$filename.'.xml');
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }

					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen($carpetaCdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);
					$archivo2 = chmod($carpetaCdr.'/'.'R-'.$filename.'.zip', 0777);

					unlink($carpetaCdr.'/'.'C'.$filename.'.xml');
					
					$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
					$DOM = new DOMDocument('1.0', 'utf-8');
					$DOM->loadXML($xml);
					$respuesta = $DOM->getElementsByTagName('Description');
					foreach ($respuesta as $r) {
						$descripcion = $r->nodeValue;
					}

					//if ($descripcion == 'La '.$tipodocumento.' numero '.$nameDoc.', ha sido aceptada') {
						
					//}

					/*$estado = $client->getStatus(array('ticket' => $ticket));
					$estadoArray = (array)$estado;
					$contenido = (array)$estadoArray['status'];
					//print_r($contenido['content']);
					$archivo = fopen('adjunto/xml/notasdecredito/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$contenido['content']);
					fclose($archivo);

					$archivo2 = chmod('adjunto/xml/notasdecredito/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);

					unlink('adjunto/xml/notasdecredito/'.$filecdr.'/'.'C'.$filename.'.xml');
					
					$this->comprobante_pago_mdl->updateEstadoCobroEmitidoManual($nc->fecha_emision, $nc->correlativo, 'BC01');*/

				}
			}


		} /*elseif ($canales == 'B007') {
			$notaDebito = $this->comprobante_pago_mdl->getDatosXmlNotasBoleta($fecinicio, $fecfin, $canales);

			foreach ($notaDebito as $nd) {

				$idestadocobro = $nd->idestadocobro;

				if ($idestadocobro == 2) {

					$filename="20600258894-08-".$nd->serie."-".$nd->correlativo;

					$datos = '<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2"
	           xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
	           xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
	           xmlns:ccts="urn:un:unece:uncefact:documentation:2"
	           xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
	           xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
	           xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
	           xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
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
	  <cbc:ID>'.$nd->serie.'-'.$nd->correlativo.'</cbc:ID>
	  <cbc:IssueDate>'.$nd->fecha_emision.'</cbc:IssueDate>
	  <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
	  <cac:DiscrepancyResponse>
	    <cbc:ReferenceID>'.$nd->serie_doc.'-'.$nd->correlativo_doc.'</cbc:ReferenceID>
	    <cbc:ResponseCode>02</cbc:ResponseCode>
	    <cbc:Description>'.$nd->sustento_nota.'</cbc:Description>
	  </cac:DiscrepancyResponse>
	  <cac:BillingReference>
	    <cac:InvoiceDocumentReference>
	      <cbc:ID>'.$nd->serie_doc.'-'.$nd->correlativo_doc.'</cbc:ID>
	      <cbc:DocumentTypeCode>03</cbc:DocumentTypeCode>
	    </cac:InvoiceDocumentReference>
	  </cac:BillingReference>

	  <cac:Signature>
	    <cbc:ID>LlamaSign</cbc:ID>
	    <cac:SignatoryParty>
	      <cac:PartyIdentification>
	        <cbc:ID>20600258894</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyName>
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	    </cac:SignatoryParty>
	    <cac:DigitalSignatureAttachment>
	      <cac:ExternalReference>
	        <cbc:URI>#LlamaSign</cbc:URI>
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
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
	        <cac:RegistrationAddress>
	          <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
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
	                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$nd->cont_numDoc.'</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>'.$nd->contratante.'</cbc:RegistrationName>
	      </cac:PartyLegalEntity>
	    </cac:Party>
	  </cac:AccountingCustomerParty>
	  <cac:TaxTotal>
	    <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	    <cac:TaxSubtotal>
	      <cbc:TaxableAmount currencyID="PEN">'.$nd->neto.'</cbc:TaxableAmount>
	      <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	      <cac:TaxCategory>
	        <cac:TaxScheme>
	          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
	          <cbc:Name>IGV</cbc:Name>
	          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	        </cac:TaxScheme>
	      </cac:TaxCategory>
	    </cac:TaxSubtotal>
	  </cac:TaxTotal>
	  <cac:RequestedMonetaryTotal>
	    <cbc:PayableAmount currencyID="PEN">'.$nd->total.'</cbc:PayableAmount>
	  </cac:RequestedMonetaryTotal>
	  <cac:DebitNoteLine>
	    <cbc:ID>1</cbc:ID>
	    <cbc:DebitedQuantity unitCode="ZZ">1</cbc:DebitedQuantity>
	    <cbc:LineExtensionAmount currencyID="PEN">'.$nd->neto.'</cbc:LineExtensionAmount>
	    <cac:PricingReference>
	      <cac:AlternativeConditionPrice>
	        <cbc:PriceAmount currencyID="PEN">'.$nd->total.'</cbc:PriceAmount>
	        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
	      </cac:AlternativeConditionPrice>
	    </cac:PricingReference>
	    <cac:TaxTotal>
	      <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	      <cac:TaxSubtotal>
	        <cbc:TaxableAmount currencyID="PEN">'.$nd->neto.'</cbc:TaxableAmount>
	        <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	        <cac:TaxCategory>
	          <cbc:Percent>18.00</cbc:Percent>
	          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
	          <cac:TaxScheme>
	            <cbc:ID>1000</cbc:ID>
	            <cbc:Name>IGV</cbc:Name>
	            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	          </cac:TaxScheme>
	        </cac:TaxCategory>
	      </cac:TaxSubtotal>
	    </cac:TaxTotal>
	    <cac:Item>
	      <cbc:Description>'.$nd->nombre_plan.'</cbc:Description>
	    </cac:Item>
	    <cac:Price>
	      <cbc:PriceAmount currencyID="PEN">'.$nd->neto.'</cbc:PriceAmount>
	    </cac:Price>
	  </cac:DebitNoteLine>
	</DebitNote>';

					$nameDoc=$nd->serie."-".$nd->correlativo;
					$filecdr=$nd->mesanio.'-cdrNotaDebitoBoleta';
					$fileNota=$nd->mesanio.'-NotaDebitoBoleta';
					$carpetaCdr = 'adjunto/xml/notasdecredito/'.$filecdr;
			    	$carpetaNota = 'adjunto/xml/notasdecredito/'.$fileNota;

			    	$tipodocumento = 'Nota de Debito';

			    	if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaNota)) {
					    mkdir($carpetaNota, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save($carpetaNota.'/'.$filename.'.xml');
					$xmlPath = $carpetaNota.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);
					$xmlSigned = $signer->signFromFile($xmlPath);
					file_put_contents($filename.'.xml', $xmlSigned);

					//echo $xmlSigned;
					if ($this->zip->open($carpetaNota.'/'.$filename.".zip", ZIPARCHIVE::CREATE)===true) {
						$this->zip->addFile($filename.'.xml');
						$this->zip->close();
						unlink($filename.".xml");
						unlink($carpetaNota.'/'.$filename.".xml");
					} else {
						unlink($filename.".xml");
						unlink($carpetaNota.'/'.$filename.".xml");
					}

					$service = 'adjunto/wsdl/billService.wsdl';
					//$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

			    	$headers = new CustomHeaders('20600258894DCACEDA2', 'DCACE716186'); 

			    	$client = new SoapClient($service, array(
			    		'cache_wsdl' => WSDL_CACHE_NONE,
			    		'trace' => TRUE,
			    		//'soap_version' => SOAP_1_2
			    	));
					//print_r($client);
			    	//exit();
			    	$client->__setSoapHeaders([$headers]); 
			    	$fcs = $client->__getFunctions();
			    	$zipXml = $filename.'.zip'; 
			    	$params = array( 
			    		'fileName' => $zipXml, 
			    		'contentFile' => file_get_contents($carpetaNota.'/'.$zipXml) 
			    	); 
			    	
			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();

			    	//Descargamos el Archivo Response
					$archivo = fopen($carpetaCdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);

					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file($carpetaCdr.'/'.'C'.$filename.'.xml');
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }
					
					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen($carpetaCdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);
					$archivo2 = chmod($carpetaCdr.'/'.'R-'.$filename.'.zip', 0777);

					unlink($carpetaCdr.'/'.'C'.$filename.'.xml');

					//verificar respuesta SUNAT
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
					if ($descripcion == 'La '.$tipodocumento.' numero '.$nameDoc.', ha sido aceptada') {
						$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nd->fecha_emision, $nd->corre, $nd->serie);
					}
				}
			}

		}*/ elseif ($canales == 'FD01') {
			$notaDebito = $this->comprobante_pago_mdl->getDatosXmlNotasFactura($fecinicio, $canales);

			foreach ($notaDebito as $nd) {

				$idestadocobro = $nd->idestadocobro;

				if ($idestadocobro == 2) {

					$filename="20600258894-08-".$nd->serie."-".$nd->correlativo;



					$datos = '<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2"
	           xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
	           xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
	           xmlns:ccts="urn:un:unece:uncefact:documentation:2"
	           xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
	           xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
	           xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
	           xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
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
	  <cbc:ID>'.$nd->serie.'-'.$nd->correlativo.'</cbc:ID>
	  <cbc:IssueDate>'.$nd->fecha_emision.'</cbc:IssueDate>
	  <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
	  <cac:DiscrepancyResponse>
	    <cbc:ReferenceID>'.$nd->serie_doc.'-'.$nd->correlativo_doc.'</cbc:ReferenceID>
	    <cbc:ResponseCode>02</cbc:ResponseCode>
	    <cbc:Description>'.$nd->sustento_nota.'</cbc:Description>
	  </cac:DiscrepancyResponse>
	  <cac:BillingReference>
	    <cac:InvoiceDocumentReference>
	      <cbc:ID>'.$nd->serie_doc.'-'.$nd->correlativo_doc.'</cbc:ID>
	      <cbc:DocumentTypeCode>01</cbc:DocumentTypeCode>
	    </cac:InvoiceDocumentReference>
	  </cac:BillingReference>

	  <cac:Signature>
	    <cbc:ID>LlamaSign</cbc:ID>
	    <cac:SignatoryParty>
	      <cac:PartyIdentification>
	        <cbc:ID>20600258894</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyName>
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	    </cac:SignatoryParty>
	    <cac:DigitalSignatureAttachment>
	      <cac:ExternalReference>
	        <cbc:URI>#LlamaSign</cbc:URI>
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
	        <cbc:Name>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:Name>
	      </cac:PartyName>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>HEALTH CARE ADMINISTRATION RED SALUD S.A.C.</cbc:RegistrationName>
	        <cac:RegistrationAddress>
	          <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
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
	                schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$nd->numero_documento_cli.'</cbc:ID>
	      </cac:PartyIdentification>
	      <cac:PartyLegalEntity>
	        <cbc:RegistrationName>'.$nd->razon_social_cli.'</cbc:RegistrationName>
	      </cac:PartyLegalEntity>
	    </cac:Party>
	  </cac:AccountingCustomerParty>
	  <cac:TaxTotal>
	    <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	    <cac:TaxSubtotal>
	      <cbc:TaxableAmount currencyID="PEN">'.$nd->neto.'</cbc:TaxableAmount>
	      <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	      <cac:TaxCategory>
	        <cac:TaxScheme>
	          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
	          <cbc:Name>IGV</cbc:Name>
	          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	        </cac:TaxScheme>
	      </cac:TaxCategory>
	    </cac:TaxSubtotal>
	  </cac:TaxTotal>
	  <cac:RequestedMonetaryTotal>
	    <cbc:PayableAmount currencyID="PEN">'.$nd->total.'</cbc:PayableAmount>
	  </cac:RequestedMonetaryTotal>
	  <cac:DebitNoteLine>
	    <cbc:ID>1</cbc:ID>
	    <cbc:DebitedQuantity unitCode="ZZ">1</cbc:DebitedQuantity>
	    <cbc:LineExtensionAmount currencyID="PEN">'.$nd->neto.'</cbc:LineExtensionAmount>
	    <cac:PricingReference>
	      <cac:AlternativeConditionPrice>
	        <cbc:PriceAmount currencyID="PEN">'.$nd->total.'</cbc:PriceAmount>
	        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
	      </cac:AlternativeConditionPrice>
	    </cac:PricingReference>
	    <cac:TaxTotal>
	      <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	      <cac:TaxSubtotal>
	        <cbc:TaxableAmount currencyID="PEN">'.$nd->neto.'</cbc:TaxableAmount>
	        <cbc:TaxAmount currencyID="PEN">'.$nd->igv.'</cbc:TaxAmount>
	        <cac:TaxCategory>
	          <cbc:Percent>18.00</cbc:Percent>
	          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
	          <cac:TaxScheme>
	            <cbc:ID>1000</cbc:ID>
	            <cbc:Name>IGV</cbc:Name>
	            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
	          </cac:TaxScheme>
	        </cac:TaxCategory>
	      </cac:TaxSubtotal>
	    </cac:TaxTotal>
	    <cac:Item>
	      <cbc:Description>'.utf8_decode($nd->nombre_plan).'</cbc:Description>
	    </cac:Item>
	    <cac:Price>
	      <cbc:PriceAmount currencyID="PEN">'.$nd->neto.'</cbc:PriceAmount>
	    </cac:Price>
	  </cac:DebitNoteLine>
	</DebitNote>';

					$nameDoc=$nd->serie."-".$nd->correlativo;
					$filecdr=$nd->mesanio.'-cdrNotaDebitoFactura';
					$fileNota=$nd->mesanio.'-NotaDebitoFactura';
					$carpetaCdr = 'adjunto/xml/notasdedebito/'.$filecdr;
			    	$carpetaNota = 'adjunto/xml/notasdedebito/'.$fileNota;

			    	$tipodocumento = 'Nota de Debito';

			    	if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaNota)) {
					    mkdir($carpetaNota, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save($carpetaNota.'/'.$filename.'.xml');
					$xmlPath = $carpetaNota.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);
					$xmlSigned = $signer->signFromFile($xmlPath);
					file_put_contents($filename.'.xml', $xmlSigned);

					$this->load->library('zip');

				    $this->zip->add_data($filename.'.xml', $xmlSigned);
					$this->zip->archive('adjunto/xml/notasdedebito/'.$fileNota.'/'.$filename.'.zip');
					$this->zip->clear_data();

					unlink($filename.".xml");
					unlink($carpetaNota.'/'.$filename.".xml");

					$service = 'adjunto/wsdl/billService.wsdl';
					//$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

					//$headers = new CustomHeaders('20600258894MODDATOS', 'MODDATOS');
			    	$headers = new CustomHeaders('20600258894DCACEDA2', 'DCACE716186'); 

			    	$client = new SoapClient($service, array(
			    		'cache_wsdl' => WSDL_CACHE_NONE,
			    		'trace' => TRUE,
			    		//'soap_version' => SOAP_1_2
			    	));
					//print_r($client);
			    	//exit();
			    	$client->__setSoapHeaders([$headers]); 
			    	$fcs = $client->__getFunctions();
			    	$zipXml = $filename.'.zip'; 
			    	$params = array( 
			    		'fileName' => $zipXml, 
			    		'contentFile' => file_get_contents($carpetaNota.'/'.$zipXml) 
			    	); 
			    	
			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();

			    	//Descargamos el Archivo Response
					$archivo = fopen($carpetaCdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);

					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nd->fecha_emision, $nd->corre, $nd->serie);

					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file($carpetaCdr.'/'.'C'.$filename.'.xml');
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }
					
					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen($carpetaCdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);
					$archivo2 = chmod($carpetaCdr.'/'.'R-'.$filename.'.zip', 0777);

					unlink($carpetaCdr.'/'.'C'.$filename.'.xml');

					$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
					$DOM = new DOMDocument('1.0', 'utf-8');
					$DOM->loadXML($xml);
					$respuesta = $DOM->getElementsByTagName('Description');
					foreach ($respuesta as $r) {
						$descripcion = $r->nodeValue;
					}
					//if ($descripcion == 'La '.$tipodocumento.' numero '.$nameDoc.', ha sido aceptada') {
						
					//}
				}
			}
		}
	}
}