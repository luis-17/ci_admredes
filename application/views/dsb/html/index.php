<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Dashboard - Redes Admin</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />


		<!-- ace settings handler -->
		<script src="public/assets/js/ace-extra.js"></script>

	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("/sideBar.php");?>
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
							<li class="active">Menú</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>Red Salud Admin <small></small></h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>

									<i class="ace-icon fa fa-check green"></i>

								  	Bienvenido a 
								  	<strong class="green">
									  Red-Salud Admin
									  <small>V1.1</small>
									</strong>,
									Administrador de Aplicaciones y procesos.
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
			window.jQuery || document.write("<script src='public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='public/assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="public/assets/js/excanvas.js"></script>
		<![endif]-->
		<script src="public/assets/js/jquery-ui.custom.js"></script>
		<script src="public/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="public/assets/js/jquery.easypiechart.js"></script>
		<script src="public/assets/js/jquery.sparkline.js"></script>
		<script src="public/assets/js/flot/jquery.flot.js"></script>
		<script src="public/assets/js/flot/jquery.flot.pie.js"></script>
		<script src="public/assets/js/flot/jquery.flot.resize.js"></script>

		<!-- ace scripts -->
		<script src="public/assets/js/ace/elements.scroller.js"></script>
		<script src="public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="public/assets/js/ace/elements.fileinput.js"></script>
		<script src="public/assets/js/ace/elements.typeahead.js"></script>
		<script src="public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="public/assets/js/ace/elements.spinner.js"></script>
		<script src="public/assets/js/ace/elements.treeview.js"></script>
		<script src="public/assets/js/ace/elements.wizard.js"></script>
		<script src="public/assets/js/ace/elements.aside.js"></script>
		<script src="public/assets/js/ace/ace.js"></script>
		<script src="public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="public/assets/js/ace/ace.sidebar.js"></script>
		<script src="public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="public/assets/js/ace/ace.widget-box.js"></script>
		<script src="public/assets/js/ace/ace.settings.js"></script>
		<script src="public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="public/docs/assets/js/rainbow.js"></script>
		<script src="public/docs/assets/js/language/generic.js"></script>
		<script src="public/docs/assets/js/language/html.js"></script>
		<script src="public/docs/assets/js/language/css.js"></script>
		<script src="public/docs/assets/js/language/javascript.js"></script>
	</body>
</html>