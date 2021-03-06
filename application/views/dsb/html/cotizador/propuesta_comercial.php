<?php 
$user = $this->session->userdata('user');
extract($user);
?>
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
							<li class="active"><a href="#">Planes</a></li>
							<li><a href="<?=base_url()?>index.php/cotizador">Cotizador</a></li>
							<li class="active">Propuesta Comercial</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							Propuesta Comercial
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">							
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/sol_apGerencia">
									<input type="hidden" id="idcotizaciondetalle" name="idcotizaciondetalle" value="<?=$idcotizaciondetalle;?>" />
									<input type="hidden" name="nom" value="<?=$nom?>">
									<input type="hidden" name="estado" value="<?=$estado?>">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-6">
											<table class="table table-bordered table-hover"  style="font-size: 12px;">
												<thead>
													<tr style="text-align: center;">
														<th style="text-align: center;" colspan="3"><?=str_replace("%20"," ",$nom);?>: COBERTURAS DEL PLAN</th>
													</tr>
													<tr>
														<th style="text-align: center;">DESCRIPCIÓN</th>
														<th style="text-align: center;">COASEGUROS</th>	
														<th style="text-align: center;">EVENTOS</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($coberturas as $c){ 
													switch ($c->tiempo) {
															case '1 day':
																$tiempo = $c->num_eventos." diario(s)";
															break;
															case '1 month':
																$tiempo = $c->num_eventos." mensual(es)";
															break;
															case '1 year':
																$tiempo = $c->num_eventos." anual(s)";
															break;	
															default:
																$tiempo = "Ilimitados";
															break;
														}?>
													<tr style="font-style: italic;">
														<th colspan="3"><?=$c->nombre_var?></th>
													</tr>
													<tr>
														<td><?=$c->texto_web?></td>
														<td><?=$c->cobertura?></td>
														<td><?=$tiempo?></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
											<br>
											<table class="table table-bordered table-hover"  style="font-size: 12px;">
												<thead>
													<tr>
														<th colspan="2" style="text-align: center;">COTIZACIÓN DE APORETS</th>
													</tr>
													<tr>
														<th style="text-align: center;">Cantidad Mínima de Afiliados</th>
														<th style="text-align: center;">Precio del Plan Mensual (Inc. IGV)</th>
													</tr>
												</thead>
												<tbody>
												<?php foreach($cot_detalle as $cd){
													$num_afiliados = $cd->num_afiliados;
													$titular = $cd->prima_titular;
													$adicional = $cd->prima_adicional;
												} 
												for($i=0; $i<$num_afiliados; $i++){
													$prima = $titular + ($adicional*$i);
													$prima =  number_format((float)$prima, 2, '.', '');
												?>
													<tr>
														<td>Titular <?php if($i>0){ echo "+ ".$i." dependiente(s)"; } ?></td>
														<td style="text-align: right;"><?=$prima?></td>
													</tr>
												<?php }?>
												</tbody>
											</table>
										</div>

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
										
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-6">
											<label><h3>CONDICIONES:</h3></label>
											<ul>
												<?php foreach ($coberturas2 as $c2) {?>
													<li><b><?=$c2->nombre_var?>: <?=$c2->texto_web?></b></li>
												<?php } ?>
											</ul>
										</div>

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
										
									</div>
								
									<div style="text-align: right;">
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button name="accion" class="btn btn-info" type="submit" value="solicitar">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Solicitar Aprobación de Gerencia
											</button>
										</div>
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
