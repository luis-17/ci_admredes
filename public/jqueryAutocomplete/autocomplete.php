<?php
	$q=$_GET['q'];
	
	//$mysqli=mysqli_connect('localhost','root','','doctorweb') or die("Database Error");
	$mysqli=mysqli_connect('50.62.209.11:3306','dev_user_rs','redsaludRS@2018','db_devrs') or die("Database Error");
	//$my_data=mysql_real_escape_string($q);
	$sql="SELECT descripcion_cie, codigo_cie FROM diagnostico WHERE descripcion_cie LIKE '%$q%' ORDER BY descripcion_cie";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			//echo "CIE ".$row['cod_enf2']." : ".$row['nom_enf2']."\n";
			echo "CIE ".$row['codigo_cie']." : ".utf8_encode($row['descripcion_cie'])."\n";
		}
	}
?>