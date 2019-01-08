<?php
	$user = $this->session->userdata('user');
	extract($user);
?>
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
		<?php include ("/../headBar.php");?>
		<!-- #section:basics/navbar.layout -->
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
							<a href="<?=base_url()?>index">Comprobantes de Pago</a></li>
							<li class="active">Notas de Crédito y Débito</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Notas de Crédito y Débito
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
												Notas de Crédito
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Notas de Débito
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Emitir Documentos
											</a>
										</li>										
									</ul>
									<div class="tab-content no-border padding-24">
						
						<div id="faq-tab-1" class="tab-pane fade in active">
							<div class="space-8"></div>
							<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">


								

									<div class="panel panel-default">
										<div class="panel-heading">
											<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
												<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													NOTAS DE CRÉDITO AUTOMÁTICAS
											</a>
										</div>

										<div class="panel-collapse collapse" id="faq-2-1">
											<div class="panel-body">

												
												<form name="formNotaCredito" id="formNotaCredito" method="post" action='<?=base_url()."index.php/boleta/crearXml/"?>'>

													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row">
															<div class="profile-info-name">Nro. Documento: </div>
															<div class="profile-info-name">
																<input type="text" class="form-control" id="docNotaCredito" name="docNotaCredito" required="Ingrese número de documento" value="<?=$docNotaCredito?>">
															</div>
															<div class="profile-info-name">Certificado: </div>
															<div class="profile-info-name">
																<input type="text" class="form-control" id="certNotaCredito" name="certNotaCredito" required="Ingrese número de certificado" value="<?=$certNotaCredito?>">
															</div>

															<div class="profile-info-name">
																<button type="button" id="buscarCredito" class="btn btn-info btn-xs">Buscar 
																	<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																</button>
															</div>
															<div class="profile-info-name"></div>
														</div>											
													</div>	
													<!---->
													<div id="respCredito"></div>
														<br>
														<div class="form-row">
															<input type='text' class='hidden' id='idcliente' name='idcliente' value=''>
															<input type='text' class='hidden' id='idplan' name='idplan' value=''>
														  	<div class="form-group col-md-9">
														    	<b class="text-primary">Nombre de Cliente</b>
														    	<input type="text" class="form-control" name="nomCliente" id="nomCliente" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">DNI/RUC</b>
														   		<input type="text" class="form-control" name="dniRuc" id="dniRuc" value="" readonly>
															</div>
														</div>

														<div class="form-row">

															<div class="form-group col-md-3">
														    	<b class="text-primary">Fecha de Emisión</b>
														   		<input type="date" class="form-control" name="fechEmiNota" id="fechEmiNota" required="Ingrese importe de la nota de crédito" value="<?=$fechaDoc;?>">
															</div>
														</div>
														<div class="form-row">
															
														  	<div class="form-group col-md-3">
														    	<b class="text-primary">Serie de Nota de Crédito</b>
														    	<input type="text" class="form-control" name="numSerie" id="numSerie" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Correlativo Generado</b>
														   		<input type="text" class="form-control" name="correGen" id="correGen" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe de Nota de Crédito</b>
														   		<input type="number" class="form-control" name="impNota" id="impNota" required="Ingrese importe de la nota de crédito" value="">
															</div>
														</div>

														<div class="form-row">
														  	<div class="form-group col-md-12">
														    	<b class="text-primary">Motivo de Nota de Crédito</b>
														   		<textarea type="textarea" class="form-control" name="motivo" id="motivo" value="" required="Ingrese el motivo de la nota de crédito"></textarea>
															</div>										
														</div>
														
													<!---->
													<div id="resp1"></div>
													<div id="resp2auto"></div>
													
													<div class="form-row" align="center">
													  	<div class="form-group col-md-12">
													  		<hr>
													    	<button type="button" id="buttonGuardarCredito" class="btn btn-info">Guardar</button>
															<button type="button" id="buttonCancelarCredito" class="btn btn-info">Cancelar</button>
														</div>										
													</div>

												</form>
											</div>
										</div>
									</div>

								<div class="panel panel-default">
										<div class="panel-heading">
											<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
												<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
												NOTAS DE CRÉDITO MANUALES
											</a>
										</div>

										<div class="panel-collapse collapse" id="faq-2-2">
											<div class="panel-body">
												
												<form action="" name="formNotaCreditoManual" id="formNotaCreditoManual" method="post">
													<div class="form-row">
														<div id="respCredito"></div>
														<div id="resp2manual"></div>
														<br>
	
														<div class="form-row">
														  	<div class="form-group col-md-3">
														    	<b class="text-primary">Serie de Referencia</b>
														    	<input type="text" class="form-control" name="numSerieRefManual" id="numSerieRefManual" value="">
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Correlativo de Referencia</b>
														   		<input type="number" class="form-control" name="CorreRefManual" id="CorreRefManual" required="Ingrese importe de la nota de crédito" value="">
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Fecha Comprobante de Referencia</b>
														   		<input type="date" class="form-control" name="fechEmiRefManual" id="fechEmiRefManual" required="Ingrese importe de la nota de crédito" value="<?=$fechaDoc;?>">
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe Total</b>
														   		<input type="number" class="form-control" name="impTotalManual" id="impTotalManual" value="">
															</div>
														</div>

														<div class="form-row">
															<input type='text' class='hidden' id='idplanManual' name='idplanManual' value=''>
														  	<div class="form-group col-md-5">
														    	<b class="text-primary">Nombre de Cliente</b>
														    	<input type="text" class="form-control" name="nomClienteManual" id="nomClienteManual" value="">
															</div>

															<div class="form-group col-md-4">
																<b class="text-primary">Nombre de Plan</b>
																<select name="planes" id="planes" required="Seleccione una opción de la lista">
																	<option value=0>Seleccione</option>
																	<?php foreach ($planes as $p):
																		if($idserie==$p->idplan):
																			$estp='selected';
																		else:
																			$estp='';
																		endif;?>
																		<option value="<?=$p->idplan;?>" <?=$estp?> ><?=$p->nombre_comercial_cli." - ".$p->nombre_plan?></option>
																	<?php endforeach; ?>
																</select>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">DNI/RUC</b>
														   		<input type="text" class="form-control" name="dniRucManual" id="dniRucManual" value="">
															</div>
														</div>

														<div class="form-row">

															<div class="form-group col-md-3">
																<b class="text-primary">Serie de Nota de Crédito</b>
																<select name="serieCredito" id="serieCredito" required="Seleccione una opción de la lista">
																	<option value=0>Seleccione</option>
																	<?php foreach ($serieCredito as $sc):
																		if($idserie==$sc->idserie):
																			$estp='selected';
																		else:
																			$estp='';
																		endif;?>
																		<option value="<?=$sc->numero_serie;?>" <?=$estp?> ><?=$sc->numero_serie." - ".$sc->descripcion_ser?></option>
																	<?php endforeach; ?>
																</select>
															</div>
														  	<!--<div class="form-group col-md-3">
														    	<b class="text-primary">Serie de Nota de Crédito</b>
														    	<input type="text" class="form-control" name="numSerieManual" id="numSerieManual" value="">
															</div>-->

															<div class="form-group col-md-3">
														    	<b class="text-primary">Correlativo Generado</b>
														   		<input type="text" class="form-control" name="correGenManual" id="correGenManual" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe de Nota de Crédito</b>
														   		<input type="number" class="form-control" name="impNotaManual" id="impNotaManual" required="Ingrese importe de la nota de crédito" value="">
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Fecha de Emisión</b>
														   		<input type="date" class="form-control" name="fechEmiNotaManual" id="fechEmiNotaManual" required="Ingrese importe de la nota de crédito" value="<?=$fechaDoc;?>">
															</div>
														</div>
														<div class="form-row">
														  	<div class="form-group col-md-12">
														    	<b class="text-primary">Motivo de Nota de Crédito</b>
														   		<textarea type="textarea" class="form-control" name="motivoManual" id="motivoManual" value="" required="Ingrese el motivo de la nota de crédito"></textarea>
															</div>										
														</div>
														
														<div class="form-row" align="center">
														  	<div class="form-group col-md-12">
														    	<button type="button" id="buttonGuardarCreditoManual" class="btn btn-info">Guardar</button>
																<button type="button" id="buttonCancelarCreditoManual" class="btn btn-info">Cancelar</button>
															</div>										
														</div>

												  	</div>
												</form>
											</div>
										</div>
									</div>

							</div>
						</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<div class="space-8"></div>
											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
												<form action="" name="formNotaDebito" id="formNotaDebito" method="post">
													<div class="form-row">

													  	<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">
																<div class="profile-info-name"> Serie: </div>
																<div class="profile-info-name">
																	<select name="serie" id="serie" required="Seleccione una opción de la lista">
																		<option value=0>Seleccione</option>
																		<?php foreach ($serie as $s):
																			if($idserie==$s->idserie):
																				$estp='selected';
																			else:
																				$estp='';
																			endif;?>
																			<option value="<?=$s->numero_serie;?>" <?=$estp?> ><?=$s->numero_serie." - ".$s->descripcion_ser?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="profile-info-name"> Correlativo: </div>
																<div class="profile-info-name">
																	<input type="text" class="form-control" id="correlativoDoc" name="correlativoDoc" required="Ingrese número correlativo" value="<?=$correlativoDoc?>">
																</div>
																<div class="profile-info-name"> Fecha: </div>
																<div class="profile-info-name">
																	<input class="form-control input-mask-date" type="date" id="fechaDoc" name="fechaDoc" required="Seleccione una fecha de inicio" value="<?=$fechaDoc;?>">
																</div>
																<div class="profile-info-name">
																	<button type="button" id="buttonBuscarDebito" class="btn btn-info btn-xs">Buscar 
																		<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																	</button>
																</div>
																<div class="profile-info-name"></div>
															</div>											
														</div>	
														<br>
														<div id="respDebito"></div>
														<div class="form-row">
															<input type='text' class='hidden' id='idclienteD' name='idcliente' value=''>
															<input type='text' class='hidden' id='idplanD' name='idplan' value=''>
														  	<div class="form-group col-md-9">
														    	<b class="text-primary">Nombre de Cliente</b>
														    	<input type="text" class="form-control" name="nomClienteD" id="nomClienteD" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">DNI/RUC</b>
														   		<input type="text" class="form-control" name="dniRucD" id="dniRucD" value="" readonly>
															</div>
														</div>

														<div class="form-row">
														  	<div class="form-group col-md-6">
														    	<b class="text-primary">Serie y Correlativo</b>
														    	<input type="text" class="form-control" name="numDocD" id="numDocD" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe Total</b>
														   		<input type="text" class="form-control" name="impTotalD" id="impTotalD" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Tipo de Moneda</b>
														   		<input type="text" class="form-control" name="tipoMonedaD" id="tipoMonedaD" value="" readonly>
															</div>
														</div>
														<div class="form-group col-md-12">
															<b class="text-primary">COMPLETAR DATOS</b>
														</div>
														<div class="form-row">
														  	<div class="form-group col-md-3">
														    	<b class="text-primary">Serie de Nota de Débito</b>
														    	<input type="text" class="form-control" name="numSeriesD" id="numSeriesD" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Correlativo Generado</b>
														   		<input type="text" class="form-control" name="numCorreD" id="numCorreD" value="" readonly>
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe de Nota de Débito</b>
														   		<input type="number" class="form-control" name="impNotaD" id="impNotaD" required="Ingrese importe de la nota de crédito" value="">
															</div>

															<div class="form-group col-md-3">
														    	<b class="text-primary">Fecha de Emisión</b>
														   		<input type="date" class="form-control" name="fechEmiNotaD" id="fechEmiNotaD" required="Ingrese importe de la nota de crédito" value="<?=$fechaDoc;?>">
															</div>
														</div>
														<div class="form-row">
														  	<div class="form-group col-md-12">
														    	<b class="text-primary">Motivo de Nota de Débito</b>
														   		<textarea type="textarea" class="form-control" name="motivoD" id="motivoD" value="" required="Ingrese el motivo de la nota de crédito"></textarea>
															</div>										
														</div>
														<div class="form-row" align="center">
														  	<div class="form-group col-md-12">
														    	<button type="button" id="buttonGuardarDebito" class="btn btn-info">Guardar</button>
																<button type="button" id="buttonCancelarDebito" class="btn btn-info">Cancelar</button>
															</div>										
														</div>

												  	</div>
												</form>
											</div>
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<div class="space-8"></div>
											<div class="row">
												<div class="col-xs-12">
													<!-- PAGE CONTENT BEGINS -->						
													<div align="center">								
														<div class="col-xs-9 col-sm-12">
															<div class="widget-box transparent">
																	
																<form name="formCategoriaEmitir" id="formCategoriaEmitir" method="post" action='<?=base_url()."index.php/boleta/crearXml/"?>'>

																	<div class="profile-user-info profile-user-info-striped">
																		<div class="profile-info-row">
																			<div class="profile-info-name"></div>
																			<div class="profile-info-name">Tipo de documento: </div>
																			<div class="profile-info-name">
																				<select name="canales" id="canales" required="Seleccione una opción de la lista">
																					<option value=0>Seleccione</option>
																					<?php foreach ($serieDos as $sd):
																						if($idserie==$sd->idserie):
																							$estp='selected';
																						else:
																							$estp='';
																						endif;?>
																						<option value="<?=$sd->numero_serie;?>" <?=$estp?> ><?=$sd->numero_serie." - ". $sd->descripcion_ser?></option>
																					<?php endforeach; ?>
																				</select>
																			</div>
																			<div class="profile-info-name"> Inicio: </div>
																			<div class="profile-info-name">
																				<input class="form-control input-mask-date" type="date" id="fechainicio" name="fechainicio" required="Seleccione una fecha de inicio" value="<?=$fecinicio;?>">
																			</div>
																			<div class="profile-info-name"> Fin: </div>
																			<div class="profile-info-name">
																				<input class="form-control input-mask-date" type="date" id="fechafin" name="fechafin" required="Seleccione una fecha de fin" value="<?=$fecfin;?>">					
																			</div>	

																			<div class="profile-info-name"></div>
																			<div class="profile-info-name">
																				<button type="button" id="buttonBuscarEmision" class="btn btn-info btn-xs">Buscar 
																					<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																				</button>
																			</div>
																			<div class="profile-info-name"></div>
																		</div>											
																	</div>	
																	<div id="resp1"></div>
																	<div id="resp2"></div>

																	<div id="accionesTablaEmitido">
																		<hr>

																		<div class="profile-user-info profile-user-info-striped">
																			<div  class="profile-info-row">
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonExcel" name="buttonExcel" class="btn btn-white btn-info btn-bold btn-xs"> Generar archivo Excel <i class="ace-icon fa fa-file-excel-o bigger-110 icon-only"></i>
																					</button>
																				</div>
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonComprobante" name="buttonComprobante" class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#modalXML"> Emitir Comprobante <i class="ace-icon fa fa-file-code-o bigger-110 icon-only"></i>
																					</button>
																				</div>
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name"></div>
																			</div>
																		</div>
																	</div>				
																	<div id="resp3"></div>

																</form>

																<div class="modal fade" id="modalXML" name="modalXML" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel" aria-hidden="true">
																	  <div class="modal-dialog" role="document">
																	    <div class="modal-content">
																	      <div class="modal-header">
																	        <h5 class="modal-title" id="saveModalLabel">Emitir Comprobantes</h5>
																	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	          <span aria-hidden="true">&times;</span>
																	        </button>
																	      </div>
																	      <div class="modal-body">
																	       ¿Seguro que desea emitir los comprobantes de pago electrónicos?
																	      </div>
																	      <div class="modal-footer">
																	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																	        <button type="button" id="buttonEmitir" name="buttonEmitir" class="btn btn-primary">Aceptar</button>
																	      </div>
																	    </div>
																	  </div>
																	</div>

																<div class="modal fade" id="modalExcel" role="dialog">
																    <div class="modal-dialog">
																        <div class="modal-content">
																            <div class="modal-header">
																                <button type="button" class="close" data-dismiss="modal">&times;</button>
																                <h3 class="modal-title">Se generaró el Excel correctamente.</h3>
																            </div>
																            <div class="modal-footer">
																                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																            </div>
																        </div>
																    </div>
																</div>

																<div class="modal fade" id="modalAnexo" role="dialog">
																    <div class="modal-dialog">
																        <div class="modal-content">
																            <div class="modal-header">
																                <button type="button" class="close" data-dismiss="modal">&times;</button>
																                <h3 class="modal-title">Se envió lista de anexos correctamente.</h3>
																            </div>
																            <div class="modal-footer">
																                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																            </div>
																        </div>

																    </div>
																</div>

															</div>
														</div>
													</div>
												</div>									
												<br/>		
												<br/>
												<br/>		
											</div>
										</div>

									</div>
								<!-- PAGE CONTENT ENDS -->
								</div><!-- /.col -->
							</div><!-- /.row -->
						</div><!-- /.page-content -->


					</div>
				</div>
			</div>

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

		<script>

				$('#myModal').on('shown.bs.modal', function () {
				  $('#myInput').trigger('focus')
				})
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
	    
	    <script>
			$(document).ready(function(){

				//NOTAS DE CRÉDITO Y NOTAS DE DÉBITO
				//función para enviar datos a la tabla dinámica que se va a generar    

				$('#buscarCredito').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarListaNotaCredito",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formNotaCredito").serialize(),
			           	beforeSend: function(){
				            $("#resp2auto").html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
			           	success: function(data)             
			           	{
			           		$("#resp2auto").html(null);
			             	$("#resp2auto").html(data); 
			             	$('#tablaDatos').DataTable({
								"pagingType": "full_numbers"
							});
			           	}
			       	});
			       	return false;
			    });

			    $('#serieCredito').change(function(){
			    	var serie = $("#serieCredito").val();
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarCorrelativo",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: {serie:serie},
			           	success: function(data)             
			           	{
			           		$('#correGenManual').val(data.correlativo);
			           	}
			       	});
			       	return false;
			    });

			     $('#planes').change(function(){
			    	var planes = $("#planes").val();
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarplanes",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: {planes:planes},
			           	success: function(data)             
			           	{
			           		$('#idplanManual').val(data.idplan);
			           	}
			       	});
			       	return false;
			    });

			    $('#buscarCredito').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarDocumentoNotaCredito",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formNotaCredito").serialize(),
			           	success: function(data)             
			           	{
			           		$('#nomCliente').val(data.contratante);
			           		$('#dniRuc').val(data.cont_numDoc);
			           		$('#numSerie').val(data.serie);
			           		$('#correGen').val(data.correlativo);
			           		$('#idcliente').val(data.id_contratante);
			           		$('#idplan').val(data.idplan);
			           	}
			       	});
			       	return false;
			    });

			    $('#buttonCancelarCredito').click(function() {
				    $('#nomCliente').val('');
	           		$('#dniRuc').val('');
	           		$('#numSerie').val('');
	           		$('#correGen').val('');
	           		$('#idcliente').val('');
	           		$('#idplan').val('');
	           		$('#fechEmiNota').date('dd/mm/yyyy');
	           		$("#resp2auto").html(null);
				});

				$('#buttonGuardarCredito').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/guardarDocumentoNotaCredito",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formNotaCredito").serialize(),
			           	beforeSend: function(){
				            $('#resp2auto').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#resp2auto").remove();
					        alert("Nota de Crédito generada.");
					        location.reload();
					    },
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

			    $('#buttonGuardarCreditoManual').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/guardarDocumentoNotaCreditoManual",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formNotaCreditoManual").serialize(),
			           	beforeSend: function(){
				            $('#resp2manual').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#resp2manual").remove();
					        alert("Nota de Crédito generada.");
					        location.reload();
					    },
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });


			    $('#buttonBuscarDebito').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarDocumentoNotaDebito",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formNotaDebito").serialize(),
			           	beforeSend: function(){
				            $('#respDebito').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#respDebito").remove();
					    },
			           	success: function(data)             
			           	{
			           		$('#numDocD').val(data.seriecorrelativo);
			           		$('#nomClienteD').val(data.nombre_comercial_cli);
			           		$('#dniRucD').val(data.numero_documento_cli);
			           		$('#impTotalD').val(data.importe_total);
			           		$('#tipoMonedaD').val(data.tipo_moneda);
			           		$('#numSeriesD').val(data.serie);
			           		$('#numCorreD').val(data.correlativo);
			           		$('#idclienteD').val(data.id_contratante);
			           		$('#idplanD').val(data.idplan);
			           	}
			       	});
			       	return false;
			    });

			    $('#buttonCancelarDebito').click(function() {
				    $('#numDocD').val('');
	           		$('#nomClienteD').val('');
	           		$('#dniRucD').val('');
	           		$('#impTotalD').val('');
	           		$('#tipoMonedaD').val('');
	           		$('#numSeriesD').val('');
	           		$('#numCorreD').val('');
	           		$('#impNotaD').val('');
	           		$('#motivoD').val('');
	           		$('#idclienteD').val('');
	           		$('#idplanD').val('');
	           		$('#serie').val(0);
			        $('#correlativoDoc').val('');
			       	$('#fechEmiNotaD').date('dd/mm/yyyy');
				});

			    $('#buttonGuardarDebito').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/guardarDocumentoNotaDebito",   
			           	type: 'POST',
			           	dataType: 'json',
			           	data: $("#formNotaDebito").serialize(),
			           	beforeSend: function(){
				            $('#respDebito').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#respDebito").remove();
					        alert("Nota de Débito generada.");
					        $('#numDoc').val('');
			           		$('#nomCliente').val('');
			           		$('#dniRuc').val('');
			           		$('#impTotal').val('');
			           		$('#tipoMoneda').val('');
			           		$('#numSerie').val('');
			           		$('#correGen').val('');
			           		$('#impNota').val('');
			           		$('#motivo').val('');
			           		$('#idcliente').val('');
			           		$('#idplan').val('');
			           		$('#serie').val(0);
			           		$('#correlativoDoc').val('');
			           		$('#fechEmiNotaD').date('dd/mm/yyyy');
					    },
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

