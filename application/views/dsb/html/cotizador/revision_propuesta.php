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

		<script type="text/javascript">
			function alerta(id){
			    if (confirm("¿Está seguro de aprobar la cotización?")) {
			    	// alert("aprobado"+id);
			        // location.href="<?=base_url()?>index.php/aprobCot/"+id;
			        //window.location.href="https://wwww.google.com";
			        document.location="https://www.google.com";
				} else {
					alert("no aprobado"+id);
				    location.href="<?=base_url()?>index.php/cot_pendientes";
				}
			}
		</script>

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
							<li><a href="<?=base_url()?>index.php/cot_pendientes">Cotizaciones Pendientes</a></li>
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
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-6">
											<table class="table table-bordered table-hover"  style="font-size: 12px;">
												<thead>
													<tr>
														<th colspan="4" style="text-align: center;">DATOS DE LA PROPUESTA</th>
													</tr>
												</thead>
												<tbody>												
													<tr>
														<th>Cliente</th>
														<td><?=$cliente?></td>
														<th>Plan Cotizado</th>
														<td><?=$plan?></td>
													</tr>
													<tr>
														<th>Ejecutiva de Cuenta</th>
														<td><?=$ejecutiva?></td>
														<th>Fecha de Creación</th>
														<td><?=$fecha_creacion?></td>
													</tr>
													<tr>
														<th>Tipo de Cotización</th>
														<td><?=$tipo_cotizacion?></td>
														<th>Tipo de Plan</th>
														<td><?=$tipo_plan?></td>
													</tr>
													<tr>
														<th>Días de Carencia</th>
														<td><?=$carencia?></td>
														<th>Días de Mora</th>
														<td><?=$mora?></td>
													</tr>
													<tr>
														<th>Fecuencia de Atención</th>
														<td>Cada <?=$atencion?> días</td>
														<th>Afiliados por Póliza</th>
														<td><?=$afiliados?></td>
													</tr>

												</tbody>
											</table>
										</div>

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
										
									</div>
								</form>
							
							</div><!-- /.col -->
						</div>
						<div class="row">
							<div class="col-xs-12">							
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
									<input type="hidden" id="idcotizaciondetalle" name="idcotizaciondetalle" value="<?=$idcotizaciondetalle;?>" />
									<input type="hidden" name="nom" value="<?=$nom?>">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-3">
											<h3>Titular</h3>
										</div>

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-3">
											<h3>Adicional</h3>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-6">
											<table id="example" class="table table-bordered table-hover"  style="font-size: 12px;">
												<thead>
													<tr>
														<th>Cobertura</th>
														<th>Eventos Cotizados</th>
														<th>Costo x Even.</th>	
														<th>Coaseguro x Even.</th>													
														<th>Costo Anual (CA)</th>
														<th>Total Coaseguro (TC)</th>
														<th>CA - TC</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$sum1 = 0;
													$sum2 = 0;
													$sum3 = 0;
													foreach ($cot_cobertura as $cc){
														$costo_anual = $cc->eventos_titular*$cc->precio;
														$coaseguro = $cc->precio;
														$cobertura = $cc->cobertura;
														$copago = $cc->copago;
														$hasta = $cc->hasta;
														$val1 = $cc->valor1;
														$val2 = $cc->valor2;
														$val3 = $cc->valor3;

														if($copago > 0){
															if($coaseguro-$val2<0){
																$coaseguro = 0;
															}else{
																$coaseguro = $coaseguro-$val2;
															}
														}

														if($cobertura>0){
															$coaseguro = $coaseguro*($val1/100);
														}

														if($hasta>0){
															if($coaseguro<$val3){
																$coaseguro = $coaseguro;
															}else{
																$coaseguro = $val3;
															}
														}
														$precio = $cc->precio;
														$precio = number_format((float)$precio, 2, '.', '');
														$coaseguro = $cc->precio - $coaseguro;
														$coaseguro2 = number_format((float)$coaseguro, 2, '.', '');
														$coaseguro_anual = $coaseguro * $cc->eventos_adicional;
														$anual_final = $costo_anual - $coaseguro_anual;

														$sum1 = $sum1 + $costo_anual;
														$sum2 = $sum2 + $coaseguro_anual;
														$sum3 = $sum3 + $anual_final;
														$costo_anual2 = number_format((float)$costo_anual, 2, '.', '');
														$coaseguro_anual2  = number_format((float)$coaseguro_anual, 2, '.', '');
														$anual_final2  = number_format((float)$anual_final, 2, '.', '');
													?>
													<tr>
														<td><?=$cc->nombre_var?>: <?=$cc->texto_web?></td>
														<td style="text-align: center;"><?=$cc->eventos_titular?></td>
														<td style="text-align: right;"><?=$precio?></td>
														<td style="text-align: right;"><?=$coaseguro2?></td>
														<td style="text-align: right;"><?=$costo_anual2?></td>	
														<td style="text-align: right;"><?=$coaseguro_anual2?></td>													
														<td style="text-align: right;"><?=$anual_final2?></td>
													</tr>
													<?php } 
													$sum1 = number_format((float)$sum1, 2, '.', '');
													$sum2 = number_format((float)$sum2, 2, '.', '');
													$sum3 = number_format((float)$sum3, 2, '.', '');														

													foreach ($cot_detalle as $cd) {
														$tipo_cot = $cd->tipo_cotizacion;
														if($tipo_cot==1){
															$text_tipo_cot = "Mensual";
															$text_tipo_cot2 = "MENSUAL";
															$prom = $sum3/12;
															$prom2 = number_format((float)$prom, 2, '.', '');
														}else{
															$text_tipo_cot = "Anual";
															$text_tipo_cot2 = "ANUAL";
															$prom = $sum3;
															$prom2 = number_format((float)$prom, 2, '.', '');
														}
														$sin = round($cd->poblacion*($cd->siniestralidad_mensual/100));
														$sin_mensual = $prom * $sin;
														$sin_mensual2 =  number_format((float)$sin_mensual, 2, '.', '');
														$g_adm = $sin_mensual * ($cd->gastos_administrativos/100);
														$g_adm2 =  number_format((float)$g_adm, 2, '.', '');	
													?>
													<tr>
														<th colspan="4">TOTALES ANUALES</th>
														<th style="text-align: right;"><?=$sum1?></th>
														<th style="text-align: right;"><?=$sum2?></th>
														<th style="text-align: right;"><?=$sum3?></th>
													</tr>
													<?php if($tipo_cot==1){?>
													<tr>
														<td colspan="6">Gasto Mensual por Persona</td>
														<td style="text-align: right;"><?=$prom2?></td>
													</tr>													
													<?php } ?>
													<tr>
														<td colspan="6">Población </td>
														<td style="text-align: right;"><?=$cd->poblacion?></td>
													</tr>
													<tr>
														<td colspan="6">Siniestralidad <?=$text_tipo_cot?> <?=$cd->siniestralidad_mensual?>%</td>
														<td style="text-align: right;"><?=$sin?></td>
													</tr>
													<tr style="font-style: italic;">
														<th colspan="6">Gastos Siniestralidad <?=$text_tipo_cot?></th>
														<th style="text-align: right;"><?=$sin_mensual2?></th>
													</tr>
													<tr>
														<td colspan="6">Gastos Administrativos <?=$cd->gastos_administrativos?>%</td>
														<td style="text-align: right;"><?=$g_adm2?></td>
													</tr>
													<?php
													$gastos = $sin_mensual + $g_adm;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$mkt = $gastos * ($cd->gastos_marketing/100);
													$mkt2 = number_format((float)$mkt, 2, '.', '');
													 ?>													
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Gastos Marketing <?=$cd->gastos_marketing?>%</td>
														<td style="text-align: right;"><?=$mkt2?></td>
													</tr>
													<?php
													$gastos = $gastos + $mkt;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$c_amd = $gastos * ($cd->costos_administrativos/100);
													$c_amd2 = number_format((float)$c_amd, 2, '.', '');
													 ?>			
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Costos Administrativos <?=$cd->costos_administrativos?>%</td>
														<td style="text-align: right;"><?=$c_amd2?></td>
													</tr>
													<?php
													$gastos = $gastos + $c_amd;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$reserva = $gastos * ($cd->reserva/100);
													$reserva2 = number_format((float)$reserva, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Reserva <?=$cd->reserva?>%</td>
														<td style="text-align: right;"><?=$reserva2?></td>
													</tr>
													<?php
													$gastos = $gastos + $reserva;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$prima_min = ($gastos/$cd->poblacion)*1.18;
													$prima_min2 = number_format((float)$prima_min, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Gastos Totales</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Prima Mínima por Persona + IGV</td>
														<td style="text-align: right;"><?=$prima_min2?></td>
													</tr>
													<?php
													$inflacion = $prima_min*($cd->inflacion_medica/100);
													$inflacion2 = number_format((float)$inflacion, 2, '.', '');
													 ?>	
													<tr>
														<td colspan="6"> Inflación Médica <?=$cd->inflacion_medica?>%</td>
														<td style="text-align: right;"><?=$inflacion2?></td>
													</tr>
													<?php
													$prima_rs = $prima_min+$inflacion;
													$prima_rs2 = number_format((float)$prima_rs, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Prima RS</th>
														<th style="text-align: right;"><?=$prima_rs2?></th>
													</tr>
													<?php
													$remanente = $cd->remanente;
													$remanente2 = number_format((float)$remanente, 2, '.', '');
													 ?>	
													<tr>
														<td colspan="6">Remanente (S/.)</td>
														<td style="text-align: right;"><?=$remanente2?></td>
													</tr>
													<?php
													$prima_final = $prima_rs+$remanente;
													$prima_final2 = number_format((float)$prima_final, 2, '.', '');
													 ?>	
												</tbody>
												<?php } ?>
												<tbody>
													<tr style="color: red;">
														<th colspan="6">PRIMA <?=$text_tipo_cot2?> POR TITULAR</th>
														<th style="text-align: right;"><?=$prima_final2?></th>
													</tr>
												</tbody>													
											</table>
										</div>

										<div class="col-sm-6">
											<table id="example" class="table table-bordered table-hover"  style="font-size: 12px;">
												<thead>
													<tr>
														<th>Cobertura</th>
														<th>Eventos Cotizados</th>
														<th>Costo x Even.</th>	
														<th>Coaseguro x Even.</th>																										
														<th>Costo Anual(CA)</th>
														<th>Total Coaseguro (TC)</th>
														<th>CA - TC</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$sum1 = 0;
													$sum2 = 0;
													$sum3 = 0;
													foreach ($cot_cobertura as $cc) {
														$costo_anual = $cc->eventos_adicional*$cc->precio;
														$coaseguro = $cc->precio;
														$cobertura = $cc->cobertura;
														$copago = $cc->copago;
														$hasta = $cc->hasta;
														$val1 = $cc->valor1;
														$val2 = $cc->valor2;
														$val3 = $cc->valor3;

														if($copago > 0){
															if($coaseguro-$val2<0){
																$coaseguro = 0;
															}else{
																$coaseguro = $coaseguro-$val2;
															}
														}

														if($cobertura>0){
															$coaseguro = $coaseguro*($val1/100);
														}

														if($hasta>0){
															if($coaseguro<$val3){
																$coaseguro = $coaseguro;
															}else{
																$coaseguro = $val3;
															}
														}
														$precio = $cc->precio;
														$precio = number_format((float)$precio, 2, '.', '');
														$coaseguro = $cc->precio - $coaseguro;
														$coaseguro2 = number_format((float)$coaseguro, 2, '.', '');
														$coaseguro_anual = $coaseguro * $cc->eventos_adicional;
														$anual_final = $costo_anual - $coaseguro_anual;

														$sum1 = $sum1 + $costo_anual;
														$sum2 = $sum2 + $coaseguro_anual;
														$sum3 = $sum3 + $anual_final;
														$costo_anual2 = number_format((float)$costo_anual, 2, '.', '');
														$coaseguro_anual2  = number_format((float)$coaseguro_anual, 2, '.', '');
														$anual_final2  = number_format((float)$anual_final, 2, '.', '');
													?>
													<tr>
														<td><?=$cc->nombre_var?>: <?=$cc->texto_web?></td>
														<td style="text-align: center;"><?=$cc->eventos_adicional?></td>
														<td style="text-align: right;"><?=$precio?></td>
														<td style="text-align: right;"><?=$coaseguro2?></td>
														<td style="text-align: right;"><?=$costo_anual2?></td>	
														<td style="text-align: right;"><?=$coaseguro_anual2?></td>													
														<td style="text-align: right;"><?=$anual_final2?></td>
													</tr>
													<?php } 
													$sum1 = number_format((float)$sum1, 2, '.', '');
													$sum2 = number_format((float)$sum2, 2, '.', '');
													$sum3 = number_format((float)$sum3, 2, '.', '');
													
													foreach ($cot_detalle as $cd) {
														$tipo_cot = $cd->tipo_cotizacion;
														if($tipo_cot==1){
															$text_tipo_cot = "Mensual";
															$text_tipo_cot2 = "MENSUAL";
															$prom = $sum3/12;
															$prom2 = number_format((float)$prom, 2, '.', '');
														}else{
															$text_tipo_cot = "Anual";
															$text_tipo_cot2 = "ANUAL";
															$prom = $sum3;
															$prom2 = number_format((float)$prom, 2, '.', '');
														}
														$sin = round($cd->poblacion*($cd->siniestralidad_mensual/100));
														$sin_mensual = $prom * $sin;
														$sin_mensual2 =  number_format((float)$sin_mensual, 2, '.', '');
														$g_adm = $sin_mensual * ($cd->gastos_administrativos/100);
														$g_adm2 =  number_format((float)$g_adm, 2, '.', '');	
													?>
													<tr>
														<th colspan="4">TOTALES ANUALES</th>
														<th style="text-align: right;"><?=$sum1?></th>
														<th style="text-align: right;"><?=$sum2?></th>
														<th style="text-align: right;"><?=$sum3?></th>
													</tr>
													<?php if($tipo_cot==1){?>
													<tr>
														<td colspan="6">Gasto Mensual por Persona</td>
														<td style="text-align: right;"><?=$prom2?></td>
													</tr>													
													<?php } ?>
													<tr>
														<td colspan="6">Población </td>
														<td style="text-align: right;"><?=$cd->poblacion?></td>
													</tr>
													<tr>
														<td colspan="6">Siniestralidad <?=$text_tipo_cot?> <?=$cd->siniestralidad_mensual?>%</td>
														<td style="text-align: right;"><?=$sin?></td>
													</tr>
													<tr style="font-style: italic;">
														<th colspan="6">Gastos Siniestralidad <?=$text_tipo_cot?></th>
														<th style="text-align: right;"><?=$sin_mensual2?></th>
													</tr>
													<tr>
														<td colspan="6">Gastos Administrativos <?=$cd->gastos_administrativos?>%</td>
														<td style="text-align: right;"><?=$g_adm2?></td>
													</tr>
													<?php
													$gastos = $sin_mensual + $g_adm;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$mkt = $gastos * ($cd->gastos_marketing/100);
													$mkt2 = number_format((float)$mkt, 2, '.', '');
													 ?>													
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Gastos Marketing <?=$cd->gastos_marketing?>%</td>
														<td style="text-align: right;"><?=$mkt2?></td>
													</tr>
													<?php
													$gastos = $gastos + $mkt;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$c_amd = $gastos * ($cd->costos_administrativos/100);
													$c_amd2 = number_format((float)$c_amd, 2, '.', '');
													 ?>			
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Costos Administrativos <?=$cd->costos_administrativos?>%</td>
														<td style="text-align: right;"><?=$c_amd2?></td>
													</tr>
													<?php
													$gastos = $gastos + $c_amd;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$reserva = $gastos * ($cd->reserva/100);
													$reserva2 = number_format((float)$reserva, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Gastos</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Reserva <?=$cd->reserva?>%</td>
														<td style="text-align: right;"><?=$reserva2?></td>
													</tr>
													<?php
													$gastos = $gastos + $reserva;
													$gastos2 = number_format((float)$gastos, 2, '.', '');
													$prima_min = ($gastos/$cd->poblacion)*1.18;
													$prima_min2 = number_format((float)$prima_min, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Gastos Totales</th>
														<th style="text-align: right;"><?=$gastos2?></th>
													</tr>
													<tr>
														<td colspan="6">Prima Mínima por Persona + IGV</td>
														<td style="text-align: right;"><?=$prima_min2?></td>
													</tr>
													<?php
													$inflacion = $prima_min*($cd->inflacion_medica/100);
													$inflacion2 = number_format((float)$inflacion, 2, '.', '');
													 ?>	
													<tr>
														<td colspan="6"> Inflación Médica <?=$cd->inflacion_medica?>%</td>
														<td style="text-align: right;"><?=$inflacion2?></td>
													</tr>
													<?php
													$prima_rs = $prima_min+$inflacion;
													$prima_rs2 = number_format((float)$prima_rs, 2, '.', '');
													 ?>	
													<tr style="font-style: italic;">
														<th colspan="6">Prima RS</th>
														<th style="text-align: right;"><?=$prima_rs2?></th>
													</tr>
													<?php
													$remanente = $cd->remanente;
													$remanente2 = number_format((float)$remanente, 2, '.', '');
													 ?>	
													<tr>
														<td colspan="6">Remanente (S/.)</td>
														<td style="text-align: right;"><?=$remanente2?></td>
													</tr>
													<?php
													$prima_final = $prima_rs+$remanente;
													$prima_final3 = number_format((float)$prima_final, 2, '.', '');
													 ?>	
												</tbody>
												<?php } ?>
												<tbody>
													<tr style="color: red;">
														<th colspan="6">PRIMA <?=$text_tipo_cot2?> POR ADICIONAL</th>
														<th style="text-align: right;"><?=$prima_final3?></th>
													</tr>
												</tbody>																						
											</table>
										</div>
									</div>
									<input type="hidden" name="titular" value="<?=$prima_final2?>">
									<input type="hidden" name="adicional" value="<?=$prima_final3?>">
									<div style="text-align: right;">
									</div>
								</form>
							
							</div><!-- /.col -->
						</div>
						<div class="row">
							<div class="col-xs-12">							
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">
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
														<th colspan="2" style="text-align: center;">COTIZACIÓN DE APORTES</th>
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
											<a href="<?=base_url()?>index.php/aprobCot/<?=$idcotizaciondetalle?>" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Aprobar Solicitud
											</a>
											
											<a href="<?=base_url()?>index.php/desaprobarCot/<?=$idcotizaciondetalle?>" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Rechazar Solicitud
											</a>
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
