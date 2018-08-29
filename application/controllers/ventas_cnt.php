<?php
ini_set('max_execution_time', 6000); 
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
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
		$idusuario=2;

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
				$estp´= 'selected';
			} else {
				$estp='';
			}

			if ($canales == 0) {
				$html .= "<option id='numeroSerie' name='numeroSerie' value='0' ".$estp." >Seleccione</option>";
			} else {
				$html .= "<option id='numeroSerie' name='numeroSerie' value='".$c->numero_serie."' ".$estp." >".$c->descripcion_tdm."</option>";
			}
				//$html .="<input type='text' class='hidden' id='numeroSerieCor' name='numeroSerieCor' value='".$c->numero_serie."'>";
		endforeach;

		echo json_encode($html);
	}

	public function mostrarCorrelativo(){

		$html=null;
		$serie=$_POST['documento'];

		$correlativo=$this->comprobante_pago_mdl->getMostrarCorrelativo($serie);

		//print_r($correlativo);
		//exit();
		foreach ($correlativo as $c):
			if ($correlativo == 0) {
				$html.="<input class='form-control' name='correlativoActual' type='text'value='0' id='correlativoActual' disabled>";
			} else {
				$html.="<input class='form-control' name='correlativoActual' type='text'value='".$c->correlativo."' id='correlativoActual' disabled>";
			}
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
							$html .="<tr>";
								$html .="<td colspan=5 align='left'>Total de cobro Boletas: </td>";
								$html .="<td colspan=2 align='right'>S/. ".$bs->suma."</td>";
							$html .="</tr>";
						endforeach;

						$boleta = $this->comprobante_pago_mdl->getDatosBoleta($inicio, $fin, $canales, $serie);
						foreach ((array) $boleta as $b):

							$importe = $b->cob_importe;
							$importe = $importe/100;
							$importe2=number_format((float)$importe, 2, '.', ',');

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
							$html .= "<tr>";
								$html .= "<td colspan=6 align='left'>Total de cobros de Facturas</td>";
								$html .= "<td colspan=2 align='right'>".$fs->suma."</td>";
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
							$importe2=number_format((float)$importeDos, 2, '.', ',');
							$sub2=number_format((float)$sub, 2, '.', ','); 

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
		$correlativo = $_POST['correlativo'];
		$importeTotal = $_POST['importeTotal'];

		

		if ($canales == 1 || $canales == 2 || $canales == 3 || $canales == 6 || $canales == 7) {

			
			$cobro = $_POST['cobro'];
			$fechaEmi = $_POST['fechaEmi'];
			$idContratante = $_POST['contratante'];

			$idTipoDoc = 3;

			//for para recorrer los datos de la tabla y hacer el insert en la bd
			for ($i=0; $i < count($cobro); $i++) {
				$this->comprobante_pago_mdl->insertDatosBoletas($inicio, $fin, $fechaEmi[$i], $serie[$i], $correlativo, $idContratante[$i], $importeTotal[$i], $cobro[$i], $idPlan[$i]);

				$correlativo = $correlativo+1;
			}

		} elseif ($canales == 4) {

			$fechaEmi = $_POST['fechaEmi'];
			$idEmpresa = $_POST['empresa'];

			$idTipoDoc = 3;

			//for para recorrer los datos de la tablay hacer el insert en la bd
			for ($i=0; $i < count($idEmpresa); $i++) {
				$this->comprobante_pago_mdl->insertDatosFacturas($inicio, $fin, $fechaEmi[$i], $serie[$i], $correlativo, $idEmpresa[$i], $importeTotal[$i], $idPlan[$i]);

				$correlativo = $correlativo+1;
			}
		}

		//for para recorrer los planes obtenidos de la vista y hacer update del idestadocobro
		for ($i=0; $i < count(array_unique($idPlan)); $i++) { 
			
			$this->comprobante_pago_mdl->updateEstadoCobro($inicio, $fin, $idPlan[$i]);

		}
	}

}