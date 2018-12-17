<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="utf-8">
		<title>Tables - Ace Admin</title>

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css">

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css">

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style">

		
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

			<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>
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
							<?=$accion?>			
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="form" name="form" role="form" method="post" action="<?=base_url()?>index.php/save_producto">
									<input type="hidden" name="idvar" id="idvar" value="<?=$id?>">
									<input type="hidden" name="idproducto" id="idproducto" value="<?=$idprod?>">
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Descripción: </label>
										<div class="col-xs-12 col-sm-9">
											<input type="text" id="descripcion" name="descripcion" value="<?=$descripcion?>"  class="col-xs-12 col-sm-6">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-offset-3 col-md-9" align="center">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>
									</div>
								</form>
							<br>

							<div class="tabbable">
									<!-- #section:pages/faq -->
									<div class="col-xs-12">
										<?php if(!empty($productos)){ ?>
													<table id="example" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
														<thead>
															<tr>
																<th>ID</th>
																<th>Descripción</th>
																<th width="5%"></th>
															</tr>
														</thead>
														<tbody style="font-size: 12px;">
														<?php foreach($productos as $p){ ?>
															<tr>
																<td><?=$p->idproducto?></td>
																<td><?=$p->descripcion_prod?></td>
																<td  width="5%">
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Editar Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a href="<?=base_url()?>index.php/seleccionar_detalle/<?=$p->idproducto?>">
																				<i class="ace-icon fa fa-pencil bigger-120"></i>
																			</a>
																		</div>
																		<div title="Elimiar Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=base_url()?>index.php/delete_producto/<?=$p->idproducto?>/<?=$p->idvariableplan?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
																			</a>
																		</div>							
																	</div>

																		<div class="hidden-md hidden-lg">
																			<div class="inline pos-rel">
																				<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																					<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																				</button>

																				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">	
																						<li>
																							<div title="Editar Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								&nbsp;<a href="<?=base_url()?>index.php/seleccionar_detalle/<?=$p->idproducto?>">
																									<i class="ace-icon fa fa-pencil bigger-120"></i>
																								</a>
																							</div>
																						</li>
																						<li>
																							<div title="Eliminar Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								&nbsp;<a href="<?=base_url()?>index.php/delete_producto/<?=$p->idproducto?>/<?=$p->idvariableplan?>">
																									<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
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
													<?php } ?>
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
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>
</body></html>