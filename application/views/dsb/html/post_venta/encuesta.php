<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
	$hora = date("H");
	if($hora>0 && $hora<=12){
		$turno = "Buenos días";
	}elseif($hora>12 && $hora<=18){
		$turno = "Buenas tardes";
	}elseif($hora>18 && $hora<=24){
		$turno = "Buenas noches";
	}

	function fechaCastellano ($fecha) {
	  $fecha = substr($fecha, 0, 10);
	  $numeroDia = date('d', strtotime($fecha));
	  $dia = date('l', strtotime($fecha));
	  $mes = date('F', strtotime($fecha));
	  $anio = date('Y', strtotime($fecha));
	  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
	  $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
	  return $nombredia." ".$numeroDia." de ".$nombreMes;
	}
	//$dia = date("d/m/y", strtotime($dia));
	$fecha_atencion = fechaCastellano($fecha_atencion);
?>
<html lang="en"><head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="utf-8">
		<title>Tables - Ace Admin</title>

		<meta name="description" content="Static &amp; Dynamic Tables">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css">
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css">

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style">

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="page-content">
						<div class="page-header">
							<div class="col-xs-12">
								<h1>
								Calificación de la atención					
							</h1></div>		
						<br>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="alert alert-info">
									<b><?=$turno?></b>, me comunico con el/la Sr(a) <b><?=$nombre?> <?=$apellido?></b>?
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									Sr(a) <b><?=$nombre?></b>, nos comunicamos de Red Salud y nos gustaría hacerle una breve encuesta sobre la atención del día <b><?=$fecha_atencion?></b> en el/la <b><?=$proveedor?></b>
								</div>								<!-- PAGE CONTENT BEGINS -->
								
							</div><!-- /.col -->
						</div>
						<br>
						<div id="botones"><div class="row">
							<div class="col-xs-12">
								<div class="form-group col-md-12" style="text-align: center;">	
								<label><h5>Según respuesta:</h5></label>
								<button class="btn btn-white btn-primary btn-sm" onclick="mostrarpositiva();"><i class="ace-icon glyphicon glyphicon-plus bigger-120"></i></button>
								<button class="btn btn-white btn-danger btn-sm" onclick="mostrarnegativa();"><i class="ace-icon glyphicon glyphicon-minus bigger-120"></i></button>
							</div>	
							</div><!-- /.col -->
						</div></div>
						<br>
						<div id="negativa" style="display: none;">
							<form method="post" action="<?=base_url()?>index.php/save_sincalificar">
							<div class="row">
								<input type="hidden" name="idsiniestro" value="<?=$idsiniestro?>">
								<div class="col-xs-12">
									<div class="alert alert-info">
										Gracias Sr(a) <b><?=$nombre?></b> por atender nuestra llamada y espero que tenga <b><?=$turno?></b>
									</div><!-- PAGE CONTENT BEGINS -->
									
								</div><!-- /.col -->
								<br><br><br>
								<div style="text-align: center;">
								<button type="submit" class="btn btn-info">Guardar sin Calificar</button>
							</div>
							</form>
							</div>
						</div>
						<div id="positiva" style="display: none;">							
							<?php if(!empty($servicios)){ ?>
							<div class="alert alert-info">
								<h5 style="color: #b52626;">Servicios Utilizados en la atención:</h5>
								<div class="row">
								<?php foreach ($servicios as $s) { ?>
									<div class="col-xs-4">	
										<i class="ace-icon fa fa-asterisk"></i><span><?=$s->nombre_var?></span>
									</div>
								<?php } ?>
								</div>
							</div>							
							<br>
							<?php } ?>
							<div class="row">
								<div class="col-xs-12">	
								<form method="post" action="<?=base_url()?>index.php/save_calificar">		
									<input type="hidden" name="idsiniestro" value="<?=$idsiniestro?>">	
									<input type="hidden" name="nombre" id="nombre" value="<?=$nombre?>">					
									<table style="font-size: 12px;" class="table table-bordered table-hover">
										<thead>
											<th>Preguntas</th>
											<th colspan="5">Respuestas</th>
										</thead>
										<tbody>
											<?php foreach ($preguntas as $p) {?>
											<tr>
												<td><?=$p->descripcion?></td>
												<?php foreach ($respuestas as $r) { 
													if($p->idpregunta==$r->idpregunta){?>
												<td><input type="radio" name="radio<?=$r->idpregunta?>" id="<?=$r->idrespuesta?>" value="<?=$r->idrespuesta?>"> <label style="font-size: 12px;" for="<?=$r->idrespuesta?>"><?=$r->descripcion?></label></td>										
												<?php } } ?>											
											</tr>
											<?php } ?>
											<tr>
												<td>¿Tiene algún comentario y/o sugerencia que le gustaría compartir?</td>
												<td colspan="5"><textarea maxlength="2500" name="comentario" id="comentario" cols="80" rows="1" placeholder="Escribir comentario y/o sugerencia aquí"></textarea></td>
											</tr>
										</tbody>
									</table>																
								</div><!-- /.col -->
							</div>
								<div style="text-align: right;">
									<button type="submit" class="btn btn-info">Guardar Calificación</button>
								</div>
							</form>		
						</div>
					</div>
				</div><!-- /.main-content -->			
			</div><!-- /.main-container -->
		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script><script src="<?=  base_url()?>public/assets/js/jquery.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		</script>
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?=  base_url()?>public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=  base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

		<!-- ace scripts -->
		<script src="<?=  base_url()?>public/assets/js/ace/elements.scroller.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.fileinput.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.typeahead.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.spinner.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.treeview.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.wizard.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.aside.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.onpage-help.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/docs/assets/js/themes/sunburst.css">

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>
		<script type="text/javascript">
			function mostrarnegativa(){
				document.getElementById('negativa').style.display = 'block';
				document.getElementById('positiva').style.display = 'none';
			}
			function mostrarpositiva(){
				document.getElementById('positiva').style.display = 'block';
				document.getElementById('negativa').style.display = 'none';
			}
		</script>
</body></html>