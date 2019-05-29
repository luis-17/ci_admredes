<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadodetalle_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('certificado_mdl');
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
	public function index($id,$doc)
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

			$certificado = $this->certificado_mdl->getCertificado($id);	
			$data['certificado'] = $certificado;

			$contratante = $this->certificado_mdl->getContratante($id);
			$data['contratante'] = $contratante;

			$asegurado = $this->certificado_mdl->getAsegurados($id);	
			$data['asegurado'] = $asegurado;

			$data['cobertura_operador'] = $this->certificado_mdl->getCoberturasOperador($id);
			$data['coberturas'] = $this->certificado_mdl->getCoberturas($id);
			$afiliado =  $this->certificado_mdl->getNomAfiliado($doc);
			$data['afiliado'] = $afiliado['asegurado'];
			$data['nombre'] = $afiliado['nombre'];
 			$cobros = $this->certificado_mdl->getCobros($id);	
			$data['cobros'] = $cobros;
			$data['doc']=$doc;
			$data['id2'] = $id;
			$ubigeo=$this->certificado_mdl->ubigeo();
			$data['ubigeo']=$ubigeo;
			$provincia2=$this->certificado_mdl->provincia2($data);
			$data['provincia2']=$provincia2;
			$distrito2=$this->certificado_mdl->distrito2($data);
			$data['distrito2']=$distrito2;

			$this->load->view('dsb/html/certificado/certificado_detalle.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function aseg_atenciones($id,$cert){

		$atenciones = $this->certificado_mdl->getAtenciones($id,$cert);
		$data['atenciones'] = $atenciones;	

		$this->load->view('dsb/html/certificado/aseg_atenciones.php', $data);
	}

	public function aseg_editar($id){
		$ubigeo=$this->certificado_mdl->ubigeo();
		$data['aseg_id'] = $id;
		$data['ubigeo']=$ubigeo;
		$provincia3=$this->certificado_mdl->provincia3($data);
		$data['provincia3']=$provincia3;
		$distrito3=$this->certificado_mdl->distrito3($data);
		$data['distrito3']=$distrito3;
		$asegurado = $this->certificado_mdl->getAseg_editar($id);
		$data['asegurado'] = $asegurado;	

		$this->load->view('dsb/html/certificado/aseg_editar.php', $data);
	}

	public function aseg_save(){
		$datos['genero'] = $_POST['genero'];
		$datos['direccion'] = $_POST['direccion'];
	}

	public function activar_certificado($id,$doc)
	{
		$activar_certificado = $this->certificado_mdl->activar_certificado($id);
		$ruta='index.php/certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function cancelar_certificado($id,$doc)
	{
		$cancelar_certificado = $this->certificado_mdl->cancelar_certificado($id);
		$ruta='index.php/certificado_detalle/'.$id.'/'.$doc;
		redirect ($ruta);
	}

	public function seleccionar_proveedor($id, $idaseg, $certase_id, $fin){
		$data['cert_id'] = $id;
		$data['aseg_id'] =$idaseg;
		$data['certase_id'] = $certase_id;
		$data['max'] = $fin;

		$proveedores = $this->certificado_mdl->getProveedores();
		$data['proveedores'] = $proveedores;
		$this->load->view('dsb/html/certificado/seleccionar_proveedor.php',$data);
	}
	public function reservar_cita($id, $idaseg, $cita, $certase_id, $fin, $idprov)
	{
		$data['cert_id'] = $id;
		$data['aseg_id'] =$idaseg;
		$data['cita'] = $cita;
		$data['certase_id'] = $certase_id;
		$data['max'] = $fin;
		$data['idprov'] = $idprov;

		$afiliado =  $this->certificado_mdl->getNomAfiliado2($idaseg);
		$data['afiliado'] = $afiliado['asegurado'];
		$data['nombre'] = $afiliado['nombre'];

		$asegurado = $this->certificado_mdl->getAsegurado($id);
		$data['asegurado'] = $asegurado;

		$citas = $this->certificado_mdl->getCitas();
		$data['citas'] = $citas;

		$proveedores = $this->certificado_mdl->getProveedores();
		$data['proveedores'] = $proveedores;

		$productos = $this->certificado_mdl->getProductos($id);
		$data['productos'] = $productos;

		if($cita==0){
			$data['getcita']="";
			if($idprov==0){
				$data['estado_prov2'] = '';
			}else{
				$data['estado_prov2'] = 'readonly="readonly"';
			}
		}else{
			$data['estado_prov2'] = '';
			$getcita=$this->certificado_mdl->getCita($data);
			$data['getcita']=$getcita; 
		}
		$this->load->view('dsb/html/certificado/reservar_cita.php',$data);
	}

	public function save_cita()
	{
		$user = $this->session->userdata('user');
		extract($user);
		date_default_timezone_set('America/Lima');
		$aseg_id = $_POST['aseg_id'];		
		$cert_id = $_POST['cert_id'];
		$data['aseg_id'] = $_POST['aseg_id'];
		$data['cert_id'] = $_POST['cert_id'];
		$data['inicio'] = $_POST['inicio'];
		$data['fin'] = $_POST['fin'];
		$data['certase_id'] = $_POST['certase_id'];
		$data['idproveedor'] = $_POST['proveedor'];	
		$data['idespecialidad'] = $_POST['producto'];	
		$data['estado'] = $_POST['estado'];	
		$data['fecha_cita'] = $_POST['feccita'];
		$data['obs'] = $_POST['obs'];
		$data['idusuario'] = $idusuario;
		$data['idsiniestro'] = $_POST['idsiniestro'];
		$afiliado =  $this->certificado_mdl->getNomAfiliado2($aseg_id);
		$data['afiliado'] = $afiliado['asegurado'];
		$data['nombre'] = $afiliado['nombre'];
		$data['idusuario_confirma'] = $idusuario;
		$data['hoy'] = date("Y-m-d H:i:s");

		if($data['idsiniestro']==''){
			$this->certificado_mdl->saveCalendario($data);
			$id = $this->db->insert_id();
			$data['idcita'] =  $id;
			$num = $this->certificado_mdl->num_orden_atencion();
			foreach ($num as $n) {
				$numero=$n->num_orden_atencion;
				$data['num'] = $numero;
			}
			$this->certificado_mdl->savePreOrden($data);
			$data['mensaje'] = 2;
		}else{
			$data['idcita'] = $_POST['idcita'];
			$this->certificado_mdl->updateCalendario($data);
			$this->certificado_mdl->updatePreOrden($data);
			$data['mensaje'] = 3;
		}
		
		if($data['estado']==2){

			$user = $this->session->userdata('user');
			extract($user);

			$contenido = $this->certificado_mdl->contenido_mail($data);
			//email para proveedor
			foreach ($contenido as $c) {
				$plan=$c->nombre_plan;
				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@, '.$c->nombre_comercial_pr.'</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se confirma la reserva de atenci&oacute;n m&eacute;dica ambulatoria con los siguientes datos:</p>
					<table align="center" border="1" width="90%">
						<tr>
							<th>DNI Afiliado:</th>
							<td>'.$c->aseg_numDoc.'</td>
							<th>Nombre del Afiliado:</th>
							<td>'.$c->afiliado.'</td>
						</tr>
						<tr>
							<th>Fecha de Nacimiento:</th>
							<td>'.$c->aseg_fechNac.'</td>
							<th>Especialidad:</th>
							<td>'.$c->nombre_esp.'</td>
						</tr>
						<tr>
							<th>Fecha y Hora de reserva: </th>
							<td>'.$c->fecha_cita.' '.$c->hora_cita_inicio.'</td>
							<th>Observaciones:</th>
							<td>'.$c->observaciones_cita.'</td>
						</tr>
					</table>
					'.$c->cuerpo_mail.'
					<p>Para dudas &oacute; consultas comun&iacute;quese a nuestra central en Lima: 01-445-3019 &oacute; provincia: 0800-47676</p>
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}
			
			$mail = new PHPMailer;
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->Port     = 25;		
			// Armo el FROM y el TO
			$mail->setFrom('contacto@red-salud.com', 'Red Salud');
			$destinatarios = $this->certificado_mdl->destinatarios($data);
			if(!empty($destinatarios)){
				foreach ($destinatarios as $d) {				
					$mail->addAddress($d->email_cp, $d->nombres);
				}
			}else {
				$texto='<div><p>El proveedor no registra contactos con env&iacute;o a email de reservas de atenciones.</p></div>';
				$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "RESERVA DE CONSULTA MEDICA - ".$plan;
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

			//email para afiliado
			 foreach ($contenido as $c2) {
				$plan2=$c2->nombre_plan;
				$to=$c2->aseg_email;
				$nom=$c2->afiliado;
				$texto2='<div><p>Afiliado(a), '.$nom.'</p>
					<p> Sabemos lo importante que es para ti el cuidado de tu salud, <b>tu cita m&eacute;dica ha sido reservada con &eacute;xito. </b>Te detallamos los datos registrados:</p>
					<table align="center" border="1" width="90%"> 
						<tr>
							<th>DNI Afiliado:</th>
							<td>'.$c2->aseg_numDoc.'</td>
							<th>Nombre del Afiliado:</th>
							<td>'.$c2->afiliado.'</td>
						</tr>
						<tr>
							<th>Centro M&eacute;dico:</th>
							<td>'.$c2->nombre_comercial_pr.'</td>
							<th>Especialidad:</th>
							<td>'.$c2->nombre_esp.'</td>
						</tr>
						<tr>
							<th>Fecha y Hora de reserva: </th>
							<td>'.$c2->fecha_cita.' '.$c2->hora_cita_inicio.'</td>
							<th>Observaciones:</th>
							<td>'.$c2->observaciones_cita.'</td>
						</tr>
					</table>
					<p>Recordar que la hora de reserva es referencial y la atenci&oacute;n se realiza por orden de llegada. Si tienes alguna consulta adicional comun&iacute;cate a nuestra central telef&oacute;nica en Lima: 01-445-3019 &oacute; provincia: 0800-47676</p>
					<p><b>Estamos agradecidos por la confianza brindada.</b></p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}
			$mail2 = new PHPMailer;
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail2->Host = 'localhost';
	        $mail2->SMTPAuth = false;
	        $mail2->SMTPSecure = false;
	        $mail2->Username = '';
	        $mail2->Password = '';
	        $mail2->Port     = 25;	
			// Armo el FROM y el TO
			$mail2->setFrom($correo_laboral, 'Red Salud');			
			if($to!==''){			
				$mail2->addAddress($to, $nom);
			}else {
				$texto2='<div><p>El asegurado no cuenta con un correo electrónico registrado.</p></div>';
				$mail2->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail2->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail2->Subject = "RESERVA DE CONSULTA MEDICA - ".$plan2;
			// El cuerpo del mail (puede ser HTML)
			$mail2->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" /> 
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto2.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail2->IsHTML(true);
			$mail2->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail2->send();


		}else{
			if($idtipousuario<>5){
				$contenido = $this->certificado_mdl->contenido_mail($data);
			//email para proveedor
			foreach ($contenido as $c) {
				$plan=$c->nombre_plan;
				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@, '.$c->nombre_comercial_pr.'</p>
					<p>Est&aacute; pendiente gestionar la reserva sin confirmar con los siguientes datos:</p>
					<table align="center" border="1" width="90%">
						<tr>
							<th>DNI Afiliado:</th>
							<td>'.$c->aseg_numDoc.'</td>
							<th>Nombre del Afiliado:</th>
							<td>'.$c->afiliado.'</td>
						</tr>
						<tr>
							<th>Fecha de Nacimiento:</th>
							<td>'.$c->aseg_fechNac.'</td>
							<th>Especialidad:</th>
							<td>'.$c->nombre_esp.'</td>
						</tr>
						<tr>
							<th>Fecha y Hora de reserva: </th>
							<td>'.$c->fecha_cita.' '.$c->hora_cita_inicio.'</td>
							<th>Observaciones:</th>
							<td>'.$c->observaciones_cita.'</td>
						</tr>
					</table>
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}
			
			$mail = new PHPMailer;
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->Port     = 25;		
			// Armo el FROM y el TO
			$mail->setFrom('contacto@red-salud.com', 'Red Salud');
			$destinatarios = $this->certificado_mdl->getAtencionCliente($data);
			if(!empty($destinatarios)){
				foreach ($destinatarios as $d) {				
					$mail->addAddress($d->correo_laboral, $d->nombres_col);
				}
			}else {
				$texto='<div><p>El proveedor no registra contactos con env&iacute;o a email de reservas de atenciones.</p></div>';
				$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "RESERVA SIN CONFIRMAR - ".$plan;
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
			 $mail->Send(); 
			// $estadoEnvio = $mail->Send(); 
   //              if($estadoEnvio){
   //                  echo"El correo fue enviado correctamente.";
   //              } else {
   //                  echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
   //              }

			}
		}

		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function anular_cita($idcita,$aseg,$cert){
		$data['cita'] = $idcita;
		$data['aseg'] = $aseg;
		$data['cert'] = $cert;
		$getcita=$this->certificado_mdl->getCita($data);
		$data['getcita']=$getcita; 
		$this->load->view('dsb/html/certificado/anular_cita.php', $data);
	}

	public function eliminar_cita(){
		$data['mensaje'] = 4;
		$data['idcita'] = $_POST['idcita'];
		$data['idsiniestro'] = $_POST['idsiniestro'];

		$this->certificado_mdl->eliminar_cita($data);
		$this->certificado_mdl->eliminar_orden($data);
		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function cert_cont_save()
	{
		$data['direcc'] = $_POST['direcc'];
		$data['telf'] = $_POST['telf'];
		$data['correo'] = $_POST['correo'];
		$data['ubigeo'] = $_POST['dist'];
		$data['cont_id'] = $_POST['cont_id'];
		$id2 = $_POST['id2'];
		$doc = $_POST['doc'];

		$this->certificado_mdl->cont_save($data);
		redirect("index.php/certificado_detalle/".$id2."/".$doc);
	}

	public function cert_aseg_up(){
		$data['aseg_id'] = $_POST['idaseg'];
		$data['ape1'] = $_POST['ape1'];
		$data['ape2'] = $_POST['ape2'];
		$data['nom1'] = $_POST['nom1'];
		$data['nom2'] = $_POST['nom2'];
		$data['fec_nac'] = $_POST['fec_nac'];
		$data['genero'] = $_POST['genero'];
		$data['dis'] = $_POST['dis'];
		$data['direccion'] = $_POST['direccion'];
		$data['correo'] = $_POST['correo'];
		$data['telf'] = $_POST['telf'];
		$data['ec'] = $_POST['ec'];
		$data['tipomen'] = 1;
		
		$this->certificado_mdl->up_aseg($data);
		$data['mensaje'] = 1;
		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function reenviar_proveedor($id){
		$user = $this->session->userdata('user');
			extract($user);
			$data['idcita'] = $id;

			$contenido = $this->certificado_mdl->contenido_mail($data);
			//email para proveedor
			foreach ($contenido as $c) {
				$plan=$c->nombre_plan;				
				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@, '.$c->nombre_comercial_pr.'</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se confirma la reserva de atenci&oacute;n m&eacute;dica ambulatoria con los siguientes datos:</p>
					<table align="center" border="1" width="90%">
						<tr>
							<th>DNI Afiliado:</th>
							<td>'.$c->aseg_numDoc.'</td>
							<th>Nombre del Afiliado:</th>
							<td>'.$c->afiliado.'</td>
						</tr>
						<tr>
							<th>Fecha de Nacimiento:</th>
							<td>'.$c->aseg_fechNac.'</td>
							<th>Especialidad:</th>
							<td>'.$c->nombre_esp.'</td>
						</tr>
						<tr>
							<th>Fecha y Hora de reserva: </th>
							<td>'.$c->fecha_cita.' '.$c->hora_cita_inicio.'</td>
							<th>Observaciones:</th>
							<td>'.$c->observaciones_cita.'</td>
						</tr>
					</table>
					'.$c->cuerpo_mail.'
					<p>Para dudas &oacute; consultas comun&iacute;quese a nuestra central en Lima: 01-445-3019 &oacute; provincia: 0800-47676</p>
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';

					$data['idproveedor'] = $c->idproveedor;
			}
	        $mail = new PHPMailer;
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->Port     = 25;		
			
			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, 'Red Salud');
			$destinatarios = $this->certificado_mdl->destinatarios($data);
			if(!empty($destinatarios)){
				foreach ($destinatarios as $d) {				
					$mail->addAddress($d->email_cp, $d->nombres);
				}
			}else {
				$texto='<div><p>El proveedor no registra contactos con env&iacute;o a email de reservas de atenciones.</p></div>';
				$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "RESERVA DE CONSULTA MEDICA - ".$plan;
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

			$data['mensaje'] = 5;

			$this->load->view('dsb/html/mensaje.php', $data);			

	}

	public function reenviar_afiliado($id){
		$user = $this->session->userdata('user');
			extract($user);
			$data['idcita'] = $id;
			$contenido = $this->certificado_mdl->contenido_mail($data);
			
			//email para afiliado
			 foreach ($contenido as $c2) {
				$plan2=$c2->nombre_plan;
				$data['idcita'] = $id;
				$to=$c2->aseg_email;
				$nom=$c2->afiliado;
				$texto2='<div><p>Afiliado(a), '.$nom.'</p>
					<p>Sabemos lo importante que es para ti el cuidado de tu salud, <b>tu cita m&eacute;dica ha sido reservada con &eacute;xito. </b>Te detallamos los datos registrados:</p>
					<table align="center" border="1" width="90%">
						<tr>
							<th>DNI Afiliado:</th>
							<td>'.$c2->aseg_numDoc.'</td>
							<th>Nombre del Afiliado:</th>
							<td>'.$c2->afiliado.'</td>
						</tr>
						<tr>
							<th>Centro M&eacute;dico:</th>
							<td>'.$c2->nombre_comercial_pr.'</td>
							<th>Especialidad:</th>
							<td>'.$c2->nombre_esp.'</td>
						</tr>
						<tr>
							<th>Fecha y Hora de reserva: </th>
							<td>'.$c2->fecha_cita.' '.$c2->hora_cita_inicio.'</td>
							<th>Observaciones:</th>
							<td>'.$c2->observaciones_cita.'</td>
						</tr>
					</table>
					<p>Recordar que la hora de reserva es referencial y la atenci&oacute;n se realiza por orden de llegada. Si tienes alguna consulta adicional comun&iacute;cate a nuestra central telef&oacute;nica en Lima: 01-445-3019 &oacute; provincia: 0800-47676</p>
					<p><b>Estamos agradecidos por la confianza brindada.</b></p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>';
			}

			$mail2 = new PHPMailer;
			$mail2->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail2->Host = 'localhost';
	        $mail2->SMTPAuth = false;
	        $mail2->SMTPSecure = false;
	        $mail2->Username = '';
	        $mail2->Password = '';
	        $mail2->Port     = 25;		
	
			// Armo el FROM y el TO
			$mail2->setFrom($correo_laboral, 'Red Salud');
			
			if($to!=''){			
				$mail2->addAddress($to, $nom);
			}else {
				$texto2='<div><p>El asegurado no cuenta con un correo electrónico registrado.</p></div>';
				$mail2->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail2->AddBCC($correo_laboral, $nombres_col);
			// El asunto
			$mail2->Subject = "RESERVA DE CONSULTA MEDICA - ".$plan2;
			// El cuerpo del mail (puede ser HTML)
			$tipo="'Century Gothic'";
			$mail2->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto2.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail2->IsHTML(true);
			$mail2->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail2->send();

			$data['mensaje'] = 6;

			$this->load->view('dsb/html/mensaje.php', $data);

	}

	public function registrar_incidencia($id,$idaseg){
		$data['id'] = $id;
		$data['idaseg'] = $idaseg;

		$this->load->view('dsb/html/certificado/reg_incidencia.php',$data);
	}

	public function save_incidencia(){
		$data['tipo'] = $_POST['tipo'];
		$data['cert_id'] = $_POST['cert_id'];
		$data['aseg_id'] = $_POST['aseg_id'];
		$data['descripcion'] = $_POST['desc'];
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusu'] = $idusuario;

		$this->certificado_mdl->save_incidencia($data);
		$id = $this->db->insert_id();

		if(isset($_POST['guardar'])){
			echo "<script>
				alert('Se registró el incidente con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
		}else{
			echo "<script>
				location.href='".base_url()."index.php/derivar_incidencia/".$id."';
				</script>";
		}
	}
}