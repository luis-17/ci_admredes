<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 class Series_mdl extends CI_Model {

 	function Menu_mdl() {
 		parent::__construct(); //llamada al constructor de Model.
 		$this->load->database();
 	}

 	function getCanales(){
 		$this->db->select('idclienteempresa, nombre_comercial_cli, razon_social_cli, numero_documento_cli, estado_cli, id_serie, s.numero_serie');
 		$this->db->from('cliente_empresa c');
 		$this->db->join('serie s', 'c.id_serie=s.idserie', 'left');

	 $canales = $this->db->get();
	 return $canales->result();
 	}

  	function getCanalesId($id){
 		$this->db->select('idclienteempresa, nombre_comercial_cli, razon_social_cli, numero_documento_cli, estado_cli, id_serie, s.numero_serie');
 		$this->db->from('cliente_empresa c');
 		$this->db->join('serie s', 'c.id_serie=s.idserie', 'left');
 		$this->db->where('idclienteempresa='.$id);

	 $canales = $this->db->get();
	 return $canales->result();
 	}

  	function getSerieMayor($letra){
 		$this->db->select('idserie, numero_serie');
 		$this->db->from('serie');
 		$this->db->where("numero_serie LIKE '".$letra."%' AND numero_serie NOT LIKE '%C%' AND numero_serie NOT LIKE '%D%'");
 		$this->db->order_by("numero_serie DESC");
		$this->db->limit(1);


	 $canales = $this->db->get();
	 return $canales->result();
 	}


	function insertar_serie($serie, $descripcion_ser, $tipo){
		$this->db->set('idempresaadmin', 1);
		$this->db->set('numero_serie', $serie);
		$this->db->set('descripcion_ser', $descripcion_ser);
		$this->db->set('tipo_documento', $tipo);
		$this->db->insert('serie');
	}

  	function getIdSerieMayor($letra){
 		$this->db->select('idserie');
 		$this->db->from('serie');
 		$this->db->where("numero_serie LIKE '".$letra."%' AND numero_serie NOT LIKE '%C%' AND numero_serie NOT LIKE '%D%'");
 		$this->db->order_by("numero_serie DESC");
		$this->db->limit(1);

	 $canales = $this->db->get();
	 return $canales->result();
 	}

 	function up_serie($serie, $idclienteempresa){
		$this->db->set("id_serie", $serie);
		$this->db->where("idclienteempresa=".$idclienteempresa);
		$this->db->update("cliente_empresa"); 
	}

 	function up_serieNull($idclienteempresa){
		$this->db->set("id_serie", NULL);
		$this->db->where("idclienteempresa=".$idclienteempresa);
		$this->db->update("cliente_empresa"); 
	}

	function delete_serie($serie){
		$this->db->where("numero_serie='".$serie."'");
		$this->db->delete("serie");
	}

}
?>