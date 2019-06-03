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
			$canales = $this->comprobante_pago_mdl->getSerieVerificar();
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

		$canales=$_POST['canales'];

		$planes = $this->comprobante_pago_mdl->getPlanes($canales);

		foreach ($planes as $p) {
			$data['numeroSerie'] = $p->numero_serie;
		}

		echo json_encode($data);
	}

	/*public function generarLista(){

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
				/*$html .= "<input class='form-check-input' type='checkbox' name='nameCheck[]' id='". $p->idplan ."' value='". $p->idplan ."'>";
				$html .= "<label class='form-check-label right' for='". $p->idplan ."'>". $p->nombre_comercial_cli ." - ". $p->nombre_plan."</label>";*/
				/*$html .= "<input type='text' class='hidden' id='numeroSerie' name='numeroSerie' value='".$p->numero_serie."'>";
				$html .= "</div>";
			endforeach;
		$html .= "</div>";
		$html .= "<hr>";

		echo json_encode($html);
	}*/

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
		//$data['nameCheck'] = $_POST['nameCheck'];
		//$idPlan = $data['nameCheck'];


		if (substr($canales, 0, 1) == 'B') {

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
							$html .="<th>Estado</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

						$boleta = $this->comprobante_pago_mdl->getDatosBoletaAnulacion($inicio, $fin, $canales);

						foreach ((array) $boleta as $b):

							/*$nameDoc=$b->serie."-".$b->correlativo;

							if ($canales == 'BD01') {

								$filename='20600258894-08-'.$b->serie.'-'.$b->correlativo;
								$filecdr=$b->mesanio.'-cdrNotaDebitoBoleta';
								$carpetaCdr = 'adjunto/xml/notasdedebito/'.$filecdr;

							} elseif ($canales == 'BC01') {

								$filename='20600258894-07-'.$b->serie.'-'.$b->correlativo;
								$filecdr=$b->mesanio.'-cdrNotaCreditoBoleta';
								$carpetaCdr = 'adjunto/xml/notasdecredito/'.$filecdr;

							} else {

								$filename='20600258894-03-'.$b->serie.'-'.$b->correlativo;
								$filecdr=$b->mesanio.'-cdrboletas';
								$carpetaCdr = 'adjunto/xml/boletas/'.$filecdr;

							}

							if (file_exists($carpetaCdr.'/R-'.$filename.'.xml')) {
								$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
								$DOM = new DOMDocument('1.0', 'utf-8');
								$DOM->loadXML($xml);
								$respuesta = $DOM->getElementsByTagName('Description');

								foreach ($respuesta as $r) {
									$descripcion = $r->nodeValue;
								}
							}*/

							$importe = $b->importe_total;
							$importe2=number_format((float)$importe, 2, '.', ',');

							$html .= "<tr>";
								$html .= "<td align='left'>".$b->fecha_emision."</td>";
								$html .= "<td align='left'>".$b->serie." - ".$b->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->serie."'></td>";
								$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value='".$b->idcomprobante."'></td>";
								$html .= "<td align='left'>".$b->cont_numDoc."</td>";
								$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
								$html .= "<td align='center'>S/. ".$importe2."</td>";
								if ($b->idestadocobro==3) {
									$html .= "<td align='left' class='danger'>Comprobante de pago emitido.</td>";
								} elseif ($b->idestadocobro==4) {
									$html .= "<td align='left' class='warning'>Comprobante de pago anulado.</td>";
								}
									
							$html .= "</tr>";

						endforeach;

					//}
				
				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";

		} elseif (substr($canales, 0, 1) == 'F') {

			//$html .="<hr>";
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
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

						$factura = $this->comprobante_pago_mdl->getDatosFacturaAnulacion($inicio, $fin, $canales);

						foreach ((array)$factura as $f):

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
									if ($b->idestadocobro==3) {
										$html .= "<td align='left' class='danger'>Comprobante de pago emitido.</td>";
									} elseif ($b->idestadocobro==4) {
										$html .= "<td align='left' class='warning'>Comprobante de pago anulado.</td>";
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

	public function crearXmlAnulaciones(){

    	include ('./application/libraries/xmldsig/src/XMLSecurityDSig.php');
    	include ('./application/libraries/xmldsig/src/XMLSecurityKey.php');
    	include ('./application/libraries/xmldsig/src/Sunat/SignedXml.php');
    	include ('./application/libraries/CustomHeaders.php');
    	//include ('./application/libraries/PhpZip/ZipFile.php');
        //$this->load->library('zip/zip');
    	$this->xml = new XMLWriter();

    	$numSerie = $this->input->post('numSerie');
    	$canales = $this->input->post('canales');
    	$fecinicio = $this->input->post('fechainicio');
    	$fecfin = $this->input->post('fechafin');
    	$numSerieUno = reset($numSerie);

    	if (substr($numSerieUno, 0, 2) == 'B0') {
    		//$numSerieUno = reset($numSerie);
    		$boletas = $this->comprobante_pago_mdl->getDatosXmlBoletasAnulacionAgrupadas($fecinicio, $fecfin, $numSerieUno);

	    	foreach ($boletas as $ba){

	    		$linea=1;

	    		$numeroConCeros = str_pad($ba->correlativo, 5, "0", STR_PAD_LEFT);
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
			$boletas = $this->comprobante_pago_mdl->getDatosXmlBoletasAnulacion($fecinicio, $fecfin, $ba->nume_corre_res, $numSerieUno);
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
			<cbc:ConditionCode>3</cbc:ConditionCode>
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

				$nameDoc='RC-'.$fechanombre.'-'.$ba->correlativo;
				$filecdr=$ba->mesanio.'-cdranulaciones'.$ba->serie;
				$fileBoleta=$ba->mesanio.'-anulaciones'.$ba->serie;
				$carpetaCdr = 'adjunto/xml/anulaciones/'.$filecdr;
		    	$carpetaBoleta = 'adjunto/xml/anulaciones/'.$fileBoleta;
				if (!file_exists($carpetaCdr)) {
				    mkdir($carpetaCdr, 0777, true);
				}
				
				if (!file_exists($carpetaBoleta)) {
				    mkdir($carpetaBoleta, 0777, true);
				}
				$doc = new DOMDocument();
				$doc->loadxml($datos);
				$doc->save('adjunto/xml/anulaciones/'.$fileBoleta.'/'.$filename.'.xml');
				$xmlPath = 'adjunto/xml/anulaciones/'.$fileBoleta.'/'.$filename.'.xml';
				$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 
				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);
				$xmlSigned = $signer->signFromFile($xmlPath);
				file_put_contents($filename.'.xml', $xmlSigned);
				//echo $xmlSigned;
			    
			    $this->load->library('zip');

			    $this->zip->add_data($filename.'.xml', $xmlSigned);
				$this->zip->archive('adjunto/xml/anulaciones/'.$fileBoleta.'/'.$filename.'.zip');
				$this->zip->clear_data();

				unlink($filename.".xml");
				unlink($carpetaBoleta.'/'.$filename.".xml");

				$service = 'adjunto/wsdl/billService.wsdl'; 
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
		    		'contentFile' => file_get_contents('adjunto/xml/anulaciones/'.$fileBoleta.'/'.$zipXml) 
		    	); 

		    	$client->sendSummary($params);
		    	$status = $client->__getLastResponse();
		    	
		    	$carpeta = 'adjunto/xml/anulaciones/'.$filecdr;
				if (!file_exists($carpeta)) {
				    mkdir($carpeta, 0777, true);
				}

		    	//Descargamos el Archivo Response
				$archivo = fopen('adjunto/xml/anulaciones/'.$filecdr.'/'.'C'.$filename.'.xml','w+');
				fputs($archivo, $status);
				fclose($archivo);
				//LEEMOS EL ARCHIVO XML
				$responsexml = simplexml_load_file('adjunto/xml/anulaciones/'.$filecdr.'/'.'C'.$filename.'.xml');

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
				$archivo = fopen('adjunto/xml/anulaciones/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
				fputs($archivo,$contenido['content']);
				fclose($archivo);

				$archivo2 = chmod('adjunto/xml/anulaciones/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);

				unlink('adjunto/xml/anulaciones/'.$filecdr.'/'.'C'.$filename.'.xml');

				//$this->comprobante_pago_mdl->updateEstadoCobroAnulacion($ba->fecha_emision, $ba->corre, $ba->serie);

				
			}

    	} elseif (substr($numSerieUno, 0, 2) == 'BC') {

			$notaCredito = $this->comprobante_pago_mdl->getDatosXmlBoletasAnulacionAgrupadas($fecinicio, $numSerieUno);

			foreach ($notaCredito as $nc) {

				$numeroConCeros = str_pad($nc->corre, 5, "0", STR_PAD_LEFT);
	    		$fechanombre = str_replace ("-" , "", $nc->fecha_emision);
				$idestadocobro = $nc->idestadocobro;

				$filename="20600258894-RC-".$fechanombre."-".$nc->nume_corre_res;

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
			$boletas = $this->comprobante_pago_mdl->getDatosXmlBoletasAnulacion($fecinicio, $ba->nume_corre_res, $numSerieUno);
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
			<cbc:ConditionCode>3</cbc:ConditionCode>
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


				$nameDoc='RC-'.$fechanombre.'-'.$nc->correlativo;
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
				

				$this->comprobante_pago_mdl->updateEstadoCobroAnulacion($nc->fecha_emision, $nc->corre, $nc->serie);

			}

    	} /*elseif (substr($numSerieUno, 0, 2) == 'F0') {

    		//for ($i=0; $i < count($idPlan); $i++) {
	    		
		    	$facturas = $this->comprobante_pago_mdl->getDatosXmlFacturas($fecinicio, $fecfin, $numSerie);
		    	

		    	foreach ($facturas as $f){

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
    <cbc:TaxAmount currencyID="PEN">'.$f->igv.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">'.$f->neto.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">'.$f->igv.'</cbc:TaxAmount>
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
    <cbc:PayableAmount currencyID="PEN">'.$f->total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">'.$f->neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">'.$f->total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">'.$f->igv.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">'.$f->neto.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">'.$f->igv.'</cbc:TaxAmount>
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
      <cbc:PriceAmount currencyID="PEN">'.$f->neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';
					$nameDoc=$f->serie."-".$f->correlativo;
					$filecdr=$f->mesanio.'-cdranulacionesF'.$f->serie;
					$fileFactura=$f->mesanio.'-anulacionesF'.$f->serie;
					$carpetaCdr = 'adjunto/xml/anulacionesF/'.$filecdr;
			    	$carpetaFactura = 'adjunto/xml/anulacionesF/'.$fileFactura;

					if (!file_exists($carpetaCdr)) {
					    mkdir($carpetaCdr, 0777, true);
					}
					
					if (!file_exists($carpetaFactura)) {
					    mkdir($carpetaFactura, 0777, true);
					}

					$doc = new DOMDocument(); 
					$doc->loadxml($datos);
					$doc->save('adjunto/xml/anulacionesF/'.$fileFactura.'/'.$filename.'.xml');

					$xmlPath = 'adjunto/xml/anulacionesF/'.$fileFactura.'/'.$filename.'.xml';
					$certPath = 'adjunto/firma/C1811152013.pem'; // Convertir pfx to pem 

					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);

					$xmlSigned = $signer->signFromFile($xmlPath);

					file_put_contents($filename.'.xml', $xmlSigned);
					//echo $xmlSigned;

					 $this->load->library('zip');

				    $this->zip->add_data($filename.'.xml', $xmlSigned);
					$this->zip->archive('adjunto/xml/anulacionesF/'.$fileFactura.'/'.$filename.'.zip');
					$this->zip->clear_data();

					unlink($filename.".xml");
					unlink('adjunto/xml/anulacionesF/'.$fileFactura.'/'.$filename.'.xml');

					//$service = 'adjunto/wsdl/billService.wsdl'; 
					$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
					
			    	$headers = new CustomHeaders('20600258894MODDATOS', 'MODDATOS');
			    	//$headers = new CustomHeaders('20600258894DCACEDA2', 'DCACE716186'); 

			    	
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
			    		'contentFile' => file_get_contents('adjunto/xml/anulacionesF/'.$fileFactura.'/'.$zipXml) 
			    	); 

			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();
			    	
			    	$carpeta = 'adjunto/xml/anulacionesF/'.$filecdr;
					if (!file_exists($carpeta)) {
					    mkdir($carpeta, 0777, true);
					}

			    	//Descargamos el Archivo Response
					$archivo = fopen('adjunto/xml/anulacionesF/'.$filecdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);
					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file('adjunto/xml/anulacionesF/'.$filecdr.'/'.'C'.$filename.'.xml');
					
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }
					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen('adjunto/xml/anulacionesF/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);

					$archivo2 = chmod('adjunto/xml/anulacionesF/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);
	
					unlink('adjunto/xml/anulacionesF/'.$filecdr.'/'.'C'.$filename.'.xml');

					//verificar respuesta SUNAT

					if ($descripcion == 'La Factura numero '.$nameDoc.', ha sido aceptada') {
						$this->comprobante_pago_mdl->updateEstadoCobroEmitido($f->fecha_emision, $f->corre, $f->serie);
					//}
				}
			//}
    	}*/ 
    }

}