//-----------------------------------------------------------------------------------------------------------------------
				//EMITIR NOTA DE CRÉDITO Y DÉBITO
				$("#accionesTablaEmitido").hide();
				//función para enviar datos a la tabla dinámica que se va a generar   
			    $('#buttonBuscarEmision').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/mostrarDocumentoGenerado",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoriaEmitir").serialize(),
			           	beforeSend: function(){
				            $("#resp2").html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
			           	success: function(data)             
			           	{
			           		$("#resp2").html(null);
			             	$("#resp2").html(data); 
			             	$("#accionesTablaEmitido").show();
			             	$('#tablaDatos').DataTable({
								"pagingType": "full_numbers"
							});
			           	}
			       	});
			       	return false;
			    });

				$('#buttonExcel').click(function(){

					var fechainicio = $("#fechainicio").val();
			    	var fechafin = $("#fechafin").val();
			    	var canales = $("#canales").val();

			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/generarExcel",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data:  {fechainicio:fechainicio,
			           			fechafin:fechafin,
			           			canales:canales}, 
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

				$(function() {
				    $("#buttonExcel").click(function(){    
				    	//location.reload(true);    
				      $('#modalExcel').modal('show');
				    });
				});

				$(function() {
				    $("#buttonDbf").click(function(){    
				    	//location.reload(true);    
				      $('#modalAnexo').modal('show');
				    });
				});

				$(function() {
				    $("#buttonEmitir").click(function(){    
				    	//location.reload(true);    
				      $('#modalXML').modal('hide');
				    });
				});

				$('#buttonDbf').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/generarArchivoDbf",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(), 
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

			    $('#buttonEmitir').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/notas_cnt/generarXmlNotaCredito",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoriaEmitir").serialize(),
			           	beforeSend: function(){
				            $('#resp1').html("<img src='<?=base_url()."public/assets/img/loading2.gif"?>'><br><br>");
				            //$("#accionesTablaEmitido").hide();
				        },
				        complete:function() {
					        $("#resp1").remove();
					        alert("Comprobante electrónico emitido.");
			    			location.reload();
					    },
					    success: function(data)
					    {

					    }
			       	});
			    	$('#canales').val() == 0;
			       	return false;
			    });

			});
    	</script>

	</body>
</html>