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
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		
		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	</head>
	<?php 
		if($nom==''):
			$estilodivaseg='none';
			if($id==''):
			$estilodiv="none";
				else:
				$estilodiv="block";
		  	endif;
			else:
				$estilodivaseg='block';
				$estilodiv="none";
		endif;
	?>
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
							<li class="active">Certificado</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">	
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Búsqueda de Certificado
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<div class="alert alert-info">
									RED SALUD <b><?=$turno?></b>, lo / la saluda <b><?=$nombres_col?> <?=$ap_paterno_col?></b> ¿Por favor me brinda su número de DNI? 
								</div>
								<div class="alert alert-danger">
									Respuesta del Afiliado
								</div>								
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->								
								<div align="center">
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
								</div><!-- PAGE CONTENT ENDS -->
								<br />
								<div style="display: <?=$estilodiv?>">
									<!-- star table -->		
									<div class="col-xs-12">
										<table id="simple-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>Num. Certificado</th>
													<th>Plan</th>
													<th>Cliente</th>
													<th>Estado Certificado</th>
													<th>Estado Atención</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($certificados as $c):
												$hoy= time();
												$inicio=$c->cert_iniVig;										
												$inicio2= strtotime($inicio);
												$fin=$c->cert_finVig;
												$finvig=$c->cert_finVig;
												$fin2= strtotime($fin); 
												$cert=$c->cert_id;
												$act_man=$c->cert_upProv;
															
												if($c->cert_estado==1){
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
													}else{
														if($act_man==1){
															$estado2="Activo Manualmente";		
														}else{
															$estado2="Inactivo";
														}
													}
												}else{
													$estado2="Inactivo";
												}
											?>

												<tr>
												<?php if($cert==$id2){?>
													<td><b><?=$c->cert_num;?></b></td>
													<td><b><?=$c->nombre_plan;?></b></td>
													<td><b><?=$c->nombre_comercial_cli;?></b></td>
													<td><b><?=$estado;?></b></td>
													<td><b><?=$estado2;?></b></td>
												<?php } else { ?>
													<td><?=$c->cert_num;?></td>
													<td><?=$c->nombre_plan;?></td>
													<td><?=$c->nombre_comercial_cli;?></td>
													<td><?=$estado;?></td>
													<td><?=$estado2;?></td>
												<?php } ?>
													<td>
														<div class="hidden-sm hidden-xs btn-group">
															<a href="<?=  base_url()?>index.php/certificado_detalle/<?=$cert?>/<?=$id;?>" title="Detalle Certificado"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
														</div>

														<div class="hidden-md hidden-lg">
															<div class="inline pos-rel">
																<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																	<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																</button>
																<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">	
																	<li>
																		<a href="<?=  base_url()?>index.php/certificado_detalle/<?=$cert?>/<?=$id;?>" title="Detalle Certificado">
																			<span class="red">
																				<i class="ace-icon fa fa-external-link bigger-120"></i>
																			</span>
																		</a>
																	</li>
																</ul>
															</div>
														</div>
													</td>
												</tr>
											<?php endforeach;?>
											</tbody>
										</table>
									</div>
												<!-- end table -->
								</div>
								<div style="display: <?=$estilodivaseg?>">
								<!-- star table -->		
									<div class="col-xs-12">
										<table id="simple-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>Nro Certificado</th>
													<th>Empresa</th>
													<th>Plan</th>
													<th>DNI Contratante</th>
													<th>Contratante</th>
													<th>DNI Asegurado</th>
													<th>Asegurado</th>																
													<th></th>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($certificadoap as $a):?>
												<tr>
													<td><?=$a->cert_num;?></td>
													<td><?=$a->nombre_comercial_cli;?></td>
													<td><?=$a->nombre_plan;?></td>
													<td><?=$a->cont_numDoc;?></td>
													<td><?=$a->contratante;?></td>
													<td><?=$a->aseg_numDoc;?></td>
													<td><?=$a->asegurado;?></td>
													<td>
														<div class="hidden-sm hidden-xs btn-group">
															<a href="<?=  base_url()?>index.php/certificado2/<?=$a->aseg_numDoc?>/<?=$a->cert_id?>" title="Detalle Certificado"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
														</div>
													</td>
												</tr>
											<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
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
