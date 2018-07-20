<?php
 class Menu_mdl extends CI_Model {

 function Menu_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	 
	function getMenu($idusuario) {

	 $this->db->distinct('menu.idmenu');
	 $this->db->select('menu.idmenu as Id, menu.descripcion as menu, icono');
	 $this->db->from('rol');
	 $this->db->join('submenu', 'submenu.idsubmenu = rol.idsubmenu'); 
	 $this->db->join('menu', 'menu.idmenu = submenu.idmenu'); 
	 $this->db->where('idusuario', $idusuario);

	 $menu = $this->db->get();
	 return $menu->result();

	}


	function getSubMenu($idusuario) {

	 
	 $this->db->select('menu.idmenu, archivo, submenu.descripcion as submenu');
	 $this->db->from('rol');
	 $this->db->join('submenu', 'submenu.idsubmenu = rol.idsubmenu'); 
	 $this->db->join('menu', 'menu.idmenu = submenu.idmenu'); 
	 $this->db->where('idusuario', $idusuario);

	 $submenu = $this->db->get();
	 return $submenu->result();

	}

}
?>