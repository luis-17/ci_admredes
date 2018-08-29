<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Agregar Diagnostico</title>

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://getbootstrap.com/docs/3.3/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/3.3/examples/jumbotron/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- script para el autocomplete de diagnostico -->

    <script type="text/javascript" src="../public/jqueryAutocomplete/jquery.js"></script>

	<link rel="stylesheet" type="text/css" href="../public/jqueryAutocomplete/jquery.autocomplete.css" />
	<script type="text/javascript" src="../public/jqueryAutocomplete/jquery.js"></script>
	<script type="text/javascript" src="../public/jqueryAutocomplete/jquery.autocomplete.js"></script>
	<script>
		jQuery.noConflict(); 
		var j = jQuery.noConflict();
	 j(document).ready(function(){
	  j("#sin_diagnostico").autocomplete("../public/jqueryAutocomplete/autocomplete.php", {
	        selectFirst: true
	  });
	  j("#sin_diagnosticoSec").autocomplete("../public/jqueryAutocomplete/autocomplete.php", {
	        selectFirst: true
	  });
	 });
	</script>


<!--Script para agregar medicinas -->




<script type="text/javascript">
	var rowNumNC = 0;
	var countRowNC = 0;
	function addRowNC(frm) {
		if(countRowNC <= 15) //maximo de inputs permitidos						
		{
			countRowNC ++;
			rowNumNC ++;
			var valor1 = document.getElementById("mediNC").value;
			var valor2 = document.getElementById("cantNC").value;
			var valor3 = document.getElementById("dosisNC").value;
			var row = '<p id="rowNumNC'+rowNumNC+'"><div class="form-group col-md-6"><table class="table"><tr class="fila-base"><td style="width:17.5em;"><input class="form-control" name="mediNC'+rowNumNC+'" value="'+valor1+'" readonly></td> <td style="width:10.5em;"><input class="form-control" name="cantNC'+rowNumNC+'" value="'+frm.cantNC.value+'" readonly></td> <td style="width:13.5em;"><input class="form-control" name="dosisNC'+rowNumNC+'" value="'+frm.dosisNC.value+'" readonly></td><td><input type="button" class="btn btn-danger" value="Eliminar" onclick="removeRowNC('+rowNumNC+');"></td></tr></table></div></p>';
			jQuery('#itemRows2').append(row);
			frm.mediNC.value = '';
			frm.cantNC.value = '';
			frm.dosisNC.value = '';
		}else{alert("Solo puede agregar 4 campos");}
	}

	function removeRowNC(rnum) {
		jQuery('#rowNumNC'+rnum).remove();
		countRowNC --;
	}
</script>




	<script type="text/javascript"> 
	  function tabE(obj,e){ 
	   var e=(typeof event!='undefined')?window.event:e;// IE : Moz 
	   if(e.keyCode==13){ 
	     var ele = document.forms[0].elements; 
	     for(var i=0;i<ele.length;i++){ 
	       var q=(i==ele.length-1)?0:i+1;// if last element : if any other 
	       if(obj==ele[i]){ele[q].focus();break} 
	     } 
	  return false; 
	   } 
	  } 
	</script> 

  </head>

  <body>

    <nav class="navbar navbar-fixed-top" style="background-color:#438eb9 !important; border-width: 0 0 0 px !important;">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="color:white; " href="#">Agregar Diagnóstico</a>
        </div>
        
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">        
		<form id="form2" name="form2" role="form" action="<?=base_url()?>index.php/guardaDiagS" method="post">	
			
			<div id="grupo5">
							
				<div class="form-group col-md-6">						        
					<h4 style="color:#033766;">Diagnóstico Secundario</h4>
					<input class='form-control' name="sin_diagnosticoSec" id="sin_diagnosticoSec"/><br>
					<input  type="hidden" name="idsiniestro" id="idsiniestro" value="<?php echo $idsiniestro;?>">
					
					<h4 style="color:#033766;">Tratamiento Secundario</h4>
					<div id="messageAtention">
						<h5>Todo tratamiento indicado como tratamiento secundario y que no se encuentre en la lista desplegable de medicamentos, deberán ser asumidas por el paciente.</h5>
					</div>


					<div id='itemRows2'>
					    <table class='table'>
					        <thead>
					        <tr>
						        <th style='width:15.5em; text-align:center;'>MEDICINA</th>
						        <th style='width:13.5em; text-align:center;'>CANTIDAD</th>
						        <th style='width:13.5em; text-align:center;'>DOSIS</th>
					        </tr>
					        </thead>
						    <br>        
								
								
							<tr class='fila-base'>
							<td>
								<input type='text' class='form-control' name='mediNC' id='mediNC' style='width:15.5em;' ><br><br>
							</td>
								
							<td>
								<input type='text' class='form-control' name='cantNC' id='cantNC' style='width:8em;' ><br><br>
								</td> 
							<td>
								<input type='text' class='form-control' name='dosisNC' id='dosisNC' style='width:20.5em;' ><br><br>
							</td>		
							<td> 
								<input onclick='addRowNC(this.form);' type='button' value='Agregar' class='btn btn-success'  style='margin-bottom:15px;'/>
							</td>
							</tr>
						</table>
					</div>
				</div>							
			</div>
				<button class="btn btn-primary" value="Click Me!" onclick="submitForms()">Guardar y continuar</button><br><br>
		</form>


      </div>
    </div>

    <div class="container">      
      <hr>

      <footer>
        <p>&copy; 2018 Red Salud Admin Aplication 2018</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/3.3/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie10-viewport-bug-workaround.js"></script>


    <!-- script para diagnostico y tratamiento -->

    <script src="../public/jqueryTratamiento/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		submitForms = function(){
			var sin_diagnosticoSec = document.getElementById("sin_diagnosticoSec").value;
			var idsiniestro = document.getElementById("idsiniestro").value;
		
			//document.getElementById("demo").innerHTML = 5 + 6;
			//alert(sin_diagnosticoSec);
			document.getElementById("form2").submit();
			parent.location.reload(true);
			parent.$.fancybox.close();
		}

		/*jQuery(document).ready(function($) {
			$('.btnSearch').click(function(){
			makeAjaxRequest();
			});

			$("#form2").submit(function(e){
			e.preventDefault();
			makeAjaxRequest();
			return false;
			});

			function makeAjaxRequest() {
			var idsiniestro=$("#idsiniestro").val();			
			var sin_diagnosticoSec=$("#sin_diagnosticoSec").val();
				            	
			$.ajax({				                	

				url: '../public/jqueryTratamiento/search.php', 
				type: 'GET',
				data: 	"idsiniestro="+idsiniestro+				        
				        "&sin_diagnosticoSec="+sin_diagnosticoSec,
				        //data: {	name: $('input#idsiniestro').val()},
				        success: function(response) {
				        $('#grupo4').html(response);
				        }
			});
			}
		});*/
	</script>
  </body>
</html>
