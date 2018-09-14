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

			$html = null;
			$iddocumento = null;

			$serie = $this->comprobante_pago_mdl->getSerie();
			$data['serie'] = $serie;

			$serieDos = $this->comprobante_pago_mdl->getSerieEmitir();
			$data['serieDos'] = $serieDos;


			$this->load->view('dsb/html/comprobante/notas_comp.php',$data);

		} else {
			redirect('/');
		}
	}

	public function mostrarDocumentoNotaCredito()
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

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {

			$boletas = $this->comprobante_pago_mdl->getDatosBoletaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$boletas as $b) {
				$data['seriecorrelativo'] = $b->seriecorrelativo;
				$data['importe_total'] = $b->importe_total;
				$data['tipo_moneda'] = $b->tipo_moneda;
				$data['nombre_comercial_cli'] = $b->nombre_comercial_cli;
				$data['numero_documento_cli'] = $b->numero_documento_cli;
				$data['id_contratante'] = $b->id_contratante;
				$data['idplan'] = $b->idplan;
				$data['serie'] = 'B006';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($fecinicio, $fecfin, $data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
				}
			}

		} elseif ($serie == 'F001') {

			$facturas = $this->comprobante_pago_mdl->getDatosFacturaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$facturas as $f) {
				$data['seriecorrelativo'] = $f->seriecorrelativo;
				$data['importe_total'] = $f->importe_total;
				$data['tipo_moneda'] = $f->tipo_moneda;
				$data['nombre_comercial_cli'] = $f->nombre_comercial_cli;
				$data['numero_documento_cli'] = $f->numero_documento_cli;
				$data['id_contratante'] = $f->id_cliente_empresa;
				$data['idplan'] = $f->idplan;
				$data['serie'] = 'F002';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($fecinicio, $fecfin, $data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
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
		$fechaEmi = date('Y-m-d');
		$numSerie = $_POST['numSerie'];
		$correGen = $_POST['correGen'];
		$impTotal = $_POST['impNota'];
		$serie = $_POST['serie'];
		$correlativoDoc = $_POST['correlativoDoc'];
		$motivo = $_POST['motivo'];
		$idTipoDoc = 5;

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {
			
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo);


		} elseif ($serie == 'F001') {
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosFacturasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo);
		}
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

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {

			$boletas = $this->comprobante_pago_mdl->getDatosBoletaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$boletas as $b) {
				$data['seriecorrelativo'] = $b->seriecorrelativo;
				$data['importe_total'] = $b->importe_total;
				$data['tipo_moneda'] = $b->tipo_moneda;
				$data['nombre_comercial_cli'] = $b->nombre_comercial_cli;
				$data['numero_documento_cli'] = $b->numero_documento_cli;
				$data['id_contratante'] = $b->id_contratante;
				$data['idplan'] = $b->idplan;
				$data['serie'] = 'B007';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($fecinicio, $fecfin, $data['serie']);
				foreach ($correlativo as $c) {
					$data['correlativo'] = $c->correlativo;
				}
			}

		} elseif ($serie == 'F001') {

			$facturas = $this->comprobante_pago_mdl->getDatosFacturaNota($serie, $correlativo, $fecha_emision);
			foreach ((array)$facturas as $f) {
				$data['seriecorrelativo'] = $f->seriecorrelativo;
				$data['importe_total'] = $f->importe_total;
				$data['tipo_moneda'] = $f->tipo_moneda;
				$data['nombre_comercial_cli'] = $f->nombre_comercial_cli;
				$data['numero_documento_cli'] = $f->numero_documento_cli;
				$data['id_contratante'] = $f->id_cliente_empresa;
				$data['idplan'] = $f->idplan;
				$data['serie'] = 'F003';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($fecinicio, $fecfin, $data['serie']);
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
		$fechaEmi = date('Y-m-d');
		$numSerie = $_POST['numSeriesD'];
		$correGen = $_POST['numCorreD'];
		$impTotal = $_POST['impNotaD'];
		$serie = $_POST['serie'];
		$correlativoDoc = $_POST['correlativoDoc'];
		$motivo = $_POST['motivoD'];
		$idTipoDoc = 6;

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {
			
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo);

		} elseif ($serie == 'F001') {
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosFacturasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo);
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
		$datos['canales'] = $canales;

		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];

		if ($canales == 'B006' || $canales == 'B007') {

			$html .="<hr>";
			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha de Emisión</th>";
							$html .="<th>N° de comprobante</th>";
							$html .="<th>Nombres y Apellidos</th>";
							$html .="<th>DNI</th>";
							$html .="<th>Plan</th>";
							$html .="<th>N° de comprobante afectado</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Sustento</th>";
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					$boletas = $this->comprobante_pago_mdl->getDatosNotasBoletas($canales, $inicio, $fin);

					foreach ((array)$boletas as $b) {

						$importe = $b->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $b->idestadocobro;
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$b->fecha_emision."</td>";
							$html .= "<td align='left'>".$b->seriecorrelativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->idcomprobante."'></td>";
							$html .= "<td align='left'>".utf8_decode($b->contratante)."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value=''></td>";
							$html .= "<td align='left'>".$b->cont_numDoc."</td>";
							$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
							$html .= "<td align='left'>".$b->seriecorrelativoDoc."</td>";
							$html .= "<td align='center'>S/. ".$importe2."</td>";
							$html .= "<td align='left'>	".$b->sustento_nota."</td>";
							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>Generado</td>";
							} elseif ($estado == 3) {
								$html .= "<td align='center' class='success'>Emitido</td>";
							}
							$html .= "<td align='left'>";
								$html .= "<ul class='ico-stack'>";
									$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
										$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
											$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
										$html .="</a>";
									$html .="</div>";
									$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
										$html .="<a class='boton fancybox' href='' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
											$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
										$html .="</a>";
									$html .="</div>";
								$html .= "</ul>";
							$html .="</td>";
						$html .= "</tr>";

					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		} elseif ($canales == 'F002' || $canales == 'F003') {

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

					$facturas = $this->comprobante_pago_mdl->getDatosNotasFacturas($canales, $inicio, $fin);

					foreach ((array)$facturas as $f) {

						$importe = $f->importe_total;
						$importe2=number_format((float)$importe, 2, '.', ',');

						$estado = $f->idestadocobro;
						
						$html .= "<tr>";
							$html .= "<td align='left'>".$f->fecha_emision."</td>";
							$html .= "<td align='left'>".$f->seriecorrelativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$f->idcomprobante."'></td>";
							$html .= "<td align='left'>".utf8_decode($f->nombre_comercial_cli)."</td>";
							$html .= "<td align='left'>".$f->numero_documento_cli."</td>";
							$html .= "<td align='left'>".utf8_decode($f->nombre_plan)."</td>";
							$html .= "<td align='left'>".$f->seriecorrelativoDoc."</td>";
							$html .= "<td align='center'>S/. ".$importe2."</td>";
							$html .= "<td align='left'>".$f->sustento_nota."</td>";
							if ($estado == 2) {
								$html .= "<td align='center' class='danger'>Generado</td>";
							} elseif ($estado == 3) {
								$html .= "<td align='center' class='success'>Emitido</td>";
							}
							$html .= "<td align='left'>";
								$html .= "<ul class='ico-stack'>";
									$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
										$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
											$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
										$html .="</a>";
									$html .="</div>";
									$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
										$html .="<a class='boton fancybox' href='' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
											$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
										$html .="</a>";
									$html .="</div>";
								$html .= "</ul>";
							$html .="</td>";
						$html .= "</tr>";

					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		}
		echo json_encode($html);
	}

	/*public function generarPdf($idcomprobante, $canales){

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
	    //$this->pdf->Image(base_url().'/public/assets/avatars/user.jpg','0','0','150','150','JPG');

	    if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {
	    	foreach ($boletas as $b){

	    		$fechaFormato = date("d/m/Y", strtotime($b->fecha_emision));
	    		          
	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('BOLETA DE VENTA ELECTRÓNICA')."\n"."Nro: ".$b->serie."-".$b->correlativo."\n"."fecha: ".$fechaFormato,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,$b->contratante,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"DNI: ".$b->cont_numDoc,0,0,'L');
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
	            $this->pdf->Cell(30,10,"S/. ".$b->neto,1,0,'C');
	            $this->pdf->Cell(25,10,"S/. 0.00",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$b->neto,1,0,'C');
	            $this->pdf->Ln('20');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones gravadas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$b->neto." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones inafectas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones exoneradas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones gratuitas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Total de Descuentos",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"IGV",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$b->igv." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$b->total." ",1,0,'R');

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($b->mes."".$b->serie."".$b->correlativo.".pdf", 'I');

	    	}
	    } elseif ($canales == 4) {
	    	foreach ($facturas as $f){

	    		$fechaFormato = date("d/m/Y", strtotime($f->fecha_emision));

	            $this->pdf->Ln('15');
	          	$this->pdf->SetFont('Arial','B',10); 
	            $this->pdf->Cell(126);
	            $this->pdf->MultiCell(64,6,utf8_decode('BOLETA DE VENTA ELECTRÓNICA')."\n"."Nro: ".$f->serie."-".$f->correlativo."\n"."fecha: ".$fechaFormato,1,'C', false);
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(0,0,$f->razon_social_cli,0,0,'L');
	            $this->pdf->Ln('5');
	            $this->pdf->SetFont('Arial','B',11);
	            $this->pdf->Cell(0,0,"RUC: ".$f->numero_documento_cli,0,0,'L');
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
	            $this->pdf->Cell(30,10,"S/. ".$f->neto,1,0,'C');
	            $this->pdf->Cell(25,10,"S/. 0.00",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$f->neto,1,0,'C');
	            $this->pdf->Ln('20');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones gravadas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$f->neto." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones inafectas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones exoneradas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Operaciones gratuitas",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Total de Descuentos",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. 0.00 ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"IGV",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$f->igv." ",1,0,'R');
	            $this->pdf->Ln('10');
	            $this->pdf->Cell(80);
	            $this->pdf->Cell(80,10,"Importe total de la venta",1,0,'C');
	            $this->pdf->Cell(30,10,"S/. ".$f->total." ",1,0,'R');

	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle("Comprobante de pago");
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);

		        $this->pdf->Output($f->mes."".$f->serie."".$f->correlativo.".pdf", 'I');

	    	}
	    }
	}*/

}