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
							<li class="active">Ventas</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Ventas
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
												Ventas
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Comprabantes manuales
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Emitir Boletas y Facturas
											</a>
										</li>									
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">

										<div id="faq-tab-1" class="tab-pane fade in active">
											<div class="space-8"></div>
											<div class="row">
												<div class="col-xs-12">
													<!-- PAGE CONTENT BEGINS -->						
													<div align="center">								
														<div class="col-xs-9 col-sm-12">
															<div class="widget-box transparent">
																	
																<form name="formCategoria" id="formCategoria" method="post" action='<?=base_url()."index.php/ventas"?>'>

																	<div class="profile-user-info profile-user-info-striped">
																		<div class="profile-info-row">

																			<div class="profile-info-name"> Canal: </div>
																			<div class="profile-info-name">
																				<select name="canales" id="canales" required="Seleccione una opción de la lista">
																					<option value=0>Seleccione</option>
																					<?php foreach ($canales as $c):
																						if($idclienteempresa==$c->idclienteempresa):
																							$estp='selected';
																						else:
																							$estp='';
																						endif;?>
																						<option value="<?=$c->idclienteempresa;?>" <?=$estp?> ><?=$c->nombre_comercial_cli?></option>
																					<?php endforeach; ?>
																				</select>
																			</div>
																			<div class="profile-info-name"> Documento: </div>
																			<div class="profile-info-name">
																				<select name="documento" id="documento" required="Seleccione una opción de la lista" class="form-control">
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
																			<div class="profile-info-name">
																				<button type="button" id="buttonBuscar" class="btn btn-info btn-xs">Buscar 
																					<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																				</button>
																			</div>
																		</div>											
																	</div>				
																	<div id="resp4"></div>	
																	<!--<div id="fechaEmiSelec">
																		<div class="profile-user-info profile-user-info-striped">
																			<div  class="profile-info-row">
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">Seleccione una fecha de Emisión.</div>
																				<div class="profile-info-name">
																					<input class="form-control input-mask-date" type="date" id="fecemisi" name="fecemisi" required="Seleccione una fecha de emisión" value="<?=$fecemisi;?>">
																				</div>
																				<div class="profile-info-name"></div>
																			</div>
																		</div>
																	</div>-->
																	<div id="resp2"></div>
																	<div id="accionesTabla">
																		
																		<div class="profile-user-info profile-user-info-striped">
																			<div  class="profile-info-row">
																				<div class="profile-info-name"></div>
																				<!--<div class="profile-info-name"> Fecha de emisión: </div>
																				<div class="profile-info-name">
																					<input class="form-control input-mask-date" type="date" id="fechaEmiFac" name="fechaEmiFac" required="Seleccione una fecha de inicio" value="<?=$fechaEmision;?>">
																				</div>-->
																				<div class="profile-info-name"> Correlativo actual: </div>
																				<div class="profile-info-name" id="correActual" name="correActual">
																				</div>
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonModal" name="buttonModal" class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#saveModal"> Generar Comprobante de Pago <i class="ace-icon glyphicon glyphicon-save bigger-110 icon-only"></i>
																					</button>
																				</div>
																				<div class="profile-info-name"></div>
																			</div>
																		</div>
																	</div>		

																	<!-- Modal -->
																	<div class="modal fade" id="saveModal" name="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel" aria-hidden="true">
																	  <div class="modal-dialog" role="document">
																	    <div class="modal-content">
																	      <div class="modal-header">
																	        <h5 class="modal-title" id="saveModalLabel">Generar Comprobantes</h5>
																	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	          <span aria-hidden="true">&times;</span>
																	        </button>
																	      </div>
																	      <div class="modal-body">
																	       ¿Seguro que desea generar los comprobantes de pago?
																	      </div>
																	      <div class="modal-footer">
																	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																	        <button type="button" id="buttonGenerar" name="buttonGenerar" class="btn btn-primary">Aceptar</button>
																	      </div>
																	    </div>
																	  </div>
																	</div>
																	
																</form>
															</div>
														</div>
													</div>
												</div>									
												<br/>		
												<br/>
												<br/>		
											</div>			
										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<div class="space-8"></div>
											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
												<form action="" name="formComprobanteManual" id="formComprobanteManual" method="post">
													<div id="respManual"></div>
													<br>
													<div class="form-row">
														<div id="respManual"></div>
														<div class="form-row">
															<div class="form-group col-md-6">
														    	<b class="text-primary">Serie</b>
														    	<select name="serie" id="serie" required="Seleccione una opción de la lista" class="form-control">
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
															<div class="form-group col-md-3">
														    	<b class="text-primary">Correlativo</b>
														    	<input type="text" class="form-control" id="correlativoDoc" name="correlativoDoc" required="Ingrese número correlativo" value="" readonly>
															</div>
															<div class="form-group col-md-3">
														    	<b class="text-primary">Fecha</b>
														    	<input class="form-control input-mask-date" type="date" id="fechaDoc" name="fechaDoc" required="Seleccione una fecha de inicio" value="<?=$fechaDoc;?>">
															</div>
														</div>
														<div class="form-row">
															<input type='text' class='hidden' id='idplan' name='idplan' value=''>
														  	<div class="form-group col-md-9">
														    	<b class="text-primary">Descripción</b>
														    	<input type="text" class="form-control" name="descripcionManual" id="descripcionManual" value="">
															</div>
															<div class="form-group col-md-3">
														    	<b class="text-primary">Importe Total</b>
														   		<input type="number" class="form-control" name="impTotalD" id="impTotalD" value="">
															</div>
														</div>
														<div class="form-row" align="center">
														  	<div class="form-group col-md-12">
														    	<button type="button" id="buttonGuardarManual" class="btn btn-info">Guardar</button>
																<button type="button" id="buttonCancelarManual" class="btn btn-info">Cancelar</button>
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
																	
																<form name="formCategoriaDos" id="formCategoriaDos" method="post" action='<?=base_url()."index.php/boleta/crearXml/"?>'>

																	<div class="profile-user-info profile-user-info-striped">
																		<div class="profile-info-row">

																			<div class="profile-info-name"> Canal: </div>
																			<div class="profile-info-name">
																				<select name="canalesDos" id="canalesDos" required="Seleccione una opción de la lista">
																					<option value=0>Seleccione</option>
																					<?php foreach ($canales as $c):
																						if($idclienteempresa==$c->idclienteempresa):
																							$estp='selected';
																						else:
																							$estp='';
																						endif;?>
																						<option value="<?=$c->idclienteempresa;?>" <?=$estp?> ><?=$c->nombre_comercial_cli?></option>
																					<?php endforeach; ?>
																				</select>
																			</div>
																			<div class="profile-info-name"> Inicio: </div>
																			<div class="profile-info-name">
																				<input class="form-control input-mask-date" type="date" id="fechainicioDos" name="fechainicioDos" required="Seleccione una fecha de inicio" value="<?=$fecinicio;?>">
																			</div>
																			<div class="profile-info-name"> Fin: </div>
																			<div class="profile-info-name">
																				<input class="form-control input-mask-date" type="date" id="fechafinDos" name="fechafinDos" required="Seleccione una fecha de fin" value="<?=$fecfin;?>">					
																			</div>	

																			<div class="profile-info-name"></div>
																			<div class="profile-info-name">
																				<button type="button" id="buttonBuscarDos" class="btn btn-info btn-xs">Buscar 
																					<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																				</button>
																			</div>
																			<!--<div class="profile-info-name">
																				<button type="button" id="buttonDbf" name="buttonDbf" class="btn btn-info btn-xs"> Anexos Concar <i class="ace-icon fa fa fa-file-o bigger-110 icon-only"></i>
																					</button>
																			</div>-->
																			<div class="profile-info-name"></div>
																		</div>											
																	</div>	
																	<div id="resp10">
																		<input type='text' class='hidden' id='numeroSerie' name='numeroSerie' value=''>
																	</div>
																	<br/>
																	<div id="resp40"></div>
																	<div id="resp20"></div>

																	<div id="accionesTablaEmitido">
																		<hr>

																		<div class="profile-user-info profile-user-info-striped">
																			<div  class="profile-info-row">
																				<!--<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonDbf" name="buttonDbf" class="btn btn-white btn-info btn-bold btn-xs"> Generar Anexos Concar <i class="ace-icon fa fa fa-file-o bigger-110 icon-only"></i>
																					</button>
																				</div>-->
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonExcel" name="buttonExcel" class="btn btn-white btn-info btn-bold btn-xs"> Enviar archivos Concar <i class="ace-icon fa fa-file-excel-o bigger-110 icon-only"></i>
																					</button>
																				</div>
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
																                <h3 class="modal-title">Se enviaron los archivos CONCAR al correo.</h3>
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

		<script src="<?=base_url()?>public/assets/js/jquery-ui.custom.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.easypiechart.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.sparkline.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.pie.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.resize.js"></script>

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
				
				$("#accionesTabla").hide();
				//funcion para enviar datos de la vista al controlador
				//para generar los checkbox dinámicos
				//VENTAS
				$("#canales").change(function() {
					var canales = $("#canales").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>index.php/ventas_cnt/mostrarDocumento",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canales').val() == 0) {
						       	$("#documento").html(null);
								$("#resp2").html(null);
						    } else {
								$("#resp").html(null);
								$("#resp2").html(null);
								$("#documento").html(data);
						    }

							$("#accionesTabla").hide();

						}
					});
					return false;
				});

			       	
				//función para enviar datos a la tabla dinámica que se va a generar    
			    $('#buttonBuscar').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/mostrarDatos",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(),
			           	beforeSend: function(){
			           		$("#accionesTabla").hide();
				            $('#resp2').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
			           	success: function(data)             
			           	{
			           		$("#resp2").html(null);
			             	$('#resp2').html(data);
							$("#correlativoActual").html(data);
			             	$("#accionesTabla").show();	
			             	$('#tablaDatos').DataTable({
								"pagingType": "full_numbers"
							});						
			           	}
			       	});
			       	return false;
			    });

			    $('#buttonBuscar').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/mostrarCorrelativo",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(), 
			           	success: function(data)             
			           	{
			           		$("#correActual").html(null); 
							$("#correActual").html(data);
			           	}
			       	});
			       	return false;
			    });

			    $('#buttonGenerar').click(function(){
			    	$.ajax({
			    		url: "<?= BASE_URL()?>index.php/ventas_cnt/generarComprobante",
			    		type: 'POST',
			    		dataType: 'json',
			    		data: $("#formCategoria").serialize(),
			    		beforeSend: function(){
				            $('#resp4').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#resp4").remove();
					        alert("Comprobante de pago generado.");
			    			location.reload();
					    },
			    		success: function(data)
			    		{	
							
			    		}
			    	});
			    	$("#saveModal").modal('hide');
			    	//alert("Comprobante de pago generado");
			    	//location.reload();
			    	$('#canales').val() == 0;
			    	return false;
			    });

