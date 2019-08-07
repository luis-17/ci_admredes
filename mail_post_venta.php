<?php 
$cnx=mysqli_connect('rdsinstancemysql.czvvckkesgis.us-east-2.rds.amazonaws.com:3306','rsaws2019','hcarsAcces2019$','new_redes_peru') or die("Database Error");

$sql="select idsiniestro, fecha_atencion, nombre_comercial_pr, nombre_comercial_cli, nombre_plan, aseg_numDoc, UPPER(coalesce(aseg_ape1, aseg_ape2)) as apellido, UPPER(coalesce(aseg_nom1, aseg_nom2)) as nombre, aseg_telf, aseg_email
      from siniestro s
      inner join asegurado a on s.idasegurado=a.aseg_id 
      inner join certificado c on c.cert_id=s.idcertificado
      inner join proveedor pr on pr.idproveedor=s.idproveedor
      inner join plan p on p.idplan=c.plan_id 
      inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
      where (TIMESTAMPDIFF(day,fecha_atencion,DATE_FORMAT(now(),'%Y-%m-%d')))=1 and fecha_atencion>'2019-04-01' 
      and idsiniestro not in (select idsiniestro from siniestro_encuesta) and estado_atencion='O' and estado_siniestro=1";

function fechaCastellano ($fecha) {
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia." ".$numeroDia." de ".$nombreMes;
  }

$atenciones = mysqli_query($cnx,$sql) or die(mysqli_error());

if($atenciones){
    while($row=mysqli_fetch_array($atenciones))
    {
      $correo = $row['aseg_email'];
      $nombre = $row['nombre'];
      $apellido = $row['apellido'];
      $fecha = $row['fecha_atencion'];
      $fecha = fechaCastellano($fecha);

      $to = $correo;
      $subject = $nombre.', TU OPINION NOS IIMPORTA';
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: Red Salud < post_venta@red-salud.com >\r\n";
      $tipo="'Century Gothic'";
      $message = '
      <html>
        <body style="font-size: 12px; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
          <div style="padding-top: 2%; text-align: left; padding-right: 15%; padding-left: 8%;">
            <img src="https://www.red-salud.com/mail/logo.png" width="20%" style="text-align: right;"></img>
          </div>
          <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;">
            <p>'.$nombre.' '.$apellido.', tu opini&oacute;n nos interesa</p>
          </div>
          <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #151c53;">
            <p>Cu&eacute;ntanos tu experiencia en  una breve encuesta sobre la atenci&oacute;n m&eacute;dica del d&iacute;a '.$fecha.' en '.$row['nombre_comercial_pr'].'.</p>
          </div>
          <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #151c53;">
            <p>Nos ayudar&aacute;s a brindarte un mejor servicio.</p>
            <br>
            <p><a style="font-family: CenturyGothic, AppleGothic, sans-serif;font-size: 13pt;font-weight: bold;padding: 9px;background-color: #b61414;color: #ffffff;text-decoration: none;border-radius: 5px;" href="https://www.red-salud.com/rsadmin/index.php/encuesta_mail/'.$row['idsiniestro'].'">Responder ahora</a></p>
          </div>
           <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;">
            <p>No responder a este correo, ya que es una cuenta s&oacute;lo de env&iacute;os.</p>
          </div>
        </body>
      </html>';
       
      mail($to, $subject, $message, $headers);
      echo $correo;
    }
}

?>