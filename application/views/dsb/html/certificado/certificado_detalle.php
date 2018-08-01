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
								<a href="<?=base_url()?>index">Inicio</a>
							</li>

							<li>
								<a href="<?=base_url()?>certificado2/<?=$doc?>">Certificado</a>
							</li>
							<li class="active">Detalle Certificado</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Detalle Certificado
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
												Certificado
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Afiliados
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Cobros
											</a>
										</li>
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">
											<h4 class="blue">
												<i class="ace-icon glyphicon glyphicon-check bigger-110"></i>
												Datos del Certificado
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
											<?php foreach ($certificado as $cert):
											$id=$cert->cert_id;
											if($cert->cert_estado==1):
												$e=1;
												$estado="Vigente";												
												else:
													$estado="Cancelado";
													$e=3;													
											endif;
											$e2=0;
											if($cert->cert_upProv==1):
												$e2=1;
												$estado2="Activo Manualmente";
												$boton="ace-icon glyphicon glyphicon-remove";
												$titulo="Cancelar Manualmente";
												$ruta="cancelar_certificado";
												else:
													$e2=3;
													$estado2="Inactivo";
													$boton="ace-icon glyphicon glyphicon-ok";
													$titulo="Activar Manualmente";
													$ruta="activar_certificado";
											endif;
											$inicio=$cert->cert_iniVig;
											if($cert->plan_id==13):
												$fin = date("d-m-Y", strtotime($inicio." + 1 month"));		
												$inicio=date("d/m/Y", strtotime($inicio));
												else:
													$inicio=date("d/m/Y", strtotime($inicio));
													$fin=$cert->cert_finVig;
													$fin=date("d/m/Y", strtotime($fin));
											endif;	
											$cobro=$cert->ultimo_cobro;
											$cobro=date("d/m/Y", strtotime($cobro));
											$cobertura=$cert->ultima_cobertura;
											$cobertura=date("d/m/Y", strtotime($cobertura));
											?>
												<div class="col-xs-9 col-sm-12">
													<div class="widget-box transparent">
													<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">
																<div class="profile-info-name"> Nro Certificado: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$cert->cert_num;?></span>
																</div>
																<div class="profile-info-name"> Contratante: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$cert->contratante;?></span>
																</div>
																<div class="profile-info-name"> Est. Atención: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$estado2;?></span>
																		<?php if($cert->plan_id!=13):?>
																		<a href="<?=  base_url()?><?=$ruta?>/<?=$id?>/<?=$doc?>" title="<?=$titulo;?>">
																				<span class="<?=$boton ?>"></span>
																		</a>
																	<?php endif; ?>
																</div>
															</div>

															<div class="profile-info-row">
																<div class="profile-info-name"> Est. Certificado: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$estado?></span>
																</div>
																<div class="profile-info-name"> Inicio Vigencia: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$inicio;?></span>
																</div>
																<div class="profile-info-name"> Fin Vigencia: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$fin;?></span>
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Cliente: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->nombre_comercial_cli;?></span>
																</div>
																<div class="profile-info-name"> Plan: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->nombre_plan;?></span>
																</div>
																<div class="profile-info-name"> Precio: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->prima_monto;?> PEN</span>
																</div>
															</div>
															
															<div class="profile-info-row">
																<div class="profile-info-name"> Últ. Cobro: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cobro;?></span>
																</div>
																<div class="profile-info-name"> Cancelación: </div>

																<div class="profile-info-value">
																	<span class="editable editable-click" id="age">-</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
											<?php endforeach; ?>
										<div id="faq-tab-2" class="tab-pane fade">
											<h4 class="blue">
												<i class="green ace-icon fa fa-users bigger-110"></i>
												Listado de Afiliados al Certificado
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
												<!-- star table -->		
												<div class="col-xs-12">
													<table id="simple-table" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>DNI</th>
																<th>Asegurado</th>
																<th>Teléfono</th>
																<th>Email</th>
																<th></th>
															</tr>
														</thead>

														<tbody>
														<?php foreach ($asegurado as $aseg):
														$idaseg=$aseg->aseg_id;
														$fec=$aseg->ultima_atencion;										
														$certase = $aseg->certase_id;
														$fec=date("d/m/Y", strtotime($fec));
														?>
															<tr>
																<td><?=$aseg->aseg_numDoc;?></td>
																<td><?=$aseg->asegurado;?></td>
																<td><?=$aseg->aseg_telf;?></td>
																<td><?=$aseg->aseg_email;?></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																			<div title="Ver Atenciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=  base_url()?>aseg_atenciones/<?=$idaseg?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</a>
																			</div>
																			<div title="Editar Asegurado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=  base_url()?>aseg_editar/<?=$idaseg?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-pencil bigger-120"></i>
																				</a>
																			</div>
																			<?php if($e==1&&$e2!=3) {?>
																			<div title="Reservar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				&nbsp;<a class="boton fancybox" href="<?=  base_url()?>reservar_cita/<?=$id?>/<?=$idaseg?>/<?=$doc?>/<?=$certase?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon fa fa-external-link bigger-120"></i>
																				</a>
																			</div>
																			<?php } ?>
																	</div>

																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<div title="Ver Atenciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=  base_url()?>aseg_atenciones/<?=$id?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon fa fa-eye bigger-120"></i>
																						</a>
																			</div>

																				</li>

																				<li>
																					<div title="Editar Asegurado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=  base_url()?>aseg_editar/<?=$id?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon fa fa-pencil bigger-120"></i>
																						</a>
																					</div>
																				</li>
																				<?php if($e==1) {?>
																				<li>
																					<div title="Reservar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a class="boton fancybox" href="<?=  base_url()?>reservar_cita/<?=$id?>/<?=$idaseg?>/<?=$doc?>/<?=$certase?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-external-link bigger-120"></i>
																						</a>
																					</div>
																				</li>
																				<?php } ?>
																			</ul>
																		</div>
																	</div>
																</td>
															</tr>
														<?php endforeach;?>
														</tbody>
													</table>
												</div>
												<!-- end table -->
											</div>
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<h4 class="blue">
												<i class="orange ace-icon fa fa-credit-card bigger-110"></i>
												Cobros Registrados
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-3" class="panel-group accordion-style1 accordion-style2">
												<!-- star table -->		
												<div class="col-xs-12">
													<table id="simple-table" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Fecha Cobro</th>
																<th>Vez Cobro</th>
																<th>Importe</th>
																<th>Inicio Cobertura</th>
																<th>Fin Cobertura</th>
															</tr>
														</thead>

														<tbody>
														<?php foreach ($cobros as $cob):
														$cobro=$cob->cob_fechCob;
														$cobro=date("d/m/Y", strtotime($cobro));
														$inicio=$cob->cob_iniCobertura;;
														$inicio=date("d/m/Y", strtotime($inicio));
														$fin=$cob->cob_finCobertura;
														$fin=date("d/m/Y", strtotime($fin));
														?>
															<tr>
																<td><?=$cobro;?></td>
																<td><?=$cob->cob_vezCob;?></td>
																<td><?=$cob->importe;?></td>
																<td><?=$inicio;?></td>
																<td><?=$fin;?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
													</table>
												</div>
												<!-- end table -->
											</div>
										</div>
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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html',
									 {
										tagValuesAttribute:'data-values',
										type: 'bar',
										barColor: barColor ,
										chartRangeMin:$(this).data('min') || 0
									 });
				});
			
			
			  //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
			  //but sometimes it brings up errors with normal resize event handlers
			  $.resize.throttleWindow = false;
			
			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "social networks",  data: 38.7, color: "#68BC31"},
				{ label: "search engines",  data: 24.5, color: "#2091CF"},
				{ label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
				{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
				{ label: "other",  data: 10, color: "#FEE074"}
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  $.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);
			
			
			  //pie chart tooltip example
			  var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
			  var previousPoint = null;
			
			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + item.series['percent']+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}
				
			 });
			
				/////////////////////////////////////
				$(document).one('ajaxloadstart.page', function(e) {
					$tooltip.remove();
				});
			
			
			
			
				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}
			
				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}
			
				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}
				
			
				var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
				$.plot("#sales-charts", [
					{ label: "Domains", data: d1 },
					{ label: "Hosting", data: d2 },
					{ label: "Services", data: d3 }
				], {
					hoverable: true,
					shadowSize: 0,
					series: {
						lines: { show: true },
						points: { show: true }
					},
					xaxis: {
						tickLength: 0
					},
					yaxis: {
						ticks: 10,
						min: -2,
						max: 2,
						tickDecimals: 3
					},
					grid: {
						backgroundColor: { colors: [ "#fff", "#fff" ] },
						borderWidth: 1,
						borderColor:'#555'
					}
				});
			
			
				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
				$('.dialogs,.comments').ace_scroll({
					size: 300
			    });
				
				
				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				});
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});
			
			})
		</script>
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
