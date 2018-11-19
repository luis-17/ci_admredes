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
        $this->load->library('zip');

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
							$html .="<th>Mensaje Sunat</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

						$boleta = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $canales);

						foreach ((array) $boleta as $b):

							$nameDoc=$b->serie."-".$b->correlativo;

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
								if (file_exists($carpetaCdr.'/R-'.$filename.'.xml')) {
									if ($descripcion == 'La Boleta numero '.$nameDoc.', ha sido aceptada' || $descripcion == 'La Nota de Credito numero '.$nameDoc.', ha sido aceptada' || $descripcion == 'La Nota de Debito numero '.$nameDoc.', ha sido aceptada') {
										$html .= "<td align='left' class='success'>".$descripcion."</td>";
										$html .= "<td align='left'>";
											$html .= "<ul class='ico-stack'>";
												$html .="<div title='descargar CDR' id='pdfButton' onclick=''>";
													$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
														$html .= "<i class='ace-icon fa fa-file-code-o bigger-120'></i>";
													$html .="</a>";
												$html .="</div>";
											$html .= "</ul>";
										$html .="</td>";
									} else {
										$this->comprobante_pago_mdl->updateEstadocobroComprobante($b->idcomprobante);
										$html .= "<td align='left' class='success'>".$descripcion."</td>";
										$html .= "<td align='left'>";
											$html .= "<ul class='ico-stack'>";
												$html .="<div title='descargar CDR' id='pdfButton' onclick=''>";
													$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
														$html .= "<i class='ace-icon fa fa-file-code-o bigger-120'></i>";
													$html .="</a>";
												$html .="</div>";
											$html .= "</ul>";
										$html .="</td>";
									}
								} else {
									$html .= "<td align='left' class='danger'>No se ha emitido el comprobante de pago.</td>";
									$html .= "<td align='left'></td>";
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
							$html .="<th>Mensaje Sunat</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					//for ($i=0; $i < count($idPlan); $i++) {

						$factura = $this->comprobante_pago_mdl->getDatosFacturaEmitida($inicio, $fin, $canales);

						foreach ((array)$factura as $f):

							$nameDoc=$f->serie."-".$f->correlativo;
							$filename="20600258894-01-".$f->serie."-".$f->correlativo;
							$filecdr=$f->mesanio.'-cdrfacturas';
							$carpetaCdr = 'adjunto/xml/facturas/'.$filecdr;

							if (file_exists($carpetaCdr.'/R-'.$filename.'.xml')) {
								$xml = file_get_contents($carpetaCdr.'/R-'.$filename.'.xml');
								$DOM = new DOMDocument('1.0', 'utf-8');
								$DOM->loadXML($xml);
								$respuesta = $DOM->getElementsByTagName('Description');

								foreach ($respuesta as $r) {
									$descripcion = $r->nodeValue;
								}
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
									if (file_exists($carpetaCdr.'/R-'.$filename.'.xml')) {
										if ($descripcion == 'La Factura numero '.$nameDoc.', ha sido aceptada' || $descripcion == 'La Nota de Credito numero '.$nameDoc.', ha sido aceptada' || $descripcion == 'La Nota de Debito numero '.$nameDoc.', ha sido aceptada') {
											$html .= "<td align='left' class='success'>".$descripcion."</td>";
										$html .= "<td align='left'>";
											$html .= "<ul class='ico-stack'>";
												$html .="<div title='descargar CDR' id='pdfButton' onclick=''>";
													$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
														$html .= "<i class='ace-icon fa fa-file-code-o bigger-120'></i>";
													$html .="</a>";
												$html .="</div>";
											$html .= "</ul>";
										$html .="</td>";
										} else {
											$this->comprobante_pago_mdl->updateEstadocobroComprobante($f->idcomprobante);
											$html .= "<td align='left' class='danger'>".$descripcion."</td>";
										$html .= "<td align='left'>";
											$html .= "<ul class='ico-stack'>";
												$html .="<div title='descargar CDR' id='pdfButton' onclick=''>";
													$html .="<a class='boton fancybox' href='' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
														$html .= "<i class='ace-icon fa fa-file-code-o bigger-120'></i>";
													$html .="</a>";
												$html .="</div>";
											$html .= "</ul>";
										$html .="</td>";
										}
									} else {
										$html .= "<td align='left' class='warning'>No se ha emitido el comprobante de pago.</td>";
										$html .= "<td align='left'></td>";
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

	public function descargarCDR(){

		$this->zip = new ZipArchive();

		$canales=$_POST['canales'];

		$inicio = $_POST['fechainicio'];
		$fin = $_POST['fechafin'];

		$serie = $_POST['numSerie'];
		$serieP = array_values($serie)[0];
		//print_r(substr($canales, 1, 1));
		//exit();
		$mesanio = $this->comprobante_pago_mdl->getMesanio($inicio, $fin);

		foreach ($mesanio as $m) {

			$nombreArch = $m->mesanios;

			if (substr($canales, 0, 1) == 'B') {

				if (substr($canales, 1, 1) == '0') {
					$filecdr=$m->mesanios.'-cdrboletas';
					$ruta="adjunto/xml/boletas/".$filecdr;		

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true) {
						
						$boletasCdr = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serieP);

						foreach ($boletasCdr as $bc) {
							$nombre="R-20600258894-03-".$bc->serie."-".$bc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();

					}
				} elseif (substr($canales, 1, 1) == 'C') {
					$filecdr=$m->mesanios.'-cdrNotaCreditoBoleta';
					$ruta="adjunto/xml/notasdecredito/".$filecdr;

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true) {
						
						$boletasCdr = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serieP);

						foreach ($boletasCdr as $bc) {
							$nombre="R-20600258894-07-".$bc->serie."-".$bc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();
					}
				} elseif (substr($canales, 1, 1) == 'D') {
					$filecdr=$m->mesanios.'-cdrNotaDebitoBoleta';
					$ruta="adjunto/xml/notasdedebito/".$filecdr;

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true) {

						$boletasCdr = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serieP);

						foreach ($boletasCdr as $bc) {
							$nombre="R-20600258894-08-".$bc->serie."-".$bc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();
					}
				}

			} elseif (substr($canales, 0, 1) == 'F') {

				if (substr($canales, 1, 1) == '0') {
					$filecdr=$m->mesanio.'-cdrfacturas';
					$ruta="adjunto/xml/facturas/".$filecdr;

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true)
						$facturasCdr = $this->comprobante_pago_mdl->getDatosFacturaEmitida($inicio, $fin, $serieP); {

						foreach ($facturasCdr as $fc) {
							$nombre="R-20600258894-01-".$fc->serie."-".$fc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();
					} 

				} elseif (substr($canales, 1, 1) == 'C') {
					$filecdr=$m->mesanios.'-cdrNotaCreditoFactura';
					$ruta="adjunto/xml/notasdecredito/".$filecdr;

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true) {
						
						$facturasCdr = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serieP);

						foreach ($facturasCdr as $fc) {
							$nombre="R-20600258894-07-".$fc->serie."-".$fc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();
					}

				} elseif (substr($canales, 1, 1) == 'D') {
					$filecdr=$m->mesanios.'-cdrNotaDebitoFactura';
					$ruta="adjunto/xml/notasdedebito/".$filecdr;

					if ($this->zip->open($ruta."/cdr-".$nombreArch.".zip", ZIPARCHIVE::CREATE)===true) {

						$facturasCdr = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serieP);

						foreach ($facturasCdr as $fc) {
							$nombre="R-20600258894-08-".$fc->serie."-".$fc->correlativo;
							$this->zip->addFile($ruta.'/'.$nombre.'.xml', $nombre.'.xml');
						}

						$this->zip->close();
					}
				}
			}
		}
		$filePath = $ruta.'/cdr-'.$nombreArch.'.zip';
		$file = basename($filePath);
		//print_r($file);
		//exit();

        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.$file);
        //header('Content-Transfer-Encoding: binary');
        //header('Expires: 0');
        //header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //header('Pragma: public');
        header('Content-Length: '.$file);

        $data = readfile($file);
        echo json_encode($data);
	}

}