<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Afiliacion_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('afiliacion_mdl'); 
         $this->load->model('menu_mdl');
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
			$planes = $this->afiliacion_mdl->getPlanes($idusuario);
			$data['planes'] = $planes;
			$data['estilo'] = "none";	
			$canales = $this->afiliacion_mdl->getCanales($idusuario);
			$data['canales'] = $canales;
			$data['canal'] = '';
			$data['doc'] = '';
			$data['certificado'] = '';
			$data["doc_bus"] = '';

			$this->load->view('dsb/html/afiliado/nueva_afiliacion.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function plan()
	{
		$canal = $_POST['canal'];

		$options='<option value="">Seleccionar</option>';
		$planes = $this->afiliacion_mdl->getPlanes2($canal);

		foreach ($planes as $p) {
			$options.='<option value="'.$p->idplan.'">'.$p->nombre_plan.'</option>';
		}
		echo $options;
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
			$data['doc'] = $_POST['cont_numDoc'];
			$canal=$_POST['canal'];

			$data['canales'] = $this->afiliacion_mdl->getCanales($idusuario);
			$planes = $this->afiliacion_mdl->getPlanes2($canal);
			$data['planes'] = $planes;
			$data['estilo'] = "";

			$certificado=$this->afiliacion_mdl->certificado($data);
			$data['certificado'] = $certificado;
			$contratante=$this->afiliacion_mdl->buscar($data);
			$data['datos'] = $contratante;
			$ubigeo=$this->afiliacion_mdl->ubigeo();
			$data['ubigeo']=$ubigeo;
			$provincia2=$this->afiliacion_mdl->provincia2($data);
			$data['provincia2']=$provincia2;
			$distrito2=$this->afiliacion_mdl->distrito2($data);
			$data['distrito2']=$distrito2;
			$asegurados=$this->afiliacion_mdl->asegurados($data);
			$data['asegurados'] = $asegurados;
			$cant=$this->afiliacion_mdl->num_aseg($data);
			$data['cant']=$cant;
			$data["doc_bus"] = $_POST['cont_numDoc'];

			$this->load->view('dsb/html/afiliado/nueva_afiliacion.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function cont_save()
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
		
			$data['tipodoc'] = $_POST['tipodoc'];
			$data['doc'] = $_POST['dni'];
			$data["doc_bus"] = $_POST['docu'];
			$data['ape1'] = $_POST['ape1'];
			$data['ape2'] = $_POST['ape2'];
			$data['nom1'] = $_POST['nom1'];
			$data['nom2'] = $_POST['nom2'];
			$data['direcc'] = $_POST['direcc'];
			$data['telf'] = $_POST['telf'];
			$data['correo'] = $_POST['correo'];
			$data['ubigeo'] = $_POST['dist'];
			$data['cont_id'] = $_POST['cont_id'];

			$this->afiliacion_mdl->cont_save($data);

			$data['canal'] = $_POST['can'];
			$data['plan'] = $_POST['pl'];
			$data['docu'] = $_POST['docu'];
			$canal=$_POST['can'];


			$certificado=$this->afiliacion_mdl->certificado($data);
			$data['certificado'] = $certificado;
			$data['canales'] = $this->afiliacion_mdl->getCanales($idusuario);
			$planes = $this->afiliacion_mdl->getPlanes2($canal);
			$data['planes'] = $planes;
			$data['estilo'] = "";

			$contratante=$this->afiliacion_mdl->buscar($data);
			$data['datos'] = $contratante;
			$ubigeo=$this->afiliacion_mdl->ubigeo();
			$data['ubigeo']=$ubigeo;
			$provincia2=$this->afiliacion_mdl->provincia2($data);
			$data['provincia2']=$provincia2;
			$distrito2=$this->afiliacion_mdl->distrito2($data);
			$data['distrito2']=$distrito2;
			$asegurados=$this->afiliacion_mdl->asegurados($data);
			$data['asegurados'] = $asegurados;		
			$cant=$this->afiliacion_mdl->num_aseg($data);
			$data['cant']=$cant;

			$this->load->view('dsb/html/afiliado/nueva_afiliacion.php',$data);
		}
		else{
			redirect('/');
		}
	}

	public function provincia()
	{
		$dep = $_POST['dep'];

		$options='<option value="">Seleccionar</option>';
		$provincia = $this->afiliacion_mdl->provincia($dep);

		foreach ($provincia as $p) {
			$options.='<option value="'.$p->idprovincia.'">'.$p->descripcion_ubig.'</option>';
		}		

		echo $options;
	}

	public function distrito()
	{
		$prov = $_POST['prov'];

		$options='<option value="">Seleccionar</option>';
		$distrito = $this->afiliacion_mdl->distrito($prov);

		foreach ($distrito as $d) {
			$options.='<option value="'.$d->iddistrito.'">'.$d->descripcion_ubig.'</option>';
		}		

		echo $options;
	}

	public function aseg_editar($id,$cert){
		$data['aseg_id']=$id;
		$data['cert_id']=$cert;
		$data['doc'] = "";
		$data["tipdoc"] ="";
		$data['cert_ver'] = "";
		$data['aseg_ver'] = "";

		$ubigeo=$this->afiliacion_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia3=$this->afiliacion_mdl->provincia3($data);
		$data['provincia3']=$provincia3;
		$distrito3=$this->afiliacion_mdl->distrito3($data);
		$data['distrito3']=$distrito3;

			$asegurado = $this->afiliacion_mdl->getAseg_editar($id,$cert);
			$data['asegurado'] = $asegurado;
			$this->load->view('dsb/html/afiliado/aseg_editar.php', $data);
	}

	public function aseg_nuevo($cert,$plan){
		$data['aseg_id']="";
		$data['cert_id']=$cert;
		$data['doc'] = "";
		$data["tipdoc"] ="";
		$data['cert_ver'] = "";
		$data['aseg_ver'] = "";
		$data['plan'] = $plan;

		$ubigeo=$this->afiliacion_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia3=$this->afiliacion_mdl->provincia3($data);
		$data['provincia3']=$provincia3;
		$distrito3=$this->afiliacion_mdl->distrito3($data);
		$data['distrito3']=$distrito3;

		$this->load->view('dsb/html/afiliado/aseg_nuevo.php',$data);
	}

	public function aseg_up(){
		$data['aseg_id'] = $_POST['aseg_id'];
		$id = $_POST['aseg_id'];
		$cert = $_POST['cert_id'];
		$data['tipodoc'] = $_POST['tipodoc'];
		$data['doc'] = $_POST['doc'];
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
		$op = $_POST['tipoop'];
		$data['tipomen'] = 1;
		$data['mensaje'] = 1;
		
		$this->afiliacion_mdl->up_aseg($data);
		$this->load->view('dsb/html/mensaje.php', $data);
	}

	public function aseg_save(){
		$data['aseg_id'] = $_POST['aseg_id'];
		$id = $_POST['aseg_id'];
		$cert = $_POST['cert_id'];
		$data['id'] = $_POST['aseg_id'];
		$data['cert'] = $_POST['cert_id'];
		$data['tipodoc'] = $_POST['tipodoc'];
		$data['doc'] = $_POST['doc'];
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
		$op = $_POST['tipoop'];
		$data['tipomen'] = 2;
		$data['hoy'] = date('Y-m-d');
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;

		$fec_fin = $this->afiliacion_mdl->fin_vig($cert);
		foreach ($fec_fin as $f) {
			$data['fecfin'] =$f->cert_finVig;
		}

		switch ($op) {
			case 2:
				$this->afiliacion_mdl->up_aseg($data);
				$this->afiliacion_mdl->in_certase($data);
				break;

			case 3:
				$this->afiliacion_mdl->in_asegurado($data);
				$id = $this->db->insert_id();
				$data['id'] = $id;
				$this->afiliacion_mdl->in_certase($data);
				break;
			}

		$data['mensaje'] = 1;
		$this->load->view('dsb/html/mensaje.php',$data);
	}

	public function verifica_dni(){
		$data['doc'] = $_POST['doc_copy'];
		$data['cert_id'] = $_POST['cert_copy'];		
		$data['tipdoc'] = $_POST['tipodoc_copy'];
		$data['aseg_id'] = $_POST['aseg_id_copy'];
		$tipoop = $_POST['tipoop_copy'];

		$ubigeo=$this->afiliacion_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia2=$this->afiliacion_mdl->provincia2($data);
		$data['provincia3']=$provincia2;
		$distrito2=$this->afiliacion_mdl->distrito2($data);
		$data['distrito3']=$distrito2;
		$data['asegurado'] = "";

			$aseg_ver = $this->afiliacion_mdl->verifica_dni($data);
			$data['aseg_ver'] = $aseg_ver;
			$asegurado = $this->afiliacion_mdl->getAseg_editar2($data);
			$data['asegurado'] = $asegurado;
			$this->load->view('dsb/html/afiliado/aseg_editar.php', $data);

	}

	public function verifica_dni_in(){
		$data['doc'] = $_POST['doc_copy'];
		$data['cert_id'] = $_POST['cert_copy'];		
		$data['tipdoc'] = $_POST['tipodoc_copy'];
		$data['plan'] = $_POST['plan_copy'];
		$tipoop = $_POST['tipoop_copy'];

		$ubigeo=$this->afiliacion_mdl->ubigeo();
		$data['ubigeo']=$ubigeo;
		$provincia2=$this->afiliacion_mdl->provincia2($data);
		$data['provincia3']=$provincia2;
		$distrito2=$this->afiliacion_mdl->distrito2($data);
		$data['distrito3']=$distrito2;
		$data['asegurado'] = "";

			$aseg_ver = $this->afiliacion_mdl->verifica_dni($data);
			$data['aseg_ver'] = $aseg_ver;
			$cert_ver = $this->afiliacion_mdl->vertifica_cert($data);
			$data['cert_ver'] = $cert_ver;
			$this->load->view('dsb/html/afiliado/aseg_nuevo.php', $data);

	}

	public function form_incidencia($id){
		$data['cert_id'] = $id;
		$this->load->view('dsb/html/afiliado/form_inicidencia.php',$data);
	}

	public function form_cancelado($id,$num,$pl){
		$data['cert_id'] = $id;
		$data['cert_num'] = $num;
		$data['plan'] = $pl;
		$this->load->view('dsb/html/afiliado/form_cancelacion.php',$data);
	}

	public function save_incidencia(){
		date_default_timezone_set('America/Lima');
		$data['cert_id'] = $_POST['cert_id'];
		$data['desc'] = $_POST['desc_incidencia'];
		$data['hoy'] = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['tipomen'] = 1;

		$this->afiliacion_mdl->save_incidencia($data);
		$this->load->view('dsb/html/mensaje2.php', $data);

	}

	public function email() {

		$data['cert_id'] = $_POST['cert_id'];
		$data['cert_num'] = $_POST['cert_num'];
		$data['plan'] = $_POST['plan_id'];
		$data['motivo'] = $_POST['can_motivo'];
		date_default_timezone_set('America/Lima');
		$data['hoy'] = date('Y-m-d');
		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;
		$data['tipomen'] = 2;

		$this->afiliacion_mdl->in_cancelado($data);
		$this->afiliacion_mdl->up_cancertificado($data);
		$this->afiliacion_mdl->up_cancertificadoasegurado($data);

		$get_can = $this->afiliacion_mdl->get_ceancelado($data);

		foreach ($get_can as $c) {
			$plan=$c->nombre_plan;
			$tit=$c->nombre;
			$correo=$c->cont_email;
		}
		
		$mail = new PHPMailer;
		$mail->isSMTP();
	    $mail->Host     = 'relay-hosting.secureserver.net';;
	    $mail->SMTPAuth = false;
	    $mail->Username = '';
	    $mail->Password = '';
	    $mail->SMTPSecure = 'false';
	    $mail->Port     = 25;

		$tipo="'Century Gothic'";
		// Armo el FROM y el TO
		$mail->setFrom('contacto@red-salud.com', 'Red Salud');
		$mail->addAddress($correo, 'Pilar');
		$mail->addAddress('contacto@red-salud.com', 'Red Salud');
		// El asunto
		$mail->Subject = "NOTIFICACION DE DESAFILIACION RED SALUD";
		// El cuerpo del mail (puede ser HTML)
		$mail->Body = '<!DOCTYPE html>
<head>
                <meta charset="UTF-8" />
                </head>
                <body style="font-size: 1.5vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
                <div><img src="https://www.red-salud.com/gestion_afiliados/public/assets/images/desafiliacion.png" style="float:left; margin:10px; width: 13%; padding-left: 10%"></div>
                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
                </div>


                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;">'.$tit.', </b></div>
                <div  style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
                <p>Te informamos que tu solicitud ha sido procesada. Lamentamos la cancelaci&oacute;n de tu '.$plan.'.</p>
                <p>&iexcl;Recuerda que siempre es momento de pensar en tu salud!</p>                
                </div>
                <br>
                <br>
                <br>
                <br>
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
		// Los archivos adjuntos
		//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
		//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
		// Enviar
		$mail->send(); 

		$this->load->view('dsb/html/mensaje2.php', $data);
	}
}
