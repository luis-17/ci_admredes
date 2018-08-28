<?php ini_set('date.timezone','America/Lima');
$hora_ini = date("h:i");
$hora_fin = date("h:i", strtotime($hora_ini."+30 minute"));?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

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
								<a href="<?=base_url()?>index.php/index">Inicio</a>
							</li>
							<?php foreach ($certificado as $c): ?>								
							<li>
								<a href="<?=base_url()?>index.php/certificado2/<?=$doc?>">Certificado</a>
							</li>
							<li>
								<a href="<?=base_url()?>index.php/certificado_detalle/<?=$c->cert_id;?>/<?=$doc?>">Detalle Certificado</a>
							</li>							
							<?php endforeach ?>
							<li class="active">Calendario de Citas</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<div class="alert alert-info hidden-sm hidden-xs" style="display: <?=$estilo_mensaje?>">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>									
									<span class="blue bolder"><?=$mensaje;?></span>
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
								Calendario de Citas
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Gestión de atenciones
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-sm-9">										
										<div class="space"></div>

										<!-- #section:plugins/data-time.calendar -->
										<div id="calendar"></div>

										<!-- /section:plugins/data-time.calendar -->
									</div>

									<div class="col-sm-3">
										<div class="widget-box transparent">
										<?php foreach ($asegurado as $as):
										$aseg_id=$as->aseg_id;
										$certase_id=$as->certase_id;
										$asegurado=$as->asegurado;
										$ultima_atencion=$as->ultima_atencion;
										$ultima_atencion=date("d/m/Y", strtotime($ultima_atencion));
										$dis=$as->distrito;
										$pro=$as->provincia;
										$dep=$as->departamento;
										$ubigeo=$dis.'-'.$pro.'-'.$dep;
										if($as->aseg_sexo=='F'):
											$genero='Femenino';
											else:
												$genero='Masculino';
										endif;
										?>
											<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name"> Asegurado: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="username"><?=$asegurado;?></span>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> DNI: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="username"><?=$as->aseg_numDoc;?></span>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Plan: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="username"><?=$as->nombre_plan;?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Últ.Atención: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="age"><?=$ultima_atencion;?></span>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Edad: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="age"><?=$as->edad;?> años</span>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Sexo: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="age"><?=$genero;?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Dirección: </div>

													<div class="profile-info-value">
														<i class="fa fa-map-marker light-orange bigger-110"></i>
														<span class="editable editable-click" id="country"><?=$as->aseg_direcc;?></span>
														<span class="editable editable-click" id="city"><?=$ubigeo;?></span>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Teléfono: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="age"><?=$as->aseg_telf;?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Correo: </div>

													<div class="profile-info-value">
														<span class="editable editable-click" id="signup"><?=$as->aseg_email;?></span>
													</div>
												</div>
											</div>
											<div class="widget-header">
												<h4>Leyenda:</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main no-padding">
													<div id="external-events">
														<div class="external-event label-grey" data-class="label-grey">
														<i class="ace-icon fa fa-arrows"></i>
															Cita Reservada
														</div>

														<div class="external-event label-yellow" data-class="label-yellow">
														<i class="ace-icon fa fa-arrows"></i>
															Cita Confirmada
														</div>

														<div class="external-event label-success" data-class="label-success">
														<i class="ace-icon fa fa-arrows"></i>
															Cita Atendida
														</div>

														<div class="external-event label-danger" data-class="label-danger">
														<i class="ace-icon fa fa-arrows"></i>
															Cita Vencida
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach;?>
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
		<script src="<?=  base_url()?>public/assets/js/jquery-ui.custom.js"></script>
		<script src="<?=  base_url()?>public/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="<?=  base_url()?>public/assets/js/date-time/moment.js"></script>
		<script src="<?=  base_url()?>public/assets/js/fullcalendar.js"></script>
		<script src="<?=  base_url()?>public/assets/js/bootbox.js"></script>

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
		<script type="text/javascript">
			jQuery(function($) {

/* initialize the external events
	-----------------------------------------------------------------*/

	$('#external-events div.external-event').each(function() {

		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};

		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);

		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});
		
	});




	/* initialize the calendar
	-----------------------------------------------------------------*/

	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();


	var calendar = $('#calendar').fullCalendar({

		//isRTL: true,
		 buttonHtml: {
			prev: '<i class="ace-icon fa fa-chevron-left"></i>',
			next: '<i class="ace-icon fa fa-chevron-right"></i>'
		},
	
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},	


		//mostrar eventos
		events: [
			<?php foreach ($citas as $c):
			switch ($c->estado_cita):
				case 0:
					$class='label-danger';
					break;
				case 1:
					$class='label-grey';
					break;
				case 2:
					$class='label-yellow';
					break;
				case 3:
					$class='label-success';
					break;
			endswitch;
			
			?>
			
		  {
			title: '<?=$c->asegurado;?>',
			start: new Date('<?=$c->anio;?>','<?=$c->mes;?>','<?=$c->dia;?>'),
			className: '<?=$class;?>',
			idCita: '<?=$c->idcita;?>',
			fechaCita: '<?=$c->fecha_cita;?>',
			horaIniCita: '<?=$c->hora_cita_inicio;?>',
			horaFinCita: '<?=$c->hora_cita_fin;?>',
			idAseg: '<?=$c->idasegurado;?>',
			idProv:'<?=$c->idproveedor;?>',
			idEspe:'<?=$c->idespecialidad;?>',
			obs: '<?=$c->observaciones_cita;?>'	
		  },

		  //var proveedor1 = calEvent.idProv;
		  
		  <?php endforeach; ?>
		],
		//terminar de mostrar eventos


	

		
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay) { // this function is called when something is dropped
		
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			var $extraEventClass = $(this).attr('data-class');
			
			
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			if($extraEventClass) copiedEventObject['className'] = [$extraEventClass];
			
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			
			// is the "remove after drop" checkbox checked?
			if ($('#drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}
		}
		,
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			starttime = $.fullCalendar.moment(start).format('YYYY-MM-DD');
			
			
			bootbox.confirm("<div class='page-header'><h1><?=$asegurado;?></h1></div>\
			<form id='infos' name='infos' method= 'POST' action='<?=base_url()?>calendario_guardar'>\
		    <input type='hidden' name='aseg_id' id='aseg_id' value='<?=$aseg_id;?>'/>\
		    <input type='hidden' name='doc' id='doc' value='<?=$doc?>' />\
		    <input type='hidden' name='certase_id' id='certase_id' value='<?=$certase_id;?>'/>\
		    	<div class='form-row'>\
				    <div class='form-group col-md-12'>\
					    <label>Proveedor:</label>\
					    <select name='proveedor' id='proveedor' required>\
					    	<option value=''>Seleccione</option>\
						    <?php foreach ($proveedores as $pr):?>\
						    	<option value='<?=$pr->idproveedor;?>'><?=$pr->nombre_comercial_pr;?></option>\
						    <?php endforeach; ?>\
					    </select>\
				    </div>\
				</div>\
				<div class='form-row'>\
				    <div class='form-group col-md-6'>\
					    <label>Servicio:</label>\
					    <select name='producto' id='producto' required class='col-xs-16'>\
					    	<option value=''>Seleccione</option>\
						    <?php foreach ($productos as $p):?>\
						    	<option value='<?=$p->idespecialidad;?>'><?=$p->descripcion_prod;?></option>\
						    <?php endforeach; ?>\
					    </select>\
				    </div>\
				    <div class='form-group col-md-4'>\
				    	 <label>Estado:</label>\
					    <select name='estado' id='estado' required>\
					    	<option value='1'>Cita Reservada</option>\
					    	<option value='2'>Cita Confirmada</option>\
					    </select>\
				    </div>\
				</div>\
				<div class='form-row'>\
				    <div class='form-group col-md-4'>\
					    <label>Fecha:</label>\
					    <input type='text' name='feccita' id='feccita' value='" + starttime + "'  />\
				    </div>\
				    <div class='form-group col-md-3'>\
					    <label>Hora Inicio:</label>\
					    <input class='form-control input-mask-date' type='time' name='inicio' id='inicio' value='<?=$hora_ini;?>'/>\
				    </div>\
				    <div class='form-group col-md-3'>\
					    <label>Hora Fin:</label>\
					    <input class='form-control input-mask-date' type='time' name='fin' id='fin' value='<?=$hora_fin;?>' />\
				    </div>\
				</div>\
				<div class='form-row'>\
				    <div class='form-group col-md-8'>\
					    <label>Observaciones:</label>\
					    <textarea rows='2' cols='71' name='obs' id='obs'></textarea>\
				    </div>\
				</div>\
		    </form>", function(result) {
		        if(result)
		            $('#infos').submit();
});
			

			calendar.fullCalendar('unselect');
		}
		,
		eventClick: function(calEvent, jsEvent, view) {

			//display a modal
			//$('#description').value + calEvent.title;
			//alert('Event: ' + calEvent.title);
			var idcita = calEvent.idCita;
			var nombre = calEvent.title;
			var fecha = calEvent.fechaCita;
			var asegurado = calEvent.idAseg;
			var proveedor = calEvent.idProv;
			var hInicio = calEvent.horaIniCita;
			var hFin = calEvent.horaFinCita;
			var espe = calEvent.idEspe;
			var observacion = calEvent.obs;
			//alert(saludo);
			
			<?php $idprove="'+proveedor +'";
			//$final="<script>document.write(proveedor)</script>";
			//$final=print $idprove;
			?>
			

			var modal = 
			'<div class="modal fade">\
			  <div class="modal-dialog">\
			   <div class="modal-content">\
				 <div class="modal-body" style="height: 533px;">\
				   <button type="button" class="close" data-dismiss="modal" style="margin-top:-10px;">&times;</button>\
				   <div class="tabbable">\
						<!-- #section:pages/faq -->\
						<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">\
							<li class="active">\
								<a data-toggle="tab" href="#faq-tab-1">\
									Editar\
								</a>\
							</li>\
							<li>\
								<a data-toggle="tab" href="#faq-tab-2">\
									Seguimiento\
								</a>\
							</li>\
						</ul>\
						<!-- /section:pages/faq -->\
						<div class="tab-content no-border padding-24">\
							<div id="faq-tab-1" class="tab-pane fade in active">\
								<form class="no-margin">\
									<div class="page-header"><h1>'+nombre + '</h1></div>\
								<input type="text" name="idcita" id="idcita" value='+idcita + ' />\
									<div class="form-row">\
										<div class="form-group col-md-12">\
											<label>Proveedor:</label>\
											<select name="proveedor" id="proveedor" required>\
											<option value=" ">Seleccione</option>\
											<?php foreach ($proveedores as $pr){ if ($pr->idproveedor== $idprove) {?>\
											    	<option value="<?=$pr->idproveedor;?>" selected><?=$pr->nombre_comercial_pr;?></option>\
											    <?php }else { ?> <option value="<?=$pr->idproveedor;?>"><?=$pr->nombre_comercial_pr;?></option>\
											    <?php } }; ?>\
										    </select>\
										</div>\
									</div>\
									<div class="form-row">\
								    <div class="form-group col-md-6">\
									    <label>Servicio:</label>\
									    <select name="producto" id="producto" required class="col-xs-16">\
									    	<option value="">Seleccione</option>\
										    <?php foreach ($productos as $p):?>\
										    	<option value="<?=$p->idespecialidad;?>"><?=$p->descripcion_prod;?></option>\
										    <?php endforeach; ?>\
									    </select>\
								    </div>\
								    <div class="form-group col-md-4">\
								    	 <label>Estado:</label>\
									    <select name="estado" id="estado" required>\
									    	<option value="1">Cita Reservada</option>\
									    	<option value="2">Cita Confirmada</option>\
									    </select>\
								    </div>\
								</div>\
								<div class="form-row">\
								    <div class="form-group col-md-4">\
									    <label>fecha:</label>\
									    <input type="text" name="fecha" id="fecha" value='+fecha + ' /> \
								    </div>\
								    <div class="form-group col-md-3">\
									    <label>Hora Inicio:</label>\
									    <input class="form-control input-mask-date" type="time" name="inicio" id="inicio" value='+hInicio + ' /> \
								    </div>\
								    <div class="form-group col-md-3">\
									    <label>Hora Fin:</label>\
									    <input class="form-control" type="time" name="fin" id="fin" varlue='+hFin + ' /> \
								    </div>\
								</div>\
								<div class="form-row">\
								    <div class="form-group col-md-8">\
									    <label>Observaciones:</label>\
									    <textarea rows="2" cols="71" name="obs" id="obs"><?php echo $idprove; ?></textarea>\
								    </div>\
								</div>\
								<div class="form-row">\
								<div class="form-group col-md-8">\
									<button type="submit" class="btn btn-sm btn-success">\
										<i class="ace-icon fa fa-check"></i> Guardar\
									</button>\
									<button type="button" class="btn btn-sm btn-danger" data-action="delete">\
										<i class="ace-icon fa fa-trash-o"></i> Eliminar\
									</button>\
								</div>\
								</div>\
								</form>\
							</div>\
							<div id="faq-tab-2" class="tab-pane fade">\
								<form class="no-margin">\
								<div class="page-header"><h1><?=$asegurado;?></h1></div>\
									<div class="widget-body">\
										<div class="widget-main padding-8">\
											<div id="profile-feed-1" class="profile-feed ace-scroll" style="position: relative;"><div class="scroll-track scroll-active" style="display: block; height: 200px;"><div class="scroll-bar" style="height: 63px; top: 0px;"></div></div><div class="scroll-content" style="max-height: 200px;">\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																9 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																8 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																7 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																6 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																5 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																4 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																3 hour ago\
														</div>\
													</div>\
												</div>\
												<div class="profile-activity clearfix">\
													<div>\
														<a class="user" href="#"> Alex Doe </a>\
																	changed his profile photo. \
														<div class="time">\
															<i class="ace-icon fa fa-clock-o bigger-110"></i>\
																2 hour ago\
														</div>\
													</div>\
												</div>\
											</div>\
										</div>\
									</div>\
								</form>\
							</div>\
						</div>\
				   </div>\
			  </div>\
			 </div>\
			</div>';
		
		
			var modal = $(modal).appendTo('body');
			modal.find('form').on('submit', function(ev){
				ev.preventDefault();

				calEvent.title = $(this).find("input[type=text]").val();
				calendar.fullCalendar('updateEvent', calEvent);
				modal.modal("hide");
			});
			modal.find('button[data-action=delete]').on('click', function() {
				calendar.fullCalendar('removeEvents' , function(ev){
					return (ev._id == calEvent._id);
				})
				modal.modal("hide");
			});
			
			modal.modal('show').on('hidden', function(){
				modal.remove();
			});


			//console.log(calEvent.id);
			//console.log(jsEvent);
			//console.log(view);

			// change the border color just for fun
			//$(this).css('border-color', 'red');

		}
		
	});


})
		</script>
		<script type="text/javascript">
			function hora(){
				var hora_ini= document.infos.inicio.value;
			}
		</script>

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