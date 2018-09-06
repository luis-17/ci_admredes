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
	</head>

	<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="page-content">
						<div class="page-header">
							<h1>
							<?php if($cita=='null'){
								echo "Reservar Atención";
								}else{
									echo "Actualizar Reserva";
									}?>						
							</h1>
						</div>
						<?php if(!empty($getcita)){
									foreach ($getcita as $c) {
										$idsiniestro=$c->idsiniestro;
										$prov=$c->idproveedor;
										$esp=$c->idespecialidad;
										$estado=$c->estado_cita;
										$hoy=$c->fecha_cita;
										$ini=$c->hora_cita_inicio;
										$fin=$c->hora_cita_fin;
										$obs=$c->observaciones_cita;
									}
								}else{
									$idsiniestro="";
									$prov="";
									$esp="";
									$estado="";									
									$hoy=date('Y-m-d');
									$ini = date("h:i");
									$fin = date("h:i", strtotime($ini."+30 minute"));
									$obs="";
								}
						?>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/save_cita">
									<input type="hidden" id="aseg_id" name="aseg_id" value="<?=$aseg_id?>" />
									<input type="hidden" name="idcita" id="idcita" value="<?=$cita?>">
									<input type="hidden" id="cert_id" name="cert_id" value="<?=$cert_id?>">
									<input type="hidden" id="idusuario" name="idusuario" value="<?=$idusuario;?>">
									<input type="hidden" name="certase_id" name="certase_id" value="<?=$certase_id?>">
									<input type="hidden" name="idsiniestro" id="idsiniestro" value="<?=$idsiniestro?>">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Proveedor: </label>
										<div class="col-sm-9">
											<select name='proveedor' id='proveedor' required>
										    	<option value=''>Seleccione</option>
											    <?php foreach ($proveedores as $pr):
											    	if($pr->idproveedor==$prov){
											    		$estado_prov="selected";
											    		}else{
											    			$estado_prov="";
											    			}?>
											    	<option value='<?=$pr->idproveedor;?>' <?=$estado_prov?> ><?=$pr->nombre_comercial_pr;?></option>
											    <?php endforeach; ?>
										    </select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Servicio: </label>

										<div class="col-sm-9">
											<select name='producto' id='producto' required>
										    	<option value=''>Seleccione</option>
											    <?php foreach ($productos as $p):
											    	if($p->idespecialidad==$esp){
											    		$est_esp="selected";
											    	}else{
											    		$est_esp="";
											    	}
											    ?>
											    	<option value='<?=$p->idespecialidad;?>' <?=$est_esp?> ><?=$p->descripcion_prod;?></option>
											    <?php endforeach; ?>
										    </select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Estado: </label>

										<div class="col-sm-9">
											<select name='estado' id='estado' required>
										    	<?php if($estado!=2){ ?><option value='1' <?php if($estado==1){echo "selected";}?> >Cita Reservada</option> <?php } ?>
										    	<option value='2' <?php if($estado==2){echo "selected";}?>>Cita Confirmada</option>
										    </select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Fecha: </label>

										<div class="col-sm-9">
											<input max="<?=$max?>" type='date' name='feccita' id='feccita' value='<?=$hoy;?>' required  />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Hora Inicio: </label>

										<div class="col-sm-2">
											<input onchange="calcular()" class='form-control input-mask-date' type='time' name='inicio' id='inicio' value='<?=$ini;?>' required/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Hora Fin: </label>

										<div class="col-sm-2">
											<input class='form-control input-mask-date' type='time' name='fin' id='fin' value='<?=$fin;?>' required />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Observaciones: </label>

										<div class="col-sm-9">
											 <textarea rows='2' cols='71' name='obs' id='obs'><?=$obs?></textarea>
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
</body></html>