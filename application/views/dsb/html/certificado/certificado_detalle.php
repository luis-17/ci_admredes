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
									<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
														CERTIFICADO
												</a>
											</div>

										<div class="panel-collapse collapse" id="faq-2-1">
											<div class="panel-body">

													<h4 class="blue">
														<i class="ace-icon glyphicon glyphicon-check bigger-110"></i>
														Datos del Certificado
													</h4>
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

										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													CONTRATANTE
												</a>
											</div>

											<div class="panel-collapse collapse" id="faq-2-2">
												<div class="panel-body">
												</div>
											</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-3" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													AFILIADOS
												</a>
											</div>
											

											<div class="panel-collapse collapse" id="faq-2-3">
												<div class="panel-body">
													<h4 class="blue">
														<i class="blue ace-icon fa fa-users bigger-110"></i>
														Listado de Afiliados al Certificado
													</h4>
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
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-4" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													COBROS
												</a>
											</div>
											<div class="panel-collapse collapse" id="faq-2-4">
												<div class="panel-body">
													<div class="col-xs-12">
														<h4 class="blue">
															<i class="blue ace-icon fa fa-credit-card bigger-110"></i>
															Cobros Registrados
														</h4>
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
												</div>								
											</div>
										</div>
									</div>
								<?php endforeach; ?>
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
