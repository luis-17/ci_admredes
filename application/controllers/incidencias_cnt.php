<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class incidencias_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('incidencia_mdl');
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

			$data['pendientes'] = $this->incidencia_mdl->getMisPendientes($idusuario);
			$data['resueltas'] = $this->incidencia_mdl->getResueltas();
			$data['otros'] = $this->incidencia_mdl->getOtrosPendientes($idusuario);
			$data['otros2'] = $this->incidencia_mdl->getOtrosPendientes2($idusuario);

			$this->load->view('dsb/html/incidente/incidencias.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function derivar_incidencia($id)
	{
		$data['id'] = $id;
		$user = $this->session->userdata('user');
		extract($user);
		$data['usuarios'] = $this->incidencia_mdl->getUsuarios($idusuario);

		$this->load->view('dsb/html/incidente/derivar_incidente.php',$data);
	}

	public function reg_derivacion(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario_deriva'] = $idusuario;
		$id = $_POST['id'];
		$data['id'] = $_POST['id'];
		$data['desc'] = $_POST['desc'];
		$data['idusuario_recepciona'] = $_POST['recepciona'];
		$idusuario_asignado = $_POST['recepciona'];
		$this->incidencia_mdl->up_derivacion($data);
		$this->incidencia_mdl->reg_derivacion($data);
		$this->incidencia_mdl->up_estado($data);

		$contenido = $this->incidencia_mdl->contenido_mail($data);
			//email 
			foreach ($contenido as $c) {
				$plan=$c->nombre_plan;				
				$tipo="'Century Gothic'";
				$texto='<p>Te he derivado el incidente I'.$data['id'].', '.$data['desc'].'</p>
						<p>Detallo los datos del incidente en la siguiente tabla:</p>
					<table a lign="center" border="1" width="90%">
						<tr>
							<th>Canal:</th>
							<td>'.$c->nombre_comercial_cli.'</td>
							<th>Plan:</th>
							<td>'.$c->nombre_plan.'</td>
							<th>N° Certificado:</th>
							<td>'.$c->cert_num.'</td>
						</tr>
						<tr>
							<th>DNI:</th>
							<td>'.$c->aseg_numDoc.'</td>
							<th>Afiliado:</th>
							<td>'.$c->afiliado.'</td>
							<th>Tel&eacute;fono/Email:</th>
							<td>'.$c->aseg_telf.'/'.$c->aseg_email.'</td>
						</tr>
						<tr>
							<th>Fecha:</th>
							<td>'.$c->fech_reg.'</td>
							<th>Motivo:</th>
							<td>'.$c->tipoincidencia.'</td>
							<th>Descripci&oacute;n</th>
							<td>'.$c->descripcion.'</td>
						</tr>
					</table>
					<p>Para registrar la solución del incidente ingresar a <a href="https://www.red-salud.com/rsadmin/index.php/resolver_incidencia/'.$idusuario_asignado.'/'.$id.'">https://www.red-salud.com/rsadmin/index.php/reg_solucion_mail/'.$idusuario_asignado.'/'.$id.'</a></p>
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.'</p></div>';
			}
			
			$mail = new PHPMailer;
			$mail->isSMTP();
	        $mail->Host     = 'relay-hosting.secureserver.net';
	       	//$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->Port     = 25;	

			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, $nombres_col);
			$destinatario = $this->incidencia_mdl->destinatario($data);
			if(!empty($destinatario)){
				foreach ($destinatario as $d) {				
					$mail->addAddress($d->correo_laboral, $d->nombres_col);
					$nom = $d->nombres_col;
				}
			}else {
				$texto='<div><p>El proveedor no registra contactos con env&iacute;o a email de reservas de atenciones.</p></div>';
				$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "NOTIFICACION: DERIVACION DE INCIDENCIA";
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
	                <p>Hola '.$nom.'</p>
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

		echo "<script>
				alert('Se derivó el incidente con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}

	public function solucion_incidencia($id){
		$data['id'] = $id;
		$this->load->view('dsb/html/incidente/reg_solucion.php',$data);
	}

	public function reg_solucion(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['id'] = $_POST['id'];
		$data['respuesta'] = $_POST['desc'];
		$data['idusuario_soluciona'] = $idusuario;
		date_default_timezone_set('America/Lima');
		$data['fecha'] = date('Y-m-d H:i:s');

		$this->incidencia_mdl->save_solucion($data);
		$this->incidencia_mdl->up_solucion($data);
		echo "<script>
				alert('Se registró la solución del incidente con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}

	public function historial($id){
		$data['historial'] = $this->incidencia_mdl->getHistorial($id);
		$usuario_asignado = $this->incidencia_mdl->getUsuarioAsignado($id);
		$data['idusuario_asignado'] = $usuario_asignado['idusuario'];
		$data['idhistorial'] = $id;
		$this->load->view('dsb/html/incidente/historial.php',$data);
	}

	public function registrar_evento($id){
		$usuario_asignado = $this->incidencia_mdl->getUsuarioAsignado($id);
		$data['idusuario_asignado'] = $usuario_asignado['idusuario'];
		$data['colaborador'] = $usuario_asignado['colaborador'];
		$data['idincidente'] = $id;
		$this->load->view('dsb/html/incidente/registrar_evento.php',$data);
	}

	public function reg_evento(){
		$user = $this->session->userdata('user');
		extract($user);
		$data['idincidente'] = $_POST['idincidente'];
		$data['idusuario_asignado'] = $_POST['id'];
		$idusuario_asignado = $_POST['id'];
		$id = $_POST['idincidente'];
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['comentario'] = $_POST['desc'];
		$data['idusuario_deriva'] = $idusuario;
		$this->incidencia_mdl->reg_evento($data);
		$usuario_asignado = $this->incidencia_mdl->getUsuarioAsignado($id);
		$nombre_uasignado = $usuario_asignado['colaborador'];
		$correo_uasignado = $usuario_asignado['correo_laboral'];
		$texto = "tienes una incidencia pendiente de resolver";
		$data['id'] =  $_POST['idincidente'];
		$data['idusuario_recepciona'] = $_POST['id'];

		$contenido = $this->incidencia_mdl->contenido_mail($data);
			//email 
			foreach ($contenido as $c) {
				$plan=$c->nombre_plan;				
				$tipo="'Century Gothic'";
				$texto='<p>A&uacute;n tienes el incidente pendiente I'.$data['id'].', '.$data['comentario'].'</p>
						<p>Detallo los datos del incidente en la siguiente tabla:</p>
					<table a lign="center" border="1" width="90%">
						<tr>
							<th>Canal:</th>
							<td>'.$c->nombre_comercial_cli.'</td>
							<th>Plan:</th>
							<td>'.$c->nombre_plan.'</td>
							<th>N° Certificado:</th>
							<td>'.$c->cert_num.'</td>
						</tr>
						<tr>
							<th>DNI:</th>
							<td>'.$c->aseg_numDoc.'</td>
							<th>Afiliado:</th>
							<td>'.$c->afiliado.'</td>
							<th>Tel&eacute;fono/Email:</th>
							<td>'.$c->aseg_telf.'/'.$c->aseg_email.'</td>
						</tr>
						<tr>
							<th>Fecha:</th>
							<td>'.$c->fech_reg.'</td>
							<th>Motivo:</th>
							<td>'.$c->tipoincidencia.'</td>
							<th>Descripci&oacute;n</th>
							<td>'.$c->descripcion.'</td>
						</tr>
					</table>
					<p>Para registrar la solución del incidente ingresar a <a href="https://www.red-salud.com/rsadmin/index.php/resolver_incidencia/'.$idusuario_asignado.'/'.$id.'">https://www.red-salud.com/rsadmin/index.php/reg_solucion_mail/'.$idusuario_asignado.'/'.$id.'</a></p>
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.'</p></div>';
			}
			
			$mail = new PHPMailer;
			$mail->isSMTP();
	        $mail->Host     = 'relay-hosting.secureserver.net';
	       	//$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->Port     = 25;	

			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, $nombres_col);
			$destinatario = $this->incidencia_mdl->destinatario($data);
			if(!empty($destinatario)){
				foreach ($destinatario as $d) {				
					$mail->addAddress($d->correo_laboral, $d->nombres_col);
					$nom = $d->nombres_col;
				}
			}else {
				$texto='<div><p>El proveedor no registra contactos con env&iacute;o a email de reservas de atenciones.</p></div>';
				$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			}
			$mail->addAddress($correo_laboral, $nombres_col);
			// El asunto
			$mail->Subject = "NOTIFICACION: INCIDENCIA PENDIENTE";
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
	                <p>Hola '.$nom.'</p>
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

		echo "<script>
				alert('Se registró el evento con éxito.');
				location.href='".base_url()."index.php/historial/".$id."'
				</script>";
	}

	public function historial_incidencias($cert_id, $aseg_id){
		$data['historial'] = $this->incidencia_mdl->historial_incidencias($cert_id, $aseg_id);
		$this->load->view('dsb/html/incidente/historial_incidencias.php',$data);
	}

	public function reg_solucion_mail($idusuario, $idincidente){
		$data['id'] = $idincidente;
		$data['idusuario'] = $idusuario;
		$this->load->view('dsb/html/incidente/reg_solucion_mail.php',$data);
	}

	public function save_solucion(){
		$data['idincidente'] = $_POST['id'];
		$data['idusuario'] = $_POST['idusuario'];
		$data['hoy'] = date("Y-m-d H:i:s");
		$data['respuesta'] = $_POST['desc'];
		$this->incidencia_mdl->save_solucionincidencia($data);
		echo "<script>
				alert('Se registró la solución de la incidencia con éxito.');
				location.href='https://www.red-salud.com/rsadmin'
				</script>";
	}

}