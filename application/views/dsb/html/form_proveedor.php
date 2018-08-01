<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Form Proveedor</title>
		<?php $titulo="Editar Proveedor"; ?>

		<meta name="description" content="and Validation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

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

	<body class="no-skin">
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="widget-box">
									<div class="widget-header widget-header-blue widget-header-flat">
										<h4 class="widget-title lighter"><?php echo $titulo ?></h4>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<!-- #section:plugins/fuelux.wizard -->
											<div id="fuelux-wizard-container">
												<div>
													<!-- #section:plugins/fuelux.wizard.steps -->
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Datos</span>
														</li>

														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Contactos</span>
														</li>

														<li data-step="3">
															<span class="step">3</span>
															<span class="title">Usuario</span>
														</li>
													</ul>

													<!-- /section:plugins/fuelux.wizard.steps -->
												</div>

												<hr />

												<!-- #section:plugins/fuelux.wizard.container -->
												<div class="step-content pos-rel">
													<div class="step-pane active" data-step="1">
													<?php 
														foreach ($data_general as $dg):
															$id= $dg->idproveedor;
															$idt1=$dg->idtipoproveedor;
															$ruc=$dg->numero_documento_pr;
															$sunasa=$dg->cod_sunasa_pr;
															$raz_social=$dg->razon_social_pr;
															$nom=$dg->nombre_comercial_pr;
															$direc=$dg->direccion_pr;
															$ref= $dg->referencia_pr;
															$dep=$dg->cod_departamento_pr;
															$prov=$dg->cod_provincia_pr;
															$dist=$dg->cod_distrito_pr;
														endforeach;
													?>
														<form class="" id="" action="">
														<input type="hidden" name="id_dg" id="id_dg" value="<?php echo $id;?>">
															<!-- #section:elements.form.input-state -->
															<!-- /section:elements.form.input-state -->
															<div class="form-group">
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">Tipo Proveedor:</label>
																		<select class="input-medium valid" id="tipoproveedor" name="tipoproveedor" aria-required="true" aria-invalid="false" aria-describedby="platform-error">
																			<?php foreach ($data_tipoproveedor as $tpr):
																			$idt2=$tpr->idtipoproveedor;
																				if($idt1==$idt2):
																					$est="selected";
																				else:
																					$est="";
																				endif;
																			?>
																			<option value="<?php echo $idt2;?>" <?php echo $est;?>><?php echo $tpr->descripcion_tpr;?></option>
																		<?php endforeach; ?>
																		</select>
																</span>
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">RUC:</label>
																		<input type="text" id="ruc" name="ruc" class="col-xs-12 col-sm-9" value="<?php echo $ruc; ?>">
																</span>	
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">Cod. SUNASA:</label>
																		<input type="text" id="codigosunasa" name="codigosunasa" class="col-xs-12 col-sm-9" value="<?php echo $sunasa;?>">
																</span>												
															</div>								
															<div class="form-group">
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-28" for="name">Razón Social:</label>
																		<input type="text" id="razonsocial" name="razonsocial" class="col-xs-12 col-sm-28" value="<?php echo $raz_social;?>">
																</span>	
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-28" for="name">Nombre Comercial:</label>
																		<input type="text" id="nombrecomercial" name="nombrecomercial" class="col-xs-12 col-sm-28" value="<?php echo $nom?>">
																</span>														
															</div>
															<div class="form-group">
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-28" for="name">Dirección:</label>
																		<input type="text" id="direccion" name="direccion" class="col-xs-12 col-sm-28" value="<?php echo $direc;?>">
																</span>	
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-28" for="name">Referencia:</label>
																		<input type="text" id="referencia" name="referencia" class="col-xs-12 col-sm-28" value="<?php echo $ref?>">
																</span>														
															</div>
															<div class="form-group">
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">Departamento:</label>
																		<select class="input-medium valid" id="departamento" name="departamento" aria-required="true" aria-invalid="false" aria-describedby="platform-error">
																		<option value="" onchange="<?=  base_url()?>pr_provincia/<?=$dep?>">Seleccionar</option>
																			<?php foreach ($departamento as $d):
																			$cod=$d->iddepartamento;
																				if($dep==$cod):
																					$estd="selected";
																				else:
																					$estd="";
																				endif;
																			?>
																			<option value="<?php echo $cod;?>" <?php echo $estd;?>><?php echo $d->descripcion_ubig;?></option>
																		<?php endforeach; ?>
																		</select>
																</span>
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">Provincia:</label>
																		<select class="input-medium valid" id="provincia" name="provincia" aria-required="true" aria-invalid="false" aria-describedby="platform-error" >
																		<option value="">Seleccionar</option>
																			<?php foreach ($provincia as $p):
																			$cod2=$p->idprovincia;
																				if($prov==$cod2):
																					$estp="selected";
																				else:
																					$estp="";
																				endif;
																			?>
																			<option value="<?php echo $cod2;?>" <?php echo $estp;?>><?php echo $p->descripcion_ubig;?></option>
																		<?php endforeach; ?>
																		</select>
																</span>	
																<span class="input-icon input-icon-right">	
																		<label class="control-label col-xs-9 col-sm-9" for="name">Distrito:</label>
																		<input type="text" id="distrito" name="distrito" class="col-xs-12 col-sm-9" value="<?php ?>">
																</span>												
															</div>				
														</form>
													</div>

													<div class="step-pane" data-step="2">
														<div class="center">																
															<h3 class="lighter block green">Contacto</h3>
															<form class="form-horizontal" id="sample-form">
															<!-- #section:elements.form.input-state -->
															<!-- /section:elements.form.input-state -->
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Usuario:</label>
																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<input type="text" id="Usuario" name="usuario" class="col-xs-12 col-sm-5">
																	</div>
																</div>																
															</div>
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Contraseña:</label>
																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<input type="text" id="contrasena" name="contrasena" class="col-xs-12 col-sm-5">
																	</div>
																</div>																
															</div>
														</form>
														</div>
													</div>


													<div class="step-pane" data-step="3">
														<div class="center">
															<h3 class="lighter block green">Usuario</h3>

														<form class="form-horizontal" id="sample-form">
															<!-- #section:elements.form.input-state -->
															<!-- /section:elements.form.input-state -->
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Usuario:</label>
																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<input type="text" id="Usuario" name="usuario" class="col-xs-12 col-sm-5">
																	</div>
																</div>																
															</div>
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Contraseña:</label>
																<div class="col-xs-12 col-sm-9">
																	<div class="clearfix">
																		<input type="text" id="contrasena" name="contrasena" class="col-xs-12 col-sm-5">
																	</div>
																</div>																
															</div>
														</form>
														</div>
													</div>
												</div>

												<!-- /section:plugins/fuelux.wizard.container -->
											</div>

											<hr />
											<div class="wizard-actions">
												<!-- #section:plugins/fuelux.wizard.buttons -->
												<button class="btn btn-prev" type="submit">
													<i class="ace-icon fa fa-arrow-left"></i>
													Anterior
												</button>

												<button type="submit" class="btn btn-success btn-next" data-last="Finalizar">
													Siguiente
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>

												<!-- /section:plugins/fuelux.wizard.buttons -->
											</div>

											<!-- /section:plugins/fuelux.wizard -->
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div>

								<div id="modal-wizard" class="modal">
									<div class="modal-dialog">
										<div class="modal-content">
											<div id="modal-wizard-container">
												<div class="modal-header">
													<ul class="steps">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Datos</span>
														</li>

														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Contactos</span>
														</li>

														<li data-step="4">
															<span class="step">3</span>
															<span class="title">Usuario</span>
														</li>
													</ul>
												</div>

												<div class="modal-body step-content">
													<div class="step-pane active" data-step="1">
														<div class="center">
															<h4 class="blue">Step 1</h4>
														</div>
													</div>

													<div class="step-pane" data-step="2">
														<div class="center">
															<h4 class="blue">Step 2</h4>
														</div>
													</div>

													</div>

													<div class="step-pane" data-step="3">
														<div class="center">
															<h4 class="blue">Step 3</h4>
														</div>
													</div>
												</div>
											</div>

											<div class="modal-footer wizard-actions">
												<button class="btn btn-sm btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Prev
												</button>

												<button class="btn btn-success btn-sm btn-next" data-last="Finish">
													Next
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>

												<button class="btn btn-danger btn-sm pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Cancel
												</button>
											</div>
										</div>
									</div>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

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
		<script src="<?=  base_url()?>public/assets/js/fuelux/fuelux.wizard.js"></script>
		<script src="<?=  base_url()?>public/assets/js/jquery.validate.js"></script>
		<script src="<?=  base_url()?>public/assets/js/additional-methods.js"></script>
		<script src="<?=  base_url()?>public/assets/js/bootbox.js"></script>
		<script src="<?=  base_url()?>public/assets/js/jquery.maskedinput.js"></script>
		<script src="<?=  base_url()?>public/assets/js/select2.js"></script>

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
