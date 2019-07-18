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
							<li><a href="#">Tablas Maestras</a></li>
							<li><a href="<?=base_url()?>index.php/diagnosticos">Diagnósticos</a></li>
							<li class="active">Editar Diagnósticos</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							<?=$descripcion_cie?>					
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
									
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="formAct" name="formAct" role="form" method="post">
									<input type="hidden" name="iddiagnostico" id="iddiagnostico" value="<?=$iddiagnostico?>">

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Código CIE: </label>

										<div class="col-sm-9">
											<input type="text" id="codigo_cie" name="codigo_cie" class="col-xs-10 col-sm-8" value="<?=$codigo_cie?>"><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Descripción: </label>

										<div class="col-sm-9">
											<input type="text" id="descripcion_cie" name="descripcion_cie" class="col-xs-10 col-sm-8" value="<?=$descripcion_cie?>"><label style="color: #FF0101;">&nbsp;*</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tipo: </label>

										<div class="col-sm-6">
											<select name='tipo' id='tipo' class='form-control'>
			                                    <option value='1'>--Seleccione tipo de diagnóstico--</option>
			                                    <option value='1'>Capa simple</option>
			                                    <option value='2'>Preexistente</option>
			                                </select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Medicamentos: </label>

										<div class="col-sm-9">
											<div id="serviciosT">
												<br>
											<table id="example" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Medicamento</th>
														<th>Presentación</th>
														<th>Estado</th>
														<th>Opciones</th>
													</tr>
												</thead>
												<tbody>
												
												<?php foreach($medicamentos as $m){?>
													<tr>
														<td><?=$m->idmedicamento?></td>
														<td><?=$m->nombre_med?></td>
														<td><?=$m->presentacion_med?></td>
														<td><?php if($m->checked==''){
															echo '<span class="label label-danger label-white middle">Inactivo</span>';
															}else{
																echo '<span class="label label-info label-white middle">Activo</span>';
																}?></td>
														<td>
															<?php if($m->checked==''){
																echo '<a title="Agregar medicamento" href="'.base_url().'index.php/add_medicamento/'.$m->idmedicamento.'/'.$iddiagnostico.'"><i class="ace-icon glyphicon glyphicon-plus red"></i></a>';
															}else{
																echo '<a title="Eliminar medicamento" href="'.base_url().'index.php/del_medicamento/'.$m->idmedicamento.'/'.$iddiagnostico.'"><i class="ace-icon  	glyphicon glyphicon-ban-circle blue"></i></a>';
															} ?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>	
										<script>			
												//para paginacion
												$(document).ready(function() {
												$('#example').DataTable( {
												"pagingType": "full_numbers"
												} );
											} );
											</script>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Productos: </label>

										<div class="col-sm-9">
											<div id="serviciosT">
												<br>
											<table id="exampledos" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Producto</th>
														<th>Estado</th>
														<th>Opciones</th>
													</tr>
												</thead>
												<tbody>
												
												<?php foreach($productos as $p){?>
													<tr>
														<td><?=$p->idproducto?></td>
														<td><?=$p->descripcion_prod?></td>	
														<td><?php if($p->checked==''){
															echo '<span class="label label-danger label-white middle">Inactivo</span>';
															}else{
																echo '<span class="label label-info label-white middle">Activo</span>';
																}?></td>
														<td>
															<?php if($p->checked==''){
																echo '<a title="Agregar producto" href="'.base_url().'index.php/add_producto/'.$p->idproducto.'/'.$iddiagnostico.'"><i class="ace-icon glyphicon glyphicon-plus red"></i></a>';
															}else{
																echo '<a title="Eliminar producto" href="'.base_url().'index.php/del_producto/'.$p->idproducto.'/'.$iddiagnostico.'"><i class="ace-icon  	glyphicon glyphicon-ban-circle blue"></i></a>';
															} ?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>	
										<script>			
												//para paginacion
												$(document).ready(function() {
												$('#exampledos').DataTable( {
												"pagingType": "full_numbers"
												} );
											} );
											</script>
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

		<script>
			$(document).ready(function() {
   				$("#formAct").submit(function(e) {
    				e.preventDefault();
		            $.ajax({
		                url: "<?= BASE_URL()?>index.php/Diagnosticos_cnt/act_datos",
		                type: 'POST',
		                dataType: 'json',
		                data: $("#formAct").serialize(),
		                complete:function(){
		                    alert('Se actualizaron los datos correctamente');
							window.location.href = ("<?= BASE_URL()?>index.php/diagnosticos");
		                },
		                success: function(data)
		                {   

		                }
		            });
		            return false;
   				});
   			});
		</script>
		
	</body>
</html>
