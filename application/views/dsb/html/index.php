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
		<title>Dashboard - Redes Admin</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />


		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("sideBar.php");?>
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
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="widget-main">
									<div class="col-xs-12"></br>
										<div style="text-align: right;" class="col-xs-12">											 
			  								<div class="">
												<h4 class="">
													<b>Descargar Red Médica<b>
													<a class="" title="Descargar Red Médica" href="<?=base_url()?>index.php/red_medica"><img style="width: 35px;" src="<?=base_url()?>public/assets/images/xls.png"/></a>	
													<a class="" title="Descargar Red Médica" href="<?=base_url()?>index.php/red_medica2"><img style="width: 35px;" src="<?=base_url()?>public/assets/images/pdf.png"/></a>												
												</h4>
											</div>											
										</div>
										<div class="col-xs-3"> <img style="width: 90%;" src="<?=base_url()?>public/assets/images/3.png"/></div>							
			  							<div class="col-xs-9"><p><h2 style="font-family: 'Montserrat', sans-serif; font-size:36px; color: #161b52;">Hola <?php $user = strtoupper($nombres_col.' '.$ap_paterno_col);
			  							echo $user; ?>!!</h2></p>
			  							<p><h2 style="color: #B61414;">¡Te damos la bienvenida al sistema Red Salud Admin!</h2></p></div>
			  							<div class="col-xs-3"><br><br><br></div>
			  							<div class="col-xs-9">
			  								<div class="well well-sm">
												<h4 class="lighter no-margin-bottom">
													 <img style="width: 25px;" src="<?=base_url()?>public/assets/images/call_center.png"/>
													<b>Brinda soporte al afiliado</b>
												</h4>
											</div>
											<div class="alert alert-info">
												RED SALUD <b><?=$turno?></b>, lo / la saluda <b><?=$nombres_col?> <?=$ap_paterno_col?></b> ¿Por favor me brinda su número de DNI? 
											</div>
											<div class="alert alert-danger">
												Respuesta del Afiliado
											</div>

											<form method="post" action="<?=base_url()?>index.php/consulta_certificado">
												<span class="input-icon">
													<input type="text" id="nom" name="nom" placeholder="Apellidos ..." class="nav-search-input" id="nav-search-input" size="30" value="<?=$nom;?>">									
												</span>	
												<span class="input-icon">
													<input type="text" id="doc" name="doc" placeholder="DNI contratante ó asegurado ..." class="nav-search-input" id="nav-search-input" size="30" value="<?=$id; ?>">
													<button type="submit" class="btn btn-info btn-xs">
														<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
													</button>
												</span>		
											</form>		
			  							</div>
								</div>

								<!-- /.row -->
                                <!-- PAGE CONTENT ENDS -->
                            </div>
							<!-- /.col -->
						</div><!-- /.row -->
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