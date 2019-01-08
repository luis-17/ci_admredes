<html lang="en"><head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="utf-8">
		<title>Tables - Ace Admin</title>

		<meta name="description" content="Static &amp; Dynamic Tables">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css">
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css">

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style">

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
			<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>
	</head>

	<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							Detalle Liquidación: L<?=$numero?>					
							</h1>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

							<div class="tabbable">
									<!-- #section:pages/faq -->
								<div class="col-xs-12">	
									<?php 
										 foreach ($liquidacion as $l){
								    		$razon_social = $l->razon_social_pr;
								    		$ruc = $l->numero_documento_pr;
								    		$banco = $l->descripcion;
								    		$tipo = $l->descripcion_fp;
								    		$op = $l->num_operacion;
								    		$total = $l->total;
								    		$igv = $total * 0.18;
								    		$subtotal = $total - $igv;
								    		$cta_corriente = $l->cta_corriente;
								    		$cta_detracciones = $l->cta_detracciones;
								    		$colaborador = $l->colaborador;
								    		$detraccion = $l->detraccion;

								    		$igv = number_format((float)$igv, 2, '.', '');
								    		$subtotal = number_format((float)$subtotal, 2, '.', '');
								    		$total = number_format((float)$total, 2, '.', '');
								    		$detraccion = number_format((float)$detraccion,2,'.','');

								    		$neto = $total - $detraccion;
										} 
									?>
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
										<th width="30%">Nombre/Razón Social</th>
										<td><?=$razon_social?></td>
										</tr>
										<tr>
										<th width="30%">DNI/RUC</th>
										<td><?=$ruc?></td>
										</tr>
										<tr>
										<th width="30%">Concepto</th>
										<td>Liquidación de Facturas</td>
										</tr>
									</table>

									<h4>N° Documentos:</h4>	
									<table id="example" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<thead>
											<tr>
												<th>N° Factura</th>
												<th>N° Orden Atención</th>
												<th>Afiliado</th>
												<th>Concepto</th>
												<th>Importe Bruto</th>
												<th>Importe Neto</th>
											</tr>
										</thead>
										<tbody>
										<?php foreach ($liquidacionDet as $ld){ ?>
											<tr>
												<td><?=$ld->liqdetalle_numfact?></td>
												<td><?=$ld->num_orden_atencion?></td>
												<td><?=$ld->afiliado?></td>
												<td><?=$ld->nombre_var?> <?=$ld->detalle?></td>
												<td style="text-align: right;"><?=$ld->liqdetalle_monto?> PEN</td>
												<td style="text-align: right;"><?=$ld->liqdetalle_neto?> PEN</td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
									<script>			
										//para paginacion
										$(document).ready(function() {
										    $('#example').DataTable( {
										        "pagingType": "full_numbers"
										    } );
										} );
									</script>

									<h4>Detalle a Pagar</h4>	

									<div style="float: left; width: 49%">
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
											<th width="30%" align="left"> Sub Total</th>
											<td style="text-align: right;"><?=$subtotal?> PEN</td>
										</tr>
										<tr>
											<th width="30%" align="left"> IGV</th>
											<td style="text-align: right;"><?=$igv?> PEN</td>
										</tr>
										<tr>
											<th width="30%" align="left"> Total</th>
											<td style="text-align: right;"><?=$total?> PEN</td>
										</tr>
									</table>
									</div>
									<div style="float: right; width: 49%">
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<tr>
											<th width="30%" align="right"> Detracciones</th>
											<td style="text-align: right;"><?=$detraccion?> PEN</td>
										</tr>
										<tr>
											<th width="30%" align="right"> NETO A PAGAR</th>
											<th style="text-align: right; color: red"><?=$neto?> PEN</th>
										</tr>
									</table>
									<br>
									<br>
									</div>


									<?php if(!empty($liquidacion_grupo)){?>

									<h4>Detalle del Pago Realizado</h4>
									<table class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
										<thead>
											<tr>
												<th>Usuario Liquida</th>
												<th>Fecha</th>
												<th>Banco</th>
												<th>Forma de Pago</th>
												<th>N° Operación</th>
												<th>Correo Notificación</th>
											</tr>														
										</thead>
										<tbody>
											<?php foreach ($liquidacion_grupo as $lg) { ?>
											<tr>
												<td><?=$lg->username?></td>
												<td><?=$lg->fecha_liquida?></td>
												<td><?=$lg->descripcion?></td>
												<td><?=$lg->descripcion_fp?></td>
												<td><?=$lg->num_operacion?></td>
												<td><?=$lg->email_notifica?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
									<?php } ?>
									</div><!-- /.col -->
											
												
												

						</div>
					</div>
				</div><!-- /.main-content -->			
			</div><!-- /.main-container -->
		
		<!-- basic scripts -->

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