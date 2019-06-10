<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

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

		<script type="text/javascript">
		$(document).ready(function(){
	        $("#dni").keyup(function () {
	        	$("#dni").each(function () {
		            dni=$('#dni').val();
		            $.post("<?=base_url();?>index.php/planesDni", { dni: dni}, function(data){
		            $("#data").html(data);
		            });
		        });	               
	        })
	    });
		</script>	

		<script>
		   $(document).ready(function() {
		   $(window).keydown(function(event){
		     if(event.keyCode == 13) {
		       event.preventDefault();
		       return false;
		     }
		   });
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
								<a href="<?=base_url()?>">Home</a>
							</li>

							<li class="active">
								Atenciones
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Atenciones
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div align="center">								
								<div class="col-xs-9 col-sm-12">
										<div class="alert alert-info">

											<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_atenciones_buscar2" class="form-horizontal">
												<div class="profile-info-name"> Inicio: </div>
												<div class="profile-info-name">
													<input class="form-control input-mask-date" type="date" id="fechainicio" name="fechainicio" required="Seleccione una fecha de inicio" value="<?=$fecinicio;?>" >
												</div>
												<div class="profile-info-name"> Fin: </div>
												<div class="profile-info-name">
													<input class="form-control input-mask-date" type="date" id="fechafin" name="fechafin" required="Seleccione una fecha de fin" value="<?=$fecfin;?>">														
												</div>
												<div  class="profile-info-name">
													<button type="submit" class="btn btn-info btn-xs" name="accion" value="buscar">Buscar 
														<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
													</button>
												</div>
											</form>	
										</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Órdenes
											</a>
										</li>

										<!-- <li>
											<a data-toggle="tab" href="#faq-tab-2">
												Pre-Órdenes
											</a>
										</li>
 -->
										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Nueva Orden
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
																<th>No Orden</th>
																<th>No Certificado</th>
																<th>Cliente</th>
																<th>Plan</th>
																<th>Fecha</th>
																<th>Centro Médico</th>
																<th>Especialidad</th>
																<th>Asegurado</th>
																<th>Dni</th>
																<th style="width: 5%;">Detalle</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach($ordenes as $o):
																if($o->idcita==''):
																	$cita='No';
																	else:
																	$cita='Sí';
																endif;
																$fecha=$o->fecha_atencion;
																$fecha=date("d/m/Y", strtotime($fecha));
																if($o->liquidacion_estado!=1){
																?>

															<tr>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>>OA<?=$o->num_orden_atencion;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?> id = "cert"><?=$o->cert_num;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->nombre_comercial_cli?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->nombre_plan;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$fecha;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->nombre_comercial_pr;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->nombre_esp;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->asegurado;?></td>
																<td <?php if($o->estado_siniestro==0){ echo "style='color:red;'"; } ?>><?=$o->aseg_numDoc;?></td>
																<td  style="width: 5%;">
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;
																			<a href="<?=base_url()?>index.php/siniestro/<?=$o->idsiniestro?>" title="Detalle Siniestro"><i class="ace-icon fa fa-external-link bigger-120 blue"></i></a>
																		</div>
																		<?php switch ($o->activacion){
																			case 'activar': ?>
																				<div title="Reactivar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;
																						<a onclick="activar(<?=$o->idsiniestro?>,'<?=$o->num_orden_atencion?>')" title="Reactivar Atención"><i class="ace-icon fa fa-unlock bigger-120 blue"></i></a>
																					</div>
																			<?php break;

																			case 'restablecer': ?>
																			<div title="Restablecer Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;
																						<a onclick="restablecer(<?=$o->idsiniestro?>,'<?=$o->num_orden_atencion?>')" title="Restablecer Atención"><i class="ace-icon fa fa-lock bigger-120 blue"></i></a>
																					</div>
																				<?php break;
																			} ?>
																		<?php if($o->estado_siniestro<>0){ ?>
																		<div title="Anular Siniestro" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a onclick="elegir(<?=$o->idsiniestro?>,'<?=$o->num_orden_atencion?>');">
																				<i class="ace-icon glyphicon glyphicon-trash blue"></i>
																			</a>
																		</div>
																		<?php } ?>																		
																	</div>

																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;
																						<a href="<?=base_url()?>index.php/siniestro/<?=$o->idsiniestro?>" title="Detalle Siniestro"><i class="ace-icon fa fa-external-link bigger-120 blue"></i></a>
																								</div>
																				</li>
																				<?php switch ($o->activacion){
																				case 'activar': ?>
																					<li>
																						<div title="Reactivar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;
																							<a onclick="activar(<?=$o->idsiniestro?>,'<?=$o->num_orden_atencion?>')" title="Reactivar Atención"><i class="ace-icon fa fa-unlock bigger-120 blue"></i></a>
																						</div>
																					</li>
																				<?php break;

																				case 'restablecer': ?>
																				<li>
																					<div title="Restablecer Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;
																							<a onclick="restablecer(<?=$o->idsiniestro?>,'<?=$o->num_orden_atencion?>')" title="Restablecer Atención"><i class="ace-icon fa fa-lock bigger-120 blue"></i></a>
																						</div>
																				</li>
																					<?php break;
																				} ?>
																				<?php if($o->estado_siniestro<>0){ ?>
																				<li>
																					<div title="Anular Siniestro" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a href="<?=base_url()?>index.php/anular_siniestro/<?=$o->idsiniestro?>/OA<?=$o->num_orden_atencion?>">
																							<i class="ace-icon glyphicon glyphicon-trash blue"></i>
																						</a>
																					</div>	
																				</li>
																				<?php } ?>																					
																			</ul>
																		</div>
																	</div>
																</td>
															</tr>
														<?php } 
														endforeach; ?>
														</tbody>
													</table>
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


										</div>

										<!-- <div id="faq-tab-2" class="tab-pane fade">	
												<div class="col-xs-12">
													<table id="simple-table" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Nro Pre-Orden</th>
																<th>Nro. Certificado</th>
																<th>Cliente</th>
																<th>Plan</th>
																<th>Fecha</th>
																<th>Centro Médico</th>
																<th>Especialidad</th>
																<th>Asegurado</th>
																<th>DNI</th>
																<th></th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach($preorden as $po):
																if($po->idcita==''):
																	$cita='No';
																	else:
																	$cita='Sí';
																endif;
																$fecha=$po->fecha_atencion;
																$fecha=date("d/m/Y", strtotime($fecha));
																?>

															<tr>
																<td>PO<?=$po->num_orden_atencion;?></td>
																<td><?=$po->cert_num;?></td>
																<td><?=$po->nombre_comercial_cli?></td>
																<td><?=$po->nombre_plan;?></td>
																<td><?=$fecha;?></td>
																<td><?=$po->nombre_comercial_pr;?></td>
																<td><?=$po->nombre_esp;?></td>
																<td><?=$po->asegurado;?></td>
																<td><?=$po->aseg_numDoc;?></td>
																<td>
																	<div>
																		<a href="<?=base_url()?>index.php/orden/<?=$po->idsiniestro?>/O"  title="Generar Orden">
																			<span class="ace-icon glyphicon glyphicon-ok"></span>
																		</a>
																	</div>
																	<div>
																		<a href="<?=base_url()?>index.php/orden/<?=$po->idsiniestro?>/A" title="Anular Pre Orden">
																			<span class="ace-icon glyphicon glyphicon-remove"></span>
																		</a>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
										</div> -->

										<div id="faq-tab-3" class="tab-pane fade">
											<!-- star table -->
										<form id="creaSin" action="<?=base_url()?>index.php/reg_siniestro" method="post">
										<div style="align-content: center;">
											<div class="row">
											  <div class="col-sm-3">
											  	<div class="form-group">
													<b class="text-primary">Ingrese DNI del Afiliado:</b>
													<input type="text" class="form-control" value="" id="dni" name="dni" required autocomplete="off">		
												</div>
											  </div>											  
											  <div class="col-sm-4">
											  </div>
											</div>

											<div id="data"></div>
										</div>
										</form>
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
			window.jQuery || document.write("<script src='<?=base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>
		<script>
			function elegir(id,num) {
				if (confirm('¿Está seguro de anular la atención OA'+num+'?')) {
				location.href="<?=base_url()?>index.php/anular_siniestro/"+id+"/"+num;
				} else {
				alert('Regresar al consolidado de atenciones.');
				}
			}

			function activar(id,num){
				if (confirm('¿Está seguro de reactivar la atención OA'+num+'?')) {
				location.href="<?=base_url()?>index.php/reactivar_siniestro/"+id+"/"+num;
				} else {
				alert('Regresar al consolidado de atenciones.');
				}
			}

			function restablecer(id,num){
				if (confirm('¿Está seguro de restablecer la atención OA'+num+'?')) {
				location.href="<?=base_url()?>index.php/restablecer_siniestro/"+id+"/"+num;
				} else {
				alert('Regresar al consolidado de atenciones.');
				}
			}
		</script>

		
		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script> -->

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<!-- <link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" /> -->

		<!-- <script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script> -->		

	</body>
</html>
