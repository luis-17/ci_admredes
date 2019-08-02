<?php
/**
*	@author 	Ing. Israel Barragan C.  Email: ibarragan at behstant dot com
*	@since 		07-Nov-2013
*	##########################################################################################
*	Comments:
*	This file is to show how to retrieve records from a database with PDO
*	The records are shown in a HTML table.
*
*	Requires:	
*	Connection.simple.php, get this file here: http://behstant.com/blog/?p=413
*   jQuery and Boostrap.
*
* 	LICENCE:
*	You can use this code to any of your projects as long as you mention where you
* 	downloaded it and the author which is me :) Happy Code.
*
* 	LICENCIA:
*	Puedes usar este código para tus proyectos, pero siempre tomando en cuenta que
* 	debes de poner de donde lo descargaste y el autor que soy yo :) Feliz Codificación.
*	##########################################################################################
*	@version
*	##########################################################################################
*	1.0	|	07-Nov-2013	|	Creation of new file to search a record.
*	##########################################################################################
*/
	require_once 'Connection.simple.php';
	$conn = dbConnect();
	$OK = true; // We use this to verify the status of the update.
	// If 'buscar' is in the array $_POST proceed to make the query.
	if (isset($_GET['dianostico_temp'])) {
		// Create the query	

		/*$data = $_GET['session_name()'];*/
		$dianostico_temp = $_GET['dianostico_temp'];

		if (isset($_GET['idsiniestro'])) {
			$idsiniestro = $_GET['idsiniestro'];
			}else{
			$idsiniestrodiagnostico = $_GET['idsiniestrodiagnostico'];	
			}
		
		//$sin_diagnosticoSec = $_GET['sin_diagnosticoSec'];
		//$sin_dosisSecundaria = $GET['sin_dosisSecundaria'];
		


		/*$idsiniestro = $_GET['idsiniestro'];*/
		$cadena_buscada = ":";
		//buscamos posicion de :
		$posicion_coincidencia = strpos($dianostico_temp, $cadena_buscada, 0);
		$resultado = substr($dianostico_temp, 4, ($posicion_coincidencia-4));

		//$sql = "SELECT * FROM medicamentos WHERE cod_enf2='$resultado'";
		$sql = "SELECT * FROM medicamento M inner join diagnostico_medicamento DM on DM.idmedicamento = M.idmedicamento inner join diagnostico D on D.iddiagnostico = DM.iddiagnostico WHERE codigo_cie='$resultado'";
		
		// we have to tell the PDO that we are going to send values to the query
		$stmt = $conn->prepare($sql);
		// Now we execute the query passing an array toe execute();
		$results = $stmt->execute(array($dianostico_temp));
		// Extract the values from $result
		$rows = $stmt->fetchAll();
		$error = $stmt->errorInfo();
		
		//echo $error[2];
	}
	// If there are no records.
	if(empty($rows)) {
		
		echo "<div class='alert alert-danger' role='alert'> <tr>";
			echo "<td colspan='4'>No existen medicinas para el diagnóstico elegido. Ingrese un diagnóstico válido de la lista desplegable o digite manualmente su diagnóstico y tratamiento como secundarios.</td>";
			
		echo "</tr></div>";

		if (isset($_GET['idsiniestro'])) {
			echo "<input type='hidden' name='idsiniestro' id='idsiniestro' value='$idsiniestro'>";
			}else{
			echo "<input type='hidden' name='idsiniestrodiagnostico' id='idsiniestrodiagnostico' value='$idsiniestrodiagnostico'>";	
			}

		
				
	}
	else {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' />";
		
			 echo "<div class='form-group col-md-6'>";
             echo "<div id='itemRows'>";
             echo "<table class='table'>";
             echo "<thead>";
             echo "<tr>";
             echo "<th style='width:15.5em; text-align:center;'>MEDICINA</th>";
             echo "<th style='width:13.5em; text-align:center;'>CANTIDAD</th>";
             echo "<th style='width:13.5em; text-align:center;'>DOSIS</th>";
             echo "</tr>";
             echo "</thead>";
        echo "<br>";        
		
		
		echo "<tr class='fila-base'>";
		echo "<td>";
		echo "<select name='add_qty' class='form-control' id='add_qty' style='width:15.5em;' >";
		echo '<option value="">Elegir</option>';

		foreach ($rows as $row) {
			
				
			/*echo "<option value=".$row['cod_med'].">".$row['nom_med']." / ".$row['presen_med']."</option>";*/

			echo "<option value=".$row['idmedicamento'].">".$row['nombre_med']." / ".$row['presentacion_med']."</option>";
							
		}
		echo "</select>";
		echo "</td>";
		//echo "</tr>";
		echo "<td>";
		echo "<input class='form-control' type='text' name='add_cant' id='cant' style='width:8em;' ><br><br>";
		echo"</td>";
		echo "<td>";
		echo "<input class='form-control' type='text' name='add_name' id='dosis1' style='width:20.5em;' ><br><br>";
		echo"</td>";		
		echo "<td>"; 
		echo"<input onclick='addRow(this.form);' type='button' value='Agregar' class='btn btn-success'  style='margin-bottom:15px;'/>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		echo "</div>";
		
		

		if (isset($_GET['idsiniestro'])) {
			echo "<input type='hidden' name='idsiniestro' id='idsiniestro' value='$idsiniestro'>";
			echo "<input type='hidden' name='dianostico_temp' id='dianostico_temp' value='$dianostico_temp'>";
			}else{
			echo "<input type='hidden' name='idsiniestrodiagnostico' id='idsiniestrodiagnostico' value='$idsiniestrodiagnostico'>";
			echo "<input type='hidden' name='dianostico_temp' id='dianostico_temp' value='$dianostico_temp'>";	
			}


		
		
		//echo "<input type='hidden' name='sin_dosisSecundaria' id='sin_dosisSecundaria' value='$sin_dosisSecundaria'>";
		
		
	}
	
?>