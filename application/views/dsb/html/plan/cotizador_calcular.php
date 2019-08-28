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
							<li><a href="<?=base_url()?>index.php/cotizador">Cotizador</a></li>
							<li class="active"><?=$accion?></li>
						</ul><!-- /.breadcrumb -->
						<!-- /section:basics/content.searchbox -->
					</div>
					
					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="page-header">
							<h1>	
							<?=str_replace("%20"," ",$nom);?>						
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="formCalc" name="formCalc" role="form" method="post" action="<?=base_url()?>index.php/plan_guardar">
									<input type="hidden" id="nom" name="nom" value="<?=$nom;?>">
									<input type="hidden" id="idcotizacion" name="idcotizacion" value="<?=$idcotizacion;?>">
									<input type="hidden" id="idcotizaciondetalle" name="idcotizaciondetalle" value="<?=$idcotizaciondetalle;?>">
									<?php 
									if($iddet==0):
										$item=0;
										$desc="";
										$visible=2;
										$style='disabled';
										$chk='';
										$flg='';
										$op='';
										$val='';
										$num_eventos="";
									else:
										foreach ($detalle as $det) :
											$item=$det->idvariableplan;
											$desc=$det->texto_web;
											$visible=$det->visible;
											$flg=$det->flg_liquidacion;
											$op=$det->simbolo_detalle;
											$val=$det->valor_detalle;
											$num_eventos=$det->num_eventos;
										endforeach;
									endif;
									?>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Cobertura: </label>

										<div class="col-sm-9">
											<select id="item" name="item" value="" class="col-xs-10 col-sm-5">
												<option value="">Seleccionar</option>
												<?php foreach($items as $i): 
													if($i->idvariableplan==$item):
														$esti="selected";
														else:
															$esti="";
													endif;?>
												<option value="<?=$i->idvariableplan;?>" <?=$esti;?> ><?=$i->nombre_var;?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Descripción: </label>

										<div class="col-sm-9">
											<textarea  class="col-xs-10 col-sm-5" id="descripcion" name="descripcion" cols="36" rows="4" placeholder="Escribe aquí una descripción"><?=$desc;?></textarea>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" type="button" id="agregarCob" name="agregarCob">
												<i class="glyphicon glyphicon-plus bigger-110"></i>
												Agregar
											</button>
										</div>
									</div>
									<hr><br>

									<div class="widget-toolbar no-border invoice-info">
										<button title="Ver detalle de cotización" class="btn btn-white btn-info" id="verCoti" name="verCoti">
											<i class="ace-icon fa fa-eye bigger-120 blue"></i>
										</button>
										<button title="Editar cotización" class="btn btn-white btn-info" id="editCoti" name="editCoti">
											<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
										</button>
									</div>
									<br/>
									<br/>

									<!--<div class="widget-toolbar no-border invoice-info">
										<div title="Ver detalle de cotización" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a class="boton fancybox" href="" data-fancybox-width="950" data-fancybox-height="690">
												<i class="ace-icon fa fa-eye bigger-120 blue"></i>
											</a>
										</div>
										<div title="Editar cotización" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
											&nbsp;<a href="" data-fancybox-width="950">
												<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
											</a>
										</div>	
									</div>
									<br/>
									<br/>-->
									<div class="col-xs-12" id="tablaCalc"></div>
									<br>
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-success" type="button" id="calcular" name="calcular">
											<i class="glyphicon glyphicon-remove bigger-110"></i>
											Calcular
										</button>
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											Siguiente
										</button>
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
			$(document).ready(function(){
		        $.ajax({
		            url: "<?= BASE_URL()?>index.php/Cotizador_cnt/mostrarTabla",
		            type: 'POST',
		            dataType: 'json',
		            data: $("#formCalc").serialize(),
		            success: function(data)
		            {   
		                $("#tablaCalc").html(null);
		                $("#tablaCalc").html(data);
		            }
		        });
		        return false;
			});

			$(document).ready(function(){
		        $("#agregarCob").click(function(){
			        $.ajax({
			            url: "<?= BASE_URL()?>index.php/Cotizador_cnt/agregarCobertura",
			            type: 'POST',
			            dataType: 'json',
			            data: $("#formCalc").serialize(),
			            success: function(data)
			            {   
			                $("#tablaCalc").html(null);
			                $("#tablaCalc").html(data);
			            }
			        });
			        return false;
		        })
			});

			$(document).ready(function(){
		        $("#calcular").click(function(){
			        $.ajax({
			            url: "<?= BASE_URL()?>index.php/Cotizador_cnt/calcularCobertura",
			            type: 'POST',
			            dataType: 'json',
			            data: $("#formCalc").serialize(),
			            success: function(data)
			            {   
			                $("#tablaCalc").html(null);
			                $("#tablaCalc").html(data);
			            }
			        });
			        return false;
		        })
			});

			$(document).ready(function(){
		        $("#editCoti").click(function(){
			        $.ajax({
			            url: "<?= BASE_URL()?>index.php/Cotizador_cnt/editarCobertura",
			            type: 'POST',
			            dataType: 'json',
			            data: $("#formCalc").serialize(),
			            success: function(data)
			            {   
			                $("#tablaCalc").html(null);
			                $("#tablaCalc").html(data);
			            }
			        });
			        return false;
		        })
			});
		</script>

	</body>
</html>
