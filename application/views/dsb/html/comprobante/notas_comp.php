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
											<h4 class="blue">
												<i class="ace-icon fa fa-file-text bigger-110"></i>
												Nota de Crédito
											</h4>
											<div class="space-8"></div>
											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
												<form action="" method="post">
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
																			<option value="<?=$s->numero_serie;?>" <?=$estp?> ><?=$s->numero_serie?></option>
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
																	<button type="button" id="buttonBuscar" class="btn btn-info btn-xs">Buscar 
																		<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																	</button>
																</div>
																<div class="profile-info-name"></div>
															</div>											
														</div>	

												  	</div>
												</form>
											</div>
										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<h4 class="blue">
												<i class="ace-icon fa fa-file-text-o bigger-110"></i>
												Nota de Débito
											</h4>
											<div class="space-8"></div>
											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
												<form action="" method="post">
													
												</form>
											</div>
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<div class="row">
												<div class="col-xs-12">
													<!-- PAGE CONTENT BEGINS -->						
													<div align="center">								
														<div class="col-xs-9 col-sm-12">
															<div class="widget-box transparent">
																	
																<form name="formCategoria" id="formCategoria" method="post" action='<?=base_url()."index.php/boleta/crearXml/"?>'>

																	<div class="profile-user-info profile-user-info-striped">
																		<div class="profile-info-row">

																			<div class="profile-info-name"> Plan: </div>
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
																				<button type="button" id="buttonBuscar" class="btn btn-info btn-xs">Buscar 
																					<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																				</button>
																			</div>
																			<div class="profile-info-name"></div>
																		</div>											
																	</div>	
																	<div id="resp1"></div>
																	<br/><br/><br/>
																	<div id="resp4"></div>
																	<div id="resp2"></div>

																	<div id="accionesTablaEmitido">
																		<hr>

																		<div class="profile-user-info profile-user-info-striped">
																			<div  class="profile-info-row">
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonDbf" name="buttonDbf" class="btn btn-white btn-info btn-bold btn-xs"> Generar Anexos Concar <i class="ace-icon fa fa fa-file-o bigger-110 icon-only"></i>
																					</button>
																				</div>
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonExcel" name="buttonExcel" class="btn btn-white btn-info btn-bold btn-xs"> Generar archivo Excel <i class="ace-icon fa fa-file-excel-o bigger-110 icon-only"></i>
																					</button>
																				</div>
																				<!--<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonPdf" name="buttonPdf" class="btn btn-white btn-info btn-bold btn-xs"> Enviar archivos PDF <i class="ace-icon fa fa-file-pdf-o bigger-110 icon-only"></i>
																					</button>
																				</div>-->
																				<div class="profile-info-name"></div>
																				<div class="profile-info-name">
																					<button type="button" id="buttonComprobante" name="buttonComprobante" class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#modalXML"> Emitir Comprobante <i class="ace-icon fa fa-file-code-o bigger-110 icon-only"></i>
																					</button>
																				</div>
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

				/*$("#canales").change(function() {
					var canales = $("#canales").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>notas_cnt/index",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canales').val() == 0) {
						       	$("#correlativoDoc").html(null);
								$("#resp2").html(null);
						    } else {
								$("#resp").html(null);
								$("#resp2").html(null);
								$("#correlativoDoc").html(data);
						    }

							$("#accionesTabla").hide();
						}
					});
					return false;
				});*/
				
//------------------------------------------------------------------------------------------------------------------------------
				//NOTAS DE CRÉDITO Y NOTAS DE DÉBITO
			    $("#accionesTablaEmitido").hide();
			    $("#canales").change(function() {
					var canales = $("#canales").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>notas_cnt/generarLista",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						beforeSend: function(){
				            $('#resp10').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canales').val() == 0) {
						       	$("#resp1").html(null);
								$("#resp2").html(null);
						    } else {
								$("#resp1").html(null);
								$("#resp2").html(null);
								$("#resp1").html(data);
						    }

							$("#accionesTablaEmitido").hide();
						}
					});
					return false;
				});

				//función para enviar datos a la tabla dinámica que se va a generar    
			    $('#buttonBuscar').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>notas_cnt/mostrarDatosComprobantes",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(),
			           	beforeSend: function(){
				            $('#resp2').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },
			           	success: function(data)             
			           	{
			           		$("#resp2").html(null);
			             	$('#resp2').html(data); 
			             	$("#accionesTablaEmitido").show();
			             	$('#tablaDatos').DataTable( {
								"pagingType": "full_numbers"
							} );
			           	}
			       	});
			       	return false;
			    });

			    $('#buttonExcel').click(function(){

			    	var fechainicio = $("#fechainicio").val();
			    	var fechafin = $("#fechafin").val();
			    	var numSerie = $("#numSerie").val();
			    	var canales = $("#canales").val();

			        $.ajax({                        
			           	url: "<?= BASE_URL()?>notas_cnt/generarExcel",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: {fechainicio:fechainicio,
			           			fechafin:fechafin,
			           			numSerie:numSerie,
			           			canales:canales}
			       	}).done(function(data){
					    var $a = $("<a>");
					    $a.attr("href",data.file);
					    $("body").append($a);
					    $a.attr("download","file.xls");
					    $a[0].click();
					    $a.remove();
					});
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
			           	url: "<?= BASE_URL()?>notas_cnt/generarArchivoDbf",   
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
			           	url: "<?= BASE_URL()?>notas_cnt/crearXml",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(),
			           	beforeSend: function(){
				            $('#resp4').html("<img src='<?=base_url()."public/assets/img/loading2.gif"?>'><br><br>");
				        },
				        complete:function() {
					        $("#resp4").remove();
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