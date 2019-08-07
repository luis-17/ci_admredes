<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
?>
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
		<div class="page-content">
			<div class="page-header">
				<h1>
					Editar Recepción					
				</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<form class="form-horizontal" role="form" method="post" action="<?php if($tipo==3){ echo base_url().'index.php/reg_recepcion3';} else{ echo base_url().'index.php/reg_recepcion';}?>">
						<input type="hidden" name="idrecepcion" id="idrecepcion" value="<?=$idrecepcion?>">
						<?php if($tipo!=3){?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">RUC:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="text" class="col-xs-12 col-sm-5" value="<?=$ruc?>" disabled>
								</div>
							</div>																
						</div>  

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Razon Social:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="text" class="col-xs-12 col-sm-5" value="<?=$raz?>" disabled>
								</div>
							</div>																
						</div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Nombre Comercial:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="text" class="col-xs-12 col-sm-5" value="<?=$nom?>" disabled>
								</div>
							</div>																
						</div>
						<?php } ?>
						<?php if($tipo==3){?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Remitente:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input maxlength="255" name="remitente"  type="text" class="col-xs-12 col-sm-5" value="<?=$nom?>">
								</div>
							</div>																
						</div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Asunto:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input maxlength="255" name="asunto"  type="text" class="col-xs-12 col-sm-5" value="<?=$asunto?>">
								</div>
							</div>																
						</div>
						<?php } ?>

						<?php if($tipo==1){ ?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">N° Orden Atención:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="text" class="col-xs-12 col-sm-5" value="OA<?=$orden?>" disabled>
								</div>
							</div>																
						</div>
						<?php } ?>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Recepción:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="date" id="recepcion" name="recepcion" class="col-xs-12 col-sm-3" value="<?=$fecha_recepcion?>" >
								</div>
							</div>																
						</div>
						<?php if($tipo!=3){?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Emisión:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="date" id="emision" name="emision" class="col-xs-12 col-sm-3" value="<?=$fecha_emision?>" >
								</div>
							</div>																
						</div>
						<?php } ?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Documento:</label>
							<div class="col-xs-12 col-sm-8">
								<div class="col-sm-2">
									<div class="form-group">
										<select  class="control-label col-xs-12 col-sm-11 no-padding-left" name="tipodoc" required="Seleccione una opción de la lista">
												<option <?php if($tipodoc=='B'){echo 'selected';} ?> value="B">Boleta</option>
												<option <?php if($tipodoc=='F'){echo 'selected';} ?> value="F">Factura</option>
												<option <?php if($tipodoc=='R'){echo 'selected';} ?> value="R">Recibo</option>
												<option <?php if($tipodoc=='OF'){echo 'selected';} ?>  value="OF">Oficio</option>
												<option <?php if($tipodoc=='S'){echo 'selected';} ?>  value="S">Solicitud</option>
												<option <?php if($tipodoc=='C'){echo 'selected';} ?>  value="C">Comunicado</option>
												<option <?php if($tipodoc=='CA'){echo 'selected';} ?>  value="CA">Carta</option>
												<option <?php if($tipodoc=='O'){echo 'selected';} ?>  value="O">Otro</option>
										</select>
									</div>
								</div>
								<?php if($tipo!=3){?>
								<div class="col-sm-1">
									<div class="form-group">
										<input  type="text" id="serie" name="serie" class="col-xs-12 col-sm-11" value="<?=$serie?>" placeholder="Serie" >
									</div>
								</div>
								<?php } ?>
								<div class="col-sm-2">
									<div class="form-group">
										<input  type="text" id="numero" name="numero" class="col-xs-12 col-sm-11" value="<?=$numero?>" placeholder="Número" >
									</div>
								</div>	
							</div>					
						</div>
						<?php if($tipo!=3){?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Importe (S/.):</label>
							<div class="col-xs-12 col-sm-8">
								<div class="clearfix">
									<input  type="text" id="importe" name="importe" class="col-xs-12 col-sm-3" value="<?=$importe?>" >
								</div>
							</div>																
						</div>
						<?php } ?>

						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-12">
								<button class="btn btn-info" type="submit" id="guardar" name="guardar">
									<i class="ace-icon fa fa-check bigger-110"></i>
										Guardar
								</button>
							</div>
						</div>
					</form>

				</div><!-- /.col -->
			</div>
		</div>			
		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script><script src="<?=  base_url()?>public/assets/js/jquery.js"></script>
		<script type="text/javascript">
			function cerrar(){
				parent.location.reload(true);
  				parent.$.fancybox.close();
  			}
		</script>


		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		</script>
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
</body>
</html>