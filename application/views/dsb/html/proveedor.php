<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<script type="text/javascript" src="<?=base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
		<!-- FancyBox -->
		<!-- Add jQuery library -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
		
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

		<!-- Add fancyBox -->
		<link rel="stylesheet" href="<?=base_url()?>public/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="<?=base_url()?>public/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

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
		<script src="public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							RED SALUD
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">

						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="public/assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Bienvenido,</small>
									acavero
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="#">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="active">
						<a href="index.html">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Menú </span>
						</a>

						<b class="arrow"></b>
					</li>

					<?php foreach ($menu1 as $u):

						$idmenu1=$u->Id;

					?>
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								<?=$u->menu; ?>
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>

						<?php foreach ($menu2 as $uv):
							if ($idmenu1==$uv->idmenu) : ?>

								<ul class="submenu">
									<li class="">

										<a href="<?php echo base_url($uv->archivo)?>">
											<i class="menu-icon fa fa-caret-right"></i>

											<?=$uv->submenu;?>
										</a>

										<!-- <a href="<?php //echo base_url().$uv->archivo; ?>">
											<i class="menu-icon fa fa-caret-right"></i>

											<?=$uv->submenu;?>
											
											<b class="arrow fa fa-angle-down"></b>
										</a> -->

										<b class="arrow"></b>
									</li>
								</ul>

						<?php  
						endif;

						endforeach; ?>




					</li>

					<?php endforeach; ?>
				</ul>

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>
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
								<a href="#">Home</a>
							</li>

							<li>
								<a href="#">Forms</a>
							</li>
							<li class="active">Proveedores</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">	

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Proveedores
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="well well-sm">
									Puedes descargar el manual de proveedores aquí:
									<a href="https://www.red-salud.com" target="_blank">
										https://www.red-salud.com
										<i class="fa fa-external-link bigger-110"></i>
									</a>
								</div>

								<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all" id="gbox_grid-table" dir="ltr" style="width: 100%;" align="center">
								<div class="ui-widget-overlay jqgrid-overlay" id="lui_grid-table"></div>
								<div class="loading ui-state-default ui-state-active" id="load_grid-table" style="display: none;">
								Loading...</div>
								<div class="ui-jqgrid-view" id="gview_grid-table" style="width: 100%;">
									<div class="ui-jqgrid-titlebar ui-jqgrid-caption ui-widget-header ui-corner-top ui-helper-clearfix">
										<a role="link" class="ui-jqgrid-titlebar-close ui-corner-all HeaderButton" style="right: 100%;">
											<span class="ui-icon ui-icon-circle-triangle-n"></span>
										</a>
										<span class="ui-jqgrid-title">Resultados</span>
									</div>
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%;">
									<div class="ui-jqgrid-hbox">
										<table class="ui-jqgrid-htable" style="width: 100%;" role="grid" aria-labelledby="gbox_grid-table" cellspacing="0" cellpadding="0" border="0">
											<thead>
												<tr class="ui-jqgrid-labels" role="rowheader">
													<th id="grid-table_cb" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 3%;">
														<div id="jqgh_grid-table_cb">
															<input role="checkbox" id="cb_grid-table" class="cbox" type="checkbox">
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_subgrid" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 5%;">
														<div id="jqgh_grid-table_subgrid">
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_sdate" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 10%;">
														<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;</span>
														<div id="jqgh_grid-table_sdate" class="ui-jqgrid-sortable">N° DOCUMENTO
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_name" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 15%;">
														<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;
														</span>
														<div id="jqgh_grid-table_name" class="ui-jqgrid-sortable">RAZÓN SOCIAL
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_stock" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 15%;">
														<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;</span>
														<div id="jqgh_grid-table_stock" class="ui-jqgrid-sortable">NOMBRE COMERCIAL
															<span class="s-ico" style="display:none">
															<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
															<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_ship" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 20%;">
													<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;</span>
														<div id="jqgh_grid-table_ship" class="ui-jqgrid-sortable">DIRECCIÓN
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
																</span>
														</div>
													</th>
													<th id="grid-table_note" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 13%;">
														<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;</span>
														<div id="jqgh_grid-table_note" class="ui-jqgrid-sortable">UBIGEO
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
													<th id="grid-table_note" role="columnheader" class="ui-state-default ui-th-column ui-th-ltr" style="width: 9%;">
														<span class="ui-jqgrid-resize ui-jqgrid-resize-ltr" style="cursor: col-resize;">&nbsp;</span>
														<div id="jqgh_grid-table_note" class="ui-jqgrid-sortable">ESTADO
															<span class="s-ico" style="display:none">
																<span sort="asc" class="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr"></span>
																<span sort="desc" class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr"></span>
															</span>
														</div>
													</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class="ui-jqgrid-bdiv" style="height: 250px; width: 100%;">
									<div style="position:relative;">
										<div>
										</div>
											<table id="grid-table" tabindex="0" cellspacing="0" cellpadding="0" border="0" role="grid" aria-multiselectable="true" aria-labelledby="gbox_grid-table" class="ui-jqgrid-btable" style="width: 100%;">
												<tbody>
													<tr class="jqgfirstrow" role="row" style="height:auto">
														<td role="gridcell" style="height: 0px; width: 3%;"></td>
														<td role="gridcell" style="height: 0px; width: 5%;"></td>
														<td role="gridcell" style="height: 0px; width: 10%;"></td>
														<td role="gridcell" style="height: 0px; width: 15%;"></td>
														<td role="gridcell" style="height: 0px; width: 15%;"></td>
														<td role="gridcell" style="height: 0px; width: 20%;"></td>
														<td role="gridcell" style="height: 0px; width: 13%;"></td>
														<td role="gridcell" style="height: 0px; width: 9%;"></td>
													</tr>												
													<?php $cont=1;
													foreach ($proveedores as $pr): 
														$dep=$pr->dep;
														$prov=$pr->prov;
														$dist=$pr->dist;
														if($cont==2):
															$estilorow="ui-widget-content jqgrow ui-row-ltr ui-priority-secondary";
															$cont=1;
															else:
																$estilorow="ui-widget-content jqgrow ui-row-ltr";
																$cont++;
														endif;

														if($pr->estado_pr==1):
															$estilo="ui-icon ui-icon-cancel";
															$titulo="Inhabilitar";
															$funcion="inhabilitar_proveedor";
															$estado="Activo";
															else:
																$estilo="ui-icon ui-icon-disk";
																$titulo="Habilitar";
																$funcion="habilitar_proveedor";
																$estado="Inactivo";
														endif;?>
													<tr role="row" id="12" tabindex="-1" class="<?=$estilorow?>">
														<td role="gridcell" style="text-align:center;width:" aria-describedby="grid-table_cb">
															<input role="checkbox" type="checkbox" id="jqg_grid-table_12" class="cbox" name="jqg_grid-table_12">
														</td>
														<td role="gridcell" style="" title="" aria-describedby="grid-table_myac">
															<div style="">
																<div title="Editar" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																	<a class="boton fancybox" href="<?=  base_url()?>editar_proveedor/<?=$pr->idproveedor?>" data-fancybox-width="950" data-fancybox-height="690"><span class="ui-icon ui-icon-pencil"></span></a>
																</div>
																<div title="<?php echo $titulo ?>" style="float:left;" class="ui-pg-div ui-inline-del" id="jDeleteButton_12" onclick="" data-original-title="Delete selected row">
																<a href="<?=  base_url()?><?=$funcion?>/<?=$pr->idproveedor?>"><span class="<?=$estilo ?>"></span></a>
																</div>
															</div>
														</td>
														<td role="gridcell" style="" aria-describedby="grid-table_sdate"><?php echo $pr->numero_documento_pr?></td>
														<td role="gridcell" style="" aria-describedby="grid-table_name"><?php echo $pr->razon_social_pr ?></td>
														<td role="gridcell" style="" aria-describedby="grid-table_stock"><?php echo $pr->nombre_comercial_pr ?></td>
														<td role="gridcell" style="" aria-describedby="grid-table_ship"><?php echo $pr->direccion_pr ?></td>
														<td role="gridcell" style="" aria-describedby="grid-table_note"><?php echo $dep.'-'.$prov.'-'.$dist ?></td>
														<td role="gridcell" style="" aria-describedby="grid-table_note"><?php echo $estado ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
									<div class="ui-jqgrid-resize-mark" id="rs_mgrid-table">&nbsp;</div><div id="grid-pager" class="ui-state-default ui-jqgrid-pager ui-corner-bottom" dir="ltr" style="width: 100%;">
										<div id="pg_grid-pager" class="ui-pager-control" role="group">
											<table cellspacing="0" cellpadding="0" border="0" class="ui-pg-table" style="width:100%;table-layout:fixed;height:100%;" role="row">
												<tbody>
													<tr>
														<td id="grid-pager_left" align="left">
															<table cellspacing="0" cellpadding="0" border="0" class="ui-pg-table navtable" style="float:left;table-layout:auto;">
																<tbody>
																	<tr>
																		<td class="ui-pg-button ui-corner-all" title="" id="add_grid-table" data-original-title="Add new row">
																			<div class="ui-pg-div">
																				<a class="boton fancybox" href="<?=  base_url()?>nuevo_proveedor" data-fancybox-width="950" data-fancybox-height="690">
																				<span class="ui-icon ace-icon fa fa-plus-circle purple"></span></a>
																			</div>
																		</td>
																		<td class="ui-pg-button ui-corner-all" title="" id="edit_grid-table" data-original-title="Edit selected row">
																			<div class="ui-pg-div">
																				<span class="ui-icon ace-icon fa fa-pencil blue"></span>
																			</div>
																		</td>
																		<td class="ui-pg-button ui-corner-all" title="" id="view_grid-table" data-original-title="View selected row">
																			<div class="ui-pg-div">
																				<span class="ui-icon ace-icon fa fa-search-plus grey"></span>
																			</div>
																		</td>
																		<td class="ui-pg-button ui-corner-all" title="" id="del_grid-table" data-original-title="Delete selected row">
																			<div class="ui-pg-div">
																				<span class="ui-icon ace-icon fa fa-trash-o red"></span>
																			</div>
																		</td>
																		<td class="ui-pg-button ui-state-disabled" style="width:" data-original-title="" title="">
																			<span class="ui-separator"></span>
																		</td>
																		<td class="ui-pg-button ui-corner-all" title="" id="search_grid-table" data-original-title="Find records">
																			<div class="ui-pg-div">
																				<span class="ui-icon ace-icon fa fa-search orange"></span>
																			</div>
																		</td>
																		<td class="ui-pg-button ui-corner-all" title="" id="refresh_grid-table" data-original-title="Reload Grid">
																			<div class="ui-pg-div">
																				<span class="ui-icon ace-icon fa fa-refresh green"></span>
																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
														<td id="grid-pager_center" align="center" style="white-space: pre; width:50%">
															<table cellspacing="0" cellpadding="0" border="0" style="table-layout:auto;" class="ui-pg-table"><tbody><tr><td id="first_grid-pager" class="ui-pg-button ui-corner-all ui-state-disabled"><span class="ui-icon ace-icon fa fa-angle-double-left bigger-140"></span></td><td id="prev_grid-pager" class="ui-pg-button ui-corner-all ui-state-disabled"><span class="ui-icon ace-icon fa fa-angle-left bigger-140"></span></td><td class="ui-pg-button ui-state-disabled" style="width:4px;"><span class="ui-separator"></span></td><td dir="ltr">
															<div id="pagination"><?=$this->pagination->create_links(); ?></div>
																
															</td><td class="ui-pg-button ui-state-disabled" style="width:4px;"><span class="ui-separator"></span></td></tr></tbody></table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								
								<!-- PAGE CONTENT ENDS -->
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
			window.jQuery || document.write("<script src='public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='public/assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="public/assets/js/excanvas.js"></script>
		<![endif]-->
		<script src="public/assets/js/jquery-ui.custom.js"></script>
		<script src="public/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="public/assets/js/jquery.easypiechart.js"></script>
		<script src="public/assets/js/jquery.sparkline.js"></script>
		<script src="public/assets/js/flot/jquery.flot.js"></script>
		<script src="public/assets/js/flot/jquery.flot.pie.js"></script>
		<script src="public/assets/js/flot/jquery.flot.resize.js"></script>

		<!-- ace scripts -->
		<script src="public/assets/js/ace/elements.scroller.js"></script>
		<script src="public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="public/assets/js/ace/elements.fileinput.js"></script>
		<script src="public/assets/js/ace/elements.typeahead.js"></script>
		<script src="public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="public/assets/js/ace/elements.spinner.js"></script>
		<script src="public/assets/js/ace/elements.treeview.js"></script>
		<script src="public/assets/js/ace/elements.wizard.js"></script>
		<script src="public/assets/js/ace/elements.aside.js"></script>
		<script src="public/assets/js/ace/ace.js"></script>
		<script src="public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="public/assets/js/ace/ace.sidebar.js"></script>
		<script src="public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="public/assets/js/ace/ace.widget-box.js"></script>
		<script src="public/assets/js/ace/ace.settings.js"></script>
		<script src="public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="public/docs/assets/js/rainbow.js"></script>
		<script src="public/docs/assets/js/language/generic.js"></script>
		<script src="public/docs/assets/js/language/html.js"></script>
		<script src="public/docs/assets/js/language/css.js"></script>
		<script src="public/docs/assets/js/language/javascript.js"></script>
	</body>
</html>
