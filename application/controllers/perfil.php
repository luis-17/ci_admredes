<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros')); 
        $this->load->model('perfil_mdl'); 
        $this->load->model('menu_mdl');
    }

    public function index()
    {
        $user = $this->session->userdata('user');
        extract($user);

        $usuario=$this->perfil_mdl->get_usuario($idusuario);
        $data['usuario'] =$usuario;

        $this->load->view('dsb/html/perfil.php',$data);
    }

    public function cambiar_pass()
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
            $data['id'] = "";   
            $data['id2'] = 0;
            $data['nom'] = "";          

            $usuario=$this->perfil_mdl->get_usuario($idusuario);
            $data['usuario'] =$usuario;
            $data['mensaje1'] = "";
            $data['mensaje2'] = "";
            $data['oldpass'] = "";
            $data['newpass'] = "";
            $data['newpass2'] = "";
            $data['passold'] = "";
            $data['passnew'] = "";
            $data['passnew2'] = "";       

            $this->load->view('dsb/html/cambiar_pass.php',$data);

            }
        else{
            redirect('/');
        }
    }

    public function actualizar_pass()
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
            $data['id'] = "";   
            $data['id2'] = 0;
            $data['nom'] = "";        

            $oldpass = $_POST['passold'];
            $newpass = $_POST['passnew'];
            $newpass2 = $_POST['passnew2'];
            $data['passold'] = $_POST['passold'];
            $data['passnew'] = $_POST['passnew'];
            $data['passnew2'] = $_POST['passnew2'];        
            $usuario=$this->perfil_mdl->get_usuario($idusuario);
            $data['usuario'] =$usuario;
            $data['id'] = $idusuario ;
            $estado = 0;

            if($newpass==$newpass2){
            $data['newpass'] = "";
            $data['newpass2'] = "";
            $data['mensaje2'] = "";
            }else{
                $data['newpass'] = $newpass;
                $data['newpass2'] = $newpass2;
                $data['mensaje2'] = "Las contraseñas no coinciden";
                $estado = 1;
            }

            $ver_passold = $this->perfil_mdl->verifica_oldpass($data);
            if(!empty($ver_passold)){
                $data['mensaje1']="";
                if($estado==1){
                    $data['oldpass'] = $oldpass; 
                }else{
                    $data['oldpass']="";
                    if($oldpass==$newpass){
                        $data['mensaje2'] = "La nueva contraseña es igual a la anterior";
                        $data['newpass'] = $newpass;
                        $data['newpass2'] = $newpass2;
                        $data['oldpass'] = $oldpass; 
                        $estado = 2;
                    }
                }
            }else{
                $data['mensaje1'] = "La contraseña es incorrecta";
                $data['oldpass'] = $oldpass;  
                $data['newpass'] = $newpass;
                $data['newpass2'] = $newpass2;
                $data['mensaje2'] = "";
                $estado = 2;
            }


            if($estado==0){
                $this->perfil_mdl->actualizar_pass($data);
                redirect("index.php/logout");
            }

             $this->load->view('dsb/html/cambiar_pass.php',$data);
        
        }else{
                redirect('/');
        }
    }
}