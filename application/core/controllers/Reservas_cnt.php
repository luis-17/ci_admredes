<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservas_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('reserva_mdl');

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

			$mis_reservas = $this->reserva_mdl->getMisReservas($idusuario);
			$data['mis_reservas'] = $mis_reservas;

			$data['reservas_confirmadas'] = $this->reserva_mdl->getReservasConfirmadas();

			$otras_reservas = $this->reserva_mdl->getOtrasReservas($idusuario);
			$data['otras_reservas'] = $otras_reservas;

			$reservas_atendidas = $this->reserva_mdl->getReservasAtendidas();
			$data['reservas_atendidas'] = $reservas_atendidas;

			$atenciones_directas = $this->reserva_mdl->getAtencionesDirectas();
			$data['atenciones_directas'] = $atenciones_directas;

			$this->load->view('dsb/html/reserva/reservas.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function notificacion_afiliado($idcita){
		$getCita = $this->reserva_mdl->getCita($idcita);
		$data['idcita'] = $idcita;
		$data['afiliado'] = $getCita['afiliado'];
		$data['nombre'] = $getCita['nombre'];
		$data['proveedor'] = $getCita['nombre_comercial_pr'];
		$dia = $getCita['fecha_cita'];
		$data['dia'] = $dia;
		$data['hora_inicio'] = $getCita['hora_cita_inicio'];
		$data['especialidad'] = $getCita['nombre_esp'];
		$data['idespecialidad'] = $getCita['idespecialidad'];
		$data['plan_id'] = $getCita['plan_id'];	
		$data['nombre_plan'] = $getCita['nombre_plan'];	
		$consulta = $this->reserva_mdl->getConsultaMedica($data);
		$data['consulta'] = $consulta['consulta'];
		$data['cobertura_operador'] = $this->reserva_mdl->getCoberturasOperador($data);
		$this->load->view('dsb/html/reserva/notificacion_afiliado.php',$data);
	}

	public function notificar($idcita){
		$this->reserva_mdl->upCitaNotificacion($idcita);

	echo "<script>
				alert('Se registró la notificación.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}

}