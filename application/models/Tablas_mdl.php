<?php
 class tablas_mdl extends CI_Model {

 function tablas_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 function getDnisPrueba(){
 	$this->db->select("c.cert_id, a.aseg_id, ca.certase_id, ce.nombre_comercial_cli, p.nombre_plan, a.aseg_numDoc, concat(coalesce(a.aseg_ape1,''),' ', coalesce(a.aseg_ape2,''),' ',coalesce(a.aseg_nom1,''),' ',coalesce(a.aseg_nom2,'')) as afiliado ");
 	$this->db->from("certificado c");
 	$this->db->join("plan p","c.plan_id=p.idplan");
 	$this->db->join("cliente_empresa ce","p.idclienteempresa=ce.idclienteempresa");
 	$this->db->join("certificado_asegurado ca","c.cert_id=ca.cert_id");
 	$this->db->join("asegurado a","ca.aseg_id=a.aseg_id");
 	$this->db->where("c.cert_num like 'PR%' and c.cert_estado=1 and ca.cert_estado=1");
 	$query = $this->db->get();
 	return $query->result();
 }

 function getCanales(){
 		$this->db->select("idclienteempresa, nombre_comercial_cli");
 		$this->db->from("cliente_empresa");
        //$this->db->where("idclienteempresa",$id);
 		$this->db->order_by("nombre_comercial_cli");

 	$canal = $this->db->get();
 	return $canal->result();
 	}

	function getPlanes2($id){
 		$this->db->select("idplan, nombre_plan, flg_cancelar");
 		$this->db->from("plan");		
 		$this->db->where("idclienteempresa",$id);

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getPlanes($id){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
 		$this->db->where("p.idclienteempresa",$id); 
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}


	function ubigeo(){
		$this->db->select("idubigeo, iddepartamento, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("idprovincia='00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$ubigeo = $this->db->get();
		return $ubigeo->result();
	}

	function provincia($dep){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento='".$dep."' and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia = $this->db->get();
		return $provincia->result();
	}

	function distrito($prov){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=SUBSTR('".$prov."',1,2) and idprovincia=SUBSTR('".$prov."',3,2) and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito = $this->db->get();
		return $distrito->result();
	}

	function provincia2($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(cont_ubg, 15,2),'') from contratante WHERE cont_numDoc='".$data['doc']."' limit 1) and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia2 = $this->db->get();
		return $provincia2->result();
	}

	function distrito2($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(cont_ubg, 15,2),'') from contratante WHERE cont_numDoc='".$data['doc']."' limit 1) and idprovincia=(SELECT coalesce(SUBSTR(cont_ubg, 17,2),'') from contratante WHERE cont_numDoc='".$data['doc']."' limit 1) and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito2 = $this->db->get();
		return $distrito2->result();
	}

	function provincia3($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia) as idprovincia, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(aseg_ubg, 1,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and idprovincia<>'00' and iddistrito='00'");
		$this->db->order_by("descripcion_ubig");

		$provincia2 = $this->db->get();
		return $provincia2->result();
	}

	function distrito3($data){
		$this->db->select("idubigeo, CONCAT(iddepartamento,idprovincia,iddistrito) as iddistrito, descripcion_ubig");
		$this->db->from("ubigeo");
		$this->db->where("iddepartamento=(SELECT coalesce(SUBSTR(aseg_ubg, 1,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and idprovincia=(SELECT coalesce(SUBSTR(aseg_ubg, 3,2),'') from asegurado WHERE aseg_id='".$data['aseg_id']."' limit 1) and iddistrito<>'00'");
		$this->db->order_by("descripcion_ubig");

		$distrito2 = $this->db->get();
		return $distrito2->result();
	}

	function verifica_dni($data)
    {        
       $this->db->select("aseg_id, aseg_nom1, aseg_nom2, aseg_ape1, aseg_ape2, aseg_direcc, coalesce(SUBSTR(aseg_ubg, 1,2),'') as dep, coalesce(SUBSTR(aseg_ubg, 1,4),'') as prov, coalesce(SUBSTR(aseg_ubg, 1,6),'') as dist, aseg_email, aseg_telf, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2)) as aseg_fechNac, tipoDoc_id, aseg_numDoc, aseg_sexo, aseg_estCiv");
         $this->db->from('asegurado');
         $this->db->where('aseg_numdoc', $data['doc']);
         $query = $this->db->get();

        if ($query->num_rows()==1)
        {
            return $query->result();
        }
        else
        {
           return "";
        }
    }

    function vertifica_cert($data)
    {
    	$this->db->select("a.aseg_id");
    	$this->db->from("asegurado a");
    	$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
    	$this->db->join("certificado c","c.cert_id=ca.cert_id");
    	$this->db->where("plan_id",$data['plan']);
    	$this->db->where("aseg_numDoc",$data['doc']);
    	$query = $this->db->get();
    	return $query->result();
    }


    function verifica_contratante ($data)
    {
    	$this->db->select("cont_id");
    	$this->db->from("contratante");
    	$this->db->where("cont_numDoc",$data['doc']);

    	$query = $this->db->get();
    	return $query->result();
    }

   function in_contratante($data)
    {
    	$array = array(
    		'cont_tipoDoc' => $data['tipodoc'],
			'cont_numDoc' => $data['doc'],
			'cont_nom1' => $data['nom1'],
			'cont_nom2' => $data['nom2'],
			'cont_ape1' => $data['ape1'],
			'cont_ape2' => $data['ape2'],
			'cont_direcc' => $data['direccion'],
			'cont_ubg' => "00000000000000".$data['dis'],
			'cont_telf' => $data['telf'],
			'cont_email' => $data['correo']
			);
		$this->db->insert('contratante',$array);
    }
    function get_cert_num($data)
    {
    	$this->db->select("case when max(cert_id) is null then 10000 else max(cert_id)+1 end as cert_num");
    	$this->db->from("certificado");
    	$this->db->where("plan_id",$data['plan']);

    	$query=$this->db->get();
    	return $query->result();
    }

    function in_certificado($data)
    {
    	$array = array(
    		'cert_num' => $data['cert_num'],
    		'plan_id' => $data['plan'],
    		'cert_estado' => 1,
    		'cert_iniVig' => $data['inicio'],
    		'cert_finVig' => $data['fin'],
    		'cli_id' => $data['canal'],
    		'cont_id' => $data['cont_id'],
    		'idusuario' => $data['idusuario']
    		);
    	$this->db->insert('certificado',$array);
    }

    function in_asegurado($data){
		$array = array(
			'tipoDoc_id' => $data['tipodoc'],
			'aseg_numDoc' => $data['doc'],
			'aseg_nom1' => $data['nom1'],
			'aseg_nom2' => $data['nom2'],
			'aseg_ape1' => $data['ape1'],
			'aseg_ape2' => $data['ape2'],
			'aseg_fechNac' => str_replace("-","",$data['fec_nac']),
			'aseg_direcc' => $data['direccion'],
			'aseg_telf' => $data['telf'],
			'aseg_ubg' => $data['dis'],
			'aseg_estCiv' => $data['ec'],
			'aseg_sexo' => $data['genero'],
			'aseg_email' => $data['correo']
			);
		$this->db->insert('asegurado',$array);
	}

	function in_certase($data){
		$array = array(
			'cert_id' => $data['cert'],
			'aseg_id' => $data['id'],
			'cert_estado' => 1,
			'cert_iniVig' => $data['inicio'],
			'cert_finVig' => $data['fin'],
			'idusuario' => $data['idusuario']
			);
		$this->db->insert('certificado_asegurado',$array);
	}

	function up_aseg($data){
		$array = array(
			'tipoDoc_id' => $data['tipodoc'],
			'aseg_numDoc' => $data['doc'],
			'aseg_nom1' => $data['nom1'],
			'aseg_nom2' => $data['nom2'],
			'aseg_ape1' => $data['ape1'],
			'aseg_ape2' => $data['ape2'],
			'aseg_fechNac' => str_replace("-","",$data['fec_nac']),
			'aseg_direcc' => $data['direccion'],
			'aseg_telf' => $data['telf'],
			'aseg_ubg' => $data['dis'],
			'aseg_estCiv' => $data['ec'],
			'aseg_sexo' => $data['genero'],
			'aseg_email' => $data['correo']
		);
		$this->db->where('aseg_id',$data['aseg_id']);
		return $this->db->update('asegurado', $array);
	}

	function baja_certasegurado($id){
		$array = array('cert_estado' => 3 );
		$this->db->where('certase_id',$id);
		$this->db->update("certificado_asegurado",$array);
	}

	function num_afiliados($id){
		$this->db->select("*");
		$this->db->from("certificado_asegurado");
		$this->db->where("cert_id",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function getCertificado($id){
		$this->db->select("*");
		$this->db->from("certificado");
		$this->db->where("cert_id",$id);
		$query = $this->db->get();
		return $query->result();
	}

	function baja_certificado($id){
		$array = array('cert_estado' => 3 );
		$this->db->where("cert_id",$id);
		$this->db->update("certificado",$array);
	}

	function cancelar_cert($data){
		$array = array
		(
			'cert_id' => $data['cert_id'],
			'can_cert_num' => $data['cert_num'],
			'can_estadoCert' => 3,
			'plan_id' => $data['plan_id'] 
		);
		$this->db->insert("cancelado",$array);
	}
}