<?php
 include('connection.php');

if(!empty($_POST['dniAseg'])){

    $data = array();    
    
    //get data from the database

    $query = $db->query("SELECT * FROM asegurado WHERE aseg_numDoc = {$_POST['dniAseg']}");
    
    if($query->num_rows > 0){
        $userData = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['result'] = $userData;
    }else{
        $data['status'] = 'err';
        $data['result'] = '';
    }
    
    //returns data as JSON format
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

if(!empty($_POST['idsiniestro'])){

    $query = $db->query("select P.nombre_plan, PD.idvariableplan, VP.nombre_var, PD.valor_detalle, PD.texto_web from plan_detalle PD
        inner join plan P on P.idplan = PD.idplan
        inner join variable_plan VP on VP.idvariableplan = PD.idvariableplan
        inner join certificado C on C.plan_id = P.idplan
        inner join siniestro S on S.idcertificado = C.cert_id
        where S.idsiniestro =".$_POST['idsiniestro']." and PD.valor_detalle is not null");

    $someArray = [];

  // Loop through query and push results into $someArray;
  while ($row = mysqli_fetch_assoc($query)) {
    array_push($someArray, [

      'nombre_plan' => $row['nombre_plan'],
      'idvariableplan' => $row['idvariableplan'],
      'nombre_var' => $row['nombre_var'],
      'valor_detalle' => $row['valor_detalle'],
      'texto_web' => $row['texto_web']
    ]);
  }

  // Convert the Array to a JSON String and echo it
  $someJSON = json_encode($someArray);
  echo $someJSON;


}


?>