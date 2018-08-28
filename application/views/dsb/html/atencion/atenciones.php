<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

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
		
		<script>		
			$(document).ready(function(){
		    $('#aseg_numDoc').on('change',function(){
		        var dniAseg = $('#aseg_numDoc').val();
		        $.ajax({
		            type:'POST',
		            url:'<?=base_url()?>public/population/getEmployeeId.php',
		            dataType: "json",
		            data:{dniAseg:dniAseg},
		            success:function(data){
		                if(data.status == 'ok'){
		                    $('#aseg_nom1').val(data.result.aseg_nom1);
		                    $('#aseg_ape1').val(data.result.aseg_ape1);
		                    $('#aseg_ape2').val(data.result.aseg_ape2);
		                    //$('#aseg_fechNac').val(data.result.aseg_fechNac);
		                    $('#aseg_id').val(data.result.aseg_id);
		                    //var aseg_id = 	data.result.aseg_id                    
		                    $('.user-content').slideDown();
		                    

		                }else{
		                    $('.user-content').slideUp();
		                    alert("User not found...");
		                } 
		            }
		        });

		        $.ajax({		            

		            url:"<?=base_url()?>public/population/getPlan.php",
			        type:'POST',
			        data:{dniAseg:dniAseg},
			        success:function(response) {
			          //var resp = $.trim(response);
			          if(response != '') {
			            $("#plan").removeAttr('disabled','disabled').html(response);
			            
			          } else {
			            $("#plan").attr('disabled','disabled').html("<option value=''>------- Select --------</option>");
			          }
			        }

		        });		        
		        
			});
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
								<a href="<?=base_url()?>">Home</a>
							</li>

							<li class="active">
								Atenciones
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Atenciones
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
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Órdenes
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Pre-Órdenes
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Nueva Orden
											</a>
										</li>										
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">								

											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>No Orden</th>
																<th>No Certificado</th>
																<th>Cliente</th>
																<th>Plan</th>
																<th>Fecha</th>
																<th>Centro Médico</th>
																<th>Especialidad</th>
																<th>Asegurado</th>
																<th>Dni</th>
																<th>Detalle</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach($ordenes as $o):
																if($o->idcita==''):
																	$cita='No';
																	else:
																	$cita='Sí';
																endif;
																$fecha=$o->fecha_atencion;
																$fecha=date("d/m/Y", strtotime($fecha));
																?>

															<tr>												<td>OA<?=$o->num_orden_atencion;?></td>
																<td id = "cert"><?=$o->cert_num;?></td>
																<td><?=$o->nombre_comercial_cli?></td>
																<td><?=$o->nombre_plan;?></td>
																<td><?=$fecha;?></td>
																<td><?=$o->nombre_comercial_pr;?></td>
																<td><?=$o->nombre_esp;?></td>
																<td><?=$o->asegurado;?></td>
																<td><?=$o->aseg_numDoc;?></td>
																<td>
																	<div class="hidden-sm hidden-xs btn-group">		
																			&nbsp;
																			<a href="<?=base_url()?>index.php/siniestro/<?=$o->idsiniestro?>" title="Detalle Siniestro"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																	</div>	
																</td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->

												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>


										</div>

										<div id="faq-tab-2" class="tab-pane fade">
											<!-- star table -->		
												<div class="col-xs-12">
													<table id="simple-table" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Nro Pre-Orden</th>
																<th>Nro. Certificado</th>
																<th>Cliente</th>
																<th>Plan</th>
																<th>Fecha</th>
																<th>Centro Médico</th>
																<th>Especialidad</th>
																<th>Asegurado</th>
																<th>DNI</th>
																<th></th>
															</tr>
														</thead>

														
														<tbody>
															<?php foreach($preorden as $po):
																if($po->idcita==''):
																	$cita='No';
																	else:
																	$cita='Sí';
																endif;
																$fecha=$po->fecha_atencion;
																$fecha=date("d/m/Y", strtotime($fecha));
																?>

															<tr>
																<td>PO<?=$po->num_orden_atencion;?></td>
																<td><?=$po->cert_num;?></td>
																<td><?=$po->nombre_comercial_cli?></td>
																<td><?=$po->nombre_plan;?></td>
																<td><?=$fecha;?></td>
																<td><?=$po->nombre_comercial_pr;?></td>
																<td><?=$po->nombre_esp;?></td>
																<td><?=$po->asegurado;?></td>
																<td><?=$po->aseg_numDoc;?></td>
																<td>
																	<div>
																		<a href="<?=base_url()?>index.php/orden/<?=$po->idsiniestro?>/O"  title="Generar Orden">
																			<span class="ace-icon glyphicon glyphicon-ok"></span>
																		</a>
																	</div>
																	<div>
																		<a href="<?=base_url()?>index.php/orden/<?=$po->idsiniestro?>/A" title="Anular Pre Orden">
																			<span class="ace-icon glyphicon glyphicon-remove"></span>
																		</a>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<!-- star table -->
										<form id="creaSin" action="<?=base_url()?>index.php/creaSiniestro" method="post">
											<div class="row">
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Ingrese DNI:</b>
													<input type="text" class="form-control" value="" id="aseg_numDoc" name="aseg_numDoc" required>
												</div>
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Fecha de atención:</b>
									                <input class="form-control" id="input-date" name="fecha_atencion" type="date">                
												</div>
											  	

											  </div>
											  <div class="col-sm-4">
											  	<input type="hidden" name="aseg_id" id="aseg_id"/>
											  	

											  </div>
											</div>

											<div class="row">
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Nombre:</b>
													<input class="form-control" type="text" name="aseg_nom1" id="aseg_nom1"/>
												</div>
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Apellido Paterno:</b>
													<input class="form-control" type="text" name="aseg_ape1" id="aseg_ape1"/>            
												</div>	
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Apellido Materno:</b>
													<input class="form-control" type="text" name="aseg_ape2" id="aseg_ape2"/>            
												</div>
											  </div>
											</div>


											<div class="row">
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Plan:</b>
													<select name="plan" class="form-control" id="plan" disabled="disabled"><option>------- Select --------</option></select>
												</div>
											  </div>

											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Proveedor:</b>
													<select name="idproveedor" class="form-control" id="idproveedor" >
												       <?php if (count($proveedor)) {
														    foreach ($proveedor as $u) {
														    	echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
														    	}
														}?>
												    </select>            
												</div>	
											  </div>

											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Especialidad:</b>
													<select name="idespecialidad" id="idespecialidad" class="form-control">
												        
												       <?php if (count($especialidad)) {
														    foreach ($especialidad as $list2) {
														    	echo "<option value='". $list2['idespecialidad']."'>" . $list2['nombre_esp']."</option>";	
														    	}
														}?>
												    </select>            
												</div>
											  </div>
												<!-- end table -->
											</div>

											<fieldset style="padding-top: 25px;">
												
												<div class="row">
												  <div class="col-sm-6">
												  	<input class="btn btn-info" name="enviar" type="submit" value="Guardar">
												  </div>
												  

												</div>
											</fieldset>
										</form>
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
