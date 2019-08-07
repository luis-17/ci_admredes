<?php
 include('connection.php');

if(!empty($_POST['dniAseg'])){
    
    $query = $db->query("SELECT CA.certase_id, C.cert_id, C.plan_id, P.nombre_plan from certificado_asegurado CA inner join asegurado A on A.aseg_id = CA.aseg_id inner join certificado C on C.cert_id = CA.cert_id inner join plan P on P.idplan = C.plan_id where A.aseg_numDoc = {$_POST['dniAseg']}");

        if(mysqli_num_rows($query) > 0) {
            echo '<option value="">------- Select -------</option>';
            while($row = mysqli_fetch_object($query)) {
                echo '<option value="'.$row->cert_id.'">'.$row->nombre_plan.'</option>';
            }
        } else {
            echo '<option value="">No Record</option>';
        }






/*    $data = array(); 
    $query = $db->query("SELECT CA.certase_id, C.cert_id, C.plan_id, P.nombre_plan from certificado_asegurado CA inner join asegurado A on A.aseg_id = CA.aseg_id inner join certificado C on C.cert_id = CA.cert_id inner join plan P on P.idplan = C.plan_id where A.aseg_numDoc = {$_POST['dniAseg']}");
    
    if($query->num_rows > 0){
        $userData = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['result'] = $userData;
    }else{
        $data['status'] = 'err';
        $data['result'] = '';
    }
    
    //returns data as JSON format
    echo json_encode($data);*/
}


?>