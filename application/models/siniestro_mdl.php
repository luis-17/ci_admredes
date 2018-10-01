<?php
 class Siniestro_mdl extends CI_Model {

 function Siniestro_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }
	
	function getInfoSiniestro($id){
		$this->db->select("C.plan_id, C.cert_num, P.idproveedor, P.nombre_comercial_pr, S.idsiniestro, A.aseg_id, A.aseg_numDoc, CONCAT(COALESCE(aseg_nom1,''), ' ', COALESCE(aseg_nom2,''), ' ', COALESCE(aseg_ape1,''), ' ', COALESCE(aseg_ape2,'')) AS asegurado, S.num_orden_atencion, S.fecha_atencion, SD.dianostico_temp, T.medicamento_temp, T.cantidad_trat, T.dosis_trat, T.tipo_tratamiento, SA.analisis_str, SA.si_cubre");
		$this->db->from("siniestro S");
		$this->db->join("certificado C","C.cert_id=S.idcertificado");
		$this->db->join("asegurado A","A.aseg_id=S.idasegurado");
		$this->db->join("siniestro_diagnostico SD","SD.idsiniestro=S.idsiniestro");
		$this->db->join("tratamiento T","T.idsiniestrodiagnostico=SD.idsiniestrodiagnostico");
		$this->db->join("siniestro_analisis SA","SA.idsiniestro=S.idsiniestro");
		$this->db->join("proveedor P","P.idproveedor=S.idproveedor");
		$this->db->where("S.idsiniestro = $id");

	$infoSiniestro = $this->db->get();
	 return $infoSiniestro->result();
	}

	function getTriaje($id){
		$this->db->select("T.idtriaje, S.idasegurado, S.idsiniestro, S.idespecialidad, motivo_consulta, presion_arterial_mm, frec_cardiaca, frec_respiratoria, peso, talla, estado_cabeza, piel_faneras, cv_ruido_cardiaco, tp_murmullo_vesicular, estado_abdomen, ruido_hidroaereo, estado_neurologico, estado_osteomuscular, gu_puno_percusion_lumbar, gu_puntos_reno_uretelares");
		$this->db->from("triaje T");
		$this->db->join("siniestro S","S.idsiniestro=T.idsiniestro");		
		$this->db->where("T.idsiniestro = $id");

	$triaje = $this->db->get();
	 return $triaje->row_array();	

	}


	function getPlan($id){
		$this->db->select("C.plan_id");
		$this->db->from("siniestro S");
		$this->db->join("certificado C","C.cert_id=S.idcertificado");		
		$this->db->where("S.idsiniestro = $id");
		$plan = $this->db->get();
		return $plan->row_array();	
	}


	function getDiagnostico($id){
		$this->db->select("idsiniestrodiagnostico, idsiniestro, tipo_diagnostico, es_principal, dianostico_temp");
		$this->db->from("siniestro_diagnostico");
		$this->db->where("idsiniestro = $id");

	$diagnostico = $this->db->get();
	 return $diagnostico->result();
	}

	function getSiniestroDiagnostico($idsiniestrodiagnostico){
		$this->db->select("idsiniestrodiagnostico, idsiniestro, tipo_diagnostico, es_principal, dianostico_temp");
		$this->db->from("siniestro_diagnostico");
		$this->db->where("idsiniestrodiagnostico = $idsiniestrodiagnostico");

	$diagnostico = $this->db->get();
	 return $diagnostico->row_array();
	}


	function getMedicamento($id){
		$this->db->select("idtratamiento, idsiniestrodiagnostico, cantidad_trat, dosis_trat, medicamento_temp, tipo_tratamiento");
		$this->db->from("tratamiento");
		$this->db->where("idsiniestrodiagnostico in (select idsiniestrodiagnostico from siniestro_diagnostico where idsiniestro =  $id)");

	$medicamento = $this->db->get();
	 return $medicamento->result();	

	}


	function getTratamiento($idtratamiento){
		$this->db->select("idtratamiento, idsiniestrodiagnostico, idmedicamento, cantidad_trat, dosis_trat, medicamento_temp, tipo_tratamiento");
		$this->db->from("tratamiento");
		$this->db->where("idtratamiento =  $idtratamiento");

	$tratamiento = $this->db->get();
	 return $tratamiento->row_array();	

	}


	function getLaboratorio($id){
		$this->db->select("idsiniestroanalisis, idsiniestro, idproducto, analisis_str, si_cubre");
		$this->db->from("siniestro_analisis");
		$this->db->where("idsiniestro=$id");

	$laboratorio = $this->db->get();
	 return $laboratorio->result();	

	}


	function getEspecialidad() {
        $data = array();
        $query = $this->db->get('especialidad');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row){
                    $data[] = $row;
                }
        }
        $query->free_result();
        return $data;
    }

    function getHistoria($idasegurado)
    {       
        $query = $this->db->query('select idhistoria from historia where idasegurado ='.$idasegurado.' order by idhistoria asc limit 1');			
        
        return $query->row_array();        
    }


    function detalle_plan($id)
    {       
        /*$query = $this->db->query('select idhistoria from historia where idasegurado ='.$idasegurado.' order by idhistoria asc limit 1');*/

        $query = $this->db->query("select P.nombre_plan, PD.idvariableplan, VP.nombre_var, PD.valor_detalle, PD.texto_web from plan_detalle PD
        inner join plan P on P.idplan = PD.idplan
        inner join variable_plan VP on VP.idvariableplan = PD.idvariableplan
        inner join certificado C on C.plan_id = P.idplan
        inner join siniestro S on S.idcertificado = C.cert_id
        where S.idsiniestro =".$id." and PD.valor_detalle is not null");			
        
        return $query->row_array();        
    }


    function getProducto($idespecialidad)
    {       
        $query = $this->db->query('select idproducto from producto where idespecialidad='.$idespecialidad);			
        
        return $query->row_array();        
    }

    function getIdAseguradoOnSiniestro($idsiniestro)
    {       
        $query = $this->db->query('select idasegurado from siniestro where idsiniestro='.$idsiniestro);			
        
        return $query->row_array();        
    }


    function getMediforDiag($resultado){
    	
		$this->db->select("M.idmedicamento, M.nombre_med, M.presentacion_med, M.estado_med, DM.idmedicamento, DM.iddiagnostico, D.iddiagnostico, D.codigo_cie, D.descripcion_cie");
		$this->db->from("medicamento M");
		$this->db->join("diagnostico_medicamento DM","DM.idmedicamento=M.idmedicamento");
		$this->db->join("diagnostico D","D.iddiagnostico=DM.iddiagnostico");
		$this->db->where("codigo_cie", $resultado);

	$medicamento = $this->db->get();
	 return $medicamento->result_array();	
	}


	function getNumOA(){
    $query = $this->db->query('SELECT * FROM siniestro ORDER BY num_orden_atencion DESC LIMIT 1');
        return $query->row_array();
	}


	function guardaSiniestro($data) {
	 //$factual=date("Y-m-d H:i:s");
	 $this->db->set('idasegurado', $data['idasegurado']); 
	 $this->db->set('idcertificado', $data['idcertificado']);
	 $this->db->set('idproveedor', $data['idproveedor']);
	 $this->db->set('idespecialidad', $data['idespecialidad']);
	 $this->db->set('fecha_atencion', $data['fecha_atencion']);
	 $this->db->set('idhistoria', $data['idhistoria']);
	 $this->db->set('idproducto', $data['idproducto']);
	 $this->db->set('num_orden_atencion', $data['num_orden_atencion']);
	 $this->db->set('fase_atencion', $data['fase_atencion']);
	 $this->db->set('estado_siniestro', $data['estado_siniestro']);
	 $this->db->set('sin_labFlag', $data['sin_labFlag']);
	 $this->db->set('estado_atencion', $data['estado_atencion']);
	 $this->db->set('idareahospitalaria', $data['idareahospitalaria']);
	 $this->db->insert('siniestro');
	 }


	 function guardaLiquidacion($data) {
	 //$factual=date("Y-m-d H:i:s");

	 	$idsiniestro = $data['idsiniestro'];
	 	$this->db->where('idsiniestro', $idsiniestro);
		$q = $this->db->get('liquidacion');
		//$this->db->reset_query();

		if ($q->num_rows()>0){

			$query = $this->db->query('SELECT liquidacionId FROM liquidacion where idsiniestro ='.$idsiniestro. ' LIMIT 1');
			$row = $query->row_array();
			$liquidacionId = $row['liquidacionId'];

			$this->db->set('liquidacionTotal', $data['total_neto']);
			$this->db->where('liquidacionId', $liquidacionId);
			$this->db->update('liquidacion');


			for($num = 1; $num<=4; $num++)
			{ 
				$query2 = $this->db->query('SELECT liqdetalleid FROM liquidacion_detalle where (liquidacionId ='.$liquidacionId.') and (liqdetalle_concepto ='.$num.') LIMIT 1');
				$row2 = $query2->row_array();
				//$liquidacionId = $row2['liquidacionId'];

				if ($data['presscheck']==0){
					$proveedorData = $data['proveedorPrin'];
					$numFactData = $data['numFact'];
					$pagoData = $data['pago0'];

				}else {
					$proveedorData = $data['proveedor'.$num];
					$numFactData = $data['factura'.$num];
					$pagoData = $data['pago'.$num];

				}


				if (isset($row2['liqdetalleid'])){	
					$liqdetalleid = $row2['liqdetalleid'];

				   	$this->db->set('liqdetalle_monto', $data['neto'.$num]);
				   	$this->db->set('idproveedor', $proveedorData);
				   	$this->db->set('liqdetalle_numfact', $numFactData);
				   	$this->db->set('liqdetalle_aprovpago', $pagoData);
				   	$this->db->where('liqdetalleid', $liqdetalleid);
					$this->db->update('liquidacion_detalle');	
				}else{
					if (isset($data['neto'.$num]) and !empty($data['neto'.$num]) ){
						$this->db->set('liquidacionId', $liquidacionId);
						$this->db->set('liqdetalle_concepto', $num);
						$this->db->set('liqdetalle_monto', $data['neto'.$num]);
					   	$this->db->set('idproveedor', $proveedorData);
					   	$this->db->set('liqdetalle_numfact', $numFactData);
					   	$this->db->set('liqdetalle_aprovpago', $pagoData);				   	
		 				$this->db->insert('liquidacion_detalle');
	 				};
				};
			};

		}else{

			$this->db->set('liquidacionTotal', $data['total_neto']);
			$this->db->set('idsiniestro', $idsiniestro);
			$this->db->insert('liquidacion');
			$insert = $this->db->insert_id();

			for($num2 = 1; $num2<=4; $num2++)
			{
				if (isset($data['neto'.$num2]) and !empty($data['neto'.$num2]) ){
					$this->db->set('liquidacionId', $insert);
					$this->db->set('liqdetalle_concepto', $num2);
					$this->db->set('liqdetalle_monto', $data['neto'.$num2]);
					$this->db->set('idproveedor', $proveedorData);
					$this->db->set('liqdetalle_numfact', $numFactData);
					$this->db->set('liqdetalle_aprovpago', $pagoData);				   	
		 			$this->db->insert('liquidacion_detalle');
		 		};
			};

		 };
 	 }


 	 function guardaLiquidacion2($data) {
 	 	

 	 	$idsiniestro = $data['idsiniestro'];
	 	$this->db->where('idsiniestro', $idsiniestro);
		$q = $this->db->get('liquidacion');
		//$this->db->reset_query();


		if ($q->num_rows()>0){

			$query = $this->db->query('SELECT liquidacionId FROM liquidacion where idsiniestro ='.$idsiniestro. ' LIMIT 1');
			$row = $query->row_array();
			$liquidacionId = $row['liquidacionId'];

			$this->db->set('liquidacionTotal', $data['total']);
			$this->db->where('liquidacionId', $liquidacionId);
			$this->db->update('liquidacion');


			for($num = 1; $num<=4; $num++)
			{ 
				$query2 = $this->db->query('SELECT liqdetalleid FROM liquidacion_detalle where (liquidacionId ='.$liquidacionId.') and (liqdetalle_concepto ='.$num.') LIMIT 1');
				$row2 = $query2->row_array();
				//$liquidacionId = $row2['liquidacionId'];

				if (isset($row2['liqdetalleid'])){	
					$liqdetalleid = $row2['liqdetalleid'];

				   	$this->db->set('liqdetalle_monto', $data['monto'.$num]);
				   	$this->db->set('idproveedor', $data['proveedor'.$num]);
				   	$this->db->set('liqdetalle_numfact', $data['factura'.$num]);
				   	$this->db->set('liqdetalle_aprovpago', $data['pago'.$num]);
				   	$this->db->where('liqdetalleid', $liqdetalleid);
					$this->db->update('liquidacion_detalle');	
				}else{
					if (isset($data['monto'.$num]) and !empty($data['monto'.$num]) ){
						$this->db->set('liquidacionId', $liquidacionId);
						$this->db->set('liqdetalle_concepto', $num);
						$this->db->set('liqdetalle_monto', $data['monto'.$num]);
					   	$this->db->set('idproveedor', $data['proveedor'.$num]);
					   	$this->db->set('liqdetalle_numfact', $data['factura'.$num]);
					   	$this->db->set('liqdetalle_aprovpago', $data['pago'.$num]);				   	
		 				$this->db->insert('liquidacion_detalle');
	 				};
				};
			};

		}else{

			$this->db->set('liquidacionTotal', $data['total']);
			$this->db->set('idsiniestro', $idsiniestro);
			$this->db->insert('liquidacion');
			$insert = $this->db->insert_id();

			for($num2 = 1; $num2<=4; $num2++)
			{
				if (isset($data['monto'.$num2]) and !empty($data['monto'.$num2]) ){
					$this->db->set('liquidacionId', $insert);
					$this->db->set('liqdetalle_concepto', $num2);
					$this->db->set('liqdetalle_monto', $data['monto'.$num2]);
					$this->db->set('idproveedor', $data['proveedor'.$num2]);
					$this->db->set('liqdetalle_numfact', $data['factura'.$num2]);
					$this->db->set('liqdetalle_aprovpago', $data['pago'.$num2]);				   	
		 			$this->db->insert('liquidacion_detalle');
		 		};
			};
		 };
 	 }


	function guardaHistoria($idasegurado) {
	 //$factual=date("Y-m-d H:i:s");
	 $this->db->set('idasegurado', $idasegurado); 
	 $this->db->insert('historia');
	 }



    function guardaTriaje($data) {
		 
		 $this->db->set('idasegurado', $data['idasegurado']);
		 $this->db->set('idsiniestro', $data['idsiniestro']);
		 $this->db->set('motivo_consulta', $data['motivo_consulta']);

		 $this->db->set('presion_arterial_mm', $data['presion_arterial_mm']);
		 $this->db->set('frec_cardiaca', $data['frec_cardiaca']);
		 $this->db->set('frec_respiratoria', $data['frec_respiratoria']);
		 $this->db->set('peso', $data['peso']);
		 $this->db->set('talla', $data['talla']);
		 $this->db->set('estado_cabeza', $data['estado_cabeza']);
		 $this->db->set('piel_faneras', $data['piel_faneras']);
		 $this->db->set('cv_ruido_cardiaco', $data['cv_ruido_cardiaco']);
		 $this->db->set('tp_murmullo_vesicular', $data['tp_murmullo_vesicular']);
		 $this->db->set('estado_abdomen', $data['estado_abdomen']);
		 $this->db->set('ruido_hidroaereo', $data['ruido_hidroaereo']);
		 $this->db->set('estado_neurologico', $data['estado_neurologico']);
		 $this->db->set('estado_osteomuscular', $data['estado_osteomuscular']);
		 $this->db->set('gu_puno_percusion_lumbar', $data['gu_puno_percusion_lumbar']);
		 $this->db->set('gu_puntos_reno_uretelares', $data['gu_puntos_reno_uretelares']);		 

		 $this->db->insert('triaje');
	}


	function updateTriaje($data) {
		 
		 $this->db->set('idasegurado', $data['idasegurado']);
		 $this->db->set('idsiniestro', $data['idsiniestro']);
		 $this->db->set('motivo_consulta', $data['motivo_consulta']);

		 $this->db->set('presion_arterial_mm', $data['presion_arterial_mm']);
		 $this->db->set('frec_cardiaca', $data['frec_cardiaca']);
		 $this->db->set('frec_respiratoria', $data['frec_respiratoria']);
		 $this->db->set('peso', $data['peso']);
		 $this->db->set('talla', $data['talla']);
		 $this->db->set('estado_cabeza', $data['estado_cabeza']);
		 $this->db->set('piel_faneras', $data['piel_faneras']);
		 $this->db->set('cv_ruido_cardiaco', $data['cv_ruido_cardiaco']);
		 $this->db->set('tp_murmullo_vesicular', $data['tp_murmullo_vesicular']);
		 $this->db->set('estado_abdomen', $data['estado_abdomen']);
		 $this->db->set('ruido_hidroaereo', $data['ruido_hidroaereo']);
		 $this->db->set('estado_neurologico', $data['estado_neurologico']);
		 $this->db->set('estado_osteomuscular', $data['estado_osteomuscular']);
		 $this->db->set('gu_puno_percusion_lumbar', $data['gu_puno_percusion_lumbar']);
		 $this->db->set('gu_puntos_reno_uretelares', $data['gu_puntos_reno_uretelares']);		 

		 $this->db->where('idtriaje', $data['idtriaje']);
 		 $this->db->update('triaje');
	}



	function search($term){ 
      //usamos after para decir que empiece a buscar por
	 //el principio de la cadena
	 //ej SELECT localidad from localidades_es 
	 //WHERE localidad LIKE '%$abuscar' limit 12
	 $this->db->select('descripcion_cie');
	 
	 $this->db->like('descripcion_cie',$abuscar,'after');
	 
	 $resultados = $this->db->get('diagnostico');
	 
	 //si existe algún resultado lo devolvemos
	 if($resultados->num_rows() > 0)
	 {
	 
	 return $resultados->result();
	 
	 //en otro caso devolvemos false
	 }else{
	 
	 return FALSE;
	 
	 }
    } 



    function updateSiniestro_diag($data) { 		
		$this->db->set('fase_atencion', $data['sin_estado']);
		$this->db->where('idsiniestro', $data['idsiniestro']); 
		$this->db->update('siniestro');
	}


	function guardaDiagnosticoSin($data) {
	 $factual=date("Y-m-d H:i:s");
	 $this->db->set('idsiniestro', $data['idsiniestro']);
	 $this->db->set('dianostico_temp', $data['dianostico_temp']);
	 $this->db->set('tipo_diagnostico', $data['diag_tipo']);
	 $this->db->set('es_principal', $data['es_principal']);
	 $this->db->set('createdat', $factual);
	 //$this->db->set('updatedat', $factual);

	 $this->db->insert('siniestro_diagnostico');

	 //return $last_id;
	}


	function guardaTratamiento($data) {
	 $factual=date("Y-m-d H:i:s");
	 $this->db->set('idmedicamento', $data['idMedi']);
	 $this->db->set('idsiniestrodiagnostico', $data['diag_id']);
	 $this->db->set('medicamento_temp', $data['trat_medi']);
	 $this->db->set('cantidad_trat', $data['trat_cant']);
	 $this->db->set('dosis_trat', $data['trat_dosis']);
	 $this->db->set('tipo_tratamiento', $data['trat_tipo']); 
	 $this->db->set('createdat', $factual);
	 //$this->db->set('updatedat', $factual);
	 $this->db->insert('tratamiento');
	}


	function updateTratamiento($data) { 		
		$this->db->set('idmedicamento', $data['idmedi']);
		$this->db->set('cantidad_trat', $data['cant']); 
		$this->db->set('dosis_trat', $data['dosis']);
		$this->db->set('medicamento_temp', $data['medicamento_temp']); 
		$this->db->set('tipo_tratamiento', $data['tipo']);		
		$this->db->where('idtratamiento', $data['idtratamiento']);
		$this->db->update('tratamiento');
	}


	function deleteTratamiento($data) { 		
		$this->db->where('idtratamiento', $data['idtratamiento']);
		$this->db->delete('tratamiento'); 
	}
	
}
?>