<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class series_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('series_mdl');

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
 	public function series(){
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

			$canales = $this->series_mdl->getCanales();
			$data['canales'] = $canales;

			$this->load->view('dsb/html/tablas_maestras/series.php',$data);
		}
		else{
			redirect('/');
		}
 	}

 	public function add_serie($id){
 		$canal = $this->series_mdl->getCanalesId($id);

 		foreach ($canal as $c) {
 			$data['cliente'] = $c->nombre_comercial_cli;
 		}
 		$data['idclienteempresa'] = $id;
 		$this->load->view('dsb/html/tablas_maestras/add_serie.php',$data);
 	}

 	public function correlativoSerie(){
 		$html = null;
 		$tipo = $_POST['tipoDoc'];

 		$serie = $this->series_mdl->getSerieMayor($tipo);

 		foreach ($serie as $s) {

 			$resultado = intval(preg_replace('/[^0-9]+/', '', $s->numero_serie), 10);
 			$correlativo = $resultado+1;
 			$valorcero = str_pad($correlativo, 3, 0, STR_PAD_LEFT);
 			$nuevaSerie = $tipo.$valorcero;
 			
 		}

 		$html.="<input type='text' class='form-control' name='serieGen' id='serieGen' value='".$nuevaSerie."' readonly>";

 		echo json_encode($html);

 	}

 	public function reg_serie(){

 		$id = $_POST['idclienteempresa'];
 		$tipo = $_POST['tipoDoc'];

 		if ($tipo == 'B') {
 			$valor = 3;
 		} else {
 			$valor = 2;
 		}

 		$serie = $_POST['serieGen'];
 		$canal = $this->series_mdl->getCanalesId($id);

 		foreach ($canal as $c) {
 			$this->series_mdl->insertar_serie($serie, $c->razon_social_cli, $valor);

 			$serie = $this->series_mdl->getIdSerieMayor($tipo);

 			foreach ($serie as $s) {
 				$this->series_mdl->up_serie($s->idserie, $id);
 			}

 		}
 	}

 	public function del_serie($id){
 		$canal = $this->series_mdl->getCanalesId($id);

 		foreach ($canal as $c) {
 			$data['cliente'] = $c->nombre_comercial_cli;
 			$data['serie'] = $c->numero_serie;
 		}
 		$data['idclienteempresa'] = $id;
 		$this->load->view('dsb/html/tablas_maestras/del_serie.php',$data);
 	}

  	public function delete_serie(){

 		$id = $_POST['idclienteempresa'];
 		$serie = $_POST['serie'];

 		$this->series_mdl->up_serieNull($id);
 		$this->series_mdl->delete_serie($serie);
 	}

}
