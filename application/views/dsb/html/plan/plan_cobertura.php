<html lang="en"><head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="utf-8">
		<title>Tables - Ace Admin</title>

		<meta name="description" content="Static &amp; Dynamic Tables">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css">

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
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							Plan: <?=str_replace("%20"," ",$nom);?>						
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="form" name="form" role="form" method="post" action="<?=base_url()?>index.php/guardar_cobertura">
									<input type="hidden" id="nom" name="nom" value="<?=$nom;?>">
									<input type="hidden" name="idplan" id="idplan" value="<?=$id?>">
									<input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$iddet?>" />
									<?php 
									if($iddet==0):
										$item=0;
										$desc="";
										$visible=2;
										$style='disabled';
										$chk='';
										$op='';
										$val='';
										else:
											foreach ($detalle as $det) :
												$item=$det->idvariableplan;
												$desc=$det->texto_web;
												$visible=$det->visible;
												$flg=$det->flg_liquidacion;
												$op=$det->simbolo_detalle;
												$val=$det->valor_detalle;
												if($flg=='Sí'):
													$chk="checked";
													$style='';
												else:
													$style='disabled';
													$chk='';
												endif;
											endforeach;
									endif;
									?>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Item: </label>

										<div class="col-sm-9">
											<select id="item" name="item" value="" class="col-xs-10 col-sm-5">
												<option value="">Seleccionar</option>
												<?php foreach($items as $i): 
													if($i->idvariableplan==$item):
														$esti="selected";
														else:
															$esti="";
													endif;?>
												<option value="<?=$i->idvariableplan;?>" <?=$esti;?> ><?=$i->nombre_var;?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Descripción: </label>

										<div class="col-sm-9">
											<input type="text" id="descripcion" name="descripcion" class="col-xs-10 col-sm-5" value="<?=$desc;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Visible en redes: </label>

										<div class="col-sm-9">
											<select id="visible" name="visible" value="" class="col-xs-10 col-sm-5">
												<option <?php if($visible==1): echo "selected"; endif; ?> value="1">Sí</option>
												<option <?php if($visible==0): echo "selected"; endif; ?> value="0">No</option>
											</select>
										</div>
									</div>

									<div class="form-group">										
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1"><input type="checkbox" name="flag" id="flag
											" <?=$chk;?> onchange="habilitar(this.checked);"> Validación:</label><input type="hidden" name="flg_liqui" value="No">

										<div class="col-sm-9">
											<input type="text" name="operador" id="operador
											" <?=$style;?> placeholder="Operador" value="<?=$op;?>">
											<input type="text" name="valor" id="valor
											" <?=$style;?> placeholder="Valor" value="<?=$val;?>">
										</div>
									</div>
										<div class="col-md-offset-3 col-md-9" align="center">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>
								</form>

							<div class="tabbable">
									<!-- #section:pages/faq -->
									<div class="col-xs-12">
													<table id="simple-table" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
														<thead>
															<tr>
																<th></th>
																<th>Item</th>
																<th>Descripción</th>
																<th>Visible</th>
																<th>Validación</th>
																<th>Estado</th>
																<th></th>
															</tr>
														</thead>

														<tbody style="font-size: 12px;">
														<?php $cont=0;
														foreach($cobertura as $c):
															if($c->visible==1):
																$visible='Sí';
																else:
																	$visible='No';
															endif;
															if($c->estado_pd==1):
																$estado="Activo";
																$titulo="Anular Cobertura";
																$boton='ace-icon glyphicon glyphicon-trash';
																$funcion="cobertura_anular";
																else:
																	$estado="Inactivo";
																	$titulo="Activar Cobertura";
																	$boton='ace-icon glyphicon glyphicon-ok';
																	$funcion="cobertura_activar";
															endif;
															$cont=$cont+1;
														?>
															<tr>
																<td align="right"><?=$cont;?></td>
																<td><?=$c->nombre_var;?></td>
																<td><?=$c->texto_web;?></td>
																<td><?=$visible?></td>
																<td><?=$c->flg_liquidacion;?>: <?=$c->simbolo_detalle;?> <?=$c->valor_detalle;?></td>
																<td><?=$estado;?></td>
																<td>
																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<div title="Editar Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/seleccionar_cobertura/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>" data-fancybox-width="950" data-fancybox-height="300">
																							<i class="ace-icon fa fa-pencil bigger-120"></i>
																						</a>
																					</div>
																				</li>
																				<li>
																					<div title="<?=$titulo?>" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a href="<?=base_url()?>index.php/<?=$funcion?>/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>">
																						<i class="<?=$boton?>"></i>
																						</a>
																					</div>
																				</li>
																			</ul>
																		</div>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
								<!-- end table -->
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

		<script type="text/javascript">
			function habilitar(value)
			{
				if(value==true)
				{
					// habilitamos
					document.form.valor.disabled=false;
					document.form.operador.disabled=false;
					document.form.flg_liqui.value='Sí';
				}else{
					// deshabilitamos
					document.form.operador.disabled=true;
					document.form.operador.value='';
					document.form.valor.disabled=true;
					document.form.valor.value='';
					document.form.flg_liqui.value='No';
				}
			}
		</script>

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