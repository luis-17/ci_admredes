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
							Proveedor					
							</h1>
						</div>

						<?php if(!empty($contacto)){
							foreach ($contacto as $c2) {
								$nombres = $c2->nombres_cp;
								$apellidos = $c2->apellidos_cp;
								$fijo = $c2->telefono_fijo_cp;
								$anexo = $c2->anexo_cp;
								$movil = $c2->telefono_movil_cp;
								$email = $c2->email_cp;
								$envio = $c2->envio_correo_cita;
								$idcp = $c2->idcontactoproveedor;
								$idp = $c2->idproveedor;
								$idcargo = $c2->idcargocontacto;
							}

							}else{
								$nombres = "";
								$apellidos = "";
								$fijo = "";
								$anexo = "";
								$movil = "";
								$email = "";
								$envio = "";
								$idcp = 0;
								$idp = $id;
								$idcargo = "";
								} ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="form" name="form" role="form" method="post" action="<?=base_url()?>index.php/guardar_contacto">
									<input type="hidden" name="idcp" value="<?=$idcp?>">
									<input type="hidden" name="idp" value="<?=$idp?>">

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name"" for="form-field-1">Cargo: </label>

										<div class="col-xs-12 col-sm-9">
											<select required="true" name="idcargo" id="idcargo">
												<option value="">Seleccionar</option>
												<?php foreach($cargos as $c){
													if($idcargo==$c->idcargocontacto){
														$est="selected";
													}else{
														$est="";
													}?>
													<option <?=$est?> value="<?=$c->idcargocontacto?>"><?=$c->descripcion_ctc?></option>

													<?php } ?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name"" for="form-field-1">Nombres: </label>

										<div class="col-xs-12 col-sm-9">
											<input class="col-xs-12 col-sm-6" type="text" name="nombres" id="nombres" value="<?=$nombres?>" required="true" >
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="form-field-1">Apellidos: </label>

										<div class="col-xs-12 col-sm-9">
											<input class="col-xs-12 col-sm-6" type="text" name="apellidos" id="apellidos" value="<?=$apellidos?>" required="true">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Teléfono Fijo: </label>

										<div class="col-sm-9">
											<input class="col-xs-12 col-sm-4" type="text" name="telf" id="telf	" required="true" value="<?=$fijo?>">
											<input  class="col-xs-9 col-sm-2" type="text" name="anexo" id="anexo" placeholder="Anexo" value="<?=$anexo?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Teléfono Móvil: </label>

										<div class="col-sm-9">
											<input class="col-xs-12 col-sm-6" type="text" name="movil" id="movil" value="<?=$movil?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email: </label>

										<div class="col-sm-9">
											<input class="col-xs-12 col-sm-6" type="text" name="email" id="email" value="<?=$email?>"  required="true">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email de Citas: </label>
										<div  class="col-sm-9">
											<input type="radio" id="envio1" name="envio" value="1" <?php if($envio==1){echo "checked";} ?> required="required">
											<label for="envio1">Sí </label>
											&nbsp;&nbsp;
											<input  type="radio" id="envio2" name="envio" value="2" <?php if($envio==2){echo "checked";} ?> required="required" >
											<label for="envio2">No</label>
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

							<?php 
									if(!empty($contactos)){ ?>

							<div class="tabbable">
									<!-- #section:pages/faq -->
									<div class="col-xs-12">
												<div>
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead  style="font-size: 12px;">
															<tr>
																<th>Cargo</th>
																<th>Nombres</th>
																<th>Apellidos</th>
																<th>Teléfono Fijo/Anexo</th>
																<th>Teléfono Móvil</th>
																<th>Email</th>
																<th>Envía Email</th>
																<th>Estado</th>
																<th></th>
															</tr>
														</thead>

														<?php foreach ($contactos as $c){
															if($c->estado_cp==1){
																$boton= '<a href="'.base_url().'index.php/contacto_anular/'.$c->idcontactoproveedor.'/'.$c->idproveedor.'"><span class="label label-info label-white middle">Activo</span></a>';
																}else{
																	$boton='<a href="'.base_url().'index.php/contacto_activar/'.$c->idcontactoproveedor.'/'.$c->idproveedor.'"><span class="label label-danger label-white middle">Inactivo</span></a>';
																	} ?>
														<tbody  style="font-size: 12px;">
														
															<tr>
																<td><?=$c->descripcion_ctc?></td>
																<td><?=$c->nombres_cp?></td>
																<td><?=$c->apellidos_cp?></td>
																<td><?=$c->telefono_fijo_cp?><?php if($c->anexo_cp!=""){echo " / ".$c->anexo_cp;} ?></td>
																<td><?=$c->telefono_movil_cp?></td>
																<td><?=$c->email_cp?></td>
																<td><?php if($c->envio_correo_cita==2){ echo "NO";} else {echo "SÍ";} ?></td>
																<td><?=$boton?></td>
																<td>

																<div class="hidden-sm hidden-xs btn-group">
																	<div title="Editar Contacto" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/seleccionar_contacto/<?=$c->idcontactoproveedor?>/<?=$c->idproveedor?>" data-fancybox-width="1000" data-fancybox-height="300">
																							<i class="ace-icon fa fa-pencil bigger-120"></i>
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
																					<div title="Editar Contacto" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						<a class="boton fancybox" href="<?=base_url()?>index.php/seleccionar_contacto/<?=$c->idcontactoproveedor?>/<?=$c->idproveedor?>" data-fancybox-width="1000" data-fancybox-height="300">
																							<i class="ace-icon fa fa-pencil bigger-120"></i>
																						</a>
																					</div>
																				</li>
																				
																			</ul>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
														<?php } ?>
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

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>
</body></html>