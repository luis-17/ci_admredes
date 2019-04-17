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
		 //$this->db->set('idespecialidad', $data['idespecialidad']);
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
		 //$this->db->set('idespecialidad', $data['idespecialidad']);
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

	function updateSiniestro_tria($data) { 		
		$this->db->set('idespecialidad', $data['idespecialidad']);
		$this->db->where('idsiniestro', $data['idsiniestro']); 
		$this->db->update('siniestro');
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
	
	function getVariable($id){

		$query = $this->db->query("select dp.idplandetalle, nombre_var, simbolo_detalle, valor_detalle, CONCAT('(',LEFT (GROUP_CONCAT(descripcion_prod),40), CASE WHEN CHAR_LENGTH(GROUP_CONCAT(descripcion_prod)) > 40 THEN '...)' ELSE ')' END ) AS detalle, COALESCE (liqdetalleid, 0) AS liqdetalleid, COALESCE (liqdetalle_aprovpago, 0) AS liqdetalle_aprovpago, COALESCE (liqdetalle_monto, 0.00) AS liqdetalle_monto,	COALESCE (liqdetalle_neto, 0.00) AS liqdetalle_neto,	COALESCE (ld.idproveedor, 0) AS idprov,	COALESCE (liqdetalle_numfact, '') AS liqdetalle_numfact, 	liquidacionTotal,	liquidacionTotal_neto,	COALESCE (l.liquidacionId, 0) AS liq_id 
			FROM siniestro s
			JOIN certificado c ON s.idcertificado = c.cert_id
			JOIN plan_detalle dp ON dp.idplan = c.plan_id
			JOIN variable_plan vp ON dp.idvariableplan = vp.idvariableplan
			LEFT JOIN producto_detalle pd ON dp.idplandetalle = pd.idplandetalle
			LEFT JOIN producto p ON p.idproducto = pd.idproducto
			LEFT JOIN liquidacion l ON l.idsiniestro = s.idsiniestro
			LEFT JOIN liquidacion_detalle ld ON l.liquidacionId = ld.liquidacionId
			AND ld.idplandetalle = dp.idplandetalle
			WHERE
			s.idsiniestro = $id
			AND valor_detalle IS NOT NULL
			AND simbolo_detalle IS NOT NULL
			AND flg_liquidacion = 'S'
			GROUP BY pd.idplandetalle

			UNION 

			SELECT 0 as idplandetalle,'Gastos Aprobados'as nombre_var, 1 as simbolo_detalle, 100 as valor_detalle, '' AS detalle, COALESCE (liqdetalleid, 0) AS liqdetalleid,	COALESCE (liqdetalle_aprovpago, 0) AS liqdetalle_aprovpago,	COALESCE (liqdetalle_monto, 0.00) AS liqdetalle_monto,	COALESCE (liqdetalle_neto, 0.00) AS liqdetalle_neto,	COALESCE (ld.idproveedor, 0) AS idprov,	COALESCE (liqdetalle_numfact, '') AS liqdetalle_numfact,	liquidacionTotal,	liquidacionTotal_neto,	COALESCE (l.liquidacionId, 0) AS liq_id
			FROM siniestro s
			JOIN certificado c ON s.idcertificado = c.cert_id
			LEFT JOIN liquidacion l ON l.idsiniestro = s.idsiniestro
			LEFT JOIN liquidacion_detalle ld ON l.liquidacionId = ld.liquidacionId
			WHERE
			s.idsiniestro = $id  and (case when liqdetalleid is not null then ld.idplandetalle=0 else 1=1 end)"); 
 		return $query->result();
	}

	function save_liquidacion($data){
		$array = array(
			'idsiniestro' => $data['idsiniestro'],
			'liquidacionTotal' => $data['liq_total'], 
			'liquidacionTotal_neto' => $data['liq_neto'],
			'liquidacionFech_reg' => date('Y-m-d H:i:s'),
			'liquidacion_estado' => $data['estadoliq']
			);
		$this->db->insert("liquidacion",$array);
	}

	function up_liquidacion($data){
		$array = array(
			'liquidacionTotal' => $data['liq_total'], 
			'liquidacionTotal_neto' => $data['liq_neto'],
			'liquidacion_estado' => $data['estadoliq']
			);
		$this->db->where("liquidacionId",$data['liq_id']);
		$this->db->update("liquidacion",$array);
	}

	function save_detalleliquidacion($data){
		if($data['aprov_pago']==1){
			$idusu=$data['idusuario'];
		}else{
			$idusu=null;
		}

		$array = array(
			'liquidacionId' => $data['liq_id'], 
			'idplandetalle' => $data['idplandetalle'],
			'liqdetalle_monto' => $data['detalle_monto'],
			'idproveedor' => $data['idproveedor'],
			'liqdetalle_numfact' => $data['num_fac'],
			'liqdetalle_neto' => $data['detalle_neto'],
			'liqdetalle_aprovpago' => $data['aprov_pago'],
			'idusuario_aprueba' => $idusu,
			);
		$this->db->insert("liquidacion_detalle",$array);
	}

	function up_detalleliquidacion($data){
		if($data['aprov_pago']==1){
			$idusu=$data['idusuario'];
		}else{
			$idusu=null;
		}

		$array = array( 
			'liqdetalle_monto' => $data['detalle_monto'],
			'idproveedor' => $data['idproveedor'],
			'liqdetalle_numfact' => $data['num_fac'],
			'liqdetalle_neto' => $data['detalle_neto'],
			'liqdetalle_aprovpago' => $data['aprov_pago'],
			'idusuario_aprueba' => $idusu,
			);
		$this->db->where('liqdetalleid',$data['liqdetalleid']);
		return $this->db->update('liquidacion_detalle', $array);
	}

	function num_orden($id){
		$this->db->select("num_orden_atencion");
		$this->db->from("siniestro");
		$this->db->where("idsiniestro",$id);
		$query=$this->db->get();
		return $query->result();
	}

	function getVariables_sin($id){
		$this->db->distinct("vp.idvariableplan");
		$this->db->select("pd.idplandetalle, vp.idvariableplan, vp.nombre_var, coalesce(concat(vez_actual,'/'),'') as vez_actual2, vez_actual, coalesce(total_vez,'Ilimitados') as total_vez, c.cert_id, s.idsiniestro, pe.idperiodo");
		$this->db->from("siniestro s");
		$this->db->join("certificado c","s.idcertificado=c.cert_id");
		$this->db->join("plan_detalle pd","pd.idplan=c.plan_id");
		$this->db->join("variable_plan vp","vp.idvariableplan=pd.idvariableplan");
		$this->db->join("producto_detalle pr","pr.idplandetalle=pd.idplandetalle");
		$this->db->join("certificado_asegurado ca","ca.aseg_id=s.idasegurado and ca.cert_id=s.idsiniestro");
		$this->db->join("periodo_evento pe","pe.certase_id=ca.certase_id");
		$this->db->where("idsiniestro=$id and vp.idvariableplan <> 1");

		$query = $this->db->get();
		return $query->result();
	}

	function getProductos_analisis($id, $ids){
		$this->db->distinct("pr.idproducto");
		$this->db->select("pr.idproducto, descripcion_prod, case when sa.idproducto is null then '' else 'checked' end as checked");
		$this->db->from("producto pr");
		$this->db->join("producto_detalle pd","pr.idproducto=pd.idproducto");
		$this->db->join("plan_detalle dp","dp.idplandetalle=pd.idplandetalle");
		$this->db->join("siniestro_analisis sa","sa.idproducto=pr.idproducto and estado_sian<>0 and sa.idsiniestro=".$ids,"left");
		$this->db->where("dp.idplandetalle=$id");

		$query = $this->db->get();
		return $query->result();
	}

	function getAnalisisNo($ids,$idv){
		$this->db->select("coalesce(idsiniestroanalisis,0) as idsiniestroanalisis, analisis_str");
		$this->db->from("siniestro_analisis sa");
		$this->db->join("plan_detalle pd","sa.idplandetalle=pd.idplandetalle and sa.idplandetalle=$idv","left");
		$this->db->where("idsiniestro=$ids and idproducto is null and estado_sian<>0");
		$this->db->limit(1);

		$query = $this->db->get();
		return $query->result();
	}

	function validarProd($data){
		$this->db->select("*");
		$this->db->from("siniestro_analisis");
		$this->db->where("idsiniestro", $data['sin_id']);
		$this->db->where("idproducto", $data['idpr']);

		$query = $this->db->get();
		return $query->result();
	}

	function insertar_analisis($data){
		$array = array(
			'idsiniestro' => $data['sin_id'],
			'idproducto' => $data['idpr'],
			'idplandetalle' => $data['idplandetalle'],
			'si_cubre' => 1,
			'estado_sian' => 3
		);

		$this->db->insert("siniestro_analisis",$array);
	}

	function activar_analisis($data){
		$array = array(
			'estado_sian' => 3
		);
		$this->db->where("idsiniestro",$data['sin_id']);
		$this->db->where("idproducto",$data['idpr']);
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->update("siniestro_analisis",$array);
	}

	function eliminar_analisis($data){
		$array = array('estado_sian' => 0 );
		$this->db->where("idsiniestro",$data['sin_id']);
		$this->db->where("estado_sian<>3");
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->update("siniestro_analisis",$array);
	}

	function actualizar_analisis($data){
		$array = array('estado_sian' => 2 );
		$this->db->where("idsiniestro",$data['sin_id']);
		$this->db->where("estado_sian",3);
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->update("siniestro_analisis",$array);
	}

	function insertar_NC($data){
		$array = array
		(
			'idsiniestro' => $data['sin_id'],
			'analisis_str' => $data['servicio'],
			'idplandetalle' => $data['idplandetalle'],
			'si_cubre' => 3,
			'estado_sian' => 2
		);
		$this->db->insert("siniestro_analisis",$array);
	}

	function update_NC($data){
		$array = array
		(
			'analisis_str' => $data['servicio'],
			'si_cubre' => 3,
			'estado_sian' => 2
		);
		$this->db->where("idsiniestro",$data['sin_id']);
		$this->db->where("idsiniestroanalisis",$data['idnc']);
		$this->db->update("siniestro_analisis",$array);
	}

	function eliminar_todo($data){
		$array = array('estado_sian' => 0 );
		$this->db->where("idsiniestro",$data['sin_id']);
		$this->db->where("idproducto is not null");
		$this->db->where("idplandetalle",$data['idplandetalle']);		
		$this->db->update("siniestro_analisis",$array);
	}

	function eliminar_servicio($data){
		$array = array('estado_sian' => 0 );
		$this->db->where("idsiniestroanalisis",$data['idnc']);		
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->update("siniestro_analisis",$array);
	}

	function get_medicamentos($id){
		$this->db->select("*");
		$this->db->from("tratamiento");
		$this->db->where("idsiniestrodiagnostico",$id);

		$query = $this->db->get();
		return $query->result();
	}

	function eliminar_diagnostico($id){
		$this->db->where("idsiniestrodiagnostico",$id);
		$this->db->delete("siniestro_diagnostico");
	}

	function getCert($data){
		$this->db->select("c.cert_id, (vez_actual)+1 as vez_actual, cant, cant_tot, ca.certase_id");
		$this->db->from("certificado c");
		$this->db->join("siniestro s","c.cert_id=s.idcertificado");
		$this->db->join("(select count(idsiniestro) as cant, idsiniestro from siniestro_analisis where idsiniestro=".$data['sin_id']." and estado_sian in (1,2))a","s.idsiniestro=a.idsiniestro","left");
		$this->db->join("(select count(idsiniestro) as cant_tot, idsiniestro from siniestro_analisis where idsiniestro=".$data['sin_id'].")b","s.idsiniestro=b.idsiniestro","left");
		$this->db->join("certificado_asegurado ca","ca.aseg_id=s.idasegurado and ca.cert_id=s.idsiniestro");
		$this->db->join("periodo_evento pe","pe.certase_id=ca.certase_id");
		$this->db->where("s.idsiniestro",$data['sin_id']);		
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$query =  $this->db->get();
		return $query->result();
	}

	function up_periodo_evento($data){
		$array = array('vez_actual' => $data['vez_actual'] );
		$this->db->where('idplandetalle',$data['idplandetalle']);
		$this->db->where('certase_id',$data['certase_id']);
		$this->db->update('periodo_evento',$array);
	}

}
?>