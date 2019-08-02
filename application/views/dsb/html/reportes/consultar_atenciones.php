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
		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

	</head>
	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/home/yrg2xfqpfv7s/public_html/redsalud/rsadmin/application/views/dsb/html/headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("/home/yrg2xfqpfv7s/public_html/redsalud/rsadmin/application/views/dsb/html/sideBar.php");?>
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
							<li class="active">Consultar Atenciones</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">	

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Consultar Atenciones
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

												<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_atenciones_buscar" class="form-horizontal">
													<div class="profile-info-name"> Canal: </div>
													<div class="profile-info-name">
														<select name="canal" id="canal" required="Seleccione una opción de la lista" class="form-control" style="width: 150px;">
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
														<select name="plan" id="plan" required="Seleccione una opción de la lista"  class="form-control" style="width: 150px;">											
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
														<input class="form-control input-mask-date" type="date" id="fechainicio" name="fechainicio" required="Seleccione una fecha de inicio" value="<?=$fecinicio;?>">
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
								</div><!-- PAGE CONTENT ENDS -->
								<br />
								<div>
								<div style="display: <?=$estilo;?>;">
								<p><div class="profile-info-row">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger label-white middle">RA = Reserva Anulada</span>&nbsp;&nbsp;
									<span class="label label-warning label-white middle">RPC = Reserva Por Confirmar</span>&nbsp;&nbsp;
									<span class="label label-success label-white middle">RC = Reserva Confirmada</span>&nbsp;&nbsp;
									<span class="label label-info label-white middle">AA = Atención Abierta</span>&nbsp;&nbsp;
									<span class="label label-purple label-white middle">AC = Aención Cerrada</span>&nbsp;&nbsp;
									<span class="label label-danger label-white middle">AAN = Atención Anulada</span>
								</div>
								</p>
									<!-- star table -->		
										<div  align="center" class="col-xs-12">
											<table align="center" id="example" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>N° Atención</th>
														<th>Fecha</th>
														<th>N° Documento</th>
														<th>Afiliado</th>
														<th>N° Teléfono</th>
														<th>Atendido por</th>
														<th>Centro Médico</th>
														<th>Especialidad</th>
														<th>Estado</th>
													</tr>
												</thead>

												<tbody>
													<?php $tot=0; $totcant=0;
													foreach ($atenciones as $a):
														if($a->estado_atencion=='P'){
															switch ($a->estado_cita):
																case 0: 
																	$estadoa='RA';
																	$class="label label-danger label-white middle";
																	$e1=0;
																break;
																case 1:
																	$estadoa='RPC';
																	$e1=1;
																	$class="label label-warning label-white middle";
																break;
																case 2:
																	$estadoa='RC';
																	$e1=2;
																	$class="label label-success label-white middle";
																break;
															endswitch;
														}else{
															switch($a->estado_siniestro):
																case 0: 
																	$estadoa='AAN';
																	$e2=0;
																	$class="label label-danger label-white middle";
																break;
																case 1:
																	$estadoa='AA';
																	$e2=1;
																	$class="label label-info label-white middle";
																break;
																case 2:
																	$estadoa='AC';
																	$e2=2;
																	$class="label label-purple label-white middle";
																break;
															endswitch;
														}
													?>
													<tr>
														<td><?=$a->tipo_atencion;?></td>	
														<td><?php echo date("d-m-Y",strtotime($a->fecha_atencion));?></td>
														<td><?=$a->aseg_numDoc;?></td>
														<td><?=$a->asegurado;?></td>														
														<td><?=$a->aseg_telf;?></td>
														<td><?php if($a->username == ""){ echo "redes";}else{echo $a->username;} ?></td>
														<td><?=$a->nombre_comercial_pr;?></td>
														<td><?=$a->nombre_esp;?></td>
														<td><span class="<?=$class;?>"><?=$estadoa;?></span></td>														
													</tr>
													<?php endforeach; ?>
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
