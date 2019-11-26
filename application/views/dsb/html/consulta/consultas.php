<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
	$hora = date("H");
	if($hora>0 && $hora<=12){
		$turno = "buenos días";
	}elseif($hora>12 && $hora<=18){
		$turno = "buenas tardes";
	}elseif($hora>18 && $hora<=24){
		$turno = "buenas noches";
	}
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
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />


		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

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

	<script type="text/javascript">
    /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
    $(document).ready(function(){
       $("#canal").change(function () {
               $("#canal option:selected").each(function () {
                canal=$('#canal').val();
                $.post("<?=base_url();?>index.php/planes", { canal: canal}, function(data){
                $("#plan").html(data);
                });            
            });
       })
    });

    /*fin de la funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
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

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

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
							<li>
								Consultas
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div align="center">								
									<div class="col-xs-9 col-sm-12">
											<div class="alert alert-info">
												<div class="profile-info-row">

												<form name="form" id="form" method="post" action="<?=base_url()?>index.php/detalle_plan" class="form-horizontal">
													<div class="profile-info-name"> Canal: </div>
													<div class="profile-info-name">
														<select name="canal" id="canal" required="Seleccione una opción de la lista" class="form-control"  style="width: 200px;">
															<option value="">Seleccione</option>
															<?php foreach ($canales as $c):
																if($canal==$c->idclienteempresa):
																	$estc='selected';
																	else:
																	$estc='';
																endif;?>
																<option value="<?=$c->idclienteempresa;?>" <?=$estc?> ><?=$c->nombre_comercial_cli?> </option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="profile-info-name"> Plan: </div>
													<div class="profile-info-name">
														<select name="plan" id="plan" required="Seleccione una opción de la lista"  class="form-control"  style="width: 200px;">											
															<option value="">Seleccione</option>
															<?php 
																foreach ($planes as $p):
																	if($plan==$p->idplan):
																		$estp='selected';
																		else:
																		$estp='';
																	endif;?>
																	<option value="<?=$p->idplan;?>" <?=$estp?> ><?=$p->nombre_plan?> </option>
																<?php endforeach; ?>				
														</select>
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
								
								<br/>		
								<br/>
								<br/>		
								</div><!-- PAGE CONTENT ENDS -->
								<!-- /.row -->
                                <!-- PAGE CONTENT ENDS -->
                            </div>
							<!-- /.col -->
						</div><!-- /.row -->
						<br>
						<?php if($estado==1){ ?>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Detalles del Plan
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Diagnósticos
											</a>
										</li>
																				
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">	
											<div class="widget-box transparent">
														<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">	

																<div class="profile-info-name"> Cliente: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$nombre_comercial_cli?></span>
																</div>

																<div class="profile-info-name"> Plan: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$nombre_plan;?></span>
																</div>

																<div class="profile-info-name"> Responsable de cuenta: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$responsable;?></span>
																</div>
															</div>

															<div class="profile-info-row">	

																<div class="profile-info-name"> Carencia: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$carencia?> días</span>
																</div>

																<div class="profile-info-name"> Mora: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$mora;?> días</span>
																</div>

																<div class="profile-info-name"> Frecuencia de Atención: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="username">Cada <?=$atencion;?> días</span>
																</div>
															</div>
																		
															
														</div>
											</div>

											<br>
											<table id="simple-table" class="table table-bordered table-hover">
														<thead>
															<tr>
																<th colspan="2" width="55%">Cobertura</th>
																<th width="15%">Copago/Coaseguro</th>
																<th width="15%">Eventos</th>
																<th width="15%">No Cubre</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($cobertura_operador as $co1) {
																switch($co1->tiempo){
													                case '':
													                    $men ="Ilimitados";
													                break;
													                case '1 month':
													                    if($co1->num_eventos==1){
													                      $men = "evento al mes";
													                    }else{
													                      $men = "eventos mensuales";
													                    }													                    
													                    break;
													                 case '2 month':
													                    if($co1->num_eventos==1){
													                      $men = "evento bimestral";
													                    }else{
													                      $men = "eventos bimestrales";
													                    }
													                    break;
													                 case '3 month':
													                    if($co1->num_eventos==1){
													                      $men = "evento trimestral";
													                    }else{
													                      $men = "eventos trimestrales";
													                    }
													                    break;
													                case '6 month':
													                    if($co1->num_eventos==1){
													                      $men = "evento semestral";
													                    }else{
													                      $men = "eventos semestrales";
													                    }
													                    break;
													                case '1 year':
													                    if($co1->num_eventos==1){
													                      $men = "evento al año";
													                    }else{
													                      $men = "eventos anuales";
													                    }
													                    break;
													            }
													           
													            if($co1->num_eventos==0){
													            	$num_eve='';
													            }else{
													            	$num_eve=$co1->num_eventos;
													            }
															?>
															<tr>
																<td width="15%"><?=$co1->nombre_var?></td>
																<td width="40%"><?=$co1->texto_web?></td>
																<td width="15%"><?=$co1->coaseguro?></td>
																<td width="15%"><?php echo $num_eve.' '.$men;?></td>
																<td width="15%"><?=$co1->bloqueos?></td>
															</tr>
															<?php } 
															foreach($coberturas as $co2){?>
															<tr>
																<td><?=$co2->nombre_var?></td>
																<td colspan="4"><?=$co2->texto_web?></td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
											<!-- star table -->	
										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<table id="example" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>ID</th>
															<th>Código CIE</th>
															<th>Descripción</th>
															<th>Tipo</th>
															<th>Estado</th>
															<th>Opciones</th>
														</tr>
													</thead>
													<tbody>
													
													<?php foreach($diagnosticos as $d){?>
														<tr>
															<td><?=$d->iddiagnostico?></td>
															<td><?=$d->codigo_cie?></td>
															<td><?=$d->descripcion_cie?></td>
															<td><?php if($d->tipo==1){
																echo 'Capa simple';
																}else{
																	echo 'Preexistente';
																	}?></td>
															<td><?php if($d->estado_cie==1){ ?>
																<a href="<?=base_url()?>index.php/diagnosticos_anular/<?=$d->iddiagnostico?>"><span class="label label-info label-white middle">Activo</span></a>
																<?php }else{ ?>
																	<a href="<?=base_url()?>index.php/diagnosticos_activar/<?=$d->iddiagnostico?>"><span class="label label-danger label-white middle">Inactivo</span></a>
																	<?php }?></td>											
															<td>
																<div title="Ver Detalle" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																	&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/diagnosticos_detalle2/<?=$d->iddiagnostico?>/<?=$plan?>" data-fancybox-width="1250" data-fancybox-height="690">
																		<i class="ace-icon fa fa-eye bigger-120 blue"></i>
																	</a>
																</div>
															</td>
														</tr>
													<?php } ?>
													</tbody>
												</table>
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

								<!-- .tabbale -->
								</div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
						<?php } ?>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
			<!-- /.contenido pagina -->
			<div>
				

			</div>
			<!-- /.end contenido pagina -->
			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Red Salud Admin </span>
							Application &copy; 2018
						</span>
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
 window.jQuery || document.write("<script src='public/assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?=base_url()?>public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="public/assets/js/excanvas.js"></script>
		<![endif]-->
		<script src="<?=base_url()?>public/assets/js/jquery-ui.custom.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.easypiechart.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.sparkline.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.pie.js"></script>
		<script src="<?=base_url()?>public/assets/js/flot/jquery.flot.resize.js"></script>

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