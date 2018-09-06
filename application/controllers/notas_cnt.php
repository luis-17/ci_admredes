<?php
ini_set('max_execution_time', 6000); 
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('soap.wsdl_cache_ttl',0); 
defined('BASEPATH') OR exit('No direct script access allowed');
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Greenter\XMLSecLibs\Sunat\SignedXml;

class notas_cnt extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->model('menu_mdl');
        $this->load->model('comprobante_pago_mdl');
        $this->load->library('My_PHPMailer');

    }

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

			$month = date('m');
	      	$year = date('Y');
	      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

	      	$data['idserie'] = 0;
			$data['fechaDoc'] = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$data['correlativoDoc'] = "";


			$html = null;
			$iddocumento = null;

			$serie = $this->comprobante_pago_mdl->getSerie();
			$data['serie'] = $serie;

			//$this->load->view('dsb/html/comprobante/generar_comp.php',$data);

			/*foreach ($serie as $c):

				if ($iddocumento==$c->idtipodocumentomov) {
					$estp´= 'selected';
				} else {
					$estp='';
				}

				if ($canales == 0) {
					$html .= "<option id='numeroSerie' name='numeroSerie' value='0' ".$estp." >Seleccione</option>";
				} else {
					$html .= "<option id='numeroSerie' name='numeroSerie' value='".$c->numero_serie."' ".$estp." >".$c->descripcion_tdm."</option>";
				}
					//$html .="<input type='text' class='hidden' id='numeroSerieCor' name='numeroSerieCor' value='".$c->numero_serie."'>";
			endforeach;

			echo json_encode($html);*/

			$this->load->view('dsb/html/comprobante/notas_comp.php',$data);

		} else {
			redirect('/');
		}
	}

}