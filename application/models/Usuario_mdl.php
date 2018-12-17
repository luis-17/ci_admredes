<?php
 class Usuario_mdl extends CI_Model {

 function Usuario_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getColaboradores(){
 		$this->db->select("LPAD(c.idusuario,5,0) as numero, c.idusuario, concat(c.ap_paterno_col,' ',c.ap_materno_col,' ',c.nombres_col) as colaborador, descripcion_tu, u.username, c.correo_laboral, u.estado_us");
 		$this->db->from("colaborador c");
 		$this->db->join("usuario u","u.idusuario=c.idusuario");
 		$this->db->join("tipo_usuario tp","tp.idtipousuario=u.idtipousuario");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getTipoUsuario(){
 		$this->db->select('*');
 		$this->db->from('tipo_usuario tp');
 		$this->db->order_by("descripcion_tu");

	 $query = $this->db->get();
	 return $query->result();
 	}

 	function getColaborador($id){
 		$this->db->select("*");
 		$this->db->from("colaborador c");
 		$this->db->join("usuario u","u.idusuario=c.idusuario");
 		$this->db->where("u.idusuario",$id);

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function insert_usuario($data){
 		$array = array
 		(
 			'idtipousuario' => $data['idtipousuario'],
 			'emailusuario' => $data['usuario'],
 			'username' => $data['usuario'],
 			'password_view' => $data['usuario'],
 			'password' => md5($data['usuario']),
 			'estado_us' => 1
 		);
 		$this->db->insert("usuario",$array);
 	}

 	function insert_colaborador($data){
 		$array = array
 		(
 			'idtipodocumentoidentidad' => 1,
 			'idusuario' => $data['idusuario2'],
 			'nombres_col' => $data['nombres'],
 			'ap_paterno_col' =>  $data['ap1'],
 			'ap_materno_col' => $data['ap2'],
 			'fecha_nacimiento_col' => $data['fec'],
 			'correo_laboral' => $data['correo'],
 			'numero_documento_col' => $data['dni'],
 			'estado_col' => 1,
 			'celular_col' => $data['telf']
 		);
 		$this->db->insert("colaborador",$array);
 	}

 	function update_usuario($data){
 		$array = array
 		(
 			'idtipousuario' => $data['idtipousuario'],
 			'emailusuario' => $data['usuario'],
 			'username' => $data['usuario']
 		);
 		$this->db->where("idusuario",$data['idusuario2']);
 		$this->db->update("usuario",$array);
 	}

 	function update_colaborador($data){
 		$array = array
 		(
 			'idtipodocumentoidentidad' => 1,
 			'idusuario' => $data['idusuario2'],
 			'nombres_col' => $data['nombres'],
 			'ap_paterno_col' =>  $data['ap1'],
 			'ap_materno_col' => $data['ap2'],
 			'fecha_nacimiento_col' => $data['fec'],
 			'correo_laboral' => $data['correo'],
 			'numero_documento_col' => $data['dni'],
 			'estado_col' => 1,
 			'celular_col' => $data['telf']
 		);
 		$this->db->where("idcolaborador",$data['idcolaborador2']);
 		$this->db->update("colaborador",$array);
 	}

 	function getMenu(){
 		$this->db->select("*");
 		$this->db->from("menu");
 		$this->db->order_by("descripcion");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getSubmenu($id, $idu){
 		$this->db->select("sm.idsubmenu, descripcion, case when a.idsubmenu= sm.idsubmenu then 'checked' else '' end as checked");
 		$this->db->from("submenu sm");
 		$this->db->join("(select idsubmenu from rol where idusuario = $idu)a","a.idsubmenu=sm.idsubmenu","left");
 		$this->db->where("idmenu",$id);
 		$this->db->order_by("descripcion");

 		$query = $this->db->get();
 		return $query->result();
 	}

 	function getRoles($id){
 		$this->db->select("lpad(concat(m.idmenu,s.idsubmenu),8,0) as id,s.idsubmenu, s.descripcion as submenu, m.idmenu, m.descripcion as menu");
 		$this->db->from("submenu s");
 		$this->db->join("menu m","m.idmenu=s.idmenu");
 		$this->db->join("rol r","r.idsubmenu=s.idsubmenu");
 		$this->db->where("r.idusuario",$id);
 		$query = $this->db->get();
 		return $query->result();
 	}

 	function eliminar_roles($data){		
 		$this->db->where("idusuario=".$data['idusuario2']." and estado=1 and idsubmenu in (select idsubmenu from submenu where idmenu=".$data['menu'].")");
 		$this->db->delete("rol");
 	}

 	function select_rol($data){
 		$this->db->select("idrol");
 		$this->db->from("rol");
 		$this->db->where("idusuario",$data['idusuario2']);
 		$this->db->where("idsubmenu",$data['idsubmenu']);
 		$query = $this->db->get();
 		return $query->result();
 	}

 	function activar_rol($data){
 		$array = array('estado' => 0 );
 		$this->db->where("idusuario",$data['idusuario2']);
 		$this->db->where("idsubmenu",$data['idsubmenu']);
 		$this->db->update("rol",$array);
 	}

 	function insertar_rol($data){
 		$array = array
 		(
 			'idsubmenu' => $data['idsubmenu'],
 			'idusuario' => $data['idusuario2'],
 			'estado' =>0
 		);

 		$this->db->insert("rol",$array);
 	}

 	function cambiar_roles($data){
 		$array = array('estado' => 1 );
 		$this->db->where("idusuario",$data['idusuario2']);
 		$this->db->update("rol",$array);
 	}

 	function usuario_anular($id){
 		$array = array('estado_us' => 0 );
 		$this->db->where("idusuario",$id);
 		$this->db->update("usuario", $array);
 	}

 	function usuario_activar($id){
 		$array = array('estado_us' => 1 );
 		$this->db->where("idusuario",$id);
 		$this->db->update("usuario", $array);
 	}
 	
 	function destinatario($id){
 		$this->db->select("*");
 		$this->db->from("usuario u");
 		$this->db->join("colaborador c","c.idusuario=u.idusuario");
 		$this->db->where("u.idusuario",$id);

 		$query = $this->db->get();
 		return $query->result();
 	}
}
?>