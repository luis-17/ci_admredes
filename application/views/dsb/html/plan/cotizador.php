<?php 
$user = $this->session->userdata('user');
extract($user);
?>
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
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<!--<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>-->
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
							<li class="active">Cotizador</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Cotizaciones
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<div class="widget-toolbar no-border invoice-info">
									<a href="<?=base_url()?>index.php/cotizador_registrar"><button class="btn btn-white btn-info">
										Nueva Cotización
									</button></a>
								</div>
								<br/>
								<br/>
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="col-xs-12">
									<div>
									<table id="example" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Cliente</th>
												<th>Cotización</th>
												<th>Prima</th>
												<th>Estado</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										
										<?php 
											if($idtipousuario==3){
											foreach($cotizaciones as $c){
												if($c->idusuario==$idusuario){?>
											<tr>
												<td><?=$c->nombre_comercial_cli?></td>
												<td><?=$c->nombre_cotizacion?></td>
												<td>S/. <?=$c->prima_monto?></td>
												<td><?php if($c->estado_cotizacion==1){
													echo '<a href="'.base_url().'index.php/plan_anular/'.$c->idcotizacion.'"><span class="label label-info label-white middle">Activo</span></a>';
													}else{
														echo '<a href="'.base_url().'index.php/plan_activar/'.$c->idcotizacion.'"><span class="label label-danger label-white middle">Inactivo</span></a>';
														}?>
												</td>
												<td>
													<div class="hidden-sm hidden-xs btn-group">
														<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
														<a href="<?=base_url()?>index.php/plan_cobertura/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
															<i class="ace-icon fa fa-eye bigger-120 blue"></i>
														</a>
														</div>
														<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a href="<?=base_url()?>index.php/plan_editar/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
																<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
															</a>
														</div>
														<?php if($c->tipo_responsable=='P'){ ?>
														<div title="Asignar Responsable" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/addresponsable/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon glyphicon glyphicon-user bigger-120 blue"></i>
															</a>

														</div>
														<?php } ?>
														<div title="Editar Email" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_email/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon fa fa-envelope bigger-120 blue"></i>
														</a>
														</div>
														<div title="Editar Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_proveedor/<?=$c->idcotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon fa fa-medkit bigger-120 blue"></i>
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
																			<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=base_url()?>index.php/plan_cobertura/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-eye bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a href="<?=base_url()?>index.php/plan_editar/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
																					<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<?php if($c->tipo_responsable=='P'){ ?>
																		<li>
																			<div title="Asignar Responsable" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/addresponsable/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-user bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<?php } ?>
																		<li>
																			<div title="Editar Email" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_email/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-envelope bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Editar Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_proveedor/<?=$c->idcotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-medkit bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>	
																	</ul>
																</div>
															</div>
														</td>
													</tr>
												<?php } }}else{
													foreach($cotizaciones2 as $c){?>
												<tr>
												<td><?=$c->nombre_comercial_cli?></td>
												<td><?=$c->nombre_cotizacion?></td>
												<td>S/. <?=$c->prima_monto?></td>
												<td><?php if($c->estado_cotizacion==1){
													echo '<a href="'.base_url().'index.php/plan_anular/'.$c->idcotizacion.'"><span class="label label-info label-white middle">Activo</span></a>';
													}else{
														echo '<a href="'.base_url().'index.php/plan_activar/'.$c->idcotizacion.'"><span class="label label-danger label-white middle">Inactivo</span></a>';
														}?>
												</td>
												<td>
													<div class="hidden-sm hidden-xs btn-group">
														<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
														<a href="<?=base_url()?>index.php/plan_cobertura/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
															<i class="ace-icon fa fa-eye bigger-120 blue"></i>
														</a>
														</div>
														<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a href="<?=base_url()?>index.php/plan_editar/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
																<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
															</a>
														</div>
														<div title="Asignar Responsable" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/addresponsable/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon glyphicon glyphicon-user bigger-120 blue"></i>
															</a>
														</div>
														<div title="Editar Email" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_email/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon fa fa-envelope bigger-120 blue"></i>
														</a>
														</div>
														<div title="Editar Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
															&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_proveedor/<?=$c->idcotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon fa fa-medkit bigger-120 blue"></i>
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
																			<div title="Ver Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=base_url()?>index.php/plan_cobertura/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-eye bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Editar Plan" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a href="<?=base_url()?>index.php/plan_editar/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>">
																					<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Asignar Responsable" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/addresponsable/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-user bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Editar Email" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_email/<?=$c->idcotizacion?>/<?=$c->nombre_cotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-envelope bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>
																		<li>
																			<div title="Editar Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/plan_proveedor/<?=$c->idcotizacion?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-medkit bigger-120 blue"></i>
																				</a>
																			</div>
																		</li>	
																	</ul>
																</div>
															</div>
														</td>
													</tr>
												<?php }} ?>
										</tbody>
									</table>							
								</div><!-- PAGE CONTENT ENDS -->	
								<script>			
										//para paginacion
										$(document).ready(function() {
										$('#example').DataTable( {
										"pagingType": "full_numbers"
										} );
									} );
									</script>	
								</div>						
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
							<span class="blue bolder">Red Salud</span>
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

		<!-- fin scripts paginacion -->

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