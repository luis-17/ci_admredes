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
		$(document).ready(function(){
	        $("#dni").keyup(function () {
	        	$("#dni").each(function () {
		            dni=$('#dni').val();
		            $.post("<?=base_url();?>index.php/planesDni", { dni: dni}, function(data){
		            $("#data").html(data);
		            });
		        });	               
	        })
	    });
		</script>	

		<script type="text/javascript">
		 /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
		    $(document).ready(function(){
		       $("#idproveedor").change(function () {
		               $("#idproveedor option:selected").each(function () {
		                idproveedor=$('#idproveedor').val();
		                $.post("<?=base_url();?>index.php/ruc", { idproveedor: idproveedor}, function(data){
		                $("#ruc").html(data);
		                });            
		            });
		       })
		    });
		</script>	

		<script>
		   $(document).ready(function() {
		   $(window).keydown(function(event){
		     if(event.keyCode == 13) {
		       event.preventDefault();
		       return false;
		     }
		   });
		 });
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

							<li class="active"><a href="#">
								Proveedores
							</a>
							</li>
							<li class="active">Mesa de Partes</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Documentos Recibidos
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li <?=$class1?>>
											<a data-toggle="tab" href="#faq-tab-1">
												Centros Médicos
											</a>
										</li>

										<li <?=$class2?>>
											<a data-toggle="tab" href="#faq-tab-2">
												Otros Proveedores
											</a>
										</li>	
										<li>
											<a data-toggle="tab" href="#faq-tab-4">
												Otros Documentos
											</a>
										</li>	
										<li <?=$class3?>>
											<a data-toggle="tab" href="#faq-tab-3">
												Documentos Recibidos
											</a>
										</li>							
									</ul>
									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="<?=$class11?>">	
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
														<div class="alert alert-info">
															<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_orden" class="form-horizontal">
																<div class="profile-info-name"> N° Orden Atención: </div>
																<div class="profile-info-name">
																	<input class="form-control input-mask-date" type="text" id="nro_orden" name="nro_orden" required="true" value="<?=$nro_orden;?>" >
																</div>
																<div  class="profile-info-name">
																	<button type="submit" class="btn btn-info btn-xs" name="accion" value="buscar">Buscar 
																		<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																	</button>
																</div>
															</form>	
														</div>
													</div>
												</div>
											</div>
											<br>
											<?php if(!empty($datos_orden)) {?>
											<div class="row">
												<div align="center">								
													<div class="col-xs-12 col-sm-12">
														<!-- PAGE CONTENT BEGINS -->
														<?php foreach($datos_orden as $o){?>
														<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/guardar_recepcion">
															<input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$o->idsiniestro;?>" >

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Generada en:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" class="col-xs-12 col-sm-5" value="<?=$o->nombre_comercial_pr?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">DNI:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" class="col-xs-12 col-sm-5" value="<?=$o->aseg_numDoc?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Afiliado:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  required="true" type="text" class="col-xs-12 col-sm-5" value="<?=$o->afiliado?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Nombre Comercial:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<select  class="col-xs-12 col-sm-5" id="idproveedor" name="idproveedor" required="Seleccione una opción de la lista">
																			<option>Seleccionar</option>
																			<?php foreach ($getProveedores as $p) { ?>
																				<option value="<?=$p->idproveedor?>" ><?=$p->nombre_comercial_pr?></option>
																			<?php } ?>
																		</select>
																	</div>
																</div>																
															</div>

															<div id="ruc"></div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Recepción:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="date" id="recepcion" name="recepcion" class="col-xs-12 col-sm-3" value="<?=$hoy?>" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Emisión:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="date" id="emision" name="emision" class="col-xs-12 col-sm-3" value="" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Documento:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="col-sm-2">
																		<div class="form-group">
																			<select  class="control-label col-xs-12 col-sm-11 no-padding-left" name="tipodoc" required="Seleccione una opción de la lista">
																				<option value="B">Boleta</option>
																				<option value="F">Factura</option>
																				<option value="R">Recibo</option>
																			</select>
																		</div>
																	</div>
																	<div class="col-sm-1">
																		<div class="form-group">
																			<input  type="text" id="serie" name="serie" class="col-xs-12 col-sm-11" value="" placeholder="Serie" >
																		</div>
																	</div>
																	<div class="col-sm-2">
																		<div class="form-group">
																			<input  type="text" id="numero" name="numero" class="col-xs-12 col-sm-11" value="" placeholder="Número" >
																		</div>
																	</div>	
																</div>					
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Importe (S/.):</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" id="importe" name="importe" class="col-xs-12 col-sm-3" value="" >
																	</div>
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
														<?php }?>
													</div>
												</div>
											</div>
											<?php } ?>
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
														<?=$mensaje?>
													</div>
												</div>
											</div>
										</div>

										<div id="faq-tab-2" class="<?=$class21?>">	
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
														<div class="alert alert-info">
															<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_ruc" class="form-horizontal">
																<div class="profile-info-name"> N° RUC: </div>
																<div class="profile-info-name">
																	<input class="form-control input-mask-date" type="text" id="ruc" name="ruc" required="true" value="<?=$ruc;?>" >
																</div>
																<div  class="profile-info-name">
																	<button type="submit" class="btn btn-info btn-xs" name="accion" value="buscar">Buscar 
																		<i class="ace-icon glyphicon glyphicon-search bigger-110 icon-only"></i>
																	</button>
																</div>
															</form>	
														</div>
													</div>
												</div>
											</div>
											<br>
											<?php if(!empty($datos_ruc)) {?>
											<div class="row">
												<div align="center">								
													<div class="col-xs-12 col-sm-12">
														<!-- PAGE CONTENT BEGINS -->
														<?php foreach($datos_ruc as $r){?>
														<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/guardar_recepcion2">
															<input type="hidden" id="idproveedor_int" name="idproveedor_int" value="<?=$r->idproveedor_int;?>" >

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Razón Social:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" class="col-xs-12 col-sm-5" value="<?=$r->razon_social_pr?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Nombre Comercial:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" class="col-xs-12 col-sm-5" value="<?=$r->nombre_comercial_pr?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Dirección:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  required="true" type="text" class="col-xs-12 col-sm-5" value="<?=$r->direccion?>" disabled>
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Recepción:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="date" id="recepcion" name="recepcion" class="col-xs-12 col-sm-3" value="<?=$hoy?>" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Emisión:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="date" id="emision" name="emision" class="col-xs-12 col-sm-3" value="" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Documento:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="col-sm-2">
																		<div class="form-group">
																			<select  class="control-label col-xs-12 col-sm-11 no-padding-left" name="tipodoc" required="Seleccione una opción de la lista">
																				<option value="B">Boleta</option>
																				<option value="F">Factura</option>
																				<option value="R">Recibo</option>
																			</select>
																		</div>
																	</div>
																	<div class="col-sm-1">
																		<div class="form-group">
																			<input  type="text" id="serie" name="serie" class="col-xs-12 col-sm-11" value="" placeholder="Serie" >
																		</div>
																	</div>
																	<div class="col-sm-2">
																		<div class="form-group">
																			<input  type="text" id="numero" name="numero" class="col-xs-12 col-sm-11" value="" placeholder="Número" >
																		</div>
																	</div>	
																</div>					
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Importe (S/.):</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="text" id="importe" name="importe" class="col-xs-12 col-sm-3" value="" >
																	</div>
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
														<?php }?>
													</div>
												</div>
											</div>
											<?php } ?>
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
														<?=$mensaje2?>
													</div>
												</div>
											</div>
										</div> 

										<div id="faq-tab-4" class="<?=$class41?>">	
											<div class="row">
												<div align="center">								
													<div class="col-xs-12 col-sm-12">
														<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/guardar_recepcion3">
															<input type="hidden" id="idproveedor_int" name="idproveedor_int" value="" >

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Remitente:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input name="remitente" maxlength="255"  type="text" class="col-xs-12 col-sm-5" value="" >
																	</div>
																</div>																	
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Asunto:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input name="asunto" maxlength="255"  type="text" class="col-xs-12 col-sm-5" value="" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Fecha de Recepción:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="clearfix">
																		<input  type="date" id="recepcion" name="recepcion" class="col-xs-12 col-sm-3" value="<?=$hoy?>" >
																	</div>
																</div>																
															</div>

															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-4 no-padding-right" for="name">Documento:</label>
																<div class="col-xs-12 col-sm-8">
																	<div class="col-sm-2">
																		<div class="form-group">
																			<select  class="control-label col-xs-12 col-sm-11 no-padding-left" name="tipodoc" required="Seleccione una opción de la lista">
																				<option value="OF">Oficio</option>
																				<option value="S">Solicitud</option>
																				<option value="C">Comunicado</option>
																				<option value="CA">Carta</option>
																				<option value="O">Otro</option>
																			</select>
																		</div>
																	</div>
																	<div class="col-sm-3">
																		<div class="form-group">
																			<input maxlength="20"  type="text" id="numero" name="numero" class="col-xs-12 col-sm-11" value="" placeholder="Número" >
																		</div>
																	</div>	
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
													</div>
												</div>
											</div>
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
														<?=$mensaje2?>
													</div>
												</div>
											</div>
										</div> 

										<div id="faq-tab-3" class="<?=$class31?>">	
											<div class="row">
												<div align="center">								
													<div class="col-xs-9 col-sm-12">
															<div class="alert alert-info">

																<form name="form" id="form" method="post" action="<?=base_url()?>index.php/consultar_recepciones_buscar" class="form-horizontal">
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
																</form>	
															</div>
													</div>
												</div>
											</div>
											<br>
											<div class="col-xs-12">
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>ID</th>																
																<th>Tipo</th>
																<th>Proveedor</th>
																<th>Rececpciona</th>
																<th>Fecha Recepción</th>
																<th>Fecha Emisión</th>
																<th>Comprobante</th>
																<th>Importe</th>
																<th></th>
															</tr>
														</thead>

														<tbody>
															<?php foreach($getRecibidos as $r){
																$importe = $r->importe;
																$importe = number_format((float)$importe, 2, '.', '');
																?>
															<tr>
																<td><?=$r->idrecepcion?></td>
																<td><?=$r->tipo?></td>
																<td><?=$r->proveedor?></td>
																<td><?=$r->username?></td>
																<td><?=$r->fecha_recepcion?></td>
																<td><?=$r->fecha_emision?></td>
																<td><?=$r->tipo_documento?>-<?=$r->comprobante?> <?php if($r->num_orden_atencion!=''){ echo '(OA'.$r->num_orden_atencion.')';} ?></td>
																<td style="text-align: right;"><?=$importe?></td>
																<td  style="width: 5%;">
																	<div class="hidden-sm hidden-xs btn-group">
																		<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																			&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/editar_recepcion/<?=$r->idrecepcion?>" data-fancybox-width="950" data-fancybox-height="690">
																				<i class="ace-icon fa fa-pencil blue bigger-120"></i>
																			</a>
																		</div>																			
																	</div>
																	<div class="hidden-md hidden-lg">
																		<div class="inline pos-rel">
																			<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">			
																				<li>
																					<div title="Editar Reserva" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																						&nbsp;<a class="boton fancybox" href="<?=base_url()?>index.php/editar_recepcion/<?=$r->idrecepcion?>" data-fancybox-width="950" data-fancybox-height="690">
																							<i class="ace-icon fa fa-pencil blue bigger-120"></i>
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
													</table>
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
									</div>


								<!-- PAGE CONTENT ENDS -->
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
			if('ontouchstart' in document.documentElement) document.write("<<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script>
	</body>

</html>
