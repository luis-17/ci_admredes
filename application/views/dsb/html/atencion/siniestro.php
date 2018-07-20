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

		<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
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
			$(function() {  
			  // Get the form fields and hidden div
			  var checkbox = $("#trigger");
			  var hidden1 = $("#hidden_fields1");
			  var hidden2 = $("#hidden_fields2");
			  var hidden3 = $("#hidden_fields3");
			 
			  // enabled.
			  hidden1.hide();
			  hidden2.hide();
			  hidden3.hide();
			  // Setup an event listener for when the state of the 
			  // checkbox changes.
			  checkbox.change(function() {
			    // Check to see if the checkbox is checked.
			    // If it is, show the fields and populate the input.
			    // If not, hide the fields.
			    if (checkbox.is(':checked')) {
			      // Show the hidden fields.
			      var chequeado=0;
			      $("#presscheck").val(chequeado);
			      hidden1.show();
			      hidden2.show();
			      hidden3.show();
			      document.getElementById("proveedor1").disabled = true;
			      document.getElementById("factura1").disabled = true;
			      document.getElementById("pago1").disabled = true;
			      document.getElementById("proveedor2").disabled = true;
			      document.getElementById("factura2").disabled = true;
			      document.getElementById("pago2").disabled = true;
			      document.getElementById("proveedor3").disabled = true;
			      document.getElementById("factura3").disabled = true;
			      document.getElementById("pago3").disabled = true;
			      document.getElementById("proveedor4").disabled = true;
			      document.getElementById("factura4").disabled = true;
			      document.getElementById("pago4").disabled = true;
			      // Populate the input.
			      //populate.val("Dude, this input got populated!");
			    } else {
			      // Make sure that the hidden fields are indeed
			      // hidden.
			      var chequeado=1;
			      $("#presscheck").val(chequeado);
			      hidden1.hide();
			      hidden2.hide();
			      hidden3.hide();
			      document.getElementById("proveedor1").disabled = false;
			      document.getElementById("factura1").disabled = false;
			      document.getElementById("pago1").disabled = false;
			      document.getElementById("proveedor2").disabled = false;
			      document.getElementById("factura2").disabled = false;
			      document.getElementById("pago2").disabled = false;
			      document.getElementById("proveedor3").disabled = false;
			      document.getElementById("factura3").disabled = false;
			      document.getElementById("pago3").disabled = false;
			      document.getElementById("proveedor4").disabled = false;
			      document.getElementById("factura4").disabled = false;
			      document.getElementById("pago4").disabled = false;
			      // You may also want to clear the value of the 
			      // hidden fields here. Just in case somebody 
			      // shows the fields, enters data to them and then 
			      // unticks the checkbox.
			      //
			      // This would do the job:
			      //
			      // $("#hidden_field").val("");
			    }
			  });
			});
		</script>


		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.txtCal', function () {
			       var calculated_total_sum = 0;
			     
			       $("#tblGasto .txtCal").each(function () {
			           var get_textbox_value = $(this).val();
			           
			           
			           if ($.isNumeric(get_textbox_value)) {
			              calculated_total_sum += parseFloat(get_textbox_value);
			              }

			            });
			              $("#total_sum_value").html(calculated_total_sum);
			              $("#total").val(calculated_total_sum);
			              
			       });

			});

		</script>

		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.item1', function () {
			       var calculated_total_sum_neto = 0;
			     
			       $("#tblGasto .item1").each(function () {
			          var porId=document.getElementById("espe").value;
			          var plan_id = document.getElementById('idplan').value;
			          var n1 = document.getElementById('monto1').value
			           
			           if ($.isNumeric(n1)&& porId==1 && plan_id==1) {
			              calculated_total_sum_neto = parseInt(n1);						  
			              
			              }else if ($.isNumeric(n1)&& porId==2 && plan_id==1){	   
			              	calculated_total_sum_neto = 0;		              	
			              
			              }else if ($.isNumeric(n1)&& porId==3 && plan_id==1){	   
			              	calculated_total_sum_neto = 0;		              	
			              
			              }else if ($.isNumeric(n1)&& porId==1 && plan_id==3){	   
			              	calculated_total_sum_neto = parseInt(n1)-15;		              	
			              
			              }else if ($.isNumeric(n1)&& porId==2 && plan_id==3){	   
			              	calculated_total_sum_neto = parseInt(n1)-20;		              	
			              
			              }else if ($.isNumeric(n1)&& porId==3 && plan_id==3){	   
			              	calculated_total_sum_neto = parseInt(n1)-20;              	
			              
			              }
			        });			              
			              $("#neto1").val(parseInt(calculated_total_sum_neto));	              
			       });
			});
		</script>


		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.item2', function () {
			       var calculated_total_sum_neto2 = 0;
			     
			       $("#tblGasto .item2").each(function () {
			          var porId=document.getElementById("espe").value;
			          var plan_id = document.getElementById('idplan').value;
			          var n2 = document.getElementById('monto2').value
			           
			           if ($.isNumeric(n2)&& porId==1 && plan_id==1) {
			              calculated_total_sum_neto2 = 25;						  
			              
			              }else if ($.isNumeric(n2)&& porId==2 && plan_id==1){	   
			              	calculated_total_sum_neto2 = 25;		              	
			              
			              }else if ($.isNumeric(n2)&& porId==3 && plan_id==1){	   
			              	calculated_total_sum_neto2 = 25;		              	
			              
			              }else if ($.isNumeric(n2)&& porId==1 && plan_id==3){	   
			              	calculated_total_sum_neto2 = parseInt(n2)*0.85;		              	
			              
			              }else if ($.isNumeric(n2)&& porId==2 && plan_id==3){	   
			              	calculated_total_sum_neto2 = parseInt(n2)*0.85;		              	
			              
			              }else if ($.isNumeric(n2)&& porId==3 && plan_id==3){	   
			              	calculated_total_sum_neto2 = parseInt(n2)*0.85;              	
			              
			              }
			        });			              
			              $("#neto2").val(parseInt(calculated_total_sum_neto2));              
			              
			       });
			});
		</script>


		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.item3', function () {
			       var calculated_total_sum_neto2 = 0;
			     
			       $("#tblGasto .item3").each(function () {
			          var porId=document.getElementById("espe").value;
			          var plan_id = document.getElementById('idplan').value;
			          var n3 = document.getElementById('monto3').value
			           
			           if ($.isNumeric(n3)&& porId==1 && plan_id==1) {
			              calculated_total_sum_neto3 = 0;						  
			              
			              }else if ($.isNumeric(n3)&& porId==2 && plan_id==1){	   
			              	calculated_total_sum_neto3 = 0;		              	
			              
			              }else if ($.isNumeric(n3)&& porId==3 && plan_id==1){	   
			              	calculated_total_sum_neto3 = 0;		              	
			              
			              }else if ($.isNumeric(n3)&& porId==1 && plan_id==3){	   
			              	calculated_total_sum_neto3 = parseInt(n3)*0.85;		              	
			              
			              }else if ($.isNumeric(n3)&& porId==2 && plan_id==3){	   
			              	calculated_total_sum_neto3 = parseInt(n3)*0.85;		              	
			              
			              }else if ($.isNumeric(n3)&& porId==3 && plan_id==3){	   
			              	calculated_total_sum_neto3 = parseInt(n3)*0.85;              	
			              
			              }
			        });			              
			              $("#neto3").val(parseInt(calculated_total_sum_neto3));              
			              
			       });
			});
		</script>


		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.item4', function () {
			       var calculated_total_sum_neto2 = 0;
			     
			       $("#tblGasto .item4").each(function () {
			          var porId=document.getElementById("espe").value;
			          var plan_id = document.getElementById('idplan').value;
			          var n4 = document.getElementById('monto4').value
			           
			           if ($.isNumeric(n4)&& porId==1 && plan_id==1) {
			              calculated_total_sum_neto4 = 0;						  
			              
			              }else if ($.isNumeric(n4)&& porId==2 && plan_id==1){	   
			              	calculated_total_sum_neto4 = 0;		              	
			              
			              }else if ($.isNumeric(n4)&& porId==3 && plan_id==1){	   
			              	calculated_total_sum_neto4 = 0;		              	
			              
			              }else if ($.isNumeric(n4)&& porId==1 && plan_id==3){	   
			              	calculated_total_sum_neto4 = parseInt(n4)*0.85;		              	
			              
			              }else if ($.isNumeric(n4)&& porId==2 && plan_id==3){	   
			              	calculated_total_sum_neto4 = parseInt(n4)*0.85;		              	
			              
			              }else if ($.isNumeric(n4)&& porId==3 && plan_id==3){	   
			              	calculated_total_sum_neto4 = parseInt(n4)*0.85;              	
			              
			              }
			        });			              
			              $("#neto4").val(parseInt(calculated_total_sum_neto4));              
			              
			       });
			});
		</script>

		<script>
			$(document).ready(function(){				
			$("#tblGasto").on('input', '.txtCal', function () {
			       var calculated_total_sum2 = 0;
			     
			       $("#tblGasto .txtCal2").each(function () {
			           var get_textbox_value2 = $(this).val();
			           
			           
			           if ($.isNumeric(get_textbox_value2)) {
			              calculated_total_sum2 += parseFloat(get_textbox_value2);
			              }

			            });
			              $("#total_sum_value_neto").html(calculated_total_sum2);
			              $("#total_neto").val(calculated_total_sum2);
			              
			       });

			});

		</script>

		

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
								<a href="<?=base_url()?>index">Home</a>
							</li>

							<li>
								<a href="<?=base_url()?>atenciones">Atenciones</a>
							</li>

							<li class="active">
								Siniestro
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Siniestro
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>Información datos de la atención
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
												Exámen Físico
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Diagnóstico y Tratamiento
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-3">
												Laboratorios
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-4">
												Reconsulta
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#faq-tab-5">
												Registro del Gasto
											</a>
										</li>
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">
											<h4 class="blue">
												<i class="ace-icon fa fa-briefcase bigger-110"></i>
												Exámen Físico
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">


											<!-- FORMULARIO DE EXAMEN FISICO -->

												<form action="<?=base_url()?>guardaTriaje" method="post">
													<input type="hidden" class="form-control" name="idtriaje" id="idtriaje" value="<?php echo $idtriaje?>">
													<input type="hidden" class="form-control" name="idsiniestro" id="idsiniestro" value="<?php echo $idsiniestro?>">
													<input type="hidden" class="form-control" name="idasegurado" id="idasegurado" value="<?php echo $idasegurado?>">

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												  	  <b class="text-primary">Especialidad:</b>
												      <select name="idespecialidad" id="idespecialidad" class="form-control">
												        
												       <?php if (count($especialidad)) {
														    foreach ($especialidad as $list) {
														    	if ($list['idespecialidad']==$idespecialidad){
																	
																echo "<option value='". $list['idespecialidad']."'selected>" . $list['nombre_esp']."</option>";	
														    	}else{

														    	echo "<option value='". $list['idespecialidad']."'>" . $list['nombre_esp']."</option>";			
														    	}

														    	
														        
														    }
														} ?>



												      </select>
												    </div>
												    <div class="form-group col-md-6">
												      <b class="text-primary">Motivo de Consulta:</b>
												      <input type="text" class="form-control" name="motivo_consulta" id="motivo_consulta" value="<?php echo $motivo_consulta?>">
												    </div>											    
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-4">
												    <b class="text-primary">PA (Presión Arterial)</b>
												    <input type="text" class="form-control" name="presion_arterial_mm" id="presion_arterial_mm" value="<?php echo $presion_arterial_mm?>">
													</div>

													<div class="form-group col-md-4">
												    <b class="text-primary">FC (Frecuencia Cardiaca) </b>
												    <input type="text" class="form-control" name="frec_cardiaca" id="frec_cardiaca" value="<?php echo $frec_cardiaca?>">
													</div>

													<div class="form-group col-md-4">
												    <b class="text-primary">FR (Frecuencia Respiratoria)</b>
												    <input type="text" class="form-control" name="frec_respiratoria" id="frec_respiratoria" value="<?php echo $frec_respiratoria?>">
													</div>
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-2">
												    <b class="text-primary">Peso (en Kg)</b>
												    <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $peso?>">
													</div>

													<div class="form-group col-md-2">
												    <b class="text-primary">Talla</b>
												    <input type="text" class="form-control" name="talla" id="talla" value="<?php echo $talla?>">
													</div>

													<div class="form-group col-md-8">
												    <b class="text-primary">Cabeza</b>
												    <input type="text" class="form-control" name="estado_cabeza" id="estado_cabeza" value="<?php echo $estado_cabeza?>">
													</div>
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
												    <b class="text-primary">Piel y Faneras</b>
												    <input type="text" class="form-control" name="piel_faneras" id="piel_faneras" value="<?php echo $piel_faneras?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
												    <b class="text-primary">CV:CR (Cardiovascular : Ruidos Cardiacos)</b>
												    <input type="text" class="form-control" name="cv_ruido_cardiaco" id="cv_ruido_cardiaco" value="<?php echo $cv_ruido_cardiaco?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
												    <b class="text-primary">TP:MV (Tórax y pulmones: Murmullo Vesicular)</b>
												    <input type="text" class="form-control" name="tp_murmullo_vesicular" id="tp_murmullo_vesicular" value="<?php echo $tp_murmullo_vesicular?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												    <b class="text-primary">Abdomen</b>
												    <input type="text" class="form-control" name="estado_abdomen" id="estado_abdomen" value="<?php echo $estado_abdomen?>">
													</div>

													<div class="form-group col-md-6">
												    <b class="text-primary">RHA (Ruidos hidroaéreos)</b>
												    <input type="text" class="form-control" name="ruido_hidroaereo" id="ruido_hidroaereo" value="<?php echo $ruido_hidroaereo?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
												    <b class="text-primary">Neuro</b>
												    <input type="text" class="form-control" name="estado_neurologico" id="estado_neurologico" value="<?php echo $estado_neurologico?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-12">
												    <b class="text-primary">Osteomuscular</b>
												    <input type="text" class="form-control" name="estado_osteomuscular" id="estado_osteomuscular" value="<?php echo $estado_osteomuscular?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												    <b class="text-primary">GU:PPL (Genito-Urinario: Puño percusion lumbar.)</b>
												    <input type="text" class="form-control" name="gu_puno_percusion_lumbar" id="gu_puno_percusion_lumbar" value="<?php echo $gu_puno_percusion_lumbar?>">
													</div>

													<div class="form-group col-md-6">
												    <b class="text-primary">GU:PRU (Genito-Urinario: Puntos Renouretelares)</b>
												    <input type="text" class="form-control" name="gu_puntos_reno_uretelares" id="gu_puntos_reno_uretelares" value="<?php echo $gu_puntos_reno_uretelares?>">
													</div>												
												  </div>

												  <div class="form-row">
												  	<div class="form-group col-md-6">
												    	<button type="submit" class="btn btn-primary">Guardar</button>
														<button type="cancel" class="btn btn-primary">Cancelar</button>
													</div>
																								
												  </div>

												</form>
											</div>
										</div>

									

										<div id="faq-tab-2" class="tab-pane fade">
											<h4 class="blue">
												<i class="green ace-icon fa fa-pencil-square-o bigger-110"></i>
												Diagnósticos
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Principal
														</a>
													</div>

												<div class="panel-collapse collapse" id="faq-2-1">
													<div class="panel-body">
														

													<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th>Diagnostico</th>
																	<th>Tipo</th>
																	
																	<th width="10%" colspan="2">					
																		<div title="Nuevo Diagnóstico" style="float:right;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=  base_url()?>add_diagnostico/<?=$idsiniestro?>" title="Nuevo Diagnóstico" data-fancybox-width="950" data-fancybox-height="490">
																					Nuevo Diagnóstico
																				</a>
																			</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($diagnostico as $o):
																
																if($o->tipo_diagnostico==1){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td colspan="2" style="font-weight: bold;"><?=$o->dianostico_temp;?></td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">	
																				<a class="boton fancybox" href="<?=base_url()?>add_tratamiento/<?=$o->idsiniestrodiagnostico?>" title="Nuevo Medicamento" data-fancybox-width="950" data-fancybox-height="490"><i class="ace-icon fa fa-certificate bigger-120"></i></a>
																		</div>	
																	</td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$o->idsiniestro?>" title="Eliminar Diagnostico"><i class="ace-icon fa fa-ban bigger-120"></i></a>
																		</div>	
																	</td>
																</tr>

																<?php
																foreach($medicamento as $u):
																
																if($u->idsiniestrodiagnostico==$o->idsiniestrodiagnostico and $u->tipo_tratamiento){ 
																?>
																<tr>
																	<td></td>
																	<td><?=$u->medicamento_temp.' -- Cantidad: '.$u->cantidad_trat.' -- Dosis: '.$u->dosis_trat;?>
																		
																	</td>
																	<td><?php if ($u->tipo_tratamiento==3){echo "<h6 style='color:red;'> No cubierto</h6>";}else{echo "<h6 style='color:green;'>Cubierto</h6>";};?>
																		
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="boton fancybox" href="<?=base_url()?>edit_medi/<?=$u->idtratamiento?>/<?=$o->idsiniestrodiagnostico?>" title="Editar Medicamento" data-fancybox-width="950" data-fancybox-height="490"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></a>
																		</div>
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a class="delete" data-confirm="¿Está seguro que desea eliminar este medicamento?" href="<?=base_url()?>delete_trata/<?=$u->idtratamiento?>/<?=$idsiniestro?>" title="Eliminar Medicamento"><i class="ace-icon fa fa-ban bigger-120"></i></a>
																		</div>
																	</td>
																</tr>
																<?php }
																endforeach; 
																} endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table -->
													</div>
												</div>
											</div>

											<div class="panel panel-default">
												<div class="panel-heading">
														<a href="#faq-2-2" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Secundario
														</a>
												</div>

											<div class="panel-collapse collapse" id="faq-2-2">
												<div class="panel-body">
													Medicamentos secundarios

													<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th>Diagnostico</th>
																	<th>Tipo</th>
																	
																	<th width="10%" colspan="3">
																		<div title="Nuevo Diagnóstico" style="float:right;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																				<a class="boton fancybox" href="<?=  base_url()?>add_diagnosticoSec/<?=$idsiniestro?>" data-fancybox-width="950" data-fancybox-height="490">
																					Nuevo Diagnóstico
																				</a>
																			</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($diagnostico as $o):
																
																if($o->tipo_diagnostico==3){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td style="font-weight: bold;"><?=$o->dianostico_temp;?></td>
																	<td></td>

																	<td>
																		<div class="hidden-sm hidden-xs btn-group">	
																				<a href="<?=base_url()?>siniestro/<?=$o->idsiniestro?>" title="Editar Diagnóstico"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></a>
																		</div>	
																	</td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">	
																				<a class="boton fancybox" href="<?=base_url()?>add_tratamientoSec/<?=$o->idsiniestrodiagnostico?>" data-fancybox-width="950" data-fancybox-height="490" title="Nuevo Medicamento"><i class="ace-icon fa fa-certificate bigger-120"></i></a>
																		</div>	
																	</td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$o->idsiniestro?>" title="Eliminar Diagnostico"><i class="ace-icon fa fa-ban bigger-120"></i></a>
																		</div>	
																	</td>
																</tr>

																<?php
																foreach($medicamento as $u):
																
																if($u->idsiniestrodiagnostico==$o->idsiniestrodiagnostico and $u->tipo_tratamiento){ 
																?>
																<tr>
																	<td></td>
																	<td><?=$u->medicamento_temp.' -- Cantidad: '.$u->cantidad_trat.' -- Dosis: '.$u->dosis_trat;?>
																		
																	</td>
																	<td><?php if ($u->tipo_tratamiento==3){echo "<h6 style='color:red;'> No cubierto</h6>";}else{echo "<h6 style='color:green;'>Cubierto</h6>";};?>
																		
																	</td>
																	<td></td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$o->idsiniestro?>" title="Editar Medicamento"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></a>
																		</div>
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$o->idsiniestro?>" title="Eliminar Medicamento"><i class="ace-icon fa fa-ban bigger-120"></i></a>
																		</div>
																	</td>
																</tr>
																<?php }
																endforeach; 
																} endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table -->													
												</div>
											</div>
											</div>			
											</div>
										</div>

										<div id="faq-tab-3" class="tab-pane fade">
											<h4 class="blue">
												<i class="orange ace-icon fa fa-flask bigger-110"></i>
												Exámenes y Laboratorios
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-3" class="panel-group accordion-style1 accordion-style2">
												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-3-1" data-parent="#faq-list-3" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Exámenes y Laboratorios Cubiertos
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-3-1">
														<div class="panel-body">
															
															<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th colspan="4">Laboratorio</th>
																	<th>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$idsiniestro?>" title="Nuevo Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($laboratorio as $i):
																
																if($i->si_cubre==1){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td colspan="3" style="font-weight: bold;"><?=$i->analisis_str;?></td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$i->idsiniestro?>" title="Editar Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>	
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$i->idsiniestro?>" title="Eliminar Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>	
																	</td>
																	
																</tr>
																
																<?php } endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table --> 

														</div>
													</div>
												</div>

												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-3-2" data-parent="#faq-list-3" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Exámenes y Laboratorios no Cubiertos
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-3-2">
														<div class="panel-body">
															<!-- star table -->		
													<div class="col-xs-12">
														<table id="simple-table" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Nro</th>
																	<th colspan="4">Laboratorio</th>
																	<th>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$idsiniestro?>" title="Nuevo Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php $nro_fila = 0;	
																foreach($laboratorio as $i):
																
																if($i->si_cubre==3){ 
																$nro_fila =$nro_fila+1;?>

																<tr>
																	<td><?php echo $nro_fila; ?></td>
																	<td colspan="3" style="font-weight: bold;"><?=$i->analisis_str;?></td>
																	
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$i->idsiniestro?>" title="Editar Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>	
																	</td>
																	<td>
																		<div class="hidden-sm hidden-xs btn-group">
																				<a href="<?=base_url()?>siniestro/<?=$i->idsiniestro?>" title="Eliminar Laboratorio"><i class="ace-icon fa fa-external-link bigger-120"></i></a>
																		</div>	
																	</td>
																	
																</tr>
																
																<?php } endforeach; ?>
															</tbody>
														</table>
													</div>
													<!-- end table --> 

														</div>
													</div>
												</div>

											</div>
										</div>

										<div id="faq-tab-4" class="tab-pane fade">
											<h4 class="blue">
												<i class="purple ace-icon fa fa-check-square-o bigger-110"></i>
												Reconsulta
											</h4>

											<div class="space-8"></div>

											<div id="faq-list-4" class="panel-group accordion-style1 accordion-style2">
												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-4-1" data-parent="#faq-list-4" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Presuntivo
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-4-1">
														<div class="panel-body">
															



														</div>
													</div>
												</div>

												<div class="panel panel-default">
													<div class="panel-heading">
														<a href="#faq-4-2" data-parent="#faq-list-4" data-toggle="collapse" class="accordion-toggle collapsed">
															<i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
															Diagnóstico Definitivo
														</a>
													</div>

													<div class="panel-collapse collapse" id="faq-4-2">
														<div class="panel-body">

														</div>
													</div>
												</div>												
											</div>
										</div>

										<div id="faq-tab-5" class="tab-pane fade">
											<h4 class="blue">
												<i class="purple ace-icon fa fa-check-square-o bigger-110"></i>
												Registro de Gastos Médicos
											</h4>

											<div class="space-8"></div>

											<button onclick="myFunction()" class="reserve-button">Detalle del Plan</button>


											<table class="grilla" id="people">
