<?php
// ini_set("max_execution_time", 12000);
// ini_set("memory_limit","256M");
error_reporting(E_ALL);
ini_set("display_error", "on");
ini_set('max_execution_time', 10000);
ini_set('memory_limit', -1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('reportes_mdl');
        $this->load->model('liquidacion_mdl');
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

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = "";

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_cobros.php',$data);

		}
		else{
			redirect('/');
		}
	}

	public function buscar()
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

			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$datos['canal'] = $_POST['canal'];
			$datos['plan'] = $_POST['plan'];
			$datos['inicio'] = $_POST['fechainicio'];
			$datos['fin'] = $_POST['fechafin'];

			$data['plan_id'] = $_POST['plan'];		
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$cobros = $this->reportes_mdl->getImportes($datos);
			$data['cobros'] = $cobros;	

			$data['canales'] = $this->reportes_mdl->getCanales();
			$planes = $this->reportes_mdl->getPlanes2($datos['canal']);
			$data['planes'] = $planes;

			$cobros2 = $this->reportes_mdl->getCobros2($datos);

			$data['estilo'] = 'block';
			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_cobros.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Liquidación');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'Periodo');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'Plan');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Descripción');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Prima inc. IGV');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Cant. de Cobros');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Sub Total');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        //$this->excel->getActiveSheet()->mergeCells('A1:D1');
		        $nom_plan="";
		        foreach ($planes as $p) {
		        	if($data['plan_id']==$p->idplan)
		        		$nom_plan=$p->nombre_plan;
		        }
		        $cont=2;
		        $tot=0;
		        $num=0;
		        foreach ($cobros as $c) {	
		        $cob = ($c->cob_importe/100);
		        $sub = ($cob*$c->cant);

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, 'Del '.$datos['inicio'].' al '.$datos['fin']);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $nom_plan);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $c->descripcion);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $cob);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $c->cant);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $sub);
			        $cont=$cont+1;
			        $num=$num+$c->cant;
			        $tot=$tot+$sub;
		        }

		        //$tot=number_format((float)$tot, 2, '.', ',');

		        $this->excel->getActiveSheet()->setCellValue('A'.$cont,'TOTAL');
		        $this->excel->getActiveSheet()->mergeCells('A'.$cont.':D'.$cont);
		        $this->excel->getActiveSheet()->setCellValue('E'.$cont,$num);
		        $this->excel->getActiveSheet()->setCellValue('F'.$cont,$tot);

		        $this->excel->getActiveSheet()->getStyle('A'.$cont.':F'.$cont)->getFont()->setSize(12);
		        $this->excel->getActiveSheet()->getStyle('A'.$cont.':F'.$cont)->getFont()->setBold(true);
		         $this->excel->getActiveSheet()->getStyle('F'.$cont)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFD700');

		        for($i=1;$i<=$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        }

		        $positionInExcel=1; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Detalle Cobros'); //Establecer nombre para la pestaña

				$this->excel->getActiveSheet()->setCellValue('A1', 'N° Certificado');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'N° Documento');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Contratante');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Vez Cobro');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'fecha');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Importe inc. IGV');		        
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Descripción');
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');

		        $cont2=2;

		        foreach ($cobros2 as $c2) {
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont2, $c2->cert_num);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont2, $c2->cont_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont2, $c2->contratante);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont2, $c2->cob_vezCob);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont2, $c2->cob_fechCob);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont2, ($c2->cob_importe/100));			        
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont2, $c2->num_afiliados);
			        $cont2=$cont2+1;
		        }
		         for($i=1;$i<$cont2;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);
		 
		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Liquidación '.$nom_plan.' '.$hoy.'.xls"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		     }
		}else{
			redirect('/');
		}
	}

	public function detalle_cobros($importe,$plan,$inicio,$fin,$num_afi){
		$datos['plan'] = $plan;		
		$datos['inicio'] = $inicio;
		$datos['fin'] = $fin;
		$datos['importe'] = $importe;
		$datos['num_afi'] = $num_afi;

		$cobros = $this->reportes_mdl->getCobros($datos);
		$data['cobros'] = $cobros;


		$this->load->view('dsb/html/reportes/detalle_cobros.php',$data);

	}

	public function exc_cobros($plan,$inicio,$fin)
	{
		$datos['plan'] = $plan;
		$datos['inicio'] = $inicio;
		$datos['fin'] = $fin;
		$hoy = date('d-m-Y');

		$result = $this->reportes_mdl->getCobros($datos);
		$this->export_excel->to_excel($result, 'cobros');
	}

	public function consultar_afiliados()
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

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = '';

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_afiliados.php',$data);

		}
		else{
			redirect('/');
		}

	}

	public function consultar_atenciones()
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

		$data['plan'] = "";
		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$planes = $this->reportes_mdl->getPlanes();
		$data['planes'] = $planes;
		$canales = $this->reportes_mdl->getCanales();
		$data['canales'] = $canales;
		$data['canal'] = '';

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_atenciones.php',$data);

		}
		else{
			redirect('/');
		}

	}

	public function consultar_atenciones_buscar()
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

			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$datos['canal'] = $_POST['canal'];
			$datos['plan'] = $_POST['plan'];
			$datos['inicio'] = $_POST['fechainicio'];
			$datos['fin'] = $_POST['fechafin'];

			$data['plan_id'] = $_POST['plan'];		
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$atenciones = $this->reportes_mdl->cons_atenciones($datos);
			$data['atenciones'] = $atenciones;	

			$data['canales'] = $this->reportes_mdl->getCanales();
			$planes = $this->reportes_mdl->getPlanes2($datos['canal']);
			$data['planes'] = $planes;

			$data['estilo'] = 'block';

			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_atenciones.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Atenciones');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'N° Atención');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'Fecha');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'N° Documento');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Afiliado');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Tipo');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'N° Teléfono');
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Atendido por');
		        $this->excel->getActiveSheet()->setCellValue('H1', 'Centro Médico');
		        $this->excel->getActiveSheet()->setCellValue('I1', 'Especialidad');
		        $this->excel->getActiveSheet()->setCellValue('J1', 'Estado');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        //$this->excel->getActiveSheet()->mergeCells('A1:D1');
		        $nom_plan="";
		        foreach ($planes as $p) {
		        	if($data['plan_id']==$p->idplan)
		        		$nom_plan=$p->nombre_plan;
		        }
		        $cont=2;

		        $e1=0;
		        $e2=0;
		        $e3=0;
		        $e4=0;
		        $e5=0;
		        $e6=0;

		        foreach ($atenciones as $a) {	
		        	if($a->estado_atencion=='P'){
						switch ($a->estado_cita):
							case 0: 
								$estadoa='Reserva Anulada';
								$color="F68B8B";
								$e1=$e1+1;
							break;
							case 1:
								$estadoa='Reserva Por Confirmar';
								$e2=$e2+1;
								$color="FFFFC9";
							break;
							case 2:
								$estadoa='Reserva Confirmada';
								$e3=$e3+1;
								$color="C8E6C8";
							break;
						endswitch;
					}else{
						switch($a->estado_siniestro):
							case 0: 
								$estadoa='Atención Anulada';
								$e4=$e4+1;
								$color="F68B8B";
							break;
							case 1:
								$estadoa='Atención Abierta';
								$e5=$e5+1;
								$color="AACFF5";
							break;
							case 2:
								$estadoa='Atención Cerrada';
								$e6=$e6+1;
								$color="E5CCFF";
							break;
						endswitch;
					}

					if($a->username == ""){ 
						$at="redes";
					}else{
						$at=$a->username;
					}

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $a->tipo_atencion);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $a->fecha_atencion);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $a->aseg_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $a->asegurado);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $a->tipo_afiliado);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $a->aseg_telf);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $at);
			        $this->excel->getActiveSheet()->setCellValue('H'.$cont, $a->nombre_comercial_pr);
		        	$this->excel->getActiveSheet()->setCellValue('I'.$cont, $a->nombre_esp);
		        	$this->excel->getActiveSheet()->setCellValue('J'.$cont, $estadoa);
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
		        	$this->excel->getActiveSheet()->getStyle('J'.$cont)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($estilo);
		        }

		        $positionInExcel=1; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen'); //Establecer nombre para la pestaña

				$this->excel->getActiveSheet()->setCellValue('A1', 'Resumen Reservas/Atenciones');
				$this->excel->getActiveSheet()->mergeCells('A1'.':D1');
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->setCellValue('A2', 'Reservas Anuladas');
				$this->excel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F68B8B');
		        $this->excel->getActiveSheet()->setCellValue('A3', 'Atenciones Anuladas');
		        $this->excel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F68B8B');
		        $this->excel->getActiveSheet()->setCellValue('A4', 'Atenciones Abiertas');
		        $this->excel->getActiveSheet()->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('AACFF5');
		        $this->excel->getActiveSheet()->setCellValue('A5', 'Atenciones Cerradas');
		        $this->excel->getActiveSheet()->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E5CCFF');

		        $this->excel->getActiveSheet()->setCellValue('B2', $e1);
		        $this->excel->getActiveSheet()->setCellValue('B3', $e4);
		        $this->excel->getActiveSheet()->setCellValue('B4', $e5);
		        $this->excel->getActiveSheet()->setCellValue('B5', $e6);

		        $this->excel->getActiveSheet()->setCellValue('C2', 'Total de Anulaciones');
		        $this->excel->getActiveSheet()->mergeCells('C2'.':C3');
		        $this->excel->getActiveSheet()->setCellValue('D2', $e1+$e4);
		        $this->excel->getActiveSheet()->mergeCells('D2'.':D3');
		        $this->excel->getActiveSheet()->setCellValue('C4', 'Total de Atenciones');		        
				$this->excel->getActiveSheet()->getStyle('C4:D4')->getFont()->setSize(16);
		        $this->excel->getActiveSheet()->getStyle('C4:D4')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->mergeCells('C4'.':C5');
		        $this->excel->getActiveSheet()->setCellValue('D4', $e5+$e6);
		        $this->excel->getActiveSheet()->mergeCells('D4'.':D5');



		        for ($i=1; $i < 6; $i++) { 
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		 
		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Atenciones '.$nom_plan.' '.$hoy.'.csv"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		     }
		}
		else{
			redirect('/');
		}
	}

	public function consultar_afiliados_buscar()
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

			$data['canal'] = $_POST['canal'];
			$data['plan'] = $_POST['plan'];
			$datos['canal'] = $_POST['canal'];
			$datos['plan'] = $_POST['plan'];
			$datos['tipo'] = $_POST['tipo'];

			$data['plan_id'] = $_POST['plan'];		
			$data['tipo'] = $_POST['tipo'];

			$afiliados = $this->reportes_mdl->cons_afiliados($datos);
			$data['afiliados'] = $afiliados;	

			$data['canales'] = $this->reportes_mdl->getCanales();
			$planes = $this->reportes_mdl->getPlanes2($datos['canal']);
			$data['planes'] = $planes;

			$data['estilo'] = 'block';
			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_afiliados.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Afiliados');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'N° Certificado');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'N° Documento');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Afiliado');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'N° Teléfono');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Tipo');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Estado');
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Afiliado por');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        //$this->excel->getActiveSheet()->mergeCells('A1:D1');
		        $nom_plan="";
		        foreach ($planes as $p) {
		        	if($data['plan_id']==$p->idplan)
		        		$nom_plan=$p->nombre_plan;
		        }
		        $cont=2;
		        if($data['tipo']==1){
		        	$estado="Vigente";
		        }else{
		        	$estado="Cancelado";
		        }

		        foreach ($afiliados as $a) {	

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $a->cert_num);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $a->aseg_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $a->asegurado);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $a->aseg_telf);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $a->tipo);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $estado);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $a->username);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        }
		 
		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Afiliados '.$nom_plan.' '.$hoy.'.csv"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		     }

		}
		else{
			redirect('/');
		}
	}

	public function gestion_atenciones(){
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

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/gestion_atenciones.php',$data);

		}
		else{
			redirect('/');
		}
	}

	public function gestion_atenciones_buscar(){
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
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$gatenciones = $this->reportes_mdl->cons_gatenciones($data);
			$data['gatenciones'] = $gatenciones;	
			$resumen_clinica = $this->reportes_mdl->resumen_clinica_usuario2($data);
			$resumen_operador = $this->reportes_mdl->resumen_operador2($data);
			$data['estilo'] = 'block';

			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/gestion_atenciones.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Gestión de Citas');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'Usuario');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'N° Atenciones');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Tiempo Promedio');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($gatenciones as $a) {	
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $a->username);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $a->atenciones);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $a->promedio);
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        }

		        $positionInExcel=1; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Clínica'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Usuario');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Centro Médico');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'N° Atenciones');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Tiempo Promedio');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($resumen_clinica as $rc) {	
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $rc->username);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $rc->nombre_comercial_pr);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $rc->atenciones);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $rc->promedio);
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=2; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(2); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Usuario'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'N° Orden');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Centro Médico');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Usuario Reserva');		        
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Fecha Reserva');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Usuario Gestiona');		        
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Fecha Confirma');        
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Tiempo Gestión');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($resumen_operador as $ro) {	
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $ro->num_orden_atencion);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $ro->nombre_comercial_pr);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $ro->usuario_reserva);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $ro->createdat);
		        	$this->excel->getActiveSheet()->setCellValue('E'.$cont, $ro->username);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $ro->updatedat);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $ro->promedio);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Gestión de Citas '.$hoy.'.xls"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		     }
		}
		else{
			redirect('/');
		}
	}

	public function resumen_clinica_usuario($id,$ini,$fin){
		$data['ini'] = $ini;
		$data['fin'] = $fin;
		$data['id'] = $id;

		$data['resumen_clinica_usuario'] = $this->reportes_mdl->resumen_clinica_usuario($data);
		$usuario_call = $this->reportes_mdl->getUsuario($id);
		$data['usuario_call'] = $usuario_call['username']; 
		$this->load->view('dsb/html/reportes/resumen_clinica_usuario.php',$data);
	}

	public function resumen_operador($id,$ini,$fin){
		$data['ini'] = $ini;
		$data['fin'] = $fin;
		$data['id'] = $id;

		$data['resumen_operador'] = $this->reportes_mdl->resumen_operador($data);
		$usuario_call = $this->reportes_mdl->getUsuario($id);
		$data['usuario_call'] = $usuario_call['username']; 
		$this->load->view('dsb/html/reportes/resumen_operador.php',$data);
	}

	public function consultar_incidencias(){
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

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$get_incidencias_canal = $this->reportes_mdl->incidencia_canal($data);
		$data['incidencias_canal'] = $get_incidencias_canal;	
		$incidencia_usuario = $this->reportes_mdl->incidencia_usuario($data);
		$data['incidencia_usuario'] = $incidencia_usuario;
		$detalle_incidencias = $this->reportes_mdl->detalle_incidencias($data);
		$data['detalle_incidencias'] = $detalle_incidencias;

		$data['estilo'] = 'none';

		$this->load->view('dsb/html/reportes/consultar_incidencias.php',$data);

		}
		else{
			redirect('/');
		}
	}

	public function consulta_incidencias_buscar(){
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
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$get_incidencias_canal = $this->reportes_mdl->incidencia_canal($data);
			$data['incidencias_canal'] = $get_incidencias_canal;	
			$incidencia_usuario = $this->reportes_mdl->incidencia_usuario($data);
			$data['incidencia_usuario'] = $incidencia_usuario;
			$detalle_incidencias = $this->reportes_mdl->detalle_incidencias($data);
			$data['detalle_incidencias'] = $detalle_incidencias;
			$data['estilo'] = 'block';

			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_incidencias.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Resumen por Cliente');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'Cliente');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'Plan');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Tipo Incidencia');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Cantidad');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Tiempo Prom.');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($get_incidencias_canal as $ic) {	
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $ic->nombre_comercial_cli);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $ic->nombre_plan);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $ic->tipoincidencia);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $ic->cant);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $ic->promedio);
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        }

		        $positionInExcel=1; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Usuario'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Usuario');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Total Incidencias');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Resueltas');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Pendientes');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Tiempo Prom.');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($incidencia_usuario as $iu) {	
		        	$pendientes = $iu->total-$iu->solucionado;
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $iu->username);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $iu->total);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $iu->solucionado);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $pendientes);			        
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $iu->promedio);
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=2; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(2); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Detalle'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Fecha Registro');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Usuario Registra');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Cliente');		        
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Plan');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'DNI');		        
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Afiliado');        
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Tipo');		              
		        $this->excel->getActiveSheet()->setCellValue('H1', 'Usuario Asignado');		              
		        $this->excel->getActiveSheet()->setCellValue('I1', 'Solución');		              
		        $this->excel->getActiveSheet()->setCellValue('J1', 'Tiempo');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($detalle_incidencias as $d) {	
		        	if($d->tiempo<60){
		        		$tiempo = $d->tiempo.' minutos(s)';
		        	}elseif($d->tiempo<1440){
		        		$tiempo = round($d->tiempo/60,0);
		        		$tiempo = $tiempo.' hora(s)';
		        	}else{
		        		$tiempo = round($d->tiempo/1440);
		        		$tiempo = $tiempo.' día(s)';
		        	}

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $d->fech_reg);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $d->registra);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $d->nombre_comercial_cli);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $d->nombre_plan);
		        	$this->excel->getActiveSheet()->setCellValue('E'.$cont, $d->aseg_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $d->asegurado);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $d->tipoincidencia);
			        $this->excel->getActiveSheet()->setCellValue('H'.$cont, $d->asignado);
			        $this->excel->getActiveSheet()->setCellValue('I'.$cont, $d->fecha_solucion);
			        $this->excel->getActiveSheet()->setCellValue('J'.$cont, $tiempo);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Incidencias '.$hoy.'.xls"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		    }
		}
		else{
			redirect('/');
		}
	}

	public function consultar_postVenta(){
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

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$resumen_general = $this->reportes_mdl->resumen_general($data);
		$data['contestaron_telf'] = $resumen_general['contestaron_telefono'];
		$data['contestaron_correo'] = $resumen_general['contestaron_correo'];
		$data['no_contestaron'] = $resumen_general['no_contestaron'];
		$data['no_opinan'] = $resumen_general['no_opinan'];
		$data['atenciones'] = $resumen_general['atenciones'];
		$data['resumen_canal'] = $this->reportes_mdl->resumen_canal($data);
		$data['resumen_plan'] = $this->reportes_mdl->resumen_plan($data);
		$data['resumen_usuario'] = $this->reportes_mdl->resumen_usuario($data);
		$data['encuesta_detalle'] = $this->reportes_mdl->encuesta_detalle($data);
		$data['resumen_clinica'] = $this->reportes_mdl->resumen_clinica($data);

		$this->load->view('dsb/html/reportes/consultar_postVenta.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function consultar_postVenta_buscar(){
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
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$resumen_general = $this->reportes_mdl->resumen_general($data);
			$data['contestaron_telf'] = $resumen_general['contestaron_telefono'];
			$data['contestaron_correo'] = $resumen_general['contestaron_correo'];
			$data['no_contestaron'] = $resumen_general['no_contestaron'];
			$data['no_opinan'] = $resumen_general['no_opinan'];			
			$data['atenciones'] = $resumen_general['atenciones'];
			$contestaron_telf = $resumen_general['contestaron_telefono'];
			$contestaron_correo = $resumen_general['contestaron_correo'];
			$no_contestaron = $resumen_general['no_contestaron'];
			$no_opinan = $resumen_general['no_opinan'];
			$atenciones = $resumen_general['atenciones'];
			$resumen_canal = $this->reportes_mdl->resumen_canal($data);
			$data['resumen_canal'] = $resumen_canal;
			$resumen_plan = $this->reportes_mdl->resumen_plan($data);
			$data['resumen_plan'] = $resumen_plan;
			$resumen_usuario = $this->reportes_mdl->resumen_usuario($data);
			$data['resumen_usuario'] = $resumen_usuario;
			$encuesta_detalle = $this->reportes_mdl->encuesta_detalle($data);
			$data['encuesta_detalle'] = $encuesta_detalle;
			$resumen_clinica= $this->reportes_mdl->resumen_clinica($data);
			$data['resumen_clinica'] = $resumen_clinica;

			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_postVenta.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Resumen General');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'Item');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'Teléfono');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Correo Electrónico');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Total PostVenta');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:G2')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $this->excel->getActiveSheet()->mergeCells('A1:A2');
		        $this->excel->getActiveSheet()->mergeCells('B1:C1');
		        $this->excel->getActiveSheet()->mergeCells('D1:E1');		        
		        $this->excel->getActiveSheet()->mergeCells('F1:G1');
		        $this->excel->getActiveSheet()->setCellValue('B2', 'Cantidad');
		        $this->excel->getActiveSheet()->setCellValue('C2', 'Porcentaje');
		        $this->excel->getActiveSheet()->setCellValue('D2', 'Cantidad');		        
		        $this->excel->getActiveSheet()->setCellValue('E2', 'Porcentaje');		        
		        $this->excel->getActiveSheet()->setCellValue('F2', 'Cantidad');		        
		        $this->excel->getActiveSheet()->setCellValue('G2', 'Porcentaje');

		        $total_encuestas = $contestaron_telf+$contestaron_correo+ $no_contestaron + $no_opinan;
		        $total_contestaron = $contestaron_telf+$contestaron_correo;
		        $total_telf = $contestaron_telf + $no_contestaron + $no_opinan;
		        if($total_telf>0){
		        	$porc_telf = round((($contestaron_telf*100)/$total_telf),2);
		        	$porc_nocontestaron = round((($no_contestaron*100)/$total_telf),2);
		        	$porc_noopinan = round((($no_opinan*100)/$total_telf),2);
		        	$totporctelf = round((($total_telf*100)/$atenciones),2);
		        }else{
		        	$porc_telf = 0;
		        	$porc_nocontestaron = 0;
		        	$porc_noopinan = 0;
		        	$totporctelf = 0;
		        }
		        if($total_encuestas>0){
		        	$porc_contestaron = round((($total_contestaron*100)/$total_encuestas),2);
		        	$porc_noc = round((($no_contestaron*100)/$total_encuestas),2);
		        	$porc_noop = round((($no_opinan*100)/$total_encuestas),2);
		        	$porc_telf2 =round((($total_telf*100)/$total_encuestas),2);
		        	$porc_correo2 = round((($contestaron_correo*100)/$total_encuestas),2);
		        }else{
		        	$porc_contestaron = 0;
		        	$porc_noc = 0;
		        	$porc_noop = 0;
		        	$porc_telf2 = 0;
		        	$porc_correo2 = 0;
		        }
		        if($contestaron_correo>0){
		        	$porc_correo = round((($contestaron_correo*100)/$contestaron_correo),2);
		        	$totporcorreo = round((($contestaron_correo*100)/$atenciones),2);
		        }else{
		        	$porc_correo = 0;
		        	$totporcorreo = 0;
		        }	

		        $this->excel->getActiveSheet()->setCellValue('A3', 'Contestaron');
			    $this->excel->getActiveSheet()->setCellValue('B3', $contestaron_telf);
			    $this->excel->getActiveSheet()->setCellValue('C3', $porc_telf.'%');
			    $this->excel->getActiveSheet()->setCellValue('D3', $contestaron_correo);
			    $this->excel->getActiveSheet()->setCellValue('E3', $porc_correo.'%');
			    $this->excel->getActiveSheet()->setCellValue('F3', $total_contestaron);
			    $this->excel->getActiveSheet()->setCellValue('G3', $porc_contestaron.'%');

			    $this->excel->getActiveSheet()->setCellValue('A4', 'No Contestaron');
			    $this->excel->getActiveSheet()->setCellValue('B4', $no_contestaron);
			    $this->excel->getActiveSheet()->setCellValue('C4', $porc_nocontestaron.'%');
			    $this->excel->getActiveSheet()->setCellValue('D4', '0');
			    $this->excel->getActiveSheet()->setCellValue('E4', '0 %');
			    $this->excel->getActiveSheet()->setCellValue('F4', $no_contestaron);
			    $this->excel->getActiveSheet()->setCellValue('G4', $porc_noc.'%');

			    $this->excel->getActiveSheet()->setCellValue('A5', 'No Opinaron');
			    $this->excel->getActiveSheet()->setCellValue('B5', $no_opinan);
			    $this->excel->getActiveSheet()->setCellValue('C5', $porc_noopinan.'%');
			    $this->excel->getActiveSheet()->setCellValue('D5', '0');
			    $this->excel->getActiveSheet()->setCellValue('E5', '0 %');
			    $this->excel->getActiveSheet()->setCellValue('F5', $no_opinan);
			    $this->excel->getActiveSheet()->setCellValue('G5', $porc_noop.'%');

			    $this->excel->getActiveSheet()->setCellValue('A6', 'Total Post Venta');
			    $this->excel->getActiveSheet()->setCellValue('B6', $total_telf);
			    $this->excel->getActiveSheet()->setCellValue('D6', $contestaron_correo);
			    $this->excel->getActiveSheet()->setCellValue('F6', $total_encuestas);
			    $this->excel->getActiveSheet()->setCellValue('B7', $porc_telf2.'%');
			    $this->excel->getActiveSheet()->setCellValue('D7', $porc_correo2.'%');
			    $this->excel->getActiveSheet()->mergeCells('F6:F7');

			    $this->excel->getActiveSheet()->mergeCells('A6:A7');
		        $this->excel->getActiveSheet()->mergeCells('B6:C6');
		        $this->excel->getActiveSheet()->mergeCells('D6:E6');		        
		        $this->excel->getActiveSheet()->mergeCells('F6:G6');
		        $this->excel->getActiveSheet()->mergeCells('B7:C7');
		        $this->excel->getActiveSheet()->mergeCells('D7:E7');		        
		        $this->excel->getActiveSheet()->mergeCells('F7:G7');

		        $this->excel->getActiveSheet()->setCellValue('A8', 'TOTAL ATENCIONES');
			    $this->excel->getActiveSheet()->setCellValue('B8', $totporctelf.'%');
			    $this->excel->getActiveSheet()->setCellValue('D8', $totporcorreo.'%');
			    $this->excel->getActiveSheet()->setCellValue('F8', $atenciones);
			    $this->excel->getActiveSheet()->mergeCells('B8:C8');
		        $this->excel->getActiveSheet()->mergeCells('D8:E8');		        
		        $this->excel->getActiveSheet()->mergeCells('F8:G8');

		        for($i=1;$i<=8;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        }

		        $positionInExcel=1; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Cliente'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Cliente');
				$this->excel->getActiveSheet()->setCellValue('B1', 'N° Encuestas');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Indicador');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Calificación');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($resumen_canal as $rc) {
		        	$num=$rc->num_encuestas;
		        	$valor=$rc->suma;
		        	$indicador=round(($valor/$num),1);
		        	if($indicador<1.5){
		        		$calificacion = 'Pésimo';
		        	}elseif ($indicador>1.4 && $indicador<2.5) {
		        		$calificacion = 'Malo';
		        	}elseif ($indicador>2.4 && $indicador<3.5) {
		        		$calificacion = 'Regular';
		        	}elseif($indicador>3.4 && $indicador<4.5){
		        		$calificacion = 'Bueno';
		        	}else{
		        		$calificacion = 'Excelente';
		        	}
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $rc->nombre_comercial_cli);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $num);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $indicador);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $calificacion);	
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=2; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(2); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Plan'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Cliente');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Plan');	
				$this->excel->getActiveSheet()->setCellValue('C1', 'Satisfacción');        
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Net Promoter Score');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:H2')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $this->excel->getActiveSheet()->mergeCells('A1:A2');
		        $this->excel->getActiveSheet()->mergeCells('B1:B2');
		        $this->excel->getActiveSheet()->mergeCells('C1:E1');
		        $this->excel->getActiveSheet()->mergeCells('F1:H1');
		        $this->excel->getActiveSheet()->setCellValue('C2', 'N° Encuestas');	        
		        $this->excel->getActiveSheet()->setCellValue('D2', 'Indicador');		        
		        $this->excel->getActiveSheet()->setCellValue('E2', 'Calificación');	        
		        $this->excel->getActiveSheet()->setCellValue('F2', 'N° Encuestas');		        
		        $this->excel->getActiveSheet()->setCellValue('G2', 'Indicador');	        
		        $this->excel->getActiveSheet()->setCellValue('H2', 'Calificación');
		        $cont=3;

		        foreach ($resumen_plan as $rp) {
		        	$num=$rp->num_encuestas;
		        	$num2=$rp->num_encuestas2;
		        	$valor=$rp->suma;
		        	$valor2=$rp->suma2;
		        	if($valor>0){
		        		$indicador=round(($valor/$num),1);
		        		if($indicador<1.5){
							$calificacion = 'Nada satisfecho';
						}elseif ($indicador>1.4 && $indicador<2.5) {
							$calificacion = 'Poco satisfecho';
						}elseif ($indicador>2.4 && $indicador<3.5) {
							$calificacion = 'Satisfecho';
						}elseif($indicador>3.4 && $indicador<4.5){
							$calificacion = 'Muy satisfecho';
						}else{
							$calificacion = 'Totalmente satisfecho';
						}
		        	}else{
		        		$indicador='-';
		        		$calificacion='-';
		        	}
		        	if($valor2>0){
		        		$indicador2=round(($valor2/$num2),1);
		        		if($indicador2<3.1){
		        			$calificacion2 = 'Detractores';
		        		}elseif ($indicador2>3 && $indicador2<4.1) {
		        			$calificacion2 = 'Pasivos';
		        		}elseif ($indicador2>4 ) {
		        			$calificacion2 = 'Promotores';
		        		}
		        	}else{
		        		$indicador2='-';
		        		$calificacion2='-';
		        	}
		        if($num<>0 or $num2<>0){

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $rp->nombre_comercial_cli);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $rp->nombre_plan);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $num);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $indicador);
		        	$this->excel->getActiveSheet()->setCellValue('E'.$cont, $calificacion);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $num2);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $indicador2);			        
			        $this->excel->getActiveSheet()->setCellValue('H'.$cont, $calificacion2);
			        $cont=$cont+1;
		        }}

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);		        		        	
		        	$this->excel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($estilo);	  
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=3; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(3); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Clínica'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Centro Médico');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Satisfacción');        
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Calidad');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:G2')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $this->excel->getActiveSheet()->mergeCells('A1:A2');
		        $this->excel->getActiveSheet()->mergeCells('B1:D1');
		        $this->excel->getActiveSheet()->mergeCells('E1:G1');
		        $this->excel->getActiveSheet()->setCellValue('B2', 'N° Encuestas');	        
		        $this->excel->getActiveSheet()->setCellValue('C2', 'Indicador');		        
		        $this->excel->getActiveSheet()->setCellValue('D2', 'Calificación');	        
		        $this->excel->getActiveSheet()->setCellValue('E2', 'N° Encuestas');		        
		        $this->excel->getActiveSheet()->setCellValue('F2', 'Indicador');	        
		        $this->excel->getActiveSheet()->setCellValue('G2', 'Calificación');
		        $cont=3;

		        foreach ($resumen_clinica as $rc2) {
		        	$num=$rc2->num_encuestas;
		        	$num2=$rc2->num_encuestas2;
		        	$valor=$rc2->suma;
		        	$valor2=$rc2->suma2;
		        	if($num>0){
		        		$indicador=round(($valor/$num),1);
		        		if($indicador<1.5){
							$calificacion = 'Nada satisfecho';
						}elseif ($indicador>1.4 && $indicador<2.5) {
							$calificacion = 'Poco satisfecho';
						}elseif ($indicador>2.4 && $indicador<3.5) {
							$calificacion = 'Satisfecho';
						}elseif($indicador>3.4 && $indicador<4.5){
							$calificacion = 'Muy satisfecho';
						}else{
							$calificacion = 'Totalmente satisfecho';
						}
		        	}
		        	if($num2>0){
		        		$indicador2=round(($valor2/$num2),1);
		        		if($indicador2<1.5){
		        			$calificacion2 = 'Pésimo';
		        		}elseif ($indicador2>1.4 && $indicador2<2.5) {
		        			$calificacion2 = 'Malo';
		        		}elseif ($indicador2>2.4 && $indicador2<3.5) {
		        			$calificacion2 = 'Regular';
		        		}elseif($indicador2>3.4 && $indicador2<4.5){
		        			$calificacion2 = 'Bueno';
		        		}else{
		        			$calificacion2 = 'Excelente';
		        		}
		        	}
		        	if($num>0 or $num2>0){

		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $rc2->nombre_comercial_pr);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $rc2->num_encuestas3);			        
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $indicador);
		        	$this->excel->getActiveSheet()->setCellValue('D'.$cont, $calificacion);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont, $rc2->num_encuestas3);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $indicador2);			        
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $calificacion2);
			        $cont=$cont+1;
		        }}

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);	
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=4; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(4); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Resumen por Usuario'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'Usuario');
				$this->excel->getActiveSheet()->setCellValue('B1', 'N° Encuestas');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Indicador');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Calificación');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($resumen_usuario as $ru) {
		        	$num=$ru->num_encuestas;
		        	$valor=$ru->suma;
		        	$indicador=round(($valor/$num),1);
		        	if($indicador<1.5){
		        		$calificacion = 'Pésimo';
		        	}elseif ($indicador>1.4 && $indicador<2.5) {
		        		$calificacion = 'Malo';
		        	}elseif ($indicador>2.4 && $indicador<3.5) {
		        		$calificacion = 'Regular';
		        	}elseif($indicador>3.4 && $indicador<4.5){
		        		$calificacion = 'Bueno';
		        	}else{
		        		$calificacion = 'Excelente';
		        	}
		        	
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $ru->username);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $num);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $indicador);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $calificacion);	
		        	//$this->excel->getActiveSheet()->getStyle('I'.$cont)->getFont()->setColor($color);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);

		        $positionInExcel=5; //Loque mencionaste
				$this->excel->createSheet($positionInExcel); //Loque mencionaste
				$this->excel->setActiveSheetIndex(5); //Seleccionar la pestaña deseada
				$this->excel->getActiveSheet()->setTitle('Detalle'); //Establecer nombre para la pestaña 
				$this->excel->getActiveSheet()->setCellValue('A1', 'N° Orden');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Cliente');
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Plan');		        
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Proveedor');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'DNI');		        
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Afiliado');        
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Usuario Gestiona');		              
		        $this->excel->getActiveSheet()->setCellValue('H1', 'Usuario Califica');			        	              
		        $this->excel->getActiveSheet()->setCellValue('I1', 'Fecha Califica');		              
		        $this->excel->getActiveSheet()->setCellValue('K1', 'Estado Calificación');		              
		        $this->excel->getActiveSheet()->setCellValue('K1', 'Inidicador');
		        $this->excel->getActiveSheet()->setCellValue('L1', 'Calificación');
		        $this->excel->getActiveSheet()->setCellValue('M1', 'Comentario');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $cont=2;

		        foreach ($encuesta_detalle as $ed) {
		        	$num=$ed->num_respuestas;
		        	if($num>0){
		        		$suma = $ed->suma;
		        		$indicador = round($suma/$num,1);
		        		if($indicador<1.5){
		        			$calificacion = 'Pésimo';
		        		}elseif ($indicador>1.4 && $indicador<2.5) {
		        			$calificacion = 'Malo';
		        		}elseif ($indicador>2.4 && $indicador<3.5) {
		        			$calificacion = 'Regular';
		        		}elseif($indicador>3.4 && $indicador<4.5){
		        			$calificacion = 'Bueno';
		        		}else{
		        			$calificacion = 'Excelente';
		        		}
		        	}else{
		        		$indicador = '-';
		        		$calificacion = '-';
		        	}
		        	$estado = $ed->estado;
		        	switch ($estado) {
		        		case 0:
		        		$estado = 'No Opina';
		        		$color = 'red';
		        		break;
		        		case 1:
		        		$estado = "Contestó";
		        		$color = 'black';
		        		break;
		        		default:
		        		$estado = "No Contestó";
		        		$color = "orange";
		        		break;
		        	}
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $ed->num_orden_atencion);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $ed->nombre_comercial_cli);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $ed->nombre_plan);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $ed->nombre_comercial_pr);
		        	$this->excel->getActiveSheet()->setCellValue('E'.$cont, $ed->aseg_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $ed->afiliado);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $ed->username);
			        $this->excel->getActiveSheet()->setCellValue('H'.$cont, $ed->medio_calificacion);			        
			        $this->excel->getActiveSheet()->setCellValue('I'.$cont, $ed->fecha_hora);
			        $this->excel->getActiveSheet()->setCellValue('J'.$cont, $estado);
			        $this->excel->getActiveSheet()->setCellValue('K'.$cont, $indicador);
			        $this->excel->getActiveSheet()->setCellValue('L'.$cont, $calificacion);
			        $this->excel->getActiveSheet()->setCellValue('M'.$cont, $ed->comentario);
			        $cont=$cont+1;
		        }

		        for($i=1;$i<$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);		        	
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($estilo);	        	
		        	$this->excel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($estilo);		        	        	
		        	$this->excel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);


		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Post-Venta '.$hoy.'.xls"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		    }
		}
		else{
			redirect('/');
		}
	}

	public function liq_pagadas()
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

			$data['pagos_pendientes'] = $this->liquidacion_mdl->getPagospendientes();
			$data['getPagosRealizados'] = $this->liquidacion_mdl->getPagosRealizados();

			$this->load->view('dsb/html/reportes/liq_pagadas.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function pago_detalle2($idpago){
		$pagoDet = $this->liquidacion_mdl->pagoDet($idpago);
		$cadena = "";
		foreach ($pagoDet as $p) {
			$id = $p->liqgrupo_id;
			$liquidacionDet = $this->liquidacion_mdl->getLiquidacionDet($id);
			$liquidacion = $this->liquidacion_mdl->liquidacionpdf($id); 
			$num = $p->liq_num;
			$liquidacion_grupo = $this->liquidacion_mdl->getLiquidacionGrupo($id);

			$cadena .= '
						<div class="page-header">
							<h1>	
							Detalle Liquidación: L'.$num.'
							</h1>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

							<div class="tabbable">
									<!-- #section:pages/faq -->
								<div class="col-xs-12">';	
								foreach ($liquidacion as $l){
								    		$razon_social = $l->razon_social_pr;
								    		$ruc = $l->numero_documento_pr;
								    		$banco = $l->descripcion;
								    		$tipo = $l->descripcion_fp;
								    		$op = $l->numero_operacion;
								    		$total = $l->total;
								    		$igv = $total * 0.18;
								    		$subtotal = $total - $igv;
								    		$cta_corriente = $l->cta_corriente;
								    		$cta_detracciones = $l->cta_detracciones;
								    		$colaborador = $l->colaborador;
								    		$detraccion = $l->detraccion;

								    		$igv = number_format((float)$igv, 2, ".", "");
								    		$subtotal = number_format((float)$subtotal, 2, ".", "");
								    		$total = number_format((float)$total, 2, ".", "");
								    		$detraccion = number_format((float)$detraccion,2,".","");

								    		$neto = $total - $detraccion;
								    		$neto = number_format((float)$neto,2,".","");

										} 
							$cadena.='<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
										<th width="30%">Nombre/Razón Social</th>
										<td>'.$razon_social.'</td>
										</tr>
										<tr>
										<th width="30%">DNI/RUC</th>
										<td>'.$ruc.'</td>
										</tr>
										<tr>
										<th width="30%">Concepto</th>
										<td>Liquidación de Facturas</td>
										</tr>
									</table>

									<h4>N° Documentos:</h4>	
									<table id="example" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<thead>
											<tr>
												<th>CC</th>
												<th>Plan</th>
												<th>N° Factura</th>
												<th>N° Orden Atención</th>
												<th>Afiliado</th>
												<th>Concepto</th>
												<th>Importe Bruto</th>
												<th>Importe Neto</th>
											</tr>
										</thead>
										<tbody>';
										foreach ($liquidacionDet as $ld){ 
											$liqdetalle_monto = $ld->liqdetalle_monto;
											$liqdetalle_monto = number_format((float)$liqdetalle_monto, 2, ".", "");
											$liqdetalle_neto = $ld->liqdetalle_neto;
											$liqdetalle_neto = number_format((float)$liqdetalle_neto, 2, ".", "");
										
									$cadena.='<tr>
												<td>'.$ld->centro_costo.'</td>
												<td>'.$ld->nombre_plan.'</td>
												<td>'.$ld->liqdetalle_numfact.'</td>
												<td>'.$ld->num_orden_atencion.'</td>
												<td>'.$ld->afiliado.'</td>
												<td>'.$ld->nombre_var.'</td>
												<td style="text-align: right;">'.$liqdetalle_monto.' PEN</td>
												<td style="text-align: right;">'.$liqdetalle_neto.' PEN</td>
											</tr>';
										 }
									$cadena.='</tbody>
									</table>

									<h4>Detalle a Pagar</h4>	

									<div style="float: left; width: 49%">
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
											<th width="30%" align="left"> Sub Total</th>
											<td style="text-align: right;">'.$subtotal.' PEN</td>
										</tr>
										<tr>
											<th width="30%" align="left"> IGV</th>
											<td style="text-align: right;">'.$igv.' PEN</td>
										</tr>
										<tr>
											<th width="30%" align="left"> Total</th>
											<td style="text-align: right;">'.$total.' PEN</td>
										</tr>
									</table>
									</div>
									<div style="float: right; width: 49%">
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
											<th width="30%" align="right"> Detracciones</th>
											<td style="text-align: right;">'.$detraccion.' PEN</td>
										</tr>
										<tr>
											<th width="30%" align="right"> NETO A PAGAR</th>
											<th style="text-align: right; color: red">'.$neto.' PEN</th>
										</tr>
									</table>
									<br>
									<br>
									</div>';


									if(!empty($liquidacion_grupo)){

								$cadena.='<h4>Detalle del Pago Realizado</h4>
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<thead>
											<tr>
												<th>Usuario Liquida</th>
												<th>Fecha</th>
												<th>Banco</th>
												<th>Forma de Pago</th>
												<th>N° Operación</th>
												<th>Correo Notificación</th>
											</tr>														
										</thead>
										<tbody>';
											foreach ($liquidacion_grupo as $lg) { 
										$cadena.='<tr>
												<td>'.$lg->username.'</td>
												<td>'.$lg->fecha_pago.'</td>
												<td>'.$lg->descripcion.'</td>
												<td>'.$lg->descripcion_fp.'</td>
												<td>'.$lg->numero_operacion.'</td>
												<td>'.$lg->email_notifica.'</td>
											</tr>';
										} 
										$cadena.='</tbody>
									</table>';
								}
								$cadena.='</div><!-- /.col -->
						</div>';

		}

		$data['cadena'] = $cadena;


		
		$this->load->view('dsb/html/reportes/pago_detalle.php',$data);
	}

	public function consultar_siniestros(){
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

      	$month2 = $month;

		$data['fecinicio'] = date('Y-m-d', mktime(0,0,0, $month2, 1, $year));
		$data['fecfin'] = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

		$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
		$data['menu2'] = $submenuLista;	

		$data['getDetalleSiniestros'] = $this->reportes_mdl->getDetalleSiniestros($data);

		$this->load->view('dsb/html/reportes/consultar_siniestros.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function consultar_siniestros_buscar(){
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
			$data['fecinicio'] = $_POST['fechainicio'];
			$data['fecfin'] = $_POST['fechafin'];

			$getDetalleSiniestros = $this->reportes_mdl->getDetalleSiniestros($data);
			$data['getDetalleSiniestros'] = $getDetalleSiniestros;


			$accion=$_POST['accion'];

			if($accion=='buscar'){
				$this->load->view('dsb/html/reportes/consultar_siniestros.php',$data);
			}else{
				$this->load->library('excel');
				$hoy=date('Y-m-d');
				// $datos['canal'] = $_POST['canal'];
				// $datos['plan'] = $_POST['plan'];
				// $datos['inicio'] = $_POST['fechainicio'];
				// $datos['fin'] = $_POST['fechafin']
				$estilo = array( 
				  'borders' => array(
				    'outline' => array(
				      'style' => PHPExcel_Style_Border::BORDER_THIN
				    )
				  )
				);

		        $this->excel->setActiveSheetIndex(0);
		        $this->excel->getActiveSheet()->setTitle('Resumen Siniestros');
		        $this->excel->getActiveSheet()->setCellValue('A1', 'N° Orden');
		        $this->excel->getActiveSheet()->setCellValue('B1', 'Fec. Atención');		        
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Plan');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Centro Médico');		        
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Servicio');
		        $this->excel->getActiveSheet()->setCellValue('F1', 'Diagnóstico');		        
		        $this->excel->getActiveSheet()->setCellValue('G1', 'Titular');		        
		        $this->excel->getActiveSheet()->setCellValue('H1', 'Afiliado');		        
		        $this->excel->getActiveSheet()->setCellValue('I1', 'Consulta');		        		        
		        $this->excel->getActiveSheet()->setCellValue('K1', 'Medicamentos');		        
		        $this->excel->getActiveSheet()->setCellValue('M1', 'Laboratorios');		        
		        $this->excel->getActiveSheet()->setCellValue('O1', 'Imágenes');
		        //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		        $this->excel->getActiveSheet()->getStyle('A1:P2')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:P2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
		        $this->excel->getActiveSheet()->mergeCells('A1:A2');
		        $this->excel->getActiveSheet()->mergeCells('B1:B2');
		        $this->excel->getActiveSheet()->mergeCells('C1:C2');		        
		        $this->excel->getActiveSheet()->mergeCells('D1:D2');
		        $this->excel->getActiveSheet()->mergeCells('E1:E2');
		        $this->excel->getActiveSheet()->mergeCells('F1:F2');		        
		        $this->excel->getActiveSheet()->mergeCells('G1:G2');
		        $this->excel->getActiveSheet()->mergeCells('H1:H2');
		        $this->excel->getActiveSheet()->mergeCells('I1:J1');
		        $this->excel->getActiveSheet()->mergeCells('K1:L1');		        
		        $this->excel->getActiveSheet()->mergeCells('M1:N1');
		        $this->excel->getActiveSheet()->mergeCells('O1:P1');
		        $this->excel->getActiveSheet()->setCellValue('I2', 'Fec. Pago');
		        $this->excel->getActiveSheet()->setCellValue('J2', 'Gasto');		        
		        $this->excel->getActiveSheet()->setCellValue('K2', 'Fec. Pago');
		        $this->excel->getActiveSheet()->setCellValue('L2', 'Gasto');
		        $this->excel->getActiveSheet()->setCellValue('M2', 'Fec. Pago');
		        $this->excel->getActiveSheet()->setCellValue('N2', 'Gasto');
		        $this->excel->getActiveSheet()->setCellValue('O2', 'Fec. Pago');
		        $this->excel->getActiveSheet()->setCellValue('P2', 'Gasto');

		        $cont=3;

		        foreach ($getDetalleSiniestros as $s) {
		        	$consulta = number_format((float)$s->consulta, 2, ".", "");
					$medicamentos = number_format((float)$s->medicamentos, 2, ".", "");
					$laboratorios = number_format((float)$s->laboratorio, 2, ".", "");
					$imagenes = number_format((float)$s->imagenes, 2, ".", "");
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont, $s->num_orden_atencion);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont, $s->fecha_atencion);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont, $s->nombre_plan);			        
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont, $s->nombre_comercial_pr);
		        	$this->excel->getActiveSheet()->setCellValue('E'.$cont, $s->nombre_esp);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont, $s->dianostico_temp);
			        $this->excel->getActiveSheet()->setCellValue('G'.$cont, $s->contratante);
			        $this->excel->getActiveSheet()->setCellValue('H'.$cont, $s->asegurado);
			        $this->excel->getActiveSheet()->setCellValue('I'.$cont, $s->pago_consulta);
			        $this->excel->getActiveSheet()->setCellValue('J'.$cont, $consulta);
			        $this->excel->getActiveSheet()->setCellValue('K'.$cont, $s->pago_medicamentos);
			        $this->excel->getActiveSheet()->setCellValue('L'.$cont, $medicamentos);
			        $this->excel->getActiveSheet()->setCellValue('M'.$cont, $s->pago_lab);
			        $this->excel->getActiveSheet()->setCellValue('N'.$cont, $laboratorios);
			        $this->excel->getActiveSheet()->setCellValue('O'.$cont, $s->pago_imagenes);
			        $this->excel->getActiveSheet()->setCellValue('P'.$cont, $imagenes);
			        $cont=$cont+1;
		        }

		       for($i=1;$i<=$cont;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($estilo);
		        }

		        
		        $this->excel->setActiveSheetIndex(0);


		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Siniestralidad '.$hoy.'.xls"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		    }
		}
		else{
			redirect('/');
		}
	}

}