<?php 
$cnx=mysqli_connect('localhost','redperu_admin','du2las0de1r8@peru','new_redes_admin') or die("Database Error");

$sql="select idsiniestro, date_format(fecha_atencion,'%d/%m/%Y') as fecha_atencion, nombre_comercial_pr, nombre_comercial_cli, nombre_plan, aseg_numDoc, UPPER(coalesce(aseg_ape1, aseg_ape2)) as apellido, UPPER(coalesce(aseg_nom1, aseg_nom2)) as nombre, aseg_telf, aseg_email
      from siniestro s
      inner join asegurado a on s.idasegurado=a.aseg_id 
      inner join certificado c on c.cert_id=s.idcertificado
      inner join proveedor pr on pr.idproveedor=s.idproveedor
      inner join plan p on p.idplan=c.plan_id 
      inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
      where (TIMESTAMPDIFF(day,fecha_atencion,DATE_FORMAT(now(),'%Y-%m-%d')))=1 and fecha_atencion>'2019-04-01' 
      and idsiniestro not in (select idsiniestro from siniestro_encuesta) and estado_atencion='O'";

$atenciones = mysqli_query($cnx,$sql) or die(mysqli_error());

if($atenciones){
    while($row=mysqli_fetch_array($certificados))
    {
      $correo = $row['aseg_email'];
      $nombre = $row['nombre'];
      $apellido = $row['apellido'];

      $to = $correo;
      $subject = $nombre.', TU OPINION NOS IIMPORTA';
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: Red Salud < post_venta@red-salud.com >\r\n";
      $tipo="'Century Gothic'";
      $message = '
      <html>
        <body style="font-size: 12px; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
          <div style="padding-top: 2%; text-align: right; padding-right: 15%;">
            <img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
          </div>
          <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;">
            <p>'.$nombre.' '.$apellido.', tu opini&oacute;n nos interesa</p>
            <p>Cu&eacute;ntanos tu experiencia en una breve encuesta</p>
          </div>
          <div  style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
           <ul>
              <li><b>Plan:</b> '.$row['nombre_plan'].'</li>
              <li><b>Cl&iacute;nica:</b> '.$row['nombre_comercial_pr'].'</li>
              <li><b>Especialidad:</b> </li>
              <li><b>Fecha:</b> '.$row['fecha_atencion'].'</li>
           </ul>          
          </div>
                  <br>
                  <br>
                  <br>
          <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
            <div style="text-align: center;"><b>
              <a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b>
            </div>
            <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b>
            </div>
          </div>
          <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
          </div>
        </body>
      </html>';
       
      mail($to, $subject, $message, $headers);
      echo "test";
    }
}

?>