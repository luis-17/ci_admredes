<?php
if (isset($questionChk)){

	echo "Proveedor = ".$proveedorPrin."<br>";
	echo "Factura = ".$numFact."<br>";	
	echo "Pago Proveedor = ".$pago0."<br>";	
	echo "Monto1 = ".$monto1."<br>";
	echo "Monto2 = ".$monto2."<br>";
	echo "Monto3 = ".$monto3."<br>";
	echo "Monto4 = ".$monto4."<br>";

}else{

//echo "chk = " .$questionChk."<br>";
echo "Monto1 = ".$monto1."<br>";
echo "Proveedor1 = ".$proveedor1."<br>";
echo "Fact1 = ".$factura1."<br>";
echo "PagoProv1 = ".$pago1."<br>";

echo "Monto2 = ".$monto2."<br>";
echo "Proveedor2 = ".$proveedor2."<br>";
echo "Fact2 = ".$factura2."<br>";
echo "PagoProv2 = ".$pago2."<br>";

echo "Monto3 = ".$monto3."<br>";
echo "Proveedor3 = ".$proveedor3."<br>";
echo "Fact3 = ".$factura3."<br>";
echo "PagoProv3 = ".$pago3."<br>";

echo "Monto4 = ".$monto4."<br>";
echo "Proveedor4 = ".$proveedor4."<br>";
echo "Fact4 = ".$factura4."<br>";
echo "PagoProv4 = ".$pago4."<br>";

}
echo "Siniestro = ".$idsiniestro."<br>";

echo "tot="; echo $monto1+$monto2+$monto3+$monto4;

?>