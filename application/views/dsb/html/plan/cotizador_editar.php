<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

		<!-- jQuery library is required, see https://jquery.com/ -->
		<script type="text/javascript" src="<?=base_url()?>public/assets/js/jquery/jquery.js"></script>
	
		

		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
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
							<li><a href="<?=base_url()?>index.php/cotizador">Cotizador</a></li>
							<li class="active"><?=$accion?></li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							<?=str_replace("%20"," ",$nom);?>						
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
							<?php if($id==0):
									$cliente="";
									$nombre_cotizacion="";
									$codigo_cotizacion="";
									$carencia="";
									$mora="";
									$atencion="";
									$prima="";
									$cuerpo="";
									$prima_ad="";
									$num_afiliados="";
									$flg_activar="";
									$flg_dependientes="";
									$flg_cancelar="";
									else:
										foreach ($cotizador as $c):
											$cliente=$c->idclienteempresa;
											$nombre_cotizacion=$c->nombre_cotizacion;
											$codigo_cotizacion=$c->codigo_cotizacion;
											$carencia=$c->dias_carencia;
											$mora=$c->dias_mora;
											$atencion=$c->dias_atencion;
											$prima=$c->prima_monto;
											$cuerpo=$c->cuerpo_mail;
											$prima_ad=$c->prima_adicional*1;
											$num_afiliados=$c->num_afiliados;
											$flg_activar=$c->flg_activar;
											$flg_dependientes=$c->flg_dependientes;
											$flg_cancelar=$c->flg_cancelar;
											endforeach;
								endif; ?>
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="formCoti" name="formCoti" role="form" method="post">
									<input type="hidden" id="idcotizacion" name="idcotizacion" value="<?=$id;?>" />
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Cliente: </label>

										<div class="col-sm-9">
											<select id="cliente" name="cliente" value="" class="col-xs-10 col-sm-5" required="Seleccionar una opción de la lista">
												<option value="">Seleccionar</option>	
												<?php foreach($clientes as $c):
													if($cliente==$c->idclienteempresa):
														$est='selected';
														else:
															$est='';
														endif;?>	
													<option value="<?=$c->idclienteempresa;?>" <?=$est;?>><?=$c->nombre_comercial_cli;?></option>								
												<?php endforeach; ?>
											</select><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nombre de la cotización: </label>

										<div class="col-sm-9">
											<input type="text" id="nombre_cotizacion" name="nombre_cotizacion" class="col-xs-10 col-sm-5" value="<?=$nombre_cotizacion;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Código de la cotización: </label>

										<div class="col-sm-9">
											<input type="text" id="codigo_cotizacion" name="codigo_cotizacion" class="col-xs-10 col-sm-5" value="<?=$codigo_cotizacion;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Días de Carencia: </label>

										<div class="col-sm-9">
											<input type="number" id="carencia" name="carencia" class="col-xs-10 col-sm-5" value="<?=$carencia;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Días de Mora: </label>

										<div class="col-sm-9">
											<input type="number" id="mora" name="mora" class="col-xs-10 col-sm-5" value="<?=$mora;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Frecuencia en la Atención: </label>

										<div class="col-sm-9">
											<input type="number" id="atencion" name="atencion" class="col-xs-10 col-sm-5" value="<?=$atencion;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<!--<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Prima (S/.) Inc. IGV: </label>

										<div class="col-sm-9">
											<input type="number" id="prima" name="prima" class="col-xs-10 col-sm-5" value="<?=$prima;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Prima por Adicional (S/.) Inc. IGV: </label>

										<div class="col-sm-9">
											<input type="number" id="prima_adicional" name="prima_adicional" class="col-xs-10 col-sm-5" value="<?=$prima_ad;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>-->

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Límite de afiliados por certificado: </label>

										<div class="col-sm-9">
											<input type="number" id="num_afiliados" name="num_afiliados" class="col-xs-10 col-sm-5" value="<?=$num_afiliados;?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">¿Activación manual desde Admin? </label>

										<div class="col-sm-9">
											<input type="radio" id="res1" name="flg_activar" value="S" <?php if($flg_activar=='S'){echo "checked";} ?> required="required">
											<label for="res1">Sí </label>
											&nbsp;&nbsp;
											<input  type="radio" id="res2" name="flg_activar" value="N" <?php if($flg_activar=='N'){echo "checked";} ?> required="required" >
											<label for="res2">No</label><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">¿Afiliación de dependientes desde Admin? </label>

										<div class="col-sm-9">
											<input type="radio" id="res3" name="flg_dependientes" value="S" <?php if($flg_dependientes=='S'){echo "checked";} ?> required="required">
											<label for="res3">Sí </label>
											&nbsp;&nbsp;
											<input  type="radio" id="res4" name="flg_dependientes" value="N" <?php if($flg_dependientes=='N'){echo "checked";} ?> required="required" >
											<label for="res2">No</label><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">¿Cancelar certificado desde Admin? </label>

										<div class="col-sm-9">
											<input type="radio" id="res5" name="flg_cancelar" value="S" <?php if($flg_cancelar=='S'){echo "checked";} ?> required="required">
											<label for="res5">Sí </label>
											&nbsp;&nbsp;
											<input  type="radio" id="res6" name="flg_cancelar" value="N" <?php if($flg_cancelar=='N'){echo "checked";} ?> required="required" >
											<label for="res6">No</label><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Siguiente
											</button>
										</div>
									</div>
								</form>
							
							</div><!-- /.col -->
						</div>
					</div>
				</div><!-- /.main-content -->
				<br/>

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

		<script>
   			$(document).ready(function(){
   				$("#formCoti").submit(function(e){
   					e.preventDefault();
   					$.ajax({
   						url: "<?= BASE_URL()?>index.php/Cotizador_cnt/cotizador_guardar",
   						type: 'POST',
   						dataType: 'json',
   						data: $("#formCoti").serialize(),
   						complete:function(){
   							window.location.href = ("<?= BASE_URL()?>index.php/cotizador_cobertura")
   						},
   						success: function(data){

   						}
   					});
   					return false;
   				});
   			});
		</script>

	</body>
</html>
