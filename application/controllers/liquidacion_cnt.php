<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');
        $this->load->model('atencion_mdl');
        $this->load->model('liquidacion_mdl');

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

			$pre_liquidaciones = $this->liquidacion_mdl->getLiquidaciones();
			$data['pre_liquidaciones'] = $pre_liquidaciones;

			$liquidaciones = $this->liquidacion_mdl->get_liquidaciongrupo();
			$data['liquidaciones'] = $liquidaciones;

			$preorden = $this->atencion_mdl->getPreOrden();
			$data['preorden'] = $preorden;

			// datos para combo especialidad
			$this->load->model('siniestro_mdl');        
	        $data['especialidad'] = $this->siniestro_mdl->getEspecialidad();

	        // datos para combo especialidad		
			$data['proveedor'] = $this->atencion_mdl->getProveedor();

			$this->load->view('dsb/html/liquidacion/liquidacion.php',$data);
		}
		else{
			redirect('/');
		}	
	}


	public function registraPago()
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
		
			//recogemos los datos obtenidos por POST
			        
		    $data['liqdetalleid'] = $_POST['liqdetalleid'];
		    $liqdetalleid =  $data['liqdetalleid'];
		    
		    $data['pagoFecha'] = $_POST['pagoFecha'];
		    $data['pagoForma'] = $_POST['pagoForma'];
		    $data['pagoBanco'] = $_POST['pagoBanco'];
		    $data['pagoNoperacion'] = $_POST['pagoNoperacion'];
		    $data['pagoNfactura'] = $_POST['pagoNfactura'];	    

		    $buscaLiqdetalleid = $this->liquidacion_mdl->buscaLiqDeta($liqdetalleid);

		    if ($buscaLiqdetalleid){
		    	$this->liquidacion_mdl->updateLiqdetalle($data);
		    }else {
		    	$this->liquidacion_mdl->guardaLiqdetalle($data);
		    }

		    $this->liquidacion_mdl->updateEstadoLiqdetalle($data);

			//$this->load->view('dsb/html/liquidacion',$data);
			redirect(base_url()."index.php/liquidacion/");

			//$this->load->view('dsb/html/atencion/test.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	

	public function orden($id,$est)
	{
		$orden = $this->atencion_mdl->orden($id,$est);
		redirect ('index.php/atenciones');
	}

	public function save_liqgrupo()
	{
		$liq_det=$_POST['liq_det'];
		$num=count($liq_det);

		$user = $this->session->userdata('user');
		extract($user);
		$data['idusuario'] = $idusuario;

		$this->liquidacion_mdl->save_liquidacion($data);
		$data['liq_id'] = $this->db->insert_Id();

		$numero = str_pad($data['liq_id'], 8, "0", STR_PAD_LEFT);

		for($i=0;$i<$num;$i++){
			$data['liq_det']=$liq_det[$i];
			$this->liquidacion_mdl->save_liqdetalle_grupo($data);
			$this->liquidacion_mdl->up_liqdetalle($data);
			//enviar $michk[$i] al model para hacer insert
		}

		echo "<script>
				alert('Se ha generado la liquidación N° L".$numero." con éxito.');
				location.href='".base_url()."index.php/liquidacion';
				</script>";
	}

	public function getLiquidacionDet($id, $num){
		$liquidacionDet = $this->liquidacion_mdl->getLiquidacionDet($id);
		$data['liquidacionDet'] = $liquidacionDet;
		$data['numero'] = $num;
		$this->load->view('dsb/html/liquidacion/liquidacion_detalle.php',$data);

	}

	public function liquidacion_grupo(){
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

			$liquidacion_grupo_p = $this->liquidacion_mdl->get_liquidaciongrupo_p();
			$data['liquidacion_grupo_p'] = $liquidacion_grupo_p;

			$liquidacion_grupo_c = $this->liquidacion_mdl->get_liquidaciongrupo_c();
			$data['liquidacion_grupo_c'] = $liquidacion_grupo_c;

			$this->load->view('dsb/html/liquidacion/liquidacion_grupo.php',$data);
		}
		else{
			redirect('/');
		}	
	}

	public function liquidacion_regpago($id, $num){
		$data['liqgrupoid'] = $id;
		$data['numero'] = $num;
		$data['bancos'] = $this->liquidacion_mdl->getBancos();
		$data['forma_pago'] =$this->liquidacion_mdl->getFormaPago();
		$this->load->view('dsb/html/liquidacion/registrar_pago.php',$data);
	}

	public function save_regPago(){

		$id = $_POST['liqgrupoid'];

		$mi_archivo = 'mi_archivo';
        $config['upload_path'] = "uploads/";
        $config['file_name'] = $id;
        $config['allowed_types'] = "pdf";
        $config['max_size'] = "50000";
        $config['max_width'] = "2000";
        $config['max_height'] = "2000";

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload($mi_archivo)) {
            //*** ocurrio un error
            $data['uploadError'] = $this->upload->display_errors();
            echo $this->upload->display_errors();
            return;
        }

        $data['uploadSuccess'] = $this->upload->data();


		$user = $this->session->userdata('user');
		extract($user);

		$data['liqgrupoid'] = $id;
		$data['idbanco'] = $_POST['banco'];
		$data['forma'] = $_POST['forma'];
		$data['nro_operacion'] = $_POST['nro_operacion'];
		$data['fecha'] = $_POST['fecha'];
		$data['correo'] = $_POST['correo'];
		$data['idusuario'] = $idusuario;

		$this->liquidacion_mdl->save_Pago($data);

		echo "<script>
				alert('Se registró el pago para la liquidación N° L".$_POST['numero']." con éxito.');
				parent.location.reload(true);
				parent.$.fancybox.close();
				</script>";
	}
}