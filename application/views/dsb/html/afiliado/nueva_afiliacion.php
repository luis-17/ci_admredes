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

    <script type="text/javascript">
    /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
    $(document).ready(function(){
       $("#dep").change(function () {
               $("#dep option:selected").each(function () {
                dep=$('#dep').val();
                $.post("<?=base_url();?>index.php/provincia", { dep: dep}, function(data){
                $("#prov").html(data);
                });            
            });
       })
    });

    $(document).ready(function(){
       $("#prov").change(function () {
               $("#prov option:selected").each(function () {
                prov=$('#prov').val();
                $.post("<?=base_url();?>index.php/distrito", { prov: prov}, function(data){
                $("#dist").html(data);
                });            
            });
       })
    });
    /*fin de la funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
    </script>
    <?php 
    if(!empty($certificado)){
    	foreach ($certificado as $c):
	    $cert_id=$c->cert_id;
	    $cert_num=$c->cert_num;
	    $afiliados=$c->num_afiliados;
	    endforeach;
	}else{
		$cert_id='';
		$cert_num='';
		$afiliados=0;
	}

	?>

	</head>
	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/../headBar.php");?>

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
								<a href="#">Herramienta de Afiliación / Desafiliación</a>
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12"></br>
								<!-- PAGE CONTENT BEGINS -->
								<form role="form" name="form_bus" id="form_bus" method="post" action="<?=base_url()?>index.php/buscar">	
								<input type="hidden" name="id" id="id" value="">
									<div class="form-row">
										<div class="form-group col-md-3">
											<b class="text-primary">Canal</b>
											<select name="canal" id="canal" required="Seleccione una opción de la lista" class="form-control">
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
										<div class="form-group col-md-3">
											<b class="text-primary">Plan</b>
											<select name="plan" id="plan" required="Seleccione una opción de la lista"  class="form-control">											
												<option value="">Seleccione</option>
												<?php 
													$cancelar='N';
													$eliminar='N';
													foreach ($planes as $p):
														if($plan==$p->idplan):
															$estp='selected';
															$cancelar=$p->flg_cancelar;
															$eliminar=$p->flg_eliminar;
															else:
															$estp='';
														endif;?>
												<option value="<?=$p->idplan;?>" <?=$estp?> ><?=$p->nombre_plan?> </option>
												<?php endforeach; ?>				
											</select>
										</div>		
										<div class="form-group col-md-3">
											<b class="text-primary">DNI Contratante</b>
											<input type="text" id="cont_numDoc" name="cont_numDoc" required="Digite el DNI"  class="form-control" value="<?=$doc;?>">
										</div>		
										<div class="form-group col-md-3">
										<b class="text-primary"> </b></br>
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Buscar
											</button>
										</div>								
								 </div>
								</form>
							</div><!-- /.col -->
							<div style="display: <?=$estilo;?>;">	
							<h4 class="black">
								<i class="green ace-icon fa fa-pencil-square-o bigger-110"></i>
									Certificado N° <?=$cert_num;?>
								</h4>

							<div class="space-8"></div>

							<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
								<div class="panel panel-default">
									<div class="panel-heading">
										<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
											<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
												DATOS DEL CONTRATANTE
										</a>
									</div>

								<div class="panel-collapse collapse" id="faq-2-1">
									<div class="panel-body">
														
						
							<!-- star table -->		
								<div class="col-xs-12">
								<?php
								if(!empty($datos)){
								foreach ($datos as $d): 
									$dep = $d->dep;
									$prov = $d->prov;
									$dist = $d->dist;
									$cape1=$d->cont_ape1;
									$cape2=$d->cont_ape2;
									$cnom1=$d->cont_nom1;
									$cnom2=$d->cont_nom2;
									$cont_id=$d->cont_id;
									?>
									<form role="form" method="post" name="form_cont" id="form_cont" action="<?=base_url()?>index.php/cont_save">	
									<input type="hidden" name="can" value="<?=$canal;?>">
									<input type="hidden" name="pl" value="<?=$plan?>">
									<input type="hidden" name="docu" value="<?=$doc?>">
									<input type="hidden" name="doc_bus" value="<?=$doc_bus?>">
									<input type="hidden" name="cont_id" value="<?=$cont_id?>">
									<div class="form-row">
										<div class="form-group col-md-3">
											<b class="text-primary">Tipo de Documento</b>
											<select name="tipodoc" id="tipodoc" required="Seleccione una opción de la lista" class="form-control" value="<?=$d->cont_tipoDoc;?>">
												<option>Seleccione</option>
												<option value="1" <?php if($d->cont_tipoDoc==1): echo "selected"; endif;?> >DNI</option>
                                                <option value="2" <?php if($d->cont_tipoDoc==2): echo "selected"; endif;?> >Pasaporte</option>
                                                <option value="4" <?php if($d->cont_tipoDoc==4): echo "selected"; endif;?> >Carné de extranjería</option>
                                            </select>
										</div>
										<div class="form-group col-md-3">
											<b class="text-primary">Nro de Documento</b>
											<input value="<?=$d->cont_numDoc;?>" type="text" id="dni" name="dni" required="Digite el DNI"  class="form-control">
										</div>
									</div>
									<div class="form-group col-md-6">
									<p><img src=""></p>
									<p><img src=""></p>
									</div>
									<div class="form-row">		
										<div class="form-group col-md-3">
											<b class="text-primary">Apellido Paterno</b>
											<input value="<?=$cape1;?>" type="text" id="ape1" name="ape1" required="Digite el apellido paterno"  class="form-control">
										</div>		
										<div class="form-group col-md-3">
											<b class="text-primary">Apellido Materno</b>
											<input value="<?=$cape2;?>" type="text" id="ape2" name="ape2" required="Digite el apellido materno"  class="form-control">
										</div>	
										<div class="form-group col-md-3">
											<b class="text-primary">Nombre 1</b>
											<input value="<?=$cnom1;?>" type="text" id="nom1" name="nom1" required="Digite el Nombre"  class="form-control">
										</div>	
										<div class="form-group col-md-3">
											<b class="text-primary">Nombre 2</b>
											<input value="<?=$cnom2;?>" type="text" id="nom2" name="nom2"  class="form-control">
										</div>									
								 	</div>
								 	<div class="form-row">		
										<div class="form-group col-md-3">
											<b class="text-primary">Dirección</b>
											<input value="<?=$d->cont_direcc;?>" type="text" id="direcc" name="direcc"  class="form-control">
										</div>
										<div class="form-group col-md-3">
											<b class="text-primary">Departamento</b>
											<select name="dep" id="dep" class="form-control">
                                            <option>Seleccionar</option>
                                            <?php foreach ($ubigeo as $u): 
                                            	if($dep==$u->iddepartamento):
                                            		$estdep='selected';
												else:
													$estdep='';
												endif;
                                            		?>
                                                <option value="<?=$u->iddepartamento;?>" <?=$estdep?> ><?=$u->descripcion_ubig;?></option>
                                                <?php endforeach; ?>                                                            
                                         	</select>
										</div>		
										 	
										<div class="form-group col-md-3">
											<b class="text-primary">Provincia</b>
											<select id="prov" name="prov" class="form-control">
											<option value="">Seleccionar</option>
											 <?php foreach ($provincia2 as $p2): 
                                            	if($prov==$p2->idprovincia):
                                            		$estprov='selected';
												else:
													$estprov='';
												endif;
                                            		?>
                                                <option value="<?=$p2->idprovincia;?>" <?=$estprov?> ><?=$p2->descripcion_ubig;?></option>
                                                <?php endforeach; ?>                                                            
                                         	</select>
										</div>	
										<div class="form-group col-md-3">
											<b class="text-primary">Distrito</b>
											<select name="dist" id="dist" class="form-control">
											<option value="">Seleccionar</option>
                                                <?php foreach ($distrito2 as $d2): 
                                            	if($dist==$d2->iddistrito):
                                            		$estdist='selected';
												else:
													$estdist='';
												endif;
                                            		?>
                                                <option value="<?=$d2->iddistrito;?>" <?=$estdist?> ><?=$d2->descripcion_ubig;?></option>
                                                <?php endforeach; ?> 
                                            </select>
										</div>									
								 	</div>
								 	<div class="form-row">
										<div class="form-group col-md-3">
											<b class="text-primary">Teléfono</b>
											<input type="text" id="telf" name="telf" value="<?=$d->cont_telf;?>" class="form-control">
										</div>
										<div class="form-group col-md-3">
											<b class="text-primary">Correo Electrónico</b>
											<input value="<?=$d->cont_email;?>" type="text" id="correo" name="correo"   class="form-control">
										</div>
									</div>
									<div class="form-group col-md-6">
									<p><img src=""></p>
									<p><img src=""></p>
									</div>
									<div class="form-row">
										<div class="form-group col-md-12" style="text-align: right;">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>	
									</div>	
								</form>
								<?php endforeach; 
								}else {?>
								<div>No se encontraron registros.</div>
								<?php } ?>
								</div>
							<!-- end table -->
							</div></div>

							<div class="panel panel-default">
								<div class="panel-heading">
									<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
										<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
										DATOS DE LOS DEPENDIENTES
									</a>
								</div>

								<div class="panel-collapse collapse" id="faq-2-2">
									<div class="panel-body">
									<?php if(!empty($cant)){
										foreach($cant as $c):
											$cant=$c->cant;
										endforeach;
											}else{
												$cant=0;
												}
										$num_aseg=0;?>


										<?php if($cant<$afiliados): ?>
										<div style="text-align: right; padding-right: 3%;">
											<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_nuevo/<?=$cert_id;?>" data-fancybox-width="950" data-fancybox-height="690">Agregar Dependiente
											</a>
										</div>
										<?php endif; ?>	

										<?php if(!empty($asegurados)){ ?>
										<div class="page-content">
											<div class="row">
											<input type="hidden" name="" value="<?=$cant;?>">
											<input type="hidden" name="" value="<?=$afiliados?>">
												<div class="col-xs-12"></br>
													<table id="simple-table" class="table table-striped table-bordered table-hover" width="60%">
														<thead>
															<tr>
																<th>Documento</th>
																<th>Asegurado</th>
																<th>Editar</th>													
															</tr>
														</thead>
														<tbody>
														<?php foreach ($asegurados as $a):
														$num_aseg=$num_aseg+1; ?>
															<tr>
																<td><?=$a->aseg_numDoc;?></td>
																<td><?=$a->asegurado;?></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Editar Dependiente" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																		&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_editar/<?=$a->aseg_id;?>/<?=$cert_id;?>" data-fancybox-width="950" data-fancybox-height="690">
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
																							<div title="Editar Dependiente" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_editar/<?=$a->aseg_id;?>/<?=$cert_id;?>" data-fancybox-width="950" data-fancybox-height="690">
																									<i class="ace-icon fa fa-pencil bigger-120"></i>
																								</a>
																							</div>
																						</li>				
																					</ul>
																				</div>
																			</div>
																		</td>																	
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<?php }else{ ?>
										<div>No se encontraron registros.</div>
										<?php } ?>
									</div>
								</div>






							<?php if($cancelar=='S'){ ?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a href="#faq-2-3" data-parent="#faq-list-3" data-toggle="collapse" class="accordion-toggle collapsed">
										<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
										CANCELAR CERTIFICADO
									</a>
								</div>
								

								<div class="panel-collapse collapse" id="faq-2-3">
									<div class="panel-body">
										<div class="page-content">
											<div class="row">
												<div class="col-xs-12">													
												<?php if($cert_id!=''){?>	
												<div style="padding-left: 15%;">								
													<form>
													<h4>Preguntas de validación</h4>
													<li><label>¿Cuáles son sus nombres completos?</label><b> <?=$cnom1;?> <?=$cnom2;?></b></li>
													<li><label>¿Cuáles son sus apellidos completos?</label><b> <?=$cape1;?> <?=$cape2?></b></li>
													<li><label>¿Cuántas personas se encuentran afiliadas a su plan?</label><b> <?=$num_aseg;?> persona(s)</b></li>
													<br>
													<div class="clearfix">
															<p>
															<a class="boton fancybox" href="<?=  base_url()?>index.php/form_incidencia/<?=$cert_id;?>" data-fancybox-width="750" data-fancybox-height="490">
															<button class="btn btn-danger" type="button">
																<i class="ace-icon glyphicon glyphicon-remove"></i>
																Respuestas Incorrectas
															</button>
															</a>
															<a class="boton fancybox" href="<?=  base_url()?>index.php/form_cancelado/<?=$cert_id?>/<?=$cert_num?>/<?=$plan?>" data-fancybox-width="750" data-fancybox-height="490">
															<button class="btn btn-success" type="submit" onclick="guardar()">
																<i class="ace-icon fa fa-check bigger-110"></i>
																Respuestas Correctas
															</button>
															</a></p>
													</div>												
													</form>
												</div>
													<?php }else{ ?>
													<div>No se encontraron registros.</div>								<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>								
							</div>

							<?php } ?>

							</div>
							</div></div>



							</div>
						</div>
					</div>
				</div><!-- /.main-content -->			
			</div><!-- /.main-container -->

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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html',
									 {
										tagValuesAttribute:'data-values',
										type: 'bar',
										barColor: barColor ,
										chartRangeMin:$(this).data('min') || 0
									 });
				});
			
			
			  //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
			  //but sometimes it brings up errors with normal resize event handlers
			  $.resize.throttleWindow = false;
			
			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "social networks",  data: 38.7, color: "#68BC31"},
				{ label: "search engines",  data: 24.5, color: "#2091CF"},
				{ label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
				{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
				{ label: "other",  data: 10, color: "#FEE074"}
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  $.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);
			
			
			  //pie chart tooltip example
			  var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
			  var previousPoint = null;
			
			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + item.series['percent']+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}
				
			 });
			
				/////////////////////////////////////
				$(document).one('ajaxloadstart.page', function(e) {
					$tooltip.remove();
				});
			
			
			
			
				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}
			
				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}
			
				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}
				
			
				var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
				$.plot("#sales-charts", [
					{ label: "Domains", data: d1 },
					{ label: "Hosting", data: d2 },
					{ label: "Services", data: d3 }
				], {
					hoverable: true,
					shadowSize: 0,
					series: {
						lines: { show: true },
						points: { show: true }
					},
					xaxis: {
						tickLength: 0
					},
					yaxis: {
						ticks: 10,
						min: -2,
						max: 2,
						tickDecimals: 3
					},
					grid: {
						backgroundColor: { colors: [ "#fff", "#fff" ] },
						borderWidth: 1,
						borderColor:'#555'
					}
				});
			
			
				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
				$('.dialogs,.comments').ace_scroll({
					size: 300
			    });
				
				
				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				});
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});
			
			})

		</script>
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
