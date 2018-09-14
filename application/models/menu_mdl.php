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
	 //$this->db->where('idusuario', $idusuario);

	 $menu = $this->db->get();
	 return $menu->result();

	}


	function getSubMenu($idusuario) {

	 
	 $this->db->select("menu.idmenu, submenu.descripcion as submenu, case when idusuario is not null then archivo else concat('denegado/',submenu.descripcion) end as archivo");
	 $this->db->from('submenu');	 
	 $this->db->join('menu', 'menu.idmenu = submenu.idmenu'); 
	 $this->db->join('rol', 'submenu.idsubmenu = rol.idsubmenu and idusuario='.$idusuario,'left'); 
	 //$this->db->where('idusuario', $idusuario);
	 $this->db->order_by('submenu.descripcion');

	 $submenu = $this->db->get();
	 return $submenu->result();

	}

}
?>