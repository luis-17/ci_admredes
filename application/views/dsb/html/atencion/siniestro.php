<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
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
		
		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('keyup', '.txtCal', function () {
			       var calculated_total_sum = 0;
			     
			       $("#tblGasto .txtCal").each(function () {
			           var get_textbox_value = $(this).val();
			           
			           
			           if ($.isNumeric(get_textbox_value)) {
			              calculated_total_sum += parseFloat(get_textbox_value);
			              }

			            });
			              $("#total_sum_value").html(calculated_total_sum);
			              $("#total").val(calculated_total_sum);
			              
			       });

			});

		</script>

		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('keyup','.txtCal', function () {
			       var calculated_total_sum2 = 0;
			     
			       $("#tblGasto .txtCal2").each(function () {
			           var get_textbox_value2 = $(this).val();
			           
			           
			           if ($.isNumeric(get_textbox_value2)) {
			              calculated_total_sum2 += parseFloat(get_textbox_value2);
			              }

			            });
			              $("#total_sum_value_neto").html(calculated_total_sum2);
			              $("#total_neto").val(calculated_total_sum2);
			              
			       });
			});

		</script>

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin" onload="cargar()">
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

							<li>
								<a href="<?=base_url()?>index.php/atenciones">Atenciones</a>
							</li>

							<li class="active">
								Siniestro
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Siniestro: OA<?=$num_orden?>							
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
												Exámen Físico
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Diagnóstico y Tratamiento
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Otros Servicios
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-4">
												Reconsulta
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-5">
												Registro del Gasto
											</a>
										</li>
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">
											<h4 class="blue">
												<i class="ace-icon fa fa-briefcase bigger-110"></i>
												Exámen Físico
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">


											<!-- FORMULARIO DE EXAMEN FISICO -->

												<form action="<?=base_url()?>index.php/guardaTriaje" method="post">
													<input type="hidden" class="form-control" name="idtriaje" id="idtriaje" value="<?php echo $idtriaje?>">
													<input type="hidden" class="form-control" name="idsiniestro" id="idsiniestro" value="<?php echo $idsiniestro?>">
													<input type="hidden" class="form-control" name="idasegurado" id="idasegurado" value="<?php echo $idasegurado?>">
													<input type="hidden" name="num_oa" id="num_oa" value="<?=$num_orden?>">

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												  	  <b class="text-primary">Especialidad:</b>
												      <select name="idespecialidad" id="idespecialidad" class="form-control"><?php if (count($especialidad)) {
														    foreach ($especialidad as $list) {
														    	if ($list['idespecialidad']==$idespecialidad){
																	
																echo "<option value='". $list['idespecialidad']."'selected>" . $list['nombre_esp']."</option>";	
														    	}else{

														    	echo "<option value='". $list['idespecialidad']."'>" . $list['nombre_esp']."</option>";			
														    	} 
														    }
														} ?>
												      </select>
												    </div>
												    <div class="form-group col-md-6">
												      <b class="text-primary">Motivo de Consulta:</b>
												      <input type="text" class="form-control" name="motivo_consulta" id="motivo_consulta" value="<?php echo $motivo_consulta?>">
												    </div>											    
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-4">
													    <b class="text-primary">PA (Presión Arterial)</b>
													    <input type="text" class="form-control" name="presion_arterial_mm" id="presion_arterial_mm" value="<?php echo $presion_arterial_mm?>">
													</div>

													<div class="form-group col-md-4">
													    <b class="text-primary">FC (Frecuencia Cardiaca) </b>
													    <input type="text" class="form-control" name="frec_cardiaca" id="frec_cardiaca" value="<?php echo $frec_cardiaca?>">
													</div>

													<div class="form-group col-md-4">
													    <b class="text-primary">FR (Frecuencia Respiratoria)</b>
													    <input type="text" class="form-control" name="frec_respiratoria" id="frec_respiratoria" value="<?php echo $frec_respiratoria?>">
													</div>
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-2">
													    <b class0="text-primary">Peso (en Kg)</b>
													    <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $peso?>">
													</div>

													<div class="form-group col-md-2">
													    <b class="text-primary">Talla</b>
													    <input type="text" class="form-control" name="talla" id="talla" value="<?php echo $talla?>">
													</div>

													<div class="form-group col-md-8">
													    <b class="text-primary">Cabeza</b>
													    <input type="text" class="form-control" name="estado_cabeza" id="estado_cabeza" value="<?php echo $estado_cabeza?>">
													</div>
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
													    <b class="text-primary">Piel y Faneras</b>
													    <input type="text" class="form-control" name="piel_faneras" id="piel_faneras" value="<?php echo $piel_faneras?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
													    <b class="text-primary">CV:CR (Cardiovascular : Ruidos Cardiacos)</b>
													    <input type="text" class="form-control" name="cv_ruido_cardiaco" id="cv_ruido_cardiaco" value="<?php echo $cv_ruido_cardiaco?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
													    <b class="text-primary">TP:MV (Tórax y pulmones: Murmullo Vesicular)</b>
													    <input type="text" class="form-control" name="tp_murmullo_vesicular" id="tp_murmullo_vesicular" value="<?php echo $tp_murmullo_vesicular?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
													    <b class="text-primary">Abdomen</b>
													    <input type="text" class="form-control" name="estado_abdomen" id="estado_abdomen" value="<?php echo $estado_abdomen?>">
													</div>

													<div class="form-group col-md-6">
													    <b class="text-primary">RHA (Ruidos hidroaéreos)</b>
													    <input type="text" class="form-control" name="ruido_hidroaereo" id="ruido_hidroaereo" value="<?php echo $ruido_hidroaereo?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
													    <b class="text-primary">Neuro</b>
													    <input type="text" class="form-control" name="estado_neurologico" id="estado_neurologico" value="<?php echo $estado_neurologico?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
													    <b class="text-primary">Osteomuscular</b>
													    <input type="text" class="form-control" name="estado_osteomuscular" id="estado_osteomuscular" value="<?php echo $estado_osteomuscular?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
													    <b class="text-primary">GU:PPL (Genito-Urinario: Puño percusion lumbar.)</b>
													    <input type="text" class="form-control" name="gu_puno_percusion_lumbar" id="gu_puno_percusion_lumbar" value="<?php echo $gu_puno_percusion_lumbar?>">
													</div>

													<div class="form-group col-md-6">
													    <b class="text-primary">GU:PRU (Genito-Urinario: Puntos Renouretelares)</b>
													    <input type="text" class="form-control" name="gu_puntos_reno_uretelares" id="gu_puntos_reno_uretelares" value="<?php echo $gu_puntos_reno_uretelares?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												    	<button type="submit" class="btn btn-info">Guardar</button>
														<button type="cancel" class="btn btn-info">Cancelar</button>
													</div>																								
												  </div>
												</form>
											</div>
										</div>

									

										<div id="faq-tab-2" class="tab-pane fade">
											<h4 class="blue">
												<i class="green ace-icon fa fa-pencil-square-o bigger-110"></i>
												Diagnósticos
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Principal
														</a>
													</div>

												<div class="panel-collapse collapse" id="faq-2-1">
													<div class="panel-body">
														

													<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th>Diagnostico</th>
																	<th>Tipo</th>
																	
																	<th width="10%" colspan="2">					
																		<div title="Nuevo Diagnóstico" style="float:right;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=  base_url()?>index.php/add_diagnostico/<?=$idsiniestro?>" title="Nuevo Diagnóstico" data-fancybox-width="950" data-fancybox-height="490">
																					Nuevo Diagnóstico
																				</a>
																			</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($diagnostico as $o):
																
																if($o->tipo_diagnostico==1){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td colspan="2" style="font-weight: bold;"><?=$o->dianostico_temp;?></td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">	
																				<a class="boton fancybox" href="<?=base_url()?>index.php/add_tratamiento/<?=$o->idsiniestrodiagnostico?>" title="Nuevo Medicamento" data-fancybox-width="950" data-fancybox-height="490"><i class="ace-icon glyphicon glyphicon-plus bigger-120"></i></a>
																		</div>	
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>index.php/eliminar_diagnostico/<?=$o->idsiniestrodiagnostico?>/<?=$o->idsiniestro?>" title="Eliminar Diagnostico"><i class="ace-icon glyphicon glyphicon-trash bigger-120"></i></a>
																		</div>	
																	</td>
																</tr>

																<?php
																foreach($medicamento as $u):
																
																if($u->idsiniestrodiagnostico==$o->idsiniestrodiagnostico and $u->tipo_tratamiento){ 
																?>
																<tr>
																	<td></td>
																	<td><?=$u->medicamento_temp.' -- Cantidad: '.$u->cantidad_trat.' -- Dosis: '.$u->dosis_trat;?>
																		
																	</td>
																	<td><?php if ($u->tipo_tratamiento==3){echo "<h6 style='color:red;'> No cubierto</h6>";}else{echo "<h6 style='color:green;'>Cubierto</h6>";};?>
																		
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="boton fancybox" href="<?=base_url()?>index.php/edit_medi/<?=$u->idtratamiento?>/<?=$o->idsiniestrodiagnostico?>" title="Editar Medicamento" data-fancybox-width="950" data-fancybox-height="490"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></a>
																		</div>
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="delete" data-confirm="¿Está seguro que desea eliminar este medicamento?" href="<?=base_url()?>index.php/delete_trata/<?=$u->idtratamiento?>/<?=$idsiniestro?>" title="Eliminar Medicamento"><i class="ace-icon glyphicon glyphicon-trash bigger-120"></i></a>
																		</div>
																	</td>
																</tr>
																<?php }
																endforeach; 
																} endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table -->
													</div>
												</div>
											</div>

											<div class="panel panel-default">
												<div class="panel-heading">
														<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Secundario
														</a>
												</div>

											<div class="panel-collapse collapse" id="faq-2-2">
												<div class="panel-body">
													Medicamentos secundarios

													<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th>Diagnostico</th>
																	<th>Tipo</th>
																	<th width="10%" colspan="3">
																		<div title="Nuevo Diagnóstico" style="float:right;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			<a class="boton fancybox" href="<?=  base_url()?>index.php/add_diagnosticoSec/<?=$idsiniestro?>" data-fancybox-width="950" data-fancybox-height="490">
																				Nuevo Diagnóstico
																			</a>
																		</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($diagnostico as $o):
																
																if($o->tipo_diagnostico==3){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td style="font-weight: bold;"><?=$o->dianostico_temp;?></td>
																	<td></td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">	
																				<a class="boton fancybox" href="<?=base_url()?>index.php/add_tratamientoSec/<?=$o->idsiniestrodiagnostico?>" data-fancybox-width="950" data-fancybox-height="490" title="Nuevo Medicamento"><i class="ace-icon glyphicon glyphicon-plus bigger-120"></i></a>
																		</div>	
																	</td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>index.php/eliminar_diagnostico/<?=$o->idsiniestrodiagnostico?>/<?=$o->idsiniestro?>" title="Eliminar Diagnostico"><i class="ace-icon glyphicon glyphicon-trash bigger-120"></i></a>
																		</div>	
																	</td>
																</tr>

																<?php
																foreach($medicamento as $u):																
																if($u->idsiniestrodiagnostico==$o->idsiniestrodiagnostico and $u->tipo_tratamiento){ 
																?>
																<tr>
																	<td></td>
																	<td><?=$u->medicamento_temp.' -- Cantidad: '.$u->cantidad_trat.' -- Dosis: '.$u->dosis_trat;?>
																		
																	</td>
																	<td><?php if ($u->tipo_tratamiento==3){echo "<h6 style='color:red;'> No cubierto</h6>";}else{echo "<h6 style='color:green;'>Cubierto</h6>";};?>
																		
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="boton fancybox" href="<?=base_url()?>index.php/edit_medi/<?=$u->idtratamiento?>/<?=$o->idsiniestrodiagnostico?>" title="Editar Medicamento" data-fancybox-width="950" data-fancybox-height="490"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></a>
																		</div>
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="delete" data-confirm="¿Está seguro que desea eliminar este medicamento?" href="<?=base_url()?>index.php/delete_trata/<?=$u->idtratamiento?>/<?=$idsiniestro?>" title="Eliminar Medicamento"><i class="ace-icon glyphicon glyphicon-trash bigger-120"></i></a>
																		</div>
																	</td>
																</tr>
																<?php }
																endforeach; 
																} endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table -->													
												</div>
											</div>
											</div>			
											</div>
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<?=$texto?>
										</div>

										<div id="faq-tab-4" class="tab-pane fade">
											<h4 class="blue">
												<i class="purple ace-icon fa fa-check-square-o bigger-110"></i>
												Reconsulta
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-4" class="panel-group accordion-style1 accordion-style2">
												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-4-1" data-parent="#faq-list-4" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Presuntivo
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-4-1">
														<div class="panel-body">
														</div>
													</div>
												</div>

												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-4-2" data-parent="#faq-list-4" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Definitivo
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-4-2">
														<div class="panel-body">

														</div>
													</div>
												</div>												
											</div>
										</div>

										<div id="faq-tab-5" class="tab-pane fade">
											<h4 class="blue">
												<i class="purple ace-icon fa fa-check-square-o bigger-110"></i>
												Registro de Gastos Médicos
											</h4>
											<div class="space-8"></div>

										<form action="<?=base_url()?>index.php/guardaGasto" method="post">	
											<div class="table-responsive">
											    <table id = "tblGasto" class="table table-bordered table-striped table-highlight">
											      <thead>
											        <th>Detalle</th>
											        <th>Costo Bruto</th>
											        <th>Proveedor</th>
											        <th>Monto Neto a pagar</th>
											        <th>Nº Factura</th>
											        <th>Aprobar Pago</th>
											      </thead>
											      <tbody>
											      <?php 
											      $cont=0;
											      foreach ($variable as $v){
											      	if($v->liqdetalle_aprovpago==2){
											      		$estado='readonly="readonly"';
											      	}else{
											      		$estado="";
											      	}
											      	$prov=$v->idprov;
											      	?>
											        <tr>
											          <td><p><b class="text-primary"><?=$v->nombre_var;?></b></p><p><?=$v->detalle;?></p> <input type="hidden" id= "idplan" name= "idplan" value="<?php echo $plan_id;?>"></td>
											          	<input type="hidden" name="idplandetalle<?=$cont?>" value="<?=$v->idplandetalle;?>">
											          	<input type="hidden" id= "espe" name= "espe" value="<?php echo $idespecialidad;?>">
											          	<input type="hidden" name="liqdetalleid<?=$cont?>" value="<?=$v->liqdetalleid?>">
											          </td>
											          <td><div class="input-group">
											              <span class="input-group-addon">S/.</span>
											              <input onkeyup="calcular(<?=$cont?>,<?=$v->valor1?>,<?=$v->valor2?>,<?=$v->valor3?>,<?=$v->cobertura?>,<?=$v->copago?>,<?=$v->hasta?>)" type="number" id= "monto<?=$cont?>" name= "monto<?=$cont?>" placeholder="0,00" step="0.01" class="txtCal item1 form-control" value="<?=$v->liqdetalle_monto;?>" <?=$estado?> >
											            </div>
											          </td>          
											          <td> 										          	          
											          	<select name="proveedor<?=$cont?>" class="prov form-control" id= "proveedor<?=$cont?>" <?=$estado?> >
											          		<option value=0>--- Seleccionar Proveedor ---</option>
															<?php if (count($proveedor)) {
																foreach ($proveedor as $u) {
																	if($u['idproveedor']==$prov){
																		$selected="selected";
																	}else{
																		$selected="";
																	}
																	echo "<option value='". $u['idproveedor']."'".$selected.">" . $u['nombre_comercial_pr']."</option>";	
																	}
															}?>
														</select>  
											          </td>
											          <td><div id="sumaNeto" class="input-group">
											          	<span id= "netospan<?=$cont;?>" class="input-group-addon">S/.</span>
											          	<input type="text" id= "neto<?=$cont?>" name= "neto<?=$cont?>" class="form-control txtCal2" placeholder="0,00" value="<?=$v->liqdetalle_neto;?>" <?=$estado?> /></div>
											          </td>
											          <td><input type="text" id= "factura<?=$cont?>" name= "factura<?=$cont?>" class="form-control" placeholder="000-000000" value="<?=$v->liqdetalle_numfact?>" <?=$estado?> /></td>
											          <td><input type="hidden" name="aprovpago<?=$cont?>" id="aprovpago<?=$cont?>" value="<?=$v->liqdetalle_aprovpago?>" />
											          	<input type="hidden" name="estado<?=$cont?>" id="estado<?=$cont?>" value="<?=$v->liqdetalle_aprovpago?>">
											          	<input onclick="aprovpago(<?=$cont?>)" type="checkbox" id="pago<?=$cont?>" name="pago<?=$cont?>" <?=$estado?> <?php if($v->liqdetalle_aprovpago!=0){ echo "checked";} ?> ></td>          
											        </tr>
											        <?php $cont=$cont+1;
											        	  $liqTotal=$v->liquidacionTotal;
											        	  $liqNeto=$v->liquidacionTotal_neto;
											        	  $liq_id=$v->liq_id;
											        } 	
											        ?>
											        <tr>
													    <td align="right"><span><b>TOTAL  :</b></span></td>
													    <td align="right"><b>S/. <span id="total_sum_value"></span></b></td>
													    <input type="hidden" name="cont" id="cont" value="<?=$cont;?>">
													    <input type="hidden" name="liq_id" value="<?=$liq_id?>">
													    <input type="hidden" class="form-control" name="total" id="total" value="<?=$liqTotal?>">
													    <input type="hidden" name="sin_id" value="<?=$idsiniestro?>">
													    <td align="right"><span><b>TOTAL NETO REDSALUD  :</b></span></td>
													    <td align="right"><b>S/. <span id="total_sum_value_neto"></span></b></td>
													    <input type="hidden" class="form-control" name="total_neto" id="total_neto" value="<?=$liqNeto?>">
													</tr>

											      </tbody>
											    </table>
											</div>

											<div class="row">
											  <div class="col-sm-4">
											  	<div class="checkbox">
												 <label><input type="checkbox" id="trigger" name="question" onclick="cambiar(<?=$cont;?>)"> El mismo proveedor factura todo el siniestro.</label>
												 <input type="hidden" id= "presscheck" name= "presscheck" value="1">
												</div>
											  </div>
											  <div class="col-sm-4">
												<div class="form-group" id="hidden_fields1">
													<b class="text-primary">Proveedor:</b>
													<select name="proveedorPrin" class="form-control" id= "proveedorPrin" disabled="true">
										          		<option value="0">--- Seleccionar Proveedor ---</option>
														<?php if (count($proveedor)) {
															foreach ($proveedor as $u) {
																echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																}
														}?>
													</select>
												</div>
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group" id="hidden_fields2">
													<b class="text-primary">Ingrese Nº de Factura:</b>
													<input type="text" class="form-control" value="" id="numFact" name="numFact" placeholder="000-000000" disabled="true">
												</div>	
											  </div>											  
											</div>

											<div class="form-group" id="hidden_fields3">
													<input type="checkbox" id="cerrar_atencion" name="cerrar_atencion" value="1">
													<b class="text-primary">Cerrar Siniestro</b>
											</div>

											<fieldset style="padding-top: 25px;">
												<input type="hidden" class="form-control" name="idsiniestro" id="idsiniestro" value="<?php echo $idsiniestro?>">

												<div class="row">
												  <div class="col-sm-6">
												  	<input class="btn btn-info" name="enviar" type="submit" value="Guardar">
												  </div>
												  

												</div>
											</fieldset>


										</form>
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

		<script type="text/javascript">
			
			function calcular(id,val1,val2,val3,cob,cop,hasta){
				monto_bruto = document.getElementById("monto"+id).value;
				if(monto_bruto==''){
					monto_neto='';
				}else{
					monto_neto=monto_bruto;
					
					if(cop>0){
						if(monto_neto-val2<0){
								monto_neto=0;
							}else{
								monto_neto = monto_neto-val2;
							}					
					}		

					if(cob>0){
						monto_neto = monto_neto*(val1/100);
					}

					if(hasta>0){
						if(monto_neto<val3){
								monto_neto=monto_neto;
							}else{
								monto_neto = val3;
							}	
					}									

					monto_neto = Math.round(monto_neto*100)/100;
				}

				document.getElementById("neto"+id).value=monto_neto;
			}

			function cambiar(cont){
				chk=document.getElementById("presscheck").value;
				if(chk==1){
					document.getElementById("presscheck").value=0;
					for(i=0;i<cont;i++){
						document.getElementById("proveedor"+i).value=0;
						document.getElementById("factura"+i).value="";
						document.getElementById("proveedor"+i).disabled=true;
						document.getElementById("factura"+i).disabled=true;
						document.getElementById("proveedorPrin").disabled=false;
						document.getElementById("numFact").disabled=false;
					}
				}else{
					document.getElementById("presscheck").value=1;
					for(i=0;i<cont;i++){
						document.getElementById("proveedor"+i).disabled=false;
						document.getElementById("factura"+i).disabled=false;
						document.getElementById("proveedorPrin").disabled=true;
						document.getElementById("numFact").disabled=true;
						document.getElementById("proveedorPrin").value=0;
						document.getElementById("numFact").value="";
					}
				}
			}

			function aprovpago(cont){
				
				estado = document.getElementById("aprovpago"+cont).value;

				if(document.getElementById("pago"+cont).checked==true){
					document.getElementById("aprovpago"+cont).value=1;
				}else{
					document.getElementById("aprovpago"+cont).value=0;
				}
				if(estado==2){
					document.getElementById("aprovpago"+cont).value=2;
				}
			}

			function cargar(){
				total= document.getElementById("total").value;
				neto=document.getElementById("total_neto").value;

				document.getElementById("total_sum_value").innerHTML=total;
				document.getElementById("total_sum_value_neto").innerHTML=neto;
			}
		</script>		

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script>
	</body>

</html>
