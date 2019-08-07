<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('atencion_mdl');
        $this->load->model('liquidacion_mdl');
        $this->load->library('My_PHPMailer');
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

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;	

			$pre_liquidaciones = $this->liquidacion_mdl->getLiquidaciones();
			$data['pre_liquidaciones'] = $pre_liquidaciones;

			$liquidaciones = $this->liquidacion_mdl->get_liquidaciongrupo();
			$data['liquidaciones'] = $liquidaciones;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();

			$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function pre_liquidacion($id)
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

			$pre_liquidaciones = $this->liquidacion_mdl->getLiquidaciones($id);
			$data['pre_liquidaciones'] = $pre_liquidaciones;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			$data['prov_id'] = $id;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();

			$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);
		}
		else{
			redirect('/');
		}	
	}


	public function agrupar_liquidacion($id)
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

			$data['prov_id'] = $id;

			$liquidacion_grupo_p = $this->liquidacion_mdl->get_liquidaciongrupo_p($id);
			$data['liquidacion_grupo_p'] = $liquidacion_grupo_p;

			$this->load->view('dsb/html/liquidacion/agrupar_liquidaciones.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function gastos_proveedor(){
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

			$liquidaciones = $this->liquidacion_mdl->get_liquidaciongrupo();
			$data['liquidaciones'] = $liquidaciones;

			$gastos = $this->liquidacion_mdl->gastos_proveedor();
			$data['gastos'] = $gastos;

			$this->load->view('dsb/html/liquidacion/gastos.php',$data);
		}
		else{
			redirect('/');
		}	
	}


	public function registraPago()
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
		
			//recogemos los datos obtenidos por POST
			        
		    $data['liqdetalleid'] = $_POST['liqdetalleid'];
		    $liqdetalleid =  $data['liqdetalleid'];
		    
		    $data['pagoFecha'] = $_POST['pagoFecha'];
		    $data['pagoForma'] = $_POST['pagoForma'];
		    $data['pagoBanco'] = $_POST['pagoBanco'];
		    $data['pagoNoperacion'] = $_POST['pagoNoperacion'];
		    $data['pagoNfactura'] = $_POST['pagoNfactura'];	    

		    $buscaLiqdetalleid = $this->liquidacion_mdl->buscaLiqDeta($liqdetalleid);

		    if ($buscaLiqdetalleid){
		    	$this->liquidacion_mdl->updateLiqdetalle($data);
		    }else {
		    	$this->liquidacion_mdl->guardaLiqdetalle($data);
		    }

		    $this->liquidacion_mdl->updateEstadoLiqdetalle($data);

			//$this->load->view('dsb/html/liquidacion',$data);
			redirect(base_url()."index.php/liquidacion/");

			//$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function orden($id,$est)
	{
		$orden = $this->atencion_mdl->orden($id,$est);
		redirect ('index.php/atenciones');
	}

	public function save_liqgrupo()
	{
		$liq_det=$_POST['liq_det'];
		$num=count($liq_det);
		$data['idprov'] = $_POST['idprov'];

		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;

		$this->liquidacion_mdl->save_liquidacion($data);
		$data['liq_id'] = $this->db->insert_Id();

		$numero = str_pad($data['liq_id'], 8, "0", STR_PAD_LEFT);

		for($i=0;$i<$num;$i++){
			$data['liq_det']=$liq_det[$i];
			$this->liquidacion_mdl->save_liqdetalle_grupo($data);
			$this->liquidacion_mdl->up_liqdetalle($data);
			//enviar $michk[$i] al model para hacer insert
		}

		$detraccion = $this->liquidacion_mdl->calcular_detraccion($data);
		$detra=0;
		$tot=0;

		foreach ($detraccion as $d) {
			$detra=$detra + $d->detraccion;
			$tot= $tot + $d->neto - $detra;
		}

		$data['detra'] = $detra;
		$data['tot'] = $tot;
		$this->liquidacion_mdl->up_liqgrupo($data);

		echo "<script>
				alert('Se ha generado la liquidación N° L".$numero." con éxito.');
				location.href='".base_url()."index.php/gastos';
				</script>";
	}

	public function save_gasto(){
		$liq_grupo=$_POST['liq_grupo'];
		$num=count($liq_grupo);
		$data['idprov'] = $_POST['idprov'];

		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['fecha'] = date('Y-m-d H:i:s');

		$this->liquidacion_mdl->save_agrupacion($data);
		$data['idpago'] = $this->db->insert_Id();

		for($i=0;$i<$num;$i++){
			$data['liq_id']=$liq_grupo[$i];
			$this->liquidacion_mdl->save_pago_detalle($data);
			$this->liquidacion_mdl->upLiqgrupo($data);
		}

		$importe = $this->liquidacion_mdl->get_importe($data);
		foreach ($importe as $i) {
			$data['importe'] = $i->importe;
			$data['detraccion'] = $i->detraccion;
		}

		$this->liquidacion_mdl->up_importe($data);

		echo "<script>
				alert('Se gruparon las liquidaciones con éxito.');
				location.href='".base_url()."index.php/liquidacion_grupo';
				</script>";
	}

	public function getLiquidacionDet($id, $num){
		$liquidacionDet = $this->liquidacion_mdl->getLiquidacionDet($id);
		$data['liquidacionDet'] = $liquidacionDet;
		$liquidacion = $this->liquidacion_mdl->liquidacionpdf($id); 
		$data['liquidacion'] = $this->liquidacion_mdl->liquidacionpdf($id); 
		$data['numero'] = $num;
		$data['liquidacion_grupo'] = $this->liquidacion_mdl->getLiquidacionGrupo($id);
		$this->load->view('dsb/html/liquidacion/liquidacion_detalle.php',$data);
	}

	public function liquidacion_grupo(){
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

			$liquidacion_grupo_p = $this->liquidacion_mdl->getLiquidacionesProveedor();
			$data['liquidacion_grupo_p'] = $liquidacion_grupo_p;

			$liquidacion_grupo_c = $this->liquidacion_mdl->getLiqAgrupadas();
			$data['liquidacion_grupo_c'] = $liquidacion_grupo_c;
			// $liquidacion_grupo_c = $this->liquidacion_mdl->get_liquidaciongrupo_c();
			// $data['liquidacion_grupo_c'] = $liquidacion_grupo_c;
			$this->load->view('dsb/html/liquidacion/liquidacion_grupo.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function pagos(){
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

			$this->load->view('dsb/html/liquidacion/liquidacion_pago.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function liquidacion_regpago($idpago){
		$data['idpago'] = $idpago;
		$data['getInfoPago'] = $this->liquidacion_mdl->getInfoPago($idpago);
		$this->load->view('dsb/html/liquidacion/registrar_pago.php',$data);
	}

	public function pagoDetalle($idpago){
		$data['pagoDet'] = $this->liquidacion_mdl->pagoDet($idpago);
		$this->load->view('dsb/html/liquidacion/pago_detalle.php',$data);
	}

	public function save_regPago(){

		$idpago = $_POST['idpago'];
        $config['upload_path'] = './uploads/';
        $config['file_name'] = $idpago;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 50000;
        $config['max_width'] = 2000;
        $config['max_height'] = 2000;

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('mi_archivo')) {
            //*** ocurrio un error
            $data['uploadError'] = $this->upload->display_errors();
            echo $this->upload->display_errors();
            return;
        }

        $data['uploadSuccess'] = $this->upload->data();

		$user = $this->session->userdata('user');
		extract($user);

		$data['idpago'] = $idpago;
		$data['nro_operacion'] = $_POST['nro_operacion'];
		$data['fecha'] = $_POST['fecha'];
		$data['idusuario'] = $idusuario;

		$this->liquidacion_mdl->save_Pago($data);

		// //Crear formato de liquidación
		$this->load->library('Pdf');
        $this->pdf = new Pdf();
	    $liqgrupo = $this->liquidacion_mdl->getLiqGrupo($idpago);

	    foreach ($liqgrupo as $lg) {	    	
	    	$id = $lg->liqgrupo_id;
	    	$num = $lg->num;
	    	$liquidacion = $this->liquidacion_mdl->liquidacionpdf($id); 
		    $liquidacion_det = $this->liquidacion_mdl->liquidacionpdf_detalle($id);
		    $detracciones=0;

		    foreach ($liquidacion as $l){
		    	$idproveedor = $l->idproveedor;
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
		    	$detracciones = $l->detraccion;
		    	$fecha = $l->fecha_pago;
		    	$fecha = date('d/m/Y',strtotime($fecha));

		    	$igv = number_format((float)$igv, 2, '.', '');
		    	$subtotal = number_format((float)$subtotal, 2, '.', '');
		    	$total = number_format((float)$total, 2, '.', '');
		    }

		    //$num = printf('%08d', $id);
	    		
			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();      
	            $this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','I',10); 
	          	$this->pdf->MultiCell(0,6,'Fecha: '.$fecha,0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','BU',12);
	            $this->pdf->MultiCell(0,6,utf8_decode('ORDEN DE PAGO A PROVEEDORES')." Nro: L".$num,0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(50,6,utf8_decode("Nombres/Razón Social"),1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode($razon_social),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"DNI/RUC",1,0,'L',false);
	            $this->pdf->Cell(0,6,$ruc,1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"Concepto",1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode("Liquidación de Facturas"),1,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("N° Documentos:"),0,0,'L',false);
	            $this->pdf->Ln();
	           	$this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(20,6,utf8_decode("CC"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Factura"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Orden Atención"),1,0,'C',false);
	            $this->pdf->Cell(80,6,utf8_decode("Paciente"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("Importe"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            foreach ($liquidacion_det as $ld) {            
	            $this->pdf->Cell(20,6,utf8_decode($ld->centro_costo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($ld->liqdetalle_numfact),1,0,'L',false);
	            $this->pdf->Cell(30,6,utf8_decode("OA".$ld->num_orden_atencion),1,0,'L',false);
	            $this->pdf->Cell(80,6,utf8_decode($ld->afiliado),1,0,'L',false);
	            $this->pdf->Cell(30,6,number_format((float)$ld->neto, 2, '.', ''),1,0,'R',false);
	            $this->pdf->Ln();
	            }
	            $detracciones = number_format((float)$detracciones, 2, '.', '');
	            $total_neto=$total-$detracciones;
	            $total_neto = number_format((float)$total_neto, 2, '.', '');
	            $this->pdf->SetFont('Arial','BI',10);
	            $this->pdf->Cell(160,6,utf8_decode("Total"),1,0,'R',false);
	            $this->pdf->Cell(30,6,utf8_decode($total),1,0,'R',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','BU',11);
	            $this->pdf->MultiCell(0,6,utf8_decode('DETALLE TOTAL A PAGAR'),0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(45,6,utf8_decode("SUB TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$subtotal,1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("IGV"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$igv,1,0,'R',false);	
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("N° CTA DETRACCIONES"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($cta_detracciones),1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$total,1,0,'R',false);	            
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("DETRACCIÓN 12%"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($detracciones),1,0,'R',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("FORMA DE PAGO:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(40,6,utf8_decode("Banco"),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode("Tipo"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Op./Cheque"),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode("N° Cuenta"),1,0,'C',false);
	            $this->pdf->Cell(0,6,utf8_decode("Neto a Pagar"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(40,6,utf8_decode($banco),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode($tipo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($op),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode($cta_corriente),1,0,'C',false);
	            $this->pdf->Cell(0,6,"S/. ".utf8_decode($total_neto),1,0,'C',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','BI',8);
	            $this->pdf->Cell(20,6,utf8_decode("Emitido por:"),0,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($colaborador),0,0,'L',false);
	         
	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle(utf8_decode("Liquidación GR").$idpago);
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);			   
	    }

		$this->pdf->Output("uploads/GR".$idpago.".pdf", 'F');

		// Enviar email de liquidación a proveedor 
		    $user = $this->session->userdata('user');
			extract($user);

			$liquidacionDet = $this->liquidacion_mdl->getLiquidacionDet($id);
			$liquidacion_grupo = $this->liquidacion_mdl->getLiquidacionGrupo($id);
			$gasto_detalle = $this->liquidacion_mdl->pagoDet($idpago);

				$tipo="'Century Gothic'";
				$texto='<p>Estimado(a) '.$razon_social.' se ha realizado el pago de las siguientes liquidaciones, se adjunta el detalle y constancia de pago de las mismas.</p>
				<table class="table table-striped table-bordered table-hover"  style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;" border="1">
							<thead>
								<tr>
									<th>N° Liquidación</th>
									<th>Importe Inc. IGV</th>
									<th>Importe Detracción</th>
								</tr>
							</thead>
							<tbody>';

						foreach ($gasto_detalle as $gd) {
							$total = $gd->total;
							$total = number_format((float)$total, 2, '.', '');
							$detraccion = $gd->detraccion;
							$detraccion = number_format((float)$detraccion, 2, '.', '');
						$texto.='<tr>
									<td>L'.$gd->liq_num.'</td>
									<td style="text-align: right;"> S/. '.$total.'</td>';

						if($detraccion>0){
						$texto.='<td style="text-align: right;"> S/.'.$detraccion.'</td>';
						}else{
							$texto.='<td style="text-align: right;">No afecto/td>';
						}
						$texto.='</tr>';
						}
					$texto.='</tbody>
						</table>';

			
			$mail = new PHPMailer;	
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->SMTPSecure = 'false';
	        $mail->Port     = 25;	
			// Armo el FROM y el TO

			$destinatarios = $this->liquidacion_mdl->getDestinatarios($idproveedor);
			$mail->setFrom($correo_laboral, 'Red Salud');
			$mail->addAddress($correo_laboral, $nombres_col);
			$mail->addAddress("pvigil@red-salud.com", "Pilar Vigil");			
			$mail->addAddress("aluna@red-salud.com", "Angie Luna");
			if(!empty($destinatarios)){
				foreach ($destinatarios as $d) {
					$mail->addAddress($d->email_cp, $d->nombres_cp);
				}
			}
			// El asunto
			$mail->Subject = "NOTIFICACION RED SALUD: LIQUIDACION DE FACTURAS";
			// El cuerpo del mail (puede ser HTML)
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                <br>
	                '.$texto.'
	                <br>
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail->IsHTML(true);
			$mail->addAttachment('uploads/'.$idpago.'.pdf', 'Constancia de pago GR'.$idpago.'.pdf');
            $mail->addAttachment('uploads/GR'.$idpago.'.pdf', 'Formato GR'.$idpago.'.pdf');
			$mail->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail->send();

		unlink('uploads/GR'.$idpago.'.pdf');
		echo "<script>
				alert('Se registró el pago con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}

	public function liquidacion_pdf($idpago){
        $this->load->library('Pdf');
        $this->pdf = new Pdf();
	    $liqgrupo = $this->liquidacion_mdl->getLiqGrupo($idpago);

	    foreach ($liqgrupo as $lg) {	    	
	    	$id = $lg->liqgrupo_id;
	    	$num = $lg->num;
	    	$liquidacion = $this->liquidacion_mdl->liquidacionpdf($id); 
		    $liquidacion_det = $this->liquidacion_mdl->liquidacionpdf_detalle($id);
		    $detracciones=0;

		    foreach ($liquidacion as $l){
		    	$idproveedor = $l->idproveedor;
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
		    	$detracciones = $l->detraccion;
		    	$fecha = $l->fecha_pago;
		    	$fecha = date('d/m/Y',strtotime($fecha));

		    	$igv = number_format((float)$igv, 2, '.', '');
		    	$subtotal = number_format((float)$subtotal, 2, '.', '');
		    	$total = number_format((float)$total, 2, '.', '');
		    }

		    //$num = printf('%08d', $id);
	    		
			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();      
	            $this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','I',10); 
	          	$this->pdf->MultiCell(0,6,'Fecha: '.$fecha,0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','BU',12);
	            $this->pdf->MultiCell(0,6,utf8_decode('ORDEN DE PAGO A PROVEEDORES')." Nro: L".$num,0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(50,6,utf8_decode("Nombres/Razón Social"),1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode($razon_social),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"DNI/RUC",1,0,'L',false);
	            $this->pdf->Cell(0,6,$ruc,1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"Concepto",1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode("Liquidación de Facturas"),1,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("N° Documentos:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(20,6,utf8_decode("CC"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Factura"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Orden Atención"),1,0,'C',false);
	            $this->pdf->Cell(80,6,utf8_decode("Paciente"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("Importe"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            foreach ($liquidacion_det as $ld) {            
	            $this->pdf->Cell(20,6,utf8_decode($ld->centro_costo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($ld->liqdetalle_numfact),1,0,'L',false);
	            $this->pdf->Cell(30,6,utf8_decode("OA".$ld->num_orden_atencion),1,0,'L',false);
	            $this->pdf->Cell(80,6,utf8_decode($ld->afiliado),1,0,'L',false);
	            $this->pdf->Cell(30,6,number_format((float)$ld->neto, 2, '.', ''),1,0,'R',false);
	            $this->pdf->Ln();
	            }
	            $detracciones = number_format((float)$detracciones, 2, '.', '');
	            $total_neto=$total-$detracciones;
	            $total_neto = number_format((float)$total_neto, 2, '.', '');
	            $this->pdf->SetFont('Arial','BI',10);
	            $this->pdf->Cell(160,6,utf8_decode("Total"),1,0,'R',false);
	            $this->pdf->Cell(30,6,utf8_decode($total),1,0,'R',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','BU',11);
	            $this->pdf->MultiCell(0,6,utf8_decode('DETALLE TOTAL A PAGAR'),0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(45,6,utf8_decode("SUB TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$subtotal,1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("IGV"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$igv,1,0,'R',false);	
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("N° CTA DETRACCIONES"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($cta_detracciones),1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$total,1,0,'R',false);	            
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("DETRACCIÓN 12%"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($detracciones),1,0,'R',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("FORMA DE PAGO:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(40,6,utf8_decode("Banco"),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode("Tipo"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Op./Cheque"),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode("N° Cuenta"),1,0,'C',false);
	            $this->pdf->Cell(0,6,utf8_decode("Neto a Pagar"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(40,6,utf8_decode($banco),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode($tipo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($op),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode($cta_corriente),1,0,'C',false);
	            $this->pdf->Cell(0,6,'S/. '.utf8_decode($total_neto),1,0,'C',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','BI',8);
	            $this->pdf->Cell(20,6,utf8_decode("Emitido por:"),0,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($colaborador),0,0,'L',false);	         
	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle(utf8_decode("Liquidación GR").$idpago);
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);			   
	    }
		
		$this->pdf->Output("GR".$idpago.".pdf", 'I');
	}

	public function liquidacion_pdf2($id,$num){
        $this->load->library('Pdf');
        $this->pdf = new Pdf();
	    $liquidacion = $this->liquidacion_mdl->liquidacionpdf2($id); 
		$liquidacion_det = $this->liquidacion_mdl->liquidacionpdf_detalle($id);
		$detracciones=0;

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
		    	$detracciones = $l->detraccion;
		    	$fecha = $l->fecha_pago;
		    	$fecha = date('d/m/Y',strtotime($fecha));

		    	$igv = number_format((float)$igv, 2, '.', '');
		    	$subtotal = number_format((float)$subtotal, 2, '.', '');
		    	$total = number_format((float)$total, 2, '.', '');
		    }

		    //$num = printf('%08d', $id);
	    		
			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();      
	            $this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','I',10); 
	          	$this->pdf->MultiCell(0,6,'Fecha: '.$fecha,0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','BU',12);
	            $this->pdf->MultiCell(0,6,utf8_decode('ORDEN DE PAGO A PROVEEDORES')." Nro: L".$num,0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(50,6,utf8_decode("Nombres/Razón Social"),1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode($razon_social),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"DNI/RUC",1,0,'L',false);
	            $this->pdf->Cell(0,6,$ruc,1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"Concepto",1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode("Liquidación de Facturas"),1,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("N° Documentos:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(20,6,utf8_decode("CC"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Factura"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Orden Atención"),1,0,'C',false);
	            $this->pdf->Cell(80,6,utf8_decode("Paciente"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("Importe"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            foreach ($liquidacion_det as $ld) {            
	            $this->pdf->Cell(20,6,utf8_decode($ld->centro_costo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($ld->liqdetalle_numfact),1,0,'L',false);
	            $this->pdf->Cell(30,6,utf8_decode("OA".$ld->num_orden_atencion),1,0,'L',false);
	            $this->pdf->Cell(80,6,utf8_decode($ld->afiliado),1,0,'L',false);
	            $this->pdf->Cell(30,6,number_format((float)$ld->neto, 2, '.', ''),1,0,'R',false);
	            $this->pdf->Ln();
	            }
	            $detracciones = number_format((float)$detracciones, 2, '.', '');
	            $total_neto=$total-$detracciones;
	            $total_neto = number_format((float)$total_neto, 2, '.', '');
	            $this->pdf->SetFont('Arial','BI',10);
	            $this->pdf->Cell(160,6,utf8_decode("Total"),1,0,'R',false);
	            $this->pdf->Cell(30,6,utf8_decode($total),1,0,'R',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','BU',11);
	            $this->pdf->MultiCell(0,6,utf8_decode('DETALLE TOTAL A PAGAR'),0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(45,6,utf8_decode("SUB TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$subtotal,1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("IGV"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$igv,1,0,'R',false);	
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("N° CTA DETRACCIONES"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($cta_detracciones),1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$total,1,0,'R',false);	            
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("DETRACCIÓN 12%"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($detracciones),1,0,'R',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("FORMA DE PAGO:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(40,6,utf8_decode("Banco"),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode("Tipo"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Op./Cheque"),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode("N° Cuenta"),1,0,'C',false);
	            $this->pdf->Cell(0,6,utf8_decode("Neto a Pagar"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(40,6,utf8_decode($banco),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode($tipo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($op),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode($cta_corriente),1,0,'C',false);
	            $this->pdf->Cell(0,6,"S/. ".utf8_decode($total_neto),1,0,'C',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','BI',8);
	            $this->pdf->Cell(20,6,utf8_decode("Emitido por:"),0,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($colaborador),0,0,'L',false);	         
	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle(utf8_decode("Liquidación L").$num);
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);		
		
		$this->pdf->Output("L".$num.".pdf", 'I');
	}


	function view_reenviar($id,$num){
		$data['id'] = $id;
		$data['num'] = $num;
		$this->load->view('dsb/html/liquidacion/enviar_pdf.php',$data);
	}

	function reenviar_liquidacion(){
		$id = $_POST['id'];
		$idpago = $id;
		//Crear formato de liquidación
        $user = $this->session->userdata('user');
		extract($user);

		$data['idusuario'] = $idusuario;

		// //Crear formato de liquidación
		$this->load->library('Pdf');
        $this->pdf = new Pdf();
	    $liqgrupo = $this->liquidacion_mdl->getLiqGrupo($idpago);

	    foreach ($liqgrupo as $lg) {	    	
	    	$id = $lg->liqgrupo_id;
	    	$num = $lg->num;
	    	$liquidacion = $this->liquidacion_mdl->liquidacionpdf($id); 
		    $liquidacion_det = $this->liquidacion_mdl->liquidacionpdf_detalle($id);
		    $detracciones=0;

		    foreach ($liquidacion as $l){
		    	$idproveedor = $l->idproveedor;
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
		    	$detracciones = $l->detraccion;
		    	$fecha = $l->fecha_pago;
		    	$fecha = date('d/m/Y',strtotime($fecha));

		    	$igv = number_format((float)$igv, 2, '.', '');
		    	$subtotal = number_format((float)$subtotal, 2, '.', '');
		    	$total = number_format((float)$total, 2, '.', '');
		    }

		    //$num = printf('%08d', $id);
	    		
			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();      
	            $this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','I',10); 
	          	$this->pdf->MultiCell(0,6,'Fecha: '.$fecha,0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','BU',12);
	            $this->pdf->MultiCell(0,6,utf8_decode('ORDEN DE PAGO A PROVEEDORES')." Nro: L".$num,0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(50,6,utf8_decode("Nombres/Razón Social"),1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode($razon_social),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"DNI/RUC",1,0,'L',false);
	            $this->pdf->Cell(0,6,$ruc,1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(50,6,"Concepto",1,0,'L',false);
	            $this->pdf->Cell(0,6,utf8_decode("Liquidación de Facturas"),1,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("N° Documentos:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(20,6,utf8_decode("CC"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Factura"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Orden Atención"),1,0,'C',false);
	            $this->pdf->Cell(80,6,utf8_decode("Paciente"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("Importe"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            foreach ($liquidacion_det as $ld) {            
	            $this->pdf->Cell(20,6,utf8_decode($ld->centro_costo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($ld->liqdetalle_numfact),1,0,'L',false);
	            $this->pdf->Cell(30,6,utf8_decode("OA".$ld->num_orden_atencion),1,0,'L',false);
	            $this->pdf->Cell(80,6,utf8_decode($ld->afiliado),1,0,'L',false);
	            $this->pdf->Cell(30,6,number_format((float)$ld->neto, 2, '.', ''),1,0,'R',false);
	            $this->pdf->Ln();
	            }
	            $detracciones = number_format((float)$detracciones, 2, '.', '');
	            $total_neto=$total-$detracciones;
	            $total_neto = number_format((float)$total_neto, 2, '.', '');
	            $this->pdf->SetFont('Arial','BI',10);
	            $this->pdf->Cell(160,6,utf8_decode("Total"),1,0,'R',false);
	            $this->pdf->Cell(30,6,utf8_decode($total),1,0,'R',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','BU',11);
	            $this->pdf->MultiCell(0,6,utf8_decode('DETALLE TOTAL A PAGAR'),0,'C', false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(45,6,utf8_decode("SUB TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$subtotal,1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("IGV"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$igv,1,0,'R',false);	
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("N° CTA DETRACCIONES"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($cta_detracciones),1,0,'R',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(45,6,utf8_decode("TOTAL"),1,0,'R',false);
	            $this->pdf->Cell(45,6,$total,1,0,'R',false);	            
	            $this->pdf->Cell(5,6,"",0,0,'L',false);            
	            $this->pdf->Cell(45,6,utf8_decode("DETRACCIÓN 12%"),1,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($detracciones),1,0,'R',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(50,6,utf8_decode("FORMA DE PAGO:"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','BI',9);
	            $this->pdf->Cell(40,6,utf8_decode("Banco"),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode("Tipo"),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode("N° Op./Cheque"),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode("N° Cuenta"),1,0,'C',false);
	            $this->pdf->Cell(0,6,utf8_decode("Neto a Pagar"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(40,6,utf8_decode($banco),1,0,'C',false);
	            $this->pdf->Cell(40,6,utf8_decode($tipo),1,0,'C',false);
	            $this->pdf->Cell(30,6,utf8_decode($op),1,0,'C',false);
	            $this->pdf->Cell(50,6,utf8_decode($cta_corriente),1,0,'C',false);
	            $this->pdf->Cell(0,6,"S/. ".utf8_decode($total_neto),1,0,'C',false);
	            $this->pdf->Ln(15);
	            $this->pdf->SetFont('Arial','BI',8);
	            $this->pdf->Cell(20,6,utf8_decode("Emitido por:"),0,0,'R',false);
	            $this->pdf->Cell(0,6,utf8_decode($colaborador),0,0,'L',false);
	         
	            $this->pdf->Line(10, 280 , 200, 280); 
			    $this->pdf->SetTitle(utf8_decode("Liquidación GR").$idpago);
			    $this->pdf->SetLeftMargin(15);
			    $this->pdf->SetRightMargin(15);
			    $this->pdf->SetFillColor(200,200,200);			   
	    }

		$this->pdf->Output("uploads/GR".$idpago.".pdf", 'F');

		// Enviar email de liquidación a proveedor 
		    $user = $this->session->userdata('user');
			extract($user);

			$liquidacionDet = $this->liquidacion_mdl->getLiquidacionDet($id);
			$liquidacion_grupo = $this->liquidacion_mdl->getLiquidacionGrupo($id);
			$gasto_detalle = $this->liquidacion_mdl->pagoDet($idpago);

				$tipo="'Century Gothic'";
				$texto='<p>Estimado(a) '.$razon_social.' se ha realizado el pago de las siguientes liquidaciones, se adjunta el detalle y constancia de pago de las mismas.</p>
				<table class="table table-striped table-bordered table-hover"  style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;" border="1">
							<thead>
								<tr>
									<th>N° Liquidación</th>
									<th>Importe Inc. IGV</th>
									<th>Importe Detracción</th>
								</tr>
							</thead>
							<tbody>';

						foreach ($gasto_detalle as $gd) {
							$total = $gd->total;
							$total = number_format((float)$total, 2, '.', '');
							$detraccion = $gd->detraccion;
							$detraccion = number_format((float)$detraccion, 2, '.', '');
						$texto.='<tr>
									<td>L'.$gd->liq_num.'</td>
									<td style="text-align: right;"> S/.'.$total.'</td>';

						if($detraccion>0){
						$texto.='<td style="text-align: right;"> S/.'.$detraccion.'</td>';
						}else{
							$texto.='<td style="text-align: right;">No afecto/td>';
						}
						$texto.='</tr>';
						}
					$texto.='</tbody>
						</table>';
			
			$mail = new PHPMailer;	
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	        $mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->SMTPSecure = 'false';
	        $mail->Port     = 25;	
			// Armo el FROM y el TO
			$destinatarios = $this->liquidacion_mdl->getDestinatarios($idproveedor);
			$mail->setFrom($correo_laboral, 'Red Salud');
			$mail->addAddress($correo_laboral, $nombres_col);
			$mail->addAddress("pvigil@red-salud.com", "Pilar Vigil");
			$mail->addAddress("aluna@red-salud.com", "Angie Luna");
			if(!empty($destinatarios)){
				foreach ($destinatarios as $d) {
					$mail->addAddress($d->email_cp, $d->nombres_cp);
				}
			}
			// El asunto
			$mail->Subject = "NOTIFICACION RED SALUD: LIQUIDACION DE FACTURAS";
			// El cuerpo del mail (puede ser HTML)
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                <br>
	                '.$texto.'
	                <br>
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail->IsHTML(true);
			$mail->addAttachment('uploads/'.$idpago.'.pdf', 'Constancia de pago GR'.$idpago.'.pdf');
            $mail->addAttachment('uploads/GR'.$idpago.'.pdf', 'Formato GR'.$idpago.'.pdf');
			$mail->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail->send();

		unlink('uploads/GR'.$idpago.'.pdf');
		echo "<script>
				alert('Se reenvió el correo electrónico con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}
}