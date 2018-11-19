<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<!--<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>-->
		<!-- FancyBox -->
		<!-- Add jQuery library -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

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

		<!-- ace settings handler -->
		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/../headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("/../sideBar.php");?>
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
								<a href="<?=base_url()?>">Inicio</a>
							</li>

							<li class="active">
								Reservas
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Reservas
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
												Mis Reservas
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Otras Reservas
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Reservas Atendidas
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-4">
												Atenciones Directas
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
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha y Hora</th>
																<th>N° DNI</th>
																<th>Afiliado</th>
																<th>Estado</th>
																<th></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($mis_reservas as $mr) { 
																switch ($mr->estado_cita):
																	case 0: 
																		$estadoa='Reserva Anulada';
																		$class="label label-danger label-white middle";
																		$e1=0;
																	break;
																	case 1:
																		$estadoa='Reserva Por Confirmar';
																		$e1=1;
																		$class="label label-warning label-white middle";
																	break;
																	case 2:
																		$estadoa='Reserva Confirmada';
																		$e1=2;
																		$class="label label-success label-white middle";
																	break;
																endswitch;
															?>
															<tr>
																<td><?=$mr->idcita?></td>
																<td>PO<?=$mr->num_orden_atencion?></td>
																<td><?=$mr->nombre_plan?></td>
																<td><?=$mr->nombre_comercial_pr?></td>
																<td><?=$mr->fecha?></td>
																<td><?=$mr->aseg_numDoc?></td>
																<td><?=$mr->afiliado?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																				<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$mr->idcertificado?>/<?=$mr->idasegurado?>/<?=$mr->idcita?>/<?=$mr->idcertificadoasegurado?>/null" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-pencil bigger-120"></i>
																					</a>
																				</div>

																				<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$mr->idcita?>/<?=$mr->idasegurado?>/<?=$mr->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
																					</a>
																				</div>	
																				<?php if($e1==2){ ?>
																				<div title="Reenviar Email Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_proveedor/<?=$mr->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-envelope bigger-120"></i>
																					</a>
																				</div>	
																				<div title="Reenviar Email Afiliado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_afiliado/<?=$mr->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-envelope-o bigger-120"></i>
																					</a>
																				</div>					
																				<?php } ?>
																		</div>

																		<div class="hidden-md hidden-lg">
																			<div class="inline pos-rel">
																				<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																					<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																				</button>

																				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">			
																					<li>
																						<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$mr->idcertificado?>/<?=$mr->idasegurado?>/<?=$mr->idcita?>/<?=$mr->idcertificadoasegurado?>/null" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-pencil bigger-120"></i>
																							</a>
																						</div>
																					</li>
																					<li>
																						<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$mr->idcita?>/<?=$mr->idasegurado?>/<?=$mr->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
																							</a>
																						</div>

																					</li>
																					<?php if($e1==2){ ?>
																					<li>
																						<div title="Reenviar Email Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_proveedor/<?=$mr->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-envelope bigger-120"></i>
																							</a>
																						</div>
																					</li>
																					<li>	
																						<div title="Reenviar Email Afiliado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_afiliado/<?=$mr->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-envelope-o bigger-120"></i>
																							</a>
																						</div>	
																					</li>				
																					<?php } ?>	
																				</ul>
																			</div>
																		</div>
																</td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
												<!-- end table -->


										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha y Hora</th>
																<th>N° DNI</th>
																<th>Afiliado</th>	
																<th>Atendido por</th>
																<th>Estado</th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach ($otras_reservas as $or) { 
																switch ($or->estado_cita):
																	case 0: 
																		$estadoa='Reserva Anulada';
																		$class="label label-danger label-white middle";
																		$e1=0;
																	break;
																	case 1:
																		$estadoa='Reserva Por Confirmar';
																		$e1=1;
																		$class="label label-warning label-white middle";
																	break;
																	case 2:
																		$estadoa='Reserva Confirmada';
																		$e1=2;
																		$class="label label-success label-white middle";
																	break;
																endswitch;
															?>
															<tr>
																<td><?=$or->idcita?></td>
																<td>PO<?=$or->num_orden_atencion?></td>
																<td><?=$or->nombre_plan?></td>
																<td><?=$or->nombre_comercial_pr?></td>
																<td><?=$or->fecha?></td>
																<td><?=$or->aseg_numDoc?></td>
																<td><?=$or->afiliado?></td>
																<td><?=$or->username?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example1').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
												<!-- end table -->
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<!-- star table -->		
											<div class="col-xs-12">

												<table id="example2" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha Atencion</th>
																<th>N° DNI</th>
																<th>Afiliado</th>
																<th>N° Telf.</th>
																<th>Atendido por</th>
																<th>Estado</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($reservas_atendidas as $ra) { 
																switch ($ra->estado_siniestro):
																	case 0: 
																		$estadoa='Atención Anulada';
																		$e2=0;
																		$class="label label-danger label-white middle";
																	break;
																	case 1:
																		$estadoa='Atención Abierta';
																		$e2=1;
																		$class="label label-info label-white middle";
																	break;
																	case 2:
																		$estadoa='Atención Cerrada';
																		$e2=2;
																		$class="label label-purple label-white middle";
																	break;
																endswitch;
															?>
															<tr>
																<td><?=$ra->idsiniestro?></td>
																<td>OA<?=$ra->num_orden_atencion?></td>
																<td><?=$ra->nombre_plan?></td>
																<td><?=$ra->nombre_comercial_pr?></td>
																<td><?=$ra->fecha_atencion?></td>
																<td><?=$ra->aseg_numDoc?></td>
																<td><?=$ra->afiliado?></td>
																<td><?=$ra->aseg_telf?></td>
																<td><?=$ra->username?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->

												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example2').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
										</div>
										<div id="faq-tab-4" class="tab-pane fade">
											<!-- star table -->		
											<div class="col-xs-12">

												<table id="example3" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha Atencion</th>
																<th>N° DNI</th>
																<th>Afiliado</th>
																<th>N° Telf.</th>
																<th>Estado</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($atenciones_directas as $ad) { 
																switch ($ad->estado_siniestro):
																	case 0: 
																		$estadoa='Atención Anulada';
																		$e2=0;
																		$class="label label-danger label-white middle";
																	break;
																	case 1:
																		$estadoa='Atención Abierta';
																		$e2=1;
																		$class="label label-info label-white middle";
																	break;
																	case 2:
																		$estadoa='Atención Cerrada';
																		$e2=2;
																		$class="label label-purple label-white middle";
																	break;
																endswitch;
															?>
															<tr>
																<td><?=$ad->idsiniestro?></td>
																<td>OA<?=$ad->num_orden_atencion?></td>
																<td><?=$ad->nombre_plan?></td>
																<td><?=$ad->nombre_comercial_pr?></td>
																<td><?=$ad->fecha_atencion?></td>
																<td><?=$ad->aseg_numDoc?></td>
																<td><?=$ad->afiliado?></td>
																<td><?=$ad->aseg_telf?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->

												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example3').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
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
			window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?=  base_url()?>public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=  base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

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

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>
	</body>
</html>