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

							<li>
								<a href="<?=base_url()?>index.php/siniestros">Siniestros</a>
							</li>

							<li class="active">
								OA<?=$nro_orden?>
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Siniestro OA<?=$nro_orden?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<div class="col-xs-9 col-sm-12">
									<div class="widget-box transparent">
										<div class="profile-user-info profile-user-info-striped">
											<div class="profile-info-row">
												<div class="profile-info-name"> RUC: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$ruc?></span>
												</div>

												<div class="profile-info-name"> Razón Social: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$razon_social?></span>
												</div>

												<div class="profile-info-name"> Nombre Comercial: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$nombre_comercial?></span>
												</div>
											</div>

											<div class="profile-info-row">	
												<div class="profile-info-name"> Tipo de Documento: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?php if($tipo_documento=='F'){ echo 'Factura';}else{ echo 'Boleta';}?></span>
												</div>

												<div class="profile-info-name"> N° Documento: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$serie?> - <?=$numero?></span>
												</div>

												<div class="profile-info-name"> Importe: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$importe?></span>
												</div>
											</div>

											<div class="profile-info-row">
												<div class="profile-info-name"> Fecha Atención: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$fecha_atencion?></span>
												</div>

												<div class="profile-info-name"> N° DNI: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$dni?></span>
												</div>

												<div class="profile-info-name"> Afiliado: </div>
												<div class="profile-info-value">
													<span class="editable editable-click" id="age"><?=$afiliado?></span>
												</div>
											</div>
										</div>
									</div>
								</div>	
								
								<div class="col-xs-9 col-sm-12">
									<br><br>
								<table class="table table-responsive-md table-striped table-bordered table-hover">
					              <thead>
					              	<tr>
					              		<th colspan="4"><?=$nombre_plan?></th>
					              	</tr>
					              	<tr>
					                <th colspan="2"><input type="image" width="40" src="https://www.red-salud.com/rsadmin/public/assets/images/aqui.gif"> Validar Cobertura</th>
					                <th style="text-align: center;">Copago/Coaseguro</th>
					                <th style="text-align: center;">Eventos</th>
					            	</tr>
					              </thead>
					              <?php
					              foreach ($coberturas as $c) { 
					                    if($c->num_eventos>0){
					                      if($c->total_vez<>0 and $c->total_vez==$c->vez_actual){
					                      }                     
					                    }
					                    $cert_iniVigc = $c->cert_iniVig;
					                    $estado_cobertura='';
					                    $titulo= 'CONSULTAR '.$c->nombre_var;
					                    $img='https://www.red-salud.com/rsadmin/iconos/'.$c->idvariableplan.'.png';
					                    $color="";
					                    
					                      if($c->iniVig<>0){
					                        $tiempo_cob = $c->iniVig;
					                        $cert_iniVigc1 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
					                        $cert_iniVigc2 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
					                        $tiempo_cob2 = date('d/m/Y', ($cert_iniVigc2));
					                        $fecha = time();
					                        if($fecha<$cert_iniVigc1){
					                          $estado_cobertura='disabled';
					                          $titulo= 'Cobertura inactiva hasta el '.$tiempo_cob2;
					                          $img='https://www.red-salud.com/rsadmin/iconos/bloqueada.png';
					                          $color="red";
					                        }
					                      }

					                      if($c->finVig<>0){
					                        $tiempo_cob = $c->finVig;
					                        $cert_finVigc1 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
					                        $cert_finVigc2 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
					                        $tiempo_cob2 = date('d/m/Y', ($cert_finVigc2));
					                        $fecha = time();
					                        if($fecha>$cert_finVigc1){
					                          $estado_cobertura='disabled';
					                          $titulo= 'Cobertura inactiva desde el '.$tiempo_cob2;
					                          $img='https://www.red-salud.com/rsadmin/iconos/bloqueada.png';
					                          $color="red";
					                        }
					                      }
					              ?>     
					              <form method="post" action="<?=base_url()?>index.php/generar_orden" id="form1" name="form1">
					                <input type="hidden" name="cert_id" value="<?=$c->cert_id?>">
					                <input type="hidden" name="aseg_id" value="<?=$c->aseg_id?>">
					                <input type="hidden" name="certase_id" value="<?=$c->certase_id?>">    
					                    <tbody>
					              <tr>
					                <td width="5%" style="vertical-align: middle; text-align: center;">
					                  <a class="boton fancybox" title="<?=$titulo?>" href="<?= base_url()?>index.php/detalle_cobertura/<?=$c->idplandetalle?>/<?=$c->certase_id?>/<?=$c->idvariableplan?>/<?=$c->estado?>" data-fancybox-width="950" data-fancybox-height="690">
					                    <input type="image" height="50%" src="<?=$img?>" <?=$estado_cobertura?>> 
					                  </a>
					                </td>
					                <td style="vertical-align: middle;">
					                  <span style="text-align:justify; color: <?=$color?>;">
					                  <b><?=$c->nombre_var?></b> <?=$c->texto_web?>
					                  </span>
					                </td>
					                <td style="color: <?=$color;?>; vertical-align: middle; text-align: center;"><?=$c->cobertura?></td>
					                <td width="15%"  style="color: <?=$color;?>; vertical-align: middle; text-align: center;"><?php switch($c->tiempo){
					                  case '':
					                    echo "Ilimitados";
					                    break;
					                  case '1 month':
					                    if($c->num_eventos==1){
					                      $men = "evento al mes";
					                    }else{
					                      $men = "eventos mensuales";
					                    }
					                    echo $c->num_eventos." ".$men;
					                    break;
					                  case '2 month':
					                     if($c->num_eventos==1){
					                      $men = "evento bimestral";
					                    }else{
					                      $men = "eventos bimestrales";
					                    }
					                    echo $c->num_eventos." ".$men;
					                    break;
					                  case '3 month':
					                    if($c->num_eventos==1){
					                      $men = "evento trimestral";
					                    }else{
					                      $men = "eventos trimestrales";
					                    }
					                    echo $c->num_eventos." ".$men;
					                    break;
					                  case '6 month':
					                    if($c->num_eventos==1){
					                      $men = "evento semestral";
					                    }else{
					                      $men = "eventos semestrales";
					                    }
					                    echo $c->num_eventos." ".$men;
					                    break;
					                  case '1 year':
					                    if($c->num_eventos==1){
					                      $men = "evento al año";
					                    }else{
					                      $men = "eventos anuales";
					                    }
					                    echo $c->num_eventos." ".$men;
					                    break;
					                }?></td>
					              </tr>
					              <?php } ?>            
					            </tbody>
					            </table> 
					        	</div>
							</div>
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