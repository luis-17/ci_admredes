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
							<li>Afiliación / Desafiliación</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Consultamos datos de afiliados
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
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
											<b class="text-primary">DNI Responsable de Pago</b>
											<input type="text" id="cont_numDoc" name="cont_numDoc" required="Digite el DNI"  class="form-control" value="<?=$doc;?>">
										</div>		
										<div class="form-group col-md-3">
										<b class="text-primary"> </b></br>
											<button class="btn btn-sm btn-info" type="submit">
												<i class="ace-icon glyphicon glyphicon-search bigger-110"></i>
												Buscar
											</button>
										</div>								
								 </div>
								</form>
							</div><!-- /.col -->
							
							<br>
							<div style="display: <?=$estilo;?>;">	
							<?php 
							$n1=2;
						    if(!empty($certificado)){

						    	foreach ($certificado as $c):
							    $cert_id=$c->cert_id;
							    $cert_num=$c->cert_num;
							    $afiliados=$c->num_afiliados;
							    $cant=$c->cant;
							    $fec_reg=$c->fec_reg;
							    ?>
							<h4 class="black">
								<i class="blue ace-icon fa fa-pencil-square-o bigger-110"></i>
									Certificado N° <?=$cert_num;?>
								</h4>

							<div class="space-8"></div>

							<div id="faq-list-<?=$n1;?>" class="panel-group accordion-style1 accordion-style<?=$n1;?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<a href="#faq-<?=$n1;?>-1" data-parent="#faq-list-<?=$n1;?>" data-toggle="collapse" class="accordion-toggle collapsed">
											<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
												DATOS DEL RESPONSABLE DE PAGO
												<input type="hidden" value="<?=$cert_id?>">
										</a>
									</div>

								<div class="panel-collapse collapse" id="faq-<?=$n1;?>-1">
									<div class="panel-body">
														
						
							<!-- star table -->		
								<div class="col-xs-12">
								<?php
								if(!empty($datos)){
								foreach ($datos as $d): 
									$cert_id2=$d->cert_id;
									$dep = $d->dep;
									$prov = $d->prov;
									$dist = $d->dist;
									$cape1=$d->cont_ape1;
									$cape2=$d->cont_ape2;
									$cnom1=$d->cont_nom1;
									$cnom2=$d->cont_nom2;
									$cont_id=$d->cont_id;
									$num_aseg=0;
									if($cert_id==$cert_id2){
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
										<a class="boton fancybox" href="https://www.red-salud.com/gestion_afiliados2.0/adjunto/certificado.pdf" data-fancybox-width="600" data-fancybox-height="690"><button class="btn btn-info" >
											<i class="ace-icon glyphicon glyphicon-print"></i>
												Imprimir Certificado
										</button>
										</a>
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>	
									</div>	
								</form>
								<?php } endforeach;  
								}else {?>
								<div>No se encontraron registros.</div>
								<?php } ?>
								</div>
							<!-- end table -->
							</div></div>

							
							<div class="panel panel-default">
								<div class="panel-heading">
									<a href="#faq-<?=$n1;?>-2" data-parent="#faq-list-<?=$n1;?>" data-toggle="collapse" class="accordion-toggle collapsed">
										<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
										VER/AGREGAR AFILIADOS
									</a>
								</div>

								<div class="panel-collapse collapse" id="faq-<?=$n1;?>-2">
									<div class="panel-body">
										<?php $hoy=date('Y-m-d');
										if($cant<$afiliados): ?>
										<div style="text-align: right; padding-right: 3%;">
											<a class="boton fancybox" href="<?=base_url()?>index.php/aseg_nuevo/<?=$cert_id;?>/<?=$plan?>" data-fancybox-width="950" data-fancybox-height="690">Agregar Afiliado
											</a>
										</div>
										<?php endif;?>

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
																<th>Afiliado</th>
																<th>Editar</th>													
															</tr>
														</thead>
														<tbody>
														<?php foreach ($asegurados as $a):
														$cert_id3=$a->cert_id;
														if($cert_id==$cert_id3){
														$num_aseg=$num_aseg+1; ?>
															<tr>
																<td><?=$a->aseg_numDoc;?></td>
																<td><?=$a->asegurado;?></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Editar Dependiente" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																		&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_editar/<?=$a->aseg_id;?>/<?=$cert_id;?>" data-fancybox-width="600" data-fancybox-height="690">
																					<i class="blue ace-icon fa fa-pencil bigger-120"></i>
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
																									<i class="blue ace-icon fa fa-pencil bigger-120"></i>
																								</a>
																							</div>
																						</li>				
																					</ul>
																				</div>
																			</div>
																		</td>																	
															</tr>
														<?php } endforeach; ?>
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
									<a href="#faq-<?=$n1;?>-3" data-parent="#faq-list-<?=$n1;?>" data-toggle="collapse" class="accordion-toggle collapsed">
										<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
										CANCELAR CERTIFICADO
									</a>
								</div>
								

								<div class="panel-collapse collapse" id="faq-<?=$n1;?>-3">
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
															<button class="btn btn-info" type="button">
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
							</div>
							<br>
							<?php 
							    $n1=$n1+1;
							endforeach; } else {
								echo "<div style='padding-left: 2%;'>No se encontraron registros.</div>";
								} ?></div>



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
