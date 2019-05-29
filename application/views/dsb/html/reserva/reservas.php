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

							<li class="active">
								Reservas
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Reservas
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<!-- <div class="col-xs-4">
								<div class="alert alert-info">
									Sr. / Sra. / Srta. (Nombre del afiliado) lo / la saluda (Nombre y Apellido de quien lo está atendiendo) de RED SALUD, lo estoy llamando por la atención médica que solicitó hace algunos minutos.
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									<p>Sr. / Sra. / Srta. (Nombre del afiliado) su atención ya ha sido coordinada. Brindar fecha, hora, tipo de atención coordinada y nombre del establecimiento de salud. Sr. / Sra. / Srta. (Nombre del afiliado) debe tener en cuenta las siguientes recomendaciones:</p>
									<ul>
										<li>Su consulta médica está cubierta al (xxx) % / debe de cancelar xxx soles.</li>
										<li>Al acercarse a (nombre del establecimiento de salud) se debe identificar como AFILIADO RED SALUD y debe de llevar consigo su DNI físico.</li>
										<li>Recuerde que el horario que le estoy brindando es referencial y usted debe de acudir media hora antes a su cita médica a fin de no tener inconvenientes.</li>
										<li>Si Usted no encontrase la totalidad de la receta en la farmacia de el/la (tipo de establecimiento de salud), Usted también puede acercarse con su receta a cualquier Botica Inkafarma a reclamar sus medicamentos, para lo cual deberá comunicarse con nosotros al 445 3019 anexo 104 para coordinar su entrega.</li>
									</ul>
									<p>Además, permítame recordarle los BENEFICIOS con los que cuenta su Plan de Salud.</p>
									<ul>
										<li>(Mencionar los beneficios del Plan en cuanto a Farmacia).</li>
										<li>(Mencionar los beneficios del Plan en cuanto a Laboratorio Clínico).</li>
										<li>(Mencionar los beneficios del Plan en cuanto a Imágenes).</li>
									</ul>
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									Sr. / Sra. / Srta. (Nombre del afiliado) ¿Tiene alguna consulta, respecto a los beneficios que le acabo de mencionar?
								</div>
								<div class="alert alert-danger">
														Respuesta del Afiliado…Si tiene consultas, absolverlas y luego seguir con el siguiente paso.
								</div>
							</div> -->
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Por Confimar
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Confirmadas
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Otras Reservas
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
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha y Hora</th>
																<th>N° DNI</th>
																<th>Afiliado</th>																
																<th>Tiempo</th>
																<th>Estado</th>
																<th></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($mis_reservas as $mr) { 
																switch ($mr->estado_cita):
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
															?>
															<tr>
																<td><?=$mr->idcita?></td>
																<td>PO<?=$mr->num_orden_atencion?></td>
																<td><?=$mr->nombre_plan?></td>
																<td><?=$mr->nombre_comercial_pr?></td>
																<td><?=$mr->fecha?></td>
																<td><?=$mr->aseg_numDoc?></td>
																<td><?=$mr->afiliado?></td>
																<td><?=$mr->tiempo?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																				<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$mr->idcertificado?>/<?=$mr->idasegurado?>/<?=$mr->idcita?>/<?=$mr->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-pencil bigger-120"></i>
																					</a>
																				</div>

																				<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$mr->idcita?>/<?=$mr->idasegurado?>/<?=$mr->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
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
																						<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$mr->idcertificado?>/<?=$mr->idasegurado?>/<?=$mr->idcita?>/<?=$mr->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-pencil bigger-120"></i>
																							</a>
																						</div>
																					</li>
																					<li>
																						<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$mr->idcita?>/<?=$mr->idasegurado?>/<?=$mr->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
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


										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>ID</th>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha y Hora</th>
																<th>N° DNI</th>
																<th>Afiliado</th>
																<th>Teléfono</th>	
																<th>Confirmado por</th>
																<th>Estado</th>
																<th></th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach ($reservas_confirmadas as $or) {
																	if($or->notifica_afiliado==0){
																		$color = "red";
																	} else{
																		$color = "blue";
																	}
																switch ($or->estado_cita):
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
															?>
															<tr>
																<td><?=$or->idcita?></td>
																<td>PO<?=$or->num_orden_atencion?></td>
																<td><?=$or->nombre_plan?></td>
																<td><?=$or->nombre_comercial_pr?></td>
																<td><?=$or->fecha?></td>
																<td><?=$or->aseg_numDoc?></td>
																<td><?=$or->afiliado?></td>
																<td><?=$or->aseg_telf?></td>
																<td><?=$or->username?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																				<div title="Notificar al afiliado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/notificacion_afiliado/<?=$or->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon glyphicon glyphicon-headphones bigger-120 <?=$color?>"></i>
																					</a>
																				</div>	
																				<div title="Reenviar Email Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_proveedor/<?=$or->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-envelope bigger-120 blue"></i>
																					</a>
																				</div>	+
																				<div title="Reenviar Email Afiliado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_afiliado/<?=$or->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-envelope-o bigger-120 blue"></i>
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
																						<div title="Reenviar Email Proveedor" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_proveedor/<?=$or->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-envelope bigger-120"></i>
																							</a>
																						</div>
																					</li>
																					<li>	
																						<div title="Reenviar Email Afiliado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reenviar_afiliado/<?=$or->idcita;?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-envelope-o bigger-120"></i>
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
												</div>
												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example1').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
												<!-- end table -->
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<!-- star table -->		
											<div class="col-xs-12">

												<table id="example2" class="table table-striped table-bordered table-hover">
													<thead>
															<tr>
																<th>N° Orden</th>
																<th>Plan</th>
																<th>Proveedor</th>
																<th>Fecha y Hora</th>
																<th>N° DNI</th>
																<th>Afiliado</th>	
																<th>Reservado por</th>
																<th>Estado</th>
																<th>Tiempo</th>
																<th></th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach ($otras_reservas as $or) {
																switch ($or->tipo2):
																	case 2:
																		$estadoa='Reserva Por Confirmar';
																		$e1=1;
																		$class="label label-warning label-white middle";
																	break;
																	case 1:
																		$estadoa='Reserva Por Gestionar';
																		$e1=2;
																		$class="label label-danger label-white middle";
																	break;
																endswitch;
															?>
															<tr>
																<td><i class="<?=$or->tipo?>"></i> PO<?=$or->num_orden_atencion?></td>
																<td><?=$or->nombre_plan?></td>
																<td><?=$or->nombre_comercial_pr?></td>
																<td><?=$or->fecha?></td>
																<td><?=$or->aseg_numDoc?></td>
																<td><?=$or->afiliado?></td>
																<td><?=$or->username?></td>
																<td><?=$or->tiempo?></td>
																<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>
																<td>
																	<?php if($or->tipo2==1){?>
																	<div class="hidden-sm hidden-xs btn-group">
																				<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$or->idcertificado?>/<?=$or->idasegurado?>/<?=$or->idcita?>/<?=$or->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-pencil bigger-120"></i>
																					</a>
																				</div>

																				<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$or->idcita?>/<?=$or->idasegurado?>/<?=$or->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
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
																						<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/reservar_cita/<?=$or->idcertificado?>/<?=$or->idasegurado?>/<?=$or->idcita?>/<?=$or->idcertificadoasegurado?>/null/0" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-pencil bigger-120"></i>
																							</a>
																						</div>
																					</li>
																					<li>
																						<div title="Anular Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/anular_cita/<?=$or->idcita?>/<?=$or->idasegurado?>/<?=$or->idcertificado?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon glyphicon glyphicon-trash bigger-120"></i>
																							</a>
																						</div>

																					</li>																					
																				</ul>
																			</div>
																		</div>
																	<?php } ?>
																</td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->

												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example2').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>
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