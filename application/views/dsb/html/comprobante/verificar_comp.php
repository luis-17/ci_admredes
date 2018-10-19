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
							<li class="active">Reporte de Ventas</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Reporte de Ventas
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->
					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->						
								<div align="center">								
									<div class="col-xs-9 col-sm-12">
										<div class="widget-box transparent">
												
												<form name="formCategoria" id="formCategoria" method="post" action='<?=base_url()."index.php/verificar"?>'>

													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row">

															<div class="profile-info-name"> Plan: </div>
															<div class="profile-info-name">
																<select name="canales" id="canales" required="Seleccione una opción de la lista">
																	<option value=0>Seleccione</option>
																	<?php foreach ($canales as $c):
																		if($idserie==$c->idserie):
																			$estp='selected';
																		else:
																			$estp='';
																		endif;?>
																		<option value="<?=$c->numero_serie;?>" <?=$estp?> ><?=$c->numero_serie.' - '.$c->descripcion_ser?></option>
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
													<hr>
													<div id="resp"></div>
													<div id="resp10">
														<input type='text' class='hidden' id='numeroSerie' name='numeroSerie' value=''>
													</div>
													
													<div id="resp4"></div>
													<div id="resp2"></div>			
													<div id="resp3"></div>

													<div id="accionesTablaVerificar">
														<hr>

														<div class="profile-user-info profile-user-info-striped">
															<div  class="profile-info-row">
																<div class="profile-info-name"></div>
																<div class="profile-info-name">
																	<button type="button" id="buttoncdr" name="buttoncdr" class="btn btn-white btn-info btn-bold btn-xs"> Descargar CDR SUNAT <i class="ace-icon fa fa-file-excel-o bigger-110 icon-only"></i>
																	</button>
																</div>
																<div class="profile-info-name"></div>
																<div class="profile-info-name"></div>
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
				
				$("#accionesTablaVerificar").hide();
				//funcion para enviar datos de la vista al controlador
				//para generar los checkbox dinámicos
				$("#canales").change(function() {
					var canales = $("#canales").val();
					//ajax para pasar los parámetros
					$.ajax({
						url: "<?= BASE_URL()?>index.php/verificar_cnt/generarLista",
						type: 'POST',
						dataType: 'json',
						data: {canales:canales},
						/*beforeSend: function(){
				            $('#resp').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				        },*/
						success: function(data)
						{
							//#resp es el id del div donde se van a crear los checkbozx
							if ($('#canales').val() == 0) {
						       	$("#resp").html(null);
								$("#resp2").html(null);
								$("#numeroSerie").val(null);
						    } else {
								$("#resp").html(null);
								$("#resp2").html(null);
								$("#resp").html(data);
								$("#numeroSerie").val(data.numeroSerie);
						    }
						}
					});
					return false;
				});

				//función para enviar datos a la tabla dinámica que se va a generar    
			    $('#buttonBuscar').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/verificar_cnt/mostrarDatosComprobantesEmitidos",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(),
			           	beforeSend: function(){
				            $('#resp2').html("<br><br><img src='<?=base_url()."public/assets/img/loading2.gif"?>'>");
				            $("#accionesTablaVerificar").hide();
				        },
			           	success: function(data)             
			           	{
			           		$("#resp2").html(null);
			             	$('#resp2').html(data); 
			             	$("#accionesTablaVerificar").show();
			             	$('#tablaDatos').DataTable( {
								"pagingType": "full_numbers"
							} );
			           	}
			       	});
			       	return false;
			    });

			    $('#buttoncdr').click(function(){
			        $.ajax({                        
			           	url: "<?= BASE_URL()?>index.php/verificar_cnt/descargarCDR",   
			           	type: 'POST',
			           	dataType: 'json',                                 
			           	data: $("#formCategoria").serialize(),
			           	success: function(response)             
			           	{
			           		if(response.zip) { 
					     		location.href = response.zip;
					     	} 
			           	}
			       	});
			       	return false;
			    });

			});
    	</script>

	</body>
</html>