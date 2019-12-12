<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

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
							<li>
								<a href="<?=base_url()?>index.php/certificado2/<?=$doc?>/<?=$id2?>">Certificado</a>
							</li>
							<li class="active">Detalle Certificado</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Detalle Certificado
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-3">
								<div class="alert alert-info">
									Sr. / Sra. / Srta. <b><?=$afiliado?></b>, ¿Es correcto?
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									Sr. / Sra. / Srta. <b><?=$nombre?></b>, ¿me puede brindar un número telefónico de contacto por si se pierde la llamada?
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>
								<div class="alert alert-info">
									Muchas gracias Sr. / Sra. / Srta. <b><?=$nombre?></b>. Por favor cuénteme ¿en qué puedo ayudarlo en esta oportunidad?
								</div>
								<div class="alert alert-danger">
									Afiliado menciona que desea una cita médica.
								</div>
								<div class="alert alert-info">
									¿En qué ciudad o distrito desea ser atendido?	
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>						
							</div>
							<div class="col-xs-9">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
														CERTIFICADO
												</a>
											</div>

											<div class="panel-collapse collapse" id="faq-2-1">
												<div class="panel-body">													
													<?php foreach ($certificado as $cert): ?>
													<h4 class="blue">
														<i class="ace-icon glyphicon glyphicon-check bigger-110"></i>
														Datos del Certificado <?=$cert->cert_num;?>
													</h4>
													<?php 
														$id=$cert->cert_id;
														$canal=$cert->idclienteempresa;
														$num=$cert->dias_atencion;
														$num= $num-1;
														$hoy= time();
														$inicio=$cert->cert_ini;	
														$finvig=$cert->cert_finVig;
														$finvig=strtotime($finvig);											
														$inicio2= strtotime($inicio);
														$inicio=date("d-m-Y", strtotime($cert->cert_iniVig));
														$fin=$cert->cert_fin;
														$fin2= strtotime($fin); 
														$fin=date("d-m-Y", strtotime($cert->cert_finVig));
														$can=$cert->fec_can;
														$cliente = $cert->nombre_comercial_cli;
														$plan = $cert->nombre_plan;
														$plan_id=$cert->plan_id;
														$flg_dependientes = $cert->flg_dependientes;
														if($can==''){
															$can='-';
														}else{
															$can=date("d-m-Y", strtotime($can));
														}
														$cobro=$cert->ultimo_cobro;
														$cobro=date("d/m/Y", strtotime($cobro));
														$cobertura=$cert->ultima_cobertura;
														$cobertura=date("d/m/Y", strtotime($cobertura));
														$act_man=$cert->cert_upProv;

														if($cert->cert_estado==1){
															$e=1;
															$estado="Vigente";	
															}elseif($hoy<=$finvig){
																$estado="Vigente";
																$e=1;
																}else{
																	$estado="Cancelado";
																	$e=3;	
																}	

														if($e==1){
															if($hoy>$inicio2 && $fin2>=$hoy){
																$estado2="Activo";
																$boton="";
																$titulo="";
																$ruta="";	
																$e2=1;	
															}else{
																if($act_man==1){
																	$estado2="Activo Manualmente";
																	$boton="ace-icon glyphicon glyphicon-remove";
																	$titulo="Inactivar Manualmente";
																	$ruta="cancelar_certificado";
																	$e2=1;
																}elseif($hoy<$inicio2){
																	$estado2="En carencia";
																	$boton="";
																	$titulo="";
																	$ruta="";
																	$e2=3;
																}else{
																	$estado2="Inactivo";
																	$boton="ace-icon glyphicon glyphicon-ok";
																	$titulo="Activar Manualmente";
																	$ruta="activar_certificado";
																	$e2=3;
																}
															}
														$fin3=date("Y-m-d", strtotime($fin2));
														}else{
															$estado2="Inactivo";
															$boton="";
															$titulo="";
															$ruta="";
															$e2=3;
														$fin3=date("Y-m-d", strtotime($finvig));
														}
														?>
												<div class="col-xs-9 col-sm-12">
													<div class="widget-box transparent">
														<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">	

																<div class="profile-info-name"> Est. Certificado: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$estado?></span>
																</div>

																<div class="profile-info-name"> Est. Atención: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="username"><?=$estado2;?></span>
																	<?php if($cert->flg_activar=='S' && $e==1):?>
																		<a href="<?=  base_url()?>index.php/<?=$ruta?>/<?=$id?>/<?=$doc?>" title="<?=$titulo;?>">
																			<span class="<?=$boton ?>"></span>
																		</a>
																	<?php endif; ?>
																</div>

																<div class="profile-info-name"> Últ. Cobro: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cobro;?></span>
																</div>
															</div>
																		
															<div class="profile-info-row">

																<div class="profile-info-name"> Cliente: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->nombre_comercial_cli;?></span>
																</div>
																
																<div class="profile-info-name"> Plan: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->nombre_plan;?></span>
																</div>

																<div class="profile-info-name"> Precio: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$cert->prima_monto;?></span>
																</div>
															</div>

															<div class="profile-info-row">
																			
																<div class="profile-info-name"> Inicio Vigencia: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$inicio;?></span>
																</div>
																
																<div class="profile-info-name"> Fin Vigencia: </div>
																<div class="profile-info-value">
																	<span class="editable editable-click" id="age"><?=$fin;?></span>
																</div>
																
																<div class="profile-info-name"> Cancelación: </div>
																	<div class="profile-info-value">
																		<span class="editable editable-click" id="age"><?=$can;?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>												
											</div>
										</div>

										<!-- coberturas del plan -->
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-5" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													COBERTURAS DEL PLAN
												</a>
											</div>

											<div class="panel-collapse collapse" id="faq-2-5">
												<div class="panel-body">
													<h4 class="blue">
														<i class="ace-icon glyphicon glyphicon-file bigger-110"></i>
														<?=$cliente?>: <?=$plan?>
													</h4>
													<br>

													<table id="simple-table" class="table table-striped table-bordered table-hover">
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
												</div>
											</div>
										</div>
										<!--  end coberturas del plan -->

										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													CONTRATANTE
												</a>
											</div>

											<div class="panel-collapse collapse" id="faq-2-2">
												<div class="panel-body">
													<h4 class="blue">
														<i class="ace-icon glyphicon glyphicon-user bigger-110"></i>
														Datos del Responsable de Pago
													</h4>
													<br>
													<?php foreach ($contratante as $co){ 
														$dep = $co->dep;
														$prov = $co->prov;
														$dist = $co->dist;?>

													<form role="form" method="post" name="form_cont" id="form_cont" action="<?=base_url()?>index.php/cert_cont_save">	
														<input type="hidden" name="cont_id" id="cont_id" value="<?=$co->cont_id?>">
														<input type="hidden" name="id2" id="id2" value="<?=$id2?>">
														<input type="hidden" name="doc" id="doc" value="<?=$doc?>">
															<div class="form-row">
																<div class="form-group col-md-3">
																	<b class="text-primary">Tipo de Documento</b>
																	<select disabled="true" name="tipodoc" id="tipodoc" required="Seleccione una opción de la lista" class="form-control" value="<?=$d->cont_tipoDoc;?>">
																		<option value="1" <?php if($co->cont_tipoDoc==1): echo "selected"; endif;?> >DNI</option>
						                                                <option value="2" <?php if($co->cont_tipoDoc==2): echo "selected"; endif;?> >Pasaporte</option>
						                                                <option value="4" <?php if($co->cont_tipoDoc==4): echo "selected"; endif;?> >Carné de extranjería</option>
						                                            </select>
																</div>
																<div class="form-group col-md-3">
																	<b class="text-primary">Nro de Documento</b>
																	<input value="<?=$co->cont_numDoc;?>" type="text" id="dni" name="dni" required="Digite el DNI"  class="form-control" disabled="true">
																</div>
															</div>
															<div class="form-group col-md-6">
															<p><img src=""></p>
															<p><img src=""></p>
															</div>
															<div class="form-row">		
																<div class="form-group col-md-3">
																	<b class="text-primary">Apellido Paterno</b>
																	<input  value="<?=$co->cont_ape1;?>" type="text" id="ape1" name="ape1" required="Digite el apellido paterno"  class="form-control" disabled="true">
																</div>		
																<div class="form-group col-md-3">
																	<b class="text-primary">Apellido Materno</b>
																	<input value="<?=$co->cont_ape2?>" type="text" id="ape2" name="ape2" required="Digite el apellido materno"  class="form-control" disabled="true">
																</div>	
																<div class="form-group col-md-3">
																	<b class="text-primary">Nombre 1</b>
																	<input value="<?=$co->cont_nom1?>" type="text" id="nom1" name="nom1" required="Digite el Nombre"  class="form-control" disabled="true">
																</div>	
																<div class="form-group col-md-3">
																	<b class="text-primary">Nombre 2</b>
																	<input value="<?=$co->cont_nom2?>" type="text" id="nom2" name="nom2"  class="form-control" disabled="true">
																</div>									
														 	</div>
														 	<div class="form-row">		
																<div class="form-group col-md-3">
																	<b class="text-primary">Dirección</b>
																	<input value="<?=$co->cont_direcc?>" type="text" id="direcc" name="direcc"  class="form-control">
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
																	<input type="text" id="telf" name="telf" value="<?=$co->cont_telf?>" class="form-control">
																</div>
																<div class="form-group col-md-3">
																	<b class="text-primary">Correo Electrónico</b>
																	<input value="<?=$co->cont_email?>" type="text" id="correo" name="correo"   class="form-control">
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
												<?php } ?>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-3" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													AFILIADOS
												</a>
											</div>
											

											<div class="panel-collapse collapse" id="faq-2-3">
												<div class="panel-body">
													<h4 class="blue">
														<i class="blue ace-icon fa fa-users bigger-110"></i>
														Listado de Afiliados al Certificado
													</h4>
													<?php if($flg_dependientes=='S'){ ?>
													<div class="form-group col-md-12" style="text-align: right;">	
														<a  class="boton fancybox btn btn-info" data-fancybox-width="950" data-fancybox-height="690" href="<?=base_url()?>index.php/aseg_nuevo/<?=$id?>/<?=$plan_id?>" type="submit">
															<i class="ace-icon glyphicon glyphicon-plus bigger-110"></i>
																Agregar Afiliado
														</a>
													</div>													
													<br><br><br>
													<?php } ?>
														<table id="example2" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>DNI</th>
																	<th>Asegurado</th>
																	<th>Última Atención</th>
																	<th>Teléfono</th>
																	<th>Email</th>
																	<th></th>
																</tr>
															</thead>

															<tbody>
															<?php foreach ($asegurado as $aseg):
															$idaseg=$aseg->aseg_id;
															$fecha=date("d-m-Y");
															$hoy2=strtotime($fecha."- ".$num." days");
															$fec=$aseg->ultima_atencion;
															$fec2= strtotime($fec);
															$fin4 = $aseg->cert_finVig;
															$open_etiqueta="";
															$end_etiqueta="";
															$estado_certase = 1;
															if($aseg->cert_estado==3){
																$fin4 = strtotime($fin4);
																$fecha2 = strtotime($fecha);
																if($fecha2>$fin4){
																	$open_etiqueta="<strike>";
																	$end_etiqueta="</strike>";
																	$estado_certase = 3;
																}
															}
																if($fec==''){
																	$fec='-';
																}else{
																	$fec=date("d/m/Y", strtotime($fec));
																}										
																$certase = $aseg->certase_id;
															?>
																<tr>
																	<td><?=$open_etiqueta?><?=$aseg->aseg_numDoc;?><?=$end_etiqueta?></td>
																	<td><?=$open_etiqueta?><?=$aseg->asegurado;?><?=$end_etiqueta?></td>
																	<td><?=$open_etiqueta?><?=$fec;?><?=$end_etiqueta?></td>
																	<td><?=$open_etiqueta?><?=$aseg->aseg_telf;?><?=$end_etiqueta?></td>
																	<td><?=$open_etiqueta?><?=$aseg->aseg_email;?><?=$end_etiqueta?></td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<?php if($estado_certase==1) { ?>
																				<div title="Editar Asegurado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_editar/<?=$idaseg?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																					</a>
																				</div>
																				<?php } ?>
																				<div title="Ver Atenciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_atenciones/<?=$idaseg?>/<?=$id?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-eye bigger-120 blue"></i>
																					</a>
																				</div>
																				<div title="Registrar Incidencia" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/registrar_incidencia/<?=$id?>/<?=$idaseg?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-comments-o bigger-120 blue"></i>
																					</a>
																				</div>																				
																				<?php if($e==1&&$e2==1&&$hoy2>$fec2&&$estado_certase==1) { ?>
																				<div title="Reservar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/validar_evento/<?=$plan_id?>/<?=$id?>/<?=$idaseg?>/<?=$certase?>/<?=$fin3?>" data-fancybox-width="1600" data-fancybox-height="1095">
																						<i class="ace-icon fa fa-external-link bigger-120 blue"></i>
																					</a>
																				</div>
																				<?php } ?>
																				
																		</div>

																		<div class="hidden-md hidden-lg">
																			<div class="inline pos-rel">
																				<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																					<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																				</button>

																				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">			<?php if($estado_certase==1) { ?>
																					<li>
																						<div title="Editar Asegurado" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_editar/<?=$id?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																							</a>
																						</div>
																					</li>
																					<?php } ?>
																					<li>
																						<div title="Ver Atenciones" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/aseg_atenciones/<?=$idaseg?>/<?=$id?>" data-fancybox-width="950" data-fancybox-height="690">
																								<i class="ace-icon fa fa-eye bigger-120 blue"></i>
																							</a>
																						</div>

																					</li>
																					<li>
																						<div title="Registrar Incidencia" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/registrar_incidencia/<?=$id?>/<?=$idaseg?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon fa fa-comments-o bigger-120 blue"></i>
																							</a>
																						</div>
																					</li>
																					<?php if($e==1&&$e2==1&&$hoy2>$fec2&&$estado_certase==1) { ?>
																					<li>
																						<div title="Reservar Atención" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																							&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/validar_evento/<?=$plan_id?>/<?=$id?>/<?=$idaseg?>/<?=$certase?>/<?=$fin3?>" data-fancybox-width="1600" data-fancybox-height="1095">
																								<i class="ace-icon fa fa-external-link bigger-120 blue"></i>
																							</a>
																						</div>
																					</li>
																					<?php } ?>
																				</ul>
																			</div>
																		</div>
																	</td>
																</tr>
															<?php endforeach;?>
															</tbody>
														</table>							
												</div>
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
										<div class="panel panel-default">
											<div class="panel-heading">
												<a href="#faq-2-4" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
													<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
													COBROS
												</a>
											</div>
											<div class="panel-collapse collapse" id="faq-2-4">
												<div class="panel-body">
													<div class="col-xs-12">
														<h4 class="blue">
															<i class="blue ace-icon fa fa-credit-card bigger-110"></i>
															Cobros Registrados
														</h4>
														<table id="example" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>ID</th>
																	<th>Fecha Cobro</th>
																	<th>Vez Cobro</th>
																	<th>Importe</th>
																	<th>Inicio Cobertura</th>
																	<th>Fin Cobertura</th>
																	<th>Ver Boleta</th>
																</tr>
															</thead>

															<tbody>
															<?php foreach ($cobros as $cob):
															$cobro=$cob->cob_fechCob;
															$cobro=date("d/m/Y", strtotime($cobro));
															$inicio2=$cob->cob_iniCobertura;;
															$inicio2=date("d/m/Y", strtotime($inicio2));
															$fin2=$cob->cob_finCobertura;
															$fin2=date("d/m/Y", strtotime($fin2));
															?>
																<tr>
																	<td><?=$cob->cob_id?></td>
																	<td><?=$cobro;?></td>
																	<td><?=$cob->cob_vezCob;?></td>
																	<td><?=$cob->importe;?></td>
																	<td><?=$inicio2;?></td>
																	<td><?=$fin2;?></td>
																	<td><?php if($cob->idcomprobante!='0'){ ?>
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Ver Boleta" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/generarPdf/<?=$cob->idcomprobante?>/<?=$canal?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon fa fa-file-pdf-o bigger-120"></i>
																			</a>
																		</div>
																		<div title="Enviar por Correo" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/enviarPdf/<?=$cob->idcomprobante?>/<?=$canal?>" data-fancybox-width="950" data-fancybox-height="290">
																				<i class="ace-icon fa fa-envelope bigger-120"></i>
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
																					<div title="Ver Boleta" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/generarPdf/<?=$cob->idcomprobante?>/<?=$plan?>" data-fancybox-width="950" data-fancybox-height="690">
																						<i class="ace-icon fa fa-file-pdf-o bigger-120"></i>
																			</a>
																		</div>
																					</li>
																					<li>
																					<div title="Enviar por Correo" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a class="boton fancybox" href="<?=  base_url()?>index.php/enviarPdf/<?=$cob->idcomprobante?>/<?=$plan?>" data-fancybox-width="950" data-fancybox-height="290">
																				<i class="ace-icon fa fa-envelope bigger-120"></i>
																			</a>
																		</div>
																					</li>
																				</ul>
																	</div>


																		<?php } ?></td>
																</tr>
															<?php endforeach;?>
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
												</div>								
											</div>
										</div>
									</div>
								<?php endforeach; ?>
								</div>
							</div>

								<!-- PAGE CONTENT ENDS -->
						</div><!-- /.col -->
					</div><!-- /.row -->					
							
				</div><!-- /.page-content -->
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
