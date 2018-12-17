<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

		<!-- jQuery library is required, see http://jquery.com/ -->
		<script type="text/javascript" src="<?=base_url()?>public/assets/js/jquery/jquery.js"></script>
	
		

		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
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
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="<?=base_url()?>">Inicio</a>
							</li>
							<li><a href="#">Seguridad</a></li>
							<li><a href="<?=base_url()?>index.php/usuario">Usuarios</a></li>
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
									
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/usuario_guardar">
									<input type="hidden" id="iduaurio2" name="idusuario2" value="<?=$idusuario2;?>" />
									<input type="hidden" name="idcolaborador2" id="idcolaborador2" value="<?=$idcolaborador2?>">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Área: </label>

										<div class="col-sm-9">
											<select id="idtipousuario" name="idtipousuario" class="col-xs-10 col-sm-5" required="Seleccionar una opción de la lista">
												<option value="">Seleccionar</option>	
												<?php foreach($tipos_usuario as $tp):
													if($tipo_usuario==$tp->idtipousuario):
														$est='selected';
														else:
															$est='';
														endif;?>	
													<option value="<?=$tp->idtipousuario;?>" <?=$est;?>><?=$tp->descripcion_tu;?></option>								
												<?php endforeach; ?>
											</select><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> DNI: </label>

										<div class="col-sm-9">
											<input type="text" id="dni" name="dni" class="col-xs-10 col-sm-5" value="<?=$dni?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nombres: </label>

										<div class="col-sm-9">
											<input type="text" id="nombres" name="nombres" class="col-xs-10 col-sm-5" value="<?=$nombres?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Apellido Paterno: </label>

										<div class="col-sm-9">
											<input type="text" id="ap_paterno" name="ap_paterno" class="col-xs-10 col-sm-5" value="<?=$ap1?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Apellido Materno: </label>

										<div class="col-sm-9">
											<input type="text" id="ap_materno" name="ap_materno" class="col-xs-10 col-sm-5" value="<?=$ap2?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Fecha de Nacimiento: </label>

										<div class="col-sm-9">
											<input type="date" id="fecha" name="fecha" class="col-xs-10 col-sm-5" value="<?=$fec?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Correo electrónico: </label>

										<div class="col-sm-9">
											<input type="email" id="correo" name="correo" class="col-xs-10 col-sm-5" value="<?=$correo?>" required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">N° Telefónico: </label>

										<div class="col-sm-9">
											<input type="number" id="telf" name="telf" class="col-xs-10 col-sm-5" value="<?=$telf?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Usuario: </label>

										<div class="col-sm-9">
											<input type="text" id="username" name="username" class="col-xs-10 col-sm-5" value="<?=$usuario?>"  required><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
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
	</body>
</html>
