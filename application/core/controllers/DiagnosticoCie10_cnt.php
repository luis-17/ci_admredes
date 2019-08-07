<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiagnosticoCie10_cnt extends CI_Controller {
  public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('menu_mdl');

    }


	function index()
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

      $this->load->view('dsb/html/diagnosticoCie10.php', $data);
    }
    else{
      redirect('/');
    }  
 }

 function pagination()
 {
  $this->load->model("diagnosticoCie10_mdl");
  $this->load->library("pagination");
  $config = array();
  $config["base_url"] = "#";
  $config["total_rows"] = $this->diagnosticoCie10_mdl->count_all();
  $config["per_page"] = 10;
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
   'country_table'   => $this->diagnosticoCie10_mdl->fetch_details($config["per_page"], $start)
  );
  echo json_encode($output);
 }
}	