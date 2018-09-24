<?php
	$user = $this->session->userdata('user');
	extract($user);
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


	
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery.gritter.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/select2.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap-editable.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="public/assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="public/assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="public/assets/js/html5shiv.js"></script>
		<script src="public/assets/js/respond.js"></script>
		<![endif]-->
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
								<a href="<?=base_url();?>">Inicio</a>
							</li>
							<li class="active">Mi Perfil</li>
						</ul><!-- /.breadcrumb -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Cambiar Contraseña
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
									<div class="col-xs-12">	
													<form class="form-horizontal" method="post" action="<?=base_url()?>index.php/actualizar_pass">
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Contraseña actual: </label>

														<div class="col-sm-4">
															<input type="password" id="passold" name="passold" required value="<?=$oldpass?>">
														</div><label style="color: #E41919;"><?=$mensaje1?></label>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nueva contraseña: </label>

														<div class="col-sm-4">
															<input type="password" id="passnew" name="passnew" required value="<?=$newpass?>">
														</div><label style="color: #E41919;"></label>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Repetir Nueva contraseña: </label>

														<div class="col-sm-4">
															<input type="password" id="passnew2" name="passnew2" required value="<?=$newpass2?>">
														</div><label style="color: #E41919;"><?=$mensaje2?></label>
													</div>

													<div class="">
														<div class="col-md-offset-3 col-md-9">
															<button class="btn btn-info" type="submit" >
																<i class="ace-icon fa fa-check bigger-110"></i>
																Guardar
															</button>
														</div>
													</div>
														
													</form>
										</div>
								<!-- PAGE CONTENT ENDS -->
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
							Admin &copy; 2018
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="https://www.facebook.com/RedSaludPeru/?ref=hl">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
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
		<script src="<?=base_url()?>public/assets/js/jquery.gritter.js"></script>
		<script src="<?=base_url()?>public/assets/js/bootbox.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.easypiechart.js"></script>
		<script src="<?=base_url()?>public/assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.hotkeys.js"></script>
		<script src="<?=base_url()?>public/assets/js/bootstrap-wysiwyg.js"></script>
		<script src="<?=base_url()?>public/assets/js/select2.js"></script>
		<script src="<?=base_url()?>public/assets/js/fuelux/fuelux.spinner.js"></script>
		<script src="<?=base_url()?>public/assets/js/x-editable/bootstrap-editable.js"></script>
		<script src="<?=base_url()?>public/assets/js/x-editable/ace-editable.js"></script>
		<script src="<?=base_url()?>public/assets/js/jquery.maskedinput.js"></script>

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
