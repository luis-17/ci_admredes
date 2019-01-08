<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class persona_juridica_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
         $this->load->model('canal_mdl');
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

			$canal = $this->canal_mdl->getCanales();
			$data['canal'] = $canal;

			$this->load->view('dsb/html/canal/persona_juridica.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function canal_anular($id){
		$this->canal_mdl->canal_anular($id);
		redirect(base_url()."index.php/persona_juridica");
	}

	public function canal_activar($id){
		$this->canal_mdl->canal_activar($id);
		redirect(base_url()."index.php/persona_juridica");
	}

	public function canal_registrar(){

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

			$categoria = $this->canal_mdl->getCategoria();

			$data['categoria'] = $categoria;
			$data['idcategoria'] = "";
			$data['nom'] = "Nuevo Canal";
			$data['accion'] = "Registrar Canal";
			$data['idcanal'] = 0;
			$data['ruc'] = "";
			$data['razon_social'] = "";
			$data['comercial'] ="";
			$data['nombre_corto'] ="";
			$data['dni'] = "";
			$data['nombres'] = "";
			$data['direccion'] = "";
			$data['telf'] = "";
			$data['web'] = "";
			$this->load->view('dsb/html/canal/canal_editar.php',$data);
		}
		else{
			redirect('/');
		}
		
	}

	public function canal_editar($id){

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

			$categoria = $this->canal_mdl->getCategoria();

			$data['categoria'] = $categoria;

			$canal = $this->canal_mdl->getCanal($id);

			foreach ($canal as $c) {
				$data['idcategoria'] = $c->idcategoriacliente;
				$data['nom'] = $c->nombre_comercial_cli;
				$data['accion'] = "Actualizar Canal";
				$data['idcanal'] = $c->idclienteempresa;
				$data['ruc'] = $c->numero_documento_cli;
				$data['razon_social'] = $c->razon_social_cli;
				$data['comercial'] = $c->nombre_comercial_cli;
				$data['nombre_corto'] = $c->nombre_corto_cli;
				$data['dni'] = $c->dni_representante_legal;
				$data['nombres'] = $c->representante_legal;
				$data['direccion'] = $c->direccion_legal;
				$data['telf'] = $c->telefono_cli;
				$data['web'] = $c->pagina_web_cli;
			}

			
			$this->load->view('dsb/html/canal/canal_editar.php',$data);
		}
		else{
			redirect('/');
		}
		
	}

	public function canal_guardar(){
		$data['idcategoria'] = $_POST['idcategoria'];
		$data['ruc'] = $_POST['ruc'];
		$data['razon_social'] = $_POST['razon_social'];
		$data['comercial'] = $_POST['comercial'];
		$data['nombre_corto'] = $_POST['nombre_corto'];
		$data['dni'] = $_POST['dni'];
		$data['nombres'] = $_POST['nombres'];
		$data['direccion'] = $_POST['direccion'];
		$data['telf'] = $_POST['telf'];
		$data['web'] = $_POST['web'];

		if($_POST['idcanal'] == 0 ){
			$this->canal_mdl->insert_canal($data);
			$data['idcanal'] = $this->db->insert_Id();
			$asunto="CREACION DE CLIENTE";
			$accion="creaci&oacute;n";
		}else{
			$data['idcanal'] = $_POST['idcanal'];
			$this->canal_mdl->update_canal($data);
			$asunto="ACTUALIZACION DE CLIENTE";
			$accion="actualizaci&oacute;n";
		}

		$user = $this->session->userdata('user');
		extract($user);

		$id=$data['idcanal'];

		$canal = $this->canal_mdl->getCanal($id);

			//email para proveedor
			foreach ($canal as $c) {

				$tipo="'Century Gothic'";
				$texto='<div><p>Estimad@s,</p>
					<p>Por medio de &eacute;ste correo electr&oacute;nico se informa la '.$accion.' del cliente '.$c->razon_social_cli.' con los siguientes datos:</p>
					<table align="center" border="1" width="90%" style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
						<tr>
							<th>RUC:</th>
							<td>'.$c->numero_documento_cli.'</td>
							<th>Raz&oacute;n Social:</th>
							<td>'.$c->razon_social_cli.'</td>
						</tr>
						<tr>
							<th>Nombre Comercial:</th>
							<td>'.$c->nombre_comercial_cli.'</td>
							<th>Nombre Corto:</th>
							<td>'.$c->nombre_corto_cli.'</td>
						</tr>
						<tr>
							<th>DNI Representante Legal: </th>
							<td>'.$c->dni_representante_legal.'</td>
							<th>Representante Legal:</th>
							<td>'.$c->representante_legal.'</td>
						</tr>
						<tr>
							<th>Direcci&oacute;n: </th>
							<td>'.$c->direccion_legal.'</td>
							<th>Tel&eacute;fono:</th>
							<td>'.$c->telefono_cli.'</td>
						</tr>
						<tr>
							<th colspan="2">Web: </th>
							<td colspan="2">'.$c->pagina_web_cli.'</td>
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


		redirect(base_url()."index.php/persona_juridica");
	}
}