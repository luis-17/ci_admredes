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
				$data['nombre_comercial_cli'] = $b->contratante;
				$data['numero_documento_cli'] = $b->cont_numDoc;
				$data['id_contratante'] = $b->id_contratante;
				$data['idplan'] = $b->idplan;
				$data['serie'] = 'BC01';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
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
				$data['serie'] = 'FC01';

				$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativoMasUno($data['serie']);
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
		$fechaEmi = $_POST['fechEmiNota'];
		$numSerie = $_POST['numSerie'];
		$correGen = $_POST['correGen'];
		$impTotal = $_POST['impNota'];
		$serie = $_POST['serie'];
		$correlativoDoc = $_POST['correlativoDoc'];
		$motivo = $_POST['motivo'];
		$idTipoDoc = 5;
		$fechaDoc = $_POST['fechaDoc'];

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {
			
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo, $fechaDoc);


		} elseif ($serie == 'F001') {
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosFacturasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo, $fechaDoc);
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

		if ($serie == 'B001' || $serie == 'B002' || $serie == 'B003' || $serie == 'B004' || $serie == 'B005') {
			
			$idplan = $_POST['idplan'];
			$idcliente = $_POST['idcliente'];

			$this->comprobante_pago_mdl->insertDatosBoletasNotas($fechaEmi, $numSerie, $correGen, $idcliente, $idTipoDoc, $impTotal, $idplan, $serie, $correlativoDoc, $motivo, $fechaDoc);

		} elseif ($serie == 'F001') {
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
		$datos['canales'] = $canales;

		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];

		if ($canales == 'BC01' || $canales == 'BD01') {

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
							}
						$html .= "</tr>";

					}

					$html .= "</tbody>";
				$html .= "</table>";
			$html .= "</div>";

		} elseif ($canales == 'FC01' || $canales == 'FD01') {

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
							}
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

	/*public function generarExcel(){

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
		//date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$fecemi = $data['fechaEmision'];

		$canales = $_POST['canales'];

		$fechainicio = $_POST['fechainicio'];
		$fechafin = $_POST['fechafin'];

		$numSerie = $_POST['numSerie'];	
			
		$boletas = $this->comprobante_pago_mdl->getDatosExcelBoletas($fechainicio, $fechafin, $numSerie);
		$facturas = $this->comprobante_pago_mdl->getDatosExcelFacturas($fechainicio, $fechafin, $numSerie);

			//Se carga la librería de excel
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle('Hoja 1');

			//contador de filas
			//$contador = 1;
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

			if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {

				//Definimos la data del cuerpo.        
		        foreach($boletas as $b){

		        	$formatoFecha = date("d/m/Y", strtotime($b->fecha_emision));
		          	//Informacion de las filas de la consulta.
					$this->excel->getActiveSheet()->setCellValue("B{$contador1}","05");
					$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$b->mes."".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
					$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$b->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
					$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$b->total);
					$this->excel->getActiveSheet()->setCellValue("R{$contador1}","03");
					$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$b->nombre_plan);

					$this->excel->getActiveSheet()->setCellValue("B{$contador2}","05");
					$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$b->mes."".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
					$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$b->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$b->igv);
					$this->excel->getActiveSheet()->setCellValue("R{$contador2}","03");
					$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$b->nombre_plan);

					$this->excel->getActiveSheet()->setCellValue("B{$contador3}","05");
					$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$b->mes."".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$b->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
					$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$b->cont_numDoc);
					$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$b->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$b->neto);
					$this->excel->getActiveSheet()->setCellValue("R{$contador3}","03");
					$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$b->serie."-".$b->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$b->nombre_plan);

		           //Incrementamos filas, para ir a la siguiente.
		           $contador1=$contador1+3;
		           $contador2=$contador2+3;
		           $contador3=$contador3+3;
		        }
		    } elseif ($canales == 4) {

		    	//Definimos la data del cuerpo.        
		        foreach($facturas as $f){

		        	$formatoFecha = date("d/m/Y", strtotime($f->fecha_emision));
		          	//Informacion de las filas de la consulta.
					$this->excel->getActiveSheet()->setCellValue("B{$contador1}","04");
					$this->excel->getActiveSheet()->setCellValue("C{$contador1}",$f->mes."".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador1}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador1}","COBRO POR ".$f->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador1}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador1}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador1}","121201");
					$this->excel->getActiveSheet()->setCellValue("L{$contador1}",$f->numero_documento_cli);
					$this->excel->getActiveSheet()->setCellValue("M{$contador1}",$f->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador1}","D");
					$this->excel->getActiveSheet()->setCellValue("O{$contador1}",$f->total);
					$this->excel->getActiveSheet()->setCellValue("R{$contador1}","01");
					$this->excel->getActiveSheet()->setCellValue("S{$contador1}",$f->serie."-".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador1}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador1}","COBRO POR ".$f->nombre_plan);

					$this->excel->getActiveSheet()->setCellValue("B{$contador2}","04");
					$this->excel->getActiveSheet()->setCellValue("C{$contador2}",$f->mes."".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador2}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador2}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador2}","COBRO POR ".$f->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador2}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador2}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador2}","401111");
					$this->excel->getActiveSheet()->setCellValue("L{$contador2}",$f->numero_documento_cli);
					$this->excel->getActiveSheet()->setCellValue("M{$contador2}",$f->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador2}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador2}",$f->igv);
					$this->excel->getActiveSheet()->setCellValue("R{$contador2}","01");
					$this->excel->getActiveSheet()->setCellValue("S{$contador2}",$f->serie."-".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador2}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador2}","COBRO POR ".$f->nombre_plan);

					$this->excel->getActiveSheet()->setCellValue("B{$contador3}","04");
					$this->excel->getActiveSheet()->setCellValue("C{$contador3}",$f->mes."".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("D{$contador3}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("E{$contador3}","MN");
					$this->excel->getActiveSheet()->setCellValue("F{$contador3}","COBRO POR ".$f->nombre_plan);
					$this->excel->getActiveSheet()->setCellValue("H{$contador3}","V");
					$this->excel->getActiveSheet()->setCellValue("I{$contador3}","S");
					$this->excel->getActiveSheet()->setCellValue("K{$contador3}","704101");
					$this->excel->getActiveSheet()->setCellValue("L{$contador3}",$f->numero_documento_cli);
					$this->excel->getActiveSheet()->setCellValue("M{$contador3}",$f->centro_costo);
					$this->excel->getActiveSheet()->setCellValue("N{$contador3}","H");
					$this->excel->getActiveSheet()->setCellValue("O{$contador3}",$f->neto);
					$this->excel->getActiveSheet()->setCellValue("R{$contador3}","01");
					$this->excel->getActiveSheet()->setCellValue("S{$contador3}",$f->serie."-".$f->correlativo);
					$this->excel->getActiveSheet()->setCellValue("T{$contador3}",$formatoFecha);
					$this->excel->getActiveSheet()->setCellValue("W{$contador3}","COBRO POR ".$f->nombre_plan);

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

		die(json_encode($response));
	}*/

	public function generarXmlNotaCredito(){

		include ('./application/libraries/xmldsig/src/XMLSecurityDSig.php');
    	include ('./application/libraries/xmldsig/src/XMLSecurityKey.php');
    	include ('./application/libraries/xmldsig/src/Sunat/SignedXml.php');
    	include ('./application/libraries/CustomHeaders.php');

    	$this->zip = new ZipArchive();

    	$this->xml = new XMLWriter();

    	//$numSerie = $this->input->post('numSerie');
    	//$idPlan = $this->input->post('nameCheck');
    	$canales = $this->input->post('canales');
    	$fecinicio = $this->input->post('fechainicio');
    	$fecfin = $this->input->post('fechafin');

		if ($canales == 'BC01') {
			$notaCredito = $this->comprobante_pago_mdl->getDatosXmlNotasBoleta($fecinicio, $fecfin, $canales);

			foreach ($notaCredito as $nc) {
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
        <cbc:ID schemeID="1" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$nc->cont_numDoc.'</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>'.$nc->contratante.'</cbc:RegistrationName>
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
    <cbc:CreditedQuantity unitCode="NIU">100</cbc:CreditedQuantity>
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
      <cbc:Description>'.$nc->sustento_nota.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$nc->total.'</cbc:PriceAmount>
    </cac:Price>
  </cac:CreditNoteLine>
</CreditNote>';

				$nameDoc=$nc->serie."-".$nc->correlativo;
				$filecdr=$nc->mesanio.'-cdrNotaCreditoBoleta';
				$fileNota=$nc->mesanio.'-NotaCreditoBoleta';
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
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 
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

				$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 

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
					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nc->fecha_emision, $nc->corre, $nc->serie);
				}

			}

		} elseif ($canales == 'FC01') {

			$notaCredito = $this->comprobante_pago_mdl->getDatosXmlNotasFactura($fecinicio, $fecfin, $canales);
				
			foreach ($notaCredito as $nc) {
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
    <cbc:CreditedQuantity unitCode="NIU">100</cbc:CreditedQuantity>
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
      <cbc:Description>'.$nc->sustento_nota.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$nc->total.'</cbc:PriceAmount>
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
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 
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

				$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 

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
					$this->comprobante_pago_mdl->updateEstadoCobroEmitido($nc->fecha_emision, $nc->corre, $nc->serie);
				}

			}


		} elseif ($canales == 'BD01') {
			$notaDebito = $this->comprobante_pago_mdl->getDatosXmlNotasBoleta($fecinicio, $fecfin, $canales);

			foreach ($notaDebito as $nd) {
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
      <cbc:Description>'.$nd->sustento_nota.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$nd->total.'</cbc:PriceAmount>
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
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 
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

				$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 

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
		} elseif ($canales == 'FD01') {
			$notaDebito = $this->comprobante_pago_mdl->getDatosXmlNotasFactura($fecinicio, $fecfin, $canales);

			foreach ($notaDebito as $nd) {
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
      <cbc:Description>'.$nd->sustento_nota.'</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">'.$nd->total.'</cbc:PriceAmount>
    </cac:Price>
  </cac:DebitNoteLine>
</DebitNote>';

				$nameDoc=$nd->serie."-".$nd->correlativo;
				$filecdr=$nd->mesanio.'-cdrNotaDebitoFactura';
				$fileNota=$nd->mesanio.'-NotaDebitoFactura';
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
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 
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

				$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 

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
	}
}