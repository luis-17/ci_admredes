<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
		<!-- FancyBox -->
		<!-- Add jQuery library -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
		
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
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->

		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

		
		<!-- pass var to model from foreach -->
		<script type="text/javascript">
			
			$(document).on("click", ".open-registerPay", function () {
		     var liqdetalleid = $(this).data('id');
		     $(".modal-body #liqdetalleid").val( liqdetalleid );
		     // As pointed out in comments, 
		     // it is superfluous to have to manually call the modal.
		     // $('#addBookDialog').modal('show');
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
								<a href="<?=base_url()?>">Inicio</a>
							</li>

							<li class="active">
								<a href="#">Reportes</a>
							</li>

							<li class="active">
								Liquidaciones
							</li>
						</ul><!-- /.breadcrumb -->
						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Liquidaciones
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
												Pendientes de Pago
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Pagadas
											</a>
										</li>								
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">	
											<!-- star table -->	
												<form method="post" action="<?=base_url()?>index.php/save_pago">	
												<div class="col-xs-12">
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th width="5%">Grupo</th>
																<th width="10%">N° RUC</th>
																<th width="15%">Proveedor</th>
																<th width="10%">Liquidaciones</th>
																<th width="25%">N° Orden</th>
																<th width="25%">Facturas</th>
																<th width="8%">Importe</th>
																<th width="3%"></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($pagos_pendientes as $pp) { 
																$total = $pp->importe; 
																$total = number_format((float)$total, 2, '.', '');
																$detraccion = $pp->importe_detraccion;
																$detraccion = number_format((float)$detraccion, 2, '.', ''); ?>
															<tr>
																<td><?=$pp->grupo?></td>
																<td><?=$pp->numero_documento_pr?></td>
																<td><?=$pp->razon_social_pr?></td>
																<td><?=$pp->liquidaciones?></td>
																<td><?=$pp->atenciones?></td>
																<td><?=$pp->facturas?></td>
																<td style="text-align: right;"><p><b>Total: <?=$total?> PEN</b></p><p>Detracción: <?=$detraccion?> PEN</p></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>index.php/pago_detalle/<?=$pp->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-zoom-in"></i>
																			</a>&nbsp;
																		</div>

																		<div title="Imprimir Liquidación" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>index.php/imprimir_liquidacion/<?=$pp->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-print bigger-110"></i>
																			</a>&nbsp;
																		</div>														
																	</div>

																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-info btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/pago_detalle/<?=$pp->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon glyphicon glyphicon-zoom-in"></i>
																						</a>&nbsp;
																					</div>
																				</li>
																				<li>
																					<div title="Imprimir Liquidaciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/imprimir_liquidacion/<?=$pp->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon glyphicon glyphicon-print bigger-110"></i>
																						</a>&nbsp;
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
													<br>
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

												</form>
										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example2" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th width="5%">Grupo</th>
																<th width="10%">N° RUC</th>
																<th width="15%">Proveedor</th>
																<th width="10%">Liquidaciones</th>
																<th width="25%">N° Orden</th>
																<th width="25%">Facturas</th>
																<th width="8%">Importe</th>
																<th width="3%"></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($getPagosRealizados as $pr) { 
																$total = $pr->importe; 
																$total = number_format((float)$total, 2, '.', '');
																$detraccion = $pr->importe_detraccion;
																$detraccion = number_format((float)$detraccion, 2, '.', ''); ?>
															<tr>
																<td><?=$pr->grupo?></td>
																<td><?=$pr->numero_documento_pr?></td>
																<td><?=$pr->razon_social_pr?></td>
																<td><?=$pr->liquidaciones?></td>
																<td><?=$pr->atenciones?></td>
																<td><?=$pr->facturas?></td>
																<td style="text-align: right;"><p><b>Total: <?=$total?> PEN</b></p><p>Detracción: <?=$detraccion?> PEN</p></td>
																<td><div class="hidden-sm hidden-xs btn-group">
																		<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>index.php/pago_detalle/<?=$pr->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-zoom-in"></i>
																			</a>&nbsp;
																		</div>
																		<div title="Constancia de Pago" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>uploads/<?=$pr->idpago?>.pdf" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-file bigger-110"></i>
																			</a>&nbsp;
																		</div>

																		<div title="Imprimir Liquidación" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>index.php/imprimir_liquidacion/<?=$pr->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-print bigger-110"></i>
																			</a>&nbsp;
																		</div>
																	</div>

																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-info btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/pago_detalle/<?=$pr->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon glyphicon glyphicon-zoom-in"></i>
																						</a>&nbsp;
																					</div>
																				</li>
																				<li>
																					<div title="Constancia de Pago" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>uploads/<?=$pr->idpago?>.pdf" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon glyphicon glyphicon-file bigger-110"></i>
																							</a>&nbsp;
																					</div>
																				</li>
																				<li>
																					<div title="Imprimir Liquidaciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/imprimir_liquidacion/<?=$pr->idpago?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon glyphicon glyphicon-print bigger-110"></i>
																						</a>&nbsp;
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

								<!-- .tabbale -->
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

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script>

	</body>
</html>
