<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->

		<!-- Add fancyBox -->
		<link rel="stylesheet" href="<?=  base_url()?>public/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

		<script>
			$(".fancybox")
	    .attr('rel', 'gallery')
	    .fancybox({
	        type: 'iframe',
	        autoSize : false,
	        beforeLoad : function() {         
	            this.width  = parseInt(this.element.data('fancybox-width'));  
	            this.height = parseInt(this.element.data('fancybox-height'));
	        }
	    });
	</script>
		<!-- inline styles related to this page -->

		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

		<script type="text/javascript">
		$(document).ready(function(){
	        $("#dni").keyup(function () {
	        	$("#dni").each(function () {
		            dni=$('#dni').val();
		            $.post("<?=base_url();?>index.php/planesDni", { dni: dni}, function(data){
		            $("#plan").html(data);
		            });
		        });	               
	        })
	    });
		</script>
		
		

		<script>
		   $(document).ready(function() {
		   $(window).keydown(function(event){
		     if(event.keyCode == 13) {
		       event.preventDefault();
		       return false;
		     }
		   });
		 });
		</script>
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include (APPPATH."views/dsb/html/headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include (APPPATH."views/dsb/html/sideBar.php");?>
			<!-- end nav. -->
			
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="<?=base_url()?>">Home</a>
							</li>

							<li>
								Cliente
							</li>

							<li class="active">
								Solicitudes de Cancelación
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Solicitudes de Cancelación
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Solicitudes Pendientes
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Solicitudes Procesadas
											</a>
										</li>
							
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">								

											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Cliente</th>
																<th>Plan</th>
																<th>N° Certificado</th>
																<th>DNI</th>
																<th>Contratante</th>
																<th>Fecha Solicitud</th>
																<th>Motivo</th>
																<th></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($getSolicitudes as $s) { 
																$fecha = $s->fecha_solicitud;
																$fecha = date('d/m/Y H:i:s', strtotime($fecha));
																?>
															<tr>
																<td><?=$s->nombre_comercial_cli?></td>
																<td><?=$s->nombre_plan?></td>
																<td><?=$s->cert_num?></td>
																<td><?=$s->cont_numDoc?></td>
																<td><?=$s->contratante?></td>
																<td><?=$fecha?></td>
																<td><?=$s->motivo?></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Aceptar Solicitud" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a href="<?=base_url()?>index.php/aceptar_solicitud/<?=$s->cert_id?>" >
																				<i class="blue ace-icon glyphicon glyphicon-ok bigger-120"></i>
																			</a>
																		</div>	
																		<div title="Rechazar Solicitud" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/rechazar_solicitud/<?=$s->cert_id?>" data-fancybox-width="750" data-fancybox-height="600">
																				<i class="red ace-icon glyphicon glyphicon-remove bigger-120"></i>
																			</a>
																		</div>													
																	</div>

																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">	
																				<li>
																					<div title="Aceptar Solicitud" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a href="<?=base_url()?>index.php/aceptar_solicitud/<?=$s->cert_id?>" >
																							<i class="blue ace-icon glyphicon glyphicon-ok bigger-120"></i>
																						</a>
																					</div>	
																				</li>

																				<li>
																					<div title="Rechazar Solicitud" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/rechazar_solicitud/<?=$s->cert_id?>" data-fancybox-width="750" data-fancybox-height="600">
																							<i class="red ace-icon glyphicon glyphicon-remove bigger-120"></i>
																						</a>
																					</div>	
																				</li>
																			</ul>
																		</div>
																	</div>
																</td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->

												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>


										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example2" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Cliente</th>
																<th>Plan</th>
																<th>N° Certificado</th>
																<th>DNI</th>
																<th>Contratante</th>
																<th>Fecha</th>
																<th>Estado</th>
																<th>Motivo</th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach ($getSolicitudesProcesadas as $sp) { 
																$fecha = $sp->fecha_procesamiento;
																$fecha = date('d/m/Y H:i:s', strtotime($fecha));
																?>

															<tr>
																<td><?=$sp->nombre_comercial_cli?></td>
																<td><?=$sp->nombre_plan?></td>
																<td><?=$sp->cert_num?></td>
																<td><?=$sp->cont_numDoc?></td>
																<td><?=$sp->contratante?></td>
																<td><?=$fecha?></td>
																<td><span class="<?=$sp->class?>"><?=$sp->estado?></span></td>
																<td><?=$sp->motivo?></td>
															</tr>

															<?php } ?>

														</tbody>
													</table>
												</div>
												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example2').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
												<!-- end table -->
										</div>

										
								</div>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Red Salud Admin</span>
							Application &copy; 2018
						</span>

						&nbsp; &nbsp;
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		
		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script> -->

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<!-- <link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" /> -->

		<!-- <script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script> -->		

	</body>
</html>
