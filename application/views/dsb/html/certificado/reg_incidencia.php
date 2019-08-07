<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
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
							<div class="col-xs-9">
								<h1>
								Registrar Incidencia					
							</h1></div>
							<div class="col-xs-3">
								<a href="<?=base_url()?>index.php/historial_incidencias/<?=$id?>/<?=$idaseg?>"  class="btn btn-info">Historial de Incidencias</a>
							</div><br><br>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/save_incidencia">
									<input type="hidden" id="aseg_id" name="aseg_id" value="<?=$idaseg?>" />
									<input type="hidden" name="cert_id" id="cert_id" value="<?=$id?>" />

									<div class="form-group">
										<div class="col-sm-12">
											<p><input type="radio" name="tipo" value="El plan de salud no le cubre una especialidad y cuando le vendieron el plan le mencionaron que sí cubría." id="1"> El plan de salud no le cubre una especialidad y cuando le vendieron el plan le mencionaron que sí cubría.</p>
											<p><input type="radio" name="tipo" value="El plan de salud no le cubre los análisis clínicos del segundo diagnóstico y cuando le vendieron el plan le mencionaron que sí cubría." id="2"> El plan de salud no le cubre los análisis clínicos del segundo diagnóstico y cuando le vendieron el plan le mencionaron que sí cubría.</p>
											<p><input type="radio" name="tipo" value="Le han negado la atención en el establecimiento de salud y le han dicho que no cuenta con plan activo en red salud." id="3"> Le han negado la atención en el establecimiento de salud y le han dicho que no cuenta con plan activo en red salud.</p>
											<p><input type="radio" name="tipo" value="Inconvenientes en la entrega de sus medicamentos en el establecimiento de salud." id="4"> 	Inconvenientes en la entrega de sus medicamentos en el establecimiento de salud.</p>
											<p><input type="radio" name="tipo" value="Insatisfacción con el establecimiento de salud." id="5"> Insatisfacción con el establecimiento de salud.</p>
											<p><input type="radio" name="tipo" value="Insatisfacción con la coordinación de la cita." id="6"> Insatisfacción con la coordinación de la cita.</p>
											<p><input type="radio" name="tipo" value="No le ha llegado la póliza de manera física o virtual." id="7"> No le ha llegado la póliza de manera física o virtual.</p>
											<p><input type="radio" name="tipo" value="El afiliado desea retirarse del plan de salud porque no usa el plan de salud.." id="8"> El afiliado desea retirarse del plan de salud porque no usa el plan de salud..</p>
											<p><input type="radio" name="tipo" value="El afiliado desea retirarse del plan de salud por una mala venta del canal donde se le prometió algo que no cubre el plan." id="9"> El afiliado desea retirarse del plan de salud por una mala venta del canal donde se le prometió algo que no cubre el plan.</p>
											<p><input type="radio" name="tipo" value="El afiliado desea retirarse del plan de salud porque no sabía lo que había adquirido." id="10"> El afiliado desea retirarse del plan de salud porque no sabía lo que había adquirido.</p>
											<p><input type="radio" name="tipo" value="Otros." id="10"> Otros.</p>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Comentario: </label>

										<div class="col-sm-9">
											<textarea cols="71" rows="5" placeholder="Escriba un comentario" id="desc" name="desc"></textarea>
										</div>
									</div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" id="derivar" name="derivar">
												<i class="ace-icon glyphicon glyphicon-share bigger-110"></i>
												Derivar
											</button>
											<button class="btn btn-info" type="submit" id="guardar" name="guardar">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
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
</body></html>