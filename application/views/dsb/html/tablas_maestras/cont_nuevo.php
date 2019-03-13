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
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		
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
	                $("#dis").html(data);
	                });            
	            });
	       })
	    });
	    /*fin de la funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
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
	</script>
		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

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
								<a href="#">Inicio</a>
							</li>

							<li>
								<a href="#">Tablas Maestras</a>
							</li>
							<li class="active">DNI de Prueba</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">	

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Nuevo DNI de Prueba
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php 
								$cont=0;
								if(!empty($cont)){
									foreach ($cont as $c) {
										$cont=$c->cont_id;
									}
								}else {
									$cont=0;
								}
											$mensaje="";
											$est_boton="";
											if (!empty($aseg_ver)) {
													$caso=2;
													$tit="NUEVO RESPONSABLE DE PAGO";
													if(!empty($cert_ver)){
														$mensaje="El documento ya se encuentra afiliado al plan.";
														$est_boton='disabled = "true"';
													}
													foreach($aseg_ver as $av):
													$tipodoc=$av->tipoDoc_id;
													$doc=$av->aseg_numDoc;
													$ape1=$av->aseg_ape1;
													$ape2=$av->aseg_ape2;
													$nom1=$av->aseg_nom1;
													$nom2=$av->aseg_nom2;
													$fecnac=$av->aseg_fechNac;
													$direc=$av->aseg_direcc;
													$telf=$av->aseg_telf;
													$ec=$av->aseg_estCiv;
													$sexo=$av->aseg_sexo;
													$correo=$av->aseg_email;
													$dep=$av->dep;
													$prov=$av->prov;
													$dist=$av->dist;
													$asegurado=$av->aseg_id;
													endforeach; 
													}else{
														$tit="NUEVO RESPONSABLE DE PAGO";
														$caso=3;
														$tipodoc=$tipdoc;
														$doc=$doc;
														$ape1="";
														$ape2="";
														$nom1="";
														$nom2="";
														$fecnac="";
														$direc="";
														$telf="";
														$ec="";
														$sexo="";
														$correo="";											
														$dep="";
														$prov="";
														$dist="";
														$asegurado="";	
									} ?>
								<div align="center">
									<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" name="aseg" id="aseg" role="form" method="post" action="<?=base_url()?>index.php/cont_save2">					
									
									<input type="hidden" id="aseg_id" name="aseg_id" value="<?=$asegurado;?>" />
									<input type="hidden" name="tipoop" id="tipoop" value="<?=$caso?>">
									<input type="hidden" name="cont_id" id="cont_id" value="<?=$cont?>">
									<input type="hidden" name="cont" id="cont" value="1">
									<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Canal</label>
										<div class="col-sm-4">
											<select name="canal" id="canal" required="Seleccione una opción de la lista" class="form-control" required="Seleccionar una opción de la lista">
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
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan</label>
											<div class="col-sm-4">
											<select name="plan" id="plan" required="Seleccione una opción de la lista"  class="form-control" required="Seleccionar una opción de la lista">											
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
										</div>	
									<div class="form-group">
									<?php 
									$hoy=date('Y-m-d');
									$month = date('m');
									$month2 = date('m')+6;
							        $year = date('Y');
							        $day = date("d", mktime(0,0,0, $month, 0, $year));
							        $inicio = date('Y-m-d', mktime(0,0,0, $month+1, 1, $year));
							        $fin = date('Y-m-d', mktime(0,0,0, $month2, 1, $year)); 
							        ?>
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Inicio de Vigencia: </label>

										<div class="col-sm-4">
											<input  class="form-control input-mask-date" type="date" id="inivig" name="inivig" value="<?=$inicio;?>"  required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Fin de Vigencia: </label>

										<div class="col-sm-4">
											<input  class="form-control input-mask-date" type="date" id="finvig" name="finvig" value="<?=$fin;?>"  required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tipo de Documento: </label>

										<div class="col-sm-4">
											<select name="tipodoc" id="tipodoc" required="Seleccione una opción de la lista" class="form-control" value="<?=$d->cont_tipoDoc;?>">
												<option value="">Seleccione</option>
												<option value="1" <?php if($tipodoc==1): echo "selected"; endif;?> >DNI</option>
                                                <option value="2" <?php if($tipodoc==2): echo "selected"; endif;?> >Pasaporte</option>
                                                <option value="4" <?php if($tipodoc==4): echo "selected"; endif;?> >Carné de extranjería</option>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Documento: </label>

										<div class="col-sm-4">
											<input onkeydown="" onkeyup="buscardni()" class="form-control input-mask-date" type="text" id="doc" name="doc" placeholder="" value="<?=$doc;?>"  required>
										</div><label style="color: #E41919;"><?=$mensaje?></label>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apellido Paterno: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="text" id="ape1" name="ape1" placeholder="" value="<?=$ape1;?>" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apellido Materno: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="text" id="ape2" name="ape2" placeholder="" value="<?=$ape2;?>" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nombre 1: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="text" id="nom1" name="nom1" placeholder="" value="<?=$nom1;?>" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nombre 2: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="text" id="nom2" name="nom2" placeholder="" value="<?=$nom2?>">
										</div>
									</div>									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Departamento: </label>

										<div class="col-sm-9">
											<select name="dep" id="dep"  class="col-xs-10 col-sm-5">
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
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Provincia: </label>

										<div class="col-sm-9">
											<select id="prov" name="prov" class="col-xs-10 col-sm-5">
											<option value="">Seleccionar</option>
											 <?php foreach ($provincia3 as $p3): 
                                            	if($prov==$p3->idprovincia):
                                            		$estprov='selected';
												else:
													$estprov='';
												endif;
                                            		?>
                                                <option value="<?=$p3->idprovincia;?>" <?=$estprov?> ><?=$p3->descripcion_ubig;?></option>
                                                <?php endforeach; ?>                                                            
                                         	</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Distrito: </label>
										<div class="col-sm-9">
											<select name="dis" id="dis" class="col-xs-10 col-sm-5">
											<option value="">Seleccionar</option>
                                                <?php foreach ($distrito3 as $d3): 
                                            	if($dist==$d3->iddistrito):
                                            		$estdist='selected';
												else:
													$estdist='';
												endif;
                                            		?>
                                                <option value="<?=$d3->iddistrito;?>" <?=$estdist?> ><?=$d3->descripcion_ubig;?></option>
                                                <?php endforeach; ?> 
                                            </select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Dirección: </label>

										<div class="col-sm-9">
											<input type="text" id="direccion" name="direccion" class="col-xs-10 col-sm-10" value="<?=$direc;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Teléfono: </label>

										<div class="col-sm-9">
											<input type="text" id="telf" name="telf" placeholder="" class="col-xs-10 col-sm-5" value="<?=$telf;?>">
										</div>
									</div>

									<div id="res_afi">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Fecha de Nacimiento: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="date" id="fec_nac" name="fec_nac" placeholder="" value="<?=$fecnac?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Género: </label>

										<div class="col-sm-9">
											<select id="genero" name="genero" value="" class="col-xs-10 col-sm-5">
												<option value="">Seleccionar</option>
												<option value="F" <?php if($sexo=='F'): echo "selected"; endif;?> >Femenino</option>
												<option value="M" <?php if($sexo=='M'): echo "selected"; endif;?> >Masculino</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Estado Civil: </label>

										<div class="col-sm-9">
											<select id="ec" name="ec" value="" class="col-xs-10 col-sm-5">
												<option value="">Seleccionar</option>
												<option value="S" <?php if($ec=='S'): echo "selected"; endif;?> >Soltero</option>
												<option value="C">Casado</option>
												<option value="CO">Conviviente</option>
												<option value="D">Divorciado</option>
												<option value="V">Viudo</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Correo electrónico: </label>

										<div class="col-sm-9">
											<input type="text" id="correo" name="correo" class="col-xs-10 col-sm-10" value="<?=$correo;?>">
										</div>
									</div>

									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9" style="text-align: right;">
											<button class="btn btn-info" type="submit" <?=$est_boton;?> >
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
										</div>
									</div>
								</form>
								<!-- basic scripts -->
								<form id="buscar" name="buscar" method="post" action="<?=base_url();?>index.php/verifica_dni_in2">
									<input type="hidden" name="doc_copy" value="" id="doc_copy">
									<input type="hidden" name="tipodoc_copy" value="" id="tipodoc_copy">
									<input type="hidden" name="canal_copy" id="canal_copy" value="">									
									<input type="hidden" name="plan_copy" id="plan_copy" value="">
								</form>
								</div><!-- PAGE CONTENT ENDS -->
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
							<span class="blue bolder">Red Salud</span>
							Application &copy; 2018
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
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

		<script type="text/javascript">
			function guardar() {
				parent.location.reload(true);
				parent.$.fancybox.close();
			}

			function buscardni() {
				document.buscar.doc_copy.value = document.getElementById("doc").value;
				document.buscar.tipodoc_copy.value = document.getElementById("tipodoc").value;
				document.buscar.canal_copy.value = document.getElementById("canal").value;
				document.buscar.plan_copy.value = document.getElementById("plan").value;
				buscar.submit();
			}
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
		<script src="<?=base_url()?><?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
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