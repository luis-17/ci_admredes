<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class plan_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('plan_mdl');
        $this->load->library("pagination");
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

			$planes = $this->plan_mdl->getPlanes();
			$data['planes'] = $planes;

			$this->load->view('dsb/html/plan/plan.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_cobertura($id,$nom)
	{
		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$operador=$this->plan_mdl->get_operador();
		$data['operador'] = $operador;

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;
		$data['cadena'] = "";

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
	}

	public function plan_editar($id,$nom)
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

			$data['nom'] = $nom;
			$data['id'] = $id;

			$clientes = $this->plan_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['accion'] = 'Editar Plan';

			if($id>0):
				$plan = $this->plan_mdl->getPlan($id);
				$data['plan'] = $plan;	
			endif;

			$this->load->view('dsb/html/plan/plan_editar.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_registrar()
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

			$data['nom'] = 'Nuevo Plan';
			$data['id'] = 0;

			$clientes = $this->plan_mdl->getClientes();
			$data['clientes'] = $clientes;

			$data['accion'] = 'Registrar Plan';

			$this->load->view('dsb/html/plan/plan_editar.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan_guardar()
	{
		$data['idplan'] = $_POST['idplan'];
		$data['cliente'] = $_POST['cliente'];
		$data['nombre_plan'] = $_POST['nombre_plan'];
		$data['codigo_plan'] = $_POST['codigo_plan'];
		$data['carencia'] = $_POST['carencia'];
		$data['mora'] = $_POST['mora'];
		$data['atencion'] = $_POST['atencion'];
		$data['prima'] = $_POST['prima'];
		$data['prima_adicional'] = $_POST['prima_adicional'];
		$data['num_afiliados'] = $_POST['num_afiliados'];
		$data['flg_activar'] = $_POST['flg_activar'];
		$data['flg_cancelar'] = $_POST['flg_cancelar'];
		$data['flg_dependientes'] = $_POST['flg_dependientes'];

		if($_POST['idplan']==0):
			$this->plan_mdl->insert_plan($data);
			$data['idplan'] = $this->db->insert_Id();
			$asunto="NOTIFICACION: CREACION DE UN PLAN DE SALUD";
			$accion="creaci&oacute;n";
			else:
				$this->plan_mdl->update_plan($data);
				$asunto="NOTIFICACION: ACTUALIZACION DE PLAN DE SALUD: ".$data['nombre_plan'];
				$accion="actualizaci&oacute;n";
		endif;

		$user = $this->session->userdata('user');
		extract($user);

		$id=$data['idplan'];

		$plan = $this->plan_mdl->getPlan($id);

			//email para proveedor
			foreach ($plan as $p) {

				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@s,</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se informa la '.$accion.' del plan de salud: '.$p->nombre_plan.' con los siguientes datos:</p>
					<table align="center" border="1" width="90%" style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
						<tr>
							<th>Cliente:</th>
							<td>'.$p->nombre_comercial_cli.'</td>
							<th>Nombre del Plan:</th>
							<td>'.$p->nombre_plan.'</td>							
						</tr>
						<tr>
							<th>D&iacute;as de Carencia:</th>
							<td>'.$p->dias_carencia.'</td>
							<th>D&iacute;as de Mora:</th>
							<td>'.$p->dias_mora.'</td>
						</tr>
						<tr>
							<th>Frecuencia en la Atenci&oacute;n: </th>
							<td>'.$p->dias_atencion.'</td>
							<th>L&iacute;mite de Afiliados por Certificado:</th>
							<td>'.$p->num_afiliados.'</td>
						</tr>
						<tr>
							<th>Prima(S/.) Inc. IGV: </th>
							<td>'.$p->prima_monto.'</td>
							<th>Prima por Adicional(S/.) Inc. IGV:</th>
							<td>'.$p->prima_adicional.'</td>
						</tr>
						<tr>
							<th colspan="2">Permite la activaci&oacute;n manual desde Admin?: </th>
							<td colspan="2">'.$p->flg_activar.'</td>
						</tr>
						<tr>
							<th colspan="2">Permite la afiliaci&oacute;n de dependientes desde Admin?: </th>
							<td colspan="2">'.$p->flg_dependientes.'</td>
						</tr>
						<tr>
							<th colspan="2">Permite la cancelaci&oacute;n desde Admin?: </th>
							<td colspan="2">'.$p->flg_cancelar.'</td>
						</tr>
					</table>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}
			
			$mail = new PHPMailer;		
			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, $nombres_col);
			$mail->addAddress('ivasquez@red-salud.com', 'Iván Vásquez');
			$mail->addAddress('aluna@red-salud.com', 'Angie Luna');
			$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = $asunto;
			// El cuerpo del mail (puede ser HTML)
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="http://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="http://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="http://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail->send();

		redirect('index.php/plan');
	}

	function guardar_cobertura()
	{
		$data['nom'] = $_POST['nom'];
		$data['id'] = $_POST['idplan'];
		$data['iddet'] = $_POST['idplandetalle'];
		$data['item'] = $_POST['item'];
		$data['descripcion'] = $_POST['descripcion'];
		$data['visible'] = $_POST['visible'];
		$data['flg_liqui'] = $_POST['flg_liqui'];
		$data['tiempo'] = $_POST['eventos'];
				
		if($_POST['eventos']==""){
			$data['num_eventos'] = "";
		}else{
			$data['num_eventos'] = $_POST['num_eventos'];
		}
		
		if($_POST['flg_liqui']=='No'){
			$data['valor'] = '';
			$data['operador'] = '';
			}else{
				$data['valor'] = $_POST['valor'];
				$data['operador'] = $_POST['operador'];
		}

		if($_POST['idplandetalle']==0){
			$caso=1;
			$this->plan_mdl->insert_cobertura($data);
			$data['iddet'] = $this->db->insert_id();
			$prod = $_POST['prod'];
			if(!empty($prod)){
			$cont = count($prod);
				for($i=0;$i<$cont;$i++){
				$data['idprod'] = $prod[$i];
				$this->plan_mdl->insert_proddet($data);
				
				}
			}
		}else{
				$this->plan_mdl->update_cobertura($data);
				$caso=2;
		}

		if($caso==2){
			$user = $this->session->userdata('user');
			extract($user);

			$cob = $this->plan_mdl->selecionar_cobertura2($data['iddet']);

			//email para proveedor
			foreach ($cob as $c) {
				
				switch ($c->tiempo) {
					case '1 month':
						$tiempo= "Menual(es)";
					break;															
					case '2 month':
						$tiempo= "Bimestral(es)";
					break;
					case '3 month':
						$tiempo= "Trimestral(es)";
					break;
					case '6 month':
						$tiempo= "Semestral(es)";
					break;
					case '1 year':
						$tiempo= "Anual(es)";
					break;
					case 'ilimitados':
						$tiempo= "Ilimitados";
					break;
					default:
						$tiempo="Sin evento";
					break;
				}
				$plan=$c->nombre_plan;

				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@s,</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se informa la actualizaci&oacute;n de una de las coberturas para el plan de salud: '.$plan.' con los siguientes datos:</p>
					<table align="center" border="1" width="90%" style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
						<tr>
							<th>Cobertura:</th>
							<td>'.$c->nombre_var.'</td>
							<th>Descripci&oacute;n:</th>
							<td>'.$c->texto_web.'</td>							
						</tr>
						<tr>
							<th>Validaci&oacute;n:</th>
							<td>'.$c->descripcion.' '.$c->valor_detalle.'</td>
							<th>Eventos:</th>
							<td>'.$c->num_eventos.' '.$tiempo.'</td>
						</tr>						
					</table>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}
			
			$mail = new PHPMailer;		
			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, $nombres_col);
			$mail->addAddress('ivasquez@red-salud.com', 'Iván Vásquez');
			$mail->addAddress('aluna@red-salud.com', 'Angie Luna');
			$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "NOTIFICACION: ACTUALIZACION DE COBERTURA - ".$plan;
			// El cuerpo del mail (puede ser HTML)
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="http://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="http://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="http://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail->send();
		}		

		$cobertura = $this->plan_mdl->getCobertura($data['id']);
		$data['cobertura'] = $cobertura;
		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;
		$data['iddet'] = 0;
		$data['cadena'] = "";
		$operador=$this->plan_mdl->get_operador();
		$data['operador'] = $operador;
		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function plan_anular($id)
 	{
 		$this->plan_mdl->plan_anular($id);
 		redirect('index.php/plan');
 	}

 	function plan_activar($id)
 	{
 		$this->plan_mdl->plan_activar($id);
 		redirect('index.php/plan');
 	}

 	function cobertura_anular($id, $nom, $iddet)
 	{
 		$data['idplandetalle'] = $iddet;
 		$this->plan_mdl->cobertura_anular($iddet);
 		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function cobertura_activar($id, $nom, $iddet)
 	{
 		$data['idplandetalle'] = $iddet;
 		$this->plan_mdl->cobertura_activar($iddet);

 		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;	

		$data['nom'] = $nom;
		$data['id'] = $id;
		$data['iddet'] = 0;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function seleccionar_cobertura($id, $nom, $iddet)
 	{
 		$data['iddet'] = $iddet;
 		$data['nom'] = $nom;
		$data['id'] = $id;

 		$detalle = $this->plan_mdl->selecionar_cobertura($iddet);
		$data['detalle'] = $detalle;

		$cobertura = $this->plan_mdl->getCobertura($id);
		$data['cobertura'] = $cobertura;

		$items = $this->plan_mdl->getItems();
		$data['items'] = $items;

		$operador=$this->plan_mdl->get_operador();
		$data['operador'] = $operador;

		$productos = $this->plan_mdl->get_productos2($iddet);
		if(!empty($productos)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
					<div class="col-sm-9">
						<table>';
			foreach ($productos as $pr) {

				$link="window.location.href='".base_url()."index.php/".$pr->funcion."/".$pr->idproducto."/".$iddet."/".$id."/".$nom."'";

				$cadena.='<tr>
							<td><input type="checkbox" onclick="'.$link.'" '.$pr->checked.'></td>
							<td>'.$pr->descripcion_prod.'</td>
						</tr>';
			}
			$cadena.='</table></div>';
		}else{
			$cadena="";
		}
		$data['cadena'] = $cadena;

		$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
 	}

 	function plan_email($id,$nom)
 	{
 		$data['idplan'] = $id;
 		$data['nom'] = $nom;
 		$plan = $this->plan_mdl->getPlan($id);
		$data['plan'] = $plan;	

 		$this->load->view('dsb/html/plan/plan_email.php',$data);
 	}

 	function guardar_email()
 	{
 		$data['cuerpo_mail'] = $_POST['cuerpo_mail'];
 		$data['idplan'] = $_POST['idplan'];

 		$this->plan_mdl->save_mail($data);

 		echo "<script>
				alert('El contenido del email ha sido actualizado con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
 	}

 	public function detalle_producto()
	{
		$item = $_POST['id'];

		$productos = $this->plan_mdl->get_productos($item);
		if(!empty($productos)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Detalle: </label>
					<div class="col-sm-9">
						<table>';
			foreach ($productos as $pr) {
				$cadena.="<tr>
							<td><input type='checkbox' name='prod[]' value='".$pr->idproducto."'></td>
							<td>".$pr->descripcion_prod."</td>
						</tr>";
			}

		$cadena.='</table></div>';
		}else{
			$cadena="<input type='hidden' id='prod' name='prod' value=''>";
		}

		echo $cadena;
	}

	public function eliminar_producto($idprod,$iddet,$id,$nom)
	{
		$this->plan_mdl->eliminar_producto($idprod,$iddet);
		redirect("index.php/seleccionar_cobertura/".$id."/".$nom."/".$iddet);
	}

	public function insertar_producto($idprod,$iddet,$id,$nom)
	{
		$data['iddet'] = $iddet;
		$data['idprod'] = $idprod;

		$this->plan_mdl->insert_proddet($data);
		redirect("index.php/seleccionar_cobertura/".$id."/".$nom."/".$iddet);
	}
}