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
	</head>

	<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<?php 
									$mensaje="";
									$est_boton="";
										foreach($asegurado as $a):
											$tit="ACTUALIZAR DATOS DEL DEPENDIENTE";
											$tipodoc=$a->tipoDoc_id;
											$doc=$a->aseg_numDoc;
											$ape1=$a->aseg_ape1;
											$ape2=$a->aseg_ape2;
											$nom1=$a->aseg_nom1;
											$nom2=$a->aseg_nom2;
											$fecnac=$a->aseg_fechNac;
											$direc=$a->aseg_direcc;
											$telf=$a->aseg_telf;
											$ec=$a->aseg_estCiv;
											$sexo=$a->aseg_sexo;
											$correo=$a->aseg_email;
											$dep=$a->dep;
											$prov=$a->prov;
											$dist=$a->dist;
											$asegurado=$aseg_id;
											$caso=1;
											if(!empty($aseg_ver)){
												$mensaje="El documento ya existe.";
												$est_boton='disabled = "true"';
											}
										endforeach;  ?>
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>
								<?=$tit;?>								
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" name="aseg" id="aseg" role="form" method="post" action="<?=base_url()?>index.php/aseg_up">					
									
									<input type="hidden" id="aseg_id" name="aseg_id" value="<?=$asegurado;?>" />
									<input type="hidden" name="cert_id" id="cert_id" value="<?=$cert_id;?>">
									<input type="hidden" name="tipoop" id="tipoop" value="1">
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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1" > Documento: </label>

										<div class="col-sm-4">
											<input onkeydown="" onkeyup="buscardni()" class="form-control input-mask-date" type="text" id="doc" name="doc" placeholder="" value="<?=$doc;?>" required>
										</div><label style="color: #E41919;"><?=$mensaje?></label>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apellido Paterno: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="text" id="ape1" name="ape1" placeholder="" required value="<?=$ape1;?>">
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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Fecha de Nacimiento: </label>

										<div class="col-sm-4">
											<input class="form-control input-mask-date" type="date" id="fec_nac" name="fec_nac" placeholder="" value="<?=$fecnac?>" required>
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
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Correo electrónico: </label>

										<div class="col-sm-9">
											<input type="text" id="correo" name="correo" class="col-xs-10 col-sm-10" value="<?=$correo;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Teléfono: </label>

										<div class="col-sm-9">
											<input type="text" id="telf" name="telf" placeholder="" class="col-xs-10 col-sm-5" value="<?=$telf;?>">
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
							</div><!-- /.col -->
						</div>	
					</div>
				</div><!-- /.main-content -->			
			</div><!-- /.main-container -->
		
		<!-- basic scripts -->
		<form id="buscar" name="buscar" method="post" action="<?=base_url();?>index.php/verifica_dni">
									<input type="hidden" name="doc_copy" value="" id="doc_copy">
									<input type="hidden" name="tipodoc_copy" value="" id="tipodoc_copy">
									<input type="hidden" name="cert_copy" value="<?=$cert_id;?>">
									<input type="hidden" name="tipoop_copy" value="1">
									<input type="hidden" name="aseg_id_copy" id="aseg_id_copy">
									</form>

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script><script src="<?=  base_url()?>public/assets/js/jquery.js"></script>

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
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

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

		<script type="text/javascript">
			function guardar() {
				parent.location.reload(true);
				parent.$.fancybox.close();
			}

			function buscardni() {
				document.buscar.doc_copy.value = document.getElementById("doc").value;
				document.buscar.tipodoc_copy.value = document.getElementById("tipodoc").value;
				document.buscar.aseg_id_copy.value = document.getElementById("aseg_id").value;
				buscar.submit();
			}
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.onpage-help.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/docs/assets/js/themes/sunburst.css">

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>
</body></html>