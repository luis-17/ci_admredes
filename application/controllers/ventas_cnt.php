<?php
ini_set('max_execution_time', 6000); 
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
date_default_timezone_set('America/Lima');
defined('BASEPATH') OR exit('No direct script access allowed');
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Greenter\XMLSecLibs\Sunat\SignedXml;

class ventas_cnt extends CI_Controller {

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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

			$data['idclienteempresa'] = 0;

			$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;

			$canales = $this->comprobante_pago_mdl->getCanales();
			$data['canales'] = $canales;

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

		$month = date('m');
      	$year = date('Y');
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$fecinicio=$data['fecinicio'];
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
		$fecfin=$data['fecfin'];
		$html=null;
		$serie=$_POST['documento'];

		$correlativo=$this->comprobante_pago_mdl->getUltimoCorrelativo($fecinicio, $fecfin, $serie);

		foreach ($correlativo as $c):

			$html.="<input class='form-control' name='correlativoActual' type='text' value='".$c->correlativo."' id='correlativoActual' readonly>";

		endforeach;			

		echo json_encode($html);
	}

	

	public function mostrarDatos(){
		
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

		//$data['nameCheck'] = $_POST['nameCheck'];
		//$plan = $data['nameCheck'];

		$data['documento'] = $_POST['documento'];
		$serie = $data['documento'];

		$idserie=$_POST['documento'];

		if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {

			//html de tabla dinámica que se va a generar
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

						$boletaSuma = $this->comprobante_pago_mdl->getDatosSumaBoleta($inicio, $fin, $canales, $serie);
						foreach ($boletaSuma as $bs):
							$suma=$bs->suma;
							$sumaDos=number_format((float)$suma, 2, '.', ',');
							$html .="<tr>";
								$html .="<td colspan=5 align='left'><b>Total de cobro Boletas: </b></td>";
								$html .="<td colspan=2 align='right'><b>S/. ".$bs->suma."</b></td>";
							$html .="</tr>";
						endforeach;

						$boleta = $this->comprobante_pago_mdl->getDatosBoleta($inicio, $fin, $canales, $serie);
						foreach ((array) $boleta as $b):

							$importe = $b->cob_importe;
							$importe = $importe/100;
							$importe2=number_format((float)$importe, 2, '.', '');

							$html .= "<tr>";
								$html .= "<td align='left'>".$b->cob_fechCob."<input type='text' class='hidden' id='fechaEmi' name='fechaEmi[]' value='".$b->cob_fechCob."'></td>";

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

		} elseif ($canales == 4) {

			$html .= "<div  align='center' class='col-xs-12'>";
				$html .= "<table align='center' id='tablaDatos' class='table table-striped table-bordered table-hover'>";
					$html .= "<thead>";
						$html .="<tr>";
							$html .="<th>Fecha para emisión</th>";
							$html .="<th>Razon Social</th>";
							$html .="<th>Nombre Comercial</th>";
							$html .="<th>RUC</th>";
							$html .="<th>Plan</th>";
							$html .="<th>Importe (S/.)</th>";
							$html .="<th>Cantidad</th>";
							$html .="<th>Estado</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

						$facturaSuma = $this->comprobante_pago_mdl->getDatosSumaFacturas($inicio, $fin, $canales, $serie);
						foreach ($facturaSuma as $fs):
							$suma=$fs->suma;
							$sumaDos=number_format((float)$suma, 2, '.', ',');
							$html .= "<tr>";
								$html .= "<td colspan=6 align='left'><b>Total de cobros de Facturas: </b></td>";
								$html .= "<td colspan=2 align='right'><b>S/. ".$sumaDos."</b></td>";
							$html .= "</tr>";
						endforeach;

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
		

		if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {
			
			$cobro = $_POST['cobro'];
			$fechaEmi = $_POST['fechaEmi'];
			$idContratante = $_POST['contratante'];

			$idTipoDoc = 3;

			//for para recorrer los datos de la tabla y hacer el insert en la bd
			for ($i=0; $i < count($cobro); $i++) {

				$correlativo = $correlativo+1;
				$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $fechaEmi[$i], $serie[$i], $correlativo, $idContratante[$i], $importeTotal[$i], $cobro[$i], $idPlan[$i]);
			}

		} elseif ($canales == 4) {

			$fechaEmi = $_POST['fechaEmi'];
			$idEmpresa = $_POST['empresa'];

			$idTipoDoc = 3;

			//for para recorrer los datos de la tablay hacer el insert en la bd
			for ($i=0; $i < count($idEmpresa); $i++) {

				$correlativo = $correlativo+1;
				$this->comprobante_pago_mdl->insertDatosFacturas($inicio, $fin, $fechaEmi[$i], $serie[$i], $correlativo, $idEmpresa[$i], $importeTotal[$i], $idPlan[$i]);
			}
		}

		//for para recorrer los planes obtenidos de la vista y hacer update del idestadocobro
		for ($i=0; $i < count(array_unique($idPlan)); $i++) { 
			
			$this->comprobante_pago_mdl->updateEstadoCobro($inicio, $fin, $idPlan[$i]);

		}
	}

