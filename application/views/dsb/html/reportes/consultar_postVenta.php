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
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

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

							<li class="active">
								Post-Venta
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Consultar Post-Venta
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div align="center">								
								<div class="col-xs-9 col-sm-12">
									<div class="alert alert-info">

										<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_postVenta_buscar" class="form-horizontal">
											<div class="profile-info-name"> Inicio: </div>
											<div class="profile-info-name">
												<input class="form-control input-mask-date" type="date" id="fechainicio" name="fechainicio" required="Seleccione una fecha de inicio" value="<?=$fecinicio;?>" >
											</div>

											<div class="profile-info-name"> Fin: </div>
											<div class="profile-info-name">
												<input class="form-control input-mask-date" type="date" id="fechafin" name="fechafin" required="Seleccione una fecha de fin" value="<?=$fecfin;?>">		
											</div>

											<div  class="profile-info-name">
												<button type="submit" class="btn btn-info btn-xs" name="accion" value="buscar">Buscar 
													<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
												</button>
											</div>

											<div  class="profile-info-name">
												<button class="btn btn-info btn-xs" type="submit" name="accion" value="exportar">Exportar
													<i class="ace-icon fa fa-download bigger-110 icon-only"></i>
												</button>
											</div>
										</form>	
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Resumen General
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Resumen por Cliente
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Resumen por Plan 
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-4">
												Resumen por Clínica
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-5">
												Resumen por Usuario 
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-6">
												Detalle
											</a>
										</li>							
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">
											<!-- star table -->														
												<div class="col-xs-9">
													<table align="center" id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th rowspan="2">Item</th>
																<th colspan="2" align="center">Teléfono</th>														
																<th colspan="2" align="center">Correo Electrónico</th>
																<th colspan="2" align="center">Total Post Venta</th>
															</tr>
															<tr>														
																<td width="10%" align="center">Cantidad</td>
																<td width="10%" align="center">Porcentaje</td>
																<td width="10%" align="center">Cantidad</td>
																<td width="10%" align="center">Porcentaje</td>
																<td width="10%" align="center">Cantidad</td>
																<td width="10%" align="center">Porcentaje</td>
															</tr>
														</thead>
														<tbody>	
															<?php 
																$total_encuestas = $contestaron_telf+$contestaron_correo+ $no_contestaron + $no_opinan;
																$total_contestaron = $contestaron_telf+$contestaron_correo;
																$total_telf = $contestaron_telf + $no_contestaron + $no_opinan;
																if($total_telf>0){
																	$porc_telf = round((($contestaron_telf*100)/$total_telf),2);
																	$porc_nocontestaron = round((($no_contestaron*100)/$total_telf),2);
																	$porc_noopinan = round((($no_opinan*100)/$total_telf),2);
																	$totporctelf = round((($total_telf*100)/$atenciones),2);
																}else{
																	$porc_telf = 0;
																	$porc_nocontestaron = 0;
																	$porc_noopinan = 0;
																	$totporctelf = 0;
																}
																if($total_encuestas>0){
																	$porc_contestaron = round((($total_contestaron*100)/$total_encuestas),2);
																	$porc_noc = round((($no_contestaron*100)/$total_encuestas),2);
																	$porc_noop = round((($no_opinan*100)/$total_encuestas),2);
																	$porc_telf2 =round((($total_telf*100)/$total_encuestas),2);
																	$porc_correo2 = round((($contestaron_correo*100)/$total_encuestas),2);
																}else{
																	$porc_contestaron = 0;
																	$porc_noc = 0;
																	$porc_noop = 0;
																	$porc_telf2 = 0;
																	$porc_correo2 = 0;
																}
																if($contestaron_correo>0){
																	$porc_correo = round((($contestaron_correo*100)/$contestaron_correo),2);
																	$totporcorreo = round((($contestaron_correo*100)/$atenciones),2);
																}else{
																	$porc_correo = 0;
																	$totporcorreo = 0;
																}	
															?>
															
															<tr>															
																<td>Contestaron</td>	
																<td align="right"><?=$contestaron_telf?></td>
																<td align="right"><?=$porc_telf?>%</td>														
																<td align="right"><?=$contestaron_correo?></td>
																<td align="right"><?=$porc_correo?>%</td>
																<td align="right"><?=$total_contestaron?></td>
																<td align="right"><?=$porc_contestaron?>%</td>
															</tr>
															<tr>
																<td>No Contestaron</td>
																<td align="right"><?=$no_contestaron?></td>
																<td align="right"><?=$porc_nocontestaron?>%</td>														
																<td align="right">0</td>
																<td align="right">0%</td>
																<td align="right"><?=$no_contestaron?></td>
																<td align="right"><?=$porc_noc?>%</td>
															</tr>
															<tr>
																<td>No Opinaron</td>
																<td align="right"><?=$no_opinan?></td>
																<td align="right"><?=$porc_noopinan?>%</td>														
																<td align="right">0</td>
																<td align="right">0%</td>
																<td align="right"><?=$no_opinan?></td>
																<td align="right"><?=$porc_noop?>%</td>
															</tr>
															<tr>
																<td rowspan="2">TOTAL POST VENTA</td>
																<td align="right" colspan="2"><?=$total_telf?></td>														
																<td align="right" colspan="2"><?=$contestaron_correo?></td>
																<td style="vertical-align: middle;" rowspan="2" align="right" colspan="2"><?=$total_encuestas?></td>
															</tr>
															<tr>																
																<td align="right" colspan="2"><?=$porc_telf2?>%</td>														
																<td align="right" colspan="2"><?=$porc_correo2?>%</td>
															</tr>
															<tr>
																<th>TOTAL ATENCIONES</th>
																<td align="right" colspan="2"><b><?=$totporctelf?>%<b></td>														
																<td align="right" colspan="2"><b><?=$totporcorreo?>%<b></td>
																<td align="right" colspan="2"><b><?=$atenciones?><b></td>
															</tr>
														</tbody>												
													</table>
												</div>
										</div>
										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table align="center" id="example2" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Cliente</th>
																<th>N° Encuestas</th>														
																<th>Indicador</th>
																<th>Calificación</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach ($resumen_canal as $rc) {
																$num=$rc->num_encuestas;
																$valor=$rc->suma;
																$indicador=round(($valor/$num),1);
																if($indicador<1.5){
																	$calificacion = 'Pésimo';
																}elseif ($indicador>1.4 && $indicador<2.5) {
																	$calificacion = 'Malo';
																}elseif ($indicador>2.4 && $indicador<3.5) {
																	$calificacion = 'Regular';
																}elseif($indicador>3.4 && $indicador<4.5){
																	$calificacion = 'Bueno';
																}else{
																	$calificacion = 'Excelente';
																}
															?>
															<tr>
																<td><?=$rc->nombre_comercial_cli?></td>	
																<td align="right"><?=$num?></td>														
																<td align="right"><?=$indicador?></td>
																<td align="left"><?=$calificacion?></td>
															</tr>
														<?php } ?>
														</tbody>												
													</table>
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
										<div id="faq-tab-3" class="tab-pane fade">
											<!-- star table -->		
											<div class="col-xs-12">

												<!-- end table -->
												<table width="100%" align="center" id="example3" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th rowspan="2">Cliente</th>
																<th rowspan="2">Plan</th>
																<th colspan="3">Satisfacción</th>
																<th colspan="3">Net Promoter Score</th>
															</tr>
															<tr>
																<th>N° Encuestas</th>
																<th>Indicador</th>
																<th>Calificación</th>
																<th>N° Encuestas</th>
																<th>Indicador</th>
																<th>Calificación</th>
															</tr>
														</thead>
														<tbody>	
																<?php foreach ($resumen_plan as $rp) {
																$num=$rp->num_encuestas;
																$num2=$rp->num_encuestas2;
																$valor=$rp->suma;
																$valor2=$rp->suma2;

																if($valor>0){
																	$indicador=round(($valor/$num),1);
																	if($indicador<1.5){
																		$calificacion = 'Nada satisfecho';
																	}elseif ($indicador>1.4 && $indicador<2.5) {
																		$calificacion = 'Poco satisfecho';
																	}elseif ($indicador>2.4 && $indicador<3.5) {
																		$calificacion = 'Satisfecho';
																	}elseif($indicador>3.4 && $indicador<4.5){
																		$calificacion = 'Muy satisfecho';
																	}else{
																		$calificacion = 'Totalmente satisfecho';
																	}
																}else{
																	$indicador='-';
																	$calificacion='-';
																}

																if($valor2>0){
																	$indicador2=round(($valor2/$num2),1);
																	if($indicador2<3.1){
																		$calificacion2 = 'Detractores';
																	}elseif ($indicador2>3 && $indicador2<4.1) {
																		$calificacion2 = 'Pasivos';
																	}elseif ($indicador2>4 ) {
																		$calificacion2 = 'Promotores';
																	}
																}else{
																	$indicador2='-';
																	$calificacion2='-';
																}
															if($num<>0 or $num2<>0){
															?>
															<tr>
																<td><?=$rp->nombre_comercial_cli?></td>	
																<td><?=$rp->nombre_plan?></td>
																<td align="right"><?=$num?></td>														
																<td align="right"><?=$indicador?></td>
																<td align="left"><?=$calificacion?></td>
																<td align="right"><?=$num2?></td>														
																<td align="right"><?=$indicador2?></td>
																<td align="left"><?=$calificacion2?></td>
															</tr>
														<?php }} ?>
														</tbody>												
													</table>
													<script>			
														//para paginacion
														$(document).ready(function() {
														$('#example3').DataTable( {
														"pagingType": "full_numbers"
														} );
													} );
													</script>		
												</div>
										</div>
										<div id="faq-tab-4" class="tab-pane fade">
											<!-- star table -->														
												<div class="col-xs-12">
													<table width="100%" align="center" id="example4" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th rowspan="2">Centro Médico</th>
																<th colspan="3">Satisfacción</th>
																<th colspan="3">Calidad</th>
															</tr>
															<tr>																
																<th>N° Encuestas</th>
																<th>Indicador</th>
																<th>Calificación</th>
																<th>N° Encuestas</th>
																<th>Indicador</th>
																<th>Calificación</th>
															</tr>
														</thead>
														<tbody>	
																<?php foreach ($resumen_clinica as $rc2) {
																$num=$rc2->num_encuestas;
																$num2=$rc2->num_encuestas2;
																$valor=$rc2->suma;
																$valor2=$rc2->suma2;
																if($num>0){
																	$indicador=round(($valor/$num),1);
																	if($indicador<1.5){
																		$calificacion = 'Nada satisfecho';
																	}elseif ($indicador>1.4 && $indicador<2.5) {
																		$calificacion = 'Poco satisfecho';
																	}elseif ($indicador>2.4 && $indicador<3.5) {
																		$calificacion = 'Satisfecho';
																	}elseif($indicador>3.4 && $indicador<4.5){
																		$calificacion = 'Muy satisfecho';
																	}else{
																		$calificacion = 'Totalmente satisfecho';
																	}
																}
																if($num2>0){
																	$indicador2=round(($valor2/$num2),1);
																	if($indicador2<1.5){
																		$calificacion2 = 'Pésimo';
																	}elseif ($indicador2>1.4 && $indicador2<2.5) {
																		$calificacion2 = 'Malo';
																	}elseif ($indicador2>2.4 && $indicador2<3.5) {
																		$calificacion2 = 'Regular';
																	}elseif($indicador2>3.4 && $indicador2<4.5){
																		$calificacion2 = 'Bueno';
																	}else{
																		$calificacion2 = 'Excelente';
																	}
																}
																if($num>0 or $num2>0){
															?>
															<tr>
																<td><?=$rc2->nombre_comercial_pr?></td>
																<td align="right"><?=$rc2->num_encuestas3?></td>														
																<td align="right"><?=$indicador?></td>
																<td align="left"><?=$calificacion?></td>																
																<td align="right"><?=$rc2->num_encuestas3?></td>														
																<td align="right"><?=$indicador2?></td>
																<td align="left"><?=$calificacion2?></td>
															</tr>
														<?php }} ?>
														</tbody>												
													</table>
													<script>			
														//para paginacion
														$(document).ready(function() {
														$('#example4').DataTable( {
														"pagingType": "full_numbers"
														} );
													} );
													</script>	
												</div>
										</div>
										<div id="faq-tab-5" class="tab-pane fade">
											<!-- star table -->														
												<div class="col-xs-12">
													<table align="center" id="example5" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Usuario</th>													
																<th>N° Encuestas</th>
																<th>Indicador</th>
																<th>Calificación</th>
															</tr>
														</thead>
														<tbody>	
																<?php foreach ($resumen_usuario as $ru) {
																$num=$ru->num_encuestas;
																$valor=$ru->suma;
																$indicador=round(($valor/$num),1);
																if($indicador<1.5){
																	$calificacion = 'Pésimo';
																}elseif ($indicador>1.4 && $indicador<2.5) {
																	$calificacion = 'Malo';
																}elseif ($indicador>2.4 && $indicador<3.5) {
																	$calificacion = 'Regular';
																}elseif($indicador>3.4 && $indicador<4.5){
																	$calificacion = 'Bueno';
																}else{
																	$calificacion = 'Excelente';
																}
															?>
															<tr>
																<td><?=$ru->username?></td>
																<td align="right"><?=$num?></td>														
																<td align="right"><?=$indicador?></td>
																<td align="left"><?=$calificacion?></td>
															</tr>
														<?php } ?>
														</tbody>												
													</table>
													<script>			
														//para paginacion
														$(document).ready(function() {
														$('#example5').DataTable( {
														"pagingType": "full_numbers"
														} );
													} );
													</script>	
												</div>
												<!-- end table -->
										</div>
										<div id="faq-tab-6" class="tab-pane fade">
											<!-- star table -->														
												<div class="col-xs-12">
													<table align="center" id="example6" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>N° Orden</th>
																<th>Cliente</th>													
																<th>Plan</th>
																<th>Proveedor</th>
																<th>DNI</th>
																<th>Afiliado</th>
																<th>Usuario Gestiona</th>
																<th>Usuario Califica</th>
																<th>Fecha Califica</th>
																<th>Estado Calificación</th>
																<th>Indicador</th>
																<th>Calificación</th>
																<th>Comentario</th>
															</tr>
														</thead>
														<tbody>		
															<?php foreach ($encuesta_detalle as $ed) {
																$num=$ed->num_respuestas;
																if($num>0){
																	$suma = $ed->suma;
																	$indicador = round($suma/$num,1);
																	if($indicador<1.5){
																		$calificacion = 'Pésimo';
																	}elseif ($indicador>1.4 && $indicador<2.5) {
																		$calificacion = 'Malo';
																	}elseif ($indicador>2.4 && $indicador<3.5) {
																		$calificacion = 'Regular';
																	}elseif($indicador>3.4 && $indicador<4.5){
																		$calificacion = 'Bueno';
																	}else{
																		$calificacion = 'Excelente';
																	}

																}else{
																	$indicador = '-';
																	$calificacion = '-';
																}
																$estado = $ed->estado;
																switch ($estado) {
																	case 0:
																		$estado = 'No Opina';
																		$color = 'red';
																		break;
																	case 1:
																		$estado = "Contestó";
																		$color = 'black';
																		break;
																	
																	default:
																		$estado = "No Contestó";
																		$color = "orange";
																		break;
																}
															?>
															<tr>
																<td style="color: <?=$color?>"><?=$ed->num_orden_atencion?></td>
																<td style="color: <?=$color?>"><?=$ed->nombre_comercial_cli?></td>
																<td style="color: <?=$color?>"><?=$ed->nombre_plan?></td>	
																<td style="color: <?=$color?>"><?=$ed->nombre_comercial_pr?></td>													
																<td style="color: <?=$color?>"><?=$ed->aseg_numDoc?></td>
																<td style="color: <?=$color?>"><?=$ed->afiliado?></td>
																<td style="color: <?=$color?>"><?=$ed->username?></td>
																<td style="color: <?=$color?>"><?=$ed->medio_calificacion?></td>
																<td style="color: <?=$color?>"><?=$ed->fecha_hora?></td>
																<td style="color: <?=$color?>"><?=$estado?></td>
																<td style="color: <?=$color?>"><?=$indicador?></td>
																<td style="color: <?=$color?>"><?=$calificacion?></td>
																<td style="color: <?=$color?>"><?=$ed->comentario?></td>
															</tr>
															<?php } ?>
														</tbody>												
													</table>
													<script>			
														//para paginacion
														$(document).ready(function() {
														$('#example6').DataTable( {
														"pagingType": "full_numbers"
														} );
													} );
													</script>	
												</div>
										</div>
									</div>
								</div>							

								<!-- PAGE CONTENT ENDS -->
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
							<span class="blue bolder">Red Salud Admin</span>
							Application &copy; 2018
						</span>

						&nbsp; &nbsp;
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

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