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

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<!--<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>-->
		<!-- FancyBox -->
		<!-- Add jQuery library -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		
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

	<script type="text/javascript">
    /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
    $(document).ready(function(){
       $("#canal").change(function () {
               $("#canal option:selected").each(function () {
                canal=$('#canal').val();
                $.post("<?=base_url();?>index.php/planes", { canal: canal}, function(data){
                $("#plan").html(data);
                });            
            });
       })
    });

    /*fin de la funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
    </script>
		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	</head>
	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/../headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("/../sideBar.php");?>
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
							<li>
							<a href="<?=base_url()?>index.php/index">Reportes</a></li>
							<li class="active">Consultar Cobros</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">	

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Consultar Cobros
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->						
								<div align="center">								
									<div class="col-xs-9 col-sm-12">
										<div class="widget-box transparent">
											<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">

												<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_cobros_buscar" class="form-horizontal">
													<div class="profile-info-name"> Canal: </div>
													<div class="profile-info-name">
														<select name="canal" id="canal" required="Seleccione una opción de la lista" class="form-control"  style="width: 150px;">
															<option value="">Seleccione</option>
															<?php foreach ($canales as $c):
																if($canal==$c->idclienteempresa):
																	$estc='selected';
																	else:
																	$estc='';
																endif;?>
																<option value="<?=$c->idclienteempresa;?>" <?=$estc?> ><?=$c->nombre_comercial_cli?> </option>
															<?php endforeach; ?>
													</select>
													</div>
													<div class="profile-info-name"> Plan: </div>
													<div class="profile-info-name">
														<select name="plan" id="plan" required="Seleccione una opción de la lista"  class="form-control"  style="width: 150px;">											
															<option value="">Seleccione</option>
															<?php 
																$cancelar='N';
																$eliminar='N';
																foreach ($planes as $p):
																	if($plan==$p->idplan):
																		$estp='selected';
																		$cancelar=$p->flg_cancelar;
																		$eliminar=$p->flg_eliminar;
																		else:
																		$estp='';
																	endif;?>
																	<option value="<?=$p->idplan;?>" <?=$estp?> ><?=$p->nombre_plan?> </option>
																<?php endforeach; ?>				
														</select>
													</div>
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
								
								<br/>		
								<br/>
								<br/>		
								</div><!-- PAGE CONTENT ENDS -->
								<br />
								<div  align="center" >
								<div style="display: <?=$estilo;?>;">
									<!-- star table -->		
										<div  align="center" class="col-xs-12">
											<table align="center" id="simple-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Descripción</th>
														<th>Prima inc. IGV(S/.)</th>
														<th>Número de Primas</th>
														<th>Sub Total (S/.)</th>
														<th></th>
													</tr>
												</thead>

												<tbody>
													<?php $tot=0; $totcant=0;
													foreach ($cobros as $co){
													$importe=$co->cob_importe;
													$importe=$importe/100;
													$importe=number_format((float)$importe, 2, '.', ',');
													$cant=$co->cant;
													$cant2=number_format((float)$cant, 0, '', ',');
													$totcant=$totcant+$cant;
													$sub=$cant*$importe;
													$sub2=number_format((float)$sub, 2, '.', ',');
													$tot=$tot+$sub;
													$desc=$co->descripcion;
													?>
													<tr>
														<td><?=$desc;?></td>
														<td align="right"><?=$importe;?></td>	
														<td align="right"><?=$cant2;?></td>
														<td align="right"><?=$sub2;?></td>
														<td>
															<div class="hidden-sm hidden-xs btn-group">
																<div title="Ver Detalle de Cobros" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																<a class="boton fancybox" href="<?=base_url()?>index.php/consultar_detalle_cobros/<?=$co->cob_importe;?>/<?=$plan_id;?>/<?=$fecinicio;?>/<?=$fecfin?>" data-fancybox-width="950" data-fancybox-height="690">
																	<i class="ace-icon fa fa-eye bigger-120"></i>
																</a>
																</div>
															</div>

															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																			<li>
																				<div title="Ver Detalle de Cobros" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=base_url()?>index.php/consultar_detalle_cobros/<?=$co->cob_importe;?>/<?=$plan_id;?>/<?=$fecinicio;?>/<?=$fecfin?>" data-fancybox-width="950" data-fancybox-height="690">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</a>
																				</div>
																			</li>				
																		</ul>
																	</div>
																</div>
														</td>
													</tr>
													<?php } ?>
												</tbody>
												<?php 
													$tot=number_format((float)$tot, 2, '.', ',');
													$totcant=number_format((float)$totcant, 0, '', ',');?>
												<tbody>
													<td colspan="2"><b>TOTAL</b></td>
													<td align="right"><b><?=$totcant;?></b></td>
													<td align="right"><b><?=$tot;?></b></td>
													<td></td>
												</tbody>
											</table>
										</div>
										</div>
										<!-- end table -->
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
