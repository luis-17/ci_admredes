<?php
 class Afiliacion_mdl extends CI_Model {

 function Afiliacion_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	 
	function getCanales($id){
 		$this->db->select("idclienteempresa, nombre_comercial_cli");
 		$this->db->from("cliente_empresa");
        $this->db->where("idcategoriacliente",2);
        $this->db->where("estado_cli",1);
 		$this->db->order_by("nombre_comercial_cli");

 	$canal = $this->db->get();
 	return $canal->result();
 	}

	function getPlanes2($id){
 		$this->db->select("idplan, nombre_plan, flg_cancelar");
 		$this->db->from("plan");		
    	$this->db->where("estado_plan",1);
 		$this->db->where("idclienteempresa",$id);

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getPlanes($id){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");
 		$this->db->where("p.idclienteempresa",$id); 		
    	$this->db->where("estado_plan",1);
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function certificado($data){
 		$this->db->select("c.cert_id, cert_num, p.num_afiliados, co.cont_id, cant, cast(c.fec_reg as date) as fec_reg");
 		$this->db->from("certificado c");
 		$this->db->join("contratante co", "c.cont_id=co.cont_id");
 		$this->db->join("plan p","c.plan_id=p.idplan");
 		$this->db->join("cliente_empresa ce","ce.idclienteempresa=p.idclienteempresa");
 		$this->db->join("(select count(certase_id) as cant, c.cert_id from certificado c inner join contratante co on c.cont_id=co.cont_id inner join plan p on p.idplan=c.plan_id LEFT JOIN certificado_asegurado ca on c.cert_id = ca.cert_id WHERE `p`.`idclienteempresa` = ".$data['canal']." AND `idplan` = ".$data['plan']." AND `cont_numDoc` = ".$data['doc']."
GROUP BY c.cert_id)b","b.cert_id=c.cert_id");
 		$this->db->where("p.idclienteempresa",$data['canal']);
 		$this->db->where("idplan",$data['plan']);
 		$this->db->where("cont_numDoc",$data['doc']);
 		$this->db->where("c.cert_estado",1);

  	$certificado = $this->db->get();
  	return $certificado->result();
 	}

 	function buscar($data){
    $this->db->select("ce.cert_id, c.cont_id, cont_numDoc, cont_nom1, cont_nom2, cont_ape1, cont_ape2, cont_tipoDoc, cont_direcc, cont_telf, cont_email, coalesce(SUBSTR(cont_ubg, 15,2),'') as dep, coalesce(SUBSTR(cont_ubg, 15,4),'') as prov, coalesce(SUBSTR(cont_ubg, 15,6),'') as dist");
    $this->db->from("contratante c");
    $this->db->join("certificado ce","c.cont_id=ce.cont_id");
    $this->db->join("plan p","p.idplan=ce.plan_id");
    $this->db->where("p.idclienteempresa",$data['canal']);
    $this->db->where("idplan",$data['plan']);
    $this->db->where("cont_numDoc",$data['doc']);
    $this->db->where("ce.cert_estado",1);

  	$datos = $this->db->get();
  	return $datos->result();
	}

	function asegurados($data){
	$this->db->select("a.aseg_id,aseg_numDoc, concat(aseg_ape1,' ', aseg_ape2,' ', aseg_nom1,' ', COALESCE(aseg_nom2,'')) as asegurado, ca.cert_id");
    $this->db->from("asegurado a");
    $this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
    $this->db->join("certificado ce","ce.cert_id=ca.cert_id");
    $this->db->join("contratante c","c.cont_id=ce.cont_id");
    $this->db->join("plan p","p.idplan=ce.plan_id");
    $this->db->where("p.idclienteempresa",$data['canal']); 
    $this->db->where("cont_numDoc",$data['doc']);
    $this->db->where("cont_numDoc",$data['doc']);
    $this->db->where("ce.cert_estado",1);
    $this->db->where("ca.cert_estado",1);   

  	$asegurados = $this->db->get();
  	return $asegurados->result();
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

	function cont_save($data){
		$array = array(
			'cont_tipoDoc' => $data['tipodoc'],
			'cont_numDoc' => $data['doc'],
			'cont_nom1' => $data['nom1'],
			'cont_nom2' => $data['nom2'],
			'cont_ape1' => $data['ape1'],
			'cont_ape2' => $data['ape2'],
			'cont_direcc' => $data['direcc'],
			'cont_ubg' => "(select LPAD('".$data['ubigeo']."',20,'0'))",
			'cont_telf' => $data['telf'],
			'cont_email' => $data['correo']
		);
		$this->db->where('cont_id',$data['cont_id']);
		return $this->db->update('contratante', $array);
	}

	function getAseg_editar($id,$cert){

		$this->db->select("a.aseg_id, aseg_nom1, aseg_nom2, aseg_ape1, aseg_ape2, aseg_direcc, coalesce(SUBSTR(aseg_ubg, 1,2),'') as dep, coalesce(SUBSTR(aseg_ubg, 1,4),'') as prov, coalesce(SUBSTR(aseg_ubg, 1,6),'') as dist, aseg_email, aseg_telf, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2)) as aseg_fechNac, tipoDoc_id, aseg_numDoc, aseg_sexo, aseg_estCiv");
		$this->db->from('asegurado a ');
		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
		$this->db->join("certificado c","c.cert_id=ca.cert_id");
		$this->db->where('a.aseg_id',$id);
		$this->db->where('c.cert_id',$cert);
	$aseg=$this->db->get();
	return $aseg->result();
	}

	function getAseg_editar2($data){

		$this->db->select("a.aseg_id, aseg_nom1, aseg_nom2, aseg_ape1, aseg_ape2, aseg_direcc, coalesce(SUBSTR(aseg_ubg, 1,2),'') as dep, coalesce(SUBSTR(aseg_ubg, 1,4),'') as prov, coalesce(SUBSTR(aseg_ubg, 1,6),'') as dist, aseg_email, aseg_telf, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2)) as aseg_fechNac, tipoDoc_id,'".$data['doc']."' as aseg_numDoc,  aseg_sexo, aseg_estCiv");
		$this->db->from('asegurado a ');
		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
		$this->db->join("certificado c","c.cert_id=ca.cert_id");
		$this->db->where('a.aseg_id',$data['aseg_id']);
		$this->db->where('c.cert_id',$data['cert_id']);
	$aseg=$this->db->get();
	return $aseg->result();
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
			'cert_iniVig' => "now()",
			'cert_finVig' => "(SELECT cert_finVig from certificado WHERE cert_id=".$data['cert'].")",
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

	function num_aseg($data){
		$this->db->select("count(certase_id) as cant, c.cert_id");
		$this->db->from('certificado c');
		$this->db->join("contratante co","c.cont_id=co.cont_id");
		$this->db->join("plan p","p.idplan=c.plan_id");		
		$this->db->join("certificado_asegurado ca","c.cert_id=ca.cert_id");
	    $this->db->where("p.idclienteempresa",$data['canal']);
	    $this->db->where("idplan",$data['plan']);
	    $this->db->where("cont_numDoc",$data['doc']);
	    $this->db->group_by("c.cert_id");
	    
	$cant=$this->db->get();
	return $cant->result();
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
    	$this->db->where("c.cert_estado",1);
    	$query = $this->db->get();
    	return $query->result();
    }


    function vertifica_cert2($data)
    {
    	$this->db->select("a.aseg_id");
    	$this->db->from("asegurado a");
    	$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
    	$this->db->join("certificado c","c.cert_id=ca.cert_id");
    	$this->db->where("plan_id in (select plan_id from certificado where cert_id=".$data['cert_id']." and c.cert_estado=1)");
    	$this->db->where("aseg_numDoc",$data['doc']);
    	$query = $this->db->get();
    	return $query->result();
    }

    function save_incidencia($data)
    {
    	$array = array(
			'idtipoincidencia' => 1,
			'cert_id' => $data['cert_id'],
			'descripcion' => $data['desc'],
    		'idusuario' => $data['idusuario']
			);
		$this->db->insert('incidencia',$array);
    }

    function get_ceancelado($data)
    {
    	$this->db->select("pl.nombre_plan, CONCAT(coalesce(co.cont_nom1,' '),' ',coalesce(co.cont_nom2,' ')) as nombre, co.cont_email");
    	$this->db->from("certificado c ");
    	$this->db->join("plan pl","c.plan_id=pl.idplan");
    	$this->db->join("contratante co","c.cont_id=co.cont_id");
    	$this->db->where("cert_id",$data['cert_id']);

    	$query = $this->db->get();
    	return $query->result();
    }

    
    function in_cancelado($data)
    {
    	$array = array(
    		'cert_id' => $data['cert_id'],
    		'can_cert_num' => $data['cert_num'],
    		'can_estadoCert' => 3,
    		'can_finVig' => $data['hoy'],
    		'plan_id' => $data['plan'],
    		'can_motivo' => $data['motivo'],
    		'can_conse' => 1,
    		'can_tipoReg' => 2,
    		'can_prodClie' => '001',
    		'idusuario' => $data['idusuario']
    		);
    	$this->db->insert('cancelado',$array);
    }

    function up_cancertificado($data)
    {
    	$array = array(
    		'cert_estado' =>3,
    		'cert_upProv' => 0
    		);
    	$this->db->where('cert_id',$data['cert_id']);
		return $this->db->update('certificado', $array);
    }

    function up_cancertificadoasegurado($data)
    {
    	$array = array(
    		'cert_estado' =>3
    		);
    	$this->db->where('cert_id',$data['cert_id']);
		return $this->db->update('certificado_asegurado', $array);
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

    function in_certificado($data)
    {
    	$array = array(
    		'cert_num' => '10001',
    		'plan_id' => $data['plan'],
    		'cert_estado' => 1,
    		'cert_iniVig' => $data['inicio'],
    		'cert_finVig' => $data['fin'],
    		'cli_id' => $data['canal'],
    		'cont_id' => $data['cont_id'],
    		'cert_upProv' => 1,
    		'idusuario_afi' => $data['idusuario_afi']
    		);
    	$this->db->insert('certificado',$array);
    }

}
?>