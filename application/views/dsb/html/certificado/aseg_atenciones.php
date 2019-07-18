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

	<body class="no-skin" style="">	
		<!-- /section:basics/sidebar -->
		<div class="main-content">
			<div class="main-content-inner">
			<!-- #section:basics/content.breadcrumbs -->					
				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<div class="modal-header no-padding">
								<div class="table-header">
									Atenciones del Asegurado
								</div>
							</div>
							<div>
								<table id="example" class="table table-striped table-bordered table-hover">
									<thead  style="font-size: 12px;">
										<tr>
											<th>ID</th>
											<th>Num. Orden</th>
											<th>Fecha Atención</th>
											<th>Centro Médico</th>
											<th>Especialidad</th>
											<th>Estado</th>
											<th></th>
										</tr>
									</thead>

									<tbody  style="font-size: 12px;">	
									<?php foreach ($atenciones as $a):
										$e1=0;
										$e2=0;
										if($a->estado_atencion=='P'){
											$atencion="PO".$a->num_orden_atencion;
											$fecha=$a->fecha_cita;
											switch ($a->estado_cita):
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
											$mostrar="S";
										}else{
											$atencion="OA".$a->num_orden_atencion;
											$fecha=$a->fecha_atencion;
											$fecha=date("d-m-Y", strtotime($fecha));
											switch($a->estado_siniestro):
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
											$mostrar="N";
										}?>
																						
										<tr>
											<td><?=$a->idsiniestro?></td>
											<td><?=$atencion?></td>
											<td><?=$fecha?></td>
											<td><?=$a->nombre_comercial_pr;?></td>
											<td><?=$a->nombre_esp;?></td>
											<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
											<td>
												<div class="hidden-sm hidden-xs btn-group">												
													<?php if($e1!=0&&$mostrar=='S'){ ?>
													<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
														<a class="boton fancybox" href="<?=  base_url()?>index.php/reservar_cita/<?=$a->idcertificado?>/<?=$a->idasegurado?>/<?=$a->idcita?>/<?=$a->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon glyphicon glyphicon-pencil bigger-120"></i>
														</a>
													</div>
													<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
														<a class="boton fancybox" href="<?=  base_url()?>index.php/anular_cita/<?=$a->idcita?>/<?=$a->idasegurado?>/<?=$a->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
															<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
														</a>
													</div>
													<?php } ?>
												</div>

												<div class="hidden-md hidden-lg">
													<div class="inline pos-rel">														
													<?php if($e1!=0&&$mostrar=='S'){ ?>
														<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
															<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
														</button>
														<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
															<li>
																<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																	<a class="boton fancybox" href="<?=  base_url()?>index.php/reservar_cita/<?=$a->idcertificado?>/<?=$a->idasegurado?>/<?=$a->idcita?>/<?=$a->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
																		<i class="ace-icon glyphicon glyphicon-pencil bigger-120"></i>
																	</a>
																</div>
															</li>
															<li>			
																<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																	<a class="boton fancybox" href="<?=  base_url()?>index.php/anular_cita/<?=$a->idcita?>/<?=$a->idasegurado?>/<?=$a->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																		<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
																	</a>
																</div>
															</li>
														</ul>													
													<?php } ?>
													</div>
												</div>
											</td>
										</tr>
									<?php endforeach;?>
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
						</div>
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