<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');        
        $this->load->model('proveedor_mdl');
        $this->load->library("pagination");
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

			$this->load->view('dsb/html/proveedor/proveedor.php',$data);
		}
		else{
			redirect('/');
		}
	}

	function pagination()
	{
	  $config = array();
	  $config["base_url"] = "#";
	  $config["total_rows"] = $this->proveedor_mdl->count_all();
	  $config["per_page"] = 5;
	  $config["uri_segment"] = 3;
	  $config["use_page_numbers"] = TRUE;
	  $config["full_tag_open"] = '<ul class="pagination">';
	  $config["full_tag_close"] = '</ul>';
	  $config["first_tag_open"] = '<li>';
	  $config["first_tag_close"] = '</li>';
	  $config["last_tag_open"] = '<li>';
	  $config["last_tag_close"] = '</li>';
	  $config['next_link'] = '&gt;';
	  $config["next_tag_open"] = '<li>';
	  $config["next_tag_close"] = '</li>';
	  $config["prev_link"] = "&lt;";
	  $config["prev_tag_open"] = "<li>";
	  $config["prev_tag_close"] = "</li>";
	  $config["cur_tag_open"] = "<li class='active'><a href='#'>";
	  $config["cur_tag_close"] = "</a></li>";
	  $config["num_tag_open"] = "<li>";
	  $config["num_tag_close"] = "</li>";
	  $config["num_links"] = 1;
	  $this->pagination->initialize($config);
	  $page = $this->uri->segment(3);
	  $start = ($page - 1) * $config["per_page"];

	  $output = array(
	   'pagination_link'  => $this->pagination->create_links(),
	   'tabbable'   => $this->proveedor_mdl->getProveedores($config["per_page"], $start)
	  );
	  echo json_encode($output);
	}

	public function habilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->habilitar($id);
		redirect ('proveedor');
	}

	public function inhabilitar($id)
	{
		$anular_proveedor = $this->proveedor_mdl->inhabilitar($id);
		redirect ('proveedor');
	}

	public function nuevo()
	{	
		$id=0;
		$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
		$data['data_general'] = $dataproveedor;
		$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
		$data['data_tipoproveedor'] = $datatipoproveedor;
		$departamento = $this->proveedor_mdl->departamento();
		$data['departamento'] = $departamento;
		$this->load->view('dsb/html/proveedor/form_proveedor.php',$data);
	}

	public function editar($id)
	{
		$dataproveedor = $this->proveedor_mdl->dataproveedor($id);
		$data2['data_general'] = $dataproveedor;
		$datatipoproveedor = $this->proveedor_mdl->datatipoproveedor();
		$data2['data_tipoproveedor'] = $datatipoproveedor;
		$departamento = $this->proveedor_mdl->departamento();
		$data2['departamento'] = $departamento;
		$provincia = $this->proveedor_mdl->provincia($id);
		$data2['provincia'] = $provincia;
		$this->load->view('dsb/html/proveedor/form_proveedor.php',$data2);
	}


}
