<?php
// ini_set("max_execution_time", 12000);
// ini_set("memory_limit","256M");
error_reporting(E_ALL);
ini_set("display_error", "on");
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
			$data['canal'] = '';

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
		        $this->excel->getActiveSheet()->setCellValue('C1', 'Descripcion');
		        $this->excel->getActiveSheet()->setCellValue('D1', 'Prima inc. IGV');
		        $this->excel->getActiveSheet()->setCellValue('E1', 'Cant. de Pólizas');
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
		        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');

		        $cont2=2;

		        foreach ($cobros2 as $c2) {
		        	$this->excel->getActiveSheet()->setCellValue('A'.$cont2, $c2->cert_num);
			        $this->excel->getActiveSheet()->setCellValue('B'.$cont2, $c2->cont_numDoc);
			        $this->excel->getActiveSheet()->setCellValue('C'.$cont2, $c2->contratante);
			        $this->excel->getActiveSheet()->setCellValue('D'.$cont2, $c2->cob_vezCob);
			        $this->excel->getActiveSheet()->setCellValue('E'.$cont2, $c2->cob_fechCob);
			        $this->excel->getActiveSheet()->setCellValue('F'.$cont2, ($c2->cob_importe/100));
			        $cont2=$cont2+1;
		        }
		         for($i=1;$i<$cont2;$i++){
		        	$this->excel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($estilo);
		        	$this->excel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($estilo);
		        }

		        $this->excel->setActiveSheetIndex(0);
		 
		        header('Content-Type: application/vnd.ms-excel');
		        header('Content-Disposition: attachment;filename="Liquidación '.$nom_plan.' '.$hoy.'.csv"');
		        header('Cache-Control: max-age=0'); //no cache
		        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		        // Forzamos a la descarga
		        $objWriter->save('php://output');
		     }
		}else{
			redirect('/');
		}
	}

	public function detalle_cobros($importe,$plan,$inicio,$fin){
		$datos['plan'] = $plan;		
		$datos['inicio'] = $inicio;
		$datos['fin'] = $fin;
		$datos['importe'] = $importe;

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
}