<thead>
<th>Plan</th>
<th>IdVar</th>
<th>NomVar</th>
<th>Valor</th>
<th>Text</th>
</thead>
<tbody></tbody>
</table>







										<form action="<?=base_url()?>guardaGasto" method="post">

											<div class="row">
											  <div class="col-sm-4">
											  	<div class="checkbox">
												 <label><input type="checkbox" id="trigger" name="question" value="si"> El mismo proveedor factura todo el siniestro.</label>
												 <input type="hidden" id= "presscheck" name= "presscheck" value="1">

												</div>
											  </div>
											  <div class="col-sm-4">
												<div class="form-group" id="hidden_fields1">
													<b class="text-primary">Proveedor:</b>
													<select name="proveedorPrin" class="form-control" id= "proveedorPrin" >
										          		<option>--- Seleccionar Proveedor ---</option>
														<?php if (count($proveedor)) {
															foreach ($proveedor as $u) {
																echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																}
														}?>
													</select>
												</div>
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group" id="hidden_fields2">
													<b class="text-primary">Ingrese Nº de Factura:</b>
													<input type="text" class="form-control" value="" id="numFact" name="numFact" placeholder="000-000000">
												</div>	
											  </div>											  
											</div>



											<div class="table-responsive">
											    <table id = "tblGasto" class="table table-bordered table-striped table-highlight">
											      <thead>
											        <th>Detalle</th>
											        <th>Costo Bruto</th>
											        <th>Proveedor</th>
											        <th>Monto Neto a pagar</th>
											        <th>Nº Factura</th>
											        <th>Aprobar Pago</th>
											      </thead>
											      <tbody>
											        <tr>
											          <td><b class="text-primary">Monto por Atención Médica</b> <input type="hidden" id= "idplan" name= "idplan" value="<?php echo $plan_id;?>"></td>
											          <input type="hidden" id= "espe" name= "espe" value="<?php echo $idespecialidad;?>"></td>
											          <td><div class="input-group">
											              <span class="input-group-addon">S/.</span>
											              <input type="number" id= "monto1" name= "monto1" placeholder="0,00" step="0.01" class="txtCal item1 form-control">
											            </div></td>          
											          <td>            
											          	<select name="proveedor1" class="form-control" id= "proveedor1" >
											          		<option value=0>--- Seleccionar Proveedor ---</option>
															<?php if (count($proveedor)) {
																foreach ($proveedor as $u) {
																	echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																	}
															}?>
														</select>            
											          </td>
											          <td><div id="sumaNeto" class="input-group">
											          	<span id= "netospan1" class="input-group-addon">S/.</span>
											          	<input type="text" id= "neto1" name= "neto1" class="form-control txtCal2" placeholder="0,00"/></div>
											          </td>
											          <td><input type="text" id= "factura1" name= "factura1" class="form-control" placeholder="000-000000"/></td>
											          <td><input type="hidden" name="pago1" value="0" /><input type="checkbox" id="pago1" name="pago1" value="1"></td>          
											        </tr>

											        <tr>
											          <td><b class="text-primary">Monto por Medicamentos</b></td>
											          <td><div class="input-group">
											              <span class="input-group-addon">S/.</span>
											              <input type="number" id= "monto2" name= "monto2" placeholder="0,00" step="0.01" class="txtCal item2 form-control">
											            </div></td>          
											          <td>            
											          	<select name="proveedor2" class="form-control" id= "proveedor2" >
											          		<option value=0>--- Seleccionar Proveedor ---</option>
															<?php if (count($proveedor)) {
																foreach ($proveedor as $u) {
																	echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																	}
															}?>
														</select>            
											          </td>
											          <td><div class="input-group">
											          	<span class="input-group-addon">S/.</span>
											          	<input type="text" id= "neto2" name= "neto2" class="form-control txtCal2" placeholder="0,00"/></div>
											          </td>
											          <td><input type="text" id= "factura2" name= "factura2" class="form-control" placeholder="000-000000"/></td>
											          <td><input type="hidden" name="pago2" value="0" /><input type="checkbox" id="pago2" name="pago2" value="1"></td>          
											        </tr>

											        <tr>
											          <td><b class="text-primary">Monto Exámenes de Laboratorio</b></td>
											          <td><div class="input-group">
											              <span class="input-group-addon">S/.</span>
											              <input type="number" id= "monto3" name= "monto3" placeholder="0,00" step="0.01" class="txtCal item3 form-control">
											            </div></td>          
											          <td>            
											          	<select name="proveedor3" class="form-control" id= "proveedor3" >
											          		<option value=0>--- Seleccionar Proveedor ---</option>
															<?php if (count($proveedor)) {
																foreach ($proveedor as $u) {
																	echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																	}
															}?>
														</select>            
											          </td>
											          <td><div class="input-group">
											          	<span class="input-group-addon">S/.</span>
											          	<input type="text" id= "neto3" name= "neto3" class="form-control txtCal2" placeholder="0,00"/></div>
											          </td>
											          <td><input type="text" id= "factura3" name= "factura3" class="form-control" placeholder="000-000000"/></td>
											          <td><input type="hidden" name="pago3" value="0" /><input type="checkbox" id="pago3" name="pago3" value="1"></td>          
											        </tr>


											        <tr>
											          <td><b class="text-primary">Monto Imagenología</b></td>
											          <td><div class="input-group">
											              <span class="input-group-addon">S/.</span>
											              <input type="number" id= "monto4" name= "monto4" placeholder="0,00" step="0.01" class="txtCal item4 form-control">
											            </div></td>          
											          <td>            
											          	<select name="proveedor4" class="form-control" id= "proveedor4" >
											          		<option value=0>--- Seleccionar Proveedor ---</option>
															<?php if (count($proveedor)) {
																foreach ($proveedor as $u) {
																	echo "<option value='". $u['idproveedor']."'>" . $u['nombre_comercial_pr']."</option>";	
																	}
															}?>
														</select>            
											          </td>
											          <td><div class="input-group">
											          	<span class="input-group-addon">S/.</span>
											          	<input type="text" id= "neto4" name= "neto4" class="form-control txtCal2" placeholder="0,00"/></div>
											          </td>
											          <td><input type="text" id= "factura4" name= "factura4" class="form-control" placeholder="000-000000"/></td>
											          <td><input type="hidden" name="pago4" value="0" /><input type="checkbox" id="pago4" name="pago4" value="1"></td>          
											        </tr>

											        <tr>
													    <td align="right"><span><b>TOTAL  :</b></span></td>
													    <td align="right"><b>S/. <span id="total_sum_value"></span></b></td>
													    <input type="hidden" class="form-control" name="total" id="total">
													    <td align="right"><span><b>TOTAL NETO REDSALUD  :</b></span></td>
													    <td align="right"><b>S/. <span id="total_sum_value_neto"></span></b></td>
													    <input type="hidden" class="form-control" name="total_neto" id="total_neto">
													</tr>

											      </tbody>
											    </table>
											</div>

											<div class="form-group" id="hidden_fields3">
													<input type="hidden" name="pago0" value="0" /><input type="checkbox" id="pago0" name="pago0" value="1">
													<b class="text-primary">Aprobar pago al proveedor:</b>
											</div>

											<fieldset style="padding-top: 25px;">
												<input type="hidden" class="form-control" name="idsiniestro" id="idsiniestro" value="<?php echo $idsiniestro?>">
												
												<div class="row">
												  <div class="col-sm-6">
												  	<input class="btn btn-primary" name="enviar" type="submit" value="Guardar">
												  </div>
												  

												</div>
											</fieldset>


										</form>
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


			//para borrar

			var deleteLinks = document.querySelectorAll('.delete');

			for (var i = 0; i < deleteLinks.length; i++) {
			  deleteLinks[i].addEventListener('click', function(event) {
			      event.preventDefault();

			      var choice = confirm(this.getAttribute('data-confirm'));

			      if (choice) {
			        window.location.href = this.getAttribute('href');
			      }
			  });

			}
		</script>


		<script>		
			function myFunction() {
		    
		        var idsiniestro = $('#idsiniestro').val();
		        $.ajax({
		            type:'POST',
		            url:'<?=base_url()?>public/population/getEmployeeId.php',
		            dataType: "json",
		            data:{idsiniestro:idsiniestro},
		            success:function(someJSON){
		                var peopleHTML = "";

      // Loop through Object and create peopleHTML
      for (var key in someJSON) {
        if (someJSON.hasOwnProperty(key)) {
          peopleHTML += "<tr>";
            peopleHTML += "<td>" + someJSON[key]["nombre_plan"] + "</td>";
            peopleHTML += "<td>" + someJSON[key]["idvariableplan"] + "</td>";
            peopleHTML += "<td>" + someJSON[key]["nombre_var"] + "</td>";
            peopleHTML += "<td>" + someJSON[key]["valor_detalle"] + "</td>";
            peopleHTML += "<td>" + someJSON[key]["texto_web"] + "</td>";            
          peopleHTML += "</tr>";
        }
      }
// Replace table’s tbody html with peopleHTML
      $("#people tbody").html(peopleHTML);

		            }
		        });		              
		        
			
		};
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