//------------------------------------------------------------------------------------------------------------------------------
				//Boletaje Manual

				$("#serie").change(function() {
					var serie = $("#serie").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>index.php/ventas_cnt/mostrarCorrelativoManual",
						type: 'POST',
						dataType: 'json',
						data: {serie:serie},
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#serie').val() == 0) {
						       	$("#correlativoDoc").val(null);
						    } else {
								$("#correlativoDoc").val(data.correlativoDoc);
						    }

						}
					});
					return false;
				});

				$('#buttonGuardarManual').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/guardarComprobanteManual",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formComprobanteManual").serialize(),
			           	beforeSend: function(){
				            $('#respManual').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
				        complete:function() {
					        $("#respManual").remove();
					        alert("Comprobante de pago generado.");
					        $('#descripcionManual').val('');
			           		$('#impTotal').val('');
			           		$('#serie').val(0);
			           		$('#correlativoDoc').val('');
			           		$('#fechaDoc').date('dd/mm/yyyy');
					    },
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

//------------------------------------------------------------------------------------------------------------------------------
			    //BOLETAJE

			    $("#accionesTablaEmitido").hide();
			   /* $("#canalesDos").change(function() {
					var canales = $("#canalesDos").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>index.php/ventas_cnt/generarLista",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						beforeSend: function(){
				            $('#resp10').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canalesDos').val() == 0) {
						       	$("#resp10").html(null);
								$("#resp20").html(null);
						    } else {
								$("#resp10").html(null);
								$("#resp20").html(null);
								$("#resp10").html(data);
						    }
							$("#accionesTablaEmitido").hide();
						}
					});
					return false;
				});*/

				$("#canalesDos").change(function() {
					var canales = $("#canalesDos").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>index.php/ventas_cnt/generarLista",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canalesDos').val() == 0) {
						       	$("#numeroSerie").val(null);
						    } else {
								$("#numeroSerie").val(data.numeroSerie);
						    }

						}
					});
					return false;
				});

				//función para enviar datos a la tabla dinámica que se va a generar    
			    $('#buttonBuscarDos').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/mostrarDatosComprobantes",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoriaDos").serialize(),
			           	beforeSend: function(){
				            $('#resp20').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				            $("#accionesTablaEmitido").hide();
				        },
			           	success: function(data)             
			           	{
			           		$("#resp20").html(null);
			             	$('#resp20').html(data); 
			             	$("#accionesTablaEmitido").show();
			             	$('#tablaDatos').DataTable({
								"pagingType": "full_numbers"
							});
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
				    $("#buttonEmitir").click(function(){    
				    	//location.reload(true);    
				      $('#modalXML').modal('hide');
				    });
				});

				$('#buttonExcel').click(function(){

					var fechainicio = $("#fechainicioDos").val();
			    	var fechafin = $("#fechafinDos").val();
			    	var numSerie = $("#numSerie").val();
			    	var canales = $("#canalesDos").val();

			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/generarExcel",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data:  {fechainicio:fechainicio,
			           			fechafin:fechafin,
			           			numSerie:numSerie,
			           			canales:canales}, 
			           	success: function(data)             
			           	{

			           	}
			       	});
			       	return false;
			    });

			    $('#buttonEmitir').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/ventas_cnt/crearXml",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoriaDos").serialize(),
			           	beforeSend: function(){
				            $('#resp40').html("<img src='<?=base_url()."public/assets/img/loading2.gif"?>'><br><br>");
				        },
				        complete:function() {
					        $("#resp40").remove();
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