<?php
 class Menu_mdl extends CI_Model {

 function Menu_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
 
function getData() {
 $doctores = $this->db->get('doctor'); //obtenemos la tabla 'doctores'. db->get('nombre_tabla') equivale a SELECT * FROM nombre_tabla.
 
 return $doctores->result(); //devolvemos el resultado de lanzar la query.
 }

}
?>