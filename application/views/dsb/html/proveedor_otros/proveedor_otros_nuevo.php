
<?php
	$user = $this->session->userdata('user');
	extract($user);
	date_default_timezone_set('America/Lima');
	$hoy = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->
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

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->

		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

		<script type="text/javascript">
	    /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
	    $(document).ready(function(){
	       $("#dep").change(function () {
	               $("#dep option:selected").each(function () {
	                dep=$('#dep').val();
	                $.post("<?=base_url();?>index.php/provincia", { dep: dep}, function(data){
	                $("#prov").html(data);
	                });            
	            });
	       })
	    });

	    $(document).ready(function(){
	       $("#prov").change(function () {
	               $("#prov option:selected").each(function () {
	                prov=$('#prov').val();
	                $.post("<?=base_url();?>index.php/distrito", { prov: prov}, function(data){
	                $("#dist").html(data);
	                });            
	            });
	       })
	    });
	    /*fin de la funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
	    </script>
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

							<li class="active"><a href="<?=base_url()?>index.php/mesa_partes">
								Mesa de Partes
							</a>
							</li>
							<li class="active">Nuevo Proveedor</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<?php
								if(!empty($data_general)):	
									foreach ($data_general as $dg):	
										$id= $dg->idproveedor_int;
										$ruc=$dg->numero_documento_pr;
										$raz_social=$dg->razon_social_pr;
										$nomb=$dg->nombre_comercial_pr;
										$direcc=$dg->direccion_pr;
										$ref=$dg->referencia_pr;
										$dep=$dg->cod_departamento_pr;
										$prov=$dg->cod_provincia_pr;
										$dist=$dg->cod_distrito_pr;
									endforeach;
								else:
									$id=0;
									$ruc="";
									$raz_social="";
									$nomb="";
									$direcc="";
									$ref="";
									$dep="";
									$prov="";
									$dist="";
								endif;
							?>
					<div class="page-content">
						<div class="page-header">
							<h1>	
							<?php if($id==0){
								echo "Registrar Proveedor";
								}else{
									echo "Actualizar Proveedor";
									}?>						
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
							
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/proveedor_otros_guardar2">
									<input type="hidden" id="idproveedor" name="idproveedor" value="<?=$id;?>" >
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Ruc:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input required="true" type="text" name="ruc" id="ruc" class="col-xs-12 col-sm-4" value="<?=$ruc;?>">
											</div>
										</div>
									</div>									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Razón Social:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input  required="true" type="text" id="razonsocial" name="razonsocial" class="col-xs-12 col-sm-5" value="<?php echo $raz_social;?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Nombre Comercial:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input  required="true" type="text" id="nombrecomercial" name="nombrecomercial" class="col-xs-12 col-sm-5"  value="<?php echo $nomb;?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="url">Dirección:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input maxlength="100"  required="true" type="text" id="direccion" name="direccion" class="col-xs-12 col-sm-8" value="<?php echo $direcc;?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="url">Referencia:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input  type="text" id="referencia" name="referencia" class="col-xs-12 col-sm-8" value="<?php echo $ref;?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Departamento</label>	
										<div class="col-xs-12 col-sm-9">
											<select class="input-medium valid" id="dep" name="dep"  required="true" aria-required="true" aria-invalid="false" aria-describedby="platform-error">
												<option value="">Seleccionar</option>
												<?php foreach ($departamento as $d):
													$cod=$d->iddepartamento;
													if($dep==$cod):
														$estd="selected";
													else:
														$estd="";
													endif;
												?>
												<option value="<?php echo $cod;?>" <?php echo $estd;?>><?php echo $d->descripcion_ubig;?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Provincia:</label>	
										<div class="col-xs-12 col-sm-9">
											<select class="input-medium valid" id="prov" name="prov"  required="true" aria-required="true" aria-invalid="false" aria-describedby="platform-error">
												<option value="">Seleccione</option>
												<?php if(!empty($provincia)) {
														foreach ($provincia as $p) {
															if($prov==substr($p->idprovincia, 2, 2)){
																$est="selected";
															}else{
																$est="";
															} 
												?>
												<option value="<?=$p->idprovincia?>" <?=$est?> ><?=$p->descripcion_ubig?></option>
												<?php 
														}
													} 
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Distrito:</label>	
										<div class="col-xs-12 col-sm-9">
										<?php ?>
											<select class="input-medium valid" id="dist"  required="true" name="dist" aria-required="true" aria-invalid="false" aria-describedby="platform-error">
												<option value="">Seleccione</option>
												<?php
													if(!empty($distrito)) {
														foreach ($distrito as $ds) {
															if($dist==substr($ds->iddistrito, 4, 2)){
																$estd="selected";
															}else{
																$estd="";
															} 
												?>
												<option value="<?=$ds->iddistrito?>" <?=$estd?> ><?=$ds->descripcion_ubig?> </option>
												<?php 
														}
													} 
												?>
											</select>
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
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>
		
		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script> -->

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<!-- <link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" /> -->

		<!-- <script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script> -->		

	</body>
</html>