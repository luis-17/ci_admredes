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
		else{
			redirect('/');
		}
		
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
			$mail->isSMTP();
	        $mail->Host     = 'localhost';;
	        $mail->SMTPAuth = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->SMTPSecure = 'false';
	        $mail->Port     = 25;	
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
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
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
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$accion = $_POST['guardar'];
			if($accion=='guardar'){

			$menuLista = $this->menu_mdl->getMenu($idusuario);
			$data['menu1'] = $menuLista;

			$submenuLista = $this->menu_mdl->getSubMenu($idusuario);
			$data['menu2'] = $submenuLista;

			$data['nom'] = $_POST['nom'];
			$data['id'] = $_POST['idplan'];
			$data['iddet'] = $_POST['idplandetalle'];
			$data['item'] = $_POST['item'];
			$data['descripcion'] = $_POST['descripcion'];
			$data['visible'] = $_POST['visible'];
			if($_POST['inicio']==1){
				$num1=$_POST['num'];
				$tiempo1=$_POST['tiempo'];
				$data['iniVig'] = $num1.' '.$tiempo1;
			}else{
				$data['iniVig'] = 0;
			}

			if($_POST['fin']==1){
				$num2=$_POST['num2'];
				$tiempo2=$_POST['tiempo2'];
				$data['finVig'] = $num2.' '.$tiempo2;
			}else{
				$data['finVig'] = 0;
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

				/*//email para proveedor
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
				$mail->isSMTP();
		        $mail->Host     = 'relay-hosting.secureserver.net';;
		        $mail->SMTPAuth = false;
		        $mail->Username = '';
		        $mail->Password = '';
		        $mail->SMTPSecure = 'false';
		        $mail->Port     = 25;	
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
		                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
		                </div>
		                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
		                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
		                '.$texto.'
		                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
		                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
		                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
		                </div>
		                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
		                </div>
		            </body>
					</html>';
				$mail->IsHTML(true);
				$mail->CharSet = 'UTF-8';
				// Los archivos adjuntos
				//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
				//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
				// Enviar
				$mail->send();*/
			}		

			$cobertura = $this->plan_mdl->getCobertura($data['id']);
			$data['cobertura'] = $cobertura;
			$items = $this->plan_mdl->getItems();
			$data['items'] = $items;
			$data['iddet'] = 0;
			$data['cadena'] = "";
			$operador=$this->plan_mdl->get_operador();
			$data['operador'] = $operador;

			$id = $_POST['idplan'];
			$nom = $_POST['nom'];
			redirect('index.php/plan_cobertura/'.$id.'/'.$nom);
			}else{				
			$id = $_POST['idplan'];
			$nom = $_POST['nom'];

			redirect('index.php/plan_cobertura/'.$id.'/'.$nom);
			}
		}
		else{
			redirect('/');
		}		
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
 		$siniestro_detalle = $this->plan_mdl->getSiniestroDetalle($iddet);
 		$cant = count($siniestro_detalle);
 			$this->plan_mdl->cobertura_anular($iddet); 	
 		echo "<script>
				alert('Se anuló la cobertura con éxito.');
				location.href='".base_url()."index.php/plan_cobertura/".$id."/".$nom."';
				</script>";
 	}

 	function cobertura_activar($id, $nom, $iddet)
 	{
 		$data['idplandetalle'] = $iddet;
 		$this->plan_mdl->cobertura_activar($iddet);
 		
 		echo "<script>
				alert('Se activó la cobertura con éxito.');
				location.href='".base_url()."index.php/plan_cobertura/".$id."/".$nom."';
				</script>";
 	}

 	function seleccionar_cobertura($id, $nom, $iddet)
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
							<table style="font-size:12px;"  width="100%">							
								<tr><td colspan="2"><input type="checkbox" id="checkTodos" /><b>Marcar/Desmarcar todos</b></td></tr>';
				$cont1=2;
				$cont2=0;
				foreach ($productos as $pr) {

					$link="window.location.href='".base_url()."index.php/".$pr->funcion."/".$pr->idproducto."/".$iddet."/".$id."/".$nom."'";

					if($cont1==2){
					$cadena.='<tr>';
					}
						$cadena.='<td width="1%"><input type="checkbox" onclick="'.$link.'" '.$pr->checked.'></td>
								<td width="49%">'.$pr->descripcion_prod.'</td>';

					if($cont2==1){
						$cont1=2;
					}else{
						$cont1=0;
					}
					$cont2++;
					if($cont1==2){
					$cadena.='</tr>';
					$cont2=0;	
					}	
				}
				$cadena.='</table></div>';
				$cadena.="<script>
						$('document').ready(function(){
						   $('#checkTodos').change(function () {
						      $('input:checkbox').prop('checked', $(this).prop('checked'));
						  });
						});
						</script>";
			}else{
				$cadena="";
			}
			$data['cadena'] = $cadena;

			$this->load->view('dsb/html/plan/plan_cobertura.php',$data);
		}
		else{
			redirect('/');
		}
 		
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
						<table style="font-size: 12px;" width="100%">
							<tr><td colspan="2"><input type="checkbox"  id="checkTodos"><b>Marcar/Desmarcar Todos</b></td></tr>';
			$cont1=2;
			$cont2=0;
			foreach ($productos as $pr) {
				if($cont1==2){
				$cadena.='<tr>';
				}
					$cadena.="<td width='1%'><input type='checkbox' name='prod[]' value='".$pr->idproducto."'></td>
							<td width='49%'>".$pr->descripcion_prod."</td>";

				if($cont2==1){
					$cont1=2;
				}else{
					$cont1=0;
				}
				$cont2++;
				if($cont1==2){
				$cadena.='</tr>';
				$cont2=0;	
				}				
			}

		$cadena.='</table></div>';
		$cadena.="<script>
					$('document').ready(function(){
					   $('#checkTodos').change(function () {
					      $('input:checkbox').prop('checked', $(this).prop('checked'));
					  });
					});
					</script>";
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

	public function eventos($id)
	{
		$eventos = $this->plan_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['num'] = $eventos['num_eventos'];
		$data['tiempo'] = $eventos['tiempo'];
		$data['id'] = $id;
		$this->load->view('dsb/html/plan/eventos.php',$data);
	}

	public function reg_evento()
	{
		$data['id'] = $_POST['id'];		
		$accion = $_POST['guardar'];

		if($accion=="guardar"){
			$data['num_eventos'] = $_POST['numero'];
			$data['tiempo'] = $_POST['periodo'];	
			$this->plan_mdl->upEventos($data);
			echo "<script>
				alert('Se actualizó el número de eventos con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";		
		}else{
			$data['num_eventos']=0;
			$data['tiempo'] ="";
			$this->plan_mdl->upEventos($data);
			echo "<script>
				alert('Se eliminó el número de eventos con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
		}
		
	}

	public function bloqueo($id){
		$eventos = $this->plan_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['id'] = $id;
		$data['coberturas'] = $this->plan_mdl->getCoberturasActivas($id);
		$data['bloqueos'] = $this->plan_mdl->getBloqueos($id);
		$this->load->view('dsb/html/plan/bloqueos.php',$data);
	}

	public function reg_bloqueo(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$id = $_POST['id'];
		$data['id'] = $id;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['cob_bloqueada'] = $_POST['cob_bloqueada'];
		$this->plan_mdl->reg_bloqueo($data);

		echo "<script>
				alert('Se bloqueó la cobertura con éxito.');
				location.href='".base_url()."index.php/bloqueo/".$id."';
				</script>";
 	}

 	public function anular_bloqueo($idbloqueo,$id){
 		$this->plan_mdl->delete_bloqueo($idbloqueo);
 		echo "<script>
				alert('Se eliminó el bloqueo con éxito.');
				location.href='".base_url()."index.php/bloqueo/".$id."';
				</script>";
 	}

 	public function coaseguro($id){
 		$eventos = $this->plan_mdl->getEventos($id);
		$data['nom'] = $eventos['nombre_var'];
		$data['id'] = $id;
		$data['operador'] = $this->plan_mdl->getOperador();
		$data['coaseguros'] = $this->plan_mdl->getCoaseguros($id);
		$this->load->view('dsb/html/plan/coaseguros.php',$data);
 	}

 	public function reg_coaseguro(){
 		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['idoperador'] = $_POST['operador'];
		$data['valor'] = $_POST['valor'];
		$id = $_POST['id'];
		$data['id'] = $id;

		$this->plan_mdl->inCoaseguro($data);
		echo "<script>
				alert('Se registró el coaseguro con éxito.');
				location.href='".base_url()."index.php/coaseguro/".$id."';
				</script>";
 	}

 	public function anular_coaseguro($idcoaseguro,$id){
 		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['hoy'] = date('Y-m-d H:i:s');
		$data['idcoaseguro'] = $idcoaseguro;

		$this->plan_mdl->upCoaseguro($data);
		echo "<script>
				alert('Se eliminó el coaseguro con éxito.');
				location.href='".base_url()."index.php/coaseguro/".$id."';
				</script>";
 	}

 	public function centro_costos(){
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

			$this->load->view('dsb/html/tablas_maestras/centro_costos.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function add_cc($id){
 		$plan = $this->plan_mdl->getPlan($id);
 		foreach ($plan as $p) {
 			$data['centro_costo'] = $p->centro_costo;
 			$data['cliente'] = $p->nombre_comercial_cli;
 			$data['nombre_plan'] = $p->nombre_plan;
 		}
 		$data['idplan'] = $id;
 		$this->load->view('dsb/html/tablas_maestras/add_cc.php',$data);
 	}

 	public function reg_cc(){
 		$data['idplan'] = $_POST['idplan'];
 		$data['cc'] = $_POST['numero'];
 		$this->plan_mdl->up_cc($data);
		echo "<script>
				alert('Se actualizó el centro de costo con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
 	}
 	
}