//----------------------------------------------------------------------------------------------------------------------

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

	public function mostrarDatosComprobantes(){

		//se declara variable donde se va a generar tabla
		$html = null;		

		$month = date('m');
      	$year = date('Y');
      	$day = date("d");

		$data['fechaEmision'] = date('Y-m-d');
		//date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$fecemi = $data['fechaEmision'];

		$canales=$_POST['canalesDos'];
		$datos['canalesDos'] = $canales;

		$inicio = $_POST['fechainicioDos'];
		$fin = $_POST['fechafinDos'];

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
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					for ($i=0; $i < count($idPlan); $i++) {

							$boleta = $this->comprobante_pago_mdl->getDatosBoletaEmitida($inicio, $fin, $serie, $idPlan[$i]);

						foreach ((array) $boleta as $b):

							$importe = $b->importe_total;
							$importe2=number_format((float)$importe, 2, '.', ',');

							$estado = $b->idestadocobro;

							$html .= "<tr>";
								$html .= "<td align='left'>".$b->fecha_emision."</td>";
								$html .= "<td align='left'>".$b->serie." - ".$b->correlativo."<input type='text' class='hidden' id='numSerie' name='numSerie[]' value='".$b->serie."'></td>";
								$html .= "<td align='left'>".$b->contratante."<input type='text' class='hidden' id='idcomprobante' name='idcomprobante[]' value='".$b->idcomprobante."'></td>";
								$html .= "<td align='left'>".$b->cont_numDoc."</td>";
								$html .= "<td align='left'>".utf8_decode($b->nombre_plan)."</td>";
								$html .= "<td align='center'>S/. ".$importe2."</td>";
								if ($estado == 2) {
									$html .= "<td align='center' class='danger'>".$b->descripcion."</td>";
								} elseif ($estado == 3) {
									$html .= "<td align='center' class='success'>".$b->descripcion."</td>";
								}
								$html .= "<td align='left'>";
									$html .= "<ul class='ico-stack'>";
										$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
											$html .="<a class='boton fancybox' href='".base_url()."ventas_cnt/generarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
												$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
											$html .="</a>";
										$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."ventas_cnt/enviarPdf/".$b->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
									$html .= "</ul>";
								$html .="</td>";
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
							$html .="<th>Estado</th>";
							$html .="<th>Opciones</th>";
						$html .="</tr>";
					$html .= "</thead>";
					$html .= "<tbody>";

					for ($i=0; $i < count($idPlan); $i++) {

							$factura = $this->comprobante_pago_mdl->getDatosFacturaEmitida($inicio, $fin, $serie, $idPlan[$i]);

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
									if ($estado == 2) {
										$html .= "<td align='center' class='danger'>".$f->descripcion."</td>";
									} elseif ($estado == 3) {
										$html .= "<td align='center' class='success'>".$f->descripcion."</td>";
									}
									$html .= "<td align='left'>";
										$html .= "<ul class='ico-stack'>";
											$html .="<div title='ver PDF' id='pdfButton' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."ventas_cnt/generarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='950' data-fancybox-height='800' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-file-pdf-o bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
											$html .="<div title='enviar PDF' id='pdfButtonEnviar' onclick=''>";
												$html .="<a class='boton fancybox' href='".base_url()."ventas_cnt/enviarPdf/".$f->idcomprobante."/".$canales."' data-fancybox-width='750' data-fancybox-height='275' target='_blank'>";
													$html .= "<i class='ace-icon fa fa-envelope bigger-120'></i>";
												$html .="</a>";
											$html .="</div>";
										$html .= "</ul>";
									$html .="</td>";
								$html .= "</tr>";

						endforeach;
					
					}

				$html .= "</tbody>";
			$html .= "</table>";
		$html .= "</div>";
		}

		echo json_encode($html);
	} 

	public function generarArchivoDbf(){

		$mail = new PHPMailer();

		$inicio=$_POST['fechainicioDos'];
		$fin=$_POST['fechafinDos'];

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
		$anexos = $this->comprobante_pago_mdl->getDatosContratante($inicio, $fin);

		foreach ($anexos as $a) {
			dbase_add_record($db, array('C', $a->cont_numDoc, $a->nombre, $a->cont_direcc, '', '', 'V', '', '', 'S', 0));
		}

		dbase_close($db);

		$mail->IsSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host       = "smtpout.secureserver.net";
        $mail->Port       = 465; 
        $mail->Username   = "dcaceda@red-salud.com"; 
        $mail->Password   = "redsalud2018caceda"; 
        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
        $mail->Subject    = "Archivos DBF";
        $mail->Body 	  = "Se adjunta archivo DBF. <br>";
        $mail->AltBody    = "Se adjunta archivo DBF.";
        $mail->AddAddress('dcaceda@red-salud.com');

       	$mail->AddAttachment("adjunto/dbf/CAN03.dbf", "CAN03.dbf");

        $estadoEnvio = $mail->Send(); 

        //unlink('adjunto/dbf/anexo.dbf');
		//header("Content-disposition: attachment; filename=anexos.dbf");
		//header("Content-type: MIME");
	}

	public function generarExcel(){

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
	}

	public function generarPdf($idcomprobante, $canales){

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
	}

	public function enviarPdf($idcomprobante, $canalesDos){

		$data['idcomprobante'] = $idcomprobante;
		$data['canalesDos'] = $canalesDos;

		$this->load->view('dsb/html/comprobante/enviar_pdf.php', $data);
	}

	public function envioEmail(){

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
	    //$this->pdf->Image(base_url().'/public/assets/avatars/user.jpg','0','0','150','150','JPG');

	    if ($canalesDos == 1 || $canalesDos == 2 || $canalesDos == 3 || $canalesDos == 6 || $canalesDos == 7) {
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

		        $this->pdf->Output("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf", 'F');

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
    <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">'.$b->neto.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
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
    <cbc:PayableAmount currencyID="PEN">'.$b->total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">'.$b->neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">'.$b->total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">'.$b->neto.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
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
      <cbc:PriceAmount currencyID="PEN">'.$b->neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';

				$doc = new DOMDocument(); 
				$doc->loadxml($datos);
				$doc->save('adjunto/comprobantes/'.$filename.'.xml');

				$xmlPath = 'adjunto/comprobantes/'.$filename.'.xml';
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 

				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);

				$xmlSigned = $signer->signFromFile($xmlPath);

				file_put_contents('adjunto/comprobantes/'.$filename.'.xml', $xmlSigned);

		     	$mail->IsSMTP();
		        $mail->SMTPAuth   = true; 
		        $mail->SMTPSecure = "ssl"; 
		        $mail->Host       = "smtpout.secureserver.net"; 
		        $mail->Port       = 465; 
		        $mail->Username   = "dcaceda@red-salud.com"; 
		        $mail->Password   = "redsalud2018caceda"; 
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Comprobante de pago";
		        $mail->Body 	  = "Se adjunta boleta de venta. <br>";
		        $mail->AltBody    = "Se adjunta boleta de venta.";
		        $mail->AddAddress($email);

		       	$mail->AddAttachment("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf", $b->mes."".$b->serie."".$b->correlativo.".pdf", 'base64', 'application/pdf');
		       	$mail->AddAttachment("adjunto/comprobantes/".$filename.".xml", $filename.".xml", 'base64', 'application/xml');

		        $estadoEnvio = $mail->Send(); 
				/*if($estadoEnvio){
				    echo"El correo fue enviado correctamente.";
				} else {
				    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
				}*/

			}

		    unlink("adjunto/comprobantes/".$b->mes."".$b->serie."".$b->correlativo.".pdf");
			unlink("adjunto/comprobantes/".$filename.".xml");
	    
	    } elseif ($canalesDos == 4) {
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
  <cbc:Note languageLocaleID="1000">SETENTA Y UN MIL TRESCIENTOS CINCUENTICUATRO Y 99/100</cbc:Note>
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
      <cbc:TaxableAmount currencyID="PEN">'.$f->total.'</cbc:TaxableAmount>
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
        <cbc:TaxableAmount currencyID="PEN">'.$f->total.'</cbc:TaxableAmount>
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
				$doc = new DOMDocument(); 
				$doc->loadxml($datos);
				$doc->save('adjunto/comprobantes/'.$filename.'.xml');

				$xmlPath = 'adjunto/comprobantes/'.$filename.'.xml';
				$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 

				$signer = new SignedXml();
				$signer->setCertificateFromFile($certPath);

				$xmlSigned = $signer->signFromFile($xmlPath);

				file_put_contents('adjunto/comprobantes/'.$filename.'.xml', $xmlSigned);

		     	$mail->IsSMTP();
		        $mail->SMTPAuth   = true;
		        $mail->SMTPSecure = "ssl";
		        $mail->Host       = "smtpout.secureserver.net";
		        $mail->Port       = 465; 
		        $mail->Username   = "dcaceda@red-salud.com"; 
		        $mail->Password   = "redsalud2018caceda"; 
		        $mail->SetFrom('dcaceda@red-salud.com', utf8_decode('RED SALUD'));
		        $mail->AddReplyTo('dcaceda@red-salud.com', utf8_decode('RED SALUD')); 
		        $mail->Subject    = "Comprobante de pago";
		        $mail->Body 	  = "Se adjunta factura de venta. <br>";
		        $mail->AltBody    = "Se adjunta factura de venta.";
		        $mail->AddAddress($email);

		       	$mail->AddAttachment("adjunto/comprobantes/".$f->mes."".$f->serie."".$f->correlativo.".pdf", $f->mes."".$f->serie."".$f->correlativo.".pdf", 'base64', 'application/pdf');
		       	$mail->AddAttachment("adjunto/comprobantes/".$filename.".xml", $filename.".xml", 'base64', 'application/xml');

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

    	$this->zip = new ZipArchive();

    	$this->xml = new XMLWriter();

    	$numSerie = $this->input->post('numSerie');
    	$idPlan = $this->input->post('nameCheck');
    	$canales = $this->input->post('canalesDos');
    	$fecinicio = $this->input->post('fechainicioDos');
    	$fecfin = $this->input->post('fechafinDos');

    	if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {

    		for ($i=0; $i < count($idPlan); $i++) {

		    	$boletas = $this->comprobante_pago_mdl->getDatosXmlBoletas($fecinicio, $fecfin, $numSerie[$i], $idPlan[$i]);

		    	foreach ($boletas as $b){

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
    <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">'.$b->neto.'</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
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
    <cbc:PayableAmount currencyID="PEN">'.$b->total.'</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="ZZ" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">1</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">'.$b->neto.'</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">'.$b->total.'</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">'.$b->neto.'</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">'.$b->igv.'</cbc:TaxAmount>
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
      <cbc:PriceAmount currencyID="PEN">'.$b->neto.'</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';
					$nameDoc=$b->serie."-".$b->correlativo;
					$filecdr=$b->mesanio.'-cdrboletas';
					$fileBoleta=$b->mesanio.'-boletas';
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
					$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 

					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);

					$xmlSigned = $signer->signFromFile($xmlPath);

					file_put_contents($filename.'.xml', $xmlSigned);
					//echo $xmlSigned;

					if ($this->zip->open("adjunto/xml/boletas/".$fileBoleta.'/'.$filename.".zip", ZIPARCHIVE::CREATE)===true) {
						$this->zip->addFile($filename.'.xml');
						$this->zip->close();
						unlink($filename.".xml");
						unlink("adjunto/xml/boletas/".$fileBoleta.'/'.$filename.".xml");
					} else {
						unlink($filename.".xml");
						unlink("adjunto/xml/boletas/".$fileBoleta.'/'.$filename.".xml");
					}

					$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl'; 
			    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 
			    	
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
			    		'contentFile' => file_get_contents('adjunto/xml/boletas/'.$fileBoleta.'/'.$zipXml) 
			    	); 

			    	$client->sendBill($params);
			    	$status = $client->__getLastResponse();

			    	//Descargamos el Archivo Response
					$archivo = fopen('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml','w+');
					fputs($archivo, $status);
					fclose($archivo);
					//LEEMOS EL ARCHIVO XML
					$responsexml = simplexml_load_file('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml');
					//print_r($responsexml);
					//exit();
					foreach ($responsexml->xpath('//applicationResponse') as $response){ }
					//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
					$cdr=base64_decode($response);
					$archivo = fopen('adjunto/xml/boletas/'.$filecdr.'/'.'R-'.$filename.'.zip','w+');
					fputs($archivo,$cdr);
					fclose($archivo);

					$archivo2 = chmod('adjunto/xml/boletas/'.$filecdr.'/'.'R-'.$filename.'.zip', 0777);
	
					unlink('adjunto/xml/boletas/'.$filecdr.'/'.'C'.$filename.'.xml');

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

					if ($descripcion == 'La Boleta numero '.$nameDoc.', ha sido aceptada') {
						$this->comprobante_pago_mdl->updateEstadoCobroEmitido($b->fecha_emision, $b->corre, $b->serie);
					}
					
				}
			}

    	} elseif ($canales == 4) {

    		for ($i=0; $i < count($idPlan); $i++) {
	    		
		    	$facturas = $this->comprobante_pago_mdl->getDatosXmlFacturas($fecinicio, $fecfin, $numSerie, $idPlan[$i]);
		    	

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
  <cbc:Note languageLocaleID="1000">SETENTA Y UN MIL TRESCIENTOS CINCUENTICUATRO Y 99/100</cbc:Note>
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
					$filecdr=$f->mesanio.'-cdrfacturas';
					$fileFactura=$f->mesanio.'-facturas';
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
					$certPath = 'adjunto/firma/LLAMA-PE-CERTIFICADO-DEMO-20600258894.pem'; // Convertir pfx to pem 

					$signer = new SignedXml();
					$signer->setCertificateFromFile($certPath);

					$xmlSigned = $signer->signFromFile($xmlPath);

					file_put_contents($filename.'.xml', $xmlSigned);
					//echo $xmlSigned;

					if ($this->zip->open('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.zip', ZIPARCHIVE::CREATE)===true) {
						$this->zip->addFile($filename.'.xml');
						$this->zip->close();
						unlink($filename.".xml");
						unlink('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml');
					} else {
						unlink($filename.'.xml');
						unlink('adjunto/xml/facturas/'.$fileFactura.'/'.$filename.'.xml');
					}

					$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl'; 
			    	$headers = new CustomHeaders('20600258894MODDATOS', 'moddatos'); 
			    	
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

					if ($descripcion == 'La Factura numero '.$nameDoc.', ha sido aceptada') {
						$this->comprobante_pago_mdl->updateEstadoCobroEmitido($f->fecha_emision, $f->corre, $f->serie);
					}
				}
			}
    	} 
    }

}