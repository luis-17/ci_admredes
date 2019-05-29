<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class usuario_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('usuario_mdl');
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

			$data['colaboradores'] = $this->usuario_mdl->getColaboradores();

			$this->load->view('dsb/html/usuario/usuario.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function usuario_registrar(){
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

			$data['accion'] = "Registrar Usuario";
			$data['nom'] = "Nuevo Usuario";
			$data['idusuario2'] = 0;
			$data['tipos_usuario'] = $this->usuario_mdl->getTipoUsuario();
			$data['tipo_usuario'] = 0;
			$data['dni'] = "";
			$data['nombres'] = "";
			$data['ap1'] = "";
			$data['ap2'] = "";
			$data['fec'] = "";
			$data['correo'] = "";
			$data['telf'] = "";
			$data['usuario'] = "";
			$data['idcolaborador2'] = 0;

			$this->load->view('dsb/html/usuario/usuario_editar.php',$data);
		}
		else{
			redirect('/');
		}	
		
	}

	public function usuario_editar($id){
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

			$usuario = $this->usuario_mdl->getColaborador($id);

			foreach ($usuario as $u) {
				$data['tipo_usuario'] = $u->idtipousuario;
				$data['dni'] = $u->numero_documento_col;
				$data['nombres'] = $u->nombres_col;
				$data['ap1'] = $u->ap_paterno_col;
				$data['ap2'] = $u->ap_materno_col;
				$data['fec'] = $u->fecha_nacimiento_col;
				$data['correo'] = $u->correo_laboral;
				$data['telf'] = $u->celular_col;
				$data['usuario'] = $u->username;
				$data['idcolaborador2'] = $u->idcolaborador;
			}

			$data['accion'] = "Actualizar Usuario";
			$data['nom'] = "Actualizar Usuario";
			$data['idusuario2'] = $id;
			$data['tipos_usuario'] = $this->usuario_mdl->getTipoUsuario();

			$this->load->view('dsb/html/usuario/usuario_editar.php',$data);
		}
		else{
			redirect('/');
		}	
		
	}

	public function usuario_guardar(){
		$id = $_POST['idusuario2'];
		$data['idtipousuario'] = $_POST['idtipousuario'];
		$data['dni'] = $_POST['dni'];
		$data['nombres'] = $_POST['nombres'];
		$data['ap1'] = $_POST['ap_paterno'];
		$data['ap2'] = $_POST['ap_materno'];
		$data['fec'] = $_POST['fecha'];
		$data['correo'] = $_POST['correo'];
		$data['telf'] = $_POST['telf'];
		$data['usuario'] = $_POST['username'];
		$data['idcolaborador2'] = $_POST['idcolaborador2'];

		if($id == 0){
			$this->usuario_mdl->insert_usuario($data);
			$data['idusuario2'] = $this->db->insert_id();
			$this->usuario_mdl->insert_colaborador($data);
			echo "<script>
				alert('Se registró el usuario con éxito.');
				location.href='".base_url()."index.php/usuario';
				</script>";
		}else{
			$data['idusuario2'] = $id;
			$this->usuario_mdl->update_usuario($data);
			$this->usuario_mdl->update_colaborador($data);
			echo "<script>
				alert('Se actualizó el usuario con éxito.');
				location.href='".base_url()."index.php/usuario';
				</script>";
		}
	}

	public function usuario_roles($id){
		$data['idusuario2'] = $id;
		$data['roles'] = $this->usuario_mdl->getRoles($id);
		$data['menu'] = $this->usuario_mdl->getMenu();
 		$this->load->view('dsb/html/usuario/usuario_roles.php',$data);
	}

	public function detalle_roles(){
		$id = $_POST['id'];
		$idusu = $_POST['idu'];

		$submenu = $this->usuario_mdl->getSubmenu($id, $idusu);
		if(!empty($submenu)){
		$cadena = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Asignar: </label>
					<div class="col-sm-9">
						<table>';
			foreach ($submenu as $sm) {
				$cadena.="<tr>
							<td><input type='checkbox' name='chk[]' value='".$sm->idsubmenu."' ".$sm->checked."></td>
							<td>".$sm->descripcion."</td>
						</tr>";
			}

		$cadena.='</table></div>';
		}else{
			$cadena="<input type='hidden' id='prod' name='prod' value=''>";
		}

		echo $cadena;
	}

	public function save_roles(){
		$data['idusuario2'] = $_POST['idusuario'];
		$submenu=$_POST['chk'];
		$data['menu']=$_POST['submenu'];
		$num=count($submenu);
		//$this->usuario_mdl->anular_roles($data);
		for($i=0;$i<$num;$i++){
			$data['idsubmenu'] = $submenu[$i];
			$accion = $this->usuario_mdl->select_rol($data);
			if(empty($accion)){
				$this->usuario_mdl->insertar_rol($data);
			}else{
				$this->usuario_mdl->activar_rol($data);
			}
		}

		$this->usuario_mdl->eliminar_roles($data);
		$this->usuario_mdl->cambiar_roles($data);
		echo "<script>
				alert('Se asignaron los permisos de usuario con éxito.');
				location.href='".base_url()."index.php/usuario_roles/".$data['idusuario2']."';
				</script>";
	}

	public function usuario_anular($id){
		$this->usuario_mdl->usuario_anular($id);
		echo "<script>
				alert('Se inativó el usuario con éxito.');
				location.href='".base_url()."index.php/usuario';
				</script>";
	}

	public function usuario_activar($id){
		$this->usuario_mdl->usuario_activar($id);
		echo "<script>
				alert('Se reactivó el usuario con éxito.');
				location.href='".base_url()."index.php/usuario';
				</script>";
	}

	public function reenviar_usuario($id){

			$user = $this->session->userdata('user');
			extract($user);

			
			$mail = new PHPMailer;
			$mail->isSMTP();
	        //$mail->Host     = 'relay-hosting.secureserver.net';
	       	$mail->Host = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->SMTPSecure = false;
	        $mail->Username = 'contacto@red-salud.com';
	        $mail->Password = 'Redperu2017HCA';
	        $mail->Port     = 25;
	        $mail->SMTPDebug = 3;		
			// Armo el FROM y el TO
			$mail->setFrom($correo_laboral, $nombres_col);
			$destinatario = $this->usuario_mdl->destinatario($id);
			if(!empty($destinatario)){
				foreach ($destinatario as $d) {				
					$mail->addAddress($d->correo_laboral, $d->nombres_col);
					$nombre = $d->nombres_col;
					$usu = $d->username;
					$pass = $d->password_view;
				}
			}else {
				$texto='<div><p>El usuario no cuenta con email registrado.</p></div>';
				$mail->addAddress($correo_laboral, $nombres_col);
			}
			// El asunto
			$mail->Subject = "CREDENCIALES DE ACCESO PLATAFORMA ADMIN";
			// El cuerpo del mail (puede ser HTML)
			$tipo="'Century Gothic'";
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">

	               	<div><p>Estimad@, '.$nombre.'</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se remite sus credenciales de acceso a nuestra plataforma Red Salud Admin:</p>
						<ul>
							<li>Usuario: '.$usu.'</li>
							<li>Contraseña: '.$pass.'</li>
						</ul>
					<p>Para acceder a nuestra plataforma ingresar al siguiente enlace <a href="https://red-salud.com/rsadmin">www.red-salud.com/rsadmin</a></p>
					<p>Para dudas &oacute; consultas comun&iacute;quese al anexo 109.
					<p>Saludos Cordiales</p>
					<p>Atte. '.$nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col.'</p></div>

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
			//$mail->send();

			$estadoEnvio = $mail->Send(); 
                if($estadoEnvio){
                    echo"El correo fue enviado correctamente.";
                } else {
                    echo"Ocurrió un error inesperado. " . $mail->ErrorInfo;
                }


			echo "<script>
				alert('Se reenvió el correo con éxito.');
				</script>";
	}

}