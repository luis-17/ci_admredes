<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
	$hora = date("H");
	if($hora>0 && $hora<=12){
		$turno = "buenos días";
	}elseif($hora>12 && $hora<=18){
		$turno = "buenas tardes";
	}elseif($hora>18 && $hora<=24){
		$turno = "buenas noches";
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
	$dia = fechaCastellano($dia);
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

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>
	</head>

	<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="page-content">
						<div class="page-header">
							<h1>
							Notificación al afiliado
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="alert alert-info">
									Sr. / Sra. / Srta. <b><?=$afiliado?></b> lo / la saluda <b><?=$nombres_col?> <?=$ap_paterno_col?></b> de RED SALUD, lo estoy llamando por la atención médica que solicitó hace algunos minutos.
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									<p>Sr. / Sra. / Srta. <b><?=$nombre?></b> su atención ya ha sido coordinada para el día <b><?=$dia?></b>, a las <b><?=$hora_inicio?></b>, en la especialidad de <b><?=$especialidad?></b> en <b><?=$proveedor?></b>.</p>
									<p>Sr. / Sra. / Srta. <b><?=$afiliado?></b> debe tener en cuenta las siguientes recomendaciones:</p>
									<ul>
										<li><?=$consulta?>.</li>
										<li>Al acercarse a <b><?=$proveedor?></b> se debe identificar como AFILIADO RED SALUD y debe de llevar consigo su DNI físico.</li>
										<li>Recuerde que el horario que le estoy brindando es referencial y usted debe de acudir media hora antes a su cita médica a fin de no tener inconvenientes.</li>
										<li>Si Usted no encontrase la totalidad de la receta en la farmacia del establecimiento de salud, Usted también puede acercarse con su receta a cualquier Botica Inkafarma a reclamar sus medicamentos, para lo cual deberá comunicarse con nosotros al 445 3019 anexo xxx para coordinar su entrega.</li>
									</ul>
									<p>Además, permítame recordarle los BENEFICIOS con los que cuenta su plan <b><?=$nombre_plan?></b>.</p>
									<ul>
										<?php foreach ($cobertura_operador as $co1) {
											switch($co1->tiempo){
												case '':
													$men ="Eventos ilimitados";
												break;
												case '1 month':
													if($co1->num_eventos==1){
							                     		$men = "evento al mes";
													}else{
														$men = "eventos mensuales";
													}													                    
												break;
												case '2 month':
													if($co1->num_eventos==1){
													    $men = "evento bimestral";
													}else{
													    $men = "eventos bimestrales";
													}
												break;
												case '3 month':
													if($co1->num_eventos==1){
													    $men = "evento trimestral";
													}else{
													    $men = "eventos trimestrales";
													}
												break;
												case '6 month':
													if($co1->num_eventos==1){
													    $men = "evento semestral";
													}else{
													    $men = "eventos semestrales";
												}
											break;
											case '1 year':
												if($co1->num_eventos==1){
													$men = "evento al año";
												}else{
													$men = "eventos anuales";
												}
											break;
										}
													           
										if($co1->num_eventos==0){
											$num_eve='';
										}else{
											$num_eve=$co1->num_eventos;
										}
									?>
										<li><b><?=$co1->nombre_var?></b>, <?=$co1->texto_web?>, <?=$co1->coaseguro?>, <?php echo $num_eve.' '.$men;?>.</li>
									<?php } ?>
									</ul>
									<p>Sr. / Sra. / Srta. <b><?=$nombre?></b> ¿Tiene alguna consulta, respecto a los beneficios que le acabo de mencionar?</p>
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado… Si tiene consultas, absolverlas y luego seguir con el siguiente paso.
								</div>	
								<div class="alert alert-info">
									Sr. / Sra. / Srta. <b><?=$nombre?></b>, permítame recordarle que cualquier inconveniente o consulta que Usted tenga en su atención médica, puede comunicarse con nosotros a nuestra CENTRAL DE AYUDA AL AFILIADO RED SALUD, al 445 3019 anexo xxx donde gustosamente lo atenderemos.
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado 
								</div>	
								<div class="alert alert-info">
									Finalmente Sr. / Sra. / Srta. <b><?=$nombre?></b> se le enviará por correo electrónico y por mensaje de texto los datos de su atención médica, de tal manera que los pueda tener disponibles.
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado 
								</div>						
								<div class="alert alert-info">
									Sr. / Sra. / Srta. <b><?=$nombre?></b>, la (lo) atendió <b><?=$nombres_col?> <?=$ap_paterno_col?></b>. Que tenga <b><?=$turno?></b>.
								</div>				
							</div><!-- /.col -->
						</div>
						<br>
						<div class="form-row">
							<div class="form-group col-md-12" style="text-align: right;">	
								<a href="<?=base_url()?>index.php/notificar/<?=$idcita?>" class="btn btn-info">Afiliado Notificado</a>
							</div>	
						</div>	
					</div>
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
</body